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
            /* slate-300 */
            border-radius: 20px;
            border: 1px solid transparent;
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
            /* slate-400 */
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
    </style>
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">

        <!-- New Premium Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100">
                    <i class="fa-solid fa-car text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-slate-800">จัดการข้อมูลรถส่วนกลาง</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        เพิ่ม ลบ หรือแก้ไขข้อมูลรถยนต์ในระบบจองรถ
                    </p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:flex gap-3 w-full md:w-auto">
                <a href="{{ route('backend.bookingcar.dashboard') }}"
                    class="btn btn-ghost border-slate-200 text-slate-600 hover:bg-slate-100 rounded-2xl px-4 sm:px-6 text-xs sm:text-sm h-10 sm:h-12">
                    <i class="fa-solid fa-chart-line mr-1 sm:mr-2"></i> แดชบอร์ด
                </a>
                <button onclick="add_vehicle_modal.showModal()"
                    class="btn bg-red-600 hover:bg-red-700 text-white border-0 shadow-lg shadow-red-200 rounded-2xl px-4 sm:px-8 transition-all hover:scale-105 active:scale-95 text-xs sm:text-sm h-10 sm:h-12">
                    <i class="fa-solid fa-plus mr-1 sm:mr-2 text-base sm:text-lg"></i> เพิ่มรถ
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs tabs-boxed bg-slate-100/50 p-1 rounded-2xl mb-8 grid grid-cols-2 sm:inline-flex backdrop-blur-sm border border-slate-200/50"
            role="tablist">
            <a class="tab tab-active font-bold text-[12px] sm:text-[13px] h-10 sm:h-11 px-2 sm:px-8 rounded-xl transition-all gap-2"
                onclick="showTab('tab-vehicles')" id="btn-vehicles">
                <i class="fa-solid fa-car-side"></i> <span>ข้อมูลรถยนต์</span>
            </a>
            <a class="tab font-bold text-[12px] sm:text-[13px] h-10 sm:h-11 px-2 sm:px-8 rounded-xl transition-all gap-2"
                onclick="showTab('tab-inspections')" id="btn-inspections">
                <i class="fa-solid fa-microscope"></i> <span>ตรวจเช็คสภาพ</span>
            </a>
        </div>

        <!-- Validation Error Feedback -->
        @if ($errors->any())
            <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 shadow-sm animate-shake">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600 shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-800 text-sm">พบข้อผิดพลาดในการบันทึกข้อมูล:</h4>
                    <ul class="mt-1 text-xs text-red-600 list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <!-- Tab 1: Vehicles -->
        <div id="tab-vehicles" class="tab-pane active space-y-4">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-list-ul text-red-500 text-lg"></i>
                        <h3 class="font-bold text-slate-700 text-lg">รายชื่อรถทั้งหมดในระบบ</h3>
                    </div>
                    <span
                        class="bg-slate-50 text-slate-500 px-4 py-1.5 rounded-full text-xs font-bold border border-slate-100">
                        {{ $vehicles->count() }} คัน
                    </span>
                </div>

                <!-- Mobile Card View -->
                <div class="grid grid-cols-1 gap-4 p-4 sm:hidden">
                    @forelse($vehicles as $vehicle)
                        <div class="bg-white rounded-[2rem] p-4 border border-slate-100 shadow-sm relative group active:scale-[0.98] transition-all cursor-pointer"
                            data-vehicle="{{ $vehicle->toJson() }}" onclick="viewVehicleDetailsFromDataset(this)">
                            <div class="flex gap-4">
                                <div
                                    class="w-20 h-20 rounded-2xl overflow-hidden border border-slate-100 bg-slate-50 shrink-0 shadow-inner">
                                    @php
                                        $images = json_decode($vehicle->images, true);
                                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                    @endphp
                                    @if($firstImage)
                                        <img src="{{ asset('images/vehicle/' . $firstImage) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-200">
                                            <i class="fa-solid fa-car text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-black text-slate-800 text-[15px] truncate">{{ $vehicle->name }}</h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                {{ $vehicle->brand }} {{ $vehicle->model_name }}
                                            </p>
                                        </div>
                                        @if($vehicle->status === 'available')
                                            <span
                                                class="bg-green-50 text-green-600 text-[9px] font-black px-2 py-1 rounded-full border border-green-100">ว่าง</span>
                                        @else
                                            <span
                                                class="bg-orange-50 text-orange-600 text-[9px] font-black px-2 py-1 rounded-full border border-orange-100">ไม่ว่าง</span>
                                        @endif
                                    </div>
                                    <div class="flex items-end justify-between">
                                        <div class="flex flex-col">
                                            <span class="text-[8px] text-slate-300 font-bold uppercase tracking-tighter">Current
                                                Mileage</span>
                                            <span
                                                class="text-blue-600 font-black text-[13px] leading-tight">{{ $vehicle->latest_mileage ? number_format($vehicle->latest_mileage) : '0' }}
                                                <span class="text-[9px] font-bold text-slate-400">KM</span></span>
                                        </div>
                                        <div class="flex gap-1.5">
                                            <button data-vehicle="{{ $vehicle->toJson() }}"
                                                onclick="event.stopPropagation(); openEditVehicleModalFromDataset(this)"
                                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
                                                <i class="fa-solid fa-edit text-xs"></i>
                                            </button>
                                            <button
                                                onclick="event.stopPropagation(); confirmDelete({{ $vehicle->vehicle_id }}, '{{ $vehicle->name }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-600 border border-red-100">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center flex flex-col items-center gap-3">
                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                <i class="fa-solid fa-car-side text-3xl"></i>
                            </div>
                            <span class="text-slate-400 font-medium text-sm">ไม่พบข้อมูลรถยนต์ในระบบ</span>
                        </div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="table w-full">
                        <thead
                            class="bg-slate-50/50 text-slate-400 font-bold text-[11px] uppercase tracking-widest border-b border-slate-100">
                            <tr>
                                <th class="py-5 pl-8 bg-transparent">รูปภาพ</th>
                                <th class="py-5 bg-transparent text-center">ทะเบียน / ชื่อเรียก</th>
                                <th class="py-5 bg-transparent">ยี่ห้อ & รุ่น</th>
                                <th class="py-5 bg-transparent text-center">ประเภท</th>
                                <th class="py-5 bg-transparent text-center">เลขไมล์ล่าสุด (กม.)</th>
                                <th class="py-5 bg-transparent text-center">สถานะ</th>
                                <th class="py-5 pr-8 bg-transparent text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-600">
                            @forelse($vehicles as $vehicle)
                                <tr class="hover:bg-slate-50/50 transition-colors group cursor-pointer"
                                    data-vehicle="{{ $vehicle->toJson() }}" onclick="viewVehicleDetailsFromDataset(this)">
                                    <td class="py-6 pl-8">
                                        <div
                                            class="w-20 h-14 rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-slate-50 flex items-center justify-center">
                                            @php
                                                $images = json_decode($vehicle->images, true);
                                                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                            @endphp
                                            @if($firstImage)
                                                <img src="{{ asset('images/vehicle/' . $firstImage) }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <i class="fa-solid fa-car text-slate-200 text-2xl"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <div class="flex flex-col items-center">
                                            <span class="font-black text-slate-800 text-[16px]">{{ $vehicle->name }}</span>
                                            <span class="text-[11px] font-bold text-slate-400 mt-0.5">ID:
                                                {{ $vehicle->vehicle_id }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-slate-700 text-[14px] uppercase tracking-wide">{{ $vehicle->brand ?? '-' }}</span>
                                            <span
                                                class="text-[12px] text-slate-400 font-medium">{{ $vehicle->model_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 text-center">
                                        <span
                                            class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            {{ $vehicle->type ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-6 text-center">
                                        <span
                                            class="text-[14px] font-black {{ $vehicle->latest_mileage > 0 ? 'text-blue-600' : 'text-slate-300' }}">
                                            {{ $vehicle->latest_mileage ? number_format($vehicle->latest_mileage) : '0' }}
                                        </span>
                                    </td>
                                    <td class="py-6 text-center">
                                        @if($vehicle->status === 'available')
                                            <span
                                                class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-green-50 text-green-600 border border-green-100 ring-4 ring-green-50/50">
                                                ว่าง
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                                ไม่ว่าง
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-6 pr-8 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button data-vehicle="{{ $vehicle->toJson() }}"
                                                onclick="event.stopPropagation(); openEditVehicleModalFromDataset(this)"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm border border-blue-100">
                                                <i class="fa-solid fa-edit text-sm"></i>
                                            </button>
                                            <button
                                                onclick="event.stopPropagation(); confirmDelete({{ $vehicle->vehicle_id }}, '{{ $vehicle->name }}')"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm border border-red-100">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div
                                                class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                                <i class="fa-solid fa-car-side text-3xl"></i>
                                            </div>
                                            <span class="text-slate-400 font-medium">ไม่พบข้อมูลรถยนต์ในระบบ</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Inspections -->
        <div id="tab-inspections" class="tab-pane hidden space-y-8">
            <!-- Maintenance Summary Grid -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 shadow-inner">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 text-lg">Maintenance Dashboard</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">ติดตามสภาพรถตามระยะทาง</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($vehicles as $v)
                        @php
                            $latestInsp = $v->latestInspection();
                            $lastMaintMile = $latestInsp ? (int) $latestInsp->mileage : 0;
                            $goalMileage = $latestInsp ? (int) ($latestInsp->next_mileage ?? ($lastMaintMile + 10000)) : (int) ($v->latest_mileage + 10000);

                            $diff = $goalMileage - $lastMaintMile;
                            $currentDist = (int) $v->latest_mileage - $lastMaintMile;
                            $progress = $diff > 0 ? min(100, max(0, ($currentDist / $diff) * 100)) : 0;

                            $isOverdue = (int) $v->latest_mileage >= $goalMileage;
                            $isWarning = (int) $v->latest_mileage >= ($goalMileage - 1000) && !$isOverdue;
                        @endphp
                        <div
                            class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 hover:shadow-md transition-all group overflow-hidden relative">
                            @if($isOverdue)
                                <div class="absolute -top-1 -right-1 w-20 h-20 bg-red-500/10 rounded-full blur-2xl"></div>
                            @endif

                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-black text-slate-800 text-lg group-hover:text-orange-600 transition-colors">
                                        {{ $v->name }}
                                    </h4>
                                    <p class="text-xs text-slate-400 font-medium">{{ $v->brand }} {{ $v->model_name }}</p>
                                </div>
                                <div class="text-right">
                                    @if($isOverdue)
                                        <span
                                            class="badge badge-error text-white font-black text-[10px] py-3 px-3 animate-pulse">OVERDUE</span>
                                    @elseif($isWarning)
                                        <span class="badge badge-warning text-white font-black text-[10px] py-3 px-3">DUE
                                            SOON</span>
                                    @else
                                        <span class="badge badge-success text-white font-black text-[10px] py-3 px-3">HEALTHY</span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Current Mileage</p>
                                        <p class="text-xl font-black text-slate-800">{{ number_format($v->latest_mileage) }}
                                            <span class="text-xs font-medium text-slate-400">km</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Goal (Maintenance)</p>
                                        <p class="text-sm font-bold text-slate-600">{{ number_format($goalMileage) }} km</p>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <progress
                                        class="progress {{ $isOverdue ? 'progress-error' : ($isWarning ? 'progress-warning' : 'progress-success') }} w-full h-3 rounded-full"
                                        value="{{ min($progress, 100) }}" max="100"></progress>
                                    <div class="flex justify-between text-[10px] font-bold">
                                        <span
                                            class="{{ $isOverdue ? 'text-red-500' : 'text-slate-400' }}">{{ number_format($progress, 1) }}%
                                            Complete</span>
                                        <span class="text-slate-400">Next Service In:
                                            {{ number_format(max(0, $goalMileage - $v->latest_mileage)) }} km</span>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button data-vehicle="{{ $v->toJson() }}" onclick="viewVehicleDetailsFromDataset(this)"
                                        class="flex-1 btn btn-ghost btn-sm rounded-xl text-slate-400 hover:text-orange-600 hover:bg-orange-50 border border-transparent hover:border-orange-100 transition-all gap-2">
                                        <i class="fa-solid fa-circle-info text-xs"></i> Details
                                    </button>
                                    <button data-vehicle="{{ $v->toJson() }}" onclick="openInspectionModalFromDataset(this)"
                                        class="flex-1 btn bg-orange-500 hover:bg-orange-600 text-white btn-sm rounded-xl border-0 shadow-sm shadow-orange-100 gap-2">
                                        <i class="fa-solid fa-plus text-xs"></i> Check
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Historical Log Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clock-rotate-left text-slate-400 text-lg"></i>
                        <h3 class="font-bold text-slate-700 uppercase tracking-widest text-xs">Inspection History Log</h3>
                    </div>
                </div>
                <!-- Mobile Inspection Cards -->
                <div class="grid grid-cols-1 gap-4 p-4 sm:hidden">
                    @forelse($inspections as $insp)
                        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm relative group">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Date</span>
                                    <span
                                        class="text-sm font-black text-slate-800">{{ \Carbon\Carbon::parse($insp->inspection_date)->format('d/m/Y') }}</span>
                                </div>
                                @if($insp->status == 0)
                                    <span
                                        class="bg-green-50 text-green-600 text-[9px] font-black px-2 py-1 rounded-full border border-green-100">NORMAL</span>
                                @else
                                    <span
                                        class="bg-red-50 text-red-600 text-[9px] font-black px-2 py-1 rounded-full border border-red-100">ISSUE</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Mileage</span>
                                    <span class="text-orange-600 font-black text-sm">{{ number_format((float) $insp->mileage) }}
                                        KM</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Vehicle</span>
                                    <span class="text-slate-700 font-bold text-xs truncate">ID: {{ $insp->vehicle_id }} -
                                        {{ $insp->vehicle->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between border-t border-slate-50 pt-3">
                                <div class="flex gap-2 text-slate-400">
                                    @if($insp->file_vehicle)
                                        <a href="{{ asset('uploads/vehicl_file_maintenance/' . $insp->file_vehicle) }}"
                                            target="_blank" class="text-blue-500 hover:text-blue-700">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button data-inspection="{{ json_encode($insp) }}"
                                        onclick="openEditInspectionModalFromDataset(this)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 text-orange-600 border border-orange-100">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <form action="{{ route('backend.bookingcar.inspections.destroy', $insp->inspection_id) }}"
                                        method="POST" onsubmit="return confirm('ยืนยันการลบ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 border border-red-100">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400">ไม่พบกขรตรวจเช็ค</div>
                    @endforelse
                </div>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="table w-full text-[12px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white"># ID</th>
                                <th class="bg-white">รถ (vehicle_id)</th>
                                <th class="bg-white">วันที่ตรวจ (inspection_date)</th>
                                <th class="bg-white">สถานที่ (location)</th>
                                <th class="bg-white">เลขไมล์ (mileage)</th>
                                <th class="bg-white">เป้าหมายถัดไป (next_mileage)</th>
                                <th class="bg-white">ผู้ตรวจเช็ค (inspector_name)</th>
                                <th class="bg-white">เอกสาร (file)</th>
                                <th class="bg-white">สถานะ (status)</th>
                                <th class="bg-white text-center">จัดการ (Actions)</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($inspections as $insp)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td>{{ $insp->inspection_id }}</td>
                                    <td>Id: {{ $insp->vehicle_id }} - {{ $insp->vehicle->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($insp->inspection_date)->format('d/m/Y') }}</td>
                                    <td>{{ $insp->location ?? '-' }}</td>
                                    <td class="font-bold text-orange-600">{{ number_format((float) $insp->mileage) }} กม.</td>
                                    <td class="text-blue-600 font-medium italic">
                                        {{ $insp->next_mileage ? number_format((float) $insp->next_mileage) . ' กม.' : '-' }}
                                    </td>
                                    <td>{{ $insp->inspector_name ?? '-' }}</td>
                                    <td>
                                        @if($insp->file_vehicle)
                                            <a href="{{ asset('uploads/vehicl_file_maintenance/' . $insp->file_vehicle) }}"
                                                target="_blank"
                                                class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50 gap-1 rounded-lg">
                                                <i class="fa-solid fa-file-arrow-down text-[10px]"></i>
                                                เปิดดู
                                            </a>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($insp->status == 0)
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-green-100 text-green-700 leading-none inline-flex items-center gap-1">
                                                <i class="fa-solid fa-check-circle text-[8px]"></i> ปกติ
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-red-100 text-red-700 leading-none inline-flex items-center gap-1">
                                                <i class="fa-solid fa-triangle-exclamation text-[8px]"></i> ไม่ปกติ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button data-inspection="{{ json_encode($insp) }}"
                                                onclick="openEditInspectionModalFromDataset(this)"
                                                class="btn btn-ghost btn-xs text-orange-600 hover:bg-orange-50 rounded-lg">
                                                <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                            </button>
                                            <form
                                                action="{{ route('backend.bookingcar.inspections.destroy', $insp->inspection_id) }}"
                                                method="POST" onsubmit="return confirm('ยืนยันการลบข้อมูลการตรวจเช็คนี้?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-ghost btn-xs text-red-500 hover:bg-red-50 rounded-lg">
                                                    <i class="fa-solid fa-trash-can"></i> ลบ
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Vehicle Modal -->
    <dialog id="add_vehicle_modal" class="modal">
        <div
            class="modal-box bg-white rounded-[2rem] border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-5xl w-11/12 max-h-[90vh] overflow-y-auto">
            <div class="px-8 py-6 bg-red-50 border-b border-red-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 shadow-inner">
                        <i class="fa-solid fa-car-side text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-slate-800 tracking-tight">เพิ่มข้อมูลรถยนต์ใหม่</h3>
                        <p class="text-xs text-red-600 font-bold uppercase tracking-widest">New Fleet Entry</p>
                    </div>
                </div>
                <form method="dialog">
                    <button class="btn btn-circle btn-ghost btn-sm text-slate-400">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </form>
            </div>

            <form action="{{ route('backend.bookingcar.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Side: Basic Info -->
                    <div class="space-y-5">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">ชื่อรถ / ข้อมูลทะเบียน <span
                                        class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="name" placeholder="เช่น Toyota Camry (กข 1234)" required
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ยี่ห้อ (Brand)</span>
                                </label>
                                <input type="text" name="brand" placeholder="เช่น Toyota"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รุ่น (Model)</span>
                                </label>
                                <input type="text" name="model_name" placeholder="เช่น Camry"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ปีที่ผลิต (Year)</span>
                                </label>
                                <input type="text" name="year" placeholder="เช่น 2023"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทรถ</span>
                                </label>
                                <select name="type" required
                                    class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                                    <option value="" disabled selected>เลือกประเภท</option>
                                    <option value="เก๋ง">เก๋ง</option>
                                    <option value="กระบะ">กระบะ</option>
                                    <option value="รถตู้">รถตู้</option>
                                    <option value="SUV">SUV</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Secondary Info & Image -->
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">จำนวนที่นั่ง</span>
                                </label>
                                <input type="number" name="seat" placeholder="เช่น 4"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทการใช้งาน <span
                                            class="text-red-500">*</span></span>
                                </label>
                                <select name="status_vehicles" required
                                    class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                                    <option value="1" selected>รถทั่วไป</option>
                                    <option value="0">รอเจ้านาย</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทน้ำมัน</span>
                                </label>
                                <input type="text" name="filling_type" placeholder="เช่น ดีเซล"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ปริมาณเติมน้ำมัน (ลิตร)</span>
                                </label>
                                <input type="text" name="filling_volume" placeholder="เช่น 50"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">เลขไมล์ล่าสุด (กม.)</span>
                            </label>
                            <input type="number" name="latest_mileage" placeholder="เช่น 50000"
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">รูปภาพรถ</span>
                            </label>
                            <input type="file" name="image"
                                class="file-input file-input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 file:bg-slate-200 file:text-slate-700 file:border-0 hover:file:bg-slate-300 transition-all">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">รายละเอียดเพิ่มเติม</span>
                            </label>
                            <textarea name="desciption" rows="2"
                                class="textarea textarea-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium"
                                placeholder="เช่น สีขาว, ประกันภัยชั้น 1..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" onclick="add_vehicle_modal.close()"
                        class="btn btn-ghost rounded-2xl px-6 font-bold text-slate-500">ยกเลิก</button>
                    <button type="submit"
                        class="btn bg-red-600 hover:bg-red-700 text-white border-0 shadow-lg shadow-red-200 rounded-2xl px-10 transition-all">
                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <!-- Edit Vehicle Modal -->
    <dialog id="edit_vehicle_modal" class="modal">
        <div
            class="modal-box bg-white rounded-[2rem] border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-5xl w-11/12 max-h-[90vh] overflow-y-auto">
            <div class="px-8 py-6 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="fa-solid fa-edit text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-slate-800 tracking-tight">แก้ไขข้อมูลรถยนต์</h3>
                        <p id="edit_vehicle_display_name" class="text-xs text-blue-600 font-bold uppercase tracking-widest">
                            Update
                            Vehicle Details</p>
                    </div>
                </div>
                <form method="dialog">
                    <button class="btn btn-circle btn-ghost btn-sm text-slate-400">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </form>
            </div>

            <form id="edit_vehicle_form" action="" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Side: Basic Info -->
                    <div class="space-y-5">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">ชื่อรถ / ข้อมูลทะเบียน <span
                                        class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="name" id="edit_v_name" placeholder="เช่น Toyota Camry (กข 1234)"
                                required
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ยี่ห้อ (Brand)</span>
                                </label>
                                <input type="text" name="brand" id="edit_v_brand" placeholder="เช่น Toyota"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รุ่น (Model)</span>
                                </label>
                                <input type="text" name="model_name" id="edit_v_model" placeholder="เช่น Camry"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ปีที่ผลิต (Year)</span>
                                </label>
                                <input type="text" name="year" id="edit_v_year" placeholder="เช่น 2023"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทรถ</span>
                                </label>
                                <select name="type" id="edit_v_type" required
                                    class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                                    <option value="" disabled>เลือกประเภท</option>
                                    <option value="เก๋ง">เก๋ง</option>
                                    <option value="กระบะ">กระบะ</option>
                                    <option value="รถตู้">รถตู้</option>
                                    <option value="SUV">SUV</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Secondary Info & Image -->
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">จำนวนที่นั่ง</span>
                                </label>
                                <input type="number" name="seat" id="edit_v_seat" placeholder="เช่น 4"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทการใช้งาน <span
                                            class="text-red-500">*</span></span>
                                </label>
                                <select name="status_vehicles" id="edit_v_status_v" required
                                    class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                                    <option value="1">รถทั่วไป</option>
                                    <option value="0">รอเจ้านาย</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทน้ำมัน</span>
                                </label>
                                <input type="text" name="filling_type" id="edit_v_fuel" placeholder="เช่น ดีเซล"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ปริมาณเติมน้ำมัน (ลิตร)</span>
                                </label>
                                <input type="text" name="filling_volume" id="edit_v_volume" placeholder="เช่น 50"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">เลขไมล์ล่าสุด (กม.)</span>
                            </label>
                            <input type="number" name="latest_mileage" id="edit_v_mileage" placeholder="เช่น 50000"
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รูปภาพรถ</span>
                                </label>
                                <input type="file" name="image" onchange="previewEditImage(this)"
                                    class="file-input file-input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 file:bg-slate-200 file:text-slate-700 file:border-0 hover:file:bg-slate-300 transition-all text-xs">
                            </div>
                            <div
                                class="h-20 w-full rounded-2xl border border-slate-100 bg-slate-50 overflow-hidden relative group">
                                <img id="edit_v_preview" src="" class="w-full h-full object-cover hidden">
                                <div id="edit_v_no_preview"
                                    class="w-full h-full flex items-center justify-center text-slate-200">
                                    <i class="fa-solid fa-image text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">รายละเอียดเพิ่มเติม</span>
                            </label>
                            <textarea name="desciption" id="edit_v_desc" rows="2"
                                class="textarea textarea-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium"
                                placeholder="เช่น สีขาว, ประกันภัยชั้น 1..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <button type="button" onclick="edit_vehicle_modal.close()"
                        class="btn btn-ghost rounded-2xl px-6 font-bold text-slate-500">ยกเลิก</button>
                    <button type="submit"
                        class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 shadow-lg shadow-blue-200 rounded-2xl px-10 transition-all">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete_modal" class="modal">
        <div class="modal-box bg-white rounded-3xl border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-sm">
            <div class="p-8 text-center">
                <div
                    class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-600 mx-auto mb-4 border border-red-100 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="font-black text-xl text-slate-800 mb-2">ยืนยันการลบข้อมูล?</h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">
                    คุณกำลังจะลบข้อมูลรถ <span id="delete_vehicle_name"
                        class="text-red-600 font-bold underline decoration-red-200"></span>
                    ออกจากระบบอย่างถาวร ไม่สามารถย้อนกลับได้
                </p>
            </div>
            <div class="bg-slate-50 p-6 flex gap-3">
                <form method="dialog" class="flex-1">
                    <button class="btn btn-ghost w-full rounded-2xl font-bold text-slate-500">ยกเลิก</button>
                </form>
                <form id="delete_form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn bg-red-600 hover:bg-red-700 text-white border-0 w-full rounded-2xl shadow-md">
                        ยืนยันการลบ
                    </button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <!-- View Vehicle Details Modal -->
    <dialog id="view_vehicle_modal" class="modal">
        <div
            class="modal-box bg-white rounded-[2rem] border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-5xl w-11/12 max-h-[85vh] overflow-y-auto animate-zoomIn">
            <div class="relative h-48 bg-slate-100 overflow-hidden">
                <img id="view_vehicle_banner" src="" alt="Vehicle" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>
                <div class="absolute bottom-6 left-8 text-white">
                    <h2 id="view_vehicle_title" class="text-3xl font-black mb-1 tracking-tight"></h2>
                    <p id="view_vehicle_subtitle" class="text-slate-300 font-medium text-sm"></p>
                </div>
                <form method="dialog">
                    <button
                        class="btn btn-circle btn-sm absolute top-4 right-4 bg-white/20 hover:bg-white/40 border-0 text-white backdrop-blur-md">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </form>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-3">ข้อมูลทางเทคนิค
                            (SPECIFICATIONS)</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">ประเภท</p>
                                <p id="view_vehicle_type" class="font-bold text-slate-700 text-sm"></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">จำนวนที่นั่ง
                                </p>
                                <p class="font-bold text-slate-700 text-sm"><span id="view_vehicle_seat"></span> ที่นั่ง</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">ประเภทน้ำมัน
                                </p>
                                <p id="view_vehicle_fuel" class="font-bold text-slate-700 text-sm"></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">ปีที่ผลิต</p>
                                <p id="view_vehicle_year" class="font-bold text-slate-700 text-sm"></p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 col-span-2 shadow-sm">
                                <p class="text-[9px] text-slate-400 font-bold mb-0.5 uppercase tracking-wider">
                                    เลขไมล์ปัจจุบัน</p>
                                <p class="font-black text-red-600 text-lg"><span id="view_vehicle_mileage"></span> กม.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mb-3">รายละเอียดเพิ่มเติม
                        </h4>
                        <p id="view_vehicle_desc"
                            class="text-slate-600 leading-relaxed text-xs bg-slate-50/30 p-4 rounded-2xl border border-dashed border-slate-200 min-h-[60px]">
                        </p>
                    </div>

                    <!-- Maintenance History Section -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-3 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                            <h4 class="text-slate-700 font-bold text-sm flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-500 text-xs"></i> ประวัติการตรวจเช็ค
                            </h4>
                        </div>
                        <div class="max-h-[250px] overflow-y-auto">
                            <table class="table table-compact w-full text-[11px]">
                                <thead class="sticky top-0 bg-white/95 backdrop-blur-md z-10 border-b border-slate-50">
                                    <tr class="text-slate-400 font-bold text-[9px] uppercase tracking-wider">
                                        <th class="py-3 pl-6">วันที่</th>
                                        <th class="py-3">สถานที่</th>
                                        <th class="py-3">เลขไมล์</th>
                                        <th class="py-3">เป้าหมายถัดไป</th>
                                        <th class="py-3 text-center">สถานะ</th>
                                        <th class="py-3">ผู้ตรวจ</th>
                                        <th class="py-3 text-center">เอกสาร</th>
                                        <th class="py-3 pr-6 text-right">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody id="view_vehicle_inspections_body" class="divide-y divide-slate-50">
                                    <!-- Dynamic Content -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Maintenance Progress Card (Dark Premium) -->
                    <div
                        class="bg-slate-900 rounded-[2rem] p-6 text-white shadow-xl relative overflow-hidden group border border-slate-800">
                        <div
                            class="absolute -right-4 -top-4 w-24 h-24 bg-red-600/10 rounded-full blur-3xl group-hover:bg-red-600/20 transition-all duration-700">
                        </div>

                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1.5">Progress
                                </p>
                                <h4 class="text-2xl font-black" id="modal_progress_percent">--%</h4>
                            </div>
                            <div id="modal_status_badge"
                                class="badge badge-success text-white font-black text-[9px] py-3 px-3 rounded-lg">HEALTHY
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500 italic uppercase">Current</span>
                                <span id="modal_current_mileage" class="text-white text-xs">0 km</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-2.5 p-0.5 shadow-inner">
                                <div id="modal_progress_bar"
                                    class="bg-gradient-to-r from-green-400 to-emerald-500 h-full rounded-full transition-all duration-1000 shadow-lg shadow-emerald-500/40"
                                    style="width: 0%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-bold">
                                <span class="text-slate-500 italic uppercase">Goal</span>
                                <span id="modal_goal_mileage" class="text-slate-400 text-xs">0 km</span>
                            </div>
                        </div>

                        <div class="bg-slate-800/50 rounded-2xl p-4 border border-slate-800">
                            <p
                                class="text-[9px] font-bold text-slate-500 uppercase mb-2 flex items-center gap-2 tracking-widest">
                                <i class="fa-solid fa-microscope text-blue-400"></i> Next Service In
                            </p>
                            <div class="flex items-baseline gap-1">
                                <span id="modal_remaining_km" class="text-xl font-black text-blue-400">0</span>
                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">KM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Buttons Card -->
                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-lg p-6 overflow-hidden relative">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-slate-800 font-bold text-sm">สถานะการใช้งาน</h4>
                            <span id="view_vehicle_status_badge"
                                class="badge font-black px-3 py-3 rounded-lg text-[10px]"></span>
                        </div>
                        <div class="divider opacity-50 mb-4"></div>
                        <div class="space-y-3">
                            <a href="{{ route('bookingcar.dashboard') }}"
                                class="btn btn-ghost btn-block justify-start gap-3 rounded-xl text-slate-600 hover:bg-slate-50 font-bold h-10 min-h-0 text-xs">
                                <i class="fa-solid fa-calendar-days text-purple-500 text-sm"></i> ดูประวัติการจอง
                            </a>
                            <button
                                onclick="document.getElementById('view_vehicle_modal').close(); showTab('tab-inspections')"
                                class="btn btn-ghost btn-block justify-start gap-3 rounded-xl text-slate-600 hover:bg-slate-50 font-bold h-10 min-h-0 text-xs">
                                <i class="fa-solid fa-wrench text-orange-500 text-sm"></i> ตรวจเช็คสภาพ
                            </button>
                            <a id="view_vehicle_edit_link" href="#"
                                class="btn btn-primary btn-block rounded-xl shadow-md shadow-blue-100 mt-4 font-bold h-11 min-h-0 text-xs transition-transform hover:scale-105 active:scale-95">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> แก้ไขข้อมูลรถ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/60 backdrop-blur-md">
            <button>close</button>
        </form>
    </dialog>
    <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
        <button>close</button>
    </form>
    </dialog>

    <!-- Quick Inspection Modal -->
    <dialog id="inspection_modal" class="modal">
        <div
            class="modal-box bg-white rounded-[2rem] border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-4xl max-h-[95vh] overflow-y-auto">
            <!-- Mockup Header Style -->
            <div class="px-8 py-4 bg-blue-50/80 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                        <i class="fa-solid fa-plus text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg text-slate-800 tracking-tight">เพิ่มการตรวจเช็ค</h3>
                        <p id="insp_vehicle_name" class="text-[10px] text-red-600 font-bold uppercase tracking-widest"></p>
                    </div>
                </div>
                <form method="dialog">
                    <button
                        class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-200 hover:bg-red-700 transition-all">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </form>
            </div>

            <form action="{{ route('backend.bookingcar.inspections.store') }}" method="POST" enctype="multipart/form-data"
                class="p-8">
                @csrf
                <input type="hidden" name="vehicle_id" id="insp_vehicle_id">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-6 mb-8">
                    <!-- Row 1 -->
                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">วันที่ตรวจเช็ค</span>
                        </label>
                        <input type="date" name="inspection_date" required value="{{ date('Y-m-d') }}"
                            class="input input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium h-11 text-sm">
                    </div>

                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">เลขไมล์ขณะตรวจเช็ค
                                (กิโลเมตร)</span>
                        </label>
                        <input type="number" name="mileage" id="insp_current_mileage" required
                            placeholder="กรุณากรอกเลขไมล์"
                            class="input input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-bold text-slate-700 h-11 text-sm">
                    </div>

                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">ชื่อผู้ตรวจเช็ค</span>
                        </label>
                        <input type="text" name="inspector_name" required value="{{ Auth::user()->fullname }}"
                            placeholder="กรุณากรอกชื่อผู้ตรวจเช็ค"
                            class="input input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium h-11 text-sm">
                    </div>

                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">สถานที่ไปตรวจ</span>
                        </label>
                        <input type="text" name="location" placeholder="เช่น ศูนย์บริการ TOYOTA"
                            class="input input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium h-11 text-sm">
                    </div>

                    <!-- Row 2 -->
                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">เลขไมล์ครั้งถัดไป</span>
                        </label>
                        <input type="number" name="next_mileage" id="insp_next_mileage" required placeholder="เช่น 50000"
                            class="input input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium h-11 text-sm">
                    </div>

                    <div class="form-control md:col-span-2">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">แนบไฟล์เอกสารรถ</span>
                        </label>
                        <input type="file" name="file_vehicle"
                            class="file-input file-input-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium h-11 text-[11px] file:bg-slate-100 file:border-0 file:text-[10px] file:font-bold">
                    </div>

                    <div class="form-control">
                        <label class="flex items-center gap-2 mb-2">

                            <span class="text-xs font-bold text-slate-700 uppercase tracking-tight">สถานะสภาพรถ</span>
                        </label>
                        <select name="status"
                            class="select select-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-bold text-slate-700 h-11 min-h-0 text-sm">
                            <option value="0">ปกติ (พร้อมใช้งาน)</option>
                            <option value="1">ต้องซ่อมบำรุง (งดใช้งานชั่วคราว)</option>
                        </select>
                    </div>

                    <!-- Row 3 -->
                    <div class="form-control md:col-span-4">
                        <label class="flex items-center gap-2 mb-2">

                            <span
                                class="text-xs font-bold text-slate-700 uppercase tracking-tight">รายละเอียดการตรวจเช็ค</span>
                        </label>
                        <textarea name="description" rows="3" placeholder="กรุณากรอกรายละเอียดการตรวจเช็ค"
                            class="textarea textarea-bordered w-full rounded-xl bg-white border-slate-200 focus:border-red-500 transition-all font-medium text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-center pt-4 border-t border-slate-50">
                    <button type="submit"
                        class="btn bg-emerald-600 hover:bg-emerald-700 text-white border-0 shadow-xl shadow-emerald-200 rounded-xl px-12 h-12 min-h-0 transition-all hover:scale-105 active:scale-95 font-black text-sm">
                        <i class="fa-solid fa-check mr-2"></i> บันทึกการตรวจเช็ค
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <!-- Edit Inspection Modal -->
    <dialog id="edit_inspection_modal" class="modal">
        <div class="modal-box bg-white rounded-3xl border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-lg">
            <div class="px-8 py-6 bg-blue-50 border-b border-blue-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-inner">
                        <i class="fa-solid fa-wrench text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-slate-800">แก้ไขข้อมูลประวัติการตรวจเช็ค</h3>
                        <p id="edit_insp_vehicle_name" class="text-xs text-blue-600 font-bold uppercase tracking-wider"></p>
                    </div>
                </div>
                <form method="dialog">
                    <button class="btn btn-circle btn-ghost btn-sm text-slate-400">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </form>
            </div>

            <form id="edit_inspection_form" action="" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-5">
                    <div class="form-control col-span-2">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">วันที่ตรวจเช็ค</span>
                        </label>
                        <input type="date" name="inspection_date" id="edit_insp_date" required
                            class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                    </div>

                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">เลขไมล์ปัจจุบัน</span>
                        </label>
                        <input type="number" name="mileage" id="edit_insp_mileage" required
                            class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-bold text-blue-600">
                    </div>

                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700 text-[11px] sm:text-xs">เป้าหมายครั้งถัดไป
                                (กม.)</span>
                        </label>
                        <input type="number" name="next_mileage" id="edit_insp_next_mileage" required
                            class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium"
                            placeholder="เช่น 50000">
                    </div>

                    <div class="form-control col-span-2">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">ผู้ตรวจเช็ค</span>
                        </label>
                        <input type="text" name="inspector_name" id="edit_insp_inspector" required
                            class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                    </div>

                    <div class="form-control col-span-2 sm:col-span-1">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">สถานที่ไปตรวจ</span>
                        </label>
                        <input type="text" name="location" id="edit_insp_location" placeholder="เช่น ศูนย์บริการ TOYOTA"
                            class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium">
                    </div>

                    <div class="form-control col-span-2 sm:col-span-1">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">แนบไฟล์เอกสารรถ</span>
                        </label>
                        <input type="file" name="file_vehicle"
                            class="file-input file-input-bordered file-input-info w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium text-xs">
                        <label class="label flex justify-between items-center">
                            <span id="current_file_name"
                                class="label-text-alt text-slate-400 truncate max-w-[150px]">ไฟล์เดิม: None</span>
                            <a id="edit_insp_file_link" href="#" target="_blank"
                                class="hidden text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 bg-blue-50 px-2 py-0.5 rounded-lg transition-all">
                                <i class="fa-solid fa-eye"></i> ดูไฟล์เดิม
                            </a>
                        </label>
                    </div>

                    <div class="form-control col-span-2">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">ผลการตรวจเช็ค / บันทึก</span>
                        </label>
                        <textarea name="description" id="edit_insp_description" rows="2"
                            class="textarea textarea-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-medium"
                            placeholder="เช่น เปลี่ยนน้ำมันเครื่อง..."></textarea>
                    </div>

                    <div class="form-control col-span-2">
                        <label class="label pb-1">
                            <span class="label-text font-bold text-slate-700">สถานะสภาพรถ</span>
                        </label>
                        <select name="status" id="edit_insp_status"
                            class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-blue-500 transition-all font-bold text-slate-700">
                            <option value="0">ปกติ (พร้อมใช้งาน)</option>
                            <option value="1">ต้องซ่อมบำรุง (งดใช้งานชั่วคราว)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="edit_inspection_modal.close()"
                        class="btn btn-ghost rounded-2xl px-6 font-bold text-slate-500">ยกเลิก</button>
                    <button type="submit"
                        class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 shadow-lg shadow-blue-200 rounded-2xl px-10 transition-all">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <script>
        // Helper to handle various image data formats (JSON, string, null)
        function safeParseImages(imagesValue) {
            if (!imagesValue) return [];
            if (Array.isArray(imagesValue)) return imagesValue;
            if (typeof imagesValue !== 'string') return [];

            try {
                const parsed = JSON.parse(imagesValue);
                return Array.isArray(parsed) ? parsed : [parsed];
            } catch (e) {
                // If not valid JSON, it might be a legacy single filename string
                return imagesValue.trim() !== "" ? [imagesValue] : [];
            }
        }

        // Wrapper to safely parse vehicle data from data attribute
        function viewVehicleDetailsFromDataset(el) {
            try {
                const vehicle = JSON.parse(el.dataset.vehicle);
                viewVehicleDetails(vehicle);
            } catch (e) {
                console.error("Error parsing vehicle data:", e);
                alert("เกิดข้อผิดพลาดในการโหลดข้อมูลรถ");
            }
        }

        // Wrapper to safely parse vehicle data from data attribute for editing
        function openEditVehicleModalFromDataset(el) {
            try {
                const vehicle = JSON.parse(el.dataset.vehicle);
                openEditVehicleModal(vehicle);
            } catch (e) {
                console.error("Error parsing vehicle data:", e);
                alert("เกิดข้อผิดพลาดในการโหลดข้อมูลรถ");
            }
        }

        // Wrapper to safely parse vehicle data from data attribute for new inspection
        function openInspectionModalFromDataset(el) {
            try {
                const vehicle = JSON.parse(el.dataset.vehicle);
                openInspectionModal(vehicle);
            } catch (e) {
                console.error("Error parsing vehicle data:", e);
                alert("เกิดข้อผิดพลาดในการโหลดข้อมูลรถ");
            }
        }

        // Wrapper to safely parse inspection data from data attribute for editing
        function openEditInspectionModalFromDataset(el) {
            try {
                const inspection = JSON.parse(el.dataset.inspection);
                openEditInspectionModal(inspection);
            } catch (e) {
                console.error("Error parsing inspection data:", e);
                alert("เกิดข้อผิดพลาดในการโหลดข้อมูลการตรวจเช็ค");
            }
        }

        // Delete inspection from modal
        function deleteInspectionFromModal(inspectionId) {
            if (confirm('ยืนยันการลบข้อมูลการตรวจเช็คนี้?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/backend/bookingcar/inspections/${inspectionId}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('input[name="_token"]').value;

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Open the Edit Vehicle Modal
        function openEditVehicleModal(vehicle) {
            try {
                const form = document.getElementById('edit_vehicle_form');
                form.action = `/backend/bookingcar/${vehicle.vehicle_id}`;

                document.getElementById('edit_vehicle_display_name').innerText = `รถ: ${vehicle.name}`;
                document.getElementById('edit_v_name').value = vehicle.name || '';
                document.getElementById('edit_v_brand').value = vehicle.brand || '';
                document.getElementById('edit_v_model').value = vehicle.model_name || '';
                document.getElementById('edit_v_year').value = vehicle.year || '';
                document.getElementById('edit_v_type').value = vehicle.type || '';
                document.getElementById('edit_v_seat').value = vehicle.seat || '';
                document.getElementById('edit_v_status_v').value = vehicle.status_vehicles !== undefined ? vehicle.status_vehicles : 1;
                document.getElementById('edit_v_fuel').value = vehicle.filling_type || '';
                document.getElementById('edit_v_volume').value = vehicle.filling_volume || '';
                document.getElementById('edit_v_mileage').value = vehicle.latest_mileage || 0;
                document.getElementById('edit_v_desc').value = vehicle.desciption || '';

                // Image Preview Logic
                const preview = document.getElementById('edit_v_preview');
                const noPreview = document.getElementById('edit_v_no_preview');
                const images = safeParseImages(vehicle.images);

                if (images && images.length > 0) {
                    preview.src = `/images/vehicle/${images[0]}`;
                    preview.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                } else {
                    preview.src = '';
                    preview.classList.add('hidden');
                    noPreview.classList.remove('hidden');
                }

                document.getElementById('edit_vehicle_modal').showModal();
            } catch (e) {
                console.error("Error opening edit modal:", e);
                alert("เกิดข้อผิดพลาดในการแสดงแบบฟอร์มแก้ไข");
            }
        }

        // Preview uploaded image immediately
        function previewEditImage(input) {
            const preview = document.getElementById('edit_v_preview');
            const noPreview = document.getElementById('edit_v_no_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    noPreview.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Open the View Vehicle Details Modal
        function viewVehicleDetails(vehicle) {
            try {
                const modal = document.getElementById('view_vehicle_modal');
                if (!modal) return;

                // Populate Text Fields
                const fields = {
                    'view_vehicle_title': vehicle.name || '-',
                    'view_vehicle_subtitle': `${vehicle.brand || '-'} ${vehicle.model_name || ''}`,
                    'view_vehicle_type': vehicle.type || '-',
                    'view_vehicle_seat': vehicle.seat || '-',
                    'view_vehicle_fuel': vehicle.filling_type || '-',
                    'view_vehicle_year': vehicle.year || '-',
                    'view_vehicle_mileage': (vehicle.latest_mileage ? parseInt(vehicle.latest_mileage).toLocaleString() : '0') + ' กม.',
                    'view_vehicle_desc': vehicle.desciption || 'ไม่มีข้อมูลเพิ่มเติม'
                };

                for (let id in fields) {
                    const el = document.getElementById(id);
                    if (el) el.textContent = fields[id];
                }

                // Image Banner
                const images = safeParseImages(vehicle.images);
                const banner = document.getElementById('view_vehicle_banner');
                if (banner) {
                    banner.src = (images && images.length > 0) ? `/images/vehicle/${images[0]}` : 'https://placehold.co/800x400/f8fafc/64748b?text=No+Image';
                }

                // Status Badge
                const statusBadge = document.getElementById('view_vehicle_status_badge');
                if (statusBadge) {
                    statusBadge.textContent = vehicle.status === 'available' ? 'ว่าง' : 'ไม่ว่าง';
                    statusBadge.className = `badge font-bold px-4 py-3 border-0 text-white ${vehicle.status === 'available' ? 'bg-green-500 shadow-lg shadow-green-100' : 'bg-red-500 shadow-lg shadow-red-100'}`;
                }

                // Maintenance History
                const historyBody = document.getElementById('view_vehicle_inspections_body');
                if (historyBody) {
                    historyBody.innerHTML = '';
                    const inspections = vehicle.inspections || [];
                    inspections.sort((a, b) => new Date(b.inspection_date) - new Date(a.inspection_date));

                    if (inspections.length === 0) {
                        historyBody.innerHTML = '<tr><td colspan="5" class="py-12 text-center text-slate-400 italic">ไม่พบประวัติการตรวจเช็ค</td></tr>';
                    } else {
                        inspections.forEach(insp => {
                            const date = insp.inspection_date ? new Date(insp.inspection_date).toLocaleDateString('th-TH') : '-';
                            const statusColor = insp.status == 0 ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100';
                            const statusText = insp.status == 0 ? 'ปกติ' : 'ไม่ปกติ';
                            const fileLink = insp.file_vehicle ?
                                `<a href="/uploads/vehicl_file_maintenance/${insp.file_vehicle}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all"><i class="fa-solid fa-file-pdf"></i></a>` : '-';

                            const nextMileageText = insp.next_mileage ? `${parseInt(insp.next_mileage).toLocaleString()} <span class="text-[9px] text-slate-400 font-medium">กม.</span>` : '-';
                            const locationText = insp.location || '-';

                            // Safely escape the inspection object for the data attribute
                            const inspectionJson = JSON.stringify(insp).replace(/'/g, "&apos;");

                            const tr = document.createElement('tr');
                            tr.className = "hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0";
                            tr.innerHTML = `
                                                                                <td class="py-4 pl-6 font-medium text-slate-500">${date}</td>
                                                                                <td class="py-4 text-slate-600 text-sm">${locationText}</td>
                                                                                <td class="py-4 font-black text-slate-800">${parseInt(insp.mileage || 0).toLocaleString()} <span class="text-[10px] text-slate-400">KM</span></td>
                                                                                <td class="py-4 font-bold text-blue-600">${nextMileageText}</td>
                                                                                <td class="py-4 text-center">
                                                                                    <span class="px-3 py-1 rounded-full text-[10px] font-black border ${statusColor}">${statusText}</span>
                                                                                </td>
                                                                                <td class="py-4 text-slate-500 text-sm whitespace-nowrap">${insp.inspector_name || '-'}</td>
                                                                                <td class="py-4 text-center">
                                                                                    <div class="flex justify-center">${fileLink}</div>
                                                                                </td>
                                                                                <td class="py-4 pr-6 text-right">
                                                                                    <div class="flex items-center justify-end gap-1">
                                                                                        <button 
                                                                                            data-inspection='${inspectionJson}'
                                                                                            onclick="openEditInspectionModalFromDataset(this)"
                                                                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white transition-all shadow-sm">
                                                                                            <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                                                                        </button>
                                                                                        <button 
                                                                                            onclick="deleteInspectionFromModal(${insp.inspection_id})"
                                                                                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                                                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </td>
                                                                            `;
                            historyBody.appendChild(tr);
                        });
                    }

                    // Maintenance Progress Tracker
                    const latestInsp = inspections[0] || null;
                    const lastMaintMile = latestInsp ? parseInt(latestInsp.mileage || 0) : 0;
                    const currentMile = parseInt(vehicle.latest_mileage || 0);
                    const goalMile = latestInsp ? parseInt(latestInsp.next_mileage || (lastMaintMile + 10000)) : (currentMile + 10000);

                    const diff = goalMile - lastMaintMile;
                    const progress = diff > 0 ? Math.min(Math.max(0, (currentMile - lastMaintMile) / diff * 100), 100) : 0;
                    const remaining = Math.max(0, goalMile - currentMile);

                    document.getElementById('modal_progress_percent').textContent = progress.toFixed(1) + '%';
                    document.getElementById('modal_current_mileage').textContent = currentMile.toLocaleString() + ' km';
                    document.getElementById('modal_goal_mileage').textContent = goalMile.toLocaleString() + ' km';
                    document.getElementById('modal_remaining_km').textContent = remaining.toLocaleString();

                    const progressBar = document.getElementById('modal_progress_bar');
                    if (progressBar) {
                        progressBar.style.width = progress + '%';

                        const mStatusBadge = document.getElementById('modal_status_badge');
                        if (currentMile >= goalMile) {
                            mStatusBadge.className = "badge badge-error text-white font-black text-[10px] py-3 px-3 animate-pulse";
                            mStatusBadge.textContent = "OVERDUE";
                            progressBar.className = "bg-gradient-to-r from-red-500 to-rose-600 h-full rounded-full transition-all duration-1000";
                        } else if (currentMile >= (goalMile - 1000)) {
                            mStatusBadge.className = "badge badge-warning text-white font-black text-[10px] py-3 px-3";
                            mStatusBadge.textContent = "DUE SOON";
                            progressBar.className = "bg-gradient-to-r from-orange-400 to-amber-500 h-full rounded-full transition-all duration-1000";
                        } else {
                            mStatusBadge.className = "badge badge-success text-white font-black text-[10px] py-3 px-3";
                            mStatusBadge.textContent = "HEALTHY";
                            progressBar.className = "bg-gradient-to-r from-green-400 to-emerald-500 h-full rounded-full transition-all duration-1000";
                        }
                    }
                }

                modal.showModal();
            } catch (e) {
                console.error("Error opening view details modal:", e);
                alert("เกิดข้อผิดพลาดในการแสดงรายละเอียดรถ");
            }
        }

        // Open Maintenance Entry Modal
        function openInspectionModal(vehicle) {
            try {
                const modal = document.getElementById('inspection_modal');
                document.getElementById('insp_vehicle_id').value = vehicle.vehicle_id;
                document.getElementById('insp_vehicle_name').textContent = `${vehicle.name} (${vehicle.brand || ''})`;
                document.getElementById('insp_current_mileage').value = vehicle.latest_mileage || 0;

                // Suggest next mileage (+10000)
                const current = parseInt(vehicle.latest_mileage || 0);
                document.getElementById('insp_next_mileage').value = current + 10000;

                const roundSelect = document.getElementById('insp_round_select');
                if (roundSelect) roundSelect.value = "";

                modal.showModal();
            } catch (e) {
                console.error("Error opening inspection modal:", e);
            }
        }

        // Handle Add Inspection Mileage Suggestion
        function updateInspMileageByRound(round) {
            if (!round) return;
            document.getElementById('insp_current_mileage').value = round;
            document.getElementById('insp_next_mileage').value = parseInt(round) + 10000;
        }

        // Handle Edit Inspection Mileage Suggestion
        function updateEditInspMileageByRound(round) {
            if (!round) return;
            document.getElementById('edit_insp_mileage').value = round;
            document.getElementById('edit_insp_next_mileage').value = parseInt(round) + 10000;
        }

        // Open Edit Inspection Modal
        function openEditInspectionModal(inspection) {
            try {
                const form = document.getElementById('edit_inspection_form');
                form.action = `/backend/bookingcar/inspections/${inspection.inspection_id}`;

                document.getElementById('edit_insp_vehicle_name').innerText = `รถ: ${inspection.vehicle?.name || inspection.vehicle_id}`;
                document.getElementById('edit_insp_date').value = inspection.inspection_date ? inspection.inspection_date.split(' ')[0] : '';
                document.getElementById('edit_insp_mileage').value = inspection.mileage || 0;
                document.getElementById('edit_insp_next_mileage').value = inspection.next_mileage || 0;
                document.getElementById('edit_insp_inspector').value = inspection.inspector_name || '';
                document.getElementById('edit_insp_location').value = inspection.location || '';
                document.getElementById('edit_insp_description').value = inspection.description || '';
                document.getElementById('edit_insp_status').value = inspection.status || 0;
                document.getElementById('current_file_name').innerText = inspection.file_vehicle ? `ไฟล์เดิม: ${inspection.file_vehicle}` : 'ไฟล์เดิม: ไม่มี';
                const fileLink = document.getElementById('edit_insp_file_link');
                if (fileLink) {
                    if (inspection.file_vehicle) {
                        fileLink.href = `/uploads/vehicl_file_maintenance/${inspection.file_vehicle}`;
                        fileLink.classList.remove('hidden');
                    } else {
                        fileLink.classList.add('hidden');
                    }
                }

                const roundSelect = document.getElementById('edit_insp_round_select');
                if (roundSelect) {
                    roundSelect.value = "";
                    for (let option of roundSelect.options) {
                        if (option.value == inspection.mileage) {
                            roundSelect.value = option.value;
                            break;
                        }
                    }
                }

                document.getElementById('edit_inspection_modal').showModal();
            } catch (e) {
                console.error("Error opening edit inspection modal:", e);
            }
        }

        // Delete Confirmation
        function confirmDelete(id, name) {
            const modal = document.getElementById('delete_modal');
            const form = document.getElementById('delete_form');
            const nameSpan = document.getElementById('delete_vehicle_name');

            if (nameSpan) nameSpan.innerText = name;
            if (form) form.action = `/backend/bookingcar/${id}`;
            if (modal) modal.showModal();
        }

        // View Booking Event Details
        function viewBookingDetails(booking) {
            try {
                const modal = document.getElementById('view_booking_modal');
                if (!modal) return;

                document.getElementById('view_book_code').textContent = `#${booking.booking_code || 'N/A'}`;
                document.getElementById('view_book_dest').textContent = booking.destination || 'ไม่ได้ระบุสถานที่';

                const userSpan = document.getElementById('view_book_user')?.querySelector('span');
                if (userSpan) {
                    userSpan.textContent = `${booking.user?.first_name || ''} ${booking.user?.last_name || ''} (${booking.user?.department?.department_name || '-'})`;
                }

                const formatDate = (str) => {
                    if (!str) return '-';
                    const date = new Date(str);
                    return date.toLocaleString('th-TH', {
                        day: '2-digit', month: '2-digit', year: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    });
                };

                document.getElementById('view_book_start').textContent = formatDate(booking.start_time);
                document.getElementById('view_book_end').textContent = formatDate(booking.end_time);
                document.getElementById('view_book_loc_full').textContent = `${booking.district || '-'} จ.${booking.province || '-'}`;
                document.getElementById('view_book_passengers').textContent = `${booking.passenger_count || 1} ท่าน`;
                document.getElementById('view_book_purpose').textContent = booking.purpose || 'ไม่มีระบุวัตถุประสงค์';
                document.getElementById('view_book_vehicle_name').textContent = booking.vehicle?.name || 'ไม่พบข้อมูลรถ';
                document.getElementById('view_book_vehicle_plate').textContent = booking.vehicle?.license_plate || '-';

                const statusBadge = document.getElementById('view_book_status_badge');
                if (statusBadge) {
                    statusBadge.textContent = booking.status || '-';
                    const s = booking.status;
                    statusBadge.className = `badge badge-lg font-bold w-full py-4 text-sm border-0 text-white ${s === 'อนุมัติแล้ว' ? 'bg-green-500' : (s === 'รออนุมัติ' ? 'bg-orange-400' : 'bg-red-500')
                        }`;
                }

                document.getElementById('view_book_return_status').textContent = booking.return_status || 'ยังไม่ส่งคืน';
                document.getElementById('view_book_return_note').textContent = booking.note_returning || 'ไม่มีหมายเหตุ';

                // Attachments handling
                const attachContainer = document.getElementById('view_book_attachments');
                if (attachContainer) {
                    attachContainer.innerHTML = '';

                    const addLink = (path, label, icon) => {
                        const a = document.createElement('a');
                        a.href = `/${path}`;
                        a.target = '_blank';
                        a.className = "flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-slate-600 text-[10px] font-bold hover:bg-slate-50 transition-colors";
                        a.innerHTML = `<i class="fa-solid ${icon} text-blue-500"></i> ${label}`;
                        attachContainer.appendChild(a);
                    };

                    if (booking.attachment) addLink(booking.attachment, 'เอกสารหลัก', 'fa-file-pdf');

                    const iterateAttach = (val, prefix, icon) => {
                        if (!val) return;
                        try {
                            const parsed = JSON.parse(val);
                            if (Array.isArray(parsed)) parsed.forEach((f, i) => addLink(f, `${prefix} ${i + 1}`, icon));
                            else addLink(val, prefix, icon);
                        } catch (e) { addLink(val, prefix, icon); }
                    };

                    iterateAttach(booking.attachment_going, 'รูปขาไป', 'fa-image');
                    iterateAttach(booking.attachment_returning, 'รูปขากลับ', 'fa-image');

                    if (!attachContainer.innerHTML) {
                        attachContainer.innerHTML = '<span class="text-slate-400 text-xs italic">ไม่มีเอกสารแนบ</span>';
                    }
                }

                const editBtn = document.getElementById('view_book_edit_btn');
                if (editBtn) editBtn.href = `/bookingcar/edit/${booking.booking_id}`;

                modal.showModal();
            } catch (e) {
                console.error("Error opening booking details:", e);
            }
        }

        // Tab System
        function showTab(tabId, save = true) {
            if (save) localStorage.setItem('activeBookingCarTab', tabId);

            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab').forEach(el => {
                el.classList.remove('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
            });

            const target = document.getElementById(tabId);
            if (target) target.classList.remove('hidden');

            const btnId = tabId.replace('tab-', 'btn-');
            const btn = document.getElementById(btnId);
            if (btn) btn.classList.add('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
        }

        // Initialization
        document.addEventListener('DOMContentLoaded', () => {
            const activeTab = localStorage.getItem('activeBookingCarTab') || 'tab-vehicles';
            showTab(activeTab, false);
        });
    </script>
@endsection