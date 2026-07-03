<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $type = $request->query('type', 'policy');
        $policies = Policy::where('type', $type)->orderBy('order')->get();
        return view('backend.policy.index', compact('policies', 'type'));
    }


    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('backend.policy.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:policy,operation',
            'order' => 'nullable|integer',
        ]);

        Policy::create($validated);

        return redirect()->route('backend.policy.index', ['type' => $request->type ?? 'policy'])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


    public function edit(Policy $policy)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        return view('backend.policy.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:policy,operation',
            'order' => 'nullable|integer',
        ]);

        $policy->update($validated);

        return redirect()->route('backend.policy.index', ['type' => $request->type ?? 'policy'])->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }


    public function destroy(Policy $policy)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $policy->delete();
        return redirect()->route('backend.policy.index', ['type' => $policy->type])->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

}
