@extends('layouts.housing.apphousing')
@section('title', 'สรุปรายงานระบบบ้านพัก')

@section('content')
<style>
    /* Hospital Ledger Table Styling */
    .clinical-table {
        width: 100%;
        border-collapse: collapse;
    }
    .clinical-table th {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border: 1px solid #cbd5e1 !important;
        padding: 12px 16px;
    }
    .clinical-table td {
        border: 1px solid #e2e8f0 !important;
        padding: 12px 16px;
        vertical-align: middle;
        font-size: 13px;
        background-color: #ffffff;
    }
    .clinical-table tr:hover td {
        background-color: #f8fafc;
    }
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Premium Header (Clinical Theme) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                <i class="fa-solid fa-chart-line text-red-600 text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">สรุปรายงานภาพรวมระบบบ้านพัก (ANALYTICS REPORT)</h2>
                <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                    <i class="fa-solid fa-calendar text-blue-500"></i>
                    สรุปภาพรวมระบบบ้านพักและงานแจ้งซ่อม ประจำปี {{ $year }}
                </p>
            </div>
        </div>
        
        <form action="{{ route('housing.report') }}" method="GET" class="flex items-center gap-2 shrink-0">
            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">เลือกปีงบประมาณ:</label>
            <div class="relative">
                <select name="year" onchange="this.form.submit()" 
                    class="appearance-none bg-white border border-slate-300 rounded-xl px-4 py-2 pr-9 text-xs font-bold text-slate-700 shadow-sm hover:border-slate-400 focus:ring-1 focus:ring-red-500 focus:border-red-500 cursor-pointer h-10">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[10px]"></i>
            </div>
        </form>
    </div>
    
    {{-- Most Frequent Insight --}}
    @if($topRepairs->isNotEmpty())
    <div class="mb-8 bg-slate-50 border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-150/50 border-b border-slate-200 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-white flex items-center justify-center text-amber-500 border border-slate-200 shadow-sm">
                <i class="fa-solid fa-ranking-star"></i>
            </div>
            <div>
                <h4 class="text-slate-500 font-bold tracking-widest uppercase text-[9px] leading-none mb-0.5">Data Intelligence Report</h4>
                <p class="text-slate-800 text-sm font-bold">5 อันดับงานแจ้งซ่อมที่พบมากที่สุด ประจำปี {{ $year }}</p>
            </div>
        </div>

        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            @foreach($topRepairs as $i => $item)
                @php
                    $rankColors = [
                        0 => 'bg-amber-500 text-white border border-amber-600', // Gold
                        1 => 'bg-slate-300 text-slate-800 border border-slate-400', // Silver
                        2 => 'bg-orange-500 text-white border border-orange-600', // Bronze
                        3 => 'bg-slate-100 text-slate-600 border border-slate-200',
                        4 => 'bg-slate-100 text-slate-600 border border-slate-200',
                    ];
                @endphp
                <div class="p-3 bg-white rounded border border-slate-200 flex flex-col h-full hover:bg-slate-50 transition-all">
                    <div class="flex justify-between items-center mb-2">
                        <span class="w-5 h-5 rounded {{ $rankColors[$i] ?? 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-[10px] font-black font-mono">
                            {{ $i + 1 }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 tracking-wider font-mono">{{ $item->count }} รายการ</span>
                    </div>
                    <div class="text-xs font-bold text-slate-700 line-clamp-2 leading-tight flex-grow" title="{{ $item->title }}">
                        {{ $item->title }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Request -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 bg-red-50 rounded-lg flex items-center justify-center text-red-600 border border-red-100 shrink-0">
                <i class="fa-solid fa-file-circle-plus text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">คำขอเข้าพักทั้งหมด</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-mono font-black text-slate-800">{{ $requestStats->sum() }}</span>
                    <span class="text-xs font-bold text-slate-400">รายการ</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Occupancy Rate -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 border border-emerald-100 shrink-0">
                <i class="fa-solid fa-house-circle-check text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">อัตราการเข้าพักที่พัก</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-mono font-black text-slate-800">{{ $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0 }}%</span>
                    <span class="text-[10px] font-bold text-slate-400">({{ $occupiedRooms }}/{{ $totalRooms }} ห้อง)</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Repairs -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600 border border-orange-100 shrink-0">
                <i class="fa-solid fa-screwdriver-wrench text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">แจ้งซ่อมประจำปี</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-mono font-black text-slate-800">{{ $repairStats->sum() }}</span>
                    <span class="text-xs font-bold text-slate-400">รายการ</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Under Repair -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-11 h-11 bg-slate-100 rounded-lg flex items-center justify-center text-slate-650 border border-slate-250 shrink-0">
                <i class="fa-solid fa-hammer text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">อยู่ระหว่างซ่อมแซม</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-mono font-black text-slate-800">{{ $underRepair }}</span>
                    <span class="text-xs font-bold text-slate-400">ห้องพัก</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Trend Chart --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6 border-b border-slate-150 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-800">แนวโน้มการแจ้งซ่อมรายเดือน</h3>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Monthly Repair Trend</p>
                </div>
                <div class="px-2 py-0.5 bg-red-50 border border-red-200 rounded text-[9px] font-bold text-red-600">
                    <i class="fa-solid fa-arrow-trend-up mr-0.5"></i> Data Insights
                </div>
            </div>
            <div class="h-80">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Repair Status Pie --}}
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-black text-slate-800 mb-6 border-b border-slate-200 pb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-orange-500"></i> สัดส่วนงานซ่อม
            </h3>
            <div class="relative flex flex-col items-center">
                <div class="w-full max-w-[200px]">
                    <canvas id="repairPieChart"></canvas>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-3 w-full">
                    <div class="flex items-center gap-2 p-2 rounded bg-amber-50 border border-amber-100">
                        <div class="w-2.5 h-2.5 rounded bg-amber-400 shadow-sm shrink-0"></div>
                        <span class="text-[10px] font-bold text-slate-600 truncate">รอกำกับ: {{ $repairStats[0] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded bg-blue-50 border border-blue-100">
                        <div class="w-2.5 h-2.5 rounded bg-blue-400 shadow-sm shrink-0"></div>
                        <span class="text-[10px] font-bold text-slate-600 truncate">ดำเนินการ: {{ $repairStats[1] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded bg-emerald-50 border border-emerald-100">
                        <div class="w-2.5 h-2.5 rounded bg-emerald-400 shadow-sm shrink-0"></div>
                        <span class="text-[10px] font-bold text-slate-600 truncate">เสร็จสิ้น: {{ $repairStats[2] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center gap-2 p-2 rounded bg-red-50 border border-red-100">
                        <div class="w-2.5 h-2.5 rounded bg-red-400 shadow-sm shrink-0"></div>
                        <span class="text-[10px] font-bold text-slate-600 truncate">ยกเลิก: {{ $repairStats[3] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Section: Residence Statistics --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-base font-black text-slate-800">สถิติแยกตามสถานที่พัก</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Property Performance Allocation</p>
            </div>
            <div class="w-8 h-8 rounded bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-hotel text-sm"></i>
            </div>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                <table class="clinical-table">
                    <thead>
                        <tr>
                            <th class="text-left">สถานที่พัก</th>
                            <th class="text-center" style="width: 120px;">ห้องทั้งหมด</th>
                            <th class="text-center" style="width: 120px;">เข้าพักแล้ว</th>
                            <th class="text-center" style="width: 120px;">คงเหลือว่าง</th>
                            <th class="text-right" style="width: 180px;">สัดส่วนห้องพัก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($residences as $res)
                            @php
                                $available = $res->rooms_count - $res->occupied;
                                $percent = $res->rooms_count > 0 ? ($res->occupied / $res->rooms_count) * 100 : 0;
                            @endphp
                            <tr>
                                <td class="font-bold text-slate-700">{{ $res->name }}</td>
                                <td class="text-center font-bold text-slate-500 font-mono">{{ $res->rooms_count }}</td>
                                <td class="text-center font-mono">
                                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded border border-emerald-250 text-xs font-bold">{{ $res->occupied }}</span>
                                </td>
                                <td class="text-center font-mono">
                                    <span class="px-2.5 py-0.5 bg-amber-50 text-amber-600 rounded border border-amber-250 text-xs font-bold">{{ $available }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-3">
                                        <div class="w-24 h-1.5 rounded-full bg-slate-100 overflow-hidden hidden sm:block">
                                            <div class="h-full bg-emerald-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 min-w-[35px] text-right font-mono">{{ round($percent, 0) }}%</span>
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
                    borderWidth: 3,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ef4444',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
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
                    hoverOffset: 12
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Intercept form submit to show loading modal
        const reportForm = document.querySelector('form');
        if (reportForm) {
            reportForm.addEventListener('submit', function() {
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
