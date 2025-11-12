<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\serviceshams\Requisitions;
use App\Models\serviceshams\Requisition_items;
use Illuminate\Support\Facades\Auth;

class RequisitionsController extends Controller
{
    public function welcomeService()
    {
        return view('serviceshams.welcomeservice');
    }

    public function ReqlistPending()
    {
         $requisitions = Requisitions::where('approve_status', Requisitions::APPROVE_STATUS_PENDING)
            ->where('status', Requisitions::STATUS_PENDING)
            ->where('requester_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $requisition_items = Requisition_items::with('item')->get();
        return view('serviceshams.requisitions.reqpending', compact('requisitions', 'requisition_items'));
    }

    public function ReqlistAll()
    {
        $requisitions = Requisitions::where('requester_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $requisition_items = Requisition_items::with('item')->get();    
        return view('serviceshams.requisitions.reqlistall', compact('requisitions', 'requisition_items'));
    }
}
