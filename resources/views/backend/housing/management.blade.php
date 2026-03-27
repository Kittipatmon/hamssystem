@extends('layouts.housing.apphousing')
@section('title', 'จัดการข้อมูลบ้านพัก')
@section('content')
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
    <div class="flex flex-wrap gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl">
        @php $tabs = ['requests' => ['คำขอเข้าพัก','fa-file-circle-plus','red'], 'agreements' => ['ข้อตกลง','fa-file-signature','blue'], 'guests' => ['นำญาติเข้าพัก','fa-people-arrows','purple'], 'leaves' => ['ขอย้ายออก','fa-right-from-bracket','orange']]; @endphp
        @foreach($tabs as $key => $info)
        <a href="{{ route('housing.management', array_merge(request()->query(), ['tab' => $key])) }}"
            class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all {{ $tab == $key ? 'bg-white dark:bg-gray-700 text-gray-800 dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}">
            <i class="fa-solid {{ $info[1] }} text-{{ $info[2] }}-500"></i> {{ $info[0] }}
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
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3 font-mono text-xs font-medium">{{ $r->requests_code }}</td>
                        <td class="px-4 py-3">{{ $r->first_name }} {{ $r->last_name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $r->department }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($r->send_status) }}">{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($r->send_status) }}</span></td>
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
                            <div class="flex justify-center gap-1">
                                @if($r->send_status < 3)
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'request', 'id' => $r->id]) }}" class="inline">
                                    @csrf <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center" title="อนุมัติ"><i class="fa-solid fa-check text-xs"></i></button>
                                </form>
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'request', 'id' => $r->id]) }}" class="inline">
                                    @csrf <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="ไม่อนุมัติ"><i class="fa-solid fa-xmark text-xs"></i></button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('housing.destroy', ['type' => 'request', 'id' => $r->id]) }}" onsubmit="return confirm('ยืนยันลบรายการนี้?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center" title="ลบ"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </form>
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
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3 font-mono text-xs font-medium">{{ $a->agreement_code }}</td>
                        <td class="px-4 py-3">{{ $a->full_name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $a->department }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $a->residence_address }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($a->send_status) }}">{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($a->send_status) }}</span></td>
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
                            <div class="flex justify-center gap-1">
                                @if($a->send_status < 3)
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center" title="อนุมัติ"><i class="fa-solid fa-check text-xs"></i></button></form>
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center" title="ไม่อนุมัติ"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                @endif
                                <form method="POST" action="{{ route('housing.destroy', ['type' => 'agreement', 'id' => $a->agreement_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center" title="ลบ"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
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
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3 font-mono text-xs font-medium">{{ $g->resident_guest_code }}</td>
                        <td class="px-4 py-3">{{ $g->first_name }} {{ $g->last_name }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $g->start_date ? \Carbon\Carbon::parse($g->start_date)->format('d/m/Y') : '' }} - {{ $g->end_date ? \Carbon\Carbon::parse($g->end_date)->format('d/m/Y') : '' }}</td>
                        <td class="px-4 py-3 text-center">{{ $g->members->count() }} คน</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($g->send_status) }}">{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($g->send_status) }}</span></td>
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
                            <div class="flex justify-center gap-1">
                                @if($g->send_status < 3)
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center"><i class="fa-solid fa-check text-xs"></i></button></form>
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                @endif
                                <form method="POST" action="{{ route('housing.destroy', ['type' => 'guest', 'id' => $g->resident_guest_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
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
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                        <td class="px-4 py-3 font-mono text-xs font-medium">{{ $l->residence_leaves_code }}</td>
                        <td class="px-4 py-3">{{ $l->first_name }} {{ $l->last_name }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $l->room_number }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $l->move_out_date ? \Carbon\Carbon::parse($l->move_out_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($l->send_status) }}">{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($l->send_status) }}</span></td>
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
                            <div class="flex justify-center gap-1">
                                @if($l->send_status < 3)
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="approve"><button type="submit" class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 flex items-center justify-center"><i class="fa-solid fa-check text-xs"></i></button></form>
                                <form method="POST" action="{{ route('housing.approve', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" class="inline">@csrf <input type="hidden" name="action" value="reject"><button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fa-solid fa-xmark text-xs"></i></button></form>
                                @endif
                                <form method="POST" action="{{ route('housing.destroy', ['type' => 'leave', 'id' => $l->residence_leaves_id]) }}" onsubmit="return confirm('ยืนยันลบ?')" class="inline">@csrf @method('DELETE')<button type="submit" class="w-7 h-7 rounded-lg bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-red-500 flex items-center justify-center"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
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


