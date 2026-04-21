@extends('layouts.housing.apphousing')

@section('title', 'รายละเอียดห้องพัก')

@section('content')
    <div class="max-w-4xl mx-auto pb-12">

        {{-- Header with Back Button --}}
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('housing.houselist') }}"
                class="group flex items-center gap-2 text-slate-500 hover:text-red-600 transition-colors font-semibold">
                <div
                    class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-red-50 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </div>
                <span>กลับไปหน้ารายการ</span>
            </a>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">ห้อง {{ $room->room_number }}</h2>
                <p class="text-xs text-slate-400 font-medium">พื้นที่: {{ $room->residence->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Room Info Card --}}
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div
                        class="h-48 bg-slate-100 dark:bg-gray-700 relative overflow-hidden flex items-center justify-center">
                        @if($room->image)
                            <img src="{{ asset($room->image) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-bed text-5xl text-slate-300"></i>
                        @endif

                        <div class="absolute top-4 right-4">
                            @php
                                $currentStay = $room->stays->where('is_current', 1)->first();
                                $status = $room->residence_room_status;

                                // Fallback logic: If no current occupant, treat status as 0 (unless in maintenance)
                                if ($status == 1 && !$currentStay) {
                                    $status = 0;
                                }
                                // If has occupant but status is 0, treat as 1
                                if ($status == 0 && $currentStay) {
                                    $status = 1;
                                }

                                $statusColors = [
                                    0 => 'bg-emerald-500',
                                    1 => 'bg-red-500',
                                    2 => 'bg-amber-500'
                                ];
                                $statusLabels = [
                                    0 => 'ว่าง',
                                    1 => 'ไม่ว่าง',
                                    2 => 'ซ่อม/ปรับปรุง'
                                ];
                            @endphp
                            <span
                                class="{{ $statusColors[$status] }} text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">
                                {{ $statusLabels[$status] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700">
                            <span class="text-xs text-slate-400 font-bold uppercase">ชั้น</span>
                            <span class="font-bold text-gray-700 dark:text-gray-200">{{ $room->floor }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700">
                            <span class="text-xs text-slate-400 font-bold uppercase">ความจุ</span>
                            <span class="font-bold text-gray-700 dark:text-gray-200">{{ $room->capacity ?? '-' }} คน</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-xs text-slate-400 font-bold uppercase">ราคา</span>
                            <span class="font-bold text-red-600">{{ number_format($room->price ?? 0) }} ฿</span>
                        </div>
                    </div>
                </div>

                @if($room->note)
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-2xl border border-blue-100 dark:border-blue-800">
                        <h4 class="text-blue-600 dark:text-blue-400 text-xs font-bold mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info"></i> หมายเหตุเพิ่มเติม
                        </h4>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $room->note }}</p>
                    </div>
                @endif
            </div>

            {{-- Right: Main Content (Occupant or Assignment) --}}
            <div class="lg:col-span-2 space-y-6">

                @if($room->residence_room_status == 1 && $currentStay)
                    {{-- Occupant Info --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-red-50/50 dark:bg-red-900/10 border-b border-red-50 dark:border-red-900/20">
                            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-user-check text-red-500"></i>
                                ข้อมูลผู้เข้าพักปัจจุบัน
                            </h3>
                        </div>
                        <div class="p-8">
                        <div class="flex items-center gap-6 mb-8 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-2xl border border-slate-100 dark:border-gray-600">
                            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-3xl shadow-inner overflow-hidden border-2 border-white dark:border-gray-800">
                                @php
                                    $displayAgreement = $agreement ?? $currentStay->resident;
                                @endphp
                                @if($displayAgreement && $displayAgreement->user && $displayAgreement->user->photo_user)
                                    <img src="{{ asset($displayAgreement->user->photo_user) }}" class="w-full h-full object-cover">
                                @elseif($displayAgreement && $displayAgreement->user && $displayAgreement->user->sex == 'หญิง')
                                    <i class="fa-solid fa-user-female text-red-300"></i>
                                @else
                                    <i class="fa-solid fa-user text-red-300"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">ผู้เข้าพัก</p>
                                <p class="text-xl font-black text-slate-800 dark:text-white leading-tight">
                                    {{ $displayAgreement->full_name ?? ($latestReq ? $latestReq->first_name . ' ' . $latestReq->last_name : 'ไม่พบข้อมูล') }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @php
                                        $displayPosition = optional($displayAgreement)->position ?? (optional($latestReq)->position ?? 'พนักงาน');
                                        $displayDept = optional($displayAgreement)->department ?? (optional($latestReq)->department ?? '-');
                                        $displaySection = optional($displayAgreement)->section ?? (optional($latestReq)->section ?? '');
                                    @endphp
                                    <span class="px-2 py-0.5 bg-red-50 dark:bg-red-900/20 rounded text-[10px] font-bold text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30">
                                        {{ $displayPosition }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-white dark:bg-gray-800 rounded text-[10px] font-bold text-slate-500 border border-slate-200 dark:border-gray-700">
                                        {{ $displayDept }} {{ $displaySection ? ' / ' . $displaySection : '' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                            <div class="grid grid-cols-2 gap-6 text-sm">
                                <div class="p-4 rounded-2xl border border-gray-50 dark:border-gray-700">
                                    <p class="text-slate-400 text-[10px] uppercase font-bold mb-1">วันที่เข้าพัก</p>
                                    <p class="font-bold text-gray-700 dark:text-gray-200">
                                        <i class="fa-solid fa-calendar-day mr-2 text-red-400"></i>
                                        {{ $currentStay->residence_stay_date ? \Carbon\Carbon::parse($currentStay->residence_stay_date)->format('d/m/Y') : ($currentStay->check_in ? \Carbon\Carbon::parse($currentStay->check_in)->format('d/m/Y') : '-') }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-2xl border border-gray-50 dark:border-gray-700">
                                    <p class="text-slate-400 text-[10px] uppercase font-bold mb-1">เบอร์ติดต่อ</p>
                                    <p class="font-bold text-gray-700 dark:text-gray-200">
                                        <i class="fa-solid fa-phone mr-2 text-red-400"></i>
                                        {{ $currentStay->tel_phone ?? ($latestReq->phone ?? '-') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Request Details --}}
                    @if($latestReq)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden mt-6">
                        <div class="px-6 py-4 bg-slate-50 dark:bg-gray-700/50 border-b border-slate-100 dark:border-gray-600 text-center">
                            <h3 class="font-bold text-gray-800 dark:text-white flex items-center justify-center gap-2 text-sm">
                                <i class="fa-solid fa-file-lines text-slate-400"></i>
                                ข้อมูลจากแบบฟอร์มขอเข้าอยู่อาศัย ({{ $latestReq->requests_code }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-8 text-center mt-4">
                                <div class="space-y-8">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">สถานที่ปฏิบัติงาน</span>
                                        <span class="text-sm font-black text-red-600 dark:text-red-400 mt-1">{{ $latestReq->site ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">สถานภาพ</span>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-200 mt-1">{{ $latestReq->marital_status ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="space-y-8">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">จำนวนผู้อยู่อาศัยรวม</span>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-200 mt-1">{{ $latestReq->number_of_residents ?? '-' }} คน</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">ที่อยู่ตามภูมิลำเนาเดิม</span>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-200 mt-1 leading-tight">
                                            {{ $latestReq->address_original_province ? ($latestReq->address_original_province . ' / ' . $latestReq->address_original_district) : ($latestReq->address_original ?? '-') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="md:col-span-2 lg:col-span-1 space-y-8">
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">ประเภทที่พักปัจจุบัน</span>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-gray-200 mt-1">{{ $latestReq->current_house_type ?? '-' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">เหตุผลความจำเป็น</span>
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 mt-2 leading-relaxed max-w-[200px]">"{{ $latestReq->residence_reason ?? '-' }}"</span>
                                    </div>
                                </div>
                            </div>

                            @if($latestReq->dependents && $latestReq->dependents->count() > 0)
                            <div class="pt-8 mt-6 border-t border-slate-50 dark:border-gray-700 text-center">
                                <span class="text-[10px] text-slate-400 font-bold uppercase block mb-4 tracking-widest">ผู้อยู่อาศัยร่วม (Dependents)</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($latestReq->dependents as $dep)
                                    <div class="bg-slate-50 dark:bg-gray-700/30 p-3 rounded-xl border border-slate-100 dark:border-gray-600 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 dark:text-gray-200">{{ $dep->full_name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $dep->relation }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400">{{ $dep->age }} ปี</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Process Status Stepper --}}
                            <div class="pt-10 mt-10 border-t border-slate-50 dark:border-gray-700">
                                <h3 class="text-[13px] font-black text-slate-800 dark:text-white uppercase tracking-widest text-center mb-10 flex items-center justify-center gap-3">
                                    <div class="w-1.5 h-6 bg-red-500 rounded-full"></div>
                                    สถานะกระบวนการ
                                </h3>

                                <div class="relative flex flex-col md:flex-row items-start justify-between gap-6 md:gap-4 px-4">
                                    {{-- Global Progress Line Background --}}
                                    <div class="absolute left-[35px] top-[70px] bottom-[40px] md:left-[50px] md:right-[50px] md:top-[35px] md:bottom-auto md:h-[2px] bg-slate-100 dark:bg-gray-700 z-0"></div>

                                    @php
                                        $s = intval($latestReq->send_status);
                                        // Progress percentage for the line
                                        $progressWidth = '0%';
                                        if ($s == 1) $progressWidth = '25%';
                                        if ($s == 2) $progressWidth = '50%';
                                        if ($s >= 3) $progressWidth = '100%';
                                    @endphp
                                    {{-- Active Progress Line --}}
                                    <div class="absolute left-[35px] top-[70px] md:left-[50px] md:top-[35px] md:h-[2px] bg-emerald-500 z-0 transition-all duration-1000"
                                         style="width: {{ $progressWidth }}; max-width: calc(100% - 100px);"></div>

                                    {{-- Step 1: Commander --}}
                                    <div class="relative z-10 flex flex-row md:flex-col items-center gap-4 md:gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-2xl {{ $s >= 1 ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-white border-2 border-slate-100 text-slate-300' }} shadow-lg flex items-center justify-center transition-all duration-500 group">
                                            <i class="fa-solid fa-user-tie text-lg"></i>
                                        </div>
                                        <div class="flex flex-col md:items-center text-left md:text-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Step 01</span>
                                            <h4 class="text-[11px] font-bold text-slate-700 dark:text-gray-300">ผู้บังคับบัญชา</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $latestReq->commander->fullname ?? 'รอระบุ' }}</p>
                                            @if($latestReq->commander_status == 1)
                                                <span class="mt-2 px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-black">ผ่านการอนุมัติ</span>
                                            @elseif($latestReq->commander_status == 2)
                                                <span class="mt-2 px-2 py-0.5 bg-red-50 text-red-600 rounded-md text-[9px] font-black">ไม่ผ่าน</span>
                                            @endif
                                            @if($latestReq->commander_date)
                                                <p class="text-[8px] text-slate-400 font-bold mt-1.5">{{ \Carbon\Carbon::parse($latestReq->commander_date)->format('d/m/Y') }}</p>
                                            @endif
                                            @if($latestReq->commander_comment)
                                                <p class="mt-2 text-[9px] text-slate-500 italic max-w-[120px] line-clamp-2">"{{ $latestReq->commander_comment }}"</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Step 2: HAMS Manager --}}
                                    <div class="relative z-10 flex flex-row md:flex-col items-center gap-4 md:gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-2xl {{ $s >= 2 ? 'bg-emerald-500 text-white shadow-emerald-200' : ($s == 1 ? 'bg-white border-2 border-blue-400 text-blue-500 shadow-blue-100 animate-pulse' : 'bg-white border-2 border-slate-100 text-slate-300') }} shadow-lg flex items-center justify-center transition-all duration-500">
                                            <i class="fa-solid fa-building-user text-lg"></i>
                                        </div>
                                        <div class="flex flex-col md:items-center text-left md:text-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Step 02</span>
                                            <h4 class="text-[11px] font-bold text-slate-700 dark:text-gray-300">ผจก. แผนกฯ</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $latestReq->managerHams->fullname ?? 'รอระบุ' }}</p>
                                            @if($latestReq->managerhams_status == 1)
                                                <span class="mt-2 px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-black">เห็นสมควร</span>
                                            @elseif($latestReq->managerhams_status == 2)
                                                <span class="mt-2 px-2 py-0.5 bg-red-50 text-red-600 rounded-md text-[9px] font-black">ไม่ผ่าน</span>
                                            @endif
                                            @if($latestReq->managerhams_date)
                                                <p class="text-[8px] text-slate-400 font-bold mt-1.5">{{ \Carbon\Carbon::parse($latestReq->managerhams_date)->format('d/m/Y') }}</p>
                                            @endif
                                            @if($latestReq->managerhams_comment)
                                                <p class="mt-2 text-[9px] text-slate-500 italic max-w-[120px] line-clamp-2">"{{ $latestReq->managerhams_comment }}"</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Step 3: Committee --}}
                                    <div class="relative z-10 flex flex-row md:flex-col items-center gap-4 md:gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-2xl {{ $s >= 3 ? 'bg-emerald-500 text-white shadow-emerald-200' : ($s == 2 ? 'bg-white border-2 border-amber-400 text-amber-500 shadow-amber-100 animate-pulse' : 'bg-white border-2 border-slate-100 text-slate-300') }} shadow-lg flex items-center justify-center transition-all duration-500">
                                            <i class="fa-solid fa-users-gear text-lg"></i>
                                        </div>
                                        <div class="flex flex-col md:items-center text-left md:text-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Step 03</span>
                                            <h4 class="text-[11px] font-bold text-slate-700 dark:text-gray-300">คณะกรรมการย่อย</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $latestReq->committee->fullname ?? 'รอมติร่วม' }}</p>
                                            @if($latestReq->Committee_status == 1)
                                                <span class="mt-2 px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md text-[9px] font-black">อนุมัติ</span>
                                            @elseif($latestReq->Committee_status == 2)
                                                <span class="mt-2 px-2 py-0.5 bg-red-50 text-red-600 rounded-md text-[9px] font-black">ไม่อนุมัติ</span>
                                            @endif
                                            @if($latestReq->Committee_date)
                                                <p class="text-[8px] text-slate-400 font-bold mt-1.5">{{ \Carbon\Carbon::parse($latestReq->Committee_date)->format('d/m/Y') }}</p>
                                            @endif
                                            @if($latestReq->Committee_comment)
                                                <p class="mt-2 text-[9px] text-slate-500 italic max-w-[120px] line-clamp-2">"{{ $latestReq->Committee_comment }}"</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Step 4: Ready/Assigned --}}
                                    <div class="relative z-10 flex flex-row md:flex-col items-center gap-4 md:gap-3 flex-1">
                                        <div class="w-12 h-12 rounded-2xl {{ $s >= 6 ? 'bg-blue-600 text-white shadow-blue-200' : ($s >= 3 ? 'bg-white border-2 border-blue-400 text-blue-500 shadow-blue-100 animate-pulse' : 'bg-white border-2 border-slate-100 text-slate-300') }} shadow-lg flex items-center justify-center transition-all duration-500">
                                            <i class="fa-solid fa-house-circle-check text-lg"></i>
                                        </div>
                                        <div class="flex flex-col md:items-center text-left md:text-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Step 04</span>
                                            <h4 class="text-[11px] font-bold text-slate-700 dark:text-gray-300">จัดเตรียมที่พัก</h4>
                                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $s >= 7 ? 'ดำเนินการแล้ว' : 'เตรียมความพร้อม' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-3 bg-slate-50 dark:bg-gray-700/20 border-t border-slate-100 dark:border-gray-600 flex justify-between items-center">
                            <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($latestReq->send_status) }} whitespace-nowrap">
                                <span class="hidden md:inline">สถานะ: {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($latestReq->send_status, 'request') }}</span>
                                <span class="md:hidden">{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($latestReq->send_status, 'request') }}</span>
                            </span>
                            <a href="{{ route('housing.request_detail', ['type' => 'request', 'id' => $latestReq->id]) }}" 
                               class="text-[10px] font-bold text-red-600 hover:text-red-700 transition-colors flex items-center gap-1">
                                <i class="fa-solid fa-eye"></i> ดูใบคำขอฉบับเต็ม
                            </a>
                        </div>
                    </div>
                    @endif

                @elseif($room->residence_room_status == 0)
                    {{-- Assignment Form --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div
                            class="px-6 py-4 bg-emerald-50/50 dark:bg-emerald-900/10 border-b border-emerald-50 dark:border-emerald-900/20">
                            <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-key text-emerald-500"></i>
                                มอบหมายห้องพัก
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div
                                class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-2xl text-emerald-700 dark:text-emerald-400 text-sm italic">
                                เลือกผู้ส่งคำขอเข้าพักที่ผ่านการพิจารณาเบื้องต้นแล้ว เพื่อมอบหมายห้องนี้และปรับสถานะเป็น
                                "ดำเนินการเสร็จสิ้น"
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">เลือกผู้ส่งคำขอเข้าพัก</label>
                                    <select id="requestSelect"
                                        class="select2 w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:ring-red-500 transition-all">
                                        <option value="">-- กรุณาเลือกรายชื่อ --</option>
                                        @foreach($eligibleRequesters as $req)
                                            @if($req->site && $room->residence->name && (str_contains($req->site, $room->residence->name) || str_contains($room->residence->name, $req->site)))
                                                <option value="{{ $req->id }}">{{ $req->requests_code }} - {{ $req->first_name }}
                                                    {{ $req->last_name }} (พื้นที่ที่ขอ: {{ $req->site }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <button onclick="submitRoomAssignment({{ $room->residence_room_id }})"
                                    class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-red-200 dark:shadow-none hover:shadow-2xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3 text-lg">
                                    <i class="fa-solid fa-check-circle text-xl"></i>
                                    ยืนยันการมอบหมายและปิดงาน
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Maintenance View --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-12 text-center">
                        <div
                            class="w-16 h-16 rounded-full bg-amber-50 mx-auto flex items-center justify-center text-amber-500 mb-4 shadow-inner">
                            <i class="fa-solid fa-wrench text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">ห้องพักอยู่ระหว่างการซ่อมแซม</h3>
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">ยังไม่เปิดให้มีการมอบหมายห้องพักในขณะนี้
                            หากดำเนินการเสร็จสิ้นแล้ว โปรดอัปเดตสถานะห้องกลับเป็น "ว่าง"</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        function submitRoomAssignment(roomId) {
            const requestId = document.getElementById('requestSelect').value;

            if (!requestId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'คำแนะนำ',
                    text: 'กรุณาเลือกผู้ส่งคำขอจากรายการก่อนครับ',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }

            Swal.fire({
                title: 'ยืนยันการมอบหมาย?',
                text: "ระบบจะทำการมอบหมายห้องและแจ้งเตือนไปยังผู้ใช้งานทันที",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route("housing.assign_room") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            room_id: roomId,
                            request_id: requestId
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'สำเร็จ!',
                                    text: 'มอบหมายห้องพักเรียบร้อยแล้ว',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('housing.houselist') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ผิดพลาด',
                                    text: data.message || 'เกิดข้อผิดพลาดในการดำเนินการ'
                                });
                            }
                        });
                }
            });
        }

        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                dropdownCssClass: 'text-sm'
            });
        });
    </script>
@endsection