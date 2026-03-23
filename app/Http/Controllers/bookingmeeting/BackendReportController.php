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
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Overall Stats for the selected period
        $stats = [
            'total_rooms' => Rooms::where('status', 1)->count(),
            'total_reservations' => Reservation::whereBetween('reservation_date', [$startDate, $endDate])->count(),
            'acknowledged_reservations' => Reservation::whereBetween('reservation_date', [$startDate, $endDate])->where('status', 'acknowledge')->count(),
            'rejected_reservations' => Reservation::whereBetween('reservation_date', [$startDate, $endDate])->where('status', 'rejected')->count(),
            'cancelled_reservations' => Reservation::whereBetween('reservation_date', [$startDate, $endDate])->where('status', 'cancelled')->count(),
        ];

        // Chart Data: Reservations grouped by room
        $roomStats = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.room_id')
            ->selectRaw('rooms.room_name, COUNT(reservations.reservation_id) as count')
            ->whereBetween('reservations.reservation_date', [$startDate, $endDate])
            ->groupBy('rooms.room_id', 'rooms.room_name')
            ->pluck('count', 'room_name')
            ->toArray();

        // Details list for export / viewing
        $reservations = Reservation::with(['user', 'room'])
            ->whereBetween('reservation_date', [$startDate, $endDate])
            ->orderBy('reservation_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('backend.bookingmeeting.report.index', compact('stats', 'roomStats', 'reservations', 'startDate', 'endDate'));
    }
}
