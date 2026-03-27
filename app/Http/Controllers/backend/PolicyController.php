<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'policy');
        $policies = Policy::where('type', $type)->orderBy('order')->get();
        return view('backend.policy.index', compact('policies', 'type'));
    }


    public function create()
    {
        return view('backend.policy.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:policy,operation',
            'order' => 'nullable|integer',
        ]);

        Policy::create($request->all());

        return redirect()->route('backend.policy.index', ['type' => $request->type ?? 'policy'])->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }


    public function edit(Policy $policy)
    {
        return view('backend.policy.edit', compact('policy'));
    }

    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:policy,operation',
            'order' => 'nullable|integer',
        ]);

        $policy->update($request->all());

        return redirect()->route('backend.policy.index', ['type' => $request->type ?? 'policy'])->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }


    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('backend.policy.index', ['type' => $policy->type])->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

}
