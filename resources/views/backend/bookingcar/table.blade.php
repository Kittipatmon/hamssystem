@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[95%] xl:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-table border-slate-200 text-red-500"></i>
                    ข้อมูลระบบรถส่วนกลางแบบตาราง
                </h2>
                <p class="text-slate-500 mt-1 text-sm">ดูข้อมูลดิบของระบบเพื่อการตรวจสอบและบำรุงรักษา</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('backend.vehicles.dashboard') }}"
                    class="btn btn-sm bg-white text-slate-700 border-slate-200 hover:bg-slate-50 shadow-sm rounded-full px-4">
                    <i class="fa-solid fa-chart-line"></i> กลับสู่หน้า Dashboard
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs tabs-boxed bg-slate-100 p-1 rounded-xl mb-6 inline-flex" role="tablist">
            <a class="tab tab-active font-semibold text-sm h-10 px-6 rounded-lg transition-all"
                onclick="showTab('tab-vehicles')" id="btn-vehicles">
                <i class="fa-solid fa-car mr-2"></i> ข้อมูลรถยนต์ (Vehicles)
            </a>
            <a class="tab font-semibold text-sm h-10 px-6 rounded-lg transition-all" onclick="showTab('tab-bookings')"
                id="btn-bookings">
                <i class="fa-solid fa-receipt mr-2"></i> ข้อมูลการจอง (Bookings)
            </a>
            <a class="tab font-semibold text-sm h-10 px-6 rounded-lg transition-all" onclick="showTab('tab-inspections')"
                id="btn-inspections">
                <i class="fa-solid fa-wrench mr-2"></i> ประวัติการตรวจเช็ค (Inspections)
            </a>
        </div>

        <!-- Tab 1: Vehicles -->
        <div id="tab-vehicles" class="tab-pane active">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <i class="fa-solid fa-car text-blue-500 text-lg"></i>
                    <h3 class="font-bold text-slate-700">ตาราง vehicles</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-[12px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white"># ID</th>
                                <th class="bg-white">ทะเบียน/ชื่อรถ (name)</th>
                                <th class="bg-white">ยี่ห้อ (brand)</th>
                                <th class="bg-white">รุ่น (model_name)</th>
                                <th class="bg-white text-center">ประเภท (type)</th>
                                <th class="bg-white text-center">สถานะ (status)</th>
                                <th class="bg-white text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($vehicles as $vehicle)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td>{{ $vehicle->vehicle_id }}</td>
                                    <td class="font-semibold">{{ $vehicle->name }}</td>
                                    <td>{{ $vehicle->brand ?? '-' }}</td>
                                    <td>{{ $vehicle->model_name ?? '-' }}</td>
                                    <td class="text-center">{{ $vehicle->type ?? '-' }}</td>
                                    <td class="text-center">
                                        <span
                                            class="px-2 py-1 rounded text-[10px] font-medium 
                                                {{ $vehicle->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $vehicle->status }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('backend.vehicles.edit', $vehicle->vehicle_id) }}"
                                            class="btn btn-xs btn-outline btn-info rounded-md">
                                            <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Bookings -->
        <div id="tab-bookings" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <i class="fa-solid fa-receipt text-purple-500 text-lg"></i>
                    <h3 class="font-bold text-slate-700">ตาราง vehicle_bookings</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-[12px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white">Tracking Code</th>
                                <th class="bg-white">พนักงาน (user_id)</th>
                                <th class="bg-white">รหัสรถ (vehicle_id)</th>
                                <th class="bg-white">วันที่เดินทาง</th>
                                <th class="bg-white">เวลา (start-end)</th>
                                <th class="bg-white">สถานะจอง</th>
                                <th class="bg-white">สถานะคืนรถ</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td class="font-mono text-xs">{{ $booking->booking_code }}</td>
                                    <td>Id: {{ $booking->user_id }} - {{ $booking->user->first_name ?? 'N/A' }}</td>
                                    <td>Id: {{ $booking->vehicle_id }} - {{ $booking->vehicle->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                    </td>
                                    <td>
                                        @php
                                            $bStatus = match ($booking->status) {
                                                'อนุมัติแล้ว' => 'text-green-600',
                                                'รออนุมัติ' => 'text-orange-600',
                                                'ไม่อนุมัติ', 'ยกเลิก' => 'text-red-600',
                                                default => 'text-slate-600'
                                            };
                                        @endphp
                                        <span class="{{ $bStatus }} font-medium">{{ $booking->status }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-xs {{ $booking->return_status === 'ส่งคืนแล้ว' ? 'text-blue-600' : 'text-slate-500' }}">
                                            {{ $booking->return_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Inspections -->
        <div id="tab-inspections" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <i class="fa-solid fa-wrench text-orange-500 text-lg"></i>
                    <h3 class="font-bold text-slate-700">ตาราง vehicle_inspections</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-[12px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white"># ID</th>
                                <th class="bg-white">รถ (vehicle_id)</th>
                                <th class="bg-white">วันที่ตรวจ (inspection_date)</th>
                                <th class="bg-white">เลขไมล์ (mileage)</th>
                                <th class="bg-white">ผู้ตรวจเช็ค (inspector_name)</th>
                                <th class="bg-white">สถานะ (status)</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($inspections as $insp)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td>{{ $insp->inspection_id }}</td>
                                    <td>Id: {{ $insp->vehicle_id }} - {{ $insp->vehicle->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($insp->inspection_date)->format('d/m/Y') }}</td>
                                    <td>{{ number_format((float) $insp->mileage) }} กม.</td>
                                    <td>{{ $insp->inspector_name ?? '-' }}</td>
                                    <td>
                                        @if($insp->status == 0)
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-green-100 text-green-700">ดำเนินการเสร็จสิ้น</span>
                                        @else
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-orange-100 text-orange-700">รอดำเนินการ</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function showTab(tabId) {
            // Hide all panes
            document.querySelectorAll('.tab-pane').forEach(el => {
                el.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(el => {
                el.classList.remove('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm');
            });

            // Show selected pane
            document.getElementById(tabId).classList.remove('hidden');

            // Add active styling to selected tab
            const activeTabStr = tabId.replace('tab-', 'btn-');
            const activeTab = document.getElementById(activeTabStr);
            if (activeTab) {
                activeTab.classList.add('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm');
            }
        }

        // Initialize first tab
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('btn-vehicles');
            if (btn) btn.classList.add('bg-white', 'text-slate-800', 'shadow-sm');
        });
    </script>
@endsection