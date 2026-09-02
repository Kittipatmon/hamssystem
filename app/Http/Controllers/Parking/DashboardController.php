<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !auth()->user()->is_hams_admin) {
            abort(403, 'สงวนสิทธิ์การเข้าถึงสำหรับผู้ดูแลระบบเท่านั้น');
        }

        // Summary Data
        $totalSlots = \App\Models\parking\ParkingSlot::count();
        $availableSlots = \App\Models\parking\ParkingSlot::where('status', 'available')->count();
        $occupiedSlots = \App\Models\parking\ParkingSlot::where('status', 'occupied')->count();
        $reservedSlots = \App\Models\parking\ParkingSlot::where('status', 'reserved')->count();
        
        $todayCarsInEmployee = \App\Models\parking\EmployeeParking::whereDate('time_in', \Carbon\Carbon::today())->count();
        $todayCarsInVisitor = \App\Models\parking\VisitorReservation::whereDate('checkin_datetime', \Carbon\Carbon::today())->count();
        $todayCarsIn = $todayCarsInEmployee + $todayCarsInVisitor;
        
        $todayReservations = \App\Models\parking\VisitorReservation::whereDate('created_at', \Carbon\Carbon::today())->count();
        $guestsCurrentlyParking = \App\Models\parking\VisitorReservation::where('status', 'checked_in')->count();

        // Fetch recent employee parkings
        $recentEmployee = \App\Models\parking\EmployeeParking::with('user', 'slot')
            ->orderBy('time_in', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'employee',
                    'name' => $item->user ? $item->user->fullname : 'ไม่ทราบชื่อ',
                    'car_registration' => $item->car_registration,
                    'slot' => $item->slot ? $item->slot->slot_number : '-',
                    'time' => $item->time_in,
                    'status' => $item->status === 'parking' ? 'เข้าจอด' : 'ออกแล้ว'
                ];
            });

        // Fetch recent visitor reservations
        $recentVisitor = \App\Models\parking\VisitorReservation::with('slot')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'visitor',
                    'name' => $item->guest_name . ' (แขก)',
                    'car_registration' => $item->car_registration,
                    'slot' => $item->slot ? $item->slot->slot_number : '-',
                    'time' => \Carbon\Carbon::parse($item->checkin_datetime),
                    'status' => $item->status === 'checked_in' ? 'เข้าจอด' : ($item->status === 'checked_out' ? 'ออกแล้ว' : 'จองแล้ว')
                ];
            });

        $recentActivities = $recentEmployee->concat($recentVisitor)
            ->sortByDesc('time')
            ->take(5);

        return view('parking.dashboard', compact(
            'totalSlots', 
            'availableSlots', 
            'occupiedSlots', 
            'reservedSlots',
            'todayCarsIn',
            'todayReservations',
            'guestsCurrentlyParking',
            'recentActivities'
        ));
    }
}
