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
                        <span>วันที่ยื่น: {{ \Carbon\Carbon::parse($date)->translatedFormat('d M') }} {{ \Carbon\Carbon::parse($date)->year + 543 }}</span>
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
                    <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-8">
                        @if($type == 'request' || ($type != 'request' && isset($item->latestReq->site)))
                        <div class="col-span-2 md:col-span-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 text-red-500">สถานที่ปฏิบัติงาน</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->site ?? ($item->latestReq->site ?? 'ไม่ได้ระบุ') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">คำนำหน้า</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->title ?? ($item->latestReq->title ?? (optional($item->user)->prefix ?? '-')) }}</p>
                        </div>
                        
                        @if($type == 'agreement')
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชื่อ-นามสกุล</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->full_name ?? ($item->latestReq->first_name . ' ' . $item->latestReq->last_name ?? (optional($item->user)->firstname . ' ' . optional($item->user)->lastname)) }}</p>
                        </div>
                        @else
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชื่อ</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->first_name ?? ($item->latestReq->first_name ?? (optional($item->user)->firstname ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">นามสกุล</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->last_name ?? ($item->latestReq->last_name ?? (optional($item->user)->lastname ?? '-')) }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">อายุงาน (ปี)</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->age_work ?? ($item->latestReq->age_work ?? '-') }}</p>
                        </div>
                        
                        <div class="col-span-2 md:col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ตำแหน่ง</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->position ?? ($item->latestReq->position ?? (optional($item->user)->position ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">แผนก</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->department ?? ($item->latestReq->department ?? (optional($item->user)->department->name ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ฝ่าย (สังกัด)</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->section ?? ($item->latestReq->section ?? (optional($item->user)->section->section_name ?? '-')) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">หมายเลขโทรศัพท์</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->phone ?? ($item->telephone ?? ($item->phone_user ?? ($item->latestReq->phone ?? (optional($item->user)->phone_user ?? '-')))) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">สถานภาพ</p>
                            <p class="text-base font-bold text-slate-700">{{ $item->marital_status ?? ($item->status_marriage ?? ($item->latestReq->marital_status ?? (optional($item->user)->status_marriage ?? '-'))) }}</p>
                        </div>
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
                        <div class="p-8 space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">ที่อยู่ตามทะเบียนบ้าน</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">บ้านเลขที่ / ที่อยู่</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_original }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">แขวง/ตำบล</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_original_subdistrict ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เขต/อำเภอ</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_original_district ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">จังหวัด</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_original_province ?? '-' }}</p>
                                </div>
                            </div>

                            <hr class="border-slate-100 border-dashed">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">ที่อยู่ปัจจุบัน</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">บ้านเลขที่ / ที่อยู่</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_current ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">แขวง/ตำบล</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_current_subdistrict ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เขต/อำเภอ</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_current_district ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">จังหวัด</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->address_current_province ?? '-' }}</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ลักษณะที่พักอาศัยปัจจุบัน</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->current_house_type ?? '-' }}</p>
                                </div>
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
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-[10px] font-bold text-slate-400">อายุ: {{ $member->age ?? '-' }} ปี</span>
                                                    <span class="text-slate-300">•</span>
                                                    <span class="text-[10px] font-bold text-slate-400">เกี่ยวข้องเป็น: {{ $member->relation ?? '-' }}</span>
                                                    @if($member->phone)
                                                        <span class="text-slate-300">•</span>
                                                        <span class="text-[10px] font-bold text-indigo-500"><i class="fa-solid fa-phone text-[8px] mr-1"></i>{{ $member->phone }}</span>
                                                    @endif
                                                </div>
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
                
                @if(isset($item->residence_reason) || isset($item->reason) || ($type == 'request' && $item->requests_file))
                <!-- Reason and Attachments Section -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                            <i class="fa-solid fa-pen-to-square text-amber-500"></i> เหตุผลและเอกสารแนบ
                        </h2>
                    </div>
                    <div class="p-8 space-y-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">เหตุผลที่ขอเข้าพัก</p>
                            <div class="bg-amber-50/50 p-6 rounded-2xl border border-amber-100 italic text-amber-900 font-medium leading-relaxed">
                                "{{ $item->residence_reason ?? $item->reason }}"
                            </div>
                        </div>

                        @if($type == 'request' && $item->requests_file)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">เอกสารแนบ</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php $files = json_decode($item->requests_file, true) ?? []; @endphp
                                @forelse($files as $fileName)
                                <a href="{{ asset('uploads/housing_requests/' . $fileName) }}" target="_blank" 
                                   class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100 rounded-2xl border border-slate-100 transition-all group">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 truncate">{{ $fileName }}</span>
                                    </div>
                                    <i class="fa-solid fa-download text-slate-300 group-hover:text-red-500 transition-colors"></i>
                                </a>
                                @empty
                                <p class="text-xs text-slate-400 font-bold italic">ไม่มีเอกสารแนบ</p>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Side: Status Stepper -->
            <div class="space-y-6">
                @php
                    $currentApproverId = null;
                    if ($item->send_status < 3) {
                        if ($type == 'leave') {
                            if ($item->send_status == 0) $currentApproverId = $item->managerhams_id;
                            elseif ($item->send_status == 2) $currentApproverId = $item->Committee_id;
                        } else {
                            if ($item->send_status == 0) $currentApproverId = $item->commander_id;
                            elseif ($item->send_status == 1) $currentApproverId = $item->managerhams_id;
                            elseif ($item->send_status == 2) $currentApproverId = $item->Committee_id;
                        }
                    }
                    $isMyTurn = (Auth::id() == $currentApproverId);
                    $itemId = match($type) {
                        'request' => $item->id,
                        'agreement' => $item->agreement_id,
                        'guest' => $item->resident_guest_id,
                        'leave' => $item->residence_leaves_id,
                        default => $item->id
                    };
                @endphp

                @if($isMyTurn)
                <!-- Admin Approval Action Card -->
                <div class="bg-white rounded-3xl p-8 border-2 border-amber-100 shadow-xl shadow-amber-50/50 relative overflow-hidden group animate-pulse-subtle">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <i class="fa-solid fa-gavel text-4xl text-amber-500"></i>
                    </div>
                    <h3 class="text-amber-600 font-black text-[13px] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation animate-bounce"></i> การพิจารณาของคุณ
                    </h3>
                    
                    <div class="space-y-4">
                        <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'approve', this)"
                            class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-3 group/btn">
                            <i class="fa-solid fa-check-circle text-lg group-hover/btn:scale-110 transition-transform"></i>
                            อนุมัติคำร้องนี้
                        </button>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'correct', this)"
                                class="py-3.5 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-2xl font-bold border border-purple-100 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-rotate-left"></i> ส่งกลับแก้ไข
                            </button>
                            <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'reject', this)"
                                class="py-3.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-2xl font-bold border border-rose-100 transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-xmark"></i> ไม่อนุมัติ
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Current Status Alert Banner (Ensures consistency with Dashboard) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                    <h3 class="text-slate-400 font-bold text-[13px] mb-4">สถานะปัจจุบัน</h3>
                    
                    @php
                        $officialStatus = \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->send_status, $type);
                        $isRejected = ($item->send_status == 5);
                    @endphp

                    <div class="{{ $isRejected ? 'bg-rose-50 border-rose-100' : 'bg-blue-50/50 border-blue-100' }} border rounded-2xl p-6 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full {{ $isRejected ? 'bg-rose-500' : 'bg-blue-500' }} text-white flex items-center justify-center shrink-0 shadow-lg {{ $isRejected ? 'shadow-rose-200' : 'shadow-blue-200' }}">
                            <i class="fa-solid {{ $isRejected ? 'fa-circle-xmark' : 'fa-circle-dot animate-pulse' }}"></i>
                        </div>
                        <span class="font-black {{ $isRejected ? 'text-rose-600' : 'text-blue-600' }} text-[14px] leading-relaxed">
                            {{ $officialStatus }}
                        </span>
                    </div>
                </div>

                @if($item->send_status == 4 && Auth::id() == $item->user_id)
                {{-- Applicant Correction Action Card --}}
                @php
                    $latestCorrectionComment = null;
                    if($item->Committee_status == 2) $latestCorrectionComment = $item->Committee_comment;
                    elseif($item->managerhams_status == 2) $latestCorrectionComment = $item->managerhams_comment;
                    elseif($item->commander_status == 2) $latestCorrectionComment = $item->commander_comment;

                    $editUrl = match ($type) {
                        'request' => route('housing.request.edit', $itemId),
                        'agreement' => route('housing.agreement.edit', $itemId),
                        'guest' => route('housing.guest.edit', $itemId),
                        'leave' => route('housing.leave.edit', $itemId),
                        default => '#'
                    };
                @endphp
                <div class="bg-white rounded-3xl p-8 border-2 border-purple-100 shadow-xl shadow-purple-50/50 relative overflow-hidden group animate-pulse-subtle">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <i class="fa-solid fa-pen-to-square text-4xl text-purple-500"></i>
                    </div>
                    <h3 class="text-purple-600 font-black text-[13px] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i> ข้อมูลที่หัวหน้างาน/ฝ่ายจัดการต้องการให้แก้ไข
                    </h3>
                    
                    @if($latestCorrectionComment)
                    <div class="mb-6 p-4 bg-purple-50/50 rounded-2xl border border-purple-100 text-[13px] text-purple-900 leading-relaxed font-medium">
                        <i class="fa-solid fa-quote-left text-purple-300 mr-2 text-lg"></i>
                        {{ $latestCorrectionComment }}
                    </div>
                    @endif

                    <div class="space-y-4">
                        <a href="{{ $editUrl }}"
                            class="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl font-black shadow-lg shadow-purple-100 transition-all flex items-center justify-center gap-3 group/btn">
                            <i class="fa-solid fa-pen-to-square text-lg group-hover/btn:rotate-12 transition-transform"></i>
                            แก้ไขข้อมูลและส่งใหม่
                        </a>
                        <p class="text-[10px] text-center text-slate-400 font-medium italic">
                            * เมื่อส่งข้อมูลกลับมาใหม่ กระบวนการอนุมัติจะเริ่มต้นใหม่อีกครั้ง
                        </p>
                    </div>
                </div>
                @endif

                <!-- Modern Status Stepper -->
                <div class="bg-white rounded-3xl p-10 border border-slate-100 shadow-xl shadow-slate-100/50 relative overflow-hidden group">
                    <h3 class="text-slate-800 font-black text-xl mb-10 flex items-center gap-3">
                        <div class="w-1.5 h-8 bg-orange-400 rounded-full"></div>
                        สถานะกระบวนการ
                    </h3>

                    <div class="relative space-y-0 pl-2">
                        @php
                            $status = intval($item->send_status);
                            $isRejected = ($status == 5);
                            $isReturned = ($status == 4);
                            
                            $isLeave = ($type === 'leave');
                            $isGuest = ($type === 'guest');
                            $isRequest = ($type === 'request');
                            $isAgreement = ($type === 'agreement');
                            
                            // Step 1: Submission (Always done)
                            
                            // Step 2: Commander (Shifted to Manager for Leave)
                            $step2Done = ($status >= 1 || $isRejected || $isReturned);
                            $step2Active = ($status == 0);
                            
                            // Step 3: Manager (HAMS)
                            $step3Done = ($status >= 2 && !$isRejected && !$isReturned);
                            $step3Active = ($status == 1);
                            
                            // Step 4: Committee / Final Consideration
                            $step4Done = ($status >= 3 && !$isRejected && !$isReturned);
                            $step4Active = ($status == 2);

                            // Step 5: Final Completion (Assignment for Request, Done for others)
                            if ($isRequest) {
                                $step5Done = ($status == 6);
                                $step5Active = ($status == 3 || $status == 7);
                            } else {
                                $step5Done = ($status == 3);
                                $step5Active = false;
                            }
                        @endphp

                        <!-- Dynamic Vertical Line -->
                        <div class="absolute left-[23.5px] top-0 bottom-0 w-[1px] bg-slate-100 z-0"></div>

                        {{-- Step 1: Submission --}}
                        <div class="relative flex gap-6 pb-12 group">
                            <div class="absolute left-[23.5px] top-12 bottom-0 w-[2.5px] bg-emerald-500 z-0"></div>
                            <div class="relative z-10 w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                                <i class="fa-solid fa-paper-plane text-lg"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Step 01</span>
                                <h4 class="text-[15px] font-black text-slate-800 tracking-tight">ยื่นคำร้อง</h4>
                                <p class="text-[11px] font-bold text-slate-400 mt-1">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y | H:i') }} น.</p>
                            </div>
                        </div>

                        {{-- Step 2: Commander --}}
                        <div class="relative flex gap-6 pb-12 group">
                            <div class="absolute left-[23.5px] top-12 bottom-0 w-[2.5px] {{ $step3Done || $step3Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div>
                            @php
                                $s2Color = 'bg-white border-2 border-slate-100 text-slate-300';
                                if ($step2Active) $s2Color = 'bg-white border-4 border-amber-400 text-amber-500 animate-pulse';
                                if ($step2Done) {
                                    $s2Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && $item->commander_status == 2) $s2Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && $item->commander_status == 2) $s2Color = 'bg-purple-500 text-white';
                                }
                            @endphp
                            <div class="relative z-10 w-12 h-12 rounded-full {{ $s2Color }} flex items-center justify-center shadow-lg transition-all">
                                <i class="fa-solid {{ $isLeave ? 'fa-building-user' : 'fa-user-tie' }} text-lg"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Step 02</span>
                                <h4 class="text-[15px] font-black {{ $step2Done ? ($status == 5 ? 'text-rose-600' : ($status == 4 ? 'text-purple-600' : 'text-slate-800')) : 'text-slate-400' }} tracking-tight">
                                    @if($isLeave) ผจก. แผนกฯ @else ผู้บังคับบัญชา @endif
                                </h4>
                                @if($isLeave)
                                    @if($item->managerHams)
                                        <p class="text-[11px] font-bold text-slate-600 mt-0.5">โดย {{ $item->managerHams->fullname }}</p>
                                        <div class="flex gap-2 items-center mt-1">
                                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $item->managerhams_status == 1 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ $item->managerhams_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->managerhams_date) <span class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->managerhams_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[11px] font-bold text-amber-500 mt-0.5">{{ $step2Active ? 'รอฝ่ายจัดการตรวจสอบ...' : ($item->managerhams_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @else
                                    @if($item->commander)
                                        <p class="text-[11px] font-bold text-slate-600 mt-0.5">โดย {{ $item->commander->fullname }}</p>
                                        <div class="flex gap-2 items-center mt-1">
                                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $item->commander_status == 1 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ $item->commander_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->commander_date) <span class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->commander_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                        @if($item->commander_comment)
                                            <div class="mt-2 p-2 bg-slate-50 border-l-2 border-slate-200 rounded text-[10px] text-slate-600 italic">
                                                "{{ $item->commander_comment }}"
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-[11px] font-bold text-amber-500 mt-0.5">{{ $step2Active ? 'รอการพิจารณา...' : ($item->commander_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 3: HAMS Manager / Committee for Leave --}}
                        <div class="relative flex gap-6 pb-12 group">
                            <div class="absolute left-[23.5px] top-12 bottom-0 w-[2.5px] {{ $step4Done || $step4Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div>
                            @php
                                $s3Color = 'bg-white border-2 border-slate-100 text-slate-300';
                                if ($step3Active) $s3Color = 'bg-white border-4 border-amber-400 text-amber-500 animate-pulse';
                                if ($step3Done) {
                                    $s3Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && ($isLeave ? $item->Committee_status == 2 : $item->managerhams_status == 2)) $s3Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && ($isLeave ? $item->Committee_status == 2 : $item->managerhams_status == 2)) $s3Color = 'bg-purple-500 text-white';
                                }
                            @endphp
                            <div class="relative z-10 w-12 h-12 rounded-full {{ $s3Color }} flex items-center justify-center shadow-lg transition-all">
                                <i class="fa-solid {{ $isLeave ? 'fa-users-gear' : 'fa-building-user' }} text-lg"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Step 03</span>
                                <h4 class="text-[15px] font-black {{ $step3Done ? ($status == 5 ? 'text-rose-600' : ($status == 4 ? 'text-purple-600' : 'text-slate-800')) : 'text-slate-400' }} tracking-tight">
                                    @if($isLeave) คณะกรรมการย่อย @else ผจก. แผนกฯ @endif
                                </h4>
                                @if($isLeave)
                                    @if($item->committee)
                                        <p class="text-[11px] font-bold text-slate-600 mt-0.5">โดย {{ $item->committee->fullname }}</p>
                                        <div class="flex gap-2 items-center mt-1">
                                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $item->Committee_status == 1 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ $item->Committee_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->Committee_date) <span class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->Committee_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                        @if($item->Committee_comment)
                                            <div class="mt-2 p-2 bg-slate-50 border-l-2 border-slate-200 rounded text-[10px] text-slate-600 italic">
                                                "{{ $item->Committee_comment }}"
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-[11px] font-bold text-amber-500 mt-0.5">{{ $step3Active ? 'รอมติการพิจารณา...' : ($item->Committee_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @else
                                    @if($item->managerHams)
                                        <p class="text-[11px] font-bold text-slate-600 mt-0.5">โดย {{ $item->managerHams->fullname }}</p>
                                        <div class="flex gap-2 items-center mt-1">
                                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $item->managerhams_status == 1 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ $item->managerhams_status == 1 ? 'เห็นสมควร' : 'REJECTED' }}
                                            </span>
                                            @if($item->managerhams_date) <span class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->managerhams_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[11px] font-bold text-amber-500 mt-0.5">{{ $step3Active ? 'รอฝ่ายจัดการตรวจสอบ...' : ($item->managerhams_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 4: Final Consideration --}}
                        <div class="relative flex gap-6 {{ $isLeave ? '' : 'pb-12' }} group">
                            @if(!$isLeave) <div class="absolute left-[23.5px] top-12 bottom-0 w-[2.5px] {{ $step5Done || $step5Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div> @endif
                            @php
                                $s4Color = 'bg-white border-2 border-slate-100 text-slate-300';
                                if ($step4Active) $s4Color = 'bg-white border-4 border-amber-400 text-amber-500 animate-pulse';
                                if ($step4Done) {
                                    $s4Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && ($isLeave ? false : $item->Committee_status == 2)) $s4Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && ($isLeave ? false : $item->Committee_status == 2)) $s4Color = 'bg-purple-500 text-white';
                                }
                                
                                $s4Icon = $isLeave ? 'fa-circle-check' : 'fa-users-gear';
                                $s4Label = $isLeave ? 'ดำเนินการเสร็จสิ้น' : 'คณะกรรมการย่อย';
                                if ($isGuest) $s4Label = 'ผลการพิจารณา';
                            @endphp
                            <div class="relative z-10 w-12 h-12 rounded-full {{ $step4Done ? ($isLeave ? 'bg-blue-600 text-white shadow-blue-200' : $s4Color) : $s4Color }} flex items-center justify-center shadow-lg transition-all">
                                <i class="fa-solid {{ $s4Icon }} text-lg"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Step 04</span>
                                <h4 class="text-[15px] font-black {{ $step4Done ? 'text-slate-800' : 'text-slate-400' }} tracking-tight">{{ $s4Label }}</h4>
                                @if($isLeave)
                                    @if($step4Done)
                                        <p class="text-[11px] font-bold text-blue-600 mt-0.5">เสร็จสิ้นกระบวนการ</p>
                                    @else
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5 opacity-60 italic">รอดำเนินการ...</p>
                                    @endif
                                @else
                                    @if($item->committee)
                                        <p class="text-[11px] font-bold text-slate-600 mt-0.5">โดย {{ $item->committee->fullname }}</p>
                                        <div class="flex gap-2 items-center mt-1">
                                            <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase {{ $item->Committee_status == 1 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                {{ $item->Committee_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->Committee_date) <span class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->Committee_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[11px] font-bold text-amber-500 mt-0.5">{{ $step4Active ? 'รอมติการพิจารณา...' : ($item->Committee_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 5: Final / Assignment (Only for enrollment) --}}
                        @if(!$isLeave)
                        <div class="relative flex gap-6 group">
                            @php
                                $s5Color = 'bg-white border-2 border-slate-100 text-slate-300';
                                if ($step5Active) $s5Color = 'bg-white border-4 border-blue-400 text-blue-500 animate-pulse';
                                if ($step5Done) $s5Color = 'bg-blue-600 text-white shadow-blue-200';
                                
                                $s5Icon = $isRequest ? 'fa-key' : 'fa-circle-check';
                                $s5Label = $isRequest ? 'มอบหมายห้องพัก' : 'ดำเนินการเสร็จสิ้น';
                                $s5Step = $isRequest ? 'Step 05' : ($isGuest || $isAgreement ? 'Step 05' : 'Step 04');
                            @endphp
                            <div class="relative z-10 w-12 h-12 rounded-full {{ $s5Color }} flex items-center justify-center shadow-lg transition-all">
                                <i class="fa-solid {{ $s5Icon }} text-lg"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[11px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">{{ $s5Step }}</span>
                                <h4 class="text-[15px] font-black {{ $step5Done ? 'text-slate-800' : 'text-slate-400' }} tracking-tight">{{ $s5Label }}</h4>
                                @if($step5Done)
                                    <p class="text-[11px] font-bold text-blue-600 mt-0.5">เสร็จสิ้นกระบวนการ</p>
                                @else
                                    <p class="text-[11px] font-bold text-slate-400 mt-0.5 opacity-60 italic">รอดำเนินการ...</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse-subtle {
            0% { transform: scale(1); }
            50% { transform: scale(1.01); box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.1), 0 8px 10px -6px rgba(245, 158, 11, 0.1); }
            100% { transform: scale(1); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 3s infinite ease-in-out;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.4s ease-out forwards;
        }
    </style>
@endsection
