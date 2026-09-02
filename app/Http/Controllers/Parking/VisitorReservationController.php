<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\parking\VisitorReservation;
use App\Models\parking\ParkingSlot;
use Illuminate\Support\Facades\Auth;

class VisitorReservationController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->is_hams_admin) {
            abort(403, 'สงวนสิทธิ์การเข้าถึงสำหรับผู้ดูแลระบบเท่านั้น');
        }

        // Auto-clear old visitor reservations (if checkin_datetime is before today and not locked)
        $pastReservations = VisitorReservation::whereDate('checkin_datetime', '<', \Carbon\Carbon::today())
            ->whereIn('status', ['reserved', 'checked_in'])
            ->where('is_locked', false)
            ->get();
            
        foreach ($pastReservations as $res) {
            $res->status = 'checked_out';
            if (!$res->checkout_datetime) {
                $res->checkout_datetime = $res->checkin_datetime ? \Carbon\Carbon::parse($res->checkin_datetime)->endOfDay() : \Carbon\Carbon::now();
            }
            $res->save();
            
            if ($res->slot) {
                $res->slot->status = 'available';
                $res->slot->save();
            }
        }

        $reservations = VisitorReservation::with('contactUser', 'slot')
                        ->orderBy('checkin_datetime', 'desc')
                        ->get();
        return view('parking.visitor.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        if (!Auth::check()) {
            return $this->guestCreate($request);
        }
        
        $selectedSlotId = null;
        if ($request->query('slot')) {
            $slotCode = $request->query('slot');
            $building = str_starts_with($slotCode, 'B') ? 'ในอาคาร' : 'ลานจอดรถหลัก';
            
            $zone = \App\Models\parking\ParkingZone::where('building', $building)->first();
            if (!$zone) {
                $zone = \App\Models\parking\ParkingZone::create([
                    'building' => $building,
                    'zone' => str_starts_with($slotCode, 'B') ? 'B' : 'A',
                    'floor' => str_starts_with($slotCode, 'B') ? 'ชั้น 1' : 'กลางแจ้ง',
                ]);
            }
            
            $slot = ParkingSlot::firstOrCreate([
                'zone_id' => $zone->id,
                'slot_number' => $slotCode,
            ]);
            $selectedSlotId = $slot->id;
        }

        $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
        $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $activeEmpReservations = \App\Models\parking\EmployeeReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $occupiedSlotIds = array_unique(array_merge($activeEmployeeSlotIds, $activeVisitorSlotIds, $activeEmpReservations));
        $availableSlots = ParkingSlot::whereNotIn('id', $occupiedSlotIds)
            ->whereHas('zone', function($q) {
                $q->where('building', 'ในอาคาร');
            })
            ->get();
        if ($selectedSlotId && !$availableSlots->contains('id', $selectedSlotId)) {
            $selSlot = ParkingSlot::find($selectedSlotId);
            if ($selSlot) {
                $availableSlots->prepend($selSlot);
            }
        }

        $slots = ParkingSlot::with([
            'employeeParkings' => function($q) {
                $q->where('status', 'parking')->with('user');
            },
            'visitorReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in']);
            }
        ])->get()->keyBy('slot_number');

        return view('parking.visitor.create', compact('availableSlots', 'selectedSlotId', 'slots'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return $this->guestStore($request);
        }
        
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'car_registration' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_details' => 'nullable|string|max:1000',
            'checkin_datetime' => 'required|date',
            'duration_hours' => 'nullable|integer|min:1',
        ]);

        $reservation = new VisitorReservation();
        $reservation->guest_name = $request->guest_name;
        $reservation->company = $request->company;
        $reservation->phone = $request->phone;
        $reservation->car_registration = $request->car_registration;
        $reservation->contact_user_id = Auth::id() ?? 1; // Fallback to 1 if not logged in
        $reservation->contact_details = $request->contact_details;
        $reservation->checkin_datetime = $request->checkin_datetime;
        $reservation->duration_hours = $request->duration_hours;
        
        if ($request->duration_hours) {
            $reservation->checkout_datetime = \Carbon\Carbon::parse($request->checkin_datetime)->addHours((int)$request->duration_hours);
        }

        if ($request->slot_id) {
            $reservation->slot_id = $request->slot_id;
            // Optionally, mark slot as reserved
            $slot = ParkingSlot::find($request->slot_id);
            if($slot) {
                $slot->status = 'reserved';
                $slot->save();
            }
        } elseif ($request->slot_number_manual) {
            // Slot selected from map but not in dropdown
            $manualSlot = ParkingSlot::where('slot_number', $request->slot_number_manual)->first();
            if (!$manualSlot) {
                // Create slot if it doesn't exist
                $zone = \App\Models\parking\ParkingZone::where('building', 'ในอาคาร')->first();
                $manualSlot = ParkingSlot::create([
                    'zone_id'     => $zone ? $zone->id : null,
                    'slot_number' => $request->slot_number_manual,
                    'status'      => 'available',
                ]);
            }
            if ($manualSlot) {
                $reservation->slot_id = $manualSlot->id;
                $manualSlot->status = 'reserved';
                $manualSlot->save();
            }
        } else {
            // Automatically assign the first available slot in the indoor building zone ("ในอาคาร")
            $buildingZone = \App\Models\parking\ParkingZone::where('building', 'ในอาคาร')->first();
            $autoSlot = null;
            if ($buildingZone) {
                // Prioritize Bay 19 (B19_1, B19_2, B19_3) which is designated for visitors
                $autoSlot = ParkingSlot::where('zone_id', $buildingZone->id)
                    ->where('status', 'available')
                    ->orderByRaw("CASE WHEN slot_number LIKE 'B19_%' THEN 0 ELSE 1 END")
                    ->orderBy('slot_number')
                    ->first();
            }
            if (!$autoSlot) {
                $autoSlot = ParkingSlot::where('status', 'available')->first();
            }
            if ($autoSlot) {
                $reservation->slot_id = $autoSlot->id;
                $autoSlot->status = 'reserved';
                $autoSlot->save();
            }
        }
        
        $reservation->status = 'reserved';
        $reservation->save();

        return redirect()->route('parking.visitors.approvals')->with('success', 'บันทึกข้อมูลการจองเรียบร้อยแล้ว รอการตรวจสอบสถานะ');
    }

    public function checkIn($id)
    {
        $reservation = VisitorReservation::findOrFail($id);
        $reservation->status = 'checked_in';
        $reservation->save();

        if ($reservation->slot) {
            $reservation->slot->status = 'occupied';
            $reservation->slot->save();
        }

        return redirect()->back()->with('success', 'บันทึกการเข้าพื้นที่เรียบร้อยแล้ว');
    }

    public function checkout($id)
    {
        $reservation = VisitorReservation::findOrFail($id);
        $reservation->status = 'checked_out';
        $reservation->checkout_datetime = \Carbon\Carbon::now();
        $reservation->save();

        if ($reservation->slot) {
            $reservation->slot->status = 'available';
            $reservation->slot->save();
        }

        return redirect()->back()->with('success', 'บันทึกการออกจากพื้นที่เรียบร้อยแล้ว');
    }

    public function cancel($id)
    {
        $reservation = VisitorReservation::findOrFail($id);
        $reservation->status = 'cancelled';
        $reservation->save();

        if ($reservation->slot) {
            $reservation->slot->status = 'available';
            $reservation->slot->save();
        }

        return redirect()->back()->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }

    public function toggleLock($id)
    {
        $reservation = VisitorReservation::findOrFail($id);
        $reservation->is_locked = !$reservation->is_locked;
        $reservation->save();

        $status = $reservation->is_locked ? 'ล็อก' : 'ปลดล็อก';
        return redirect()->back()->with('success', "{$status}ที่จอดรถเรียบร้อยแล้ว");
    }

    public function guestCreate(Request $request)
    {
        $selectedSlotId = null;
        if ($request->query('slot')) {
            $slotCode = $request->query('slot');
            $building = str_starts_with($slotCode, 'B') ? 'ในอาคาร' : 'ลานจอดรถหลัก';
            
            $zone = \App\Models\parking\ParkingZone::where('building', $building)->first();
            if (!$zone) {
                $zone = \App\Models\parking\ParkingZone::create([
                    'building' => $building,
                    'zone' => str_starts_with($slotCode, 'B') ? 'B' : 'A',
                    'floor' => str_starts_with($slotCode, 'B') ? 'ชั้น 1' : 'กลางแจ้ง',
                ]);
            }
            
            $slot = ParkingSlot::firstOrCreate([
                'zone_id' => $zone->id,
                'slot_number' => $slotCode,
            ]);
            $selectedSlotId = $slot->id;
        }

        $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
        $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $activeEmpReservations = \App\Models\parking\EmployeeReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $occupiedSlotIds = array_unique(array_merge($activeEmployeeSlotIds, $activeVisitorSlotIds, $activeEmpReservations));
        $availableSlots = ParkingSlot::whereNotIn('id', $occupiedSlotIds)
            ->whereHas('zone', function($q) {
                $q->where('building', 'ในอาคาร');
            })
            ->get();
        if ($selectedSlotId && !$availableSlots->contains('id', $selectedSlotId)) {
            $selSlot = ParkingSlot::find($selectedSlotId);
            if ($selSlot) {
                $availableSlots->prepend($selSlot);
            }
        }

        // Get all staff users so the guest can choose who they are contacting
        $staffUsers = \App\Models\User::orderBy('firstname')->get();

        $slots = ParkingSlot::with([
            'employeeParkings' => function($q) {
                $q->where('status', 'parking')->with('user');
            },
            'visitorReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in']);
            }
        ])->get()->keyBy('slot_number');

        return view('parking.visitor.guest_create', compact('availableSlots', 'selectedSlotId', 'staffUsers', 'slots'));
    }

    public function guestStore(Request $request)
    {
        $request->validate([
            'guest_name'       => 'required|string|max:255',
            'car_registration' => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'contact_details'  => 'nullable|string|max:1000',
            'checkin_datetime' => 'required|date',
            'duration_hours'   => 'nullable|integer|min:1',
            'contact_name'     => 'required|string|max:255',
        ]);

        $reservation = new VisitorReservation();
        $reservation->guest_name      = $request->guest_name;
        $reservation->company         = $request->company;
        $reservation->phone           = $request->phone;
        $reservation->car_registration = $request->car_registration;
        $reservation->contact_details = $request->contact_details;
        $reservation->checkin_datetime = $request->checkin_datetime;
        $reservation->duration_hours  = $request->duration_hours;

        // Try to find a matching staff user by name, otherwise fallback to user ID 1
        $contactUser = \App\Models\User::where('firstname', 'like', '%' . $request->contact_name . '%')
            ->orWhere('lastname', 'like', '%' . $request->contact_name . '%')
            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $request->contact_name . '%'])
            ->first();
        $reservation->contact_user_id = $contactUser ? $contactUser->id : 1; // fallback: ID 1 if no match
        
        if ($request->duration_hours) {
            $reservation->checkout_datetime = \Carbon\Carbon::parse($request->checkin_datetime)->addHours((int)$request->duration_hours);
        }

        if ($request->slot_id) {
            // Slot selected from dropdown (slot_id known)
            $reservation->slot_id = $request->slot_id;
            $slot = ParkingSlot::find($request->slot_id);
            if ($slot) {
                $slot->status = 'reserved';
                $slot->save();
            }
        } elseif ($request->slot_number_manual) {
            // Slot selected from building map but not in dropdown — lookup by slot_number
            $manualSlot = ParkingSlot::where('slot_number', $request->slot_number_manual)->first();
            if (!$manualSlot) {
                // Create slot if it doesn't exist yet
                $zone = \App\Models\parking\ParkingZone::where('building', 'ในอาคาร')->first();
                $manualSlot = ParkingSlot::create([
                    'zone_id'     => $zone ? $zone->id : null,
                    'slot_number' => $request->slot_number_manual,
                    'status'      => 'available',
                ]);
            }
            if ($manualSlot) {
                $reservation->slot_id = $manualSlot->id;
                $manualSlot->status = 'reserved';
                $manualSlot->save();
            }
        } else {
            // Auto-assign: prefer Building zone Bay 19 (visitor designated area)
            $buildingZone = \App\Models\parking\ParkingZone::where('building', 'ในอาคาร')->first();
            $autoSlot = null;
            if ($buildingZone) {
                $autoSlot = ParkingSlot::where('zone_id', $buildingZone->id)
                    ->where('status', 'available')
                    ->orderByRaw("CASE WHEN slot_number LIKE 'B19_%' THEN 0 ELSE 1 END")
                    ->orderBy('slot_number')
                    ->first();
            }
            if (!$autoSlot) {
                $autoSlot = ParkingSlot::where('status', 'available')->first();
            }
            if ($autoSlot) {
                $reservation->slot_id = $autoSlot->id;
                $autoSlot->status = 'reserved';
                $autoSlot->save();
            }
        }
        
        $reservation->status = 'reserved';
        $reservation->save();

        return redirect()->route('parking.visitors.guestSuccess', $reservation->id);
    }

    public function guestSuccess($id)
    {
        $reservation = VisitorReservation::with('contactUser', 'slot')->findOrFail($id);
        return view('parking.visitor.guest_success', compact('reservation'));
    }

    public function approvals()
    {
        $user = Auth::user();

        // Auto-clear old visitor reservations (if checkin_datetime is before today and not locked)
        $pastVisitorReservations = VisitorReservation::whereDate('checkin_datetime', '<', \Carbon\Carbon::today())
            ->whereIn('status', ['reserved', 'checked_in'])
            ->where('is_locked', false)
            ->get();
            
        foreach ($pastVisitorReservations as $res) {
            $res->status = 'checked_out';
            if (!$res->checkout_datetime) {
                $res->checkout_datetime = $res->checkin_datetime ? \Carbon\Carbon::parse($res->checkin_datetime)->endOfDay() : \Carbon\Carbon::now();
            }
            $res->save();
            
            if ($res->slot) {
                $res->slot->status = 'available';
                $res->slot->save();
            }
        }
        
        // Auto-clear old employee reservations
        $pastEmpReservations = \App\Models\parking\EmployeeReservation::whereDate('checkin_datetime', '<', \Carbon\Carbon::today())
            ->whereIn('status', ['reserved', 'checked_in'])
            ->get();
            
        foreach ($pastEmpReservations as $res) {
            $res->status = 'checked_out';
            if (!$res->checkout_datetime) {
                $res->checkout_datetime = $res->checkin_datetime ? \Carbon\Carbon::parse($res->checkin_datetime)->endOfDay() : \Carbon\Carbon::now();
            }
            $res->save();
            
            if ($res->slot) {
                $res->slot->status = 'available';
                $res->slot->save();
            }
        }

        // 1. My Parking Reservations
        $myVisitorReservations = VisitorReservation::where('contact_user_id', $user->id)
            ->with(['contactUser', 'slot.zone'])
            ->get()->map(function($r) { $r->res_type = 'visitor'; return $r; });
        
        $myEmployeeReservations = \App\Models\parking\EmployeeReservation::where('user_id', $user->id)
            ->with(['user', 'slot.zone'])
            ->get()->map(function($r) { $r->res_type = 'employee'; return $r; });
            
        $myReservations = $myVisitorReservations->merge($myEmployeeReservations)->sortByDesc('checkin_datetime');

        // 2. Pending Manager Approvals (Only for Employee requests)
        $managedDeptIds = \App\Models\Department::where('manager_id', $user->id)->pluck('id');
        $pendingManagerReservations = collect();
        // Only manager, hams admin, or system admin/editor can acknowledge
        if ($managedDeptIds->isNotEmpty() || $user->is_hams_admin || in_array($user->role, ['admin', 'editor'])) {
            $employeeQuery = \App\Models\parking\EmployeeReservation::where('manager_approval', 'pending')
                ->with(['user', 'department.manager', 'slot.zone']);

            if (!$user->is_hams_admin && !in_array($user->role, ['admin', 'editor'])) {
                $employeeQuery->whereIn('dept_id', $managedDeptIds);
            }
            
            $pendingManagerReservations = $employeeQuery->get()->map(function($r) { $r->res_type = 'employee'; return $r; })->sortByDesc('checkin_datetime');
        }

        // 3. Pending Visitor Approvals (Directly to HAMS)
        $pendingVisitorApprovals = collect();
        if ($user->is_hams_admin) {
            $pendingVisitorApprovals = VisitorReservation::where('manager_approval', 'pending')
                ->with(['contactUser.department.manager', 'slot.zone'])
                ->get()->map(function($r) { $r->res_type = 'visitor'; return $r; })->sortByDesc('checkin_datetime');
        }

        // 4. Pending HAMS Acknowledgement (Only for Employee requests)
        $pendingHamsReservations = collect();
        if ($user->is_hams_admin) {
            $pendingHamsReservations = \App\Models\parking\EmployeeReservation::where('manager_approval', 'approved')
                ->where('hams_status', 'pending')
                ->with(['user', 'slot.zone'])
                ->get()->map(function($r) { $r->res_type = 'employee'; return $r; })->sortByDesc('checkin_datetime');
        }

        return view('parking.visitor.approvals', compact('myReservations', 'pendingManagerReservations', 'pendingVisitorApprovals', 'pendingHamsReservations', 'managedDeptIds'));
    }

    public function approve(Request $request, $id)
    {
        if ($request->type == 'employee') {
            $reservation = \App\Models\parking\EmployeeReservation::findOrFail($id);
            $reservation->manager_approval = 'approved';
            $reservation->manager_approved_by = Auth::id();
            $reservation->manager_approved_at = now();
            $reservation->save();
        } else {
            $reservation = VisitorReservation::findOrFail($id);
            $reservation->manager_approval = 'approved';
            $reservation->manager_approved_by = Auth::id();
            $reservation->manager_approved_at = now();
            // Auto-acknowledge for visitors since HAMS is the one approving it
            $reservation->hams_status = 'acknowledged';
            $reservation->hams_acknowledged_by = Auth::id();
            $reservation->hams_acknowledged_at = now();
            $reservation->save();
        }

        return redirect()->back()->with('success', 'อนุมัติคำขอจองที่จอดรถเรียบร้อยแล้ว');
    }

    public function reject(Request $request, $id)
    {
        if ($request->type == 'employee') {
            $reservation = \App\Models\parking\EmployeeReservation::findOrFail($id);
        } else {
            $reservation = VisitorReservation::findOrFail($id);
        }
        
        $reservation->manager_approval = 'rejected';
        $reservation->manager_approved_by = Auth::id();
        $reservation->manager_approved_at = now();
        $reservation->status = 'cancelled';
        if ($request->type != 'employee') {
            $reservation->hams_status = 'acknowledged';
            $reservation->hams_acknowledged_by = Auth::id();
            $reservation->hams_acknowledged_at = now();
        }
        $reservation->save();

        if ($reservation->slot) {
            $reservation->slot->status = 'available';
            $reservation->slot->save();
        }

        return redirect()->back()->with('success', 'ปฏิเสธคำขอจองที่จอดรถเรียบร้อยแล้ว');
    }

    public function acknowledge(Request $request, $id)
    {
        if ($request->type == 'employee') {
            $reservation = \App\Models\parking\EmployeeReservation::findOrFail($id);
        } else {
            $reservation = VisitorReservation::findOrFail($id);
        }
        
        $reservation->hams_status = 'acknowledged';
        $reservation->hams_acknowledged_by = Auth::id();
        $reservation->hams_acknowledged_at = now();
        $reservation->save();

        return redirect()->back()->with('success', 'รับทราบคำขอจองที่จอดรถเรียบร้อยแล้ว');
    }
}
