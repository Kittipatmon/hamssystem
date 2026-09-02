@extends('layouts.parking.app')

@section('content')

{{-- DataTables CSS CDN --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    /* ============ DataTable Custom Overrides ============ */
    #reservationTable_wrapper .dataTables_length label,
    #reservationTable_wrapper .dataTables_filter label {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    #reservationTable_wrapper .dataTables_length select,
    #reservationTable_wrapper .dataTables_filter input {
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

    #reservationTable_wrapper .dataTables_filter input:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245,158,11,.15);
    }

    #reservationTable_wrapper .dataTables_info {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
        padding-top: 0.75rem;
    }

    #reservationTable_wrapper .dataTables_paginate {
        padding-top: 0.6rem;
    }

    #reservationTable_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        border: none !important;
        padding: 0.3rem 0.75rem !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        transition: background .15s, color .15s;
        font-family: 'Prompt', sans-serif;
    }

    #reservationTable_wrapper .dataTables_paginate .paginate_button:hover {
        background: #fef3c7 !important;
        color: #92400e !important;
    }

    #reservationTable_wrapper .dataTables_paginate .paginate_button.current,
    #reservationTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #f59e0b !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(245,158,11,.35);
    }

    #reservationTable_wrapper .dataTables_paginate .paginate_button.disabled,
    #reservationTable_wrapper .dataTables_paginate .paginate_button.disabled:hover {
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
                    <i class="fa-solid fa-list-check text-emerald-500"></i> รายการคำร้องขอจอดรถในอาคาร (พนักงาน)
                </h2>
                <p class="text-slate-500 mt-1 font-medium">ข้อมูลคำร้องขอจอดรถในอาคารของคุณ</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('parking.employee_reservations.create') }}" class="btn bg-slate-900 hover:bg-slate-800 text-white border-none shadow-lg shadow-slate-200 rounded-xl">
                    <i class="fa-solid fa-plus mr-1"></i> สร้างคำร้องใหม่
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
            $managerPending = $resCollection->where('manager_approval', 'pending')->count();
            $hamsPending = $resCollection->where('manager_approval', 'approved')->where('hams_status', 'pending')->count();
            $approved = $resCollection->where('manager_approval', 'approved')->where('hams_status', 'acknowledged')->where('status', 'reserved')->count();
            $parked = $resCollection->where('status', 'checked_in')->count();
            
            $topDepartments = $resCollection->filter(function($res) {
                return $res->department != null;
            })->groupBy(function($res) {
                return $res->department->name;
            })->map->count()->sortDesc()->take(5);
        @endphp

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">ผู้จัดการแผนก</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $managerPending }}</h3>
                    <p class="text-[10px] font-bold text-blue-600">รออนุมัติขั้นต้น</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">ฝ่ายบริหาร HAMS</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $hamsPending }}</h3>
                    <p class="text-[10px] font-bold text-amber-600">รอตรวจสอบจัดสรร</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">อนุมัติแล้ว</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $approved }}</h3>
                    <p class="text-[10px] font-bold text-emerald-600">พร้อมเข้าจอดได้</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">จอดแล้ว</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $parked }}</h3>
                    <p class="text-[10px] font-bold text-indigo-600">กำลังใช้งานช่องจอด</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-car"></i>
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
                    <span class="text-sm text-slate-600 block">ทุกข้ามวันเวลาเที่ยงคืน หรือตามการตั้งค่าส่วนกลาง</span>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-3">
                <!-- Table Data -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-6">
            <div class="overflow-x-auto">
                <table id="reservationTable" class="w-full text-left border-collapse" style="width:100%">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">ผู้จอง</th>
                            <th class="px-6 py-4">รถและช่องจอด</th>
                            <th class="px-6 py-4">วัน-เวลาที่ใช้งาน</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-right" data-orderable="false">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                        @foreach ($reservations as $reservation)
                            @php
                                $name = $reservation->user->fullname ?? 'ไม่ระบุ';
                                $initials = '';
                                $words = explode(' ', trim($name));
                                if (count($words) >= 2) {
                                    $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
                                } else {
                                    $initials = mb_substr($name, 0, 2);
                                }
                                $initials = mb_strtoupper($initials);

                                $bgColors = [
                                    'bg-red-50 text-red-700 border-red-100', 
                                    'bg-blue-50 text-blue-700 border-blue-100', 
                                    'bg-emerald-50 text-emerald-700 border-emerald-100', 
                                    'bg-amber-50 text-amber-700 border-amber-100', 
                                    'bg-indigo-50 text-indigo-700 border-indigo-100', 
                                    'bg-purple-50 text-purple-700 border-purple-100'
                                ];
                                $colorIndex = abs(crc32($name)) % count($bgColors);
                                $selectedColor = $bgColors[$colorIndex];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3 cursor-pointer group" onclick="openDetailsModal({{ $reservation->id }})">
                                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-bold {{ $selectedColor }} shrink-0 group-hover:scale-105 transition-transform">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $name }}</p>
                                            <p class="text-xs text-slate-500 font-semibold mt-0.5">{{ $reservation->department->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 bg-slate-50 text-slate-800 rounded-lg border border-slate-200 font-bold tracking-wide text-xs mb-1.5">
                                        {{ $reservation->car_registration }}
                                    </span>
                                    <br>
                                    @if($reservation->slot)
                                        @php
                                            $slotNum = $reservation->slot->slot_number;
                                            if (str_starts_with($slotNum, 'B')) {
                                                $parts = explode('_', substr($slotNum, 1));
                                                $displaySlot = "ในอาคาร ช่อง " . ($parts[0] ?? '') . " คันที่ " . ($parts[1] ?? '');
                                                $badgeStyle = "bg-indigo-50 text-indigo-700 border-indigo-100";
                                            } else {
                                                $displaySlot = "สำนักงานใหญ่ ช่อง " . $slotNum;
                                                $badgeStyle = "bg-emerald-50 text-emerald-700 border-emerald-100";
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 border rounded-full font-bold text-[11px] {{ $badgeStyle }}">
                                            <i class="fa-solid fa-square-parking"></i> {{ $displaySlot }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 font-normal">ยังไม่ระบุช่องจอด</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" data-order="{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('Y-m-d H:i:s') }}">
                                    <div class="text-xs text-slate-600 font-semibold">
                                        <p><i class="fa-regular fa-calendar text-slate-400 mr-1.5 w-4"></i>{{ \Carbon\Carbon::parse($reservation->checkin_datetime)->format('d M y H:i') }}</p>
                                        @if($reservation->checkout_datetime)
                                            <p class="text-slate-400 mt-1"><i class="fa-solid fa-arrow-right text-slate-300 mr-1.5 w-4"></i>{{ \Carbon\Carbon::parse($reservation->checkout_datetime)->format('H:i') }} น.</p>
                                        @else
                                            <p class="text-slate-400 mt-1"><i class="fa-solid fa-arrow-right text-slate-300 mr-1.5 w-4"></i>-</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($reservation->status === 'reserved')
                                        @if($reservation->manager_approval === 'approved' && $reservation->hams_status === 'acknowledged')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">จอดแล้ว</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-orange-50 text-orange-700 border border-orange-200">รอการอนุมัติ</span>
                                        @endif
                                    @elseif($reservation->status === 'checked_in')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">กำลังใช้งาน</span>
                                    @elseif($reservation->status === 'checked_out')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200/60">ออกแล้ว</span>
                                    @elseif($reservation->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">ยกเลิก</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        @if($reservation->status === 'pending' || $reservation->status === 'reserved')
                                            <button type="button" class="btn btn-xs bg-red-50 hover:bg-red-100 text-red-700 border border-red-200/80 rounded-lg flex items-center gap-1 font-bold">
                                                <i class="fa-solid fa-ban"></i> ยกเลิก
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 font-normal pr-3">-</span>
                                        @endif
                                    </div>
                                    <!-- Store JSON data for modal -->
                                    <textarea id="res_data_{{ $reservation->id }}" class="hidden">{{ json_encode([
                                        'name' => $name,
                                        'department' => $reservation->department->name ?? '-',
                                        'car' => $reservation->car_registration,
                                        'slot' => $reservation->slot ? ($reservation->slot->zone->zone ?? '') . ' ช่อง ' . $reservation->slot->slot_number : '-',
                                        'details' => $reservation->details ?? 'ไม่มีรายละเอียดเพิ่มเติม',
                                        'checkin' => \Carbon\Carbon::parse($reservation->checkin_datetime)->format('d M y H:i'),
                                        'checkout' => $reservation->checkout_datetime ? \Carbon\Carbon::parse($reservation->checkout_datetime)->format('d M y H:i') : '-',
                                        'manager' => $reservation->manager_approval === 'approved' ? 'อนุมัติแล้ว' : 'รอตรวจสอบ',
                                        'manager_by' => $reservation->manager->fullname ?? '-',
                                        'manager_at' => $reservation->manager_approved_at ? \App\Helpers\ThaiDate::format($reservation->manager_approved_at) : '',
                                        'hams' => $reservation->hams_status === 'acknowledged' ? 'รับทราบแล้ว' : 'รอตรวจสอบ',
                                        'hams_by' => $reservation->hamsAckBy->fullname ?? '-',
                                        'hams_at' => $reservation->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($reservation->hams_acknowledged_at) : ''
                                    ]) }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            </div>
            
            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 flex flex-col gap-6">
                    <!-- Top Departments -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h4 class="text-base font-black text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-pie text-blue-500"></i> แผนกที่ขอจอดมากสุด
                    </h4>
                    
                    @if($topDepartments->isEmpty())
                        <div class="text-center py-6">
                            <div class="text-slate-300 mb-2"><i class="fa-solid fa-chart-bar text-3xl"></i></div>
                            <p class="text-xs text-slate-400 font-medium">ยังไม่มีข้อมูลคำร้อง</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @php 
                                $maxCount = $topDepartments->first(); 
                                $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-amber-500', 'bg-purple-500', 'bg-rose-500'];
                            @endphp
                            @foreach($topDepartments as $deptName => $count)
                                @php 
                                    $percent = $maxCount > 0 ? ($count / $maxCount) * 100 : 0; 
                                    $color = $colors[$loop->index % count($colors)];
                                @endphp
                                <div>
                                    <div class="flex justify-between items-end mb-1">
                                        <span class="text-xs font-bold text-slate-700 truncate pr-2">{{ $deptName }}</span>
                                        <span class="text-xs font-black text-slate-900">{{ $count }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    </div>
                    
                    <!-- Latest Reservations -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                        <h4 class="text-base font-black text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> รายการล่าสุด
                        </h4>
                        
                        @if($reservations->isEmpty())
                            <div class="text-center py-6">
                                <div class="text-slate-300 mb-2"><i class="fa-solid fa-inbox text-3xl"></i></div>
                                <p class="text-xs text-slate-400 font-medium">ยังไม่มีข้อมูลคำร้อง</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($reservations->take(5) as $latest)
                                    <div class="flex flex-col gap-1 pb-3 border-b border-slate-50 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <span class="text-sm font-bold text-slate-700 truncate pr-2">{{ $latest->user->fullname ?? 'ไม่ระบุ' }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($latest->created_at)->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-xs text-slate-500 flex justify-between items-center">
                                            <span><i class="fa-solid fa-car text-slate-300 mr-1"></i> {{ $latest->car_registration }}</span>
                                            @if($latest->status === 'reserved')
                                                @if($latest->manager_approval === 'approved' && $latest->hams_status === 'acknowledged')
                                                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">จอดแล้ว</span>
                                                @else
                                                    <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-200">รออนุมัติ</span>
                                                @endif
                                            @elseif($latest->status === 'checked_in')
                                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">กำลังใช้งาน</span>
                                            @elseif($latest->status === 'checked_out')
                                                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">ออกแล้ว</span>
                                            @elseif($latest->status === 'cancelled')
                                                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">ยกเลิก</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[999] flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="detailsModalContent">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-lines text-blue-500"></i> รายละเอียดคำร้อง
            </h3>
            <button onclick="closeDetailsModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-sm text-slate-600">
            <div class="grid grid-cols-3 gap-2">
                <div class="font-bold text-slate-700">ผู้จอง:</div>
                <div class="col-span-2" id="modal_name"></div>
                
                <div class="font-bold text-slate-700">แผนก:</div>
                <div class="col-span-2" id="modal_department"></div>
                
                <div class="font-bold text-slate-700">ทะเบียนรถ:</div>
                <div class="col-span-2" id="modal_car"></div>
                
                <div class="font-bold text-slate-700">ช่องจอด:</div>
                <div class="col-span-2" id="modal_slot"></div>
                
                <div class="font-bold text-slate-700">เวลาเข้า:</div>
                <div class="col-span-2" id="modal_checkin"></div>
                
                <div class="font-bold text-slate-700">เวลาออก:</div>
                <div class="col-span-2" id="modal_checkout"></div>
                
                <div class="col-span-3 border-t border-slate-100 my-2"></div>
                
                <div class="font-bold text-slate-700">สถานะหัวหน้า:</div>
                <div class="col-span-2" id="modal_manager"></div>
                
                <div class="font-bold text-slate-700">สถานะ HRMS:</div>
                <div class="col-span-2" id="modal_hams"></div>
                
                <div class="col-span-3 border-t border-slate-100 my-2"></div>
                
                <div class="font-bold text-slate-700 col-span-3 mb-1">รายละเอียด/เหตุผล:</div>
                <div class="col-span-3 bg-slate-50 p-3 rounded-xl border border-slate-200 text-slate-700" id="modal_details"></div>
            </div>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 text-right">
            <button onclick="closeDetailsModal()" class="btn bg-slate-900 hover:bg-slate-800 text-white border-none rounded-xl px-6 shadow">ปิดหน้าต่าง</button>
        </div>
    </div>
</div>

{{-- jQuery + DataTables JS CDN --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function () {
    $('#reservationTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'ทั้งหมด']],
        order: [[2, 'desc']], // Sort by date/time column descending
        columnDefs: [
            { orderable: false, targets: 4 } // Disable sort on action column
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/th.json'
        },
        dom: '<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4"lf>rt<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4"ip>',
        drawCallback: function() {
            // Re-apply Tailwind-friendly styling after each draw
        }
    });
});

function openDetailsModal(id) {
    const dataStr = document.getElementById('res_data_' + id).value;
    const data = JSON.parse(dataStr);
    
    document.getElementById('modal_name').textContent = data.name;
    document.getElementById('modal_department').textContent = data.department;
    document.getElementById('modal_car').textContent = data.car;
    document.getElementById('modal_slot').textContent = data.slot;
    document.getElementById('modal_checkin').textContent = data.checkin;
    document.getElementById('modal_checkout').textContent = data.checkout;
    document.getElementById('modal_manager').innerHTML = data.manager + (data.manager_by !== '-' ? `<br><span class="text-xs text-slate-400">โดย: ${data.manager_by} <br>เมื่อ: ${data.manager_at} น.</span>` : '');
    document.getElementById('modal_hams').innerHTML = data.hams + (data.hams_by !== '-' ? `<br><span class="text-xs text-slate-400">โดย: ${data.hams_by} <br>เมื่อ: ${data.hams_at} น.</span>` : '');
    document.getElementById('modal_details').textContent = data.details;
    
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailsModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
    }, 50);
}

function closeDetailsModal() {
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('detailsModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Clock
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
