<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Reservation;
use App\Models\bookingmeeting\Rooms;
use Carbon\Carbon;

class BackendReportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->format('Y'));
        $month = $request->input('month', Carbon::now()->format('m'));
        $day = $request->input('day');

        $reservationQuery = Reservation::query();
        if ($year) {
            $reservationQuery->whereYear('reservation_date', $year);
        }
        if ($month) {
            $reservationQuery->whereMonth('reservation_date', $month);
        }
        if ($day) {
            $reservationQuery->whereDay('reservation_date', $day);
        }

        // Overall Stats for the selected period
        $stats = [
            'total_rooms' => Rooms::where('status', 1)->count(),
            'total_reservations' => (clone $reservationQuery)->count(),
            'acknowledged_reservations' => (clone $reservationQuery)->where('status', 'acknowledge')->count(),
            'rejected_reservations' => (clone $reservationQuery)->where('status', 'rejected')->count(),
            'cancelled_reservations' => (clone $reservationQuery)->where('status', 'cancelled')->count(),
        ];

        // Chart Data: Reservations grouped by room
        $roomStats = (clone $reservationQuery)->join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
            ->selectRaw('rooms.room_name, COUNT(reservations.reservation_id) as count')
            ->groupBy('rooms.room_id', 'rooms.room_name')
            ->pluck('count', 'room_name')
            ->toArray();

        // Details list for export / viewing
        $reservations = (clone $reservationQuery)->with(['user', 'room'])
            ->orderBy('reservation_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('backend.bookingmeeting.report.index', compact('stats', 'roomStats', 'reservations', 'year', 'month', 'day'));
    }
}
