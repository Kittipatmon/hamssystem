@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-chart-line text-red-500"></i>
                    ภาพรวมระบบรถส่วนกลาง (Backend)
                </h2>
                <p class="text-slate-500 mt-1 text-sm">Dashboard แสดงสถิติและภาพรวมทั้งหมดของระบบ</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('backend.vehicles.table') }}"
                    class="btn btn-sm bg-white text-slate-700 border-slate-200 hover:bg-slate-50 shadow-sm rounded-full px-4">
                    <i class="fa-solid fa-table border-slate-200"></i> ดูข้อมูลแบบตาราง
                </a>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Total Vehicles -->
            <div
                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">รถทั้งหมดในระบบ</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $totalVehicles }} <span
                            class="text-sm font-normal text-slate-500">คัน</span></h3>
                    <p class="text-[11px] text-slate-400 mt-1">พร้อมใช้งาน: <span
                            class="text-blue-600 font-medium">{{ $availableVehicles }}</span> คัน</p>
                </div>
            </div>

            <!-- Total Bookings -->
            <div
                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider mb-1">การจองทั้งหมด</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $totalBookings }} <span
                            class="text-sm font-normal text-slate-500">รายการ</span></h3>
                    <p class="text-[11px] text-slate-400 mt-1">อนุมัติแล้ว: <span
                            class="text-purple-600 font-medium">{{ $approvedBookings }}</span> รายการ</p>
                </div>
            </div>

            <!-- Pending Bookings -->
            <div
                class="bg-white rounded-2xl p-6 border border-orange-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p class="text-orange-600 text-xs font-semibold uppercase tracking-wider mb-1">รอการอนุมัติ</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $pendingBookings }} <span
                            class="text-sm font-normal text-slate-500">รายการ</span></h3>
                    <p class="text-[11px] text-orange-500 mt-1">ต้องตรวจสอบคำร้อง</p>
                </div>
            </div>

            <!-- Pending Inspections -->
            <div
                class="bg-white rounded-2xl p-6 border border-red-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                <div
                    class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <p class="text-red-600 text-xs font-semibold uppercase tracking-wider mb-1">รอตรวจเช็คสภาพ</p>
                    <h3 class="text-2xl font-bold text-slate-800">{{ $pendingInspections }} <span
                            class="text-sm font-normal text-slate-500">รายการ</span></h3>
                    <p class="text-[11px] text-red-500 mt-1">จำเป็นต้องดำเนินการ</p>
                </div>
            </div>

        </div>

        <!-- Recent Bookings Snapshot -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-list-ul text-slate-400"></i>
                    รายการจองล่าสุด (5 รายการ)
                </h3>
                <a href="{{ route('backend.vehicles.table') }}"
                    class="text-blue-500 hover:text-blue-700 text-xs font-medium">ดูทั้งหมด &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full text-[13px]">
                    <thead class="bg-white text-slate-500 font-medium text-[11px] border-b border-slate-100">
                        <tr>
                            <th class="py-3 pl-5 bg-white font-medium">เลขที่ใบจอง</th>
                            <th class="py-3 bg-white font-medium">ผู้จอง</th>
                            <th class="py-3 bg-white font-medium">รถที่จอง</th>
                            <th class="py-3 bg-white font-medium">วันที่</th>
                            <th class="py-3 bg-white font-medium">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-slate-50/50 border-b border-slate-50 last:border-0 pointer-events-none">
                                <td class="pl-5 font-mono text-xs">{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->first_name ?? 'N/A' }} {{ $booking->user->last_name ?? '' }}</td>
                                <td>
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">
                                        {{ $booking->vehicle->name ?? 'ไม่ระบุ' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $statusClass = match ($booking->status) {
                                            'อนุมัติแล้ว' => 'text-green-600 bg-green-50',
                                            'รออนุมัติ' => 'text-orange-600 bg-orange-50',
                                            'ไม่อนุมัติ', 'ยกเลิก' => 'text-red-600 bg-red-50',
                                            default => 'text-slate-600 bg-slate-50'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-[10px] font-semibold {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">ไม่มีรายการจองล่าสุด</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection