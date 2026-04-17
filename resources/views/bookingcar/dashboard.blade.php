@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 animate-fadeIn">

        <!-- Header Banner Section (Matching Img 1) -->
        <div
            class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-10 mb-8 text-center text-white relative overflow-hidden no-print">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">รายงานข้อมูลจองรถส่วนกลาง</h1>
                <p class="text-indigo-100 text-base font-light opacity-80 uppercase tracking-[0.2em] text-[10px]">
                    ข้อมูลจองรถส่วนกลางทั้งหมดในระบบ</p>
            </div>
        </div>

        @php
            $hasFilters = request()->anyFilled(['search', 'booking_date', 'status', 'passenger_count', 'return_status', 'month', 'year', 'province', 'district']);
        @endphp

        <!-- Filter & Search Card (Collapsible) -->
        <div class="mb-12 no-print transition-all">
            <!-- Filter Button Toggle -->
            <div class="flex items-center gap-4 mb-6">
                <button onclick="toggleFilters()" 
                    class="flex items-center gap-3 px-8 py-3 bg-white border border-slate-200 text-slate-800 font-black rounded-[1rem] shadow-sm hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all active:scale-95 select-none group">
                    <i id="filterIcon" class="fa-solid fa-filter text-xs {{ $hasFilters ? 'text-blue-600' : 'text-slate-400' }} group-hover:text-blue-600"></i>
                    ตัวกรองข้อมูล
                    @if($hasFilters)
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse ml-1 ring-4 ring-red-50"></span>
                    @endif
                    <i class="fa-solid fa-chevron-{{ $hasFilters ? 'down' : 'right' }} text-[10px] ml-4 opacity-30"></i>
                </button>
            </div>

            <!-- Filter Content (Card Look when open) -->
            <div id="filterContent" class="{{ $hasFilters ? '' : 'hidden' }} bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden transition-all duration-500 transform origin-top">
                <div class="p-10">
                    <form action="{{ route('bookingcar.dashboard') }}" method="GET" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-6 items-end">
                    <!-- Global Search -->
                    <div class="xl:col-span-2">
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">ค้นหาข้อมูล</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="รหัสการจอง, ชื่อผู้จอง, แผนก, สถานที่..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                    </div>

                    <!-- Date -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">(กำหนด:วัน/เดือน/ปี)</label>
                        <input type="date" name="booking_date" value="{{ request('booking_date') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                    </div>

                    <!-- Month -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">เดือน</label>
                        <select name="month"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                            <option value="">-- ทุกเดือน --</option>
                            @foreach($thaiMonths as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">ปี
                            (พ.ศ.)</label>
                        <select name="year"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                            <option value="">-- ทุกปี --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year + 543 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">สถานะการจอง</label>
                        <select name="status"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                            <option value="">-- ทุกสถานะ --</option>
                            <option value="รออนุมัติ" {{ request('status') == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ
                            </option>
                            <option value="อนุมัติแล้ว" {{ request('status') == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว
                            </option>
                            <option value="ไม่อนุมัติ" {{ request('status') == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ
                            </option>
                        </select>
                    </div>

                    <!-- Passenger -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">จำนวนผู้โดยสาร</label>
                        <select name="passenger_count"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                            <option value="">-- ทั้งหมด --</option>
                            @foreach($passengerCounts as $count)
                                <option value="{{ $count }}" {{ request('passenger_count') == $count ? 'selected' : '' }}>
                                    {{ $count }} คน
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Return Status -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">สถานะคืนรถ</label>
                        <select name="return_status"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm appearance-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                            <option value="">-- ทุกสถานะคืนรถ --</option>
                            <option value="ยังไม่ส่งคืน" {{ request('return_status') == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>
                                ยังไม่ส่งคืน</option>
                            <option value="ส่งคืนแล้ว" {{ request('return_status') == 'ส่งคืนแล้ว' ? 'selected' : '' }}>
                                ส่งคืนแล้ว</option>
                        </select>
                    </div>

                    <!-- Province -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">จังหวัด</label>
                        <input type="text" name="province" value="{{ request('province') }}" list="province_list_dash"
                            placeholder="ค้นหาจังหวัด..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                    </div>

                    <!-- District -->
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">อำเภอ</label>
                        <input type="text" name="district" value="{{ request('district') }}" placeholder="ค้นหาระบุอำเภอ..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl h-11 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-400 transition-all">
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 xl:col-span-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black h-11 rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-search text-xs"></i> ค้นหา
                        </button>
                        @if(request()->anyFilled(['search', 'booking_date', 'status', 'passenger_count', 'return_status', 'month', 'year', 'province', 'district']))
                            <a href="{{ route('bookingcar.dashboard') }}"
                                class="w-11 h-11 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-all">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        @endif
                    </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="py-4"></div> <!-- Extra spacing -->

        <!-- Export Bar (Matching Img 1) -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 no-print px-2">
            <div class="flex items-center gap-3 text-indigo-700 font-black text-sm uppercase tracking-widest">
                <i class="fa-solid fa-file-export text-xl opacity-40"></i>
                รายการจองรถส่วนกลาง
            </div>
            <div class="flex gap-3">
                <a href="{{ route('bookingcar.export.excel', request()->all()) }}"
                    class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 text-white border-0 px-8 rounded-lg shadow-lg shadow-emerald-200 font-black">
                    <i class="fa-regular fa-file-excel mr-2"></i> Excel
                </a>
                <button onclick="window.print()"
                    class="btn btn-sm bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-8 rounded-lg shadow-sm font-black">
                    <i class="fa-solid fa-print mr-2"></i> พิมพ์รายงาน
                </button>
            </div>
        </div>

        <!-- Data Table Section (Matching Img 2 strictly) -->
        <div class="bg-white rounded-[1.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-500 font-bold text-[13px] border-b border-slate-100">
                            <th class="py-5 px-6 text-center w-16">#</th>
                            <th class="py-5 px-4 min-w-[250px]">รายละเอียดการจอง</th>
                            <th class="py-5 px-4">รถ / วันเวลาเดินทาง</th>
                            <th class="py-5 px-4">ผู้จอง</th>
                            <th class="py-5 px-4 text-center">สถานะ</th>
                            <th class="py-5 px-4 text-center">ชื่อผู้อนุมัติ</th>
                            <th class="py-5 px-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($bookings as $index => $item)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="py-6 px-6 text-center font-bold text-slate-400 group-hover:text-indigo-600">
                                    {{ $bookings->firstItem() + $index }}
                                </td>

                                <td class="py-6 px-4">
                                    <div class="flex flex-col gap-1.5">
                                        <div
                                            class="text-slate-900 font-black text-[15px] leading-tight group-hover:text-indigo-600 transition-colors">
                                            {{ $item->destination }}
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                            <div class="flex items-center gap-1.5 text-[12px] font-bold text-slate-400">
                                                <i class="fa-solid fa-users text-indigo-400"></i>
                                                {{ $item->passenger_count ?? 1 }} คน
                                            </div>
                                            <div
                                                class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[10px] font-bold font-mono">
                                                Ref: {{ $item->booking_code }}
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[12px] font-bold text-slate-400">
                                                <i
                                                    class="fa-solid fa-map-location-dot text-slate-300 group-hover:text-rose-500 transition-colors"></i>
                                                อ.{{ $item->district }} จ.{{ $item->province }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-6 px-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-[14px] font-black text-indigo-600 mb-1">
                                            <i class="fa-solid fa-car"></i>
                                            {{ $item->vehicle->name ?? '-' }} {{ $item->vehicle->model_name ?? '' }}
                                        </div>
                                        <div class="flex flex-col gap-0.5 ml-0.5 border-l-2 border-slate-100 pl-3">
                                            <div class="flex items-center gap-2 text-[12px] font-bold text-emerald-600">
                                                <i class="fa-regular fa-clock opacity-60"></i>
                                                {{ \Carbon\Carbon::parse($item->start_time)->locale('th')->addYears(543)->isoFormat('D MMM YYYY | HH:mm') }} น.
                                            </div>
                                            <div class="flex items-center gap-2 text-[12px] font-bold text-rose-500">
                                                <i class="fa-regular fa-clock opacity-60"></i>
                                                {{ \Carbon\Carbon::parse($item->end_time)->locale('th')->addYears(543)->isoFormat('D MMM YYYY | HH:mm') }} น.
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-6 px-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-slate-800 font-black text-[14px] tracking-tight">{{ $item->user->first_name ?? 'N/A' }}
                                            {{ $item->user->last_name ?? '' }}</span>
                                        <span
                                            class="text-[11px] font-bold text-slate-400 mb-1">{{ $item->user->department->department_name ?? '-' }}</span>
                                        @if($item->requester_name)
                                            <span class="text-[10px] font-bold text-indigo-400 italic">(เจ้าของงาน:
                                                {{ $item->requester_name }})</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-6 px-4">
                                    <div class="flex flex-col items-center gap-3">
                                        @php
                                            $statusConfig = match ($item->status) {
                                                'อนุมัติแล้ว' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'label' => 'รับทราบแล้ว', 'icon' => 'fa-circle-check'],
                                                'รออนุมัติ' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'label' => 'รออนุมัติ', 'icon' => 'fa-clock'],
                                                'ไม่อนุมัติ', 'ยกเลิก' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'label' => $item->status, 'icon' => 'fa-circle-xmark'],
                                                default => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'label' => '-', 'icon' => 'fa-circle-info']
                                            };
                                        @endphp
                                        <div
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} text-[11px] font-black uppercase ring-1 ring-inset {{ str_replace('bg-', 'ring-', $statusConfig['bg']) }}/30 shadow-sm">
                                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>
                                            {{ $statusConfig['label'] }}
                                        </div>

                                        @if($item->status === 'รออนุมัติ')
                                            <div class="flex items-center gap-1.5 no-print">
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="อนุมัติแล้ว">
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-emerald-500 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition-all shadow-sm active:scale-90"><i
                                                            class="fa-solid fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="ไม่อนุมัติ">
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm active:scale-90"><i
                                                            class="fa-solid fa-xmark"></i></button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-6 px-4 text-center">
                                    @if ($item->approver)
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="text-[12px] font-black text-slate-700 tracking-tight">{{ $item->approver->first_name }}
                                                {{ $item->approver->last_name }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ \Carbon\Carbon::parse($item->approved_at)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}</span>
                                        </div>
                                    @else
                                        <span class="text-[12px] font-bold text-slate-300 italic">รอการพิจารณา</span>
                                    @endif
                                </td>

                                <td class="py-6 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2 no-print">
                                        <!-- <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                                                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-100 flex items-center justify-center transition-all duration-300 shadow-sm">
                                                                                    <i class="fa-regular fa-eye"></i>
                                                                                </a> -->
                                        <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                            class="w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-400 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-100 flex items-center justify-center transition-all duration-300 shadow-sm">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-24 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-50 grayscale">
                                        <i class="fa-solid fa-folder-open text-6xl text-slate-200"></i>
                                        <p class="font-black text-xl text-slate-800 uppercase tracking-[0.2em]">Data Not Found
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-8 border-t border-slate-50 bg-slate-50/30 flex justify-center items-center no-print">
                    <div class="pagination-wrapper">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                font-family: 'Sarabun', sans-serif;
                font-size: 10pt;
            }

            .no-print,
            .btn,
            form,
            h3 {
                display: none !important;
            }

            .rounded-2xl,
            .rounded-[1.5rem] {
                border-radius: 0 !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                border: 1px solid #e2e8f0 !important;
            }

            th {
                background-color: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                padding: 10px 5px !important;
                color: black !important;
                font-weight: bold !important;
            }

            td {
                border: 1px solid #e2e8f0 !important;
                padding: 10px 5px !important;
                color: black !important;
                vertical-align: middle !important;
            }
        }
    </style>
    <script>
        function toggleFilters() {
            const content = document.getElementById('filterContent');
            const icon = document.getElementById('filterIcon');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }
    </script>
@endsection