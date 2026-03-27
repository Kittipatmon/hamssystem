<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Division;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('division')->get();
        $divisions = Division::all();
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
            'division_id' => 'required',
            'department_name' => 'required|string|max:255',
            'department_status' => 'required',
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
