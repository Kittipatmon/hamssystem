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

        // 4. Housing Repairs (แจ้งซ่อม) - Focus on Pending/In-progress
        $repairs = \App\Models\housing\ResidenceRepair::where('user_id', $userId)
            ->whereIn('status', ['รอดำเนินการ', 'กำลังดำเนินการ', 'pending', 'processing'])
            ->orderBy('created_at', 'desc')
            ->get();
        $repairCount = $repairs->count();

        return view('backend.welcomedatamanage', compact(
            'requisitions',
            'requisitionCount',
            'reservations',
            'reservationCount',
            'vehicleBookings',
            'vehicleBookingCount',
            'repairs',
            'repairCount'
        ));
    }
}