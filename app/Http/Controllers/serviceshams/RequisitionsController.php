<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequisitionsController extends Controller
{
    public function welcomeService()
    {
        return view('serviceshams.welcomeservice');
    }
}
