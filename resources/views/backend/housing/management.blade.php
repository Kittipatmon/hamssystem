@extends('layouts.housing.apphousing')
@section('title', 'จัดการข้อมูลบ้านพัก')
@section('content')
<style>
    @keyframes glow-attention {
        0% { box-shadow: inset 0 0 10px rgba(239, 68, 68, 0.05); }
        50% { box-shadow: inset 0 0 20px rgba(239, 68, 68, 0.2); }
        100% { box-shadow: inset 0 0 10px rgba(239, 68, 68, 0.05); }
    }
    .row-attention { 
        animation: glow-attention 2.5s infinite; 
        background-color: rgba(254, 242, 242, 0.7) !important;
        border-left: 4px solid #ef4444 !important;
    }
</style>
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm shadow-sm">
        <i class="fa-solid fa-circle-check"></i><span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center shadow-lg"><i class="fa-solid fa-table-list text-white text-lg"></i></div>
                จัดการข้อมูลบ้านพัก
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm ml-[52px]">เพิ่ม ลบ แก้ไข และอนุมัติคำร้องทุกประเภท</p>
        </div>
        <a href="{{ route('housing.welcome') }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 shadow-sm flex items-center gap-2"><i class="fa-solid fa-arrow-left"></i> Dashboard</a>
    </div>

    {{-- Search & Filters --}}
    <form method="GET" action="{{ route('housing.management') }}" class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1">ค้นหา</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาเลขที่, ชื่อผู้ยื่น..." class="w-full pl-10 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm h-10">
                </div>
            </div>
            <div class="w-48">
                <label class="block text-xs font-semibold text-gray-500 mb-1">สถานะ</label>
                <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm h-10">
                    <option value="all" {{ request('status')=='all'?'selected':'' }}>ทุกสถานะ</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>รอผู้บังคับบัญชา</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>รอผจก.แผนกจัดการฯ</option>
                    <option value="2" {{ request('status')==='2'?'selected':'' }}>รอกรรมการ</option>
                    <option value="3" {{ request('status')==='3'?'selected':'' }}>มอบหมายห้องแล้ว</option>
                    <option value="6" {{ request('status')==='6'?'selected':'' }}>ดำเนินการเสร็จสิ้น</option>
                    <option value="4" {{ request('status')==='4'?'selected':'' }}>ส่งกลับแก้ไข</option>
                    <option value="5" {{ request('status')==='5'?'selected':'' }}>ยกเลิก</option>
                </select>
            </div>
            <input type="hidden" name="tab" value="{{ $tab }}">
            <button type="submit" class="h-10 px-5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-sm font-semibold shadow-sm"><i class="fa-solid fa-search mr-1"></i> ค้นหา</button>
        </div>
    </form>

    {{-- Tab Navigation --}}
    @php
        $userId = Auth::id();
        $pRequestsTotal = \App\Models\housing\ResidenceRequest::where(function($q) use ($userId) {
            $q->where(function($sq) use ($userId) { $sq->where('send_status', 0)->where('commander_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 1)->where('managerhams_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 2)->where('Committee_id', $userId); });
        })->count();
        $pAgreementsTotal = \App\Models\housing\ResidenceAgreement::where(function($q) use ($userId) {
            $q->where(function($sq) use ($userId) { $sq->where('send_status', 0)->where('commander_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 1)->where('managerhams_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 2)->where('Committee_id', $userId); });
        })->count();
        $pGuestsTotal = \App\Models\housing\ResidentGuestRequest::where(function($q) use ($userId) {
            $q->where(function($sq) use ($userId) { $sq->where('send_status', 0)->where('commander_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 1)->where('managerhams_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 2)->where('Committee_id', $userId); });
        })->count();
        $pLeavesTotal = \App\Models\housing\ResidenceLeave::where(function($q) use ($userId) {
            $q->where(function($sq) use ($userId) { $sq->where('send_status', 0)->where('managerhams_id', $userId); })
              ->orWhere(function($sq) use ($userId) { $sq->where('send_status', 2)->where('Committee_id', $userId); });
        })->count();

        $tabs = [
            'requests' => ['ชื่อ' => 'คำขอเข้าพัก', 'ไอคอน' => 'fa-file-circle-plus', 'สี' => 'red', 'นับ' => $pRequestsTotal],
            'agreements' => ['ชื่อ' => 'ข้อตกลง', 'ไอคอน' => 'fa-file-signature', 'สี' => 'blue', 'นับ' => $pAgreementsTotal],
            'guests' => ['ชื่อ' => 'นำญาติเข้าพัก', 'ไอคอน' => 'fa-people-arrows', 'สี' => 'purple', 'นับ' => $pGuestsTotal],
            'leaves' => ['ชื่อ' => 'ขอย้ายออก', 'ไอคอน' => 'fa-right-from-bracket', 'สี' => 'orange', 'นับ' => $pLeavesTotal],
            'repairs' => ['ชื่อ' => 'แจ้งซ่อม', 'ไอคอน' => 'fa-screwdriver-wrench', 'สี' => 'emerald', 'นับ' => 0]
        ];
    @endphp
    <div class="flex flex-wrap gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
        @foreach($tabs as $key => $info)
        <a href="{{ route('housing.management', array_merge(request()->query(), ['tab' => $key])) }}"
            class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all {{ $tab == $key ? 'bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">
            <i class="fa-solid {{ $info['ไอคอน'] }} text-{{ $info['สี'] }}-500"></i> {{ $info['ชื่อ'] }}
            @if($info['นับ'] > 0)
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                    {{ $info['นับ'] }}
                </span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- TAB: Requests --}}
    @if($tab == 'requests')
    <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                    <tr><th class="px-4 py-3 text-left">เลขที่</th><th class="px-4 py-3 text-left">ชื่อ</th><th class="px-4 py-3 text-left">แผนก</th><th class="px-4 py-3 text-left">วันที่ยื่น</th><th class="px-4 py-3 text-left">สถานะ</th><th class="px-4 py-3 text-left">ผู้ที่มีสิทธิอนุมัติ</th><th class="px-4 py-3 text-center">จัดการ</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($requests as $r)
                        @php
                            $currentVal = null;
                            if ($r->send_status == 0) $currentVal = $r->commander_id;
                            elseif ($r->send_status == 1) $currentVal = $r->managerhams_id;
                            elseif ($r->send_status == 2) $currentVal = $r->Committee_id;
                            $isMyTurn =  ($currentVal && Auth::id() == $currentVal);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $isMyTurn ? 'row-attention' : '' }}">
                            <td class="px-4 py-3 font-mono text-xs font-medium">{{ $r->requests_code }}</td>
                            <td class="px-4 py-3">{{ $r->first_name }} {{ $r->last_name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $r->department }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($r->send_status) }}">
                                    {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($r->send_status) }}
                                </span>
                            </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-semibold">
                            @if($r->send_status < 3)
                                @php
                                    $currentLevel = '';
                                    $currentVal = null;
                                    if ($r->send_status == 0) { $currentLevel = 'commander'; $currentVal = $r->commander_id; }
                                    elseif ($r->send_status == 1) { $currentLevel = 'manager'; $currentVal = $r->managerhams_id; }
                                    elseif ($r->send_status == 2) { $currentLevel = 'committee'; $currentVal = $r->Committee_id; }
                                @endphp
                                
                                @if($currentLevel)
                                <select onchange="updateApprover('request', {{ $r->id }}, '{{ $currentLevel }}', this.value)" 
                                    class="select2 text-[10px] py-1 border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 focus:ring-red-500 h-8 w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" {{ $currentVal == $ap->id ? 'selected' : '' }}>{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                                @else
                                -
                                @endif
                            @else
                                <span class="text-gray-400 italic">ดำเนินการแล้ว</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-1 flex-col items-center">
                                @if($r->send_status < 3)
                                    @php
                                        $currentVal = null;
                                        if ($r->send_status == 0) $currentVal = $r->commander_id;
                                        elseif ($r->send_status == 1) $currentVal = $r->managerhams_id;
                                        elseif ($r->send_status == 2) $currentVal = $r->Committee_id;
                                    @endphp
                                    
                                    @if(Auth::id() == $currentVal)
                                        <span class="block text-[9px] text-red-500 font-bold mb-1 animate-bounce"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                                    @endif

                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'request', 'id' => $r->id]) }}" class="inline">
                                            @csrf <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center border border-emerald-200" title="อนุมัติ"><i class="fa-solid fa-check text-xs"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'request', 'id' => $r->id]) }}" class="inline">
                                            @csrf <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center border border-red-200" title="ไม่อนุมัติ"><i class="fa-solid fa-xmark text-xs"></i></button>
                                        </form>
                                    </div>
                                @endif
                                <div class="flex gap-1 mt-1">
                                    <a href="{{ route('housing.request_detail', ['type' => 'request', 'id' => $r->id]) }}" 
                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center" title="รายละเอียด">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('housing.request.pdf', $r->id) }}" target="_blank" 
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="PDF">
                                    <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                    <form id="delete-form-request-{{ $r->id }}" method="POST" action="{{ route('housing.destroy', ['type' => 'request', 'id' => $r->id]) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDelete('request', {{ $r->id }})" 
                                        class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center" title="ลบ">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>ไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">{{ $requests->links() }}</div>
    </div>
    @endif

    {{-- TAB: Agreements --}}
    @if($tab == 'agreements')
    <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                    <tr><th class="px-4 py-3 text-left">เลขที่</th><th class="px-4 py-3 text-left">ชื่อ</th><th class="px-4 py-3 text-left">แผนก</th><th class="px-4 py-3 text-left">ห้อง</th><th class="px-4 py-3 text-left">สถานะ</th><th class="px-4 py-3 text-left">ผู้ที่มีสิทธิอนุมัติ</th><th class="px-4 py-3 text-center">จัดการ</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($agreements as $a)
                        @php
                            $currentVal = null;
                            if ($a->send_status == 0) $currentVal = $a->commander_id;
                            elseif ($a->send_status == 1) $currentVal = $a->managerhams_id;
                            elseif ($a->send_status == 2) $currentVal = $a->Committee_id;
                            $isMyTurn =  ($currentVal && Auth::id() == $currentVal);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $isMyTurn ? 'row-attention' : '' }}">
                            <td class="px-4 py-3 font-mono text-xs font-medium">{{ $a->agreement_code }}</td>
                            <td class="px-4 py-3">{{ $a->full_name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $a->department }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $a->residence_address }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($a->send_status) }}">
                                    {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($a->send_status) }}
                                </span>
                            </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-semibold">
                            @if($a->send_status < 3)
                                @php
                                    $currentLevel = '';
                                    $currentVal = null;
                                    if ($a->send_status == 0) { $currentLevel = 'commander'; $currentVal = $a->commander_id; }
                                    elseif ($a->send_status == 1) { $currentLevel = 'manager'; $currentVal = $a->managerhams_id; }
                                    elseif ($a->send_status == 2) { $currentLevel = 'committee'; $currentVal = $a->Committee_id; }
                                @endphp
                                
                                @if($currentLevel)
                                <select onchange="updateApprover('agreement', {{ $a->agreement_id }}, '{{ $currentLevel }}', this.value)" 
                                    class="select2 text-[10px] py-1 border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 focus:ring-red-500 h-8 w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" {{ $currentVal == $ap->id ? 'selected' : '' }}>{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                                @else - @endif
                            @else
                                <span class="text-gray-400 italic">ดำเนินการแล้ว</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-1 flex-col items-center">
                                @if($a->send_status < 3)
                                    @if(Auth::id() == $currentVal)
                                        <span class="block text-[9px] text-red-500 font-bold mb-1 animate-bounce"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                                    @endif
                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center border border-emerald-200" title="อนุมัติ"><i class="fa-solid fa-check text-xs"></i></button></form>
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center border border-red-200" title="ไม่อนุมัติ"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                    </div>
                                @endif
                                <div class="flex gap-1 mt-1">
                                    <a href="{{ route('housing.request_detail', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" 
                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center" title="รายละเอียด">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('housing.agreement.pdf', $a->agreement_id) }}" target="_blank" 
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="PDF">
                                    <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('housing.destroy', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center" title="ลบ"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>ไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">{{ $agreements->links() }}</div>
    </div>
    @endif

    {{-- TAB: Guests --}}
    @if($tab == 'guests')
    <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                    <tr><th class="px-4 py-3 text-left">เลขที่</th><th class="px-4 py-3 text-left">ชื่อผู้ขอ</th><th class="px-4 py-3 text-left">ช่วงวันที่</th><th class="px-4 py-3 text-left">จำนวนผู้เข้าพัก</th><th class="px-4 py-3 text-left">สถานะ</th><th class="px-4 py-3 text-left">ผู้ที่มีสิทธิอนุมัติ</th><th class="px-4 py-3 text-center">จัดการ</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($guests as $g)
                        @php
                            $currentVal = null;
                            if ($g->send_status == 0) $currentVal = $g->commander_id;
                            elseif ($g->send_status == 1) $currentVal = $g->managerhams_id;
                            elseif ($g->send_status == 2) $currentVal = $g->Committee_id;
                            $isMyTurn =  ($currentVal && Auth::id() == $currentVal);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $isMyTurn ? 'row-attention' : '' }}">
                            <td class="px-4 py-3 font-mono text-xs font-medium">{{ $g->resident_guest_code }}</td>
                            <td class="px-4 py-3">{{ $g->first_name }} {{ $g->last_name }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $g->start_date ? \Carbon\Carbon::parse($g->start_date)->format('d/m/Y') : '' }} - {{ $g->end_date ? \Carbon\Carbon::parse($g->end_date)->format('d/m/Y') : '' }}</td>
                            <td class="px-4 py-3 text-center">{{ $g->members->count() }} คน</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($g->send_status) }}">
                                    {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($g->send_status) }}
                                </span>
                            </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-semibold">
                            @if($g->send_status < 3)
                                @php
                                    $currentLevel = '';
                                    $currentVal = null;
                                    if ($g->send_status == 0) { $currentLevel = 'commander'; $currentVal = $g->commander_id; }
                                    elseif ($g->send_status == 1) { $currentLevel = 'manager'; $currentVal = $g->managerhams_id; }
                                    elseif ($g->send_status == 2) { $currentLevel = 'committee'; $currentVal = $g->Committee_id; }
                                @endphp
                                
                                @if($currentLevel)
                                <select onchange="updateApprover('guest', {{ $g->resident_guest_id }}, '{{ $currentLevel }}', this.value)" 
                                    class="select2 text-[10px] py-1 border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 focus:ring-red-500 h-8 w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" {{ $currentVal == $ap->id ? 'selected' : '' }}>{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                                @else - @endif
                            @else
                                <span class="text-gray-400 italic">ดำเนินการแล้ว</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-1 flex-col items-center">
                                @if($g->send_status < 3)
                                    @if(Auth::id() == $currentVal)
                                        <span class="block text-[9px] text-red-500 font-bold mb-1 animate-bounce"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                                    @endif
                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center border border-emerald-200"><i class="fa-solid fa-check text-xs"></i></button></form>
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center border border-red-200"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                    </div>
                                @endif
                                <div class="flex gap-1 mt-1">
                                    <a href="{{ route('housing.request_detail', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" 
                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center" title="รายละเอียด">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('housing.guest.pdf', $g->resident_guest_id) }}" target="_blank" 
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="PDF">
                                    <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('housing.destroy', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>ไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">{{ $guests->links() }}</div>
    </div>
    @endif

    {{-- TAB: Leaves --}}
    @if($tab == 'leaves')
    <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                    <tr><th class="px-4 py-3 text-left">เลขที่</th><th class="px-4 py-3 text-left">ชื่อ</th><th class="px-4 py-3 text-left">ห้อง</th><th class="px-4 py-3 text-left">วันย้ายออก</th><th class="px-4 py-3 text-left">สถานะ</th><th class="px-4 py-3 text-left">ผู้ที่มีสิทธิอนุมัติ</th><th class="px-4 py-3 text-center">จัดการ</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($leaves as $l)
                        @php
                            $currentVal = null;
                            if ($l->send_status == 0) $currentVal = $l->managerhams_id;
                            elseif ($l->send_status == 2) $currentVal = $l->Committee_id;
                            $isMyTurn =  ($currentVal && Auth::id() == $currentVal);
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 {{ $isMyTurn ? 'row-attention' : '' }}">
                            <td class="px-4 py-3 font-mono text-xs font-medium">{{ $l->residence_leaves_code }}</td>
                            <td class="px-4 py-3">{{ $l->first_name }} {{ $l->last_name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $l->room_number }}</td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $l->move_out_date ? \Carbon\Carbon::parse($l->move_out_date)->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($l->send_status) }}">
                                    {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($l->send_status) }}
                                </span>
                            </td>
                        <td class="px-4 py-3 text-gray-500 text-xs font-semibold">
                            @if($l->send_status < 3)
                                @php
                                    $currentLevel = '';
                                    $currentVal = null;
                                    if ($l->send_status == 0) { $currentLevel = 'manager'; $currentVal = $l->managerhams_id; }
                                    elseif ($l->send_status == 2) { $currentLevel = 'committee'; $currentVal = $l->Committee_id; }
                                @endphp
                                
                                @if($currentLevel)
                                <select onchange="updateApprover('leave', {{ $l->residence_leaves_id }}, '{{ $currentLevel }}', this.value)" 
                                    class="select2 text-[10px] py-1 border-gray-300 rounded-lg bg-white dark:bg-gray-700 dark:border-gray-600 focus:ring-red-500 h-8 w-full">
                                    <option value="">เลือกผู้อนุมัติ</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}" {{ $currentVal == $ap->id ? 'selected' : '' }}>{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                                @else - @endif
                            @else
                                <span class="text-gray-400 italic">ดำเนินการแล้ว</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-1 flex-col items-center">
                                @if($l->send_status < 3)
                                    @if(Auth::id() == $currentVal)
                                        <span class="block text-[9px] text-red-500 font-bold mb-1 animate-bounce"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                                    @endif
                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center border border-emerald-200" title="อนุมัติ"><i class="fa-solid fa-check text-xs"></i></button></form>
                                        <form method="POST" action="{{ route('housing.approve', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center border border-red-200" title="ไม่อนุมัติ"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                    </div>
                                @endif
                                <div class="flex gap-1 mt-1">
                                    <a href="{{ route('housing.request_detail', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" 
                                    class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-100 flex items-center justify-center" title="รายละเอียด">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('housing.leave.pdf', $l->residence_leaves_id) }}" target="_blank" 
                                    class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center" title="PDF">
                                    <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('housing.destroy', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center" title="ลบ"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>ไม่มีข้อมูล</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">{{ $leaves->links() }}</div>
    </div>
    @endif

    {{-- TAB: Repairs --}}
    @if($tab == 'repairs')
    <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="px-4 py-3 bg-emerald-50 dark:bg-emerald-900/10 border-b border-emerald-100 dark:border-emerald-800/50 flex justify-between items-center">
            <h3 class="font-bold text-emerald-800 dark:text-emerald-400 text-sm flex items-center gap-2"><i class="fa-solid fa-list-check"></i> รายการแจ้งซ่อม</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                    <tr>
                        <th class="px-4 py-4 text-left">Code</th>
                        <th class="px-4 py-4 text-left">ผู้แจ้ง-ห้องพัก</th>
                        <th class="px-4 py-4 text-left">รายละเอียด</th>
                        <th class="px-4 py-4 text-left">ผู้ดำเนินการ</th>
                        <th class="px-4 py-4 text-left">สถานะ</th>
                        <th class="px-4 py-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($repairs as $r)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-4 py-4 align-top font-mono text-xs font-bold text-gray-700 dark:text-gray-300">#{{ $r->repair_code }}</td>
                        <td class="px-4 py-4 align-top">
                            <p class="font-bold text-gray-800 dark:text-white">{{ $r->user->fullname ?? '-' }}</p>
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-700 rounded-md text-[10px] font-bold text-slate-500">ห้อง {{ $r->room->room_number ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <p class="font-bold text-gray-700 dark:text-gray-200 mb-1">{{ $r->title }}</p>
                            <p class="text-xs text-gray-500 line-clamp-2 max-w-xs">{{ $r->description }}</p>
                            @if($r->images)
                                <div class="flex gap-1 mt-2">
                                    @foreach($r->images as $img)
                                        <a href="{{ asset($img) }}" target="_blank" class="w-8 h-8 rounded border border-gray-200 overflow-hidden group">
                                            <img src="{{ asset($img) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if($r->status == 0)
                                <select onchange="assignTechnician({{ $r->id }}, this.value)" 
                                    class="select2 text-[10px] h-8 border-gray-200 rounded-lg dark:bg-gray-700 w-full min-w-[140px] focus:ring-emerald-500">
                                    <option value="">เลือกช่างผู้ดูแล</option>
                                    @foreach($approvers as $ap)
                                        <option value="{{ $ap->id }}">{{ $ap->fullname }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 text-[10px]"><i class="fa-solid fa-user-gear"></i></div>
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-700 dark:text-gray-200 leading-tight">{{ $r->technician->fullname ?? '-' }}</p>
                                        <p class="text-[9px] text-gray-400">มอบหมายแล้ว</p>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @php
                                $statusConf = [
                                    0 => ['รอแอดมิน','bg-amber-50 text-amber-600 border-amber-200','fa-regular fa-clock'],
                                    1 => ['กำลังดำเนินการ','bg-blue-50 text-blue-600 border-blue-200','fa-solid fa-gear fa-spin'],
                                    2 => ['ซ่อมเสร็จสิ้น','bg-emerald-50 text-emerald-600 border-emerald-200','fa-solid fa-check-double'],
                                    3 => ['ยกเลิก','bg-red-50 text-red-600 border-red-200','fa-solid fa-ban']
                                ];
                                $sc = $statusConf[$r->status];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $sc[1] }}">
                                <i class="{{ $sc[2] }}"></i> {{ $sc[0] }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center align-top">
                            <div class="flex justify-center gap-1">
                                @if($r->status == 1)
                                    <button onclick="finishRepairTask({{ $r->id }})" class="w-8 h-8 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-md flex items-center justify-center transition-all" title="ปิดงานซ่อม">
                                        <i class="fa-solid fa-check-to-slot text-xs"></i>
                                    </button>
                                @endif
                                <button type="button" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center" title="รายละเอียดเพิ่มเติม">
                                    <i class="fa-solid fa-ellipsis-v text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400 italic font-medium"><i class="fa-solid fa-box-open text-3xl mb-3 block opacity-20"></i> ไม่มีข้อมูลการแจ้งซ่อมยื่นเข้ามา</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4 border-t border-gray-100 dark:border-gray-700">{{ $repairs->links() }}</div>
    </div>
    @endif
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        placeholder: 'เลือกผู้อนุมัติ',
        allowClear: true,
        selectionCssClass: 'select2-custom-selection',
        dropdownCssClass: 'select2-custom-dropdown'
    });
});

function updateApprover(type, id, level, approverId) {
    if (!approverId) return;
    
    fetch('{{ route("housing.update_approver") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            id: id,
            approver_level: level,
            approver_id: approverId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Optional: show a small toast or success indicator
            console.log('Approver updated successfully');
        } else {
            alert('เกิดข้อผิดพลาดในการอัปเดตผู้อนุมัติ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการส่งข้อมูล');
    });
}

function assignTechnician(repairId, technicianId) {
    if (!technicianId) return;
    
    Swal.fire({
        title: 'มอบหมายช่าง?',
        text: 'ต้องการมอบหมายงานซ่อมนี้และเปลี่ยนสถานะห้องเป็น "ซ่อม/ปรับปรุง" ใช่หรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        confirmButtonText: 'ยืนยันมอบหมาย'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("housing.repair.assign") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ repair_id: repairId, technician_id: technicianId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('สำเร็จ!', 'มอบหมายงานและปรับสถานะห้องแล้ว', 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', 'ไม่สามารถมอบหมายงานได้', 'error');
                }
            });
        }
    });
}

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'ค้นหารายชื่อ...',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection

@section('styles')
<style>
    .select2-container--default .select2-selection--single {
        border-radius: 0.5rem;
        border-color: #d1d5db;
        height: 2rem;
        display: flex;
        align-items: center;
        font-size: 10px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 1.75rem;
    }
    .dark .select2-container--default .select2-selection--single {
        background-color: #374151;
        border-color: #4b5563;
        color: white;
    }
    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white;
    }
    .select2-dropdown {
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #e5e7eb;
        font-size: 10px;
    }
    .dark .select2-dropdown {
        background-color: #1f2937;
        border-color: #374151;
        color: white;
    }
    .dark .select2-results__option--highlighted[aria-selected] {
        background-color: #dc2626;
    }
</style>
@endsection


