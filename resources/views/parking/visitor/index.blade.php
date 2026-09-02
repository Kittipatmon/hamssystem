@extends('layouts.parking.app')

@section('content')

{{-- DataTables CSS CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    /* ============ DataTable Custom Overrides ============ */
    #visitorTable_wrapper .dataTables_length label,
    #visitorTable_wrapper .dataTables_filter label {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    #visitorTable_wrapper .dataTables_length select,
    #visitorTable_wrapper .dataTables_filter input {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.625rem;
        padding: 0.35rem 0.75rem;
        font-size: 0.82rem;
        color: #334155;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        background-color: #f8fafc;
        font-family: 'Prompt', sans-serif;
    }

    #visitorTable_wrapper .dataTables_filter input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.15);
    }

    #visitorTable_wrapper .dataTables_info {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
        padding-top: 0.75rem;
    }

    #visitorTable_wrapper .dataTables_paginate {
        padding-top: 0.6rem;
    }

    #visitorTable_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        border: none !important;
        padding: 0.3rem 0.75rem !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        transition: background .15s, color .15s;
        font-family: 'Prompt', sans-serif;
    }

    #visitorTable_wrapper .dataTables_paginate .paginate_button:hover {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    #visitorTable_wrapper .dataTables_paginate .paginate_button.current,
    #visitorTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #f59e0b !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(245,158,11,.35);
    }

    #visitorTable_wrapper .dataTables_paginate .paginate_button.disabled,
    #visitorTable_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #cbd5e1 !important;
        background: transparent !important;
    }

    table.dataTable thead th {
        border-bottom: 1px solid #f1f5f9 !important;
    }

    table.dataTable tbody tr:hover {
        background-color: #fafafa !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }
</style>

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-users text-amber-500"></i> รายการจองที่จอดรถแขก
                </h2>
                <p class="text-slate-500 mt-1 font-medium">จัดการรายการจองพื้นที่สำหรับบุคคลภายนอก (Visitor)</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="openQrModal()" class="btn bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm rounded-xl">
                    <i class="fa-solid fa-qrcode mr-1"></i> QR Code ให้แขกจองเอง
                </button>
                <a href="{{ route('parking.visitors.create') }}" class="btn bg-amber-500 hover:bg-amber-600 text-white border-none shadow-lg shadow-amber-200 rounded-xl">
                    <i class="fa-solid fa-calendar-plus mr-1"></i> เพิ่มรายการจองใหม่
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-lg mb-6 rounded-xl border border-emerald-200 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                <span class="font-bold text-slate-800">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error shadow-lg mb-6 rounded-xl border border-red-200 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-xl"></i>
                <span class="font-bold text-slate-800">{{ session('error') }}</span>
            </div>
        @endif

        @php
            $resCollection = collect($reservations);
            $bookedToday = $resCollection->filter(fn($r) => \Carbon\Carbon::parse($r->created_at)->isToday())->count();
            $waitingToPark = $resCollection->where('status', 'reserved')->count();
            
            // "เข้าจอดแล้ว" (Total checked in today, including those who already left)
            $parkedToday = $resCollection->filter(fn($r) => 
                in_array($r->status, ['checked_in', 'checked_out']) && 
                ($r->checkin_datetime ? \Carbon\Carbon::parse($r->checkin_datetime)->isToday() : false)
            )->count();
            
            $cancelled = $resCollection->filter(fn($r) => in_array($r->status, ['cancelled', 'no_show']))->count();
            $guestsInside = $resCollection->where('status', 'checked_in')->count();
            
            // Calculate available slots in building
            $activeEmployeeSlotIds = \App\Models\parking\EmployeeParking::where('status', 'parking')->pluck('slot_id')->filter()->toArray();
            $activeVisitorSlotIds = \App\Models\parking\VisitorReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
            $activeEmpReservations = \App\Models\parking\EmployeeReservation::whereIn('status', ['reserved', 'checked_in'])->pluck('slot_id')->filter()->toArray();
            $occupiedSlotIds = array_unique(array_merge($activeEmployeeSlotIds, $activeVisitorSlotIds, $activeEmpReservations));
            
            $availableSlotsCount = \App\Models\parking\ParkingSlot::whereNotIn('id', $occupiedSlotIds)
                ->whereHas('zone', function($q) {
                    $q->where('building', 'ในอาคาร');
                })
                ->count();
        @endphp

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
            <!-- Card 1: จองทั้งหมดวันนี้ -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $bookedToday }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">จองทั้งหมดวันนี้</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
            </div>
            
            <!-- Card 2: รอเข้าจอด -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $waitingToPark }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">รอเข้าจอด</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>

            <!-- Card 3: เข้าจอดแล้ว -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $parkedToday }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">เข้าจอดแล้ว(วันนี้)</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Card 4: ยกเลิก / ไม่มาตามนัด -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $cancelled }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">ยกเลิก/ไม่มาตามนัด</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600 shrink-0">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>

            <!-- Card 5: ที่จอดว่าง -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $availableSlotsCount }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">ที่จอดว่าง(ช่อง)</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                    <i class="fa-solid fa-square-parking"></i>
                </div>
            </div>

            <!-- Card 6: แขกภายในปัจจุบัน -->
            <div class="bg-white rounded-2xl p-4 md:p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-1">{{ $guestsInside }}</h3>
                    <p class="text-[11px] font-bold text-slate-500">แขกในปัจจุบัน</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>

        @php
            $settings = \Illuminate\Support\Facades\Storage::exists('settings.json') ? json_decode(\Illuminate\Support\Facades\Storage::get('settings.json'), true) : [];
            $autoResetEnabled = $settings['parking_auto_reset'] ?? true;
        @endphp
        <div class="alert shadow-lg mb-6 rounded-xl border border-blue-200 bg-blue-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4 p-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-clock-rotate-left text-blue-600 text-2xl"></i>
                <div>
                    <span class="font-bold text-slate-800 block text-base">ระบบจะรีเซ็ตสถานะเป็น "ว่าง" อัตโนมัติ</span>
                    <span class="text-sm text-slate-600 block">ทุกวันเวลา 19:00 น. สำหรับรายการที่ไม่ได้กด "ล็อก" เอาไว้</span>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-5 bg-white/70 px-5 py-2.5 rounded-xl border border-blue-100 shadow-sm">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เวลาปัจจุบัน</span>
                    <div class="font-mono text-xl font-black text-blue-700 tracking-tight" id="realtime-clock">--:--:--</div>
                </div>
                
                <div class="h-8 w-px bg-slate-300 mx-1 hidden sm:block"></div>
                
                @if(Auth::check() && Auth::user()->is_hams_admin)
                <div class="flex items-center gap-3 border-t sm:border-t-0 pt-2 sm:pt-0 w-full sm:w-auto border-slate-200">
                    <span class="text-sm font-bold text-slate-600" id="auto-reset-status">{{ $autoResetEnabled ? 'เปิดทำงาน' : 'ปิดใช้งาน' }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="autoResetToggle" class="sr-only peer" {{ $autoResetEnabled ? 'checked' : '' }} onchange="toggleAutoReset(this)">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
                @endif
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 mb-2">ค้นหา</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fa-solid fa-search text-slate-400"></i>
                        </div>
                        <input type="text" id="customSearchInput" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all bg-slate-50" placeholder="ชื่อแขก, ทะเบียนรถ, บริษัท, รหัสการจอง, ชื่อผู้มาติดต่อ...">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">สถานะ</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all bg-slate-50">
                        <option value="">ทั้งหมด</option>
                        <option value="รอยืนยัน">รอยืนยัน</option>
                        <option value="ยืนยันแล้ว">ยืนยันแล้ว</option>
                        <option value="รอเข้าจอด">รอเข้าจอด</option>
                        <option value="เข้าจอดแล้ว">เข้าจอดแล้ว</option>
                        <option value="ออกแล้ว">ออกแล้ว</option>
                        <option value="ยกเลิก">ยกเลิก</option>
                        <option value="ไม่มาตามนัด">ไม่มาตามนัด</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-2">วันที่ใช้งาน</label>
                    <input type="date" id="dateFilter" class="w-full px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all bg-slate-50">
                </div>
            </div>
        </div>

        <!-- Table Data -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table id="visitorTable" class="w-full text-left border-collapse" style="width:100%">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">เวลา</th>
                            <th class="px-6 py-4">แขก</th>
                            <th class="px-6 py-4">บริษัท</th>
                            <th class="px-6 py-4">ติดต่อ</th>
                            <th class="px-6 py-4">ทะเบียน</th>
                            <th class="px-6 py-4">ช่องจอด</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-right" data-orderable="false">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @foreach ($reservations as $reservation)
                            @php
                                $visitorData = [
                                    'guest_name' => $reservation->guest_name,
                                    'company' => $reservation->company,
                                    'car_registration' => $reservation->car_registration,
                                    'phone' => $reservation->phone,
                                    'checkin' => \App\Helpers\ThaiDate::format($reservation->checkin_datetime),
                                    'duration' => $reservation->duration_hours ? $reservation->duration_hours . ' ชั่วโมง' : '-',
                                    'slot' => $reservation->slot ? $reservation->slot->slot_number . ' (' . ($reservation->slot->zone?->zone ?: 'ไม่ระบุโซน') . ')' : 'ไม่ระบุ',
                                    'contact_user' => $reservation->contactUser?->fullname,
                                    'contact_dept' => $reservation->contactUser?->department?->dept_name_th,
                                    'contact_details' => $reservation->contact_details,
                                    'manager_status' => $reservation->manager_approval,
                                    'manager_name' => $reservation->manager?->fullname ?: ($reservation->contactUser?->department?->manager?->fullname ?: 'หัวหน้าแผนก'),
                                    'manager_at' => $reservation->manager_approved_at ? \App\Helpers\ThaiDate::format($reservation->manager_approved_at) : '-',
                                    'hams_status' => $reservation->hams_status,
                                    'hams_name' => $reservation->hamsAckBy?->fullname ?: 'HAMS',
                                    'hams_at' => $reservation->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($reservation->hams_acknowledged_at) : '-'
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap" data-order="{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('Y-m-d H:i:s') }}">
                                    <div class="text-xs text-slate-600 font-semibold">
                                        <p><i class="fa-regular fa-calendar text-slate-400 mr-1 w-4"></i>{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('d/m/Y') }}</p>
                                        <p class="text-slate-500 mt-0.5"><i class="fa-regular fa-clock text-slate-300 mr-1 w-4"></i>{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('H:i') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="cursor-pointer group inline-block" onclick="showVisitorDetails(this)" data-visitor="{{ json_encode($visitorData) }}">
                                        <p class="font-bold text-slate-800 group-hover:text-amber-500 transition-colors">{{ $reservation->guest_name }} <i class="fa-solid fa-up-right-from-square text-[10px] text-slate-300 ml-1 opacity-0 group-hover:opacity-100 transition-opacity"></i></p>
                                        <p class="text-[11px] text-slate-500 mt-0.5"><i class="fa-solid fa-phone mr-1"></i> {{ $reservation->phone }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-600">
                                    {{ $reservation->company ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-600">
                                    {{ $reservation->contactUser?->fullname ?? 'ไม่ระบุ' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block px-2 py-1 bg-slate-50 text-slate-800 rounded border border-slate-200 font-bold text-xs">
                                        {{ $reservation->car_registration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($reservation->slot)
                                        @php
                                            $slotNum = $reservation->slot->slot_number;
                                            if (str_starts_with($slotNum, 'B')) {
                                                $parts = explode('_', substr($slotNum, 1));
                                                $displaySlot = "ในอาคาร ช่อง " . ($parts[0] ?? '') . " คันที่ " . ($parts[1] ?? '');
                                                $badgeStyle = "bg-indigo-50 text-indigo-700 border-indigo-100";
                                            } else {
                                                $displaySlot = "ลานจอดหลัก " . $slotNum;
                                                $badgeStyle = "bg-emerald-50 text-emerald-700 border-emerald-100";
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 border rounded font-bold text-[10px] {{ $badgeStyle }}">
                                            {{ $displaySlot }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($reservation->status === 'reserved')
                                        @if($reservation->manager_approval === 'approved' && $reservation->hams_status === 'acknowledged')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">🟠 รอเข้าจอด</span>
                                        @elseif($reservation->manager_approval === 'approved')
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">🔵 ยืนยันแล้ว</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">🟡 รอยืนยัน</span>
                                        @endif
                                    @elseif($reservation->status === 'checked_in')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">🟢 เข้าจอดแล้ว</span>
                                    @elseif($reservation->status === 'checked_out')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-slate-50 text-slate-600 border border-slate-200">⚪ ออกแล้ว</span>
                                    @elseif($reservation->status === 'cancelled')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-50 text-red-700 border border-red-100">🔴 ยกเลิก</span>
                                    @elseif($reservation->status === 'no_show')
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-zinc-800 text-zinc-100 border border-zinc-900">⚫ ไม่มาตามนัด</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        @if($reservation->status === 'reserved')
                                            @if($reservation->manager_approval === 'approved' && $reservation->hams_status === 'acknowledged')
                                                <form action="{{ route('parking.visitors.checkin', $reservation->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs bg-emerald-600 hover:bg-emerald-700 text-white border-none rounded flex items-center gap-1 font-bold">
                                                        Check-in
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('parking.visitors.cancel', $reservation->id) }}" method="POST" class="inline" onsubmit="return confirm('ยกเลิกการจองนี้?')">
                                                @csrf
                                                <button type="submit" class="btn btn-xs bg-red-50 hover:bg-red-100 text-red-700 border border-red-200/80 rounded flex items-center gap-1 font-bold">
                                                    ยกเลิก
                                                </button>
                                            </form>
                                        @elseif($reservation->status === 'checked_in')
                                            <form action="{{ route('parking.visitors.checkout', $reservation->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="btn btn-xs bg-slate-800 hover:bg-slate-900 text-white border-none rounded flex items-center gap-1 font-bold">
                                                    Check-out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 font-normal pr-3">-</span>
                                        @endif
                                        
                                        @if(in_array($reservation->status, ['reserved', 'checked_in']))
                                            <form action="{{ route('parking.visitors.toggleLock', $reservation->id) }}" method="POST" class="inline">
                                                @csrf
                                                @if($reservation->is_locked)
                                                    <button type="submit" class="btn btn-xs bg-amber-500 hover:bg-amber-600 text-white border-none rounded flex items-center gap-1 font-bold" title="ปลดล็อก">
                                                        <i class="fa-solid fa-lock"></i>
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-xs bg-slate-200 hover:bg-slate-300 text-slate-700 border-none rounded flex items-center gap-1 font-bold" title="ล็อกที่">
                                                        <i class="fa-solid fa-unlock"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- QR Code Modal -->
<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[999] flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-sm w-full overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="qrModalContent">
        <div class="p-6 text-center">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-black text-slate-800">QR Code จองที่จอดรถ</h3>
                <button onclick="closeQrModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <p class="text-xs text-slate-500 font-medium mb-6">แขก/ผู้มาติดต่อสามารถสแกน QR Code นี้ผ่านโทรศัพท์มือถือเพื่อทำรายการจองและระบุข้อมูลรถยนต์เข้าจอดได้ด้วยตัวเอง</p>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 inline-block mb-6 shadow-inner">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('parking.visitors.guestCreate')) }}" alt="QR Code" class="w-48 h-48 mx-auto rounded-lg shadow border border-white">
            </div>

            <div class="space-y-3">
                <input type="text" readonly value="{{ route('parking.visitors.guestCreate') }}" class="w-full text-center text-xs font-mono bg-slate-100 border border-slate-200 p-2.5 rounded-xl text-slate-600 focus:outline-none">
                <button onclick="copyQrLink()" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-all flex items-center justify-center gap-1.5 shadow">
                    <i class="fa-solid fa-copy"></i> คัดลอกลิงก์จอง
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Visitor Details Modal -->
<div id="visitor-details-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" aria-hidden="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="visitor-details-modal-content">
        <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-black text-lg text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-address-card text-blue-500"></i>
                รายละเอียดแขกผู้มาติดต่อ
            </h3>
            <button type="button" onclick="closeVisitorDetails()" class="text-slate-400 hover:text-rose-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-rose-50">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชื่อแขก</div>
                    <div id="modal-guest-name" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">บริษัท</div>
                    <div id="modal-company" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ทะเบียนรถ</div>
                    <div id="modal-car-reg" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เบอร์โทรศัพท์</div>
                    <div id="modal-phone" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เวลาเข้า</div>
                    <div id="modal-checkin" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="text-[10px] font-bold text-amber-500 uppercase tracking-wider mb-1">ระยะเวลาจอด (โดยประมาณ)</div>
                    <div id="modal-duration" class="text-sm font-bold text-amber-700"></div>
                </div>
                <div class="col-span-2 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ช่องจอด</div>
                    <div id="modal-slot" class="text-sm font-bold text-[#b81515]"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">ติดต่อพนักงาน / ผู้ขอจอง</div>
                    <div id="modal-contact-user" class="text-sm font-bold text-blue-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">แผนกที่ติดต่อ</div>
                    <div id="modal-contact-dept" class="text-sm font-bold text-blue-800"></div>
                </div>
                <div class="col-span-2 p-3 bg-purple-50 rounded-xl border border-purple-100">
                    <div class="text-[10px] font-bold text-purple-400 uppercase tracking-wider mb-1">รายละเอียดการติดต่อ (เรื่องที่มาติดต่อ)</div>
                    <div id="modal-contact-details" class="text-sm font-bold text-purple-800 break-words whitespace-pre-wrap"></div>
                </div>
                <div class="col-span-2 mt-2 pt-4 border-t border-slate-200">
                    <div class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-timeline text-slate-400"></i> สเตปการอนุมัติ
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-emerald-100 text-emerald-600" id="modal-manager-icon">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-slate-800">อนุมัติโดยหัวหน้าแผนก</div>
                                <div class="text-[10px] text-slate-500" id="modal-manager-text"></div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-indigo-100 text-indigo-600" id="modal-hams-icon">
                                <i class="fa-solid fa-check-double text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-slate-800">การรับทราบจาก HAMS</div>
                                <div class="text-[10px] text-slate-500" id="modal-hams-text"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button type="button" onclick="closeVisitorDetails()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm shadow-sm">
                ปิด
            </button>
        </div>
    </div>
</div>

{{-- jQuery + DataTables JS CDN --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#visitorTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'ทั้งหมด']],
        order: [[0, 'desc']], // Sort by date/time column descending
        columnDefs: [
            { orderable: false, targets: 7 } // Disable sort on action column
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/th.json'
        },
        dom: '<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4"l>rt<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4"ip>'
    });

    // Custom Search Input
    $('#customSearchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    // Custom Status Filter (Column index 6)
    $('#statusFilter').on('change', function () {
        const val = $.fn.dataTable.util.escapeRegex($(this).val());
        table.column(6).search(val ? val : '', true, false).draw();
    });

    // Custom Date Filter (Column index 0)
    $('#dateFilter').on('change', function () {
        const dateVal = $(this).val(); 
        if (dateVal) {
            const parts = dateVal.split('-');
            if (parts.length === 3) {
                const formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                table.column(0).search(formattedDate).draw();
            }
        } else {
            table.column(0).search('').draw();
        }
    });
});

function openQrModal() {
    const modal = document.getElementById('qrModal');
    const content = document.getElementById('qrModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
    }, 50);
}

function closeQrModal() {
    const modal = document.getElementById('qrModal');
    const content = document.getElementById('qrModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function copyQrLink() {
    const link = "{{ route('parking.visitors.guestCreate') }}";
    navigator.clipboard.writeText(link).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'คัดลอกลิงก์สำเร็จ',
            text: 'คัดลอกลิงก์สำหรับลงทะเบียนของแขกลงคลิปบอร์ดแล้ว',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-3xl border-0 shadow-xl'
            }
        });
    });
}

function showVisitorDetails(btn) {
    const data = JSON.parse(btn.getAttribute('data-visitor'));
    
    document.getElementById('modal-guest-name').innerText = data.guest_name || '-';
    document.getElementById('modal-company').innerText = data.company || '-';
    document.getElementById('modal-car-reg').innerText = data.car_registration || '-';
    document.getElementById('modal-phone').innerText = data.phone || '-';
    document.getElementById('modal-checkin').innerText = data.checkin || '-';
    document.getElementById('modal-duration').innerText = data.duration || '-';
    document.getElementById('modal-slot').innerText = data.slot || '-';
    document.getElementById('modal-contact-user').innerText = data.contact_user || '-';
    document.getElementById('modal-contact-dept').innerText = data.contact_dept || '-';
    document.getElementById('modal-contact-details').innerText = data.contact_details || '-';
    
    // Approval steps
    const mgrIcon = document.getElementById('modal-manager-icon');
    const mgrText = document.getElementById('modal-manager-text');
    if (data.manager_status === 'approved') {
        mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-emerald-100 text-emerald-600";
        mgrIcon.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i>';
        mgrText.innerHTML = `อนุมัติแล้ว โดย <b>${data.manager_name}</b> <br> เมื่อ ${data.manager_at}`;
    } else if (data.manager_status === 'rejected') {
        mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-rose-100 text-rose-600";
        mgrIcon.innerHTML = '<i class="fa-solid fa-xmark text-[10px]"></i>';
        mgrText.innerHTML = `ปฏิเสธ โดย <b>${data.manager_name}</b> <br> เมื่อ ${data.manager_at}`;
    } else {
        mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-amber-100 text-amber-600";
        mgrIcon.innerHTML = '<i class="fa-solid fa-clock text-[10px]"></i>';
        mgrText.innerHTML = `รอการอนุมัติ (โดย ${data.manager_name})`;
    }

    const hamsIcon = document.getElementById('modal-hams-icon');
    const hamsText = document.getElementById('modal-hams-text');
    if (data.hams_status === 'acknowledged') {
        hamsIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-indigo-100 text-indigo-600";
        hamsIcon.innerHTML = '<i class="fa-solid fa-check-double text-[10px]"></i>';
        hamsText.innerHTML = `รับทราบแล้ว โดย <b>${data.hams_name}</b> <br> เมื่อ ${data.hams_at}`;
    } else {
        hamsIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-slate-100 text-slate-400";
        hamsIcon.innerHTML = '<i class="fa-solid fa-clock text-[10px]"></i>';
        hamsText.innerHTML = `รอดำเนินการ`;
    }
    
    const modal = document.getElementById('visitor-details-modal');
    const modalContent = document.getElementById('visitor-details-modal-content');
    
    modal.classList.remove('hidden');
    // Slight delay to allow display block to apply before transition
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
    }, 10);
}

function closeVisitorDetails() {
    const modal = document.getElementById('visitor-details-modal');
    const modalContent = document.getElementById('visitor-details-modal-content');
    
    modal.classList.add('opacity-0');
    modalContent.classList.add('scale-95');
    
    // Wait for transition to finish before hiding
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Real-time Clock Update
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const clockEl = document.getElementById('realtime-clock');
    if(clockEl) {
        clockEl.innerText = `${hours}:${minutes}:${seconds}`;
    }
}
setInterval(updateClock, 1000);
updateClock();

// Toggle Auto Reset Status
function toggleAutoReset(checkbox) {
    const isEnabled = checkbox.checked;
    const statusText = document.getElementById('auto-reset-status');
    statusText.innerText = isEnabled ? 'เปิดทำงาน' : 'ปิดใช้งาน';
    
    $.ajax({
        url: "{{ route('backend.settings.toggle-parking-auto-reset') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            enabled: isEnabled
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: response.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        },
        error: function(xhr) {
            // Revert on error
            checkbox.checked = !isEnabled;
            statusText.innerText = !isEnabled ? 'เปิดทำงาน' : 'ปิดใช้งาน';
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: xhr.responseJSON?.message || 'ไม่สามารถเปลี่ยนการตั้งค่าได้',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
}
</script>
@endsection
