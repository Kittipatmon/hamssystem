<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Reservation;
use App\Models\bookingmeeting\Rooms;

class BackendReservationsController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'room']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('topic', 'like', '%' . $search . '%')
                    ->orWhere('requester_name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('employee_code', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('room', function ($qr) use ($search) {
                        $qr->where('room_name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        // Note: DB enum values are: pending, acknowledge, rejected, cancelled

        $reservations = $query->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('backend.bookingmeeting.reservations.index', compact('reservations'));
    }

    public function create()
    {
        // Admins might book from the frontend or an admin booking form. Can implement later if needed.
        return redirect()->route('backend.bookingmeeting.reservations.index')->with('error', 'กรุณาทำการจองห้องประชุมผ่านหน้าผู้ใช้งาน');
    }

    public function store(Request $request)
    {
        // 
    }

    public function show(string $id)
    {
        $reservation = Reservation::with(['user', 'room'])->findOrFail($id);
        return view('backend.bookingmeeting.reservations.show', compact('reservation'));
    }

    public function edit(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        $rooms = Rooms::where('status', 1)->get();
        return view('backend.bookingmeeting.reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'room_id' => 'required|exists:rooms,room_id',
            'reservation_date' => 'required|date',
            'reservation_dateend' => 'required|date|after_or_equal:reservation_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'topic' => 'required|string|max:255',
            'status' => 'required|in:pending,acknowledge,rejected,cancelled'
        ]);

        $newStart = $request->reservation_date . ' ' . $request->start_time . ':00';
        $newEnd = $request->reservation_dateend . ' ' . $request->end_time . ':00';

        // Additional validation for same-day start/end time
        if ($request->reservation_date === $request->reservation_dateend && $request->end_time <= $request->start_time) {
            return redirect()->back()->with('error', 'เวลาสิ้นสุดต้องอยู่หลังเวลาเริ่มต้นสำหรับการจองในวันเดียวกัน')->withInput();
        }

        // Check for conflicts (excluding the current reservation)
        $conflict = Reservation::where('room_id', $request->room_id)
            ->where('reservation_id', '!=', $id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->whereRaw("CONCAT(reservation_date, ' ', start_time) < ?", [$newEnd])
                      ->whereRaw("CONCAT(COALESCE(reservation_dateend, reservation_date), ' ', end_time) > ?", [$newStart]);
            })->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'ห้องประชุมนี้ถูกจองในช่วงเวลาดังกล่าวแล้ว')->withInput();
        }

        $reservation->update([
            'room_id' => $request->room_id,
            'reservation_date' => $request->reservation_date,
            'reservation_dateend' => $request->reservation_dateend,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'topic' => $request->topic,
            'status' => $request->status,
            'participant_count' => $request->participant_count,
            'requester_name' => $request->requester_name
        ]);

        return redirect()->route('backend.bookingmeeting.reservations.index')->with('success', 'ปรับปรุงข้อมูลการจองสำเร็จ');
    }

    public function destroy(string $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->attached_file && file_exists(public_path('documents/reservations/' . $reservation->attached_file))) {
            unlink(public_path('documents/reservations/' . $reservation->attached_file));
        }
        if ($reservation->budget_file && file_exists(public_path('documents/reservations/' . $reservation->budget_file))) {
            unlink(public_path('documents/reservations/' . $reservation->budget_file));
        }

        $reservation->delete();
        return redirect()->route('backend.bookingmeeting.reservations.index')->with('success', 'ลบข้อมูลการจองห้องประชุมสำเร็จ');
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,acknowledge,rejected,cancelled'
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        $msg = '';
        switch ($request->status) {
            case 'acknowledge': $msg = 'อนุมัติการจองห้องประชุมเรียบร้อยแล้ว'; break;
            case 'rejected': $msg = 'ปฏิเสธการจองห้องประชุมเรียบร้อยแล้ว'; break;
            case 'cancelled': $msg = 'ยกเลิกการจองห้องประชุมเรียบร้อยแล้ว'; break;
            default: $msg = 'อัปเดตสถานะการจองเรียบร้อยแล้ว';
        }

        return redirect()->route('backend.bookingmeeting.reservations.index')->with('success', $msg);
    }
}
