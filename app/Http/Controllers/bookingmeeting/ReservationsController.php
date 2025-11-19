<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    public function welcomeReservations()
    {
        return view('bookingmeeting.welcomemeeting');
    }
}
