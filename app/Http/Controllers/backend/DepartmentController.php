<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Division;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

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
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'division_id' => 'required',
            'department_name' => 'required|string|max:255',
        ]);

        Department::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'สร้างแผนกใหม่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'อัปเดตแผนกเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

        $department = Department::findOrFail($id);
        $department->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'ลบแผนกเรียบร้อยแล้ว');
    }

    public function managers(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'editor']) && !in_array(Auth::user()->dept_id, [14, 16])) {
            abort(403, 'Unauthorized action.');
        }

        $query = Department::with('manager');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        $departments = $query->get();

        if ($request->ajax()) {
            return response()->json($departments);
        }

        return view('backend.department.managers', compact('departments'));
    }
}
