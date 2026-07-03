@extends('layouts.navmeeting.app')

@section('title', 'รายงานการใช้งานห้องประชุม')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 pb-10">

        <!-- Header / Filters -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">รายงานการใช้งานห้องประชุม</h2>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">ภาพรวมเชิงสถิติ ข้อมูลความถี่ และบันทึกรายงานสรุปสัดส่วนการจองใช้งาน</p>
                    </div>
                </div>

                <form action="{{ route('backend.bookingmeeting.report.index') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-3 items-end w-full lg:w-auto text-xs font-semibold">
                    
                    <div class="w-full sm:w-auto">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">ปี</label>
                        <select name="year" class="w-full bg-slate-50 border border-slate-200 rounded h-9 px-3 focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all text-slate-800 min-w-[100px]">
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for($i = $currentYear; $i >= $currentYear - 5; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i + 543 }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">เดือน</label>
                        <select name="month" class="w-full bg-slate-50 border border-slate-200 rounded h-9 px-3 focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all text-slate-800 min-w-[120px]">
                            <option value="">ทั้งหมด</option>
                            @php
                                $months = [
                                    '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
                                    '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                                    '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                                    '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
                                ];
                            @endphp
                            @foreach($months as $m => $name)
                                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <label class="block text-[11px] font-bold text-slate-500 mb-1">วัน (ตัวเลือก)</label>
                        <select name="day" class="w-full bg-slate-50 border border-slate-200 rounded h-9 px-3 focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all text-slate-800 min-w-[100px]">
                            <option value="">ทั้งหมด</option>
                            @for($i = 1; $i <= 31; $i++)
                                @php $d = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                <option value="{{ $d }}" {{ $day == $d ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit"
                        class="bg-[#c31919] hover:bg-red-800 text-white font-bold h-9 rounded px-6 shadow transition-all flex items-center justify-center gap-1.5 w-full sm:w-auto shrink-0">
                        <i class="fa-solid fa-filter text-[10px]"></i> ค้นหารายงาน
                    </button>
                </form>
            </div>

            <!-- Stats Counters -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100 text-xs">
                <!-- Stat Card 1 -->
                <div class="bg-slate-50/50 rounded p-4 border border-slate-200 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded bg-red-50 text-red-600 flex items-center justify-center text-lg shrink-0 border border-red-100">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">การจองทั้งหมด</p>
                        <p class="text-xl font-black text-slate-800 mt-0.5">
                            {{ number_format($stats['total_reservations']) }} <span class="text-xs font-normal text-slate-400">ครั้ง</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-slate-50/50 rounded p-4 border border-slate-200 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-100">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">อนุมัติเสร็จสิ้น</p>
                        <p class="text-xl font-black text-slate-800 mt-0.5">
                            {{ number_format($stats['acknowledged_reservations']) }} <span class="text-xs font-normal text-slate-400">ครั้ง</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-slate-50/50 rounded p-4 border border-slate-200 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded bg-rose-50 text-rose-600 flex items-center justify-center text-lg shrink-0 border border-rose-100">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">ยกเลิก / ไม่อนุมัติ</p>
                        <p class="text-xl font-black text-slate-800 mt-0.5">
                            {{ number_format($stats['cancelled_reservations']) }} <span class="text-xs font-normal text-slate-400">ครั้ง</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-slate-50/50 rounded p-4 border border-slate-200 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 border border-blue-100">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">จำนวนห้องประชุม</p>
                        <p class="text-xl font-black text-slate-800 mt-0.5">
                            {{ number_format($stats['total_rooms']) }} <span class="text-xs font-normal text-slate-400">ห้อง</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Room Usage Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 p-5 shadow-sm">
                <h3 class="text-xs font-bold text-slate-700 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2 uppercase tracking-wider">
                    <i class="fa-solid fa-chart-column text-red-600"></i> แผนภูมิความถี่การใช้งานห้องประชุมแต่ละห้อง
                </h3>
                <div class="relative h-[280px] w-full">
                    <canvas id="roomUsageChart"></canvas>
                </div>
            </div>

            <!-- Summary Sheet -->
            <div class="bg-gradient-to-br from-[#1E2B3C] to-[#121418] rounded-lg border border-slate-800 p-5 text-white relative overflow-hidden flex flex-col justify-between shadow-sm">
                <div class="absolute top-0 right-0 opacity-5 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                    <svg width="180" height="180" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V5H19V19M10,17V15H7V17H10M17,17V15H14V17H17M10,13V11H7V13H10M17,13V11H14V13H17M10,9V7H7V9H10M17,9V7H14V9H17Z" />
                    </svg>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-wider pb-2 border-b border-white/10 flex items-center gap-2">
                        <i class="fa-solid fa-ranking-star text-amber-400 animate-pulse"></i> ข้อมูลรายงานสรุปย่อ
                    </h3>

                    @php
                        $total = array_sum($roomStats);
                        arsort($roomStats);
                        $topRoom = key($roomStats);
                        $topCount = current($roomStats);
                    @endphp

                    <div class="bg-white/5 rounded p-3.5 border border-white/5 text-xs">
                        <p class="text-slate-400 font-semibold mb-1">ยอดรวมบันทึกคิวจอง:</p>
                        <p class="text-2xl font-black text-white">{{ $total }} <span class="text-xs font-normal text-slate-400">รายการ</span></p>
                    </div>

                    @if($topRoom)
                        <div class="bg-white/5 rounded p-3.5 border border-white/5 text-xs">
                            <p class="text-slate-400 font-semibold mb-1">ห้องที่ถูกใช้บ่อยที่สุด:</p>
                            <p class="text-sm font-bold text-amber-400 truncate" title="{{ $topRoom }}">
                                <i class="fa-regular fa-star mr-1"></i> {{ $topRoom }}
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">
                                สถิติใช้งาน: <span class="font-bold text-white">{{ number_format($topCount) }} ครั้ง</span>
                                ({{ $total > 0 ? round(($topCount / $total) * 100) : 0 }}%)
                            </p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-white/5 text-[10px] text-slate-400 font-semibold italic text-right">
                    Kumwell Meeting Room Scheduler Statistics
                </div>
            </div>
        </div>

        <!-- Detailed Log Table -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between no-print">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-slate-500 text-sm"></i>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                        ตารางสรุปบันทึกการจองตามรอบช่วงเวลาคัดกรอง
                    </h2>
                </div>
                <button onclick="window.print()"
                    class="bg-white hover:bg-slate-50 text-slate-700 font-bold border border-slate-300 rounded h-7 px-3.5 text-[10px] transition-all flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-print text-[9px]"></i> พิมพ์รายงาน
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border-slate-200 text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-12">#</th>
                            <th class="py-3 px-3 border-r border-slate-200 w-28">วันที่ประชุม</th>
                            <th class="py-3 px-3 border-r border-slate-200 w-44">ห้องประชุมที่จอง</th>
                            <th class="py-3 px-3 border-r border-slate-200 w-36">ช่วงเวลาจอง</th>
                            <th class="py-3 px-3 border-r border-slate-200 min-w-[200px]">หัวข้อการจัดประชุม</th>
                            <th class="py-3 px-3 border-r border-slate-200 w-40">ผู้ประสานงาน / ผู้จอง</th>
                            <th class="py-3 px-3 text-center w-28">ผลการอนุมัติ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($reservations as $index => $res)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                                    {{ $reservations->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 font-bold text-slate-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($res->reservation_date)->locale('th')->addYears(543)->translatedFormat('d/m/Y') }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 font-bold text-red-700">{{ $res->room->room_name ?? 'N/A' }}</td>
                                <td class="py-3 px-3 border-r border-slate-200 font-semibold text-slate-600 whitespace-nowrap">
                                    {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} น.
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 leading-normal font-semibold text-slate-800">
                                    {{ $res->topic }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 leading-tight">
                                    <div class="font-bold text-slate-800">{{ $res->requester_name }}</div>
                                    <div class="text-[9px] text-slate-400 font-bold mt-0.5">ผู้จอง: {{ $res->user->fullname ?? 'Guest' }}</div>
                                </td>
                                <td class="py-3 px-3 text-center whitespace-nowrap">
                                    @if($res->status == 'acknowledge')
                                        <span class="text-green-700 bg-green-50 px-2 py-0.5 rounded text-[10px] font-bold border border-green-200 shadow-sm">อนุมัติแล้ว</span>
                                    @elseif($res->status == 'rejected')
                                        <span class="text-red-700 bg-red-50 px-2 py-0.5 rounded text-[10px] font-bold border border-red-200 shadow-sm">ไม่อนุมัติ</span>
                                    @elseif($res->status == 'cancelled')
                                        <span class="text-slate-500 bg-slate-50 px-2 py-0.5 rounded text-[10px] font-bold border border-slate-200 shadow-sm">ยกเลิก</span>
                                    @else
                                        <span class="text-amber-700 bg-amber-50 px-2 py-0.5 rounded text-[10px] font-bold border border-amber-200 shadow-sm">รออนุมัติ</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-slate-400 font-semibold">ไม่พบรายการในช่วงเวลานี้</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Prepare Chart Data
            const statsData = @json($roomStats);
            const labels = Object.keys(statsData);
            const data = Object.values(statsData);

            const ctx = document.getElementById('roomUsageChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'จำนวนครั้งที่ใช้งาน',
                            data: data,
                            backgroundColor: 'rgba(195, 25, 25, 0.75)', // Kumwell Red with opacity
                            borderColor: 'rgb(195, 25, 25)',
                            borderWidth: 1,
                            borderRadius: 4,
                            hoverBackgroundColor: 'rgba(195, 25, 25, 0.95)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                bottom: 10
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(30, 43, 60, 0.95)',
                                padding: 10,
                                titleFont: { size: 12, family: 'Prompt', weight: 'bold' },
                                bodyFont: { size: 11, family: 'Prompt' },
                                displayColors: false,
                                callbacks: {
                                    label: function (context) {
                                        return context.parsed.y + ' ครั้ง';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: { family: 'Prompt', size: 10 }
                                },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.6)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: { family: 'Prompt', size: 10 }
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    <style>
        @media print {
            body {
                background: white;
            }

            #sidebar,
            .btn,
            form,
            nav,
            .breadcrumbs,
            .shadow-sm,
            .no-print {
                display: none !important;
            }

            .max-w-7xl {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .bg-white {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
@endpush