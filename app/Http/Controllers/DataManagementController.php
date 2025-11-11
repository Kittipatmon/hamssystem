<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataManagementController extends Controller
{
    public function welcomeDataManagement()
    {
        return view('datamanage.welcomedatamanage');
    }
}