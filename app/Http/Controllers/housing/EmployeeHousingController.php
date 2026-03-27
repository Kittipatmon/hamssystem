<?php

namespace App\Http\Controllers\housing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\housing\Residence;

use App\Models\housing\ResidenceRoom;
use App\Models\housing\ResidenceRequest;
use App\Models\housing\ResidenceDependent;
use App\Models\housing\ResidenceAgreement;
use App\Models\housing\ResidentGuestRequest;
use App\Models\housing\ResidentGuestMember;
use App\Models\housing\ResidenceLeave;
use App\Models\housing\ResidenceStay;

class EmployeeHousingController extends Controller
{
    // ==================== WELCOME / LANDING PAGE ====================
    public function welcome()
    {
        $residences = Residence::with('rooms')->get();

        $recentRequests = ResidenceRequest::with('user')
            ->orderBy('created_at', 'desc')->take(5)->get();
        $recentAgreements = ResidenceAgreement::with('user')
            ->orderBy('created_at', 'desc')->take(5)->get();
        $recentGuests = ResidentGuestRequest::with('user')
            ->orderBy('created_at', 'desc')->take(5)->get();
        $recentLeaves = ResidenceLeave::with('user')
            ->orderBy('created_at', 'desc')->take(5)->get();

        $totalRooms = ResidenceRoom::count();
        $availableRooms = ResidenceRoom::where('residence_room_status', 0)->count();
        $occupiedRooms = ResidenceRoom::where('residence_room_status', 1)->count();
        $pendingRequests = ResidenceRequest::where('send_status', 0)->count()
            + ResidenceAgreement::where('send_status', 0)->count()
            + ResidentGuestRequest::where('send_status', 0)->count()
            + ResidenceLeave::where('send_status', 0)->count();
        $activeResidents = ResidenceStay::where('is_current', 1)->count();

        $activeResidents = ResidenceStay::where('is_current', 1)->count();

        // Check for missing agreement notification
        $needsAgreement = false;
        if (Auth::check()) {
            $userRequest = ResidenceRequest::where('user_id', Auth::id())
                ->where('send_status', 3)
                ->first();
            if ($userRequest) {
                $hasAgreement = ResidenceAgreement::where('user_id', Auth::id())->exists();
                if (!$hasAgreement) {
                    $needsAgreement = true;
                }
            }
        }

        return view('backend.housing.welcome', compact(
            'residences',
            'recentRequests',
            'recentAgreements',
            'recentGuests',
            'recentLeaves',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'pendingRequests',
            'activeResidents',
            'needsAgreement'
        ));
    }

    // ==================== HOUSE LIST (ROOM GRID) ====================
    public function houselist()
    {
        $totalRooms = ResidenceRoom::count();
        $availableRooms = ResidenceRoom::where('residence_room_status', 0)->count();
        $occupiedRooms = ResidenceRoom::where('residence_room_status', 1)->count();
        $maintenanceRooms = ResidenceRoom::where('residence_room_status', 2)->count();

        $residences = Residence::with(['rooms' => function ($q) {
            $q->orderBy('floor')->orderBy('room_number');
        }, 'rooms.stays' => function ($q) {
            $q->where('is_current', 1);
        }])->get();

        // Fetch eligible requesters (Approved by Committee but no room assigned)
        $eligibleRequesters = ResidenceRequest::where('send_status', 2)->get();

        return view('backend.housing.houselist', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'residences',
            'eligibleRequesters'
        ));
    }

    // ==================== HOUSING REQUEST (QF-HAMS-02) ====================
    public function requestForm()
    {
        $residences = Residence::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->load(['department', 'division', 'section']);
        }
        return view('backend.housing.form.request_form', compact('residences', 'user'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'site' => 'required|string',
            'title' => 'required|string',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'position' => 'required|string',
            'department' => 'required|string',
            'section' => 'required|string',
            'phone' => 'required|string',
            'marital_status' => 'required|string',
            'address_original' => 'required|string',
            'residence_reason' => 'required|string',
        ]);

        $lastId = ResidenceRequest::max('id') ?? 0;
        $code = 'RR-' . date('ym') . sprintf('%02d', ($lastId % 100) + 1);

        $filePaths = [];
        if ($request->hasFile('requests_file')) {
            foreach ($request->file('requests_file') as $file) {
                $filename = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/housing_requests'), $filename);
                $filePaths[] = $filename;
            }
        }

        $housingRequest = ResidenceRequest::create([
            'requests_code' => $code,
            'request_date' => now()->toDateString(),
            'site' => $request->site,
            'title' => $request->title,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'department' => $request->department,
            'section' => $request->section,
            'age_work' => $request->age_work,
            'phone' => $request->phone,
            'marital_status' => $request->marital_status,
            'address_original' => $request->address_original,
            'address_original_subdistrict' => $request->address_original_subdistrict,
            'address_original_district' => $request->address_original_district,
            'address_original_province' => $request->address_original_province,
            'address_current' => $request->address_current,
            'address_current_subdistrict' => $request->address_current_subdistrict,
            'address_current_district' => $request->address_current_district,
            'address_current_province' => $request->address_current_province,
            'current_house_type' => $request->current_house_type,
            'spouse_name' => $request->spouse_name,
            'spouse_occupation' => $request->spouse_occupation,
            'spouse_phone' => $request->spouse_phone,
            'workplace_spouse' => $request->workplace_spouse,
            'number_of_residents' => $request->number_of_residents,
            'residence_reason' => $request->residence_reason,
            'requests_file' => !empty($filePaths) ? json_encode($filePaths) : null,
            'send_status' => 0,
            'user_id' => Auth::id(),
        ]);

        // Save dependents
        if ($request->has('dep_name')) {
            foreach ($request->dep_name as $i => $name) {
                if (!empty($name)) {
                    ResidenceDependent::create([
                        'request_id' => $housingRequest->id,
                        'full_name' => $name,
                        'age' => $request->dep_age[$i] ?? null,
                        'relation' => $request->dep_relation[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('housing.welcome')->with('success', 'ส่งคำร้องขอเข้าพักบ้านพักเรียบร้อยแล้ว');
    }

    // ==================== AGREEMENT (QF-HAMS-03) ====================
    public function agreementForm()
    {
        $residences = Residence::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        $userStay = null;

        if ($user) {
            $user->load(['department', 'division', 'section']);
            
            // Check for current stay to auto-fill
            $userStay = \App\Models\housing\ResidenceStay::with(['room.residence'])
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();
        }
        return view('backend.housing.form.agreement_form', compact('residences', 'user', 'userStay'));
    }

    public function storeAgreement(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'full_name' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
        ]);

        $lastId = ResidenceAgreement::max('agreement_id') ?? 0;
        $code = 'RA-' . date('ym') . sprintf('%02d', ($lastId % 100) + 1);

        ResidenceAgreement::create([
            'agreement_code' => $code,
            'user_id' => Auth::id(),
            'agreement_date' => now()->toDateString(),
            'title' => $request->title,
            'full_name' => $request->full_name,
            'position' => $request->position,
            'department' => $request->department,
            'section' => $request->section,
            'residence_address' => $request->residence_address,
            'residence_floor' => $request->residence_floor,
            'number_of_residents' => $request->number_of_residents,
            'send_status' => 0,
        ]);

        return redirect()->route('housing.welcome')->with('success', 'ส่งข้อตกลงเข้าพักเรียบร้อยแล้ว');
    }

    // ==================== GUEST REQUEST (QF-HAMS-05) ====================
    public function guestForm()
    {
        $residences = Residence::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->load(['department', 'division', 'section']);
        }
        return view('backend.housing.form.guest_form', compact('residences', 'user'));
    }

    public function storeGuest(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'residence_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $lastId = ResidentGuestRequest::max('resident_guest_id') ?? 0;
        $code = 'RQ-' . date('ym') . sprintf('%02d', ($lastId % 100) + 1);

        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $guestRequest = ResidentGuestRequest::create([
            'resident_guest_code' => $code,
            'user_id' => Auth::id(),
            'request_date' => now()->toDateString(),
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'department' => $request->department,
            'section' => $request->section,
            'residence_type' => $request->residence_type,
            'room_number' => $request->room_number,
            'relationship' => $request->relationship,
            'start_date' => $request->start_date,
            'start_time' => $request->start_time,
            'end_date' => $request->end_date,
            'end_time' => $request->end_time,
            'total_days' => $totalDays,
            'send_status' => 0,
        ]);

        // Save guest members
        if ($request->has('guest_name')) {
            foreach ($request->guest_name as $i => $name) {
                if (!empty($name)) {
                    ResidentGuestMember::create([
                        'guest_request_id' => $guestRequest->resident_guest_id,
                        'full_name' => $name,
                        'age' => $request->guest_age[$i] ?? null,
                        'relation' => $request->guest_relation[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('housing.welcome')->with('success', 'ส่งคำขอนำญาติเข้าพักเรียบร้อยแล้ว');
    }

    // ==================== LEAVE/MOVE-OUT REQUEST ====================
    public function leaveForm()
    {
        $residences = Residence::all();
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $user->load(['department', 'division', 'section']);
        }
        return view('backend.housing.form.leave_form', compact('residences', 'user'));
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'residence_type' => 'required|string',
            'room_number' => 'required|string',
            'move_out_date' => 'required|date',
            'reason' => 'required|string',
        ]);

        $lastId = ResidenceLeave::max('residence_leaves_id') ?? 0;
        $code = 'RL-' . date('ym') . sprintf('%02d', ($lastId % 100) + 1);

        ResidenceLeave::create([
            'residence_leaves_code' => $code,
            'user_id' => Auth::id(),
            'request_date' => now()->toDateString(),
            'prefix' => $request->prefix,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'position' => $request->position,
            'department' => $request->department,
            'section' => $request->section,
            'residence_type' => $request->residence_type,
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'move_out_date' => $request->move_out_date,
            'reason' => $request->reason,
            'send_status' => 0,
        ]);

        return redirect()->route('housing.welcome')->with('success', 'ส่งคำร้องขอย้ายออกเรียบร้อยแล้ว');
    }

    // ==================== MANAGEMENT TABLE ====================
    public function management(Request $request)
    {
        $tab = $request->get('tab', 'requests');

        $requests = ResidenceRequest::with('user')->orderBy('created_at', 'desc');
        $agreements = ResidenceAgreement::with('user')->orderBy('created_at', 'desc');
        $guests = ResidentGuestRequest::with(['user', 'members'])->orderBy('created_at', 'desc');
        $leaves = ResidenceLeave::with('user')->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $requests->where(function ($q) use ($search) {
                $q->where('requests_code', 'like', "%$search%")
                    ->orWhere('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
            $agreements->where(function ($q) use ($search) {
                $q->where('agreement_code', 'like', "%$search%")
                    ->orWhere('full_name', 'like', "%$search%");
            });
            $guests->where(function ($q) use ($search) {
                $q->where('resident_guest_code', 'like', "%$search%")
                    ->orWhere('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
            $leaves->where(function ($q) use ($search) {
                $q->where('residence_leaves_code', 'like', "%$search%")
                    ->orWhere('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $statusVal = (int) $request->status;
            $requests->where('send_status', $statusVal);
            $agreements->where('send_status', $statusVal);
            $guests->where('send_status', $statusVal);
            $leaves->where('send_status', $statusVal);
        }

        $approvers = User::where('status', '0')->where('level_user', '>=', '2')->get();

        return view('backend.housing.management', [
            'tab' => $tab,
            'requests' => $requests->paginate(10, ['*'], 'requests_page')->withQueryString(),
            'agreements' => $agreements->paginate(10, ['*'], 'agreements_page')->withQueryString(),
            'guests' => $guests->paginate(10, ['*'], 'guests_page')->withQueryString(),
            'leaves' => $leaves->paginate(10, ['*'], 'leaves_page')->withQueryString(),
            'approvers' => $approvers,
        ]);
    }

    public function updateApprover(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'approver_level' => 'required|string', // commander, manager, committee
            'approver_id' => 'required|integer',
        ]);

        switch ($request->type) {
            case 'request': $item = ResidenceRequest::findOrFail($request->id); break;
            case 'agreement': $item = ResidenceAgreement::findOrFail($request->id); break;
            case 'guest': $item = ResidentGuestRequest::findOrFail($request->id); break;
            case 'leave': $item = ResidenceLeave::findOrFail($request->id); break;
            default: return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $column = '';
        if ($request->approver_level === 'commander') $column = 'commander_id';
        elseif ($request->approver_level === 'manager') $column = 'managerhams_id';
        elseif ($request->approver_level === 'committee') $column = 'Committee_id';
        
        if ($column) {
            $item->update([$column => $request->approver_id]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function assignRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'request_id' => 'required|integer',
        ]);

        $resReq = ResidenceRequest::findOrFail($request->request_id);
        $room = ResidenceRoom::findOrFail($request->room_id);

        if ($room->residence_room_status != 0) {
            return response()->json(['success' => false, 'message' => 'Room is not available']);
        }

        // Update Room
        $room->update(['residence_room_status' => 1]);

        // Update Request
        $resReq->update(['send_status' => 3]);

        // Create Stay
        ResidenceStay::create([
            'residence_room_id' => $room->residence_room_id,
            'residence_resident_id' => $resReq->user_id,
            'is_current' => 1,
            'check_in' => now(),
            'residence_stay_date' => now(),
            'user_createdid' => Auth::id()
        ]);

        return response()->json(['success' => true]);
    }

    public function roomDetail($id)
    {
        $room = ResidenceRoom::with(['residence', 'stays' => function ($q) {
            $q->where('is_current', 1);
        }])->findOrFail($id);

        $currentStay = $room->stays->first();
        
        // Fetch eligible requesters (Approved by Committee but no room assigned)
        $eligibleRequesters = ResidenceRequest::where('send_status', 2)->get();

        return view('backend.housing.housinglist_detail', compact('room', 'currentStay', 'eligibleRequesters'));
    }

    public function myRequests()
    {
        $userId = Auth::id();
        $requests = ResidenceRequest::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $agreements = ResidenceAgreement::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $guests = ResidentGuestRequest::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $leaves = ResidenceLeave::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('backend.housing.my_requests', compact('requests', 'agreements', 'guests', 'leaves'));
    }




    // ==================== DESTROY ====================
    public function destroy($type, $id)
    {
        switch ($type) {
            case 'request':
                $item = ResidenceRequest::findOrFail($id);
                $item->dependents()->delete();
                $item->delete();
                break;
            case 'agreement':
                ResidenceAgreement::findOrFail($id)->delete();
                break;
            case 'guest':
                $item = ResidentGuestRequest::findOrFail($id);
                $item->members()->delete();
                $item->delete();
                break;
            case 'leave':
                ResidenceLeave::findOrFail($id)->delete();
                break;
        }

        return redirect()->route('housing.management')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    // ==================== APPROVE ====================
    public function approve(Request $request, $type, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'comment' => 'nullable|string',
        ]);

        $action = $request->action;
        $comment = $request->comment;
        $userId = Auth::id();
        $date = now()->toDateString();

        switch ($type) {
            case 'request':
                $item = ResidenceRequest::findOrFail($id);
                break;
            case 'agreement':
                $item = ResidenceAgreement::findOrFail($id);
                break;
            case 'guest':
                $item = ResidentGuestRequest::findOrFail($id);
                break;
            case 'leave':
                $item = ResidenceLeave::findOrFail($id);
                break;
            default:
                return back()->with('error', 'ประเภทไม่ถูกต้อง');
        }

        $currentStatus = $item->send_status;
        $approvalStatus = ($action === 'approve') ? 1 : 2;

        // Determine which level to update based on current send_status
        if ($currentStatus == 0 && in_array($type, ['request', 'agreement', 'guest'])) {
            // Level 1: Commander
            $item->commander_id = $userId;
            $item->commander_status = $approvalStatus;
            $item->commander_comment = $comment;
            $item->commander_date = $date;
            $item->send_status = ($action === 'approve') ? 1 : 4;
        } elseif (
            ($currentStatus == 1 && in_array($type, ['request', 'agreement', 'guest'])) ||
            ($currentStatus == 0 && $type === 'leave')
        ) {
            // Level 2: Manager HAMS
            $item->managerhams_id = $userId;
            $item->managerhams_status = $approvalStatus;
            $item->managerhams_comment = $comment;
            $item->managerhams_date = $date;
            $item->send_status = ($action === 'approve') ? 2 : 4;
        } elseif ($currentStatus == 2) {
            // Level 3: Committee
            $item->Committee_id = $userId;
            $item->Committee_status = $approvalStatus;
            $item->Committee_comment = $comment;
            $item->Committee_date = $date;
            $item->send_status = ($action === 'approve') ? 3 : 4;

            // SPECIAL LOGIC: If Agreement is fully approved, mark the related Housing Request as Completed (6)
            if ($type === 'agreement' && $action === 'approve') {
                $housingRequest = ResidenceRequest::where('user_id', $item->user_id)
                    ->where('send_status', 3) // มอบหมายห้องแล้ว
                    ->first();
                if ($housingRequest) {
                    $housingRequest->update(['send_status' => 6]);
                }
            }
        }

        $item->save();

        $msg = ($action === 'approve') ? 'อนุมัติเรียบร้อยแล้ว' : 'ไม่อนุมัติเรียบร้อยแล้ว';
        return redirect()->route('housing.management')->with('success', $msg);
    }

    // ==================== HELPER: Status Label ====================
    public static function getStatusLabel($status)
    {
        return match (intval($status)) {
            0 => 'รอพิจารณา (ส่วนงานต้นสังกัด)',
            1 => 'รอพิจารณา (ผจก.แผนกฯ)',
            2 => 'อนุมัติแล้ว (รอทำสัญญา/มอบหมายห้อง)',
            3 => 'มอบหมายห้องแล้ว (รอทำสัญญา)',
            4 => 'รอกรรมการบ้านพักตรวจสอบ',
            5 => 'ยกเลิก',
            6 => 'ดำเนินการเสร็จสิ้น (เข้าพักแล้ว)',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public static function getStatusColor($status)
    {
        return match (intval($status)) {
            0 => 'bg-amber-50 text-amber-600 border-amber-200',
            1 => 'bg-blue-50 text-blue-600 border-blue-200',
            2 => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            3 => 'bg-sky-50 text-sky-600 border-sky-200',
            4 => 'bg-purple-50 text-purple-600 border-purple-200',
            5 => 'bg-red-50 text-red-600 border-red-200',
            6 => 'bg-slate-50 text-slate-600 border-slate-200',
            default => 'bg-slate-50 text-slate-400 border-slate-200',
        };
    }
}
