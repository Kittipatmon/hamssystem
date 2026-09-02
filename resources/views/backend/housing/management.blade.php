@extends('layouts.housing.apphousing')
@section('title', 'จัดการข้อมูลบ้านพัก')
@section('content')
     <style>
        @keyframes glow-attention {
            0% { box-shadow: 0 0 5px rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); }
            50% { box-shadow: 0 0 15px rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.4); }
            100% { box-shadow: 0 0 5px rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); }
        }
        .row-attention { 
            animation: glow-attention 3s infinite; 
            background-color: rgba(254, 242, 242, 0.4) !important;
            border-left: 5px solid #ef4444 !important;
        }

        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }

        /* Status Pill Fix for Production */
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .bg-amber-50 { background-color: #fffbeb !important; }
        .text-amber-600 { color: #d97706 !important; }
        .border-amber-200 { border-color: #fde68a !important; }

        .bg-blue-50 { background-color: #eff6ff !important; }
        .text-blue-600 { color: #2563eb !important; }
        .border-blue-200 { border-color: #bfdbfe !important; }

        .bg-emerald-50 { background-color: #ecfdf5 !important; }
        .text-emerald-600 { color: #059669 !important; }
        .border-emerald-200 { border-color: #a7f3d0 !important; }

        .bg-sky-50 { background-color: #f0f9ff !important; }
        .text-sky-600 { color: #0284c7 !important; }
        .border-sky-200 { border-color: #bae6fd !important; }

        .bg-purple-50 { background-color: #faf5ff !important; }
        .text-purple-600 { color: #9333ea !important; }
        .border-purple-200 { border-color: #e9d5ff !important; }

        .bg-red-50 { background-color: #fef2f2 !important; }
        .text-red-600 { color: #dc2626 !important; }
        .border-red-200 { border-color: #fecaca !important; }

        .bg-slate-50 { background-color: #f8fafc !important; }
        .text-slate-600 { color: #475569 !important; }
        .border-slate-200 { border-color: #e2e8f0 !important; }
        .text-slate-400 { color: #94a3b8 !important; }

        .bg-cyan-50 { background-color: #ecfeff !important; }
        .text-cyan-600 { color: #0891b2 !important; }
        .border-cyan-200 { border-color: #a5f3fc !important; }

        .bg-rose-50 { background-color: #fff1f2 !important; }
        .text-rose-700 { color: #be123c !important; }
        .border-rose-200 { border-color: #fecdd3 !important; }

        /* Clinical Tab System */
        .management-tabs-container {
            display: flex;
            gap: 6px;
            padding: 6px;
            background: #f1f5f9;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            width: 100%;
            overflow-x: auto;
        }

        .management-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            transition: all 0.2s ease;
            position: relative;
            background: transparent;
        }

        .management-tab:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.5);
        }

        .management-tab.active {
            color: #1e293b;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .management-tab.active i {
            color: #ef4444;
        }

        .badge-count {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            background: #dc2626;
            color: white;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            border: 1px solid white;
        }

        /* Hospital Ledger Table Styling */
        .clinical-table {
            width: 100%;
            border-collapse: collapse;
        }
        .clinical-table th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid #cbd5e1 !important;
            padding: 12px 16px;
        }

        .clinical-table td {
            border: 1px solid #e2e8f0 !important;
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 13px;
            background-color: #ffffff;
        }
        .clinical-table tr:hover td {
            background-color: #f8fafc;
        }

        .premium-pagination nav {
            display: flex;
            justify-content: center;
        }

        @media (max-width: 1024px) {
            .management-tabs-container {
                display: flex;
                flex-direction: column;
                width: 100%;
                border-radius: 12px;
                padding: 6px;
            }
            .management-tab {
                width: 100%;
                padding: 12px 16px;
            }
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8 pb-20 px-4 md:px-0 py-8">
        
        <!-- Premium Header (Clinical Theme) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                    <i class="fa-solid fa-screwdriver-wrench text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">ระบบบริหารจัดการ (MANAGEMENT DASHBOARD)</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-user-shield text-blue-500"></i>
                        จัดการข้อมูลคำร้อง สัญญาข้อตกลง การขอเข้าพัก และรายการแจ้งซ่อมบำรุง
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('housing.welcome') }}" 
                    class="btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-house-chimney text-slate-400"></i> กลับหน้าหลัก
                </a>
            </div>
        </div>

        {{-- Main Control Island --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" id="management-card">
            
            {{-- Filter Bar --}}
            <div class="p-6 border-b border-slate-200 bg-slate-50/50">
                <form method="GET" action="{{ route('housing.management') }}" class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-[2] min-w-[280px] relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ค้นหาเลขที่คำร้อง, ชื่อพนักงาน, หรือหน่วยงาน..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-medium focus:ring-2 focus:ring-red-500/10 focus:border-red-500 transition-all placeholder:text-slate-400 h-11">
                    </div>
                    <div class="flex-1 min-w-[200px] relative">
                        <i class="fa-solid fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        <select name="status" class="w-full pl-11 pr-10 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-700 appearance-none focus:ring-2 focus:ring-red-500/10 focus:border-red-500 transition-all h-11">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ทุกสถานะรายการ</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>รอผู้บังคับบัญชา / จัดการ</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>รอ ผจก. แผนกจัดการฯ</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>รอคณะกรรมการ</option>
                            <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>ส่งกลับแก้ไขข้อมูล</option>
                            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>ผ่านการอนุมัติ (รอขั้นถัดไป)</option>
                            <option value="6" {{ request('status') === '6' ? 'selected' : '' }}>เสร็จสิ้น (เข้าพักแล้ว)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                    </div>
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <button type="submit" class="px-8 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition-all flex items-center justify-center gap-2 h-11">
                        <i class="fa-solid fa-filter"></i> ค้นหา
                    </button>
                </form>
            </div>

            {{-- Navigation Tabs (Clinical Style) --}}
            @php
                $userId = Auth::id();
                $user = Auth::user();

                if ($user && (in_array($user->role, ['admin', 'editor']) || in_array($user->dept_id, [14, 16]) || $user->is_hams_editor)) {
                    $pRequestsTotal = \App\Models\housing\ResidenceRequest::whereIn('send_status', [0, 1, 2])->count();
                    $pAgreementsTotal = \App\Models\housing\ResidenceAgreement::whereIn('send_status', [0, 1, 2])->count();
                    $pGuestsTotal = \App\Models\housing\ResidentGuestRequest::whereIn('send_status', [0, 1, 2])->count();
                    $pLeavesTotal = \App\Models\housing\ResidenceLeave::whereIn('send_status', [0, 1, 2])->count();
                    $pRepairsTotal = \App\Models\housing\ResidenceRepair::where('status', 0)->count();
                } else {
                    $pRequestsTotal = \App\Models\housing\ResidenceRequest::where(function ($q) use ($userId) {
                        $q->where('send_status', 0)->where('commander_id', $userId)
                          ->orWhere('send_status', 1)->where('managerhams_id', $userId)
                          ->orWhere('send_status', 2)->where('Committee_id', $userId);
                    })->count();
                    $pAgreementsTotal = \App\Models\housing\ResidenceAgreement::where(function ($q) use ($userId) {
                        $q->where('send_status', 0)->where('commander_id', $userId)
                          ->orWhere('send_status', 1)->where('managerhams_id', $userId)
                          ->orWhere('send_status', 2)->where('Committee_id', $userId);
                    })->count();
                    $pGuestsTotal = \App\Models\housing\ResidentGuestRequest::where(function ($q) use ($userId) {
                        $q->where('send_status', 0)->where('commander_id', $userId)
                          ->orWhere('send_status', 1)->where('managerhams_id', $userId)
                          ->orWhere('send_status', 2)->where('Committee_id', $userId);
                    })->count();
                    $pLeavesTotal = \App\Models\housing\ResidenceLeave::where(function ($q) use ($userId) {
                        $q->where('send_status', 0)->where('managerhams_id', $userId)
                          ->orWhere('send_status', 2)->where('Committee_id', $userId);
                    })->count();
                    $pRepairsTotal = 0;
                }

                $tabsInfo = [
                    'requests' => ['ชื่อ' => 'คำขอเข้าพัก', 'ไอคอน' => 'fa-file-circle-plus', 'นับ' => $pRequestsTotal],
                    'agreements' => ['ชื่อ' => 'ข้อตกลงพนักงาน', 'ไอคอน' => 'fa-file-signature', 'นับ' => $pAgreementsTotal],
                    'guests' => ['ชื่อ' => 'นำญาติเข้าพัก', 'ไอคอน' => 'fa-people-arrows', 'นับ' => $pGuestsTotal],
                    'leaves' => ['ชื่อ' => 'ขอย้ายออก', 'ไอคอน' => 'fa-right-from-bracket', 'นับ' => $pLeavesTotal],
                    'repairs' => ['ชื่อ' => 'รายการแจ้งซ่อม', 'ไอคอน' => 'fa-screwdriver-wrench', 'นับ' => $pRepairsTotal]
                ];
            @endphp

            <div class="px-6 pt-6">
                <div class="management-tabs-container">
                    @foreach($tabsInfo as $key => $info)
                        <a href="{{ route('housing.management', array_merge(request()->query(), ['tab' => $key])) }}"
                            class="management-tab {{ $tab == $key ? 'active' : '' }}">
                            <i class="fa-solid {{ $info['ไอคอน'] }} text-sm"></i> 
                            <span class="whitespace-nowrap">{{ $info['ชื่อ'] }}</span>
                            @if($info['นับ'] > 0)
                                <span class="badge-count">
                                    {{ $info['นับ'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Table Content Area --}}
            <div class="p-6">
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="clinical-table">
                        <thead>
                            <tr>
                                @if($tab == 'repairs')
                                    <th class="text-left" style="width: 120px;">Code</th>
                                    <th class="text-left" style="width: 200px;">ผู้แจ้ง-ห้องพัก</th>
                                    <th class="text-left">รายละเอียดการแจ้งซ่อม</th>
                                    <th class="text-left" style="width: 180px;">ผู้ดำเนินการ</th>
                                    <th class="text-center" style="width: 130px;">สถานะ</th>
                                @else
                                    <th class="text-left" style="width: 140px;">รหัสคำร้อง</th>
                                    <th class="text-left">ผู้ยื่นคำร้อง</th>
                                    <th class="text-left" style="width: 180px;">วันที่ยื่น / ห้องพัก</th>
                                    <th class="text-center" style="width: 180px;">สถานะขั้นตอน</th>
                                    <th class="text-left" style="width: 200px;">ผู้พิจารณาปัจจุบัน</th>
                                @endif
                                <th class="text-center" style="width: 160px;">จัดการรายการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($tab == 'requests')
                                @forelse($requests as $r)
                                    @php
                                        $currentVal = null;
                                        if ($r->send_status == 0) $currentVal = $r->commander_id;
                                        elseif ($r->send_status == 1) $currentVal = $r->managerhams_id;
                                        elseif ($r->send_status == 2) $currentVal = $r->Committee_id;
                                        $isMyTurn = ($currentVal && Auth::id() == $currentVal);
                                    @endphp
                                    <tr class="{{ $isMyTurn ? 'row-attention' : '' }}">
                                        <td class="font-mono text-xs font-bold text-slate-800 text-left">
                                            <div class="flex flex-col">
                                                <span>{{ $r->requests_code }}</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Residence Request</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs shadow-sm border border-slate-200 shrink-0">
                                                    @if($r->user && $r->user->photo_user)
                                                        <img src="{{ asset($r->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($r->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700 leading-tight">{{ $r->first_name }} {{ $r->last_name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $r->department }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col font-mono text-xs">
                                                <span class="font-bold text-slate-600">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->translatedFormat('d M Y') : '-' }}</span>
                                                <span class="text-[10px] text-slate-400">เวลา {{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('H:i') : '' }} น.</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($r->send_status) }} whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                                <span>{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($r->send_status, 'request') }}</span>
                                            </span>
                                        </td>
                                        <td>
                                            @if($r->send_status < 3)
                                                @php
                                                    $currentAp = null;
                                                    if ($r->send_status == 0) $currentAp = $r->commander;
                                                    elseif ($r->send_status == 1) $currentAp = $r->managerHams;
                                                    elseif ($r->send_status == 2) $currentAp = $r->committee;
                                                    $hasApprovers = $r->commander_id || $r->managerhams_id || $r->Committee_id;
                                                @endphp
                                                <button type="button" 
                                                    onclick="openApproverModal('request', {{ $r->id }}, '{{ $r->requests_code }}', '{{ $r->commander_id }}', '{{ $r->managerhams_id }}', '{{ $r->Committee_id }}')"
                                                    class="flex items-center justify-between w-full p-2 rounded-lg {{ $hasApprovers ? 'bg-blue-50/50 border-blue-200' : 'bg-white border-slate-200' }} border shadow-sm hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                                                    <div class="flex flex-col overflow-hidden">
                                                        <span class="text-[10px] font-bold {{ $hasApprovers ? 'text-blue-700' : 'text-slate-700' }} truncate">{{ $currentAp->fullname ?? 'ระบุผู้พิจารณา' }}</span>
                                                        <span class="text-[9px] {{ $hasApprovers ? 'text-blue-400' : 'text-slate-400' }} font-bold uppercase tracking-tighter mt-0.5">
                                                            @if($r->send_status == 0) Commander @elseif($r->send_status == 1) Manager @else Committee @endif
                                                        </span>
                                                    </div>
                                                    <i class="fa-solid fa-user-pen {{ $hasApprovers ? 'text-blue-400' : 'text-slate-300' }} ml-2 shrink-0"></i>
                                                </button>
                                            @else
                                                <div class="flex items-center gap-1.5 text-slate-400 font-bold italic text-[10px] px-2 py-1 bg-slate-50 border border-slate-200 rounded">
                                                    <i class="fa-solid fa-check-double text-emerald-500"></i> ผ่านการพิจารณาแล้ว
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                @if($isMyTurn)
                                                    <div class="flex gap-1">
                                                        <button onclick="handleApproval('request', {{ $r->id }}, 'approve', this)" class="h-8 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1" title="อนุมัติ">
                                                            <i class="fa-solid fa-check"></i> อนุมัติ
                                                        </button>
                                                        <button onclick="handleApproval('request', {{ $r->id }}, 'correct', this)" class="h-8 px-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs flex items-center justify-center gap-1" title="ส่งกลับแก้ไข">
                                                            <i class="fa-solid fa-rotate-left"></i> ส่งกลับ
                                                        </button>
                                                    </div>
                                                @endif
                                                <div class="flex gap-1 justify-center">
                                                    <a href="{{ route('housing.request_detail', ['type' => 'request', 'id' => $r->id]) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-all border border-slate-200" title="ดูรายละเอียด">
                                                        <i class="fa-solid fa-eye text-xs"></i>
                                                    </a>
                                                    <a href="{{ route('housing.request.pdf', $r->id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center transition-all border border-blue-200" title="PDF">
                                                        <i class="fa-solid fa-file-pdf text-xs"></i>
                                                    </a>
                                                    <form id="delete-form-request-{{ $r->id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'request', 'id' => $r->id]) }}" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    @if(in_array($user->role, ['admin', 'editor']) || in_array($user->dept_id, [14, 16]))
                                                        <button onclick="confirmDelete('request', {{ $r->id }})" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-all flex items-center justify-center border border-red-200" title="ลบข้อมูล">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-16 text-center text-slate-400"><i class="fa-solid fa-inbox text-4xl mb-3 block opacity-30"></i> ไม่พบรายการคำขอเข้าพัก</td></tr>
                                @endforelse

                            @elseif($tab == 'agreements')
                                @forelse($agreements as $a)
                                    @php
                                        $currentVal = null;
                                        if ($a->send_status == 0) $currentVal = $a->commander_id;
                                        elseif ($a->send_status == 1) $currentVal = $a->managerhams_id;
                                        elseif ($a->send_status == 2) $currentVal = $a->Committee_id;
                                        $isMyTurn = ($currentVal && Auth::id() == $currentVal);
                                    @endphp
                                    <tr class="{{ $isMyTurn ? 'row-attention' : '' }}">
                                        <td class="font-mono text-xs font-bold text-slate-800 text-left">
                                            <div class="flex flex-col">
                                                <span>{{ $a->agreement_code }}</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Residence Agreement</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs shadow-sm border border-slate-200 shrink-0">
                                                    @if($a->user && $a->user->photo_user)
                                                        <img src="{{ asset($a->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($a->full_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700 leading-tight">{{ $a->full_name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $a->department }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col font-mono text-xs">
                                                <span class="font-bold text-slate-700">{{ $a->residence_address }}</span>
                                                <span class="text-[9px] text-slate-400 mt-0.5">ยื่นเมื่อ: {{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($a->send_status) }} whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                                <span>{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($a->send_status, 'agreement') }}</span>
                                            </span>
                                        </td>
                                        <td>
                                            @php $hasApprovers = $a->commander_id || $a->managerhams_id || $a->Committee_id; @endphp
                                            <button type="button" onclick='openApproverModal("agreement", {{ $a->agreement_id }}, "{{ $a->agreement_code }}", "{{ $a->commander_id }}", "{{ $a->managerhams_id }}", "{{ $a->Committee_id }}")' 
                                                class="flex items-center justify-between w-full max-w-[180px] p-2 rounded-lg {{ $hasApprovers ? 'bg-blue-50/50 border-blue-200 text-blue-700' : 'bg-white border-slate-200 text-slate-500' }} border shadow-sm hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                                                <span class="text-[10px] font-bold">ตั้งค่าผู้พิจารณา</span>
                                                <i class="fa-solid fa-users-gear text-xs"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                @if($isMyTurn)
                                                    <div class="flex gap-1">
                                                        <button onclick="handleApproval('agreement', {{ $a->agreement_id }}, 'approve', this)" class="h-8 px-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs">อนุมัติ</button>
                                                        <button onclick="handleApproval('agreement', {{ $a->agreement_id }}, 'correct', this)" class="h-8 px-2.5 rounded-lg bg-violet-600 hover:bg-violet-700 text-white font-bold text-xs">ส่งกลับ</button>
                                                    </div>
                                                @endif
                                                <div class="flex gap-1 justify-center">
                                                    <a href="{{ route('housing.request_detail', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-eye text-xs"></i></a>
                                                    <a href="{{ route('housing.agreement.pdf', $a->agreement_id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 flex items-center justify-center border border-blue-200"><i class="fa-solid fa-file-invoice text-xs"></i></a>
                                                    <form id="delete-form-agreement-{{ $a->agreement_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    <button onclick="confirmDelete('agreement', {{ $a->agreement_id }})" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center border border-red-200"><i class="fa-solid fa-trash text-xs"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-16 text-center text-slate-400">ยังไม่มีข้อตกลงพนักงานยื่นเข้ามา</td></tr>
                                @endforelse

                            @elseif($tab == 'guests')
                                @forelse($guests as $g)
                                    <tr>
                                        <td class="font-mono text-xs font-bold text-slate-800 text-left">#{{ $g->resident_guest_code }}</td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs border border-slate-200 shrink-0">
                                                    @if($g->user && $g->user->photo_user)
                                                        <img src="{{ asset($g->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($g->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <span class="font-bold text-slate-700">{{ $g->first_name }} {{ $g->last_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col font-mono text-xs">
                                                <span class="font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded border border-purple-100 w-fit mb-1">
                                                    {{ $g->members->count() }} ผู้เข้าพัก
                                                </span>
                                                <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($g->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($g->end_date)->format('d/m/y') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($g->send_status) }} whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                                <span>{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($g->send_status, 'guest') }}</span>
                                            </span>
                                        </td>
                                        <td>
                                            @if($g->send_status < 3)
                                                @php $hasApprovers = $g->commander_id || $g->managerhams_id || $g->Committee_id; @endphp
                                                <button onclick='openApproverModal("guest", {{ $g->resident_guest_id }}, "{{ $g->resident_guest_code }}", "{{ $g->commander_id }}", "{{ $g->managerhams_id }}", "{{ $g->Committee_id }}")' 
                                                    class="flex items-center justify-between w-full max-w-[180px] p-2 rounded-lg {{ $hasApprovers ? 'bg-blue-50/50 border-blue-200 text-blue-700' : 'bg-white border-slate-200 text-slate-500' }} border shadow-sm hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                                                    <span class="text-[10px] font-bold">ตั้งค่าผู้พิจารณา</span>
                                                    <i class="fa-solid fa-user-gear text-xs"></i>
                                                </button>
                                            @else
                                                <div class="text-slate-400 font-bold italic text-[10px] px-2 py-1 bg-slate-50 border border-slate-200 rounded">
                                                    เสร็จสิ้นการอนุมัติ
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('housing.request_detail', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-magnifying-glass text-xs"></i></a>
                                                <form id="delete-form-guest-{{ $g->resident_guest_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                                <button onclick="confirmDelete('guest', {{ $g->resident_guest_id }})" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center border border-red-200"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-16 text-center text-slate-400">ไม่พบคำขอนำญาตเข้าพัก</td></tr>
                                @endforelse

                            @elseif($tab == 'leaves')
                                @forelse($leaves as $l)
                                    <tr>
                                        <td class="font-mono text-xs font-bold text-slate-800 text-left">#{{ $l->residence_leaves_code }}</td>
                                        <td>
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg overflow-hidden bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs border border-slate-200 shrink-0">
                                                    @if($l->user && $l->user->photo_user)
                                                        <img src="{{ asset($l->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($l->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <span class="font-bold text-slate-700">{{ $l->first_name }} {{ $l->last_name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col font-mono text-xs">
                                                <span class="font-bold text-orange-600">ห้อง {{ $l->room_number }}</span>
                                                <p class="text-[9px] text-slate-400 mt-0.5">ย้ายออก: {{ \Carbon\Carbon::parse($l->move_out_date)->translatedFormat('d M Y') }}</p>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($l->send_status) }} whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                                                <span>{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($l->send_status, 'leave') }}</span>
                                            </span>
                                        </td>
                                        <td>
                                            @if($l->send_status < 3)
                                                @php $hasApprovers = $l->managerhams_id || $l->Committee_id; @endphp
                                                <button onclick='openApproverModal("leave", {{ $l->residence_leaves_id }}, "{{ $l->residence_leaves_code }}", null, "{{ $l->managerhams_id }}", "{{ $l->Committee_id }}")' 
                                                    class="flex items-center justify-between w-full max-w-[180px] p-2 rounded-lg {{ $hasApprovers ? 'bg-blue-50/50 border-blue-200 text-blue-700' : 'bg-white border-slate-200 text-slate-500' }} border shadow-sm hover:border-blue-500 hover:bg-blue-50 transition-all text-left">
                                                    <span class="text-[10px] font-bold">ตั้งค่าผู้พิจารณา</span>
                                                    <i class="fa-solid fa-id-badge text-xs"></i>
                                                </button>
                                            @else
                                                <div class="text-slate-400 font-bold italic text-[10px] px-2 py-1 bg-slate-50 border border-slate-200 rounded">
                                                    เสร็จสิ้นการอนุมัติ
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('housing.request_detail', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center border border-slate-200"><i class="fa-solid fa-eye text-xs"></i></a>
                                                <form id="delete-form-leave-{{ $l->residence_leaves_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                                <button onclick="confirmDelete('leave', {{ $l->residence_leaves_id }})" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center border border-red-200"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-16 text-center text-slate-400">ไม่พบคำขอย้ายออก</td></tr>
                                @endforelse

                            @elseif($tab == 'repairs')
                                @forelse($repairs as $r)
                                    <tr>
                                        <td class="font-mono text-xs font-bold text-slate-800 align-top">#{{ $r->repair_code }}</td>
                                        <td class="align-top">
                                            <p class="font-bold text-slate-700 leading-tight">{{ $r->user->fullname ?? '-' }}</p>
                                            <span class="inline-block px-2 py-0.5 bg-slate-100 rounded text-[10px] font-bold text-slate-500 border border-slate-200 mt-1">ห้อง {{ $r->room->room_number ?? '-' }}</span>
                                        </td>
                                        <td class="align-top text-left">
                                            <div class="flex flex-col gap-1">
                                                <p class="font-bold text-slate-800 leading-snug">{{ $r->title }}</p>
                                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $r->description }}</p>
                                                @if($r->images)
                                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                                        @foreach($r->images as $img)
                                                            <a href="{{ asset($img) }}" onclick="openImagePreview('{{ asset($img) }}', event)" target="_blank" class="w-10 h-10 rounded overflow-hidden border border-slate-200 shadow-sm hover:scale-105 transition-all cursor-pointer">
                                                                <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="align-top">
                                            @if($r->status == 0)
                                                <select onchange="assignTechnician({{ $r->id }}, this.value)" 
                                                    class="select2 text-[11px] h-9 border-slate-300 rounded-lg w-full min-w-[150px]">
                                                    <option value="">เลือกช่าง...</option>
                                                    @foreach($approvers as $ap)
                                                        <option value="{{ $ap->id }}">{{ $ap->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-[10px] shrink-0"><i class="fa-solid fa-user-gear"></i></div>
                                                    <span class="text-xs font-bold text-slate-700 truncate leading-tight">{{ $r->technician->fullname ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center align-top">
                                            @php
                                                $repairStatus = [
                                                    0 => ['รออนุมัติแจ้งซ่อม', 'bg-amber-50 text-amber-600 border-amber-200'],
                                                    1 => ['กำลังซ่อมบำรุง', 'bg-blue-50 text-blue-600 border-blue-200'],
                                                    2 => ['เสร็จสมบูรณ์', 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                                                    3 => ['ยกเลิก', 'bg-red-50 text-red-600 border-red-200']
                                                ];
                                                $rs = $repairStatus[$r->status] ?? ['-', ''];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold border uppercase {{ $rs[1] }}">{{ $rs[0] }}</span>
                                        </td>
                                        <td class="text-center align-top">
                                            <div class="flex justify-center gap-1">
                                                @if($r->status == 1)
                                                    <button onclick="finishRepairTask({{ $r->id }})" class="h-8 px-2.5 rounded bg-emerald-600 text-white flex items-center justify-center gap-1 font-bold text-xs shadow-sm hover:bg-emerald-700" title="ปิดงานซ่อม"><i class="fa-solid fa-check-double text-xs"></i> เสร็จสิ้น</button>
                                                @endif
                                                <button class="w-8 h-8 rounded border border-slate-200 hover:bg-slate-50 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-ellipsis-v text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-16 text-center text-slate-400">ไม่มีข้อมูลการแจ้งซ่อม</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Styling --}}
                <div class="mt-6 px-2 premium-pagination">
                    @php
                        $paginator = null;
                        if($tab == 'requests') $paginator = $requests;
                        elseif($tab == 'agreements') $paginator = $agreements;
                        elseif($tab == 'guests') $paginator = $guests;
                        elseif($tab == 'leaves') $paginator = $leaves;
                        elseif($tab == 'repairs') $paginator = $repairs;
                    @endphp
                    @if($paginator)
                        {{ $paginator->appends(request()->query())->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Approvers -->
    <div id="approverModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeApproverModal()"></div>
            <div class="relative inline-block align-middle bg-white rounded-xl text-left shadow-2xl transform transition-all max-w-md w-full overflow-hidden border border-slate-200">
                <div class="bg-slate-800 px-6 py-4 flex items-center gap-3 border-b border-slate-700">
                    <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center text-white shadow-inner">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white leading-tight">ระบุผู้พิจารณาอนุมัติ</h3>
                        <p id="modal-request-code-header" class="text-slate-400 text-xs font-mono mt-0.5"></p>
                    </div>
                </div>

                <div class="px-6 py-6 bg-slate-50">
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm mb-4">
                        <form id="approverForm" class="space-y-4">
                            <input type="hidden" id="modal-type">
                            <input type="hidden" id="modal-id">
                            
                            <div id="step-commander-div">
                                <label class="flex items-center gap-2 text-xs font-bold text-slate-600 mb-1.5 ml-1">
                                    <span class="flex-none w-5 h-5 rounded bg-red-100 text-red-600 flex items-center justify-center text-[10px] font-bold">01</span>
                                    ผู้บังคับบัญชา (Commander)
                                </label>
                                <select id="modal-commander" class="select2-modal w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" data-dept="{{ $ap->department->department_name ?? '-' }}" data-role="{{ $ap->level_user_label }}">{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label id="step2-label" class="flex items-center gap-2 text-xs font-bold text-slate-600 mb-1.5 ml-1">
                                    <span class="flex-none w-5 h-5 rounded bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">02</span>
                                    ผู้จัดการแผนก (Manager HAMS)
                                </label>
                                <select id="modal-manager" class="select2-modal w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" data-dept="{{ $ap->department->department_name ?? '-' }}" data-role="{{ $ap->level_user_label }}">{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div id="step-committee-div">
                                <label id="step3-label" class="flex items-center gap-2 text-xs font-bold text-slate-600 mb-1.5 ml-1">
                                    <span class="flex-none w-5 h-5 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">03</span>
                                    คณะกรรมการ (Committee)
                                </label>
                                <select id="modal-committee" class="select2-modal w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" data-dept="{{ $ap->department->department_name ?? '-' }}" data-role="{{ $ap->level_user_label }}">{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="closeApproverModal()" class="flex-1 py-2.5 rounded-lg bg-white border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all text-sm">ยกเลิก</button>
                        <button type="button" onclick="saveAllApprovers()" class="flex-[1.5] py-2.5 rounded-lg bg-slate-800 text-white font-bold hover:bg-slate-900 shadow-sm transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-floppy-disk"></i> บันทึกข้อมูล
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="hidden fixed inset-0 z-[150] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeImagePreview()"></div>
            
            <div class="relative inline-block align-middle bg-white rounded-xl text-left shadow-2xl transform transition-all max-w-4xl w-full overflow-hidden border border-slate-200">
                <div class="bg-slate-800 px-6 py-3 flex items-center justify-between border-b border-slate-700">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-image text-white opacity-70"></i>
                        <span class="text-sm font-bold text-white">รูปภาพหลักฐานการแจ้งซ่อม</span>
                    </div>
                    <button type="button" onclick="closeImagePreview()" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-4 bg-slate-900 flex items-center justify-center min-h-[300px] max-h-[80vh] overflow-hidden">
                    <img id="previewModalImage" src="" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-md">
                </div>
                <div class="px-6 py-3 bg-slate-50 flex justify-end gap-2 border-t border-slate-100">
                    <a id="previewDownloadLink" href="" target="_blank" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs transition-all flex items-center gap-1">
                        <i class="fa-solid fa-expand"></i> ดูขนาดจริง
                    </a>
                    <button type="button" onclick="closeImagePreview()" class="px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs transition-all">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function formatApprover(state) {
                if (!state.id) return state.text;
                var dept = $(state.element).data('dept');
                var role = $(state.element).data('role');
                var subText = (dept || '') + (role ? ' | ' + role : '');
                return $('<div class="flex flex-col py-0.5"><div class="text-[11px] font-bold text-gray-800">' + state.text + '</div><div class="text-[9px] text-gray-400 font-medium leading-none mt-0.5">' + (subText || '-') + '</div></div>');
            }
            window.formatApproverResult = formatApprover;
            $('.select2').select2({ width: '100%', placeholder: 'เลือกผู้อนุมัติ', allowClear: true });
            
            // Bind Ajax events
            bindAjaxEvents();
        });

        // Overlay helpers
        function showLoadingOverlay(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.classList.add('relative');
            
            const existing = container.querySelector('.loading-overlay');
            if (existing) existing.remove();
            
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay absolute inset-0 bg-white/70 backdrop-blur-[1px] z-50 flex flex-col items-center justify-center gap-3 transition-opacity duration-200 pointer-events-auto';
            overlay.innerHTML = `
                <div class="flex flex-col items-center justify-center p-6 bg-white rounded-xl border border-slate-200 shadow-md">
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-red-600"></i>
                    <span class="text-xs font-bold text-slate-700 mt-2">กำลังประมวลผล...</span>
                </div>
            `;
            container.appendChild(overlay);
        }

        function hideLoadingOverlay(containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            const overlay = container.querySelector('.loading-overlay');
            if (overlay) {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.remove(), 200);
            }
        }

        // AJAX Table Load logic
        function loadTableData(url) {
            const card = document.getElementById('management-card');
            if (!card) return;
            
            // Set min-height to prevent jump
            card.style.minHeight = card.offsetHeight + 'px';
            
            showLoadingOverlay('management-card');
            card.style.pointerEvents = 'none';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newCard = doc.getElementById('management-card');
                
                if (newCard) {
                    card.style.transition = 'opacity 0.15s ease-out';
                    card.style.opacity = '0.3';
                    
                    setTimeout(() => {
                        card.innerHTML = newCard.innerHTML;
                        window.history.pushState({ url: url }, '', url);
                        
                        // Re-initialize select2 and bindings
                        $('.select2').select2({ width: '100%', placeholder: 'เลือกผู้อนุมัติ', allowClear: true });
                        bindAjaxEvents();
                        
                        card.style.opacity = '1';
                        // Remove height lock after fade-in
                        setTimeout(() => {
                            card.style.minHeight = '';
                            card.style.pointerEvents = 'auto';
                        }, 150);
                    }, 150);
                } else {
                    hideLoadingOverlay('management-card');
                    card.style.pointerEvents = 'auto';
                    card.style.minHeight = '';
                }
            })
            .catch(error => {
                console.error('AJAX load error:', error);
                hideLoadingOverlay('management-card');
                card.style.pointerEvents = 'auto';
                card.style.minHeight = '';
                window.location.href = url;
            });
        }

        function bindAjaxEvents() {
            // 1. Intercept Tab clicks
            document.querySelectorAll('.management-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    loadTableData(url);
                });
            });

            // 2. Intercept Pagination links
            document.querySelectorAll('.premium-pagination a, .pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    loadTableData(url);
                });
            });

            // 3. Intercept Search/Filter Form submit
            const form = document.querySelector('#management-card form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const params = new URLSearchParams(formData).toString();
                    const action = this.getAttribute('action') || window.location.pathname;
                    const url = action + (params ? '?' + params : '');
                    loadTableData(url);
                });
            }
        }

        // Handle browser Back/Forward navigation
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) {
                loadTableData(e.state.url);
            } else {
                loadTableData(window.location.href);
            }
        });

        function openApproverModal(type, id, code, commanderId, managerId, committeeId) {
            $('#modal-type').val(type); $('#modal-id').val(id); $('#modal-request-code-header').text('รหัสคำร้อง: #' + code);
            $('.select2-modal').val('').trigger('change');
            
            if (type === 'leave') {
                 $('#step-commander-div').hide();
                 $('#step2-label').html('<span class="flex-none w-5 h-5 rounded bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">01</span> ผู้จัดการ (Manager)');
                 $('#step3-label').html('<span class="flex-none w-5 h-5 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">02</span> คณะกรรมการ (Committee)');
            } else {
                 $('#step-commander-div').show();
                 $('#step2-label').html('<span class="flex-none w-5 h-5 rounded bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-bold">02</span> ผู้จัดการ (Manager)');
                 $('#step3-label').html('<span class="flex-none w-5 h-5 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">03</span> คณะกรรมการ (Committee)');
                 if (commanderId && commanderId !== 'null') $('#modal-commander').val(commanderId).trigger('change');
            }
            if (managerId && managerId !== 'null') $('#modal-manager').val(managerId).trigger('change');
            if (committeeId && committeeId !== 'null') $('#modal-committee').val(committeeId).trigger('change');
            
            $('#approverModal').removeClass('hidden');
            $('body').addClass('overflow-hidden');
            $('.select2-modal').select2({
                dropdownParent: $('#approverModal'),
                width: '100%',
                placeholder: 'ค้นหา...',
                allowClear: true,
                templateResult: window.formatApproverResult,
                templateSelection: window.formatApproverResult
            });
        }

        function closeApproverModal() { $('#approverModal').addClass('hidden'); $('body').removeClass('overflow-hidden'); }

        function saveAllApprovers() {
            const payload = { type: $('#modal-type').val(), id: $('#modal-id').val(), commander_id: $('#modal-commander').val(), managerhams_id: $('#modal-manager').val(), Committee_id: $('#modal-committee').val() };
            Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
            fetch('{{ route("housing.update_all_approvers") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'สำเร็จ!', timer: 1500, showConfirmButton: false }).then(() => {
                        closeApproverModal();
                        loadTableData(window.location.href);
                    });
                }
                else Swal.fire('ผิดพลาด', data.message || 'ไม่สามารถบันทึกได้', 'error');
            }).catch(() => { Swal.fire('ผิดพลาด', 'การเชื่อมต่อขัดข้อง', 'error'); });
        }

        function assignTechnician(repairId, technicianId) {
            if (!technicianId) return;
            Swal.fire({ title: 'มอบหมายช่าง?', text: 'ยืนยันเพื่อเปลี่ยนสถานะเป็นซ่อมบำรุง', icon: 'question', showCancelButton: true, confirmButtonText: 'ยืนยัน' }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("housing.repair.assign") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ repair_id: repairId, technician_id: technicianId })
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            Swal.fire('สำเร็จ!', 'มอบหมายงานแล้ว', 'success').then(() => {
                                loadTableData(window.location.href);
                            });
                        }
                        else Swal.fire('ผิดพลาด', 'ไม่สามารถมอบหมายได้', 'error');
                    });
                }
            });
        }

        function confirmDelete(type, id) {
            Swal.fire({ title: 'ยืนยันการลบ?', text: 'ข้อมูลนี้จะถูกลบถาวร', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'ลบ', cancelButtonText: 'ยกเลิก' }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + type + '-' + id);
                    if (!form) return;
                    
                    Swal.fire({ title: 'กำลังลบ...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                    
                    fetch(form.getAttribute('action'), {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        Swal.close();
                        Swal.fire({ icon: 'success', title: 'ลบข้อมูลสำเร็จ!', timer: 1500, showConfirmButton: false }).then(() => {
                            loadTableData(window.location.href);
                        });
                    })
                    .catch(err => {
                        Swal.fire('ผิดพลาด', 'การเชื่อมต่อขัดข้อง', 'error');
                    });
                }
            });
        }

        function openImagePreview(src, event) {
            if (event) event.preventDefault();
            document.getElementById('previewModalImage').src = src;
            document.getElementById('previewDownloadLink').href = src;
            document.getElementById('imagePreviewModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeImagePreview() {
            document.getElementById('imagePreviewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
@endsection
