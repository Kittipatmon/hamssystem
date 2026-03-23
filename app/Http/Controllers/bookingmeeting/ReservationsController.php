<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Rooms;
use App\Models\bookingmeeting\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    public function welcomeReservations()
    {
        $rooms = Rooms::where('status', 1)->get();
        // Limit the number of rooms displayed on the sidebar to 3
        $sidebarRooms = Rooms::where('status', 1)->take(3)->get();
        return view('bookingmeeting.welcomemeeting', compact('rooms', 'sidebarRooms'));
    }

    public function events()
    {
        $reservations = Reservation::with(['user', 'room'])
            ->where('status', '!=', 'cancelled')
            ->get();

        $events = $reservations->map(function ($res) {
            // Mapping times to ISO 8601 for FullCalendar
            $start = $res->reservation_date . 'T' . $res->start_time;
            $endDate = $res->reservation_dateend ?? $res->reservation_date;
            $end = $endDate . 'T' . $res->end_time;

            return [
                'id' => $res->reservation_id,
                'title' => ($res->room ? $res->room->room_name : 'Room') . ' - ' . ($res->user ? $res->user->employee_code : 'User'),
                'start' => $start,
                'end' => $end,
                'extendedProps' => [
                    'topic' => $res->topic,
                    'first_name' => $res->user ? $res->user->first_name : '',
                    'last_name' => $res->user ? $res->user->last_name : '',
                    'room_id' => $res->room_id,
                    'user_id' => $res->user_id,
                    'start_time' => $res->start_time,
                    'end_time' => $res->end_time,
                    'participant_count' => $res->participant_count,
                    'requester_name' => $res->requester_name
                ],
                'backgroundColor' => $res->color ?? '#dc2626',
                'borderColor' => $res->color ?? '#dc2626'
            ];
        });

        return response()->json($events);
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // ลบไฟล์ที่เกี่ยวข้องถ้ามี
        if (!empty($reservation->attached_file)) {
            $attachedPath = public_path('documents/reservations/' . $reservation->attached_file);
            if (file_exists($attachedPath)) {
                unlink($attachedPath);
            }
        }
        if (!empty($reservation->budget_file)) {
            $budgetPath = public_path('documents/reservations/' . $reservation->budget_file);
            if (file_exists($budgetPath)) {
                unlink($budgetPath);
            }
        }

        // เปลี่ยนสถานะเป็นยกเลิก (soft cancel) แทนการลบข้อมูล
        $reservation->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'ยกเลิกการจองเรียบร้อยแล้ว']);
    }

    public function store(Request $request)
    {
        // dd('color submitted:', $request->color, 'all input:', $request->only(['color', 'start_time', 'end_time']));
        $request->validate([
            'room_id' => 'required|exists:rooms,room_id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_dateend' => 'required|date|after_or_equal:reservation_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'topic' => 'required|string',
            'participant_count' => 'nullable|integer',
            'objective' => 'nullable|string',
            'details' => 'nullable|string',
            'requester_name' => 'nullable|string',
            'attached_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
            'budget_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,png|max:5120',
            'break_morning' => 'nullable|boolean',
            'lunch' => 'nullable|boolean',
            'break_afternoon' => 'nullable|boolean',
            'dinner' => 'nullable|boolean',
            'break_morning_detail' => 'nullable|string',
            'lunch_detail' => 'nullable|string',
            'break_afternoon_detail' => 'nullable|string',
            'dinner_detail' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ], [
            'budget_file.required' => 'กรุณาแนบไฟล์งบประมาณ',
            'room_id.required' => 'กรุณาเลือกห้องประชุม',
            'topic.required' => 'กรุณาระบุหัวข้อการประชุม',
            'reservation_dateend.after_or_equal' => 'วันที่สิ้นสุดต้องไม่ก่อนวันที่เริ่มจอง',
        ]);

        $newStart = $request->reservation_date . ' ' . $request->start_time . ':00';
        $newEnd = $request->reservation_dateend . ' ' . $request->end_time . ':00';

        // Additional validation for same-day start/end time
        if ($request->reservation_date === $request->reservation_dateend && $request->end_time <= $request->start_time) {
            return redirect()->back()->with('error', 'เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่มต้นสำหรับการจองในวันเดียวกัน')->withInput();
        }

        // Check for conflicts using full datetime comparison
        $conflict = Reservation::where('room_id', $request->room_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->whereRaw("CONCAT(reservation_date, ' ', start_time) < ?", [$newEnd])
                      ->whereRaw("CONCAT(COALESCE(reservation_dateend, reservation_date), ' ', end_time) > ?", [$newStart]);
            })->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'ห้องประชุมนี้ถูกจองในช่วงเวลาดังกล่าวแล้ว')->withInput();
        }

        // Handle File Uploads
        $attachedFilename = null;
        if ($request->hasFile('attached_file')) {
            $file = $request->file('attached_file');
            $attachedFilename = 'AF_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documents/reservations'), $attachedFilename);
        }

        $budgetFilename = null;
        if ($request->hasFile('budget_file')) {
            $file = $request->file('budget_file');
            $budgetFilename = 'BF_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('documents/reservations'), $budgetFilename);
        }

        $resCode = 'RES-' . strtoupper(uniqid());

        $startTime = $request->start_time;
        $endTime = $request->end_time;

        Reservation::create([
            'reservation_code' => $resCode,
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'reservation_date' => $request->reservation_date,
            'reservation_dateend' => $request->reservation_dateend,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'topic' => $request->topic,
            'objective' => $request->objective,
            'details' => $request->details,
            'participant_count' => $request->participant_count ?? 1,
            'requester_name' => $request->requester_name,
            'attached_file' => $attachedFilename,
            'budget_file' => $budgetFilename,
            'break_morning' => $request->break_morning ? 1 : 0,
            'lunch' => $request->lunch ? 1 : 0,
            'break_afternoon' => $request->break_afternoon ? 1 : 0,
            'dinner' => $request->dinner ? 1 : 0,
            'break_morning_detail' => $request->break_morning_detail,
            'lunch_detail' => $request->lunch_detail,
            'break_afternoon_detail' => $request->break_afternoon_detail,
            'dinner_detail' => $request->dinner_detail,
            'color' => $request->color ?? '#dc2626',
            'status' => 'pending'
        ]);

        return redirect()->route('reservations.welcomemeeting')->with('success', 'ส่งคำร้องขอจองห้องประชุมเรียบร้อยแล้ว');
    }
}
