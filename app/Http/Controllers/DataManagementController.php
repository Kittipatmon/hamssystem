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
        // Data Management Stats
        $newsCount = News::where('is_active', true)->count();
        $userCount = User::where('status', User::STATUS_ACTIVE)->count();

        // Vehicle Management Stats
        $vehicleCount = Vehicle::where('status', 'available')->count();
        $pendingBookingsCount = BookingCar::where('status', 'รออนุมัติ')->count();

        return view('backend.welcomedatamanage', compact(
            'newsCount',
            'userCount',
            'vehicleCount',
            'pendingBookingsCount'
        ));
    }
}