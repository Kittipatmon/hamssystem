<?php

namespace App\Http\Controllers\bookingcar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingcar\Vehicle;
use App\Models\bookingcar\BookingCar;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BookingCarController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $startTime = $request->query('start_time');
        $endTime = $request->query('end_time');

        if (!$startDate || !$endDate || !$startTime || !$endTime) {
            return response()->json(['occupied_vehicle_ids' => []]);
        }

        $startDatetime = $startDate . ' ' . $startTime . ':00';
        $endDatetime = $endDate . ' ' . $endTime . ':00';

        // Find vehicles that HAVE approved bookings in this range
        $occupiedVehicleIds = BookingCar::where('status', 'อนุมัติแล้ว')
            ->where('return_status', '!=', 'ส่งคืนแล้ว')
            ->where(function ($query) use ($startDatetime, $endDatetime) {
                $query->where('start_time', '<', $endDatetime)
                    ->where('end_time', '>', $startDatetime);
            })
            ->pluck('vehicle_id')
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'occupied_vehicle_ids' => $occupiedVehicleIds
        ]);
    }

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
                        'start_time_formatted' => \Carbon\Carbon::parse($booking->start_time)->format('d/m/Y H:i'),
                        'end_time_formatted' => \Carbon\Carbon::parse($booking->end_time)->format('d/m/Y H:i'),
                        'user_id' => $booking->user_id,
                        'return_status' => $booking->return_status,
                        'status' => $booking->status,
                        'requester_name' => $booking->requester_name ?? '-',
                        'vehicle_name' => $booking->vehicle->name ?? 'ไม่ระบุ',
                        'vehicle_model' => $booking->vehicle->model_name ?? '-',
                        'vehicle_type' => $booking->vehicle->type ?? '-',
                        'province' => $booking->province ?? '-',
                        'passenger_count' => $booking->passenger_count ?? '-',
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

        $existingDistricts = BookingCar::select('district')
            ->distinct()
            ->whereNotNull('district')
            ->orderBy('district')
            ->pluck('district');

        $jsonPath = storage_path('app/thailand_geography.json');
        $provinces = [];
        if (file_exists($jsonPath)) {
            $geoData = json_decode(file_get_contents($jsonPath), true);
            $provinces = array_keys($geoData);
            sort($provinces);
        }

        return view('bookingcar.welcome', compact('vehicles', 'previewVehicles', 'calendarBookings', 'upcomingBookings', 'returnedBookings', 'currentUserId', 'isHamsOrAdmin', 'existingDistricts', 'provinces'));
    }

    public function vehicles()
    {
        // Get all active vehicles (status = 'available' or similar)
        // Note: Using status_vehicles = 1 for general pool cars, but we want all active ones here as per user request to see 'all'
        $vehicles = Vehicle::whereIn('status', ['available', '1'])->get();

        // Get unique filter values for the sidebar/header
        $types = Vehicle::whereIn('status', ['available', '1'])->pluck('type')->unique()->filter()->values();
        $fuels = Vehicle::whereIn('status', ['available', '1'])->pluck('filling_type')->unique()->filter()->values();
        $seats = Vehicle::whereIn('status', ['available', '1'])->pluck('seat')->unique()->sort()->values();
        $usageTypes = Vehicle::whereIn('status', ['available', '1'])->pluck('status_vehicles')->unique()->values();

        return view('bookingcar.vehicles', compact('vehicles', 'types', 'fuels', 'seats', 'usageTypes'));
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
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'mileage_before' => 'nullable|integer',
            'mileage_after' => 'nullable|integer',
            'note_returning' => 'nullable|string',
            'attachment_going' => 'nullable|array',
            'attachment_going.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'attachment_returning' => 'nullable|array',
            'attachment_returning.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
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
            ->where('return_status', '!=', 'ส่งคืนแล้ว')
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

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $query = BookingCar::with(['user.department', 'vehicle']);

        // Global Search (Booking Code, Name, Requester, Department, Destination, Province) - Fixed for cross-db
        if ($request->filled('search')) {
            $search = $request->search;

            // Pre-fetch user IDs matching name or department from secondary database
            $matchingUserIds = User::where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhereHas('department', function ($dq) use ($search) {
                    $dq->where('department_name', 'like', '%' . $search . '%');
                })
                ->pluck('id');

            $query->where(function ($q) use ($search, $matchingUserIds) {
                // Direct fields in primary database
                $q->where('booking_code', 'like', '%' . $search . '%')
                    ->orWhere('requester_name', 'like', '%' . $search . '%')
                    ->orWhere('destination', 'like', '%' . $search . '%')
                    ->orWhere('province', 'like', '%' . $search . '%');

                // Use pre-fetched User IDs for cross-database relations
                if ($matchingUserIds->isNotEmpty()) {
                    $q->orWhereIn('user_id', $matchingUserIds);
                }
            });
        }

        // Specialized Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('return_status')) {
            $query->where('return_status', $request->return_status);
        }

        if ($request->filled('booking_date')) {
            $query->where('booking_date', $request->booking_date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('booking_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('booking_date', $request->year);
        }

        if ($request->filled('passenger_count')) {
            $query->where('passenger_count', $request->passenger_count);
        }

        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }

        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $passengerCounts = BookingCar::select('passenger_count')
            ->distinct()
            ->whereNotNull('passenger_count')
            ->orderBy('passenger_count')
            ->pluck('passenger_count');

        // Dynamic Years and Thai Months
        $years = BookingCar::selectRaw('YEAR(booking_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $thaiMonths = [
            '1' => 'มกราคม',
            '2' => 'กุมภาพันธ์',
            '3' => 'มีนาคม',
            '4' => 'เมษายน',
            '5' => 'พฤษภาคม',
            '6' => 'มิถุนายน',
            '7' => 'กรกฎาคม',
            '8' => 'สิงหาคม',
            '9' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม'
        ];

        $existingDistricts = BookingCar::select('district')
            ->distinct()
            ->whereNotNull('district')
            ->orderBy('district')
            ->pluck('district');

        return view('bookingcar.dashboard', compact('bookings', 'passengerCounts', 'years', 'thaiMonths', 'existingDistricts'));
    }

    public function exportExcel(Request $request)
    {
        $query = BookingCar::with('user.department');

        // Apply same filters as dashboard
        if ($request->filled('search')) {
            $search = $request->search;
            $matchingUserIds = \App\Models\User::where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhereHas('department', function ($q) use ($search) {
                    $q->where('department_name', 'like', '%' . $search . '%');
                })
                ->pluck('id');

            $query->where(function ($q) use ($search, $matchingUserIds) {
                $q->where('booking_code', 'like', '%' . $search . '%')
                    ->orWhere('destination', 'like', '%' . $search . '%')
                    ->orWhere('province', 'like', '%' . $search . '%')
                    ->orWhereIn('user_id', $matchingUserIds);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('return_status')) {
            $query->where('return_status', $request->return_status);
        }

        if ($request->filled('booking_date')) {
            $query->where('booking_date', $request->booking_date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('booking_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('booking_date', $request->year);
        }

        if ($request->filled('passenger_count')) {
            $query->where('passenger_count', $request->passenger_count);
        }

        if ($request->filled('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }

        if ($request->filled('district')) {
            $query->where('district', 'like', '%' . $request->district . '%');
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        $fileName = "booking_report_" . date('Y_m_d_His') . ".xls";

        $headers = [
            "Content-Type" => "application/vnd.ms-excel; charset=utf-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Expires" => "0",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Pragma" => "public"
        ];

        // Construct HTML Table for Excel
        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8 support in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            echo '<table border="1">';
            echo '<tr>
                    <th style="background-color: #f2f2f2;">ลำดับ</th>
                    <th style="background-color: #f2f2f2;">เลขที่การจอง</th>
                    <th style="background-color: #f2f2f2;">ชื่อผู้จอง</th>
                    <th style="background-color: #f2f2f2;">แผนก</th>
                    <th style="background-color: #f2f2f2;">วันเวลาเดินทาง</th>
                    <th style="background-color: #f2f2f2;">วันเวลาสิ้นสุด</th>
                    <th style="background-color: #f2f2f2;">ปลายทาง</th>
                    <th style="background-color: #f2f2f2;">อำเภอ</th>
                    <th style="background-color: #f2f2f2;">จังหวัด</th>
                    <th style="background-color: #f2f2f2;">ผู้โดยสาร</th>
                    <th style="background-color: #f2f2f2;">สถานะปฎิบัติงาน</th>
                    <th style="background-color: #f2f2f2;">สถานะคืนรถ</th>
                  </tr>';

            foreach ($bookings as $index => $item) {
                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . $item->booking_code . '</td>';
                echo '<td>' . ($item->user->first_name ?? '-') . ' ' . ($item->user->last_name ?? '') . '</td>';
                echo '<td>' . ($item->user->department->department_name ?? '-') . '</td>';
                echo '<td>' . date('d/m/Y H:i', strtotime($item->start_time)) . '</td>';
                echo '<td>' . date('d/m/Y H:i', strtotime($item->end_time)) . '</td>';
                echo '<td>' . $item->destination . '</td>';
                echo '<td>' . ($item->district ?? '-') . '</td>';
                echo '<td>' . ($item->province ?? '-') . '</td>';
                echo '<td>' . $item->passenger_count . '</td>';
                echo '<td>' . $item->status . '</td>';
                echo '<td>' . $item->return_status . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser) {
            return redirect()->route('bookingcar.welcome')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (เฉพาะแผนก HAMS หรือผู้ที่ได้รับอนุญาต)');
        }

        $query = BookingCar::with(['user', 'vehicle']);

        // Filtering
        if ($request->filled('transaction_date')) {
            $query->whereDate('created_at', $request->transaction_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            // Fetch User IDs from secondary DB to avoid cross-database join issues
            $userIds = User::where('first_name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->pluck('id');

            $query->where(function ($q) use ($search, $userIds) {
                $q->where('destination', 'like', '%' . $search . '%');
                if ($userIds->isNotEmpty()) {
                    $q->orWhereIn('user_id', $userIds);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        //ความเคลื่อนไหวล่าสุด
        $recentBookings = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Calculate stats (keeping global counts for the header cards)
        $totalBookings = BookingCar::count();
        $approvedBookings = BookingCar::where('status', 'อนุมัติแล้ว')->count();
        $pendingBookings = BookingCar::where('status', 'รออนุมัติ')->count();
        $rejectedBookings = BookingCar::where('status', 'ไม่อนุมัติ')->orWhere('status', 'ยกเลิก')->count();

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
            'start_time' => 'nullable|date',
            'start_date' => 'nullable|date',
            'start_time_only' => 'nullable',
            'end_time' => 'nullable|date',
            'end_date' => 'nullable|date',
            'end_time_only' => 'nullable',
            'mileage_before' => 'nullable|integer',
            'mileage_after' => 'nullable|integer',
            'note_returning' => 'nullable|string',
            'return_status' => 'required|string',
            'requester_name' => 'nullable|string|max:255',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'passenger_count' => 'nullable|integer|min:1',
            'purpose' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $booking->vehicle_id = $request->vehicle_id;
        $booking->destination = $request->destination;

        // Handle split date/time or unified datetime
        if ($request->filled('start_date') && $request->filled('start_time_only')) {
            $booking->start_time = \Carbon\Carbon::parse($request->start_date . ' ' . $request->start_time_only)->toDateTimeString();
        } elseif ($request->filled('start_time')) {
            $booking->start_time = \Carbon\Carbon::parse($request->start_time)->toDateTimeString();
        }

        if ($request->filled('end_date') && $request->filled('end_time_only')) {
            $booking->end_time = \Carbon\Carbon::parse($request->end_date . ' ' . $request->end_time_only)->toDateTimeString();
        } elseif ($request->filled('end_time')) {
            $booking->end_time = \Carbon\Carbon::parse($request->end_time)->toDateTimeString();
        }

        $booking->mileage_before = $request->mileage_before;
        $booking->mileage_after = $request->mileage_after;
        $booking->note_returning = $request->note_returning;
        $booking->return_status = $request->return_status;
        $booking->requester_name = $request->requester_name;
        $booking->district = $request->district;
        $booking->province = $request->province;
        $booking->passenger_count = $request->passenger_count;
        $booking->purpose = $request->purpose;
        $booking->status = $request->status;

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

        if ($booking->vehicle) {
            $booking->vehicle->syncMileage();
        }

        return redirect()->route('bookingcar.dashboard')->with('success', 'ปรับปรุงข้อมูลการจองเรียบร้อยแล้ว');
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

        // Prevent cancellation if the booking has already started
        if (now()->greaterThan(\Carbon\Carbon::parse($booking->start_time))) {
            return redirect()->back()->with('error', 'ไม่สามารถยกเลิกการจองที่เริ่มไปแล้วได้');
        }

        // Allow cancellation if not already canceled
        if ($booking->status !== 'ยกเลิก' && $booking->return_status !== 'ส่งคืนแล้ว') {
            // Delete main attachment if exists
            if ($booking->attachment && file_exists(public_path($booking->attachment))) {
                unlink(public_path($booking->attachment));
            }

            // Delete going attachments (stored as JSON)
            if ($booking->attachment_going) {
                $goingPaths = json_decode($booking->attachment_going, true);
                if (is_array($goingPaths)) {
                    foreach ($goingPaths as $path) {
                        if (file_exists(public_path($path))) {
                            unlink(public_path($path));
                        }
                    }
                }
            }

            // Delete returning attachments (stored as JSON)
            if ($booking->attachment_returning) {
                $returningPaths = json_decode($booking->attachment_returning, true);
                if (is_array($returningPaths)) {
                    foreach ($returningPaths as $path) {
                        if (file_exists(public_path($path))) {
                            unlink(public_path($path));
                        }
                    }
                }
            }

            // Clear paths in database and update status
            $booking->attachment = null;
            $booking->attachment_going = null;
            $booking->attachment_returning = null;
            $booking->status = 'ยกเลิก';
            $booking->save();

            return redirect()->back()->with('success', 'ยกเลิกการจองและลบไฟล์ประกอบเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถยกเลิกการจองนี้ได้');
    }

    public function returnCar(Request $request, $id)
    {
        $user = Auth::user();
        $booking = BookingCar::findOrFail($id);

        $isOwner = $user && $user->id === $booking->user_id;
        $isHams = $user && $user->department && $user->department->department_name === 'HAMS';
        $isSpecificUser = $user && $user->employee_code === '11648';

        if (!$isHams && !$isSpecificUser && !$isOwner) {
            return redirect()->back()->with('error', 'คุณไม่มีสิทธิ์ทำรายการนี้');
        }

        // Validate incoming data if it's a detailed return (from user modal)
        $request->validate([
            'mileage_before' => 'nullable|integer',
            'mileage_after' => 'nullable|integer',
            'note_returning' => 'nullable|string',
            'attachment_going' => 'nullable|array',
            'attachment_going.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'attachment_returning' => 'nullable|array',
            'attachment_returning.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
        ]);

        if ($booking->return_status !== 'ส่งคืนแล้ว') {
            $booking->return_status = 'ส่งคืนแล้ว';
            if (!$booking->returned_at) {
                $booking->returned_at = now();
            }

            // Save detailed info if provided
            if ($request->has('mileage_before'))
                $booking->mileage_before = $request->mileage_before;
            if ($request->has('mileage_after'))
                $booking->mileage_after = $request->mileage_after;
            if ($request->has('note_returning'))
                $booking->note_returning = $request->note_returning;

            // Handle file uploads (Going)
            if ($request->hasFile('attachment_going')) {
                $paths = json_decode($booking->attachment_going, true) ?: [];
                foreach ($request->file('attachment_going') as $file) {
                    $filename = time() . '_going_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                    $paths[] = 'uploads/bookingcar_attachments/' . $filename;
                }
                $booking->attachment_going = json_encode($paths);
            }

            // Handle file uploads (Returning)
            if ($request->hasFile('attachment_returning')) {
                $paths = json_decode($booking->attachment_returning, true) ?: [];
                foreach ($request->file('attachment_returning') as $file) {
                    $filename = time() . '_returning_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $file->move(public_path('uploads/bookingcar_attachments'), $filename);
                    $paths[] = 'uploads/bookingcar_attachments/' . $filename;
                }
                $booking->attachment_returning = json_encode($paths);
            }

            $booking->save();

            // Sync vehicle mileage after return
            if ($booking->vehicle) {
                $booking->vehicle->syncMileage();
            }

            return redirect()->back()->with('success', 'บันทึกสถานะการคืนรถและข้อมูลหลังการเดินทางเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'รถได้ถูกส่งคืนไปแล้ว');
    }

    public function getDistricts(Request $request)
    {
        $province = $request->query('province');
        if (!$province) {
            return response()->json([]);
        }

        $jsonPath = storage_path('app/thailand_geography.json');
        if (!file_exists($jsonPath)) {
            return response()->json([]);
        }

        $geoData = json_decode(file_get_contents($jsonPath), true);
        $districts = $geoData[$province] ?? [];

        return response()->json($districts);
    }
}
