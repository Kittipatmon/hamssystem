@extends('layouts.sidebar')

@section('title', 'ระบบตรวจสอบการแจ้งเตือนส่วนกลาง')

@section('content')
<div class="min-h-screen bg-[#F8F8F9] dark:bg-[#161D31] px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-8 font-sans">
        
        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (COMMAND CENTER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 dark:bg-amber-900/20 border-0 dark:border-amber-800 rounded mb-3">
                    
                    <span class="text-[10px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest">LIVE SYSTEM ALERTS</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    ศูนย์ควบคุมการแจ้งเตือนส่วนกลาง <span class="text-slate-400 font-medium">(Command Center)</span>
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    รายการคำขอเบิกพัสดุ การจองห้องประชุม การจองรถ และงานบ้านพักพนักงานที่อยู่ในสถานะ <span class="font-bold text-amber-600">รอดำเนินการ</span>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- KPI Box -->
                <div class="flex items-center bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-6 py-4 rounded-lg min-w-[200px]">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL PENDING TASKS</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-4xl font-bold text-slate-900 dark:text-white leading-none">
                                {{ $requisitionCount + $vehicleBookingCount + $housingTasksCount + $parkingReservationsCount }}
                            </span>
                            <span class="text-sm font-semibold text-slate-500">รายการ</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 text-amber-600 rounded">
                        <i class="fa-solid fa-bell text-xl"></i>
                    </div>
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
                    ['title' => 'การจองรถรออนุมัติ', 'count' => $vehicleBookingCount, 'color' => 'amber', 'icon' => 'fa-car', 'link' => route('bookingcar.dashboard')],
                    ['title' => 'งานบ้านพักรอดำเนินการ', 'count' => $housingTasksCount, 'color' => 'purple', 'icon' => 'fa-building-user', 'link' => route('housing.management')],
                    ['title' => 'จองที่จอดรถรอดำเนินการ', 'count' => $parkingReservationsCount, 'color' => 'rose', 'icon' => 'fa-square-parking', 'link' => auth()->user()->is_hams_admin || \App\Models\Department::where('manager_id', auth()->id())->exists() ? route('parking.visitors.approvals') : route('parking.employee_reservations.index')],
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
        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm overflow-hidden">
            <div class="bg-transparent border-b border-slate-200 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-sm">
                        <i class="fa-solid fa-box text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการคำขอเบิกพัสดุ</h2>
                    <span class="bg-blue-50 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded border-0">{{ $requisitions->count() }} รายการ</span>
                </div>
                <a href="{{ route('requisitions.reqlistall') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#283046] border-b border-slate-200 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">รหัสคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">รายละเอียด</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">จำนวน/มูลค่า</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">วันที่ส่งคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
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
                                <span class="inline-block px-2 py-1 bg-amber-50 text-amber-800 border-0 text-[11px] font-bold uppercase rounded">
                                    {{ $req->status_label ?? $req->status ?? 'รอตรวจสอบ' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('requisitions.reqlistall') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
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




        {{-- 3. VEHICLE BOOKINGS TABLE --}}
        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm overflow-hidden">
            <div class="bg-transparent border-b border-slate-200 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-amber-500 text-white rounded-sm">
                        <i class="fa-solid fa-car text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการจองรถรออนุมัติ</h2>
                    <span class="bg-amber-50 text-amber-800 text-xs font-bold px-2.5 py-0.5 rounded border-0">{{ $vehicleBookings->count() }} รายการ</span>
                </div>
                <a href="{{ route('bookingcar.dashboard') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#283046] border-b border-slate-200 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">รหัสการจอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">วัตถุประสงค์ / จุดหมาย</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-48">ช่วงเวลาที่จอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">คนขับรถ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-32">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
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
                                <span class="inline-block px-2 py-1 bg-amber-50 text-amber-800 border-0 text-[11px] font-bold uppercase rounded">
                                    {{ $veh->status == 'รออนุมัติ' ? 'รอแผนก HAMS อนุมัติ' : $veh->status }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ route('bookingcar.dashboard') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
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
        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm overflow-hidden">
            <div class="bg-transparent border-b border-slate-200 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
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
                        <tr class="bg-slate-50 dark:bg-[#283046] border-b border-slate-200 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">รหัสอ้างอิง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">ประเภทรายการ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">สถานที่ / รายละเอียด</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">วันที่ส่งคำขอ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
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
                                <div class="inline-block px-2 py-1 bg-amber-50 text-amber-800 border-0 text-[11px] font-bold uppercase rounded mb-1">
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


        {{-- 5. PARKING RESERVATIONS TABLE --}}
        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm overflow-hidden">
            <div class="bg-transparent border-b border-slate-200 dark:border-zinc-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-rose-600 text-white rounded-sm">
                        <i class="fa-solid fa-square-parking text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">รายการจองที่จอดรถรอดำเนินการ</h2>
                    <span class="bg-rose-50 text-rose-800 text-xs font-bold px-2.5 py-0.5 rounded border-0">{{ $pendingParkingReservations->count() }} รายการ</span>
                </div>
                <a href="{{ auth()->user()->is_hams_admin || \App\Models\Department::where('manager_id', auth()->id())->exists() ? route('parking.visitors.approvals') : route('parking.employee_reservations.index') }}" class="text-sm font-bold text-rose-600 hover:text-rose-700 uppercase tracking-wide">จัดการทั้งหมด &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#283046] border-b border-slate-200 dark:border-zinc-700">
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">ประเภทการจอง</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider">ผู้ขอจอง / ทะเบียนรถ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-48">เวลาเช็คอิน</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-36">ช่องจอดรถ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-40">สถานะ</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-600 dark:text-zinc-300 uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($pendingParkingReservations as $pking)
                            @php
                                $isVisitor = $pking->res_type === 'visitor' || $pking->task_type === 'visitor_hams' || $pking->task_type === 'my_visitor';
                                $typeLabel = $isVisitor ? 'ผู้มาติดต่อ (Visitor)' : 'พนักงาน (Employee)';
                                $badgeColor = $isVisitor ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                
                                $applicant = $isVisitor ? ($pking->visitor_name ?? '-') : ($pking->user?->fullname ?? '-');
                                $licensePlate = $pking->license_plate ?? '-';
                                $slotName = $pking->slot?->slot_number ?? 'ยังไม่จัดสรร';
                                
                                $checkinDate = $pking->checkin_datetime ? \Carbon\Carbon::parse($pking->checkin_datetime)->format('d/m/Y H:i') . ' น.' : '-';
                                
                                $statusLabel = 'รออนุมัติ';
                                if (!$isVisitor) {
                                    if ($pking->manager_approval === 'pending') {
                                        $statusLabel = 'รอผู้บังคับบัญชาอนุมัติ';
                                    } elseif ($pking->manager_approval === 'approved' && $pking->hams_status === 'pending') {
                                        $statusLabel = 'รอแผนก HAMS อนุมัติ';
                                    }
                                }
                            @endphp
                        <tr class="hover:bg-rose-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-3 px-6 text-sm">
                                <span class="{{ $badgeColor }} px-2 py-1 rounded border text-xs font-bold">{{ $typeLabel }}</span>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-semibold text-slate-800 dark:text-zinc-200">{{ $applicant }}</div>
                                <div class="text-xs text-slate-500">ทะเบียน: {{ $licensePlate }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm text-slate-700 dark:text-zinc-300">{{ $checkinDate }}</div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="text-sm font-bold text-slate-800 dark:text-zinc-100 flex items-center gap-1">
                                    <i class="fa-solid fa-square-parking text-rose-500"></i> {{ $slotName }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <span class="inline-block px-2 py-1 bg-amber-50 text-amber-800 border-0 text-[11px] font-bold uppercase rounded">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <a href="{{ auth()->user()->is_hams_admin || \App\Models\Department::where('manager_id', auth()->id())->exists() ? route('parking.visitors.approvals') : route('parking.employee_reservations.index') }}" class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 rounded border border-slate-200 transition-colors" title="ตรวจสอบรายละเอียด">
                                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900">
                                <i class="fa-solid fa-square-parking text-2xl mb-2"></i>
                                <p class="text-sm font-bold">ไม่มีรายการจองที่จอดรถใหม่</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@section('scripts')
<script>
function toggleServicesSection(isChecked) {
    // Send AJAX request to toggle the Support and Services section display state
    fetch('{{ route('backend.settings.toggle-services') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ show_services: isChecked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: data.message,
                showConfirmButton: false,
                timer: 2500
            });
            
            // Sync with sidebar toggle
            const sidebarToggle = document.getElementById('toggle_services_btn_sidebar');
            if (sidebarToggle) {
                sidebarToggle.checked = isChecked;
            }
        }
    })
    .catch(error => {
        console.error('Error toggling services section:', error);
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่สามารถบันทึกการตั้งค่าได้สำเร็จ'
        });
        // Revert UI switch state on error
        document.getElementById('toggle_services_btn').checked = !isChecked;
    });
}
</script>
@endsection
@endsection