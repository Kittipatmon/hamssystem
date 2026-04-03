@extends('layouts.bookingcar.appcar')

@section('content')
    <!-- Dashboard Header Section -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-10 animate-fadeIn text-slate-800">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <nav class="flex mb-3 text-[11px] font-bold text-slate-400 uppercase tracking-widest"
                    aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('welcome') }}" class="hover:text-red-600 transition-colors">หน้าหลัก</a></li>
                        <li><i class="fa-solid fa-chevron-right mx-1 text-[8px]"></i></li>
                        <li class="text-slate-600">การวิเคราะห์ข้อมูลการจองรถ</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-5xl font-black tracking-tighter text-slate-900 flex items-center gap-4">
                    <span class="w-2.5 h-12 bg-red-600 rounded-full"></span>
                    แดชบอร์ดสรุปข้อมูล
                </h1>
                <p class="text-slate-500 font-medium mt-2 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-red-500/50"></i>
                    สรุปวิเคราะห์ข้อมูลประจำปี {{ date('Y') + 543 }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white px-5 py-3 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">อัปเดตล่าสุด</div>
                        <div class="text-sm font-black text-slate-700">{{ now()->format('H:i') }} น.</div>
                    </div>
                </div>
                <button onclick="window.print()"
                    class="btn btn-md bg-red-800 border-none hover:bg-red-900 text-white rounded-xl px-6 shadow-xl shadow-red-100 group">
                    <i class="fa-solid fa-print mr-2 group-hover:scale-110 transition-transform"></i> พิมพ์รายงาน
                </button>
            </div>
        </div>

        <!-- Main Stats Grid - Bright Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total -->
            <div
                class="relative overflow-hidden bg-white p-7 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100/50 group hover:border-red-200 transition-all duration-500">
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl mb-5 border border-red-100">
                        <i class="fa-solid fa-list-ul"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">การจองทั้งหมด</div>
                    <div class="text-4xl font-black text-slate-900 mt-2 leading-none tracking-tight">
                        {{ number_format($totalBookings) }} <span class="text-lg text-slate-300 ml-1">ครั้ง</span></div>
                    <div
                        class="mt-5 flex items-center text-[11px] font-black text-slate-500 bg-slate-50 w-fit px-3 py-1.5 rounded-xl border border-slate-100">
                        <i class="fa-solid fa-globe mr-2 text-red-500"></i> ข้อมูลภาพรวม
                    </div>
                </div>
            </div>

            <!-- Approved -->
            <div
                class="relative overflow-hidden bg-white p-7 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100/50 group hover:border-emerald-200 transition-all duration-500">
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-5 border border-emerald-100">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">อนุมัติสำเร็จ</div>
                    <div class="text-4xl font-black text-slate-900 mt-2 leading-none tracking-tight">
                        {{ number_format($approvedBookings) }} <span class="text-lg text-slate-300 ml-1">ครั้ง</span></div>
                    <div
                        class="mt-5 flex items-center text-[11px] font-black text-emerald-600 bg-emerald-50 w-fit px-3 py-1.5 rounded-xl border border-emerald-100">
                        <i class="fa-solid fa-circle-check mr-2 font-black"></i> สำเร็จ
                        {{ round(($approvedBookings / ($totalBookings ?: 1)) * 100) }}%
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div
                class="relative overflow-hidden bg-white p-7 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100/50 group hover:border-orange-200 transition-all duration-500">
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl mb-5 border border-orange-100">
                        <i class="fa-solid fa-hourglass-half"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">อยู่ระหว่างพิจารณา</div>
                    <div class="text-4xl font-black text-slate-900 mt-2 leading-none tracking-tight">
                        {{ number_format($pendingBookings) }} <span class="text-lg text-slate-300 ml-1">ครั้ง</span></div>
                    <div
                        class="mt-5 flex items-center text-[11px] font-black text-orange-600 bg-orange-50 w-fit px-3 py-1.5 rounded-xl border border-orange-100">
                        <i class="fa-solid fa-circle-dot mr-2"></i> กำลังดำเนินการ
                    </div>
                </div>
            </div>

            <!-- Ready Fleet -->
            <div
                class="relative overflow-hidden bg-white p-7 rounded-[2rem] border border-slate-100 shadow-lg shadow-slate-100/50 group hover:border-blue-200 transition-all duration-500">
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-5 border border-blue-100">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                    <div class="text-xs font-black text-slate-400 uppercase tracking-widest">รถที่พร้อมใช้งาน</div>
                    <div class="text-4xl font-black text-slate-900 mt-2 leading-none tracking-tight">
                        {{ \App\Models\bookingcar\Vehicle::where('status', 1)->count() }} <span
                            class="text-lg text-slate-300 ml-1">คัน</span></div>
                    <div
                        class="mt-5 flex items-center text-[11px] font-black text-blue-600 bg-blue-50 w-fit px-3 py-1.5 rounded-xl border border-blue-100">
                        <i class="fa-solid fa-key mr-2"></i> กองรถส่วนกลาง
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
            <!-- Usage Trend Chart (Main) -->
            <div
                class="lg:col-span-8 bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50 relative overflow-hidden">
                <div class="flex items-center justify-between mb-12">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">แนวโน้มการใช้งานรายเดือน</h3>
                        <p class="text-slate-400 text-[10px] font-black leading-relaxed tracking-wider mt-1 uppercase">
                            Monthly activity pattern distribution</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-slate-300 border border-slate-100">
                        <i class="fa-solid fa-chart-simple text-lg"></i>
                    </div>
                </div>

                <div class="h-64 flex items-end justify-between gap-4 px-2 pt-10 border-b border-slate-50">
                    @php
                        $maxVal = collect($usageTrends)->max() ?: 1;
                        $thaiMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
                    @endphp
                    @foreach($usageTrends as $month => $count)
                        @php $pct = ($count / $maxVal) * 100; @endphp
                        <div class="flex-1 flex flex-col items-center gap-4 group relative h-full">
                            <div class="w-full flex flex-col justify-end items-center h-full relative">
                                <div class="w-2/3 bg-red-600 group-hover:bg-red-700 transition-all duration-300 rounded-2xl relative shadow-lg shadow-red-100"
                                    style="height: {{ $pct }}%;">
                                    <div
                                        class="absolute -top-10 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-all transform scale-50 group-hover:scale-100 pointer-events-none z-20">
                                        <div
                                            class="bg-slate-900 text-white text-[10px] font-black px-2 py-1 rounded-lg whitespace-nowrap shadow-xl">
                                            {{ $count }} ครั้ง
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <span
                                class="text-[10px] font-black {{ $count > 0 ? 'text-slate-900' : 'text-slate-300' }} group-hover:text-red-700 transition-colors mb-2">{{ $thaiMonths[$month - 1] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Driver Support & Fleet Pulse -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Driver Utilization -->
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-8 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div> รูปแบบการจัดระบบขนส่ง
                    </h3>
                    @php
                        $totalD = array_sum($driverStats) ?: 1;
                        $rP = round(($driverStats['requested'] / $totalD) * 100);
                        $sP = 100 - $rP;
                    @endphp
                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between items-end mb-3">
                                <span class="text-[13px] font-black text-slate-600">ต้องการพนักงานขับรถ</span>
                                <span class="text-sm font-black text-slate-900">{{ $driverStats['requested'] }} ครั้ง</span>
                            </div>
                            <div
                                class="h-4 w-full bg-slate-50 rounded-full overflow-hidden p-1 border border-slate-100 shadow-inner">
                                <div class="h-full bg-slate-900 rounded-full transition-all duration-1000 shadow-sm"
                                    style="width: {{ $rP }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-3">
                                <span class="text-[13px] font-black text-slate-500">ขับโดยพนักงานเอง</span>
                                <span class="text-sm font-black text-slate-400">{{ $driverStats['self_drive'] }}
                                    ครั้ง</span>
                            </div>
                            <div
                                class="h-4 w-full bg-slate-50 rounded-full overflow-hidden p-1 border border-slate-100 shadow-inner">
                                <div class="h-full bg-slate-300 rounded-full transition-all duration-1000 shadow-sm"
                                    style="width: {{ $sP }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Card - Bright Version -->
                <div
                    class="bg-white p-10 rounded-[2.5rem] border-2 border-emerald-50 shadow-xl shadow-emerald-50 text-slate-800 relative overflow-hidden group">
                    <div class="relative z-10 text-center">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-600 mb-6 font-bold">
                            อัตราการทำรายการสำเร็จ</h3>
                        <div class="flex flex-col items-center gap-2">
                            <div class="text-7xl font-black text-slate-900 tracking-tighter">{{ $approvedBookings }} <span class="text-2xl text-slate-300">ครั้ง</span></div>
                            <div class="flex flex-col text-center">
                                <span class="text-sm font-black text-emerald-600">รายการที่ได้รับการอนุมัติ</span>
                                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">Confirmed
                                    Mobility Requests</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="absolute -bottom-8 -right-8 w-32 h-32 bg-emerald-50 border border-emerald-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Fleet Analytics - Light Theme -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Fleet Usage Ranking -->
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50 flex flex-col">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-2xl bg-red-600 text-white flex items-center justify-center shadow-lg shadow-red-100">
                            <i class="fa-solid fa-car-rear text-lg"></i>
                        </div>
                        รถที่มีการใช้งานสูงสุด
                    </h3>
                </div>
                <div class="space-y-6">
                    @forelse($vehicleUsage as $vUsed)
                        <div class="flex items-center gap-6 group">
                            <div
                                class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-red-50 group-hover:text-red-600 transition-all duration-300">
                                <i class="fa-solid fa-car text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-2">
                                    <span
                                        class="text-sm font-black text-slate-800 truncate">{{ $vUsed->vehicle->name ?? 'ข้อมูลรถไม่ระบุ' }}</span>
                                    <span
                                        class="text-xs font-black text-slate-900 bg-slate-50 px-3 py-1 rounded-xl border border-slate-100">{{ $vUsed->count }}
                                        ครั้ง</span>
                                </div>
                                <div class="w-full bg-slate-50 h-3 rounded-full overflow-hidden border border-slate-100 p-0.5">
                                    <div class="bg-red-600 h-full rounded-full transition-all duration-1000 delay-300 shadow-sm"
                                        style="width: {{ ($vUsed->count / ($vehicleUsage->first()->count ?: 1)) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-slate-300 italic text-sm">
                            <i class="fa-solid fa-inbox text-5xl mb-4 opacity-10"></i>
                            ขณะนี้ยังไม่มีข้อมูลการใช้งาน
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Geographic hotspot - Bright Version -->
            <div
                class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-100/50 relative overflow-hidden group">
                <h1 class="text-xl font-black text-slate-900 flex items-center gap-4 mb-12 relative z-10 tracking-tight">
                    <div
                        class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-100">
                        <i class="fa-solid fa-map-location-dot text-lg"></i>
                    </div>
                    จุดหมายปลายทางยอดนิยม
                </h1>

                <div class="space-y-4 relative z-10">
                    @forelse($topDestinations as $dest)
                        <div
                            class="flex items-center justify-between p-5 rounded-[2rem] bg-white border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-500 group/item">
                            <div class="flex items-center gap-5">
                                <div
                                    class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center text-sm font-black transition-all duration-500 group-hover/item:bg-blue-600 group-hover/item:text-white group-hover/item:scale-110">
                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <span
                                    class="text-base font-black text-slate-800 transition-transform tracking-tight">{{ $dest->destination }}</span>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">ครั้ง</span>
                                <span
                                    class="text-2xl font-black text-slate-900 leading-none tracking-tighter">{{ $dest->count }}</span>
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-16 text-slate-300 italic text-sm font-black uppercase tracking-widest border-2 border-dashed border-slate-50 rounded-3xl">
                            ระบบกำลังรวบรวมข้อมูล...
                        </div>
                    @endforelse
                </div>

                <div class="mt-12 pt-8 border-t border-slate-50 flex justify-between items-center relative z-10">
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.4em]">Operations Intelligence
                        Hub</span>
                </div>
            </div>
        </div>
    </div>
@endsection