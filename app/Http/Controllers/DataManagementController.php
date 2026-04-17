<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\User;
use App\Models\bookingcar\Vehicle;
use App\Models\bookingcar\BookingCar;

class DataManagementController extends Controller
{
    public function welcomeDataManagement()
    {
        $userId = auth()->id();

        // 1. Office Supplies (เบิกของ) - Focus on Pending
        $requisitions = \App\Models\serviceshams\Requisitions::where('requester_id', $userId)
            ->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING) // Only Pending
            ->orderBy('created_at', 'desc')
            ->get();
        $requisitionCount = $requisitions->count();

        // 2. Meeting Rooms (จองห้องประชุม) - Focus on Pending/Waiting
        $reservations = \App\Models\bookingmeeting\Reservation::where('user_id', $userId)
            ->whereIn('status', ['รออนุมัติ', 'รอดำเนินการ', 'pending']) // Common pending labels
            ->with('room')
            ->orderBy('created_at', 'desc')
            ->get();
        $reservationCount = $reservations->count();

        // 3. Vehicle Bookings (จองรถ) - Focus on Waiting Approval
        $vehicleBookings = \App\Models\bookingcar\BookingCar::where('user_id', $userId)
            ->where('status', 'รออนุมัติ') // Only Waiting Approval
            ->with('vehicle')
            ->orderBy('created_at', 'desc')
            ->get();
        $vehicleBookingCount = $vehicleBookings->count();

        // 4. Unified Housing Tasks (รวบรวมงานบ้านพักทุกประเภท) - Focus on Pending
        $userId = auth()->id();
        $housingTasks = collect();

        // 4.1 Requests (คำขอเข้าพัก) - Pending states: 0,1,2,7. Exclude: 3(appr), 4(back), 5(cancel), 6(done), 8(deny)
        $requests = \App\Models\housing\ResidenceRequest::where('user_id', $userId)
            ->whereIn('send_status', [0, 1, 2, 7])
            ->get()->each(fn($i) => $i->task_type = 'request');
        $housingTasks = $housingTasks->merge($requests);

        // 4.2 Agreements (ข้อตกลง/สัญญา)
        $agreements = \App\Models\housing\ResidenceAgreement::where('user_id', $userId)
            ->whereIn('send_status', [0, 1, 2])
            ->get()->each(fn($i) => $i->task_type = 'agreement');
        $housingTasks = $housingTasks->merge($agreements);

        // 4.3 Guests (นำญาติเข้าพัก)
        $guests = \App\Models\housing\ResidentGuestRequest::where('user_id', $userId)
            ->whereIn('send_status', [0, 1, 2])
            ->get()->each(fn($i) => $i->task_type = 'guest');
        $housingTasks = $housingTasks->merge($guests);

        // 4.4 Leave (ขอย้ายออก)
        $leaves = \App\Models\housing\ResidenceLeave::where('user_id', $userId)
            ->whereIn('send_status', [0, 1, 2])
            ->get()->each(fn($i) => $i->task_type = 'leave');
        $housingTasks = $housingTasks->merge($leaves);

        // 4.5 Repairs (แจ้งซ่อม)
        $repairs = \App\Models\housing\ResidenceRepair::where('user_id', $userId)
            ->whereIn('status', ['รอดำเนินการ', 'กำลังดำเนินการ', 'pending', 'processing', 'กำลังดำเนินการซ่อม'])
            ->with(['room'])
            ->get()->each(fn($i) => $i->task_type = 'repair');
        $housingTasks = $housingTasks->merge($repairs);

        $housingTasksCount = $housingTasks->count();

        return view('backend.welcomedatamanage', compact(
            'requisitions', 'requisitionCount',
            'reservations', 'reservationCount',
            'vehicleBookings', 'vehicleBookingCount',
            'housingTasks', 'housingTasksCount'
        ));
    }
}