<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\parking\EmployeeParking;

class EmployeeParkingController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->is_hams_admin) {
            abort(403, 'สงวนสิทธิ์การเข้าถึงสำหรับผู้ดูแลระบบเท่านั้น');
        }

        // Fetch active/recent employee parking records
        $parkings = EmployeeParking::with(['user', 'slot'])
                        ->orderBy('time_in', 'desc')
                        ->get();

        return view('parking.employee.index', compact('parkings'));
    }

    public function create()
    {
        // Fetch all users to display in the dropdown
        $users = \App\Models\User::orderBy('firstname')->orderBy('lastname')->get();

        $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
        $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
        $occupiedSlotIds = array_merge($activeEmployeeSlotIds, $activeVisitorSlotIds);

        $slots = \App\Models\parking\ParkingSlot::with([
            'employeeParkings' => function($q) {
                $q->where('status', 'parking')->with('user');
            },
            'visitorReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in']);
            }
        ])->get()->keyBy('slot_number');

        $occupiedSlots = $slots->filter(fn($s) => in_array($s->id, $occupiedSlotIds) || $s->status === 'occupied' || $s->status === 'reserved')->pluck('slot_number')->toArray();

        return view('parking.employee.create', compact('users', 'occupiedSlots', 'slots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'parkings' => 'required|array|min:1',
            'parkings.*.user_id' => [
                'required',
                'distinct',
                'exists:userkml2025.employees,id',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\parking\EmployeeParking::where('user_id', $value)
                        ->where('status', 'parking')
                        ->exists();
                    if ($exists) {
                        $fail('รหัสพนักงานนี้มีการลงทะเบียนจอดรถอยู่แล้ว');
                    }
                },
            ],
            'parkings.*.car_registration' => 'required|string|max:255',
            'parkings.*.slot_number' => 'required|string|max:10',
        ]);

        // Find or create default zone for headquarters outdoor
        $zone = \App\Models\parking\ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        if (!$zone) {
            $zone = \App\Models\parking\ParkingZone::create([
                'building' => 'ลานจอดรถหลัก',
                'zone' => 'A',
                'floor' => 'กลางแจ้ง',
            ]);
        }

        foreach ($request->parkings as $item) {
            // Find or create the parking slot
            $slot = \App\Models\parking\ParkingSlot::firstOrCreate([
                'zone_id' => $zone->id,
                'slot_number' => $item['slot_number'],
            ]);

            // Update slot status to occupied
            $slot->status = 'occupied';
            $slot->save();

            // Check if there's already an active parking record for this slot, if so set status to left
            EmployeeParking::where('slot_id', $slot->id)
                ->where('status', 'parking')
                ->update([
                    'status' => 'left',
                    'time_out' => now()
                ]);

            // Create the employee parking registration
            EmployeeParking::create([
                'user_id' => $item['user_id'],
                'car_registration' => $item['car_registration'],
                'slot_id' => $slot->id,
                'time_in' => now(),
                'status' => 'parking',
            ]);
        }

        return redirect()->route('parking.employees.index')->with('success', 'บันทึกข้อมูลการจอดรถพนักงานเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $parking = EmployeeParking::findOrFail($id);
        $users = \App\Models\User::orderBy('firstname')->orderBy('lastname')->get();

        $zone = \App\Models\parking\ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        $occupiedSlots = [];
        if ($zone) {
            $currentSlotNumber = $parking->slot ? $parking->slot->slot_number : null;
            $occupiedSlots = \App\Models\parking\ParkingSlot::where('zone_id', $zone->id)
                ->where('status', 'occupied')
                ->pluck('slot_number')
                ->filter(fn($val) => $val !== $currentSlotNumber)
                ->toArray();
        }

        return view('parking.employee.edit', compact('parking', 'users', 'occupiedSlots'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => [
                'required',
                'exists:userkml2025.employees,id',
                function ($attribute, $value, $fail) use ($id, $request) {
                    if ($request->status === 'parking') {
                        $exists = \App\Models\parking\EmployeeParking::where('user_id', $value)
                            ->where('status', 'parking')
                            ->where('id', '!=', $id)
                            ->exists();
                        if ($exists) {
                            $fail('รหัสพนักงานนี้มีการลงทะเบียนจอดรถอยู่แล้ว');
                        }
                    }
                },
            ],
            'car_registration' => 'required|string|max:255',
            'slot_number' => 'required|string|max:10',
            'status' => 'required|in:parking,left',
        ]);

        $parking = EmployeeParking::findOrFail($id);

        // Find or create default zone for headquarters outdoor
        $zone = \App\Models\parking\ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        if (!$zone) {
            $zone = \App\Models\parking\ParkingZone::create([
                'building' => 'ลานจอดรถหลัก',
                'zone' => 'A',
                'floor' => 'กลางแจ้ง',
            ]);
        }

        // Find or create the parking slot
        $slot = \App\Models\parking\ParkingSlot::firstOrCreate([
            'zone_id' => $zone->id,
            'slot_number' => $request->slot_number,
        ]);

        // Update slot status
        if ($request->status === 'parking') {
            $slot->status = 'occupied';
        } else {
            // Check if there are other active parkings on this slot
            $otherActive = EmployeeParking::where('slot_id', $slot->id)
                ->where('id', '!=', $id)
                ->where('status', 'parking')
                ->exists();
            if (!$otherActive) {
                $slot->status = 'available';
            }
        }
        $slot->save();

        $parking->update([
            'user_id' => $request->user_id,
            'car_registration' => $request->car_registration,
            'slot_id' => $slot->id,
            'status' => $request->status,
            'time_out' => $request->status === 'left' ? now() : null,
        ]);

        return redirect()->route('parking.employees.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $parking = EmployeeParking::findOrFail($id);

        $slot = $parking->slot;
        if ($slot) {
            $otherActive = EmployeeParking::where('slot_id', $slot->id)
                ->where('id', '!=', $id)
                ->where('status', 'parking')
                ->exists();
            if (!$otherActive) {
                $slot->status = 'available';
                $slot->save();
            }
        }

        $parking->delete();

        return redirect()->route('parking.employees.index')->with('success', 'ลบข้อมูลการจอดรถเรียบร้อยแล้ว');
    }
}
