<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Rooms;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Rooms::with(['reservations' => function ($query) {
            $query->where('status', '!=', 'cancelled')
                ->where(function ($q) {
                    $q->whereRaw("CONCAT(COALESCE(reservation_dateend, reservation_date), ' ', end_time) >= ?", [now()->toDateTimeString()]);
                })
                ->with('user')
                ->orderBy('reservation_date')
                ->orderBy('start_time');
        }])->get();
        
        // Find room IDs that currently have an active approved (acknowledge) meeting
        $occupiedRoomIds = \App\Models\bookingmeeting\Reservation::where('status', 'acknowledge')
            ->where(function ($query) {
                $query->whereRaw("CONCAT(reservation_date, ' ', start_time) <= ?", [now()->toDateTimeString()])
                    ->whereRaw("CONCAT(COALESCE(reservation_dateend, reservation_date), ' ', end_time) >= ?", [now()->toDateTimeString()]);
            })
            ->pluck('room_id')
            ->toArray();

        return view('bookingmeeting.rooms.index', compact('rooms', 'occupiedRoomIds'));
    }
}
