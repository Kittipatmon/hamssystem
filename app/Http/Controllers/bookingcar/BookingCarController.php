<?php

namespace App\Http\Controllers\bookingcar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingcar\Vehicle;
use App\Models\bookingcar\BookingCar;
use Illuminate\Support\Facades\Auth;

class BookingCarController extends Controller
{
    public function welcome()
    {
        // Get all available vehicles for dropdown
        $vehicles = Vehicle::where('status', 1)->get();
        // Get 3 available vehicles for preview panel
        $previewVehicles = Vehicle::where('status', 1)->take(3)->get();

        // Get current user details for passing to view
        $currentUser = Auth::user();
        $isHamsOrAdmin = $currentUser && (($currentUser->department && $currentUser->department->department_name === 'HAMS') || $currentUser->employee_code === '11648');
        $currentUserId = $currentUser ? $currentUser->id : null;

        // Fetch approved and pending bookings for the calendar (All relevant ones)
        $calendarBookings = BookingCar::with(['user', 'vehicle'])
            ->whereIn('status', ['อนุมัติแล้ว', 'รออนุมัติ'])
            ->get()
            ->map(function ($booking) {
                $isPending = $booking->status === 'รออนุมัติ';
                return [
                    'id' => $booking->booking_id,
                    'title' => ($booking->vehicle->name ?? 'รถส่วนกลาง') . ' - ' . ($booking->user->first_name ?? 'N/A'),
                    'start' => \Carbon\Carbon::parse($booking->start_time)->toIso8601String(),
                    'end' => \Carbon\Carbon::parse($booking->end_time)->toIso8601String(),
                    'color' => $isPending ? '#f59e0b' : '#ef4444',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'user' => ($booking->user->first_name ?? 'N/A') . ' ' . ($booking->user->last_name ?? ''),
                        'destination' => $booking->destination,
                        'purpose' => $booking->purpose,
                        'start_time_formatted' => \Carbon\Carbon::parse($booking->start_time)->format('d/m H:i'),
                        'end_time_formatted' => \Carbon\Carbon::parse($booking->end_time)->format('d/m H:i'),
                        'user_id' => $booking->user_id,
                        'return_status' => $booking->return_status,
                        'status' => $booking->status
                    ]
                ];
            });

        // Separate lists for the dashboard boards
        $upcomingBookings = BookingCar::with(['user', 'vehicle'])
            ->whereIn('status', ['อนุมัติแล้ว', 'รออนุมัติ'])
            ->where('return_status', 'ยังไม่ส่งคืน')
            ->orderBy('start_time', 'asc')
            ->get();

        $returnedBookings = BookingCar::with(['user', 'vehicle'])
            ->where('return_status', 'ส่งคืนแล้ว')
            ->orderBy('returned_at', 'desc')
            ->take(10)
            ->get();

        return view('bookingcar.welcome', compact('vehicles', 'previewVehicles', 'calendarBookings', 'upcomingBookings', 'returnedBookings', 'currentUserId', 'isHamsOrAdmin'));
    }

    public function vehicles()
    {
        // Get all active vehicles
        $vehicles = Vehicle::where('status', 1)->get();
        return view('bookingcar.vehicles', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'purpose' => 'nullable|string',
            'requester_name' => 'nullable|string|max:100',
            'destination' => 'required|string|max:200',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_date_end' => 'required|date|after_or_equal:booking_date',
            'passenger_count' => 'nullable|integer|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'mileage_before' => 'nullable|integer',
            'mileage_after' => 'nullable|integer',
            'note_returning' => 'nullable|string',
            'attachment_going' => 'nullable|array',
            'attachment_going.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'attachment_returning' => 'nullable|array',
            'attachment_returning.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ], [
            'vehicle_id.required' => 'กรุณาเลือกรถส่วนกลาง',
            'booking_date.required' => 'กรุณาระบุวันที่เริ่มเดินทาง',
            'booking_date_end.required' => 'กรุณาระบุวันที่สิ้นสุดการเดินทาง',
            'booking_date_end.after_or_equal' => 'วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่มเดินทาง',
            'start_time.required' => 'กรุณาระบุเวลาที่เริ่มการใช้งานรถ',
            'end_time.required' => 'กรุณาระบุเวลาที่สิ้นสุดการใช้งานรถ',
            'destination.required' => 'กรุณาระบุสถานที่ปลายทาง',
            'district.required' => 'กรุณาระบุอำเภอ',
            'province.required' => 'กรุณาระบุจังหวัด',
        ]);

        $bookingCode = 'BKC-' . strtoupper(uniqid());

        $startDatetime = $request->booking_date . ' ' . $request->start_time . ':00';
        $endDatetime = $request->booking_date_end . ' ' . $request->end_time . ':00';

        // Additional validation for same-day start/end time
        if ($request->booking_date === $request->booking_date_end && $request->end_time <= $request->start_time) {
            return redirect()->back()->with('error', 'เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่มต้นสำหรับการจองในวันเดียวกัน')->withInput();
        }

        // Check for conflicts
        $conflict = BookingCar::where('vehicle_id', $request->vehicle_id)
            ->whereIn('status', ['อนุมัติแล้ว', 'รออนุมัติ'])
            ->where(function ($query) use ($startDatetime, $endDatetime) {
                $query->where('start_time', '<', $endDatetime)
                      ->where('end_time', '>', $startDatetime);
            })->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'รถคันนี้ถูกจองในช่วงเวลาดังกล่าวแล้ว')->withInput();
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/bookingcar_attachments'), $filename);
            $attachmentPath = 'uploads/bookingcar_attachments/' . $filename;
        }

        $attachmentGoingPaths = [];
        if ($request->hasFile('attachment_going')) {
            foreach ($request->file('attachment_going') as $file) {
                $filename = time() . '_going_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                $attachmentGoingPaths[] = 'uploads/bookingcar_attachments/' . $filename;
            }
        }
        $attachmentGoingPathStr = !empty($attachmentGoingPaths) ? json_encode($attachmentGoingPaths) : null;

        $attachmentReturningPaths = [];
        if ($request->hasFile('attachment_returning')) {
            foreach ($request->file('attachment_returning') as $file) {
                $filename = time() . '_returning_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                $attachmentReturningPaths[] = 'uploads/bookingcar_attachments/' . $filename;
            }
        }
        $attachmentReturningPathStr = !empty($attachmentReturningPaths) ? json_encode($attachmentReturningPaths) : null;

        BookingCar::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'vehicle_id' => $request->vehicle_id,
            'bookings_date' => now()->toDateString(),
            'booking_date' => $request->booking_date,
            'start_time' => $startDatetime,
            'end_time' => $endDatetime,
            'destination' => $request->destination,
            'district' => $request->district,
            'province' => $request->province,
            'requester_name' => $request->requester_name,
            'passenger_count' => $request->passenger_count,
            'purpose' => $request->purpose,
            'attachment' => $attachmentPath,
            'mileage_before' => $request->mileage_before,
            'mileage_after' => $request->mileage_after,
            'note_returning' => $request->note_returning,
            'attachment_going' => $attachmentGoingPathStr,
            'attachment_returning' => $attachmentReturningPathStr,
            'status' => 'รออนุมัติ',
            'return_status' => 'ยังไม่ส่งคืน',
            'approved_status' => 0,
        ]);

        return redirect()->route('bookingcar.welcome')->with('success', 'ส่งคำร้องขอจองรถส่วนกลางเรียบร้อยแล้ว');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $bookings = BookingCar::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('bookingcar.dashboard', compact('bookings'));
    }

    public function report()
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $totalBookings = BookingCar::count();
        $approvedBookings = BookingCar::where('status', 'อนุมัติแล้ว')->count();
        $pendingBookings = BookingCar::where('status', 'รออนุมัติ')->count();
        $rejectedBookings = BookingCar::where('status', 'ไม่อนุมัติ')->orWhere('status', 'ยกเลิก')->count();

        // Optional chart data or tabular report data can be added here
        $recentBookings = BookingCar::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('bookingcar.report', compact(
            'totalBookings',
            'approvedBookings',
            'pendingBookings',
            'rejectedBookings',
            'recentBookings'
        ));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $booking = BookingCar::with(['user', 'vehicle'])->findOrFail($id);
        $vehicles = Vehicle::where('status', 1)->get();
        return view('bookingcar.edit', compact('booking', 'vehicles'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $booking = BookingCar::findOrFail($id);

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'destination' => 'required|string|max:200',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'mileage_before' => 'nullable|integer',
            'mileage_after' => 'nullable|integer',
            'note_returning' => 'nullable|string',
            'return_status' => 'required|string',
        ]);

        $booking->vehicle_id = $request->vehicle_id;
        $booking->destination = $request->destination;
        $booking->start_time = $request->start_time;
        $booking->end_time = $request->end_time;
        $booking->mileage_before = $request->mileage_before;
        $booking->mileage_after = $request->mileage_after;
        $booking->note_returning = $request->note_returning;
        $booking->return_status = $request->return_status;

        // Handle return file uploads if needed in update...
        if ($request->hasFile('attachment_going')) {
            $existing_paths = json_decode($booking->attachment_going, true);
            if (!is_array($existing_paths)) {
                $existing_paths = $booking->attachment_going ? [$booking->attachment_going] : [];
            }

            foreach ($request->file('attachment_going') as $file) {
                $filename = time() . '_going_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                $existing_paths[] = 'uploads/bookingcar_attachments/' . $filename;
            }
            $booking->attachment_going = json_encode($existing_paths);
        }

        if ($request->hasFile('attachment_returning')) {
            $existing_paths = json_decode($booking->attachment_returning, true);
            if (!is_array($existing_paths)) {
                $existing_paths = $booking->attachment_returning ? [$booking->attachment_returning] : [];
            }

            foreach ($request->file('attachment_returning') as $file) {
                $filename = time() . '_returning_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                $existing_paths[] = 'uploads/bookingcar_attachments/' . $filename;
            }
            $booking->attachment_returning = json_encode($existing_paths);
        }

        if ($request->return_status === 'ส่งคืนแล้ว' && !$booking->returned_at) {
            $booking->returned_at = now();
        }

        $booking->save();

        return redirect()->route('bookingcar.dashboard')->with('success', 'อัปเดตข้อมูลการจองเรียบร้อยแล้ว');
    }

    public function approve(Request $request, $id)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $booking = BookingCar::findOrFail($id);

        $request->validate([
            'status' => 'required|in:รออนุมัติ,อนุมัติแล้ว,ไม่อนุมัติ,ยกเลิก',
        ]);

        $booking->status = $request->status;

        if ($request->status === 'อนุมัติแล้ว' || $request->status === 'ไม่อนุมัติ') {
            $booking->approved_by = Auth::id();
            $booking->approved_status = ($request->status === 'อนุมัติแล้ว') ? 1 : 2;
            $booking->approved_at = now();
        }

        $booking->save();

        return redirect()->route('bookingcar.dashboard')->with('success', 'อัปเดตสถานะการอนุมัติเรียบร้อยแล้ว');
    }

    public function cancel($id)
    {
        $booking = BookingCar::findOrFail($id);

        // Allow cancellation only if the user owns the booking
        if ($booking->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'คุณไม่สามารถยกเลิกการจองของผู้อื่นได้');
        }

        // Allow cancellation if not already canceled
        if ($booking->status !== 'ยกเลิก' && $booking->return_status !== 'ส่งคืนแล้ว') {
            $booking->status = 'ยกเลิก';
            $booking->save();
            return redirect()->back()->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถยกเลิกการจองนี้ได้');
    }

    public function returnCar($id)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ทำรายการนี้');
        }

        $booking = BookingCar::findOrFail($id);

        if ($booking->return_status !== 'ส่งคืนแล้ว') {
            $booking->return_status = 'ส่งคืนแล้ว';
            if (!$booking->returned_at) {
                $booking->returned_at = now();
            }
            $booking->save();
            return redirect()->back()->with('success', 'บันทึกสถานะการคืนรถเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'รถได้ถูกส่งคืนไปแล้ว');
    }
}
