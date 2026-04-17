<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Division;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $departments = $query->get();

        if ($request->ajax()) {
            return response()->json($departments);
        }

        $divisions = collect([]);
        return view('backend.department.index', compact('departments', 'divisions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_id' => 'required',
            'department_name' => 'required|string|max:255',
        ]);

        Department::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'สร้างแผนกใหม่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $department->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'อัปเดตแผนกเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'ลบแผนกเรียบร้อยแล้ว');
    }
}
