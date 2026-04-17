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
        .island-tab {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .island-tab.active {
            background: white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            color: #1e293b;
        }
        .premium-table thead th {
            letter-spacing: 0.05em;
            font-weight: 800;
        }
        .premium-pagination nav {
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8 pb-20">
        
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center shadow-2xl shadow-slate-200">
                    <i class="fa-solid fa-screwdriver-wrench text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">ระบบบริหารจัดการ</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-black rounded-md uppercase tracking-widest">Administrator</span>
                        <div class="w-1 h-1 rounded-full bg-slate-300"></div>
                        <p class="text-slate-500 text-sm font-medium">จัดการคำร้องและตรวจสอบข้อมูลบ้านพัก</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('housing.welcome') }}" class="group flex items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                    <i class="fa-solid fa-house-chimney text-slate-400 group-hover:text-red-500 transition-colors"></i>
                    กลับหน้าหลัก
                </a>
            </div>
        </div>

        {{-- Main Control Island --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            
            {{-- Filter Bar --}}
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <form method="GET" action="{{ route('housing.management') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="ค้นหาเลขที่คำร้อง, ชื่อพนักงาน, หรือหน่วยงาน..." 
                            class="w-full pl-12 pr-4 py-4 bg-white border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-red-500/5 focus:border-red-500 transition-all">
                    </div>
                    <div class="w-full md:w-64 relative">
                        <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="status" class="w-full pl-12 pr-10 py-4 bg-white border-slate-200 rounded-2xl text-sm font-bold text-slate-700 appearance-none focus:ring-4 focus:ring-red-500/5 focus:border-red-500 transition-all">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ทุกสถานะรายการ</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>รอผู้บังคับบัญชา / จัดการ</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>รอ ผจก. แผนกจัดการฯ</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>รอคณะกรรมการ</option>
                            <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>ส่งกลับแก้ไขข้อมูล</option>
                            <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>ผ่านการอนุมัติ (รอขั้นถัดไป)</option>
                            <option value="6" {{ request('status') === '6' ? 'selected' : '' }}>เสร็จสิ้น (เข้าพักแล้ว)</option>
                        </select>
                    </div>
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <button type="submit" class="px-8 py-4 bg-red-600 text-white rounded-2xl font-black text-sm shadow-lg shadow-red-200 hover:bg-red-700 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-sliders"></i> ค้นหา
                    </button>
                </form>
            </div>

            {{-- Navigation Tabs (Island Style) --}}
            @php
                $userId = Auth::id();
                $user = Auth::user();

                if ($user && ($user->role === 'admin' || in_array($user->dept_id, [14, 16]) || $user->is_hams_editor)) {
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
                    'requests' => ['ชื่อ' => 'คำขอเข้าพัก', 'ไอคอน' => 'fa-file-circle-plus', 'สี' => 'red', 'นับ' => $pRequestsTotal],
                    'agreements' => ['ชื่อ' => 'ข้อตกลงพนักงาน', 'ไอคอน' => 'fa-file-signature', 'สี' => 'blue', 'นับ' => $pAgreementsTotal],
                    'guests' => ['ชื่อ' => 'นำญาติเข้าพัก', 'ไอคอน' => 'fa-people-arrows', 'สี' => 'purple', 'นับ' => $pGuestsTotal],
                    'leaves' => ['ชื่อ' => 'ขอย้ายออก', 'ไอคอน' => 'fa-right-from-bracket', 'สี' => 'orange', 'นับ' => $pLeavesTotal],
                    'repairs' => ['ชื่อ' => 'รายการแจ้งซ่อม', 'ไอคอน' => 'fa-screwdriver-wrench', 'สี' => 'emerald', 'นับ' => $pRepairsTotal]
                ];
            @endphp

            <div class="px-8 pt-6">
                <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-100 rounded-2xl w-fit">
                    @foreach($tabsInfo as $key => $info)
                        <a href="{{ route('housing.management', array_merge(request()->query(), ['tab' => $key])) }}"
                            class="island-tab flex items-center gap-2.5 px-5 py-2.5 rounded-xl text-sm font-black transition-all {{ $tab == $key ? 'active text-red-600' : 'text-slate-500 hover:text-slate-700' }}">
                            <i class="fa-solid {{ $info['ไอคอน'] }} {{ $tab == $key ? 'text-red-600' : 'text-slate-400' }}"></i> 
                            {{ $info['ชื่อ'] }}
                            @if($info['นับ'] > 0)
                                <span class="flex items-center justify-center min-w-[20px] h-5 px-1 bg-red-600 text-[10px] text-white rounded-lg shadow-sm">
                                    {{ $info['นับ'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Table Content Area --}}
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full premium-table">
                        <thead class="text-slate-400 text-[10px] uppercase border-b border-slate-100">
                            <tr>
                                @if($tab == 'repairs')
                                    <th class="px-4 py-6 text-left">Code</th>
                                    <th class="px-4 py-6 text-left">ผู้แจ้ง-ห้องพัก</th>
                                    <th class="px-4 py-6 text-left">รายละเอียด</th>
                                    <th class="px-4 py-6 text-left">ผู้ดำเนินการ</th>
                                    <th class="px-4 py-6 text-left">สถานะ</th>
                                @else
                                    <th class="px-4 py-6 text-left">ข้อมูลคำร้อง</th>
                                    <th class="px-4 py-6 text-left">ผู้ยื่นคำร้อง</th>
                                    <th class="px-4 py-6 text-left">วันที่/เวลา / ห้อง</th>
                                    <th class="px-4 py-6 text-left">สถานะการอนุมัติ</th>
                                    <th class="px-4 py-6 text-left">ผู้พิจารณาปัจจุบัน</th>
                                @endif
                                <th class="px-4 py-6 text-center">จัดการรายการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @if($tab == 'requests')
                                @forelse($requests as $r)
                                    @php
                                        $currentVal = null;
                                        if ($r->send_status == 0) $currentVal = $r->commander_id;
                                        elseif ($r->send_status == 1) $currentVal = $r->managerhams_id;
                                        elseif ($r->send_status == 2) $currentVal = $r->Committee_id;
                                        $isMyTurn = ($currentVal && Auth::id() == $currentVal);
                                    @endphp
                                    <tr class="group hover:bg-slate-50/50 transition-colors {{ $isMyTurn ? 'row-attention' : '' }}">
                                        <td class="px-4 py-5 font-mono text-xs font-black text-slate-800">
                                            <div class="flex flex-col">
                                                <span>{{ $r->requests_code }}</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Residence Request</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 flex items-center justify-center text-slate-400 font-black text-sm shadow-sm">
                                                    @if($r->user && $r->user->photo_user)
                                                        <img src="{{ asset($r->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($r->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700">{{ $r->first_name }} {{ $r->last_name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $r->department }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-600">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->translatedFormat('d M Y') : '-' }}</span>
                                                <span class="text-[10px] text-slate-400">เมื่อ {{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('H:i') : '' }} น.</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($r->send_status) }}">
                                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                                {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($r->send_status, 'request') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-5">
                                            @if($r->send_status < 3)
                                                @php
                                                    $currentAp = null;
                                                    if ($r->send_status == 0) $currentAp = $r->commander;
                                                    elseif ($r->send_status == 1) $currentAp = $r->managerHams;
                                                    elseif ($r->send_status == 2) $currentAp = $r->committee;
                                                @endphp
                                                @php
                                                    $hasApprovers = $r->commander_id || $r->managerhams_id || $r->Committee_id;
                                                @endphp
                                                <button type="button" 
                                                    onclick="openApproverModal('request', {{ $r->id }}, '{{ $r->requests_code }}', '{{ $r->commander_id }}', '{{ $r->managerhams_id }}', '{{ $r->Committee_id }}')"
                                                    class="flex items-center justify-between w-full max-w-[180px] p-2 rounded-xl {{ $hasApprovers ? 'bg-blue-50/50 border-blue-200' : 'bg-white border-slate-200' }} border shadow-sm hover:border-blue-500 hover:bg-blue-50 group/ap transition-all">
                                                    <div class="flex flex-col text-left overflow-hidden">
                                                        <span class="text-[10px] font-black {{ $hasApprovers ? 'text-blue-700' : 'text-slate-700' }} truncate capitalize">{{ $currentAp->fullname ?? 'ระบุผู้อนุมัติ' }}</span>
                                                        <span class="text-[9px] {{ $hasApprovers ? 'text-blue-400' : 'text-slate-400' }} group-hover/ap:text-blue-500 font-bold uppercase tracking-tighter">
                                                            @if($r->send_status == 0) Commander @elseif($r->send_status == 1) Manager @else Committee @endif
                                                        </span>
                                                    </div>
                                                    <i class="fa-solid fa-user-pen {{ $hasApprovers ? 'text-blue-400' : 'text-slate-300' }} group-hover/ap:text-blue-600 ml-2"></i>
                                                </button>
                                            @else
                                                <div class="flex items-center gap-2 text-slate-400 font-bold italic text-[10px]">
                                                    <i class="fa-solid fa-check-double"></i> ผ่านการพิจารณาแล้ว
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex flex-col items-center gap-1.5">
                                                @if($isMyTurn)
                                                    <div class="flex gap-1.5 mb-1.5">
                                                        <button onclick="handleApproval('request', {{ $r->id }}, 'approve', this)" class="w-10 h-10 rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all flex items-center justify-center" title="อนุมัติ">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                        <button onclick="handleApproval('request', {{ $r->id }}, 'correct', this)" class="w-10 h-10 rounded-xl bg-amber-500 text-white shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all flex items-center justify-center" title="ส่งกลับแก้ไข">
                                                            <i class="fa-solid fa-rotate-left"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                                <div class="flex gap-1">
                                                    <a href="{{ route('housing.request_detail', ['type' => 'request', 'id' => $r->id]) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center transition-all" title="ดูรายละเอียด">
                                                        <i class="fa-solid fa-eye text-xs"></i>
                                                    </a>
                                                    <a href="{{ route('housing.request.pdf', $r->id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-all" title="PDF">
                                                        <i class="fa-solid fa-file-pdf text-xs"></i>
                                                    </a>
                                                    <form id="delete-form-request-{{ $r->id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'request', 'id' => $r->id]) }}" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    @if($user->role === 'admin' || in_array($user->dept_id, [14, 16]))
                                                        <button onclick="confirmDelete('request', {{ $r->id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="ลบข้อมูล">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-20 text-center text-slate-400"><i class="fa-solid fa-inbox text-5xl mb-4 block opacity-20"></i> ไม่พบรายการคำขอเข้าพัก</td></tr>
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
                                    <tr class="group hover:bg-slate-50/50 transition-colors {{ $isMyTurn ? 'row-attention' : '' }}">
                                        <td class="px-4 py-5 font-mono text-xs font-black text-slate-800">
                                            <div class="flex flex-col">
                                                <span>{{ $a->agreement_code }}</span>
                                                <span class="text-[9px] text-slate-400 font-medium">Residence Agreement</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-blue-50 text-blue-400 flex items-center justify-center font-black text-sm shadow-sm">
                                                    @if($a->user && $a->user->photo_user)
                                                        <img src="{{ asset($a->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($a->full_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700">{{ $a->full_name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $a->department }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-700">{{ $a->residence_address }}</span>
                                                <span class="text-[9px] text-slate-400">ยื่นเมื่อ: {{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('d/m/Y') : '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($a->send_status) }}">
                                                <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                                {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($a->send_status, 'agreement') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            @php $hasApprovers = $a->commander_id || $a->managerhams_id || $a->Committee_id; @endphp
                                            <button type="button" onclick='openApproverModal("agreement", {{ $a->agreement_id }}, "{{ $a->agreement_code }}", "{{ $a->commander_id }}", "{{ $a->managerhams_id }}", "{{ $a->Committee_id }}")' 
                                                class="flex items-center justify-center w-full max-w-[40px] h-10 mx-auto rounded-xl border {{ $hasApprovers ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-slate-200 text-slate-300' }} hover:border-blue-500 hover:bg-blue-50 transition-all">
                                                <i class="fa-solid fa-users-gear text-sm"></i>
                                            </button>
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                @if($isMyTurn)
                                                    <div class="flex gap-1.5 mb-1.5">
                                                        <button onclick="handleApproval('agreement', {{ $a->agreement_id }}, 'approve', this)" class="px-4 py-2.5 rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all font-black text-[10px]">อนุมัติ</button>
                                                        <button onclick="handleApproval('agreement', {{ $a->agreement_id }}, 'correct', this)" class="px-4 py-2.5 rounded-xl bg-violet-600 text-white shadow-lg shadow-violet-100 hover:bg-violet-700 transition-all font-black text-[10px]">ส่งกลับ</button>
                                                    </div>
                                                @endif
                                                <div class="flex gap-1">
                                                    <a href="{{ route('housing.request_detail', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
                                                    <a href="{{ route('housing.agreement.pdf', $a->agreement_id) }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center"><i class="fa-solid fa-file-invoice text-xs"></i></a>
                                                    <form id="delete-form-agreement-{{ $a->agreement_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    <button onclick="confirmDelete('agreement', {{ $a->agreement_id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-20 text-center text-slate-400">ยังไม่มีข้อตกลงพนักงานยื่นเข้ามา</td></tr>
                                @endforelse

                            @elseif($tab == 'guests')
                                @forelse($guests as $g)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-5 font-mono text-xs font-black text-slate-800">#{{ $g->resident_guest_code }}</td>
                                        <td class="px-4 py-5 font-black text-slate-700">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-purple-50 text-purple-400 flex items-center justify-center font-black text-sm shadow-sm">
                                                    @if($g->user && $g->user->photo_user)
                                                        <img src="{{ asset($g->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($g->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <span>{{ $g->first_name }} {{ $g->last_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md w-fit mb-1">
                                                    {{ $g->members->count() }} ผู้เข้าพัก
                                                </span>
                                                <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($g->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($g->end_date)->format('d/m/y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($g->send_status) }}">
                                                {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($g->send_status, 'guest') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            @if($g->send_status < 3)
                                                @php $hasApprovers = $g->commander_id || $g->managerhams_id || $g->Committee_id; @endphp
                                                <button onclick='openApproverModal("guest", {{ $g->resident_guest_id }}, "{{ $g->resident_guest_code }}", "{{ $g->commander_id }}", "{{ $g->managerhams_id }}", "{{ $g->Committee_id }}")' 
                                                    class="w-10 h-10 flex items-center justify-center border rounded-xl transition-all {{ $hasApprovers ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-slate-200 text-slate-300' }} hover:border-blue-600">
                                                    <i class="fa-solid fa-user-gear text-sm"></i>
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('housing.request_detail', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-search text-xs"></i></a>
                                                <form id="delete-form-guest-{{ $g->resident_guest_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                                <button onclick="confirmDelete('guest', {{ $g->resident_guest_id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-20 text-center text-slate-400">ไม่พบคำขอนำญาตเข้าพัก</td></tr>
                                @endforelse

                            @elseif($tab == 'leaves')
                                @forelse($leaves as $l)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-5 font-mono text-xs font-black text-slate-800">#{{ $l->residence_leaves_code }}</td>
                                        <td class="px-4 py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-orange-50 text-orange-400 flex items-center justify-center font-black text-sm shadow-sm">
                                                    @if($l->user && $l->user->photo_user)
                                                        <img src="{{ asset($l->user->photo_user) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($l->first_name ?? '?', 0, 1) }}
                                                    @endif
                                                </div>
                                                <span class="font-bold text-slate-700">{{ $l->first_name }} {{ $l->last_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-5">
                                            <span class="text-xs font-black text-orange-600">ห้อง {{ $l->room_number }}</span>
                                            <p class="text-[9px] text-slate-400 mt-1">ย้ายออก: {{ \Carbon\Carbon::parse($l->move_out_date)->translatedFormat('d M Y') }}</p>
                                        </td>
                                        <td class="px-4 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($l->send_status) }}">
                                                {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($l->send_status, 'leave') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            @if($l->send_status < 3)
                                                @php $hasApprovers = $l->managerhams_id || $l->Committee_id; @endphp
                                                <button onclick='openApproverModal("leave", {{ $l->residence_leaves_id }}, "{{ $l->residence_leaves_code }}", null, "{{ $l->managerhams_id }}", "{{ $l->Committee_id }}")' 
                                                    class="w-10 h-10 flex items-center justify-center border rounded-xl transition-all {{ $hasApprovers ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-slate-200 text-slate-300' }} hover:border-blue-600">
                                                    <i class="fa-solid fa-id-badge text-sm"></i>
                                                </button>
                                            @endif
                                        </td>
                                        <td class="px-4 py-5 text-center">
                                            <div class="flex justify-center gap-1">
                                                <a href="{{ route('housing.request_detail', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center"><i class="fa-solid fa-eye text-xs"></i></a>
                                                <form id="delete-form-leave-{{ $l->residence_leaves_id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                                <button onclick="confirmDelete('leave', {{ $l->residence_leaves_id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-20 text-center text-slate-400">ไม่พบคำขอย้ายออก</td></tr>
                                @endforelse

                            @elseif($tab == 'repairs')
                                @forelse($repairs as $r)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-4 py-4 align-top font-mono text-xs font-bold text-gray-700">#{{ $r->repair_code }}</td>
                                        <td class="px-4 py-4 align-top">
                                            <p class="font-bold text-gray-800">{{ $r->user->fullname ?? '-' }}</p>
                                            <span class="px-2 py-0.5 bg-slate-100 rounded-md text-[10px] font-bold text-slate-500">ห้อง {{ $r->room->room_number ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex justify-between items-start gap-2">
                                                <div class="flex-1">
                                                    <p class="font-bold text-gray-700 mb-1 leading-tight">{{ $r->title }}</p>
                                                    <p class="text-[10px] text-gray-400 line-clamp-2">{{ $r->description }}</p>
                                                    @if($r->images)
                                                        <div class="flex flex-wrap gap-1.5 mt-2.5">
                                                            @foreach($r->images as $img)
                                                                <a href="{{ asset($img) }}" target="_blank" class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100 shadow-sm hover:scale-110 active:scale-95 transition-all">
                                                                    <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 align-top">
                                            @if($r->status == 0)
                                                <select onchange="assignTechnician({{ $r->id }}, this.value)" 
                                                    class="select2 text-[10px] h-8 border-gray-200 rounded-lg w-full min-w-[150px]">
                                                    <option value="">เลือกช่าง...</option>
                                                    @foreach($approvers as $ap)
                                                        <option value="{{ $ap->id }}">{{ $ap->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-[10px]"><i class="fa-solid fa-user-gear"></i></div>
                                                    <span class="text-[11px] font-bold text-gray-700 leading-tight">{{ $r->technician->fullname ?? '-' }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 align-top">
                                            @php
                                                $repairStatus = [
                                                    0 => ['รอแอนมิน', 'bg-amber-50 text-amber-600 border-amber-200'],
                                                    1 => ['กำลังดำเนินการ', 'bg-blue-50 text-blue-600 border-blue-200'],
                                                    2 => ['ซ่อมเสร็จสิ้น', 'bg-emerald-50 text-emerald-600 border-emerald-200'],
                                                    3 => ['ยกเลิก', 'bg-red-50 text-red-600 border-red-200']
                                                ];
                                                $rs = $repairStatus[$r->status] ?? ['-', ''];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[9px] font-black border uppercase {{ $rs[1] }}">{{ $rs[0] }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-center align-top">
                                            <div class="flex justify-center gap-1">
                                                @if($r->status == 1)
                                                    <button onclick="finishRepairTask({{ $r->id }})" class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-sm" title="ปิดงานซ่อม"><i class="fa-solid fa-check-double text-xs"></i></button>
                                                @endif
                                                <button class="w-8 h-8 rounded-lg border border-slate-200 text-slate-300 flex items-center justify-center"><i class="fa-solid fa-ellipsis-v text-xs"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-4 py-20 text-center text-slate-400">ไม่มีข้อมูลการแจ้งซ่อม</td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Styling --}}
                <div class="mt-8 px-2 premium-pagination">
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
            <div class="relative inline-block align-middle bg-white rounded-[2.5rem] text-left shadow-2xl transform transition-all max-w-md w-full overflow-hidden border border-white/20">
                <div class="bg-[#00a65a] px-8 py-6 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white shadow-inner">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white leading-tight">ระบุผู้พิจารณา</h3>
                        <p id="modal-request-code-header" class="text-white/70 text-xs font-mono mt-0.5"></p>
                    </div>
                </div>

                <div class="px-8 py-8 bg-[#f8f9fa]">
                    <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm mb-6">
                        <form id="approverForm" class="space-y-6">
                            <input type="hidden" id="modal-type">
                            <input type="hidden" id="modal-id">
                            
                            <div id="step-commander-div">
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-600 mb-2 ml-1">
                                    <span class="flex-none w-6 h-6 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-[10px] font-black">01</span>
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
                                <label id="step2-label" class="flex items-center gap-2 text-sm font-bold text-gray-600 mb-2 ml-1">
                                    <span class="flex-none w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-black">02</span>
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
                                <label id="step3-label" class="flex items-center gap-2 text-sm font-bold text-gray-600 mb-2 ml-1">
                                    <span class="flex-none w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">03</span>
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

                    <div class="flex items-center justify-center gap-3">
                        <button type="button" onclick="closeApproverModal()" class="flex-1 py-3.5 rounded-2xl bg-white border border-gray-200 text-gray-600 font-black hover:bg-gray-50 transition-all">ยกเลิก</button>
                        <button type="button" onclick="saveAllApprovers()" class="flex-[1.5] py-3.5 rounded-2xl bg-[#5d45fb] text-white font-black hover:bg-[#4b35e0] shadow-lg shadow-purple-200 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> บันทึกข้อมูล
                        </button>
                    </div>
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
            };
            window.formatApproverResult = formatApprover;
            $('.select2').select2({ width: '100%', placeholder: 'เลือกผู้อนุมัติ', allowClear: true });
        });

        function openApproverModal(type, id, code, commanderId, managerId, committeeId) {
            $('#modal-type').val(type); $('#modal-id').val(id); $('#modal-request-code-header').text('รหัสคำร้อง: #' + code);
            $('.select2-modal').val('').trigger('change');
            
            if (type === 'leave') {
                 $('#step-commander-div').hide();
                 $('#step2-label').html('<span class="flex-none w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-black">01</span> ผู้จัดการ (Manager)');
                 $('#step3-label').html('<span class="flex-none w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">02</span> คณะกรรมการ (Committee)');
            } else {
                 $('#step-commander-div').show();
                 $('#step2-label').html('<span class="flex-none w-6 h-6 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-black">02</span> ผู้จัดการ (Manager)');
                 $('#step3-label').html('<span class="flex-none w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-black">03</span> คณะกรรมการ (Committee)');
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
                if (data.success) Swal.fire({ icon: 'success', title: 'สำเร็จ!', timer: 1500, showConfirmButton: false }).then(() => location.reload());
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
                        if (data.success) Swal.fire('สำเร็จ!', 'มอบหมายงานแล้ว', 'success').then(() => location.reload());
                        else Swal.fire('ผิดพลาด', 'ไม่สามารถมอบหมายได้', 'error');
                    });
                }
            });
        }

        function confirmDelete(type, id) {
            Swal.fire({ title: 'ยืนยันการลบ?', text: 'ข้อมูลนี้จะถูกลบถาวร', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'ลบ', cancelButtonText: 'ยกเลิก' }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + type + '-' + id).submit();
            });
        }
    </script>
@endsection
