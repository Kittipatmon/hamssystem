<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserType;

class UserTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = UserType::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('type_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        try {
            $userTypes = $query->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $userTypes = collect();
        }

        if ($request->ajax()) {
            return response()->json($userTypes);
        }

        return view('backend.usertypes.index', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        UserType::create([
            'type_name' => $request->type_name,
            'description' => $request->description,
            'status' => 0, // Default to active
        ]);

        return redirect()->route('usertypes.index')->with('success', 'เพิ่มระดับพนักงานเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $userType = UserType::findOrFail($id);
        $userType->update($request->all());

        return redirect()->route('usertypes.index')->with('success', 'อัปเดตระดับพนักงานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $userType = UserType::findOrFail($id);
        $userType->delete();

        return redirect()->route('usertypes.index')->with('success', 'ลบระดับพนักงานเรียบร้อยแล้ว');
    }
}
