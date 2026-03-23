@extends('layouts.navmeeting.app')

@section('title', 'รายงานการใช้งานห้องประชุม')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 pb-10">

        <!-- Header / Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-inner">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">รายงานการใช้งานห้องประชุม</h2>
                        <p class="text-sm text-slate-500 mt-0.5">ภาพรวมสถิติการใช้งานและการจองห้อง</p>
                    </div>
                </div>

                <form action="{{ route('backend.bookingmeeting.report.index') }}" method="GET"
                    class="flex flex-col sm:flex-row gap-3 items-end w-full md:w-auto">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">วันที่เริ่มต้น</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="input input-bordered h-10 text-sm focus:border-purple-500 w-full sm:w-auto">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">วันที่สิ้นสุด</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="input input-bordered h-10 text-sm focus:border-purple-500 w-full sm:w-auto">
                    </div>
                    <button type="submit"
                        class="btn bg-purple-600 hover:bg-purple-700 text-white border-0 h-10 min-h-10 px-6 w-full sm:w-auto shadow-md shadow-purple-200 gap-2">
                        <i class="fa-solid fa-filter text-xs"></i> กรองข้อมูล
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Stat Card 1 -->
                <div
                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div
                        class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">การจองทั้งหมด</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_reservations']) }} <span
                                class="text-sm font-normal text-slate-500">ครั้ง</span></p>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div
                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div
                        class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">อนุมัติสำเร็จ</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($stats['acknowledged_reservations']) }} <span
                                class="text-sm font-normal text-slate-500">ครั้ง</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div
                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div
                        class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">ยกเลิก / ไม่อนุมัติ</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['cancelled_reservations']) }}
                            <span class="text-sm font-normal text-slate-500">ครั้ง</span>
                        </p>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div
                    class="bg-slate-50 rounded-xl p-4 border border-slate-100 flex items-center gap-4 transition-transform hover:-translate-y-1 duration-300">
                    <div
                        class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">ห้องที่เปิดใช้งาน</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_rooms']) }} <span
                                class="text-sm font-normal text-slate-500">ห้อง</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Room Usage Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-purple-500"></i> สถิติการใช้งานแต่ละห้อง
                </h3>
                <div class="relative h-[350px] w-full">
                    <canvas id="roomUsageChart"></canvas>
                </div>
            </div>

            <!-- Summary Info -->
            <div
                class="bg-gradient-to-br from-[#1E2B3C] to-[#121418] rounded-2xl shadow-sm border border-slate-800 p-6 text-white relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 opacity-10 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                    <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V5H19V19M10,17V15H7V17H10M17,17V15H14V17H17M10,13V11H7V13H10M17,13V11H14V13H17M10,9V7H7V9H10M17,9V7H14V9H17Z" />
                    </svg>
                </div>

                <h3 class="text-base font-bold text-white mb-6 relative z-10 flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-amber-400"></i> สรุปข้อมูล
                </h3>

                <div class="space-y-4 relative z-10">
                    @php
                        $total = array_sum($roomStats);
                        arsort($roomStats);
                        $topRoom = key($roomStats);
                        $topCount = current($roomStats);
                    @endphp

                    <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10">
                        <p class="text-xs text-slate-300 mb-1">ยอดการจองรวมในคาบเวลานี้</p>
                        <p class="text-3xl font-bold text-white">{{ $total }} <span
                                class="text-sm font-normal text-slate-400">รายการ</span></p>
                    </div>

                    @if($topRoom)
                        <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm border border-white/10 mt-4">
                            <p class="text-xs text-slate-300 mb-1">ห้องที่ถูกใช้งานบ่อยที่สุด</p>
                            <p class="text-lg font-bold text-amber-400 truncate" title="{{ $topRoom }}"><i
                                    class="fa-regular fa-star text-sm mr-1"></i> {{ $topRoom }}</p>
                            <p class="text-sm text-slate-300 mt-1">{{ number_format($topCount) }} ครั้ง
                                ({{ $total > 0 ? round(($topCount / $total) * 100) : 0 }}%)</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detailed List Row -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-table-list text-slate-400"></i> รายการจองในคาบเวลา
                </h3>
                <button onclick="window.print()"
                    class="btn btn-sm bg-white border-slate-300 text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-print text-xs mr-1"></i> พิมพ์รายงาน
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="bg-white text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="font-medium">วันที่</th>
                            <th class="font-medium">ห้องประชุม</th>
                            <th class="font-medium">เวลา</th>
                            <th class="font-medium">หัวข้อ/รายละเอียด</th>
                            <th class="font-medium">ผู้ใช้งาน</th>
                            <th class="text-center font-medium">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                            <tr class="hover">
                                <td class="whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($res->reservation_date)->format('d/m/Y') }}
                                </td>
                                <td class="font-medium text-slate-800">{{ $res->room->room_name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap">{{ substr($res->start_time, 0, 5) }} -
                                    {{ substr($res->end_time, 0, 5) }}
                                </td>
                                <td>
                                    <div class="max-w-xs truncate" title="{{ $res->topic }}">{{ $res->topic }}</div>
                                </td>
                                <td>{{ $res->requester_name }}</td>
                                <td class="text-center">
                                    @if($res->status == 'acknowledge')
                                        <span
                                            class="text-green-600 bg-green-50 px-2 py-0.5 rounded text-xs font-medium border border-green-200">อนุมัติแล้ว</span>
                                    @elseif($res->status == 'rejected')
                                        <span
                                            class="text-red-600 bg-red-50 px-2 py-0.5 rounded text-xs font-medium border border-red-200">ไม่อนุมัติ</span>
                                    @elseif($res->status == 'cancelled')
                                        <span
                                            class="text-orange-600 bg-orange-50 px-2 py-0.5 rounded text-xs font-medium border border-orange-200">ยกเลิก</span>
                                    @else
                                        <span
                                            class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded text-xs font-medium border border-amber-200">รออนุมัติ</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">ไม่พบรายการในช่วงเวลานี้</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50 text-center">
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
                            backgroundColor: 'rgba(139, 92, 246, 0.7)', // Purple-500 with opacity
                            borderColor: 'rgb(139, 92, 246)',
                            borderWidth: 1,
                            borderRadius: 6,
                            hoverBackgroundColor: 'rgba(139, 92, 246, 0.9)',
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
                                backgroundColor: 'rgba(30, 43, 60, 0.9)',
                                padding: 12,
                                titleFont: { size: 14, family: 'Prompt' },
                                bodyFont: { size: 13, family: 'Prompt' },
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
                                    font: { family: 'Prompt' }
                                },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.5)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                ticks: {
                                    font: { family: 'Prompt' }
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
            .shadow-sm {
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