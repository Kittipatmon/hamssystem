@extends('layouts.housing.apphousing')
@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8 space-y-8 animate-fade-in">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-100/50">
                    <i class="fa-solid fa-file-invoice text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 leading-tight">
                        @switch($type)
                            @case('request') รายละเอียดคำขอเข้าพักอาศัย @break
                            @case('agreement') รายละเอียดข้อตกลงเข้าพัก @break
                            @case('guest') รายละเอียดคำขอนำญาติเข้าพัก @break
                            @case('leave') รายละเอียดคำร้องขอย้ายออก @break
                        @endswitch
                    </h1>
                    <p class="text-sm text-slate-400 font-semibold mt-2 flex items-center gap-2">
                        @php
                            $code = match($type) {
                                'request' => $item->requests_code,
                                'agreement' => $item->agreement_code,
                                'guest' => $item->resident_guest_code,
                                'leave' => $item->residence_leaves_code,
                            };
                            $date = match($type) {
                                'request' => $item->request_date,
                                'agreement' => $item->agreement_date,
                                'guest' => $item->request_date,
                                'leave' => $item->request_date,
                            };
                        @endphp
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg font-mono text-xs">{{ $code }}</span>
                        <span>•</span>
                        <span>วันที่ยื่น: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $backTab = match($type) {
                        'request' => 'requests',
                        'agreement' => 'agreements',
                        'guest' => 'guests',
                        'leave' => 'leaves',
                        default => 'requests'
                    };
                @endphp
                <a href="{{ route('housing.management', ['tab' => $backTab]) }}" 
                   class="px-6 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left text-xs"></i> กลับหน้ารวม
                </a>
                @php
                    $pdfRoute = match($type) {
                        'request' => route('housing.request.pdf', $item->id),
                        'agreement' => route('housing.agreement.pdf', $item->agreement_id),
                        'guest' => route('housing.guest.pdf', $item->resident_guest_id),
                        'leave' => route('housing.leave.pdf', $item->residence_leaves_id),
                        default => '#'
                    };
                @endphp
                <a href="{{ $pdfRoute }}" target="_blank"
                   class="px-6 py-2.5 bg-red-600 text-white rounded-xl text-sm font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-100 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf"></i> พิมพ์ PDF
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Main Info Card -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                            <i class="fa-solid fa-user-circle text-red-500"></i> ข้อมูลผู้ยื่นคำขอ
                        </h2>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชื่อ-นามสกุล</p>
                            <p class="text-base font-bold text-slate-700">
                                {{ $item->title ?? '' }}{{ $item->first_name }} {{ $item->last_name }}
                                @if($type == 'agreement') {{ $item->full_name }} @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ตำแหน่ง / สังกัด</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->position }}</p>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                {{ $item->department }} / {{ $item->section }}
                            </p>
                        </div>
                        @if(isset($item->phone))
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เบอร์โทรศัพท์</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->phone }}</p>
                        </div>
                        @endif
                        @if(isset($item->marital_status))
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">สถานภาพสมรส</p>
                            <p class="text-base font-bold text-slate-700">
                                {{ match($item->marital_status) { 'โสด' => 'โสด', 'สมรส' => 'สมรส', 'หย่าร้าง' => 'หย่าร้าง', 'หม้าย' => 'หม้าย', default => $item->marital_status } }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Specific Request Type Information -->
                @if($type == 'request')
                    <!-- Address Section -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                                <i class="fa-solid fa-map-location-dot text-blue-500"></i> ข้อมูลที่อยู่
                            </h2>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">ที่อยู่ตามภูมิลำเนา</p>
                                <p class="text-sm font-semibold text-slate-700 leading-relaxed">
                                    {{ $item->address_original }}
                                    ต.{{ $item->address_original_subdistrict }} 
                                    อ.{{ $item->address_original_district }} 
                                    จ.{{ $item->address_original_province }}
                                </p>
                            </div>
                            <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">ที่อยู่ปัจจุบัน</p>
                                <p class="text-sm font-semibold text-slate-700 leading-relaxed">
                                    {{ $item->address_current }}
                                    ต.{{ $item->address_current_subdistrict }} 
                                    อ.{{ $item->address_current_district }} 
                                    จ.{{ $item->address_current_province }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-500 mt-2">ขั้วคำขอ: {{ $item->current_house_type }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Joint Residents Section -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                                <i class="fa-solid fa-people-group text-emerald-500"></i> ผู้พักอาศัยร่วม
                            </h2>
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold">รวม {{ $item->number_of_residents }} คน</span>
                        </div>
                        <div class="p-0">
                            @if($item->dependents->count() > 0)
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50/50 text-[10px] font-black uppercase text-slate-400 border-b border-slate-100">
                                    <tr>
                                        <th class="px-8 py-4 text-left">ชื่อ-นามสกุล</th>
                                        <th class="px-8 py-4 text-center">อายุ (ปี)</th>
                                        <th class="px-8 py-4 text-left">เกี่ยวข้องเป็น</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($item->dependents as $dep)
                                    <tr>
                                        <td class="px-8 py-5 font-bold text-slate-700">{{ $dep->full_name }}</td>
                                        <td class="px-8 py-5 text-center font-bold text-slate-500">{{ $dep->age }}</td>
                                        <td class="px-8 py-5 font-bold text-slate-500">{{ $dep->relation }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="p-12 text-center text-slate-300">
                                <i class="fa-solid fa-user-slash text-3xl mb-3 block"></i>
                                <p class="text-xs font-bold">ไม่มีผู้พักอาศัยร่วม</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($type == 'agreement')
                    <!-- Agreement Detail Card -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                                <i class="fa-solid fa-house-chimney-user text-blue-500"></i> รายละเอียดบ้านพัก
                            </h2>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">บ้านพักที่ได้รับมอบหมาย</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->residence_address }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชั้น</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->residence_floor }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">จำนวนผู้พักอาศัยร่วม</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->number_of_residents }} คน</p>
                            </div>
                        </div>
                    </div>
                @elseif($type == 'guest')
                    <!-- Guest Detail Card -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                                <i class="fa-solid fa-people-arrows text-purple-500"></i> ข้อมูลการนำญาติเข้าพัก
                            </h2>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 border-b border-slate-50">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ประเภทบ้านพัก</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->residence_type }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เลขที่ห้อง</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->room_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ความสัมพันธ์กับพนักงาน</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->relationship }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ช่วงเวลาที่เข้าพัก</p>
                                <p class="text-base font-bold text-indigo-600">
                                    {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-xs font-bold text-slate-400 mt-1">รวมทั้งสิ้น {{ $item->total_days }} วัน</p>
                            </div>
                        </div>
                        <div class="p-8">
                            <h3 class="text-sm font-bold text-slate-800 mb-4">รายชื่อผู้อาศัยร่วม</h3>
                            @if($item->members->count() > 0)
                                <div class="space-y-3">
                                    @foreach($item->members as $member)
                                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm">
                                                <i class="fa-solid fa-user text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700">{{ $member->full_name }}</p>
                                                <p class="text-[10px] font-bold text-slate-400">เลชบัตรประจำตัวประชาชน: {{ $member->id_card }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-400 font-bold italic">ไม่ระบุรายชื่อ</p>
                            @endif
                        </div>
                    </div>
                @elseif($type == 'leave')
                    <!-- Leave Detail Card -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                                <i class="fa-solid fa-right-from-bracket text-orange-500"></i> ข้อมูลการความประสงค์ย้ายออก
                            </h2>
                        </div>
                        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ประเภทบ้านพัก</p>
                                <p class="text-base font-bold text-slate-700">{{ $item->residence_type }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เลขที่ห้อง / ชั้น</p>
                                <p class="text-base font-bold text-slate-700">ห้อง {{ $item->room_number }} @if($item->floor) ชั้น {{ $item->floor }} @endif</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">วันที่ต้องการย้ายออก</p>
                                <p class="text-base font-bold text-orange-600">
                                    {{ \Carbon\Carbon::parse($item->move_out_date)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(isset($item->residence_reason) || isset($item->reason))
                <!-- Reason Card -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                            <i class="fa-solid fa-comment-dots text-amber-500"></i> เหตุผลความจำเป็น
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="bg-amber-50/50 p-6 rounded-2xl border border-amber-100 italic text-amber-900 font-medium leading-relaxed">
                            "{{ $item->residence_reason ?? $item->reason }}"
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Side: Status and Files -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">สถานะปัจจุบัน</h3>
                    <div class="flex flex-col items-center text-center space-y-4">
                        <div class="px-5 py-2.5 rounded-2xl text-xs font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->send_status) }} shadow-sm inline-flex items-center gap-2">
                            <i class="fa-solid fa-circle-dot animate-pulse"></i>
                            {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->send_status) }}
                        </div>
                    </div>
                </div>

                @if($type == 'request' && $item->requests_file)
                <!-- Files Card -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">เอกสารแนบ</h3>
                    <div class="space-y-3">
                        @php $files = json_decode($item->requests_file, true) ?? []; @endphp
                        @forelse($files as $fileName)
                        <a href="{{ asset('uploads/housing_requests/' . $fileName) }}" target="_blank" 
                           class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-100 transition-all group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                <span class="text-xs font-bold text-slate-600 truncate max-w-[150px]">{{ $fileName }}</span>
                            </div>
                            <i class="fa-solid fa-download text-slate-300 group-hover:text-red-500 transition-colors"></i>
                        </a>
                        @empty
                        <p class="text-xs text-slate-400 font-bold text-center">ไม่มีเอกสารแนบ</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
@endsection
