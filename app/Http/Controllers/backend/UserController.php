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
        if (!in_array(Auth::user()->role, ['admin', 'editor', 'viewer'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = User::with(['department', 'hamsPermission', 'hamsPermissionLatestLog.grantedBy']);

        // Filtering Logic
        if ($request->filled('emp_code')) {
            $query->where('emp_code', 'like', '%' . trim($request->emp_code) . '%');
        }
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', '%' . $search . '%')
                  ->orWhere('lastname', 'like', '%' . $search . '%')
                  ->orWhere('emp_code', 'like', '%' . $search . '%')
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
            if ($request->status === 'online') {
                $onlineIds = [];
                $allUsers = User::pluck('id');
                foreach ($allUsers as $id) {
                    if (\Illuminate\Support\Facades\Cache::has('user-is-online-' . $id)) {
                        $onlineIds[] = $id;
                    }
                }
                // If no one is online, whereIn will return empty which is correct
                $query->whereIn('id', empty($onlineIds) ? [0] : $onlineIds);
            } elseif ($request->status === 'offline') {
                $onlineIds = [];
                $allUsers = User::pluck('id');
                foreach ($allUsers as $id) {
                    if (\Illuminate\Support\Facades\Cache::has('user-is-online-' . $id)) {
                        $onlineIds[] = $id;
                    }
                }
                if (!empty($onlineIds)) {
                    $query->whereNotIn('id', $onlineIds);
                }
            } else {
                $query->where('status', $request->status);
            }
        }
        if ($request->filled('department')) {
            $query->where('dept_id', $request->department);
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        // skip division/section filters as they don't exist in new schema

        $users = $query->get();

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
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action. Editor or Admin required.');
        }

        $request->validate([
            'emp_code' => 'required|unique:userkml2025.employees,emp_code',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'role' => 'sometimes|in:admin,editor,viewer',
            'status' => 'required|in:active,resign',
        ]);

        if (Auth::user()->role !== 'admin') {
            $request->request->remove('role'); // Editor cannot assign roles
        }

        $data = $request->except(['password', 'remember_token', 'role']);
        $data['role'] = 'staff'; // Default for the shared employees table
        $user = User::create($data);

        // Store actual HAMS role
        $role = $request->input('role', 'viewer');
        if ($role === 'admin' && Auth::user()->getRawOriginal('role') !== 'admin') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถมอบสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)'], 403);
            }
            return redirect()->back()->with('error', 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถมอบสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)');
        }

        \App\Models\HamsPermission::ensureRoleColumnExists();
        \App\Models\HamsPermission::updateOrCreate(
            ['user_id' => $user->id],
            ['role' => $role]
        );

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
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($id);
        
        $request->validate([
            'emp_code' => 'required|unique:userkml2025.employees,emp_code,' . $id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'role' => 'sometimes|in:admin,editor,viewer',
            'status' => 'required|in:active,resign',
        ]);

        if (Auth::user()->role !== 'admin') {
            $request->request->remove('role'); // Editor cannot assign roles
        }

        $data = $request->except(['password', 'remember_token', 'role']);
        // Don't modify the employees.role column
        $user->update($data);

        // Store actual HAMS role if provided
        if ($request->has('role')) {
            $role = $request->role;
            $oldRole = $user->role;
            $isChangingAdminRole = ($oldRole === 'admin' || $role === 'admin');

            if ($isChangingAdminRole && Auth::user()->getRawOriginal('role') !== 'admin') {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถปรับเปลี่ยนสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)'], 403);
                }
                return redirect()->back()->with('error', 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถปรับเปลี่ยนสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)');
            }
            \App\Models\HamsPermission::ensureRoleColumnExists();
            \App\Models\HamsPermission::updateOrCreate(
                ['user_id' => $user->id],
                ['role' => $role]
            );
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('users.index')->with('success', 'อัปเดตข้อมูลพนักงานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized action.');
        }

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

        // 1. My Parking Reservations
        $myReservations = \App\Models\parking\VisitorReservation::where('contact_user_id', $user->id)
            ->with(['contactUser', 'slot'])
            ->orderBy('checkin_datetime', 'desc')
            ->get();

        // 2. Pending Manager Approvals
        $managedDeptIds = \App\Models\Department::where('manager_id', $user->id)->pluck('id');
        $pendingManagerReservations = collect();
        if ($managedDeptIds->isNotEmpty()) {
            $managedUserIds = \App\Models\User::whereIn('dept_id', $managedDeptIds)->pluck('id');
            $pendingManagerReservations = \App\Models\parking\VisitorReservation::whereIn('contact_user_id', $managedUserIds)
                ->where('manager_approval', 'pending')
                ->with(['contactUser', 'slot'])
                ->orderBy('checkin_datetime', 'desc')
                ->get();
        }

        // 3. Pending HAMS Acknowledgement
        $pendingHamsReservations = collect();
        $isHamsAdmin = \App\Models\HamsPermission::where('user_id', $user->id)->value('is_hams_editor') || in_array($user->role, ['admin', 'editor']);
        if ($isHamsAdmin) {
            $pendingHamsReservations = \App\Models\parking\VisitorReservation::where('manager_approval', 'approved')
                ->where('hams_status', 'pending')
                ->with(['contactUser', 'slot'])
                ->orderBy('checkin_datetime', 'desc')
                ->get();
        }

        return view('backend.users.profile', compact('user', 'myReservations', 'pendingManagerReservations', 'pendingHamsReservations', 'isHamsAdmin'));
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
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

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

    public function updateRole(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action. Only Admin can update roles.'], 403);
        }

        $request->validate([
            'role' => 'required|in:admin,editor,viewer',
            'reason' => 'nullable|string|max:1000'
        ]);

        if ($request->role === 'admin' && Auth::user()->getRawOriginal('role') !== 'admin') {
            return response()->json(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถมอบสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)'], 403);
        }

        \App\Models\HamsPermission::ensureRoleColumnExists();
        $user = User::findOrFail($id);
        $permission = \App\Models\HamsPermission::firstOrCreate(['user_id' => $user->id]);
        $oldRole = $permission->role ?? 'viewer';
        $isChangingAdminRole = ($oldRole === 'admin' || $request->role === 'admin');

        if ($isChangingAdminRole && Auth::user()->getRawOriginal('role') !== 'admin') {
            return response()->json(['success' => false, 'message' => 'เฉพาะผู้ดูแลระบบหลัก (Central Admin) เท่านั้นที่สามารถปรับเปลี่ยนสิทธิ์ Admin ได้ (โปรดติดต่อฝ่าย IT)'], 403);
        }
        
        // Prevent changing own role to avoid locking oneself out
        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Cannot change your own role.'], 422);
        }

        $permission->role = $request->role;
        $permission->save();

        // Log the role change with reason
        \App\Models\HamsPermissionLog::ensureReasonColumnExists();
        \App\Models\HamsPermissionLog::create([
            'target_user_id' => $user->id,
            'granted_by_user_id' => Auth::id(),
            'action' => "changed role from {$oldRole} to {$request->role}",
            'reason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'อัปเดตสิทธิ์ผู้ใช้งานเรียบร้อยแล้ว'
        ]);
    }
}

