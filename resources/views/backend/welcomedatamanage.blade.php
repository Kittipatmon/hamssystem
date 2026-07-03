@extends('layouts.sidebar')

@section('title', 'ระบบตรวจสอบการแจ้งเตือนส่วนกลาง')

@section('content')
<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-8 font-noto">
        
        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (COMMAND CENTER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border-l-4 border-l-amber-500 border border-slate-300 dark:border-zinc-800 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded mb-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest">LIVE SYSTEM ALERTS</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    ศูนย์ควบคุมการแจ้งเตือนส่วนกลาง <span class="text-slate-400 font-medium">(Command Center)</span>
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    รายการคำขอเบิกพัสดุ การจองห้องประชุม การจองรถ และงานบ้านพักพนักงานที่อยู่ในสถานะ <span class="font-bold text-amber-600">รอดำเนินการ</span>
                </p>
            </div>
            
            <div class="flex items-center bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-6 py-4 rounded-lg min-w-[200px]">
                <div class="flex-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL PENDING TASKS</p>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-slate-900 dark:text-white leading-none">
                            {{ $requisitionCount + $reservationCount + $vehicleBookingCount + $housingTasksCount }}
                        </span>
                        <span class="text-sm font-semibold text-slate-500">รายการ</span>
                    </div>
                </div>
                <div class="w-12 h-12 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 text-amber-600 rounded">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             KPI SUMMARY TILES
             ════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $statCards = [
                    ['title' => 'คำขอเบิกพัสดุรอตรวจสอบ', 'count' => $requisitionCount, 'color' => 'blue', 'icon' => 'fa-box-open', 'link' => route('requisitions.reqlistall')],
                    ['title' => 'ห้องประชุมรออนุมัติ', 'count' => $reservationCount, 'color' => 'emerald', 'icon' => 'fa-door-open', 'link' => route('backend.bookingmeeting.reservations.index')],
                    ['title' => 'การจองรถรออนุมัติ', 'count' => $vehicleBookingCount, 'color' => 'amber', 'icon' => 'fa-car', 'link' => route('bookingcar.dashboard')],
                    ['title' => 'งานบ้านพักรอดำเนินการ', 'count' => $housingTasksCount, 'color' => 'purple', 'icon' => 'fa-building-user', 'link' => route('housing.management')],
                ];
            @endphp

            @foreach($statCards as $stat)
            <a href="{{ $stat['link'] }}" class="bg-white dark:bg-zinc-900 border-t-4 border-{{ $stat['color'] }}-500 border border-slate-300 dark:border-zinc-800 p-5 flex items-center justify-between shadow-sm hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors group">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase mb-1">{{ $stat['title'] }}</p>
                    <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ $stat['count'] }}</p>
                </div>
                <div class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-zinc-800 text-{{ $stat['color'] }}-600 rounded group-hover:bg-{{ $stat['color'] }}-100 transition-colors">
                    <i class="fa-solid {{ $stat['icon'] }} text-lg"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             DATA TABLES (CLINICAL/LEDGER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        
        {{-- 1. REQUISITIONS TABLE --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-sm">
                        <i class="fa-solid fa-box text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการคำขอเบิกพัสดุ</h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border border-blue-200">{{ $requisitions->count() }} รายการ</span>
                </div>
                <a href="{{ route('requisitions.reqlistall') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-zinc-800/80 border-b-2 border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">รหัสคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">รายละเอียด</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">จำนวน/มูลค่า</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">วันที่ส่งคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($requisitions as $req)
                        <tr class="hover:bg-blue-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6 text-sm font-bold text-slate-900 dark:text-white">REQ-{{ $req->requisitions_code ?? $req->requisitions_id }}</td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200">คำขอเบิกพัสดุ/อุปกรณ์สำนักงาน</div>
                                <div class="text-xs text-slate-500">รหัสอ้างอิงภายใน: {{ $req->id_requisitions_items ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-bold text-slate-700">{{ $req->item_quantity ?? 0 }} รายการ</div>
                                <div class="text-xs text-slate-500 font-mono">฿{{ number_format($req->total_price ?? 0, 2) }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm text-slate-700 dark:text-zinc-300">{{ $req->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $req->created_at->format('H:i') }} น.</div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold uppercase rounded">
                                    {{ $req->status_label ?? $req->status ?? 'รอตรวจสอบ' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('requisitions.reqlistall') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-blue-100 text-slate-600 hover:text-blue-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900">
                                <i class="fa-solid fa-box-open text-2xl mb-2"></i>
                                <p class="text-sm font-bold">ไม่มีรายการคำขอเบิกพัสดุใหม่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. RESERVATIONS TABLE --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-600 text-white rounded-sm">
                        <i class="fa-solid fa-door-open text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการจองห้องประชุมรออนุมัติ</h2>
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded border border-emerald-200">{{ $reservations->count() }} รายการ</span>
                </div>
                <a href="{{ route('backend.bookingmeeting.reservations.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-800 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-zinc-800/80 border-b-2 border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">รหัสการจอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">หัวข้อการประชุม / สถานที่</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-48">ช่วงเวลาที่จอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">ผู้เข้าร่วม</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($reservations as $res)
                        <tr class="hover:bg-emerald-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6 text-sm font-bold text-slate-900 dark:text-white">RES-{{ $res->reservation_code }}</td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200 line-clamp-1">{{ $res->topic }}</div>
                                <div class="text-xs text-slate-600 font-medium flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-emerald-500"></i> {{ $res->room->room_name ?? 'ไม่ได้ระบุ' }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-bold text-slate-700 bg-slate-100 dark:bg-zinc-800 inline-block px-2 py-0.5 border border-slate-200 dark:border-zinc-700 rounded text-center">
                                    {{ $res->start_time ?? '-' }} <br> <span class="text-xs font-normal text-slate-500">ถึง</span> {{ $res->end_time ?? '-' }}
                                </div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <span class="text-sm font-bold text-slate-700">{{ $res->attendees ?? 0 }}</span> <span class="text-xs text-slate-500">ท่าน</span>
                            </td>
                            <td class="py-3 px-6">
                                <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold uppercase rounded">
                                    {{ $res->status == 'รออนุมัติ' ? 'รอเจ้าหน้าที่ยืนยัน' : $res->status }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('backend.bookingmeeting.reservations.index') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-emerald-100 text-slate-600 hover:text-emerald-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900">
                                <i class="fa-solid fa-calendar-check text-2xl mb-2"></i>
                                <p class="text-sm font-bold">ไม่มีรายการจองห้องประชุมใหม่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 3. VEHICLE BOOKINGS TABLE --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white rounded-sm">
                        <i class="fa-solid fa-car text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการจองรถรออนุมัติ</h2>
                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded border border-amber-200">{{ $vehicleBookings->count() }} รายการ</span>
                </div>
                <a href="{{ route('bookingcar.dashboard') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-zinc-800/80 border-b-2 border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">รหัสการจอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">วัตถุประสงค์ / จุดหมาย</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-48">ช่วงเวลาที่จอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">คนขับรถ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($vehicleBookings as $veh)
                        <tr class="hover:bg-amber-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6 text-sm font-bold text-slate-900 dark:text-white">VEH-{{ $veh->booking_code }}</td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200 line-clamp-1">{{ $veh->purpose }}</div>
                                <div class="text-xs text-slate-600 font-medium flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-map-pin text-amber-500"></i> {{ $veh->destination }} ({{ $veh->province }})
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-bold text-slate-700 bg-slate-100 dark:bg-zinc-800 inline-block px-2 py-0.5 border border-slate-200 dark:border-zinc-700 rounded text-center">
                                    {{ $veh->start_time ?? '-' }} <br> <span class="text-xs font-normal text-slate-500">ถึง</span> {{ $veh->end_time ?? '-' }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm text-slate-700 dark:text-zinc-300">{{ $veh->driver_name ?? 'ยังไม่จัดสรร' }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold uppercase rounded">
                                    {{ $veh->status == 'รออนุมัติ' ? 'รอแผนก HAMS อนุมัติ' : $veh->status }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('bookingcar.dashboard') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-amber-100 text-slate-600 hover:text-amber-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900">
                                <i class="fa-solid fa-car-side text-2xl mb-2"></i>
                                <p class="text-sm font-bold">ไม่มีรายการจองรถใหม่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. HOUSING TASKS TABLE --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-purple-600 text-white rounded-sm">
                        <i class="fa-solid fa-building-user text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการงานบ้านพักพนักงาน</h2>
                    <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2.5 py-0.5 rounded border border-purple-200">{{ $housingTasks->count() }} รายการ</span>
                </div>
                <a href="{{ route('housing.management') }}" class="text-sm font-bold text-purple-600 hover:text-purple-800 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-zinc-800/80 border-b-2 border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">รหัสอ้างอิง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">ประเภทรายการ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">สถานที่ / รายละเอียด</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">วันที่ส่งคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($housingTasks as $hse)
                            @php
                                $isHousingRequest = in_array(($hse->task_type ?? ''), ['request', 'agreement', 'guest', 'leave']);
                                $currentStep = 'รอกำเนินการ';
                                if ($isHousingRequest) {
                                    $steps = [
                                        ['label' => 'ผู้บังคับบัญชา', 'status' => $hse->commander_status],
                                        ['label' => 'ผจก. HAMS', 'status' => $hse->managerhams_status],
                                        ['label' => 'คณะกรรมการ', 'status' => $hse->Committee_status]
                                    ];
                                    foreach($steps as $s) {
                                        if ($s['status'] === 0 || is_null($s['status'])) {
                                            $currentStep = 'รอ' . $s['label'];
                                            break;
                                        }
                                    }
                                }

                                $code = match($hse->task_type ?? '') {
                                    'repair' => 'RPR-' . $hse->repair_code,
                                    'request' => 'HSG-REQ-' . $hse->requests_code,
                                    'agreement' => 'HSG-AGR-' . $hse->agreement_code,
                                    'guest' => 'HSG-GST-' . $hse->guest_code,
                                    'leave' => 'HSG-LVE-' . $hse->leave_code,
                                    default => 'HSG-' . ($hse->id ?? '-')
                                };

                                $typeLabel = match($hse->task_type ?? '') {
                                    'repair' => 'แจ้งซ่อมบำรุง',
                                    'request' => 'คำขอเข้าพักอาศัย',
                                    'agreement' => 'ข้อตกลงเข้าพัก',
                                    'guest' => 'ขอนำญาติเข้าพัก',
                                    'leave' => 'แจ้งย้ายออก',
                                    default => 'อื่นๆ'
                                };
                                
                                $locationLabel = $hse->room->room_number ?? $hse->residence_address ?? $hse->site ?? 'บ้านพักพนักงาน';
                            @endphp
                        <tr class="hover:bg-purple-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6 text-sm font-bold text-slate-900 dark:text-white">{{ $code }}</td>
                            <td class="py-3 px-6">
                                <span class="bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 px-2 py-1 rounded border border-purple-200 text-xs font-bold">{{ $typeLabel }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200 line-clamp-1">{{ $hse->title ?? $hse->topic ?? '-' }}</div>
                                <div class="text-xs text-slate-600 font-medium flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-building text-purple-500"></i> {{ $locationLabel }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm text-slate-700 dark:text-zinc-300">{{ $hse->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $hse->created_at->format('H:i') }} น.</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="inline-block px-2 py-1 bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold uppercase rounded mb-1">
                                    {{ $isHousingRequest ? ($hse->Committee_status === 0 || is_null($hse->Committee_status) ? 'รออนุมัติ' : 'รอดำเนินการ') : ($hse->status ?? 'รอดำเนินการ') }}
                                </div>
                                <div class="text-[10px] font-bold text-amber-600">{{ $currentStep }}</div>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('housing.management') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-purple-100 text-slate-600 hover:text-purple-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900">
                                <i class="fa-solid fa-house-chimney text-2xl mb-2"></i>
                                <p class="text-sm font-bold">ไม่มีรายการงานบ้านพักใหม่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection