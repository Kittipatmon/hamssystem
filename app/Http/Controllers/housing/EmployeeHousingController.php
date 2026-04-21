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
use App\Models\housing\HousingCommittee;
use App\Models\housing\ResidenceRepair;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeHousingController extends Controller
{
    public function reportDashboard(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // 1. Repair Stats (By Status)
        $repairStats = ResidenceRepair::whereYear('repair_date', $year)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // 2. Repair Monthly Trend (Count per month)
        $repairMonthly = ResidenceRepair::whereYear('repair_date', $year)
            ->select(DB::raw('MONTH(repair_date) as month'), DB::raw('count(*) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $monthlyLabels = [];
        $monthlyValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyLabels[] = Carbon::create(null, $m)->locale('th')->translatedFormat('M');
            $monthlyValues[] = isset($repairMonthly[$m]) ? $repairMonthly[$m] : 0;
        }

        // 3. Request Stats
        $requestStats = DB::table('residence_requests')
            ->whereYear('created_at', $year)
            ->select('send_status', DB::raw('count(*) as count'))
            ->groupBy('send_status')
            ->get()
            ->pluck('count', 'send_status');

        // 4. Occupancy Summary
        $residences = Residence::withCount([
            'rooms',
            'rooms as occupied' => function ($q) {
                $q->where('residence_room_status', 1); // 1 = Occupied
            }
        ])->get();

        $totalRooms = ResidenceRoom::count();
        $occupiedRooms = ResidenceRoom::where('residence_room_status', 1)->count();
        $underRepair = ResidenceRoom::where('residence_room_status', 2)->count();

        // 4.1 Most common repair issues (Top 5)
        $topRepairs = ResidenceRepair::whereYear('repair_date', $year)
            ->select('title', DB::raw('count(*) as count'))
            ->groupBy('title')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        // 5. Available Years for Filtering
        $dbYears = ResidenceRepair::select(DB::raw('YEAR(repair_date) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        $currentYear = (int) date('Y');
        $defaultYears = [$currentYear, $currentYear - 1, $currentYear - 2, $currentYear - 3];

        $years = collect(array_merge($dbYears, $defaultYears))
            ->unique()
            ->sortDesc()
            ->values();

        return view('backend.housing.report.index', compact(
            'year',
            'years',
            'repairStats',
            'monthlyLabels',
            'monthlyValues',
            'requestStats',
            'residences',
            'totalRooms',
            'occupiedRooms',
            'underRepair',
            'topRepairs'
        ));
    }
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

        $residenceRooms = ResidenceRoom::with([
            'stays' => function ($q) {
                $q->where('is_current', 1);
            }
        ])->get();
        $totalRooms = $residenceRooms->count();
        $availableRooms = 0;
        $occupiedRooms = 0;
        foreach ($residenceRooms as $room) {
            $hasOccupant = $room->stays->isNotEmpty();
            $status = $room->residence_room_status;
            if ($status != 2 && !$hasOccupant) {
                $availableRooms++;
            } elseif ($hasOccupant) {
                $occupiedRooms++;
            }
        }

        $pendingRequests = ResidenceRequest::where('send_status', 0)->count()
            + ResidenceAgreement::where('send_status', 0)->count()
            + ResidentGuestRequest::where('send_status', 0)->count()
            + ResidenceLeave::where('send_status', 0)->count();
        $activeResidents = $occupiedRooms;

        // Check for active request and next step
        $userActiveRequest = null;
        $needsAgreement = false;
        $pendingAgreement = false;
        if (Auth::check()) {
            $userActiveRequest = ResidenceRequest::where('user_id', Auth::id())
                ->whereIn('send_status', [0, 1, 2, 3, 4, 7])
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($userActiveRequest && $userActiveRequest->send_status == 7) {
                // Find agreement created AFTER or ON the same date as the request
                $agreement = ResidenceAgreement::where('user_id', Auth::id())
                    ->where('created_at', '>=', $userActiveRequest->created_at)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if (!$agreement) {
                    $needsAgreement = true;
                } elseif ($agreement->send_status < 3) {
                    $pendingAgreement = true;
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
            'needsAgreement',
            'pendingAgreement',
            'userActiveRequest'
        ));
    }

    // ==================== HOUSE LIST (ROOM GRID) ====================
    public function houselist()
    {
        $residences = Residence::with([
            'rooms' => function ($q) {
                $q->orderBy('floor')->orderBy('room_number');
            },
            'rooms.stays' => function ($q) {
                $q->where('is_current', 1)->with(['resident', 'latestRequest']);
            }
        ])->get();

        $allRooms = $residences->flatMap->rooms;
        $totalRooms = $allRooms->count();
        $availableRooms = 0;
        $occupiedRooms = 0;
        $maintenanceRooms = 0;

        foreach ($allRooms as $room) {
            $status = $room->residence_room_status;
            $hasOccupant = $room->stays->where('is_current', 1)->isNotEmpty();

            if ($status == 2) {
                $maintenanceRooms++;
            } elseif ($hasOccupant) {
                $occupiedRooms++;
            } else {
                $availableRooms++;
            }
        }

        // Fetch eligible requesters (Approved by Committee but no room assigned)
        $eligibleRequesters = ResidenceRequest::where('send_status', 3)->get();

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
        $user = Auth::user();
        if ($user) {
            $user->load(['department']);
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
    public function editRequest($id)
    {
        $item = ResidenceRequest::with('dependents')->findOrFail($id);

        // Security: only owner can edit if status is 4
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $residences = Residence::all();
        $user = Auth::user();
        if ($user) {
            $user->load(['department']);
        }
        return view('backend.housing.form.request_form', compact('residences', 'user', 'item'));
    }

    public function updateRequest(Request $request, $id)
    {
        $item = ResidenceRequest::findOrFail($id);

        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

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

        $filePaths = json_decode($item->requests_file, true) ?? [];
        if ($request->hasFile('requests_file')) {
            // Optional: delete old files if desired, but here we just append or replace
            // For simplicity, let's replace if new files are uploaded
            $filePaths = [];
            foreach ($request->file('requests_file') as $file) {
                $filename = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('uploads/housing_requests'), $filename);
                $filePaths[] = $filename;
            }
        }

        $item->update([
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
            'requests_file' => !empty($filePaths) ? json_encode($filePaths) : $item->requests_file,
            'send_status' => 0, // Reset status to pending
        ]);

        // Sync dependents
        ResidenceDependent::where('request_id', $item->id)->delete();
        if ($request->has('dep_name')) {
            foreach ($request->dep_name as $i => $name) {
                if (!empty($name)) {
                    ResidenceDependent::create([
                        'request_id' => $item->id,
                        'full_name' => $name,
                        'age' => $request->dep_age[$i] ?? null,
                        'relation' => $request->dep_relation[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('housing.my_requests')->with('success', 'แก้ไขคำร้องขอเข้าพักเรียบร้อยแล้ว');
    }
    public function editAgreement($id)
    {
        $item = ResidenceAgreement::findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $residences = Residence::all();
        $user = Auth::user();
        $userStay = null;
        $userRequest = null;

        if ($user) {
            $user->load(['department']);
            $userStay = ResidenceStay::with(['room.residence'])
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();
            $userRequest = ResidenceRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return view('backend.housing.form.agreement_form', compact('residences', 'user', 'userStay', 'userRequest', 'item'));
    }

    public function updateAgreement(Request $request, $id)
    {
        $item = ResidenceAgreement::findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $request->validate([
            'title' => 'required|string',
            'full_name' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
        ]);

        $item->update([
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

        return redirect()->route('housing.my_requests')->with('success', 'แก้ไขข้อตกลงเข้าพักเรียบร้อยแล้ว');
    }

    public function exportRequestPdf($id)
    {
        $requestData = ResidenceRequest::with(['user', 'dependents'])->findOrFail($id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('backend.housing.pdf.request_pdf', compact('requestData'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'housing_request_' . ($requestData->requests_code ?? $id) . '.pdf';
        return $pdf->stream($filename);
    }

    public function exportAgreementPdf($id)
    {
        $agreement = ResidenceAgreement::with(['user'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('backend.housing.pdf.agreement_pdf', compact('agreement'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'housing_agreement_' . ($agreement->agreement_code ?? $id) . '.pdf';
        return $pdf->stream($filename);
    }

    public function exportGuestPdf($id)
    {
        $guest = ResidentGuestRequest::with(['user', 'members'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('backend.housing.pdf.guest_pdf', compact('guest'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'housing_guest_' . ($guest->resident_guest_code ?? $id) . '.pdf';
        return $pdf->stream($filename);
    }

    public function exportLeavePdf($id)
    {
        $leave = ResidenceLeave::with(['user'])->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('backend.housing.pdf.leave_pdf', compact('leave'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'housing_leave_' . ($leave->residence_leaves_code ?? $id) . '.pdf';
        return $pdf->stream($filename);
    }

    // ==================== AGREEMENT (QF-HAMS-03) ====================
    public function agreementForm()
    {
        $residences = Residence::all();
        $user = Auth::user();
        $userStay = null;

        if ($user) {
            $user->load(['department']);

            // Check for current stay to auto-fill
            $userStay = ResidenceStay::with(['room.residence'])
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();

            // Check for latest request to pull number of residents
            $userRequest = ResidenceRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return view('backend.housing.form.agreement_form', compact('residences', 'user', 'userStay', 'userRequest'));
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
        $user = Auth::user();
        $userStay = null;
        $userRequest = null;

        if ($user) {
            $user->load(['department']);

            // Get current stay to pre-fill room number
            $userStay = ResidenceStay::with(['room.residence'])
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();

            // Get latest housing request for personal details (Position, Dept, Section)
            $userRequest = ResidenceRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return view('backend.housing.form.guest_form', compact('residences', 'user', 'userStay', 'userRequest'));
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

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
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
                        'phone' => $request->guest_phone[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('housing.welcome')->with('success', 'ส่งคำขอนำญาติเข้าพักเรียบร้อยแล้ว');
    }
    public function editGuest($id)
    {
        $item = ResidentGuestRequest::with('members')->findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $residences = Residence::all();
        $user = Auth::user();
        $userStay = null;
        $userRequest = null;

        if ($user) {
            $user->load(['department']);
            $userStay = ResidenceStay::with(['room.residence'])
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();
            $userRequest = ResidenceRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();
        }
        return view('backend.housing.form.guest_form', compact('residences', 'user', 'userStay', 'userRequest', 'item'));
    }

    public function updateGuest(Request $request, $id)
    {
        $item = ResidentGuestRequest::findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'residence_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $item->update([
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

        // Sync guest members
        ResidentGuestMember::where('guest_request_id', $item->resident_guest_id)->delete();
        if ($request->has('guest_name')) {
            foreach ($request->guest_name as $i => $name) {
                if (!empty($name)) {
                    ResidentGuestMember::create([
                        'guest_request_id' => $item->resident_guest_id,
                        'full_name' => $name,
                        'age' => $request->guest_age[$i] ?? null,
                        'relation' => $request->guest_relation[$i] ?? null,
                        'phone' => $request->guest_phone[$i] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('housing.my_requests')->with('success', 'แก้ไขคำขอนำญาติเข้าพักเรียบร้อยแล้ว');
    }

    // ==================== LEAVE/MOVE-OUT REQUEST ====================
    public function leaveForm()
    {
        $residences = Residence::all();
        $user = Auth::user();
        $currentStay = null;
        $snapshot = null;

        if ($user) {
            $user->load(['department']);
            $currentStay = ResidenceStay::with('room.residence')
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();

            // Try to find the latest snapshot from Agreement or Request
            $latestAgreement = ResidenceAgreement::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestAgreement) {
                $snapshot = $latestAgreement;
            } else {
                $latestRequest = ResidenceRequest::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($latestRequest) {
                    $snapshot = $latestRequest;
                }
            }
        }
        return view('backend.housing.form.leave_form', compact('residences', 'user', 'currentStay', 'snapshot'));
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'prefix' => 'nullable|string',
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
            'residence_room_id' => $request->residence_room_id,
            'request_date' => now()->toDateString(),
            'prefix' => $request->prefix ?: (Auth::user()->prefix ?? '-'),
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
    public function editLeave($id)
    {
        $item = ResidenceLeave::findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $residences = Residence::all();
        $user = Auth::user();
        $currentStay = null;
        $snapshot = null;

        if ($user) {
            $user->load(['department']);
            $currentStay = ResidenceStay::with('room.residence')
                ->where('residence_resident_id', $user->id)
                ->where('is_current', 1)
                ->first();

            $latestAgreement = ResidenceAgreement::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestAgreement) {
                $snapshot = $latestAgreement;
            } else {
                $latestRequest = ResidenceRequest::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($latestRequest) {
                    $snapshot = $latestRequest;
                }
            }
        }
        return view('backend.housing.form.leave_form', compact('residences', 'user', 'currentStay', 'snapshot', 'item'));
    }

    public function updateLeave(Request $request, $id)
    {
        $item = ResidenceLeave::findOrFail($id);
        if ($item->user_id !== Auth::id() || $item->send_status !== 4) {
            return redirect()->route('housing.my_requests')->with('error', 'คุณไม่ได้รับอนุญาตให้แก้ไขรายการนี้');
        }

        $request->validate([
            'prefix' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'residence_type' => 'required|string',
            'room_number' => 'required|string',
            'move_out_date' => 'required|date',
            'reason' => 'required|string',
        ]);

        $item->update([
            'residence_room_id' => $request->residence_room_id,
            'prefix' => $request->prefix ?: (Auth::user()->prefix ?? '-'),
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

        return redirect()->route('housing.my_requests')->with('success', 'แก้ไขคำร้องขอย้ายออกเรียบร้อยแล้ว');
    }

    // ==================== MANAGEMENT TABLE ====================
    public function management(Request $request)
    {
        $tab = $request->get('tab', 'requests');

        $requests = ResidenceRequest::with('user')->orderBy('created_at', 'desc');
        $agreements = ResidenceAgreement::with('user')->orderBy('created_at', 'desc');
        $guests = ResidentGuestRequest::with(['user', 'members'])->orderBy('created_at', 'desc');
        $leaves = ResidenceLeave::with('user')->orderBy('created_at', 'desc');
        $repairs = ResidenceRepair::with(['user', 'room', 'technician'])->orderBy('created_at', 'desc');

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
            $repairs->where(function ($q) use ($search) {
                $q->where('repair_code', 'like', "%$search%")
                    ->orWhere('title', 'like', "%$search%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $statusVal = (int) $request->status;
            $requests->where('send_status', $statusVal);
            $agreements->where('send_status', $statusVal);
            $guests->where('send_status', $statusVal);
            $leaves->where('send_status', $statusVal);
            $repairs->where('status', $statusVal);
        }

        $approvers = User::with(['department'])
            ->where('firstname', 'not like', '%System%')
            ->where('firstname', 'not like', '%ICT%')
            ->orderBy('firstname', 'asc')
            ->get();

        return view('backend.housing.management', [
            'tab' => $tab,
            'requests' => $requests->paginate(10, ['*'], 'requests_page')->withQueryString(),
            'agreements' => $agreements->paginate(10, ['*'], 'agreements_page')->withQueryString(),
            'guests' => $guests->paginate(10, ['*'], 'guests_page')->withQueryString(),
            'leaves' => $leaves->paginate(10, ['*'], 'leaves_page')->withQueryString(),
            'repairs' => $repairs->paginate(10, ['*'], 'repairs_page')->withQueryString(),
            'approvers' => $approvers,
        ]);
    }

    public function updateApprover(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'approver_level' => 'required|string', // commander, manager, committee
            'approver_id' => 'nullable|integer',
        ]);

        switch ($request->type) {
            case 'request':
                $item = ResidenceRequest::findOrFail($request->id);
                break;
            case 'agreement':
                $item = ResidenceAgreement::findOrFail($request->id);
                break;
            case 'guest':
                $item = ResidentGuestRequest::findOrFail($request->id);
                break;
            case 'leave':
                $item = ResidenceLeave::findOrFail($request->id);
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $column = '';
        if ($request->approver_level === 'commander') {
            $column = 'commander_id';
        } elseif ($request->approver_level === 'manager') {
            $column = 'managerhams_id';
        } elseif ($request->approver_level === 'committee') {
            $column = 'Committee_id';
        }

        if ($column) {
            $item->update([$column => $request->approver_id ?: null]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function updateAllApprovers(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'commander_id' => 'nullable|integer',
            'managerhams_id' => 'nullable|integer',
            'Committee_id' => 'nullable|integer',
        ]);

        switch ($request->type) {
            case 'request':
                $item = ResidenceRequest::findOrFail($request->id);
                break;
            case 'agreement':
                $item = ResidenceAgreement::findOrFail($request->id);
                break;
            case 'guest':
                $item = ResidentGuestRequest::findOrFail($request->id);
                break;
            case 'leave':
                $item = ResidenceLeave::findOrFail($request->id);
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid type']);
        }

        $updates = [
            'managerhams_id' => $request->managerhams_id ?: null,
            'Committee_id' => $request->Committee_id ?: null,
        ];

        if ($request->type !== 'leave') {
            $updates['commander_id'] = $request->commander_id ?: null;
        }

        $item->update($updates);
        return response()->json(['success' => true]);
    }

    public function assignRoom(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'request_id' => 'required|integer',
        ]);

        $resReq = ResidenceRequest::findOrFail($request->request_id);
        $room = ResidenceRoom::findOrFail($request->room_id);

        $hasOccupant = ResidenceStay::where('residence_room_id', $room->residence_room_id)
            ->where('is_current', 1)
            ->exists();

        if ($room->residence_room_status == 2 || $hasOccupant) {
            return response()->json(['success' => false, 'message' => 'ห้องไม่ว่างหรือไม่สามารถมอบหมายได้ในขณะนี้']);
        }

        // Update Room
        $room->update(['residence_room_status' => 1]);

        // Update Request
        $resReq->update(['send_status' => 7]);

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
        $room = ResidenceRoom::with([
            'residence',
            'stays' => function ($q) {
                $q->where('is_current', 1)->with('resident');
            }
        ])->findOrFail($id);

        $currentStay = $room->stays->first();
        $agreement = null;
        $latestReq = null;

        if ($currentStay) {
            // Priority: Find an agreement for this user.
            $agreement = ResidenceAgreement::where('user_id', $currentStay->residence_resident_id)
                ->where('send_status', 3) // Success
                ->orderBy('created_at', 'desc')
                ->first();

            // Fallback: Get the latest request to ensure we have a name even if they haven't signed the agreement yet
            $latestReq = ResidenceRequest::with(['commander', 'managerHams', 'committee', 'dependents'])
                ->where('user_id', $currentStay->residence_resident_id)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Fetch eligible requesters (Approved by Committee but no room assigned)
        // Filter by site matching the room's residence name
        $siteName = $room->residence->name; // e.g. "บางใหญ่"
        $eligibleRequesters = ResidenceRequest::where('send_status', 3)
            ->where(function ($q) use ($siteName) {
                $q->where('site', 'like', '%' . $siteName . '%');
            })
            ->get();

        return view('backend.housing.housinglist_detail', compact('room', 'currentStay', 'eligibleRequesters', 'agreement', 'latestReq'));
    }

    public function myRequests()
    {
        $userId = Auth::id();
        $requests = ResidenceRequest::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $agreements = ResidenceAgreement::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $guests = ResidentGuestRequest::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $leaves = ResidenceLeave::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        // Tasks pending for this user to approve
        $pendingApprovals = [
            'requests' => ResidenceRequest::where(function ($q) use ($userId) {
                $q->where(function ($sq) use ($userId) {
                    $sq->where('send_status', 0)->where('commander_id', $userId);
                })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 1)->where('managerhams_id', $userId);
                    })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 2)->where('Committee_id', $userId);
                    });
            })->get(),
            'agreements' => ResidenceAgreement::where(function ($q) use ($userId) {
                $q->where(function ($sq) use ($userId) {
                    $sq->where('send_status', 0)->where('commander_id', $userId);
                })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 1)->where('managerhams_id', $userId);
                    })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 2)->where('Committee_id', $userId);
                    });
            })->get(),
            'guests' => ResidentGuestRequest::where(function ($q) use ($userId) {
                $q->where(function ($sq) use ($userId) {
                    $sq->where('send_status', 0)->where('commander_id', $userId);
                })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 1)->where('managerhams_id', $userId);
                    })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 2)->where('Committee_id', $userId);
                    });
            })->get(),
            'leaves' => ResidenceLeave::where(function ($q) use ($userId) {
                $q->where(function ($sq) use ($userId) {
                    $sq->where('send_status', 0)->where('managerhams_id', $userId);
                })
                    ->orWhere(function ($sq) use ($userId) {
                        $sq->where('send_status', 2)->where('Committee_id', $userId);
                    });
            })->get(),
        ];

        return view('backend.housing.my_requests', compact('requests', 'agreements', 'guests', 'leaves', 'pendingApprovals'));
    }

    public function requestDetail($type, $id)
    {
        $item = null;
        switch ($type) {
            case 'request':
                $item = ResidenceRequest::with(['user', 'dependents'])->findOrFail($id);
                break;
            case 'agreement':
                $item = ResidenceAgreement::with(['user', 'commander', 'managerHams', 'committee'])->findOrFail($id);
                $item->latestReq = ResidenceRequest::where('user_id', $item->user_id)->orderBy('created_at', 'desc')->first();
                break;
            case 'guest':
                $item = ResidentGuestRequest::with(['user', 'members', 'commander', 'managerHams', 'committee'])->findOrFail($id);
                $item->latestReq = ResidenceRequest::where('user_id', $item->user_id)->orderBy('created_at', 'desc')->first();
                break;
            case 'leave':
                $item = ResidenceLeave::with(['user', 'managerHams', 'committee'])->findOrFail($id);
                $item->latestReq = ResidenceRequest::where('user_id', $item->user_id)->orderBy('created_at', 'desc')->first();
                break;
            default:
                abort(404);
        }

        return view('backend.housing.request_detail', compact('item', 'type'));
    }

    // ==================== DESTROY ====================
    public function destroy($type, $id)
    {
        $item = null;
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
                abort(404);
        }

        // Security Check: Only owner or official can delete
        if ($item->user_id !== Auth::id() && Auth::user()->level_user < 3) {
            return back()->with('error', 'คุณไม่ได้รับอนุญาตให้ลบรายการนี้');
        }

        // Status Check: Only status 0 can be deleted by user
        if ($item->send_status >= 1 && Auth::user()->level_user < 3) {
            return back()->with('error', 'ใบคำขอถูกส่งไปพิจารณาแล้ว ไม่สามารถยกเลิกได้');
        }

        // Perform deletion
        switch ($type) {
            case 'request':
                $item->dependents()->delete();
                break;
            case 'guest':
                $item->members()->delete();
                break;
        }
        $item->delete();

        return back()->with('success', 'ยกเลิกรายการเรียบร้อยแล้ว');
    }

    // ==================== APPROVE ====================
    public function approve(Request $request, $type, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,correct',
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

        // --- Custom Authorization Check ---
        // 1. Check if user has HAMS management rights
        $user = Auth::user();
        $isHams = ($user->role === 'admin' || in_array($user->dept_id, [14, 16]) || $user->is_hams_editor);

        // 2. Determine who should be the assigned approver for this specific step
        $assignedApproverId = null;
        if ($type === 'leave') {
            if ($currentStatus == 0) $assignedApproverId = $item->managerhams_id;
            elseif ($currentStatus == 2) $assignedApproverId = $item->Committee_id;
        } else {
            if ($currentStatus == 0) $assignedApproverId = $item->commander_id;
            elseif ($currentStatus == 1) $assignedApproverId = $item->managerhams_id;
            elseif ($currentStatus == 2) $assignedApproverId = $item->Committee_id;
        }

        // 3. Block if NOT HAMS and NOT the assigned approver
        if (!$isHams && $userId != $assignedApproverId) {
            $msg = 'ขออภัย คุณไม่ได้รับอนุญาตให้ดำเนินการในขั้นตอนนี้ (ต้องเป็นผู้อนุมัติที่ได้รับมอบหมายหรือเจ้าหน้าที่ HAMS)';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 403);
            }
            return back()->with('error', $msg);
        }
        // ----------------------------------

        // Map actions to internal status codes for the current approver level
        // 1 = Approved, 2 = Rejected (Final), 4 = Correction Required
        $approvalStatus = 1;
        if ($action === 'reject')
            $approvalStatus = 2;
        if ($action === 'correct')
            $approvalStatus = 4;

        // Determine which level to update based on current send_status
        if ($currentStatus == 0 && in_array($type, ['request', 'agreement', 'guest'])) {
            // Level 1: Commander
            $item->commander_id = $userId;
            $item->commander_status = $approvalStatus;
            $item->commander_comment = $comment;
            $item->commander_date = $date;
            $item->send_status = ($action === 'approve') ? 1 : ($action === 'correct' ? 4 : 8);
        } elseif (
            ($currentStatus == 1 && in_array($type, ['request', 'agreement', 'guest'])) ||
            ($currentStatus == 0 && $type === 'leave')
        ) {
            // Level 2: Manager HAMS
            $item->managerhams_id = $userId;
            $item->managerhams_status = $approvalStatus;
            $item->managerhams_comment = $comment;
            $item->managerhams_date = $date;
            $item->send_status = ($action === 'approve') ? 2 : ($action === 'correct' ? 4 : 8);
        } elseif ($currentStatus == 2) {
            // Level 3: Committee
            $item->Committee_id = $userId;
            $item->Committee_status = $approvalStatus;
            $item->Committee_comment = $comment;
            $item->Committee_date = $date;
            $item->send_status = ($action === 'approve') ? 3 : ($action === 'correct' ? 4 : 8);

            // SPECIAL LOGIC: If Agreement is fully approved, mark the related Housing Request as Completed (6)
            if ($type === 'agreement' && $action === 'approve') {
                $housingRequest = ResidenceRequest::where('user_id', $item->user_id)
                    ->where('send_status', 7) // มอบหมายห้องแล้ว
                    ->first();
                if ($housingRequest) {
                    $housingRequest->update(['send_status' => 6]);
                }
            }

            // SPECIAL LOGIC: If Leave Request is fully approved, update ResidenceRoom and ResidenceStay
            if ($type === 'leave' && $action === 'approve') {
                // Try to find room by ID first (preferred)
                $room = null;
                if ($item->residence_room_id) {
                    $room = ResidenceRoom::find($item->residence_room_id);
                }

                // Fallback to string search if ID not found
                if (!$room) {
                    $room = ResidenceRoom::where('room_number', $item->room_number)
                        ->whereHas('residence', function ($q) use ($item) {
                            $q->where('name', $item->residence_type);
                        })->first();
                }

                if ($room) {
                    // Update Room Status to Vacant (0)
                    $room->update(['residence_room_status' => 0]);

                    // Update ResidenceStay for this user and room
                    ResidenceStay::where('residence_room_id', $room->residence_room_id)
                        ->where('residence_resident_id', $item->user_id)
                        ->where('is_current', 1)
                        ->update([
                            'is_current' => 0,
                            'check_out' => $item->move_out_date,
                            'reason_leave' => $item->reason
                        ]);
                }
            }
        }

        $item->save();

        $msg = 'ดำเนินการเรียบร้อยแล้ว';
        if ($action === 'approve')
            $msg = 'อนุมัติเรียบร้อยแล้ว';
        elseif ($action === 'correct')
            $msg = 'ส่งกลับแก้ไขเรียบร้อยแล้ว';
        elseif ($action === 'reject')
            $msg = 'ไม่อนุมัติเรียบร้อยแล้ว';

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    // ==================== REPAIR REQUEST (QF-HAMS-REPAIR) ====================
    public function repairForm()
    {
        $user = Auth::user();
        // Get user's current stay
        $currentStay = ResidenceStay::with('room.residence')
            ->where('residence_resident_id', $user->id)
            ->where('is_current', 1)
            ->first();

        return view('backend.housing.form.repair_form', compact('user', 'currentStay'));
    }

    public function storeRepair(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $lastId = ResidenceRepair::max('id') ?? 0;
        $code = 'RP-' . date('ym') . sprintf('%02d', ($lastId % 100) + 1);

        $filePaths = [];
        if ($request->hasFile('repair_images')) {
            foreach ($request->file('repair_images') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/housing_repairs'), $filename);
                $filePaths[] = 'uploads/housing_repairs/' . $filename;
            }
        }

        ResidenceRepair::create([
            'repair_code' => $code,
            'user_id' => Auth::id(),
            'residence_room_id' => $request->room_id,
            'title' => $request->title,
            'description' => $request->description,
            'images' => !empty($filePaths) ? $filePaths : null,
            'status' => 0,
            'repair_date' => now(),
        ]);

        return redirect()->route('housing.welcome')->with('success', 'ส่งคำแจ้งซ่อมเรียบร้อยแล้ว เราจะรีบดำเนินการตรวจสอบให้เร็วที่สุด');
    }

    public function assignRepair(Request $request)
    {
        $request->validate([
            'repair_id' => 'required|integer',
            'technician_id' => 'required|integer',
        ]);

        $repair = ResidenceRepair::findOrFail($request->repair_id);
        $repair->update([
            'technician_id' => $request->technician_id,
            'status' => 1, // In Progress
        ]);

        // Update Room Status to "Maintenance" (2)
        $room = ResidenceRoom::findOrFail($repair->residence_room_id);
        $room->update(['residence_room_status' => 2]);

        return response()->json(['success' => true]);
    }

    public function finishRepair(Request $request)
    {
        try {
            $request->validate([
                'repair_id' => 'required|integer',
                'technician_note' => 'nullable|string',
                'finish_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $repair = ResidenceRepair::findOrFail($request->repair_id);

            $filePaths = [];
            if ($request->hasFile('finish_images')) {
                // Ensure directory exists
                $uploadPath = public_path('uploads/housing_repairs');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                foreach ($request->file('finish_images') as $file) {
                    $filename = time() . '_fin_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $filePaths[] = 'uploads/housing_repairs/' . $filename;
                }
            }

            $repair->update([
                'status' => 2, // Completed
                'technician_note' => $request->technician_note,
                'technician_images' => !empty($filePaths) ? $filePaths : null,
                'completion_date' => now(),
            ]);

            // Check if room is still occupied by someone
            $hasOccupant = ResidenceStay::where('residence_room_id', $repair->residence_room_id)
                ->where('is_current', 1)
                ->exists();

            $room = ResidenceRoom::findOrFail($repair->residence_room_id);
            $room->update(['residence_room_status' => $hasOccupant ? 1 : 0]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ==================== COMMITTEE ORGANIZATION CHART ====================
    public function committeeChart()
    {
        $committees = HousingCommittee::with('user.department')
            ->orderBy('order', 'asc')
            ->get();

        // Check strictly for HAMS department or System Admin (Level 0)
        $isHams = false;
        if (Auth::check()) {
            $user = Auth::user();
            $dept_id = $user->dept_id;
            if ($user->role === 'admin' || in_array($dept_id, [14, 16])) {
                $isHams = true;
            }
        }

        // All users to choose from for committee
        $users = User::where('status', 'active')->get();
        // Or if '0' is still needed for some reason, but 'active' is the new enum

        return view('backend.housing.committee_chart', compact('committees', 'isHams', 'users'));
    }

    public function storeCommittee(Request $request)
    {
        $user = Auth::user();
        $deptName = strtoupper($user->department->department_name ?? '');
        if (!in_array($deptName, ['HAMS', 'HAMS_ADMIN']) && $user->level_user < 3) {
            return back()->with('error', 'ระบบอนุญาตให้เฉพาะแผนก HAMS หรือผู้ดูแลระดับสูงเท่านั้นที่สามารถจัดการข้อมูลนี้ได้');
        }

        $request->validate([
            'user_id' => 'required|exists:userkml2025.employees,id',
            'role' => 'required|string',
            'order' => 'required|integer'
        ]);

        HousingCommittee::create($request->all());

        return back()->with('success', 'เพิ่มข้อมูลกรรมการบ้านพักเรียบร้อยแล้ว');
    }

    public function updateCommittee(Request $request, $id)
    {
        $user = Auth::user();
        $deptName = strtoupper($user->department->department_name ?? '');
        if (!in_array($deptName, ['HAMS', 'HAMS_ADMIN']) && $user->level_user < 3) {
            return back()->with('error', 'ระบบอนุญาตให้เฉพาะแผนก HAMS หรือผู้ดูแลระดับสูงเท่านั้นที่สามารถจัดการข้อมูลนี้ได้');
        }

        $request->validate([
            'user_id' => 'required|exists:userkml2025.employees,id',
            'role' => 'required|string',
            'order' => 'required|integer'
        ]);

        $committee = HousingCommittee::findOrFail($id);
        $committee->update($request->all());

        return back()->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroyCommittee($id)
    {
        $user = Auth::user();
        $deptName = strtoupper($user->department->department_name ?? '');
        if (!in_array($deptName, ['HAMS', 'HAMS_ADMIN']) && $user->level_user < 3) {
            return back()->with('error', 'ระบบอนุญาตให้เฉพาะแผนก HAMS หรือผู้ดูแลระดับสูงเท่านั้นที่สามารถจัดการข้อมูลนี้ได้');
        }

        HousingCommittee::findOrFail($id)->delete();

        return back()->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    // ==================== HELPER: Status Label ====================
    public static function getStatusLabel($status, $type = 'request')
    {
        $status = intval($status);
        switch ($status) {
            case 0:
                if ($type == 'leave')
                    return 'รอผู้จัดการแผนกจัดการฯ ตรวจสอบ (ถัดไป: รอกรรมการบ้านพัก)';
                return 'รอผู้บังคับบัญชาอนุมัติ (ถัดไป: รอผู้จัดการแผนกจัดการฯ)';
            case 1:
                return 'รอผู้จัดการแผนกจัดการฯ อนุมัติ (ถัดไป: รอกรรมการบ้านพัก)';
            case 2:
                if ($type == 'request')
                    return 'รอกรรมการบ้านพักตรวจสอบ (ถัดไป: รอมอบหมายห้อง)';
                return 'รอกรรมการบ้านพักตรวจสอบ (ถัดไป: อนุมัติขั้นสุดท้าย)';
            case 3:
                if ($type == 'request')
                    return 'ผ่านการอนุมัติ (ถัดไป: รอเจ้าหน้าที่มอบหมายห้อง)';
                if ($type == 'leave')
                    return 'อนุมัติการย้ายออกแล้ว (ดำเนินการเสร็จสิ้น)';
                return 'ดำเนินการเสร็จสิ้น';
            case 7:
                if ($type == 'request')
                    return 'มอบหมายห้องแล้ว (ถัดไป: รอลงนามข้อตกลงเข้าพัก)';
                return 'มอบหมายห้องแล้ว';
            case 4:
                return 'ส่งกลับแก้ไข (ถัดไป: รอคุณแก้ไขข้อมูล)';
            case 5:
                return 'ยกเลิก';
            case 6:
                return 'ดำเนินการเสร็จสิ้น (เข้าพักแล้ว)';
            case 8:
                return 'ไม่อนุมัติ (ดำเนินการเสร็จสิ้น)';
            default:
                return 'ไม่ทราบสถานะ';
        }
    }

    public static function getStatusShortLabel($status, $type = 'request')
    {
        $status = intval($status);
        switch ($status) {
            case 0:
                if ($type == 'leave') return 'รอฝ่ายจัดการ';
                return 'รอหัวหน้างาน';
            case 1:
                return 'รอฝ่ายจัดการ';
            case 2:
                return 'รอกรรมการ';
            case 3:
                if ($type == 'request') return 'ผ่านอนุมัติ';
                if ($type == 'leave') return 'อนุมัติย้ายออก';
                return 'สำเร็จ';
            case 7:
                return 'มอบหมายห้อง';
            case 4:
                return 'แก้ไข';
            case 5:
                return 'ยกเลิก';
            case 6:
                return 'สำเร็จ';
            case 8:
                return 'ไม่อนุมัติ';
            default:
                return 'N/A';
        }
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
            7 => 'bg-cyan-50 text-cyan-600 border-cyan-200',
            8 => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-50 text-slate-400 border-slate-200',
        };
    }
}
