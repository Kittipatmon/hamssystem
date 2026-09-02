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

        // 5. Parking Reservations (จองที่จอดรถ) - Focus on Pending
        $pendingParkingReservations = collect();
        $user = auth()->user();
        
        $managedDeptIds = \App\Models\Department::where('manager_id', $user->id)->pluck('id');
        if ($managedDeptIds->isNotEmpty() || $user->is_hams_admin || in_array($user->role, ['admin', 'editor'])) {
            $employeeQuery = \App\Models\parking\EmployeeReservation::where('manager_approval', 'pending')
                ->with(['user', 'department', 'slot.zone']);
            if (!$user->is_hams_admin && !in_array($user->role, ['admin', 'editor'])) {
                $employeeQuery->whereIn('dept_id', $managedDeptIds);
            }
            $pendingParkingReservations = $pendingParkingReservations->merge(
                $employeeQuery->get()->map(function($r) { $r->task_type = 'employee_manager'; return $r; })
            );
        }

        if ($user->is_hams_admin) {
            $visitorQuery = \App\Models\parking\VisitorReservation::where('manager_approval', 'pending')
                ->with(['contactUser.department', 'slot.zone']);
            $pendingParkingReservations = $pendingParkingReservations->merge(
                $visitorQuery->get()->map(function($r) { $r->task_type = 'visitor_hams'; return $r; })
            );
            
            $hamsEmpQuery = \App\Models\parking\EmployeeReservation::where('manager_approval', 'approved')
                ->where('hams_status', 'pending')
                ->with(['user', 'slot.zone']);
            $pendingParkingReservations = $pendingParkingReservations->merge(
                $hamsEmpQuery->get()->map(function($r) { $r->task_type = 'employee_hams'; return $r; })
            );
        }

        if ($pendingParkingReservations->isEmpty()) {
            $myVisitor = \App\Models\parking\VisitorReservation::where('contact_user_id', $user->id)
                ->where('manager_approval', 'pending')
                ->with(['slot.zone'])
                ->get()->map(function($r) { $r->task_type = 'my_visitor'; return $r; });
            $pendingParkingReservations = $pendingParkingReservations->merge($myVisitor);

            $myEmp = \App\Models\parking\EmployeeReservation::where('user_id', $user->id)
                ->where(function($q) {
                    $q->where('manager_approval', 'pending')
                      ->orWhere(function($sq) {
                          $sq->where('manager_approval', 'approved')->where('hams_status', 'pending');
                      });
                })
                ->with(['slot.zone'])
                ->get()->map(function($r) { $r->task_type = 'my_employee'; return $r; });
            $pendingParkingReservations = $pendingParkingReservations->merge($myEmp);
        }

        $parkingReservationsCount = $pendingParkingReservations->count();

        return view('backend.welcomedatamanage', compact(
            'requisitions', 'requisitionCount',
            'vehicleBookings', 'vehicleBookingCount',
            'housingTasks', 'housingTasksCount',
            'pendingParkingReservations', 'parkingReservationsCount'
        ));
    }
}