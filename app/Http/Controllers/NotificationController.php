<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\parking\VisitorReservation;
use App\Models\parking\EmployeeReservation;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function checkNewRequests(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $lastChecked = $request->query('last_checked');
        $now = Carbon::now();

        if (!$lastChecked) {
            // First time loading, just return current time
            return response()->json([
                'success' => true, 
                'has_new' => false, 
                'timestamp' => $now->toDateTimeString()
            ]);
        }

        $lastCheckedTime = Carbon::parse($lastChecked);
        $notifications = [];

        // Check if user is admin
        if (Auth::user()->is_hams_admin) {
            // Check Visitor Reservations
            $newVisitorReqs = VisitorReservation::where('created_at', '>', $lastCheckedTime)->get();
            foreach ($newVisitorReqs as $req) {
                $notifications[] = [
                    'type' => 'visitor_parking',
                    'title' => 'คำขอจอดรถแขกใหม่',
                    'message' => 'ทะเบียน ' . $req->car_registration,
                    'url' => route('parking.visitors.index')
                ];
            }

            // Check Employee Reservations
            $newEmpReqs = EmployeeReservation::where('created_at', '>', $lastCheckedTime)->get();
            foreach ($newEmpReqs as $req) {
                $notifications[] = [
                    'type' => 'employee_parking',
                    'title' => 'คำขอจอดรถพนักงาน',
                    'message' => 'ทะเบียน ' . $req->car_registration,
                    'url' => route('parking.visitors.approvals')
                ];
            }
        }

        return response()->json([
            'success' => true,
            'has_new' => count($notifications) > 0,
            'notifications' => $notifications,
            'timestamp' => $now->toDateTimeString()
        ]);
    }
}
