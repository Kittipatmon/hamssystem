<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\parking\EmployeeReservation;
use App\Models\parking\ParkingSlot;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class EmployeeReservationController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('welcome')->with('error', 'กรุณาเข้าสู่ระบบก่อน');
        }

        // Auto-clear old employee reservations
        $pastReservations = EmployeeReservation::whereDate('checkin_datetime', '<', \Carbon\Carbon::today())
            ->whereIn('status', ['reserved', 'checked_in'])
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
        
        $query = EmployeeReservation::with(['user', 'department', 'slot.zone'])->orderBy('created_at', 'desc');
        
        if (!Auth::user()->is_hams_admin) {
            $query->where('user_id', Auth::id());
        }
        
        $reservations = $query->get();
        return view('parking.employee_reservation.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('welcome')->with('error', 'กรุณาเข้าสู่ระบบก่อนทำการจองที่จอดรถ');
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

        // Available slots in building
        $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
        $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $activeEmpReservations = EmployeeReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        
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

        $departments = Department::orderBy('name')->get();

        return view('parking.employee_reservation.create', compact('availableSlots', 'selectedSlotId', 'departments'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('welcome');
        }

        $request->validate([
            'car_registration' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'slot_id' => 'required|exists:parking_slots,id',
            'dept_id' => 'required|exists:userkml2025.departments,id',
            'checkin_datetime' => 'required|date',
            'checkout_datetime' => 'nullable|date|after:checkin_datetime',
        ]);

        // Check if slot is already occupied
        $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
        $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $activeEmpReservations = EmployeeReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        
        $occupiedSlotIds = array_unique(array_merge($activeEmployeeSlotIds, $activeVisitorSlotIds, $activeEmpReservations));
        
        if (in_array($request->slot_id, $occupiedSlotIds)) {
            return back()->with('error', 'ช่องจอดนี้ไม่ว่างหรือถูกจองไปแล้ว กรุณาเลือกช่องจอดใหม่')->withInput();
        }

        $reservation = new EmployeeReservation();
        $reservation->user_id = Auth::id();
        $reservation->car_registration = $request->car_registration;
        $reservation->details = $request->details;
        $reservation->slot_id = $request->slot_id;
        $reservation->dept_id = $request->dept_id;
        $reservation->checkin_datetime = $request->checkin_datetime;
        $reservation->checkout_datetime = $request->checkout_datetime;
        $reservation->manager_approval = 'pending';
        $reservation->hams_status = 'pending';
        $reservation->status = 'reserved';
        $reservation->save();

        return redirect()->route('parking.visitors.approvals')->with('success', 'ส่งคำขอจองที่จอดรถเรียบร้อยแล้ว รอการอนุมัติจากหัวหน้าแผนก');
    }

    public function getManager($dept_id)
    {
        $department = Department::with('manager')->find($dept_id);
        
        if ($department && $department->manager) {
            return response()->json([
                'success' => true,
                'manager_name' => $department->manager->fullname,
                'manager_id' => $department->manager_id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'ไม่พบข้อมูลหัวหน้าแผนกสำหรับแผนกนี้'
        ]);
    }
}
