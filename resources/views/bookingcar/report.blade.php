@extends('layouts.bookingcar.appcar')

@section('content')
    <!-- Main Dashboard Workspace -->
    <div class="max-w-[1600px] mx-auto px-4 lg:px-8 py-8 animate-fadeIn text-slate-800">
        
        <!-- Hospital-style Header Bar -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 border-b border-slate-200 pb-6 no-print">
            <div>
                <nav class="flex mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('welcome') }}" class="hover:text-red-700 transition-colors">หน้าหลัก</a></li>
                        <li><i class="fa-solid fa-chevron-right mx-1 text-[8px]"></i></li>
                        <li class="text-slate-600">รายงานวิเคราะห์ระบบจองรถ</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-8 bg-red-700 rounded-sm"></div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                        ระบบบันทึกและวิเคราะห์ข้อมูลการใช้รถยนต์ส่วนกลาง
                    </h1>
                </div>
                <p class="text-slate-500 font-medium text-xs mt-1">
                    <i class="fa-solid fa-file-invoice text-red-600/70 mr-1"></i>
                    ทะเบียนบันทึกและสถิติการใช้ยานพาหนะโรงพยาบาล/สำนักงาน ประจำปี พ.ศ. {{ date('Y') + 543 }}
                </p>
            </div>

            <!-- Top Actions -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">อัปเดตล่าสุด</div>
                        <div class="text-xs font-bold text-slate-800">{{ now()->format('d/m/Y H:i') }} น.</div>
                    </div>
                </div>
                <button onclick="window.print()"
                    class="btn btn-sm h-10 bg-slate-900 border-none hover:bg-slate-800 text-white rounded-lg px-5 shadow-sm flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-print text-xs"></i> พิมพ์ทะเบียนรายงาน
                </button>
            </div>
        </div>

        <!-- Metric Counter Grid (Clinically Clean cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Metric 1 -->
            <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:border-slate-300 transition-all">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">จำนวนการจองทั้งหมด</span>
                    <div class="text-3xl font-black text-slate-900 leading-none tracking-tight">
                        {{ number_format($totalBookings) }}
                        <span class="text-xs font-medium text-slate-400 ml-1">ครั้ง</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 text-lg">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
            </div>

            <!-- Metric 2 -->
            <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:border-emerald-300 transition-all">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">อนุมัติเรียบร้อย</span>
                    <div class="text-3xl font-black text-emerald-600 leading-none tracking-tight">
                        {{ number_format($approvedBookings) }}
                        <span class="text-xs font-medium text-slate-400 ml-1">ครั้ง</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Metric 3 -->
            <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:border-amber-300 transition-all">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">รอพิจารณาอนุมัติ</span>
                    <div class="text-3xl font-black text-amber-600 leading-none tracking-tight">
                        {{ number_format($pendingBookings) }}
                        <span class="text-xs font-medium text-slate-400 ml-1">ครั้ง</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-lg">
                    <i class="fa-solid fa-clock-four"></i>
                </div>
            </div>

            <!-- Metric 4 -->
            <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between hover:border-rose-300 transition-all">
                <div class="space-y-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">ไม่อนุมัติ / ยกเลิก</span>
                    <div class="text-3xl font-black text-rose-600 leading-none tracking-tight">
                        {{ number_format($rejectedBookings) }}
                        <span class="text-xs font-medium text-slate-400 ml-1">ครั้ง</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-lg bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 text-lg">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls (Hospital Registry Style) -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8 shadow-sm no-print">
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-red-700"></i> ค้นหาทะเบียนข้อมูลผู้ป่วย/การทำรายการจองรถ
            </h3>
            <form action="{{ route('bookingcar.report') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end" id="booking-report-form">
                <!-- Search Keyword -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ค้นหาคีย์เวิร์ด (ชื่อ, ปลายทาง)</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ชื่อผู้จอง, ปลายทาง..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                </div>

                <!-- Transaction Date -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">วันที่ทำรายการจอง</label>
                    <input type="date" name="transaction_date" value="{{ request('transaction_date') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                </div>

                <!-- Status Selector -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">สถานะใบคำขอ</label>
                    <select name="status"
                        class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                        <option value="">-- แสดงทุกสถานะ --</option>
                        <option value="รออนุมัติ" {{ request('status') == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ (Pending)</option>
                        <option value="อนุมัติแล้ว" {{ request('status') == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว (Approved)</option>
                        <option value="ไม่อนุมัติ" {{ request('status') == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ (Rejected)</option>
                        <option value="ยกเลิก" {{ request('status') == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก (Cancelled)</option>
                    </select>
                </div>

                <!-- Control Buttons -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-red-700 hover:bg-red-800 text-white font-bold h-9 text-xs rounded-md shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-search"></i> กรองรายการ
                    </button>
                    @if(request()->anyFilled(['search', 'transaction_date', 'status']))
                        <a href="{{ route('bookingcar.report') }}"
                            class="w-9 h-9 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-md flex items-center justify-center transition-all"
                            title="ล้างการค้นหา">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Side: Hospital Bordered Register Table (70% or lg:col-span-8) -->
            <div class="lg:col-span-8 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-database text-red-700 text-sm"></i>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                            ทะเบียนรายการจองรถยนต์และบันทึกผู้ใช้ยานพาหนะ (Activity Log Register)
                        </h2>
                    </div>
                    @if($recentBookings->total() > 0)
                        <span class="text-[11px] font-bold bg-slate-200/80 text-slate-600 px-2.5 py-0.5 rounded-full">
                            พบข้อมูล {{ $recentBookings->total() }} รายการ
                        </span>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <!-- High density, hospital register style bordered table -->
                    <table class="w-full text-left border-collapse border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-12">#</th>
                                <th class="py-3 px-3 border-r border-slate-200 w-28">รหัสใบจอง</th>
                                <th class="py-3 px-3 border-r border-slate-200 min-w-[150px]">ผู้จอง / สังกัดแผนก</th>
                                <th class="py-3 px-3 border-r border-slate-200 w-40">วันเวลาเดินทาง (ไป-กลับ)</th>
                                <th class="py-3 px-3 border-r border-slate-200 min-w-[120px]">ยานพาหนะส่วนกลาง</th>
                                <th class="py-3 px-3 border-r border-slate-200 min-w-[150px]">สถานที่หมายปลายทาง</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-28">พนักงานขับรถ</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-28">สถานะรายการ</th>
                                <th class="py-3 px-3 text-center w-16 no-print">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($recentBookings as $index => $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- Row number -->
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                                        {{ $recentBookings->firstItem() + $index }}
                                    </td>

                                    <!-- Booking Reference -->
                                    <td class="py-3 px-3 border-r border-slate-200 font-mono text-[10px] font-bold text-slate-700">
                                        {{ $item->booking_code }}
                                    </td>

                                    <!-- Requester & Department -->
                                    <td class="py-3 px-3 border-r border-slate-200 leading-normal">
                                        <div class="font-bold text-slate-800">
                                            {{ $item->user->first_name ?? 'N/A' }} {{ $item->user->last_name ?? '' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                            {{ $item->user->department->department_name ?? '-' }}
                                        </div>
                                        @if($item->requester_name)
                                            <div class="text-[9px] text-red-600 font-bold mt-0.5 italic">
                                                (เจ้าของงาน: {{ $item->requester_name }})
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Departure & Return Time -->
                                    <td class="py-3 px-3 border-r border-slate-200 leading-tight">
                                        <div class="text-[11px] font-bold text-slate-700 flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ \Carbon\Carbon::parse($item->start_time)->locale('th')->addYears(543)->isoFormat('D MMM YY | HH:mm') }}
                                        </div>
                                        <div class="text-[11px] font-bold text-slate-500 flex items-center gap-1 mt-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            {{ \Carbon\Carbon::parse($item->end_time)->locale('th')->addYears(543)->isoFormat('D MMM YY | HH:mm') }}
                                        </div>
                                    </td>

                                    <!-- Vehicle Name -->
                                    <td class="py-3 px-3 border-r border-slate-200 font-bold text-slate-700">
                                        <i class="fa-solid fa-car text-slate-400 mr-1"></i>
                                        {{ $item->vehicle->name ?? '-' }}
                                        @if(isset($item->vehicle->model_name))
                                            <div class="text-[10px] text-slate-400 font-medium">
                                                {{ $item->vehicle->model_name }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Destination -->
                                    <td class="py-3 px-3 border-r border-slate-200">
                                        <div class="font-bold text-slate-800 leading-tight">{{ $item->destination }}</div>
                                        <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-map-pin text-red-500/60"></i>
                                            อ.{{ $item->district ?? '-' }} จ.{{ $item->province ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Driver Type -->
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-bold">
                                        @if($item->driver_request == 1)
                                            <span class="text-slate-800 text-[10px] bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">
                                                <i class="fa-solid fa-user-tie text-slate-500 mr-1"></i>ต้องการคนขับ
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[10px] px-2 py-0.5">
                                                ขับเอง
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Booking Status -->
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        @php
                                            $badgeColors = match ($item->status) {
                                                'อนุมัติแล้ว' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                'รออนุมัติ' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                'ไม่อนุมัติ' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                                'ยกเลิก' => 'bg-slate-100 text-slate-500 border border-slate-200',
                                                default => 'bg-slate-50 text-slate-400 border border-slate-100'
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $badgeColors }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-3 px-3 text-center no-print">
                                        <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                            class="inline-flex w-7 h-7 bg-white hover:bg-slate-100 border border-slate-200 rounded text-slate-500 hover:text-red-700 items-center justify-center transition-all shadow-sm"
                                            title="แก้ไขข้อมูล">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-16 text-center text-slate-400 italic">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="fa-solid fa-inbox text-4xl opacity-20"></i>
                                            <span>ไม่พบประวัติการบันทึกข้อมูลตามเงื่อนไขที่กำหนด</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($recentBookings->hasPages())
                    <div class="p-4 border-t border-slate-200 bg-slate-50/50 flex justify-center items-center no-print">
                        <div class="pagination-wrapper">
                            {{ $recentBookings->links() }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Side: Secondary Side-panels (30% or lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Usage Trends Chart (Clean Minimal Bars) -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col">
                    <div class="border-b border-slate-100 pb-3 mb-6 flex justify-between items-center">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-chart-bar text-red-700"></i> สถิติการใช้รถรายเดือน
                        </h3>
                        <span class="text-[9px] font-bold text-slate-400">ปี {{ date('Y') + 543 }}</span>
                    </div>

                    <div class="h-44 flex items-end justify-between gap-2.5 px-1 pt-6 border-b border-slate-100">
                        @php
                            $maxVal = collect($usageTrends)->max() ?: 1;
                            $thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                        @endphp
                        @foreach($usageTrends as $month => $count)
                            @php $pct = ($count / $maxVal) * 100; @endphp
                            <div class="flex-1 flex flex-col items-center gap-2 group relative h-full">
                                <div class="w-full flex flex-col justify-end items-center h-full relative">
                                    <div class="w-full bg-slate-200 group-hover:bg-red-700 transition-all duration-300 rounded-t-sm relative shadow-sm"
                                        style="height: {{ max($pct, 4) }}%;">
                                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all transform scale-95 pointer-events-none z-10">
                                            <div class="bg-slate-800 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow whitespace-nowrap">
                                                {{ $count }} ครั้ง
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 group-hover:text-red-700 transition-colors">{{ $thaiMonths[$month - 1] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Driver Allocation -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col">
                    <div class="border-b border-slate-100 pb-3 mb-5">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-circle-nodes text-red-700"></i> สัดส่วนความต้องการพนักงานขับรถ
                        </h3>
                    </div>

                    @php
                        $totalD = array_sum($driverStats) ?: 1;
                        $rP = round(($driverStats['requested'] / $totalD) * 100);
                        $sP = 100 - $rP;
                    @endphp
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-1.5 text-xs">
                                <span class="font-bold text-slate-600">พนักงานขับรถส่วนกลาง</span>
                                <span class="font-mono font-bold text-slate-800">{{ $driverStats['requested'] }} ครั้ง ({{ $rP }}%)</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded overflow-hidden">
                                <div class="h-full bg-red-700 rounded transition-all duration-1000" style="width: {{ $rP }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1.5 text-xs">
                                <span class="font-bold text-slate-500">พนักงานขับดูแลตนเอง</span>
                                <span class="font-mono font-bold text-slate-800">{{ $driverStats['self_drive'] }} ครั้ง ({{ $sP }}%)</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded overflow-hidden">
                                <div class="h-full bg-slate-400 rounded transition-all duration-1000" style="width: {{ $sP }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fleet Usage Ranking (Top Vehicles) -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col">
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-car-rear text-red-700"></i> ลำดับยานพาหนะใช้บ่อยสูงสุด (Top 5)
                        </h3>
                    </div>

                    <div class="space-y-3.5">
                        @forelse($vehicleUsage as $vUsed)
                            @php
                                $firstCount = $vehicleUsage->first()->count ?: 1;
                                $widthPct = ($vUsed->count / $firstCount) * 100;
                            @endphp
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700 truncate w-3/4">
                                        <i class="fa-solid fa-car text-[10px] text-slate-400 mr-1"></i>
                                        {{ $vUsed->vehicle->name ?? 'ไม่ระบุ' }}
                                    </span>
                                    <span class="font-mono font-bold text-slate-900 bg-slate-100 px-1.5 py-0.5 rounded text-[10px]">
                                        {{ $vUsed->count }} ครั้ง
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 h-1.5 rounded overflow-hidden">
                                    <div class="bg-red-700 h-full rounded transition-all duration-1000" style="width: {{ $widthPct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 italic text-xs">ไม่มีข้อมูลการจองรถ</div>
                        @endforelse
                    </div>
                </div>

                <!-- Geographic Destinations (Hotspots) -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col">
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-red-700"></i> จุดหมายปลายทางยอดนิยม (Top 5)
                        </h3>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($topDestinations as $dest)
                            <div class="flex items-center justify-between py-2 text-xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-4 h-4 rounded-full bg-slate-100 text-slate-500 font-bold flex items-center justify-center text-[9px] flex-shrink-0">
                                        {{ $loop->iteration }}
                                    </span>
                                    <span class="font-bold text-slate-700 truncate">{{ $dest->destination }}</span>
                                </div>
                                <span class="font-bold text-slate-900 flex-shrink-0 ml-2">{{ $dest->count }} ครั้ง</span>
                            </div>
                        @empty
                            <div class="text-center py-6 text-slate-400 italic text-xs">ระบบกำลังประมวลผลข้อมูล...</div>
                        @endforelse
                    </div>
                </div>
            </div>

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

            /* Adjust table for printable area */
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

            .lg:col-span-8 {
                width: 100% !important;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('booking-report-form');
            if (form) {
                form.addEventListener('submit', () => {
                    Swal.fire({
                        title: 'กำลังประมวลผล...',
                        text: 'กรุณารอซักครู่ ระบบกำลังดึงข้อมูลรายงาน',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }
        });
    </script>
@endsection