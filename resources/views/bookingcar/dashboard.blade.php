@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[1600px] mx-auto px-4 lg:px-8 py-8 animate-fadeIn text-slate-800">
        
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 border-b border-slate-200 pb-6 no-print">
            <div>
                <nav class="flex mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('welcome') }}" class="hover:text-red-700 transition-colors">หน้าหลัก</a></li>
                        <li><i class="fa-solid fa-chevron-right mx-1 text-[8px]"></i></li>
                        <li class="text-slate-600">จัดการข้อมูลการจองรถ</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-8 bg-red-700 rounded-sm"></div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                        ระบบจัดการคำขอจองยานพาหนะส่วนกลาง
                    </h1>
                </div>
                <p class="text-slate-500 font-medium text-xs mt-1">
                    <i class="fa-solid fa-tasks text-red-600/70 mr-1"></i>
                    ทะเบียนคำร้องและควบคุมสถานะการจองยานพาหนะ สำหรับผู้จัดการ / เจ้าหน้าที่แผนก HAMS
                </p>
            </div>

            <!-- Export and Print Buttons -->
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route('bookingcar.export.excel', request()->all()) }}"
                    class="btn btn-sm h-10 bg-emerald-600 hover:bg-emerald-700 border-none text-white rounded-lg px-5 shadow-sm flex items-center gap-2 transition-all">
                    <i class="fa-regular fa-file-excel text-xs"></i> ส่งออกข้อมูล (Excel)
                </a>
                <button onclick="window.print()"
                    class="btn btn-sm h-10 bg-slate-900 border-none hover:bg-slate-800 text-white rounded-lg px-5 shadow-sm flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-print text-xs"></i> พิมพ์เอกสาร
                </button>
            </div>
        </div>

        @php
            $hasFilters = request()->anyFilled(['search', 'booking_date', 'status', 'passenger_count', 'return_status', 'month', 'year', 'province', 'district']);
        @endphp

        <!-- Filter & Search Controls (Hospital Registry Style) -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8 shadow-sm no-print">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-filter text-red-700"></i> ตัวกรองข้อมูลผู้จองและยานพาหนะ
                </h3>
                <button type="button" onclick="toggleFilters()" class="text-xs font-bold text-red-700 hover:text-red-800 transition-colors flex items-center gap-1">
                    <span id="toggleText">{{ $hasFilters ? 'ซ่อนตัวกรองขั้นสูง' : 'แสดงตัวกรองขั้นสูง' }}</span>
                    <i id="toggleIcon" class="fa-solid fa-chevron-{{ $hasFilters ? 'up' : 'down' }} text-[9px]"></i>
                </button>
            </div>

            <form action="{{ route('bookingcar.dashboard') }}" method="GET" class="space-y-4" id="booking-filter-form">
                <!-- Row 1: Core Search -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                    <!-- Search Keyword -->
                    <div class="md:col-span-2 lg:col-span-2">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ค้นหาข้อมูลทั่วไป</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="รหัสการจอง, ชื่อผู้จอง, แผนก, สถานที่ปลายทาง..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                    </div>

                    <!-- Booking Date -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">วันที่เดินทาง</label>
                        <input type="date" name="booking_date" value="{{ request('booking_date') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                    </div>

                    <!-- Status Selector -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">สถานะการจอง</label>
                        <select name="status"
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                            <option value="">-- ทุกสถานะ --</option>
                            <option value="รออนุมัติ" {{ request('status') == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ</option>
                            <option value="อนุมัติแล้ว" {{ request('status') == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว (รับทราบแล้ว)</option>
                            <option value="ไม่อนุมัติ" {{ request('status') == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                            <option value="ยกเลิก" {{ request('status') == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                        </select>
                    </div>
                </div>

                <!-- Row 2: Advanced Search (Collapsible) -->
                <div id="advancedFilters" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end pt-4 border-t border-slate-100 {{ $hasFilters ? '' : 'hidden' }}">
                    <!-- Month -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">เดือนเดินทาง</label>
                        <select name="month"
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                            <option value="">-- ทุกเดือน --</option>
                            @foreach($thaiMonths as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ปีเดินทาง (พ.ศ.)</label>
                        <select name="year"
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                            <option value="">-- ทุกปี --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year + 543 }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Return Status -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">สถานะการคืนรถ</label>
                        <select name="return_status"
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                            <option value="">-- ทุกสถานะการคืน --</option>
                            <option value="ยังไม่ส่งคืน" {{ request('return_status') == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>ยังไม่ส่งคืน</option>
                            <option value="ส่งคืนแล้ว" {{ request('return_status') == 'ส่งคืนแล้ว' ? 'selected' : '' }}>ส่งคืนแล้ว</option>
                        </select>
                    </div>

                    <!-- Province -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">จังหวัดปลายทาง</label>
                        <input type="text" name="province" value="{{ request('province') }}" placeholder="ค้นหาจังหวัด..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                    </div>

                    <!-- District -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 mb-1.5">อำเภอปลายทาง</label>
                        <input type="text" name="district" value="{{ request('district') }}" placeholder="ค้นหาอำเภอ..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex justify-end gap-2 pt-2">
                    <button type="submit"
                        class="bg-red-700 hover:bg-red-800 text-white font-bold h-9 text-xs rounded-md px-6 shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-search"></i> ค้นหาทะเบียน
                    </button>
                    @if($hasFilters)
                        <a href="{{ route('bookingcar.dashboard') }}"
                            class="bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-md h-9 px-4 flex items-center justify-center text-xs font-bold transition-all">
                            <i class="fa-solid fa-rotate-right mr-1.5"></i> ล้างการกรอง
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Master Registry Data Table (Hospital Grid Layout) -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col" id="booking-table-card">
            <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between no-print">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-database text-red-700 text-sm"></i>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                        บัญชีรายชื่อควบคุมการใช้น้ำมันและบันทึกเดินทางรถยนต์ส่วนกลาง
                    </h2>
                </div>
                @if($bookings->total() > 0)
                    <span class="text-[11px] font-bold bg-slate-200/80 text-slate-600 px-2.5 py-0.5 rounded-full">
                        พบคิวจองในระบบ {{ $bookings->total() }} รายการ
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border-slate-200 text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center w-12">#</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 w-28">รหัสใบเบิกจอง</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 min-w-[200px]">วัตถุประสงค์และสถานที่ปลายทาง</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 w-44">รถส่วนกลาง / วันเวลาเดินทาง</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 min-w-[150px]">ผู้จอง & แผนก / เจ้าของงาน</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center w-28">สถานะคืนรถ</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center w-28">ผลอนุมัติ</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center min-w-[120px]">ผู้อนุมัติ / วันที่</th>
                            <th class="py-3.5 px-3 text-center w-20 no-print">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($bookings as $index => $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Row number -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                                    {{ $bookings->firstItem() + $index }}
                                </td>

                                <!-- Reference Code -->
                                <td class="py-3.5 px-3 border-r border-slate-200 font-mono text-[10px] font-bold text-slate-700">
                                    {{ $item->booking_code }}
                                </td>

                                <!-- Purpose & Destination -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800 leading-tight">
                                        {{ $item->destination }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-semibold">
                                        <i class="fa-solid fa-location-arrow text-red-500/50"></i>
                                        อ.{{ $item->district }} จ.{{ $item->province }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-1 flex items-start gap-1 font-medium bg-slate-50 p-1.5 rounded border border-slate-100">
                                        <i class="fa-regular fa-comment-dots text-slate-400 mt-0.5 shrink-0"></i>
                                        <span class="italic">"{{ $item->purpose ?? '-' }}"</span>
                                    </div>
                                </td>

                                <!-- Vehicle & Datetime -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-tight">
                                    <div class="font-bold text-red-700 flex items-center gap-1 mb-2">
                                        <i class="fa-solid fa-car text-xs text-red-700/60"></i>
                                        {{ $item->vehicle->name ?? '-' }}
                                    </div>
                                    <div class="space-y-1 pl-1 border-l border-slate-200">
                                        <div class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ \Carbon\Carbon::parse($item->start_time)->locale('th')->addYears(543)->isoFormat('D MMM YY | HH:mm') }} น.
                                        </div>
                                        <div class="text-[10px] font-bold text-rose-500 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            {{ \Carbon\Carbon::parse($item->end_time)->locale('th')->addYears(543)->isoFormat('D MMM YY | HH:mm') }} น.
                                        </div>
                                    </div>
                                </td>

                                <!-- Requester Info -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800">
                                        {{ $item->user->first_name ?? 'N/A' }} {{ $item->user->last_name ?? '' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                        {{ $item->user->department->department_name ?? '-' }}
                                    </div>
                                    @if($item->requester_name)
                                        <div class="text-[9px] text-red-600 font-bold mt-1.5 italic bg-red-50 px-1.5 py-0.5 rounded border border-red-100/50 w-fit">
                                            เจ้าของงาน: {{ $item->requester_name }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Return Status -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center font-bold">
                                    @if($item->return_status === 'ส่งคืนแล้ว')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 whitespace-nowrap">
                                            <i class="fa-solid fa-circle-check"></i> ส่งคืนรถแล้ว
                                        </span>
                                    @else
                                        @php
                                            $isOverdue = $item->status === 'อนุมัติแล้ว' && \Carbon\Carbon::parse($item->end_time)->isPast();
                                        @endphp
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold text-red-700 bg-red-50 border border-red-300 whitespace-nowrap animate-pulse shadow-sm" title="เกินเวลากำหนดส่งคืนรถ!">
                                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i> เลยกำหนดส่งคืน
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold text-slate-400 bg-slate-50 border border-slate-200 whitespace-nowrap">
                                                ยังไม่ส่งคืน
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Approval Status (Click actions for admin) -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        @php
                                            $statusBadge = match ($item->status) {
                                                'อนุมัติแล้ว' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'รออนุมัติ' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                'ไม่อนุมัติ', 'ยกเลิก' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                default => 'bg-slate-50 text-slate-400 border border-slate-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $statusBadge }} whitespace-nowrap">
                                            {{ $item->status === 'อนุมัติแล้ว' ? 'รับทราบแล้ว' : $item->status }}
                                        </span>

                                        <!-- Direct actions if Pending -->
                                        @if($item->status === 'รออนุมัติ' && !request()->routeIs('bookingcar.report'))
                                            <div class="flex items-center gap-1 mt-1 no-print">
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="อนุมัติแล้ว">
                                                    <button type="submit"
                                                        class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all shadow-sm"
                                                        title="รับทราบ/อนุมัติ">
                                                        <i class="fa-solid fa-check text-[10px]"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="ไม่อนุมัติ">
                                                    <button type="submit"
                                                        class="w-6 h-6 rounded bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all shadow-sm"
                                                        title="ไม่อนุมัติ">
                                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Approver info -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center">
                                    @if ($item->approver)
                                        <div class="font-bold text-slate-700">{{ $item->approver->first_name }} {{ $item->approver->last_name }}</div>
                                        <div class="text-[9px] text-slate-400 font-semibold mt-0.5">
                                            {{ \Carbon\Carbon::parse($item->approved_at)->locale('th')->addYears(543)->isoFormat('D MMM YY') }}
                                        </div>
                                    @else
                                        <span class="text-slate-300 italic text-[10px]">รอพิจารณา</span>
                                    @endif
                                </td>

                                <!-- Action button -->
                                <td class="py-3.5 px-3 text-center no-print">
                                    <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                        class="inline-flex w-7 h-7 bg-white hover:bg-slate-100 border border-slate-200 rounded text-slate-500 hover:text-red-700 items-center justify-center transition-all shadow-sm"
                                        title="แก้ไขและคืนรถ">
                                        <i class="fa-regular fa-pen-to-square text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-20 text-center text-slate-400 italic">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-inbox text-4xl opacity-20"></i>
                                        <span>ไม่พบรายการประวัติบันทึกการจองรถส่วนกลางในระบบ</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Grid Footer -->
            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex justify-center items-center no-print">
                    <div class="pagination-wrapper">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Print Custom Stylesheet -->
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            body {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                font-family: 'Sarabun', 'Helvetica Neue', Arial, sans-serif;
                font-size: 8pt;
            }

            .no-print,
            .btn,
            form,
            nav,
            .pagination-wrapper {
                display: none !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 7.5pt !important;
            }

            th, td {
                padding: 6px 4px !important;
                border: 1px solid #94a3b8 !important;
                color: #000 !important;
            }

            th {
                background-color: #f1f5f9 !important;
                font-weight: bold !important;
            }
        }
    </style>

    <script>
        function toggleFilters() {
            const advFilters = document.getElementById('advancedFilters');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleText = document.getElementById('toggleText');

            if (advFilters.classList.contains('hidden')) {
                advFilters.classList.remove('hidden');
                toggleIcon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                toggleText.textContent = 'ซ่อนตัวกรองขั้นสูง';
            } else {
                advFilters.classList.add('hidden');
                toggleIcon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                toggleText.textContent = 'แสดงตัวกรองขั้นสูง';
            }
        }

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
                    <i class="fa-solid fa-spinner fa-spin text-2xl text-red-650"></i>
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
            const tableCard = document.getElementById('booking-table-card');
            if (!tableCard) return;
            
            // Set min-height to prevent jump
            tableCard.style.minHeight = tableCard.offsetHeight + 'px';
            
            showLoadingOverlay('booking-table-card');
            tableCard.style.pointerEvents = 'none';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableCard = doc.getElementById('booking-table-card');
                
                if (newTableCard) {
                    tableCard.style.transition = 'opacity 0.15s ease-out';
                    tableCard.style.opacity = '0.3';
                    
                    setTimeout(() => {
                        tableCard.innerHTML = newTableCard.innerHTML;
                        window.history.pushState({ url: url }, '', url);
                        
                        // Re-bind events to new elements
                        bindAjaxEvents();
                        
                        tableCard.style.opacity = '1';
                        // Remove height lock after fade-in
                        setTimeout(() => {
                            tableCard.style.minHeight = '';
                        }, 150);
                    }, 150);
                } else {
                    hideLoadingOverlay('booking-table-card');
                    tableCard.style.pointerEvents = 'auto';
                    tableCard.style.minHeight = '';
                }
            })
            .catch(error => {
                console.error('AJAX load error:', error);
                hideLoadingOverlay('booking-table-card');
                tableCard.style.pointerEvents = 'auto';
                tableCard.style.minHeight = '';
                window.location.href = url;
            });
        }

        function bindAjaxEvents() {
            // 1. Intercept Pagination links
            document.querySelectorAll('#booking-table-card .pagination-wrapper a, #booking-table-card .pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    loadTableData(url);
                });
            });

            // 2. Intercept Approve/Reject forms inside the table
            document.querySelectorAll('#booking-table-card td form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('action');
                    const formData = new FormData(this);
                    
                    showLoadingOverlay('booking-table-card');
                    const tableCard = document.getElementById('booking-table-card');
                    if (tableCard) {
                        tableCard.style.pointerEvents = 'none';
                    }

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => {
                        // After successful update, refresh the table data
                        loadTableData(window.location.href);
                    })
                    .catch(err => {
                        console.error('Action submit error:', err);
                        // Fallback submission if fetch fails
                        this.submit();
                    });
                });
            });

            // 3. Intercept Filter Clear link
            const clearLink = document.querySelector('#booking-filter-form a');
            if (clearLink) {
                // Ensure clear filter link doesn't do a full page load if it's the reset button
                if (clearLink.getAttribute('href') && clearLink.getAttribute('href').includes('bookingcar/dashboard')) {
                    clearLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        loadTableData(url);
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Intercept Filter Form submit
            const filterForm = document.getElementById('booking-filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const params = new URLSearchParams(formData).toString();
                    const action = this.getAttribute('action') || window.location.pathname;
                    const url = action + (params ? '?' + params : '');
                    loadTableData(url);
                });
            }

            // Bind Ajax events
            bindAjaxEvents();
        });

        // Handle browser Back/Forward navigation
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) {
                loadTableData(e.state.url);
            } else {
                loadTableData(window.location.href);
            }
        });
    </script>
@endsection