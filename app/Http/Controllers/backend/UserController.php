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
        $query = User::with(['department', 'hamsPermission', 'hamsPermissionLatestLog.grantedBy'])
            ->where('role', '!=', 'admin');

        // Filtering Logic
        if ($request->filled('emp_code')) {
            $query->where('emp_code', 'like', '%' . trim($request->emp_code) . '%');
        }
        if ($request->filled('fullname')) {
            $search = trim($request->fullname);
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%$search%"]);
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
            $query->where('dept_id', $request->department);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        // skip division/section filters as they don't exist in new schema

        $perPage = $request->get('per_page', 50);
        $users = $query->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json($users);
        }

        $departments = Department::all();
        $divisions = collect([]); // No divisions table in appkum_user
        $sections = collect([]);  // No sections table in appkum_user
        $userTypes = collect([]); // No user_types table in appkum_user

        return view('backend.users.index', compact('users', 'departments', 'divisions', 'sections', 'userTypes'));
    }

    public function create()
    {
        $departments = Department::all();
        $divisions = collect([]);
        $sections = collect([]);
        $userTypes = collect([]);
        return view('backend.users.create', compact('departments', 'divisions', 'sections', 'userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_code' => 'required|unique:userkml2025.employees,emp_code',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'role' => 'required|in:admin,staff',
            'status' => 'required|in:active,resign',
        ]);

        $user = User::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return redirect()->route('users.index')->with('success', 'เพิ่มพนักงานเรียบร้อยแล้ว');
    }

    public function show($id)
    {
        $user = User::with(['department'])->findOrFail($id);
        return view('backend.users.detail', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $departments = Department::all();
        $divisions = collect([]);
        $sections = collect([]);
        $userTypes = collect([]);
        return view('backend.users.edit', compact('user', 'departments', 'divisions', 'sections', 'userTypes'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'emp_code' => 'required|unique:userkml2025.employees,emp_code,' . $id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'role' => 'required|in:admin,staff',
            'status' => 'required|in:active,resign',
        ]);

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

    public function profileUser()
    {
        $user = Auth::user();
        $user->load(['department']);
        return view('backend.users.profile', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        \Log::info('Update Avatar Started');
        try {
            $fileKey = $request->hasFile('avatar') ? 'avatar' : 'photo_user';
            \Log::info('File Key: ' . $fileKey);
            
            $request->validate([
                $fileKey => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $user = Auth::user();

            if ($request->hasFile($fileKey)) {
                // Delete old photo if exists
                if ($user->profile_pic && file_exists(public_path($user->profile_pic))) {
                    @unlink(public_path($user->profile_pic));
                }

                $imageName = time() . '_' . $user->emp_code . '.' . $request->file($fileKey)->extension();
                $request->file($fileKey)->move(public_path('images/users'), $imageName);
                
                $user->profile_pic = 'images/users/' . $imageName;
                $user->save();

                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'avatar_url' => asset($user->profile_pic),
                        'message' => 'อัปเดตรูปประจำตัวสำเร็จแล้ว'
                    ]);
                }

                return back()->with('success', 'อัปเดตรูปประจำตัวสำเร็จแล้ว');
            }

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'ถิดพลาดในการอัปเดตรูปประจำตัว']);
            }

            return back()->with('error', 'ถิดพลาดในการอัปเดตรูปประจำตัว');
        } catch (\Exception $e) {
            \Log::error('Avatar Upload Error: ' . $e->getMessage());
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function toggleHamsEditor(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        $permission = \App\Models\HamsPermission::firstOrCreate(['user_id' => $user->id]);
        $oldValue = $permission->is_hams_editor ?? false;
        $permission->is_hams_editor = !$oldValue;
        $permission->save();

        // Log the change
        \App\Models\HamsPermissionLog::create([
            'target_user_id' => $user->id,
            'granted_by_user_id' => $currentUser->id,
            'action' => $permission->is_hams_editor ? 'granted' : 'revoked'
        ]);

        return response()->json([
            'success' => true,
            'is_hams_editor' => $permission->is_hams_editor,
            'grantor_name' => $currentUser->fullname,
            'message' => 'ปรับปรุงสิทธิ์ HAMS Editor เรียบร้อยแล้ว'
        ]);
    }
}

