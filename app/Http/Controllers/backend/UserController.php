<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Department;
use App\Models\Division;
use App\Models\Section;
use App\Models\UserType;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['department', 'division', 'section', 'usertype']);

        // Filtering Logic
        if ($request->filled('employee_code')) {
            $query->where('employee_code', 'like', '%' . $request->employee_code . '%');
        }
        if ($request->filled('fullname')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->fullname . '%')
                  ->orWhere('last_name', 'like', '%' . $request->fullname . '%');
            });
        }
        if ($request->filled('position')) {
            $query->where('position', 'like', '%' . $request->position . '%');
        }
        if ($request->filled('employee_type')) {
            $query->where('employee_type', $request->employee_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }
        if ($request->filled('section')) {
            $query->where('section_id', $request->section);
        }
        if ($request->filled('level_user')) {
            $query->where('level_user', $request->level_user);
        }
        if ($request->filled('hr_status')) {
            $query->where('hr_status', $request->hr_status);
        }

        $perPage = $request->get('per_page', 50);
        $users = $query->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        $departments = Department::all();
        $divisions = Division::all();
        $sections = Section::all();
        $userTypes = UserType::all();

        return view('backend.users.index', compact('users', 'departments', 'divisions', 'sections', 'userTypes'));
    }

    public function create()
    {
        $departments = Department::all();
        $divisions = Division::all();
        $sections = Section::all();
        $userTypes = UserType::all();
        return view('backend.users.create', compact('departments', 'divisions', 'sections', 'userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required|unique:userskml,employee_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Add other validations as needed
        ]);

        $user = User::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return redirect()->route('users.index')->with('success', 'เพิ่มพนักงานเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        $user = User::with(['department', 'division', 'section', 'usertype'])->findOrFail($id);
        return view('backend.users.detail', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $divisions = Division::all();
        $sections = Section::all();
        $userTypes = UserType::all();
        return view('backend.users.edit', compact('user', 'departments', 'divisions', 'sections', 'userTypes'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'ลบพนักงานเรียบร้อยแล้ว');
    }
}
