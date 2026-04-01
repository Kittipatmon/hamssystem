@extends('layouts.housing.apphousing')
@section('title', 'สรุปรายงานระบบบ้านพัก')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header & Year Filter --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Analytics Dashboard</h1>
            <p class="text-slate-500 font-medium">สรุปภาพรวมระบบบ้านพักและงานแจ้งซ่อม ประจำปี {{ $year }}</p>
        </div>
        
        <form action="{{ route('housing.report') }}" method="GET" class="flex items-center gap-2">
            <label class="text-sm font-bold text-slate-400 uppercase tracking-wider">เลือกปีงบประมาณ:</label>
            <div class="relative">
                <select name="year" onchange="this.form.submit()" 
                    class="appearance-none bg-white border-2 border-slate-100 rounded-2xl px-6 py-2.5 pr-10 text-sm font-bold text-slate-700 shadow-sm hover:border-red-200 transition-all focus:ring-red-500 focus:border-red-500 cursor-pointer">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-calendar-days absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
            </div>
        </form>
    </div>
    
    {{-- Most Frequent Insight --}}
    @if($topRepairs->isNotEmpty())
    <div class="mb-8 p-6 bg-gradient-to-r from-red-600 to-red-800 rounded-[2rem] shadow-2xl shadow-red-100 flex flex-col items-start gap-6 overflow-hidden relative group transition-all duration-500 hover:scale-[1.01]">
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
        
        <div class="flex items-center gap-6 relative z-10 w-full mb-2">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-xl rounded-xl flex items-center justify-center text-white text-xl shadow-lg ring-1 ring-white/30 group-hover:rotate-12 transition-transform duration-500">
                <i class="fa-solid fa-ranking-star"></i>
            </div>
            <div>
                <h4 class="text-white/70 font-black tracking-widest uppercase text-[10px]">Data Intelligence Report</h4>
                <p class="text-white text-xl font-black">5 อันดับงานแจ้งซ่อมที่พบมากที่สุด ประจำปี {{ $year }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 w-full relative z-10">
            @foreach($topRepairs as $i => $item)
                @php
                    $rankColors = [
                        0 => 'from-amber-300 to-amber-500', // Gold
                        1 => 'from-slate-200 to-slate-400', // Silver
                        2 => 'from-orange-300 to-orange-500', // Bronze
                        3 => 'from-white/20 to-white/10',
                        4 => 'from-white/20 to-white/10',
                    ];
                    $isTop3 = $i < 3;
                @endphp
                <div class="p-3 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex flex-col h-full group/card hover:bg-white/20 transition-all">
                    <div class="flex justify-between items-center mb-2">
                        <span class="w-6 h-6 rounded-lg bg-gradient-to-br {{ $rankColors[$i] ?? 'from-white/20 to-white/10' }} flex items-center justify-center text-[10px] font-black text-red-900 shadow-sm shadow-red-900/20">
                            {{ $i + 1 }}
                        </span>
                        <span class="text-[10px] font-black text-white/50 tracking-wider">{{ $item->count }} รายการ</span>
                    </div>
                    <div class="text-sm font-black text-white line-clamp-2 leading-tight flex-grow" title="{{ $item->title }}">
                        {{ $item->title }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Request -->
        <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-red-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-red-100">
                    <i class="fa-solid fa-file-circle-plus text-xl"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">คำขอเข้าพักทั้งหมด</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-800">{{ $requestStats->sum() }}</span>
                    <span class="text-xs font-bold text-slate-400">รายการ</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Occupancy Rate -->
        <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-emerald-100">
                    <i class="fa-solid fa-house-circle-check text-xl"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">อัตราการเข้าพักที่พัก</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-800">{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%</span>
                    <span class="text-xs font-bold text-slate-400">({{ $occupiedRooms }}/{{ $totalRooms }} ห้อง)</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Repairs -->
        <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-orange-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-orange-100">
                    <i class="fa-solid fa-screwdriver-wrench text-xl"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">แจ้งซ่อมประจำปี</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-800">{{ $repairStats->sum() }}</span>
                    <span class="text-xs font-bold text-slate-400">รายการ</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Under Repair -->
        <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:translate-y-[-4px] transition-all duration-300 overflow-hidden relative">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 bg-slate-700 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-hammer text-xl"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">อยู่ระหว่างซ่อมแซม</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-800">{{ $underRepair }}</span>
                    <span class="text-xs font-bold text-slate-400">ห้องพัก</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-800">แนวโน้มการแจ้งซ่อมรายเดือน</h3>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mt-1">Monthly Repair Trend</p>
                </div>
                <div class="p-2 px-3 bg-red-50 rounded-xl text-[10px] font-black text-red-600 border border-red-100 italic">
                    <i class="fa-solid fa-arrow-trend-up mr-1"></i> Data Insights
                </div>
            </div>
            <div class="h-80">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Repair Status Pie --}}
        <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm">
            <h3 class="text-lg font-black text-slate-800 mb-8 border-b border-slate-50 pb-4 flex items-center gap-3">
                <i class="fa-solid fa-chart-pie text-orange-500"></i> สัดส่วนงานซ่อม
            </h3>
            <div class="relative flex flex-col items-center">
                <div class="w-full max-w-[220px]">
                    <canvas id="repairPieChart"></canvas>
                </div>
                <div class="mt-8 grid grid-cols-2 gap-4 w-full">
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-amber-50">
                        <div class="w-3 h-3 rounded bg-amber-400 shadow-sm shadow-amber-200"></div>
                        <span class="text-[10px] font-bold text-slate-600">รอกำกับ: {{ $repairStats[0] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-blue-50">
                        <div class="w-3 h-3 rounded bg-blue-400 shadow-sm shadow-blue-200"></div>
                        <span class="text-[10px] font-bold text-slate-600">ดำเนินการ: {{ $repairStats[1] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-emerald-50">
                        <div class="w-3 h-3 rounded bg-emerald-400 shadow-sm shadow-emerald-200"></div>
                        <span class="text-[10px] font-bold text-slate-600">เสร็จสิ้น: {{ $repairStats[2] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded-xl bg-red-50">
                        <div class="w-3 h-3 rounded bg-red-400 shadow-sm shadow-red-200"></div>
                        <span class="text-[10px] font-bold text-slate-600">ยกเลิก: {{ $repairStats[3] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Residence Statistics --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-8">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-gradient-to-r from-white to-slate-50">
            <div>
                <h3 class="text-lg font-black text-slate-800">สถิติแยกตามสถานที่พัก</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Property Performance Allocation</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>
        <div class="overflow-x-auto p-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-slate-400 font-black uppercase text-[10px] tracking-widest">
                        <th class="px-6 py-4 text-left">สถานที่พัก</th>
                        <th class="px-6 py-4 text-center">ห้องทั้งหมด</th>
                        <th class="px-6 py-4 text-center">เข้าพักแล้ว</th>
                        <th class="px-6 py-4 text-center">คงเหลือว่าง</th>
                        <th class="px-6 py-4 text-right">สัดส่วนห้องพัก</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($residences as $res)
                        @php
                            $available = $res->rooms_count - $res->occupied;
                            $percent = $res->rooms_count > 0 ? ($res->occupied / $res->rooms_count) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $res->name }}</td>
                            <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $res->rooms_count }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-black">{{ $res->occupied }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-black">{{ $available }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <div class="w-24 h-2 rounded-full bg-slate-100 overflow-hidden hidden sm:block">
                                        <div class="h-full bg-emerald-400 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-xs font-black text-slate-700 min-w-[35px] text-right">{{ round($percent, 0) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Chart.js Script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Trend Chart ---
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); // red-500
        gradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [{
                    label: 'จำนวนรายการแจ้งซ่อม',
                    data: {!! json_encode($monthlyValues) !!},
                    borderColor: '#ef4444',
                    borderWidth: 4,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ef4444',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });

        // --- Repair Status Pie Chart ---
        const repairPieCtx = document.getElementById('repairPieChart').getContext('2d');
        new Chart(repairPieCtx, {
            type: 'doughnut',
            data: {
                labels: ['รอกำกับ', 'ดำเนินการ', 'เสร็จสิ้น', 'ยกเลิก'],
                datasets: [{
                    data: [
                        {{ $repairStats[0] ?? 0 }},
                        {{ $repairStats[1] ?? 0 }},
                        {{ $repairStats[2] ?? 0 }},
                        {{ $repairStats[3] ?? 0 }}
                    ],
                    backgroundColor: ['#fbbf24', '#60a5fa', '#34d399', '#f87171'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection
