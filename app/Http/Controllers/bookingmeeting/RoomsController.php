<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Rooms;

class RoomsController extends Controller
{
    public function index()
    {
        $rooms = Rooms::all();
        return view('bookingmeeting.rooms.index', compact('rooms'));
    }
}
