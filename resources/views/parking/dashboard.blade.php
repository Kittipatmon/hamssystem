@extends('layouts.parking.app')

@section('content')
<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight">ระบบลานจอดรถ <span class="text-emerald-600">Parking Management</span></h2>
                <p class="text-slate-500 mt-1 font-medium">จัดการพื้นที่จอดรถและดูแลการจองสำหรับแขก (Visitor Parking)</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('parking.employees.index') }}" class="btn bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm rounded-xl">
                    <i class="fa-solid fa-car-side mr-1 text-blue-500"></i> รถพนักงาน
                </a>
                <a href="{{ route('parking.visitors.index') }}" class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-none shadow-lg shadow-emerald-200 rounded-xl">
                    <i class="fa-solid fa-plus mr-1"></i> จองที่จอดรถแขก
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Stat 1 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-blue-50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-[13px] font-bold mb-1 uppercase tracking-wider">ช่องจอดทั้งหมด</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $totalSlots ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-emerald-50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-[13px] font-bold mb-1 uppercase tracking-wider">ว่างพร้อมใช้งาน</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $availableSlots ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-inner">
                        <i class="fa-solid fa-check-to-slot text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-red-50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-[13px] font-bold mb-1 uppercase tracking-wider">รถที่จอดอยู่ขณะนี้</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $occupiedSlots ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-red-600 shadow-inner">
                        <i class="fa-solid fa-car text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-amber-50 transition-transform duration-500 group-hover:scale-150"></div>
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-slate-500 text-[13px] font-bold mb-1 uppercase tracking-wider">การจองแขกวันนี้</p>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $todayReservations ?? 0 }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shadow-inner">
                        <i class="fa-solid fa-user-tie text-xl"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main Content Area -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Charts Section (Col Span 2) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col p-6">
                <div class="border-b border-slate-100 pb-4 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2 tracking-tight">
                        <i class="fa-solid fa-chart-pie text-indigo-500"></i> รายงานและการวิเคราะห์การใช้งาน
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('parking.map.full') }}" class="btn btn-xs bg-slate-100 hover:bg-slate-200 border-none text-slate-700 font-bold rounded-lg px-3">
                            <i class="fa-solid fa-map"></i> แผนผัง HQ (Outdoor)
                        </a>
                        <a href="{{ route('parking.map.building') }}" class="btn btn-xs bg-slate-100 hover:bg-slate-200 border-none text-slate-700 font-bold rounded-lg px-3">
                            <i class="fa-solid fa-building-user"></i> แผนผังอาคาร (Indoor)
                        </a>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center flex-1">
                    <!-- Chart 1: Donut occupancy -->
                    <div class="flex flex-col items-center">
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">สัดส่วนสถานะช่องจอดในระบบ</h4>
                        <div class="w-full max-w-[220px]">
                            <canvas id="occupancyChart"></canvas>
                        </div>
                    </div>
                    <!-- Chart 2: Bar user type -->
                    <div class="flex flex-col items-center">
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">ผู้ใช้งานลานจอดจำแนกประเภท</h4>
                        <div class="w-full max-w-[220px]">
                            <canvas id="userTypeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity / Logs (Col Span 1) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col h-[500px] lg:h-auto">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center shrink-0">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2 tracking-tight">
                        <i class="fa-solid fa-clock-rotate-left text-orange-500"></i> รถเข้า-ออกล่าสุด
                    </h3>
                    <span class="badge badge-sm bg-orange-100 text-orange-700 border-none font-bold">วันนี้: {{ $todayCarsIn ?? 0 }} คัน</span>
                </div>
                <div class="p-4 flex-1 overflow-y-auto bg-slate-50/30 space-y-3">
                    
                    @forelse($recentActivities as $activity)
                        <div class="p-3 bg-white rounded-xl border border-slate-100 shadow-sm flex items-center justify-between gap-3 hover:border-slate-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $activity['type'] === 'employee' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                                    <i class="fa-solid {{ $activity['type'] === 'employee' ? 'fa-user-tie' : 'fa-user-clock' }}"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $activity['name'] }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <span class="px-1.5 py-0.5 bg-slate-100 text-slate-700 font-bold rounded text-[10px]">{{ $activity['car_registration'] }}</span>
                                        &bull; ช่อง {{ $activity['slot'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-sm border-none font-bold text-[10px] {{ $activity['status'] === 'เข้าจอด' ? 'bg-emerald-100 text-emerald-800' : ($activity['status'] === 'ออกแล้ว' ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $activity['status'] }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1 font-semibold">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <!-- Placeholder when empty -->
                        <div class="h-full flex flex-col items-center justify-center py-12 text-center text-slate-400">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-slate-100">
                                <i class="fa-solid fa-car-tunnel text-2xl text-slate-300"></i>
                            </div>
                            <p class="font-bold text-slate-600 mb-1">ยังไม่มีรายการ</p>
                            <p class="text-xs font-medium">ยังไม่มีข้อมูลการเข้า-ออกของรถในวันนี้</p>
                        </div>
                    @endforelse
                    
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Data from backend variables
        const avail = {{ $availableSlots ?? 0 }};
        const occupied = {{ $occupiedSlots ?? 0 }};
        const reserved = {{ $reservedSlots ?? 0 }};
        const guests = {{ $guestsCurrentlyParking ?? 0 }};
        const employees = Math.max(0, occupied - guests);

        // Occupancy Chart
        new Chart(document.getElementById('occupancyChart'), {
            type: 'doughnut',
            data: {
                labels: ['ว่าง', 'ไม่ว่าง (พนักงาน/แขก)', 'ปิด/สงวนสิทธิ์'],
                datasets: [{
                    data: [avail, occupied, reserved],
                    backgroundColor: ['#10b981', '#ef4444', '#94a3b8'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });

        // User Type Chart
        new Chart(document.getElementById('userTypeChart'), {
            type: 'doughnut',
            data: {
                labels: ['รถพนักงาน', 'รถบุคคลภายนอก (Visitor)'],
                datasets: [{
                    data: [employees, guests],
                    backgroundColor: ['#dc2626', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
