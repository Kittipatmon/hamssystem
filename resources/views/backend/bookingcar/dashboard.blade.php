@extends('layouts.bookingcar.appcar')

@section('content')
    <style>
        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
            border: 1px solid transparent;
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
    </style>
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 animate-fadeIn">

        <!-- New Premium Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center shadow-sm border border-sky-100 shrink-0">
                    <i class="fa-solid fa-chart-line text-sky-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">ภาพรวมระบบรถส่วนกลาง (DASHBOARD)</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-hospital text-blue-500"></i>
                        ระบบวิเคราะห์ข้อมูล ยานพาหนะ และประวัติการจองรถส่วนกลาง
                    </p>
                </div>
            </div>
            <div class="w-full md:w-auto">
                <a href="{{ route('backend.bookingcar.table') }}"
                    class="btn bg-slate-800 hover:bg-slate-900 text-white border-0 shadow-lg shadow-slate-250 rounded-2xl px-6 transition-all hover:scale-105 active:scale-95 text-xs sm:text-sm h-10 sm:h-12 w-full sm:w-auto flex items-center justify-center gap-2">
                    <i class="fa-solid fa-table"></i> เข้าสู่หน้าจัดการข้อมูลตาราง
                </a>
            </div>
        </div>

        <!-- Clinical KPI Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <!-- Card 1: Total Fleet Status -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-inner">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">Fleet Spec</span>
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-wider mb-1">รถยนต์ทั้งหมดในระบบ</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $totalVehicles }} <span class="text-sm font-bold text-slate-500">คัน</span></h3>
                </div>
                <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex justify-between text-xs font-semibold text-slate-600">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> ว่าง: {{ $availableVehicles }}</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> ไม่ว่าง: {{ $totalVehicles - $availableVehicles }}</span>
                </div>
            </div>

            <!-- Card 2: Total Booking Log -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-inner">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-200">Booking Log</span>
                    </div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-wider mb-1">การจองทั้งหมด</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $totalBookings }} <span class="text-sm font-bold text-slate-500">รายการ</span></h3>
                </div>
                <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex justify-between text-xs font-semibold text-slate-600">
                    <span class="text-emerald-700 font-bold"><i class="fa-solid fa-check mr-1"></i> อนุมัติ: {{ $approvedBookings }}</span>
                    <span class="text-rose-700 font-bold"><i class="fa-solid fa-xmark mr-1"></i> ยกเลิก: {{ $totalBookings - $approvedBookings - $pendingBookings }}</span>
                </div>
            </div>

            <!-- Card 3: Pending Action -->
            <div class="bg-white rounded-2xl border border-amber-200 shadow-sm overflow-hidden flex flex-col justify-between bg-amber-50/10">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shadow-inner">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-800 px-2 py-0.5 rounded border border-amber-300">Requires Review</span>
                    </div>
                    <p class="text-amber-800 text-[10px] font-black uppercase tracking-wider mb-1">คำขอรอการอนุมัติ</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $pendingBookings }} <span class="text-sm font-bold text-slate-500">รายการ</span></h3>
                </div>
                <div class="bg-amber-50 px-5 py-3 border-t border-amber-200 text-xs font-bold text-amber-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600 animate-pulse"></i> ต้องได้รับการตรวจสอบโดยเจ้าหน้าที่
                </div>
            </div>

            <!-- Card 4: Overdue Maintenance -->
            <div class="bg-white rounded-2xl border border-rose-200 shadow-sm overflow-hidden flex flex-col justify-between bg-rose-50/10">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-lg shadow-inner">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest bg-rose-100 text-rose-800 px-2 py-0.5 rounded border border-rose-300">Overdue Alert</span>
                    </div>
                    <p class="text-rose-800 text-[10px] font-black uppercase tracking-wider mb-1">รถยนต์รอการตรวจเช็คสภาพ</p>
                    <h3 class="text-3xl font-black text-slate-800 font-mono">{{ $pendingInspections }} <span class="text-sm font-bold text-slate-500">รายการ</span></h3>
                </div>
                <div class="bg-rose-50 px-5 py-3 border-t border-rose-200 text-xs font-bold text-rose-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-screwdriver-wrench text-rose-600 animate-pulse"></i> ตรวจสอบการทำความสะอาด/ไมล์บำรุงรักษา
                </div>
            </div>

        </div>

        <!-- Recent Bookings Hospital-Style Report Grid -->
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl overflow-hidden mb-8">
            <div class="px-8 py-5 border-b border-slate-200 bg-slate-800 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-notes-medical text-sky-400 text-lg"></i>
                    <h3 class="font-black text-sm uppercase tracking-wider">
                        รายงานการขอใช้รถส่วนกลางล่าสุด (RECENT BOOKINGS CLINICAL REPORT)
                    </h3>
                </div>
                <span class="text-[10px] font-black bg-slate-700 text-sky-400 px-3 py-1 rounded-full border border-slate-600">
                    อัพเดตล่าสุด
                </span>
            </div>
            
            <div class="overflow-x-auto p-6 bg-slate-50/30">
                <table class="w-full border-collapse border border-slate-200 shadow-sm bg-white text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-bold uppercase tracking-wider text-[11px] border-b border-slate-300">
                            <th class="py-3 px-4 border border-slate-200 text-center">รหัสใบจอง (CODE)</th>
                            <th class="py-3 px-4 border border-slate-200 text-left">ผู้ขออนุมัติ (APPLICANT)</th>
                            <th class="py-3 px-4 border border-slate-200 text-left">ยานพาหนะที่เลือก (VEHICLE)</th>
                            <th class="py-3 px-4 border border-slate-200 text-center">วันที่ยื่นคำขอ (SUBMIT DATE)</th>
                            <th class="py-3 px-4 border border-slate-200 text-center">สถานะการทำรายการ (STATUS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-slate-700">
                        @forelse($recentBookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 border border-slate-200 text-center font-mono font-bold text-slate-800">
                                    #{{ $booking->booking_code }}
                                </td>
                                <td class="py-3.5 px-4 border border-slate-200 font-bold text-slate-800">
                                    <div class="flex flex-col">
                                        <span>{{ $booking->user->first_name ?? 'N/A' }} {{ $booking->user->last_name ?? '' }}</span>
                                        <span class="text-[10px] text-slate-400 font-normal">ฝ่าย/แผนก: {{ $booking->user->department->department_name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 border border-slate-200">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-car text-slate-400"></i>
                                        <span class="font-bold text-slate-700">
                                            {{ $booking->vehicle->name ?? 'ไม่ระบุ' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 border border-slate-200 text-center font-mono text-slate-500">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}
                                </td>
                                <td class="py-3.5 px-4 border border-slate-200 text-center">
                                    @php
                                        $statusClass = match ($booking->status) {
                                            'อนุมัติแล้ว' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                                            'รออนุมัติ' => 'bg-orange-50 text-orange-700 border-orange-300',
                                            'ไม่อนุมัติ', 'ยกเลิก' => 'bg-rose-50 text-rose-700 border-rose-300',
                                            default => 'bg-slate-50 text-slate-700 border-slate-300'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black border {{ $statusClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $booking->status === 'อนุมัติแล้ว' ? 'bg-emerald-600' : ($booking->status === 'รออนุมัติ' ? 'bg-orange-600' : 'bg-rose-600') }}"></span>
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center border border-slate-200">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-200">
                                            <i class="fa-solid fa-receipt text-2xl"></i>
                                        </div>
                                        <span class="text-slate-400 font-bold text-xs">ไม่มีรายการจองล่าสุดในระบบ</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection