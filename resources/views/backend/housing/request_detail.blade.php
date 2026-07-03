@extends('layouts.housing.apphousing')
@section('content')
     <style>
        /* Hospital Ledger Table Styling */
        .clinical-table {
            width: 100%;
            border-collapse: collapse;
        }
        .clinical-table th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid #cbd5e1 !important;
            padding: 10px 14px;
        }
        .clinical-table td {
            border: 1px solid #e2e8f0 !important;
            padding: 10px 14px;
            vertical-align: middle;
            font-size: 13px;
            background-color: #ffffff;
        }
        .clinical-table tr:hover td {
            background-color: #f8fafc;
        }

        /* Clinical Status Colors */
        .bg-rose-50 { background-color: #fff1f2 !important; }
        .border-rose-100 { border-color: #ffe4e6 !important; }
        .text-rose-600 { color: #e11d48 !important; }
        .bg-rose-500 { background-color: #f43f5e !important; }

        .bg-blue-50 { background-color: #eff6ff !important; }
        .border-blue-100 { border-color: #dbeafe !important; }
        .text-blue-600 { color: #2563eb !important; }
        .bg-blue-500 { background-color: #3b82f6 !important; }
     </style>

    <div class="max-w-[1200px] mx-auto px-4 py-8 space-y-8 animate-fade-in">
        
        <!-- Premium Header (Clinical Theme) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                    <i class="fa-solid fa-file-invoice text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">
                        @switch($type)
                            @case('request') รายละเอียดคำขอเข้าพักอาศัย @break
                            @case('agreement') รายละเอียดข้อตกลงเข้าพัก @break
                            @case('guest') รายละเอียดคำขอนำญาติเข้าพัก @break
                            @case('leave') รายละเอียดคำร้องขอย้ายออก @break
                        @endswitch
                    </h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
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
                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded font-mono text-xs">{{ $code }}</span>
                        <span>•</span>
                        <span>วันที่ยื่น: {{ \Carbon\Carbon::parse($date)->translatedFormat('d M') }} {{ \Carbon\Carbon::parse($date)->year + 543 }}</span>
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
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
                   class="btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-chevron-left text-slate-400"></i> กลับหน้ารวม
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
                   class="btn bg-red-600 hover:bg-red-700 text-white rounded-xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-file-pdf"></i> พิมพ์ PDF
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Main Info Card -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                        <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle text-red-500"></i> ข้อมูลผู้ยื่นคำขอ
                        </h2>
                    </div>
                    <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($type == 'request' || ($type != 'request' && isset($item->latestReq->site)))
                        <div class="col-span-2 md:col-span-4 border-b border-slate-100 pb-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">สถานที่ปฏิบัติงาน</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->site ?? ($item->latestReq->site ?? 'ไม่ได้ระบุ') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">คำนำหน้า</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->title ?? ($item->latestReq->title ?? (optional($item->user)->prefix ?? '-')) }}</p>
                        </div>
                        
                        @if($type == 'agreement')
                        <div class="col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ชื่อ-นามสกุล</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->full_name ?? ($item->latestReq->first_name . ' ' . $item->latestReq->last_name ?? (optional($item->user)->firstname . ' ' . optional($item->user)->lastname)) }}</p>
                        </div>
                        @else
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ชื่อ</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->first_name ?? ($item->latestReq->first_name ?? (optional($item->user)->firstname ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">นามสกุล</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->last_name ?? ($item->latestReq->last_name ?? (optional($item->user)->lastname ?? '-')) }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">อายุงาน (ปี)</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->age_work ?? ($item->latestReq->age_work ?? '-') }}</p>
                        </div>
                        
                        <div class="col-span-2 md:col-span-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ตำแหน่ง</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->position ?? ($item->latestReq->position ?? (optional($item->user)->position ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">แผนก</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->department ?? ($item->latestReq->department ?? (optional($item->user)->department->name ?? '-')) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ฝ่าย (สังกัด)</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->section ?? ($item->latestReq->section ?? (optional($item->user)->section->section_name ?? '-')) }}</p>
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">หมายเลขโทรศัพท์</p>
                            <p class="text-sm font-bold text-slate-800 font-mono">{{ $item->phone ?? ($item->telephone ?? ($item->phone_user ?? ($item->latestReq->phone ?? (optional($item->user)->phone_user ?? '-')))) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">สถานภาพ</p>
                            <p class="text-sm font-bold text-slate-800">{{ $item->marital_status ?? ($item->status_marriage ?? ($item->latestReq->marital_status ?? (optional($item->user)->status_marriage ?? '-'))) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Specific Request Type Information -->
                @if($type == 'request')
                    <!-- Address Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-map-location-dot text-blue-500"></i> ข้อมูลที่อยู่
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 md:col-span-2 border-b border-slate-100 pb-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ที่อยู่ตามทะเบียนบ้าน</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">บ้านเลขที่ / ที่อยู่</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_original }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">แขวง/ตำบล</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_original_subdistrict ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เขต/อำเภอ</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_original_district ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">จังหวัด</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_original_province ?? '-' }}</p>
                                </div>
                            </div>

                            <hr class="border-slate-200">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 md:col-span-2 border-b border-slate-100 pb-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ที่อยู่ปัจจุบัน</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">บ้านเลขที่ / ที่อยู่</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_current ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">แขวง/ตำบล</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_current_subdistrict ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เขต/อำเภอ</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_current_district ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">จังหวัด</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->address_current_province ?? '-' }}</p>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ลักษณะที่พักอาศัยปัจจุบัน</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $item->current_house_type ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Joint Residents Section -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-people-group text-emerald-500"></i> ผู้พักอาศัยร่วม
                            </h2>
                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded border border-emerald-200 text-xs font-bold">รวม {{ $item->number_of_residents }} คน</span>
                        </div>
                        <div class="p-4">
                            @if($item->dependents->count() > 0)
                            <div class="overflow-x-auto border border-slate-200 rounded-lg">
                                <table class="clinical-table">
                                    <thead>
                                        <tr>
                                            <th class="text-left">ชื่อ-นามสกุล</th>
                                            <th class="text-center" style="width: 100px;">อายุ (ปี)</th>
                                            <th class="text-left" style="width: 150px;">เกี่ยวข้องเป็น</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($item->dependents as $dep)
                                        <tr>
                                            <td class="font-bold text-slate-700">{{ $dep->full_name }}</td>
                                            <td class="text-center font-bold text-slate-500 font-mono">{{ $dep->age }}</td>
                                            <td class="font-bold text-slate-500">{{ $dep->relation }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="p-8 text-center text-slate-400">
                                <i class="fa-solid fa-user-slash text-2xl mb-2 block opacity-30"></i>
                                <p class="text-xs font-bold">ไม่มีผู้พักอาศัยร่วม</p>
                            </div>
                            @endif
                        </div>
                    </div>
                @elseif($type == 'agreement')
                    <!-- Agreement Detail Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-house-chimney-user text-blue-500"></i> รายละเอียดบ้านพัก
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">บ้านพักที่ได้รับมอบหมาย</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->residence_address }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ชั้น</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->residence_floor }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">จำนวนผู้พักอาศัยร่วม</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->number_of_residents }} คน</p>
                            </div>
                        </div>
                    </div>
                @elseif($type == 'guest')
                    <!-- Guest Detail Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-people-arrows text-purple-500"></i> ข้อมูลการนำญาติเข้าพัก
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-slate-100">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ประเภทบ้านพัก</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->residence_type }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เลขที่ห้อง</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->room_number }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ความสัมพันธ์กับพนักงาน</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->relationship }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ช่วงเวลาที่เข้าพัก</p>
                                <p class="text-sm font-bold text-indigo-600 font-mono">
                                    {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-450 mt-0.5">รวมทั้งสิ้น {{ $item->total_days }} วัน</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-wider">รายชื่อผู้อาศัยร่วม</h3>
                            @if($item->members->count() > 0)
                                <div class="space-y-2">
                                    @foreach($item->members as $member)
                                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                                            <div class="w-8 h-8 rounded bg-white flex items-center justify-center text-slate-400 border border-slate-200 shadow-sm shrink-0">
                                                <i class="fa-solid fa-user text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-700">{{ $member->full_name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5 text-[10px] font-bold text-slate-400">
                                                    <span>อายุ: {{ $member->age ?? '-' }} ปี</span>
                                                    <span>•</span>
                                                    <span>เกี่ยวข้องเป็น: {{ $member->relation ?? '-' }}</span>
                                                    @if($member->phone)
                                                        <span>•</span>
                                                        <span class="text-indigo-600 font-mono"><i class="fa-solid fa-phone text-[8px] mr-0.5"></i>{{ $member->phone }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-slate-450 font-bold italic">ไม่ระบุรายชื่อ</p>
                            @endif
                        </div>
                    </div>
                @elseif($type == 'leave')
                    <!-- Leave Detail Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-right-from-bracket text-orange-500"></i> ข้อมูลการความประสงค์ย้ายออก
                            </h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ประเภทบ้านพัก</p>
                                <p class="text-sm font-bold text-slate-800">{{ $item->residence_type }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เลขที่ห้อง / ชั้น</p>
                                <p class="text-sm font-bold text-slate-800">ห้อง {{ $item->room_number }} @if($item->floor) ชั้น {{ $item->floor }} @endif</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">วันที่ต้องการย้ายออก</p>
                                <p class="text-sm font-bold text-orange-600 font-mono">
                                    {{ \Carbon\Carbon::parse($item->move_out_date)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(isset($item->residence_reason) || isset($item->reason) || ($type == 'request' && $item->requests_file))
                <!-- Reason and Attachments Section -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-5 border-b border-slate-200 bg-slate-50/50">
                        <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-amber-500"></i> เหตุผลและเอกสารแนบ
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">เหตุผลที่ขอเข้าพัก</p>
                            <div class="bg-amber-50/50 p-4 rounded-lg border border-amber-200 italic text-amber-900 font-medium leading-relaxed text-xs">
                                "{{ $item->residence_reason ?? $item->reason }}"
                            </div>
                        </div>

                        @if($type == 'request' && $item->requests_file)
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">เอกสารแนบ</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php $files = json_decode($item->requests_file, true) ?? []; @endphp
                                @forelse($files as $fileName)
                                <a href="{{ asset('uploads/housing_requests/' . $fileName) }}" target="_blank" 
                                   class="flex items-center justify-between p-3 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 transition-all group">
                                    <div class="flex items-center gap-2.5 overflow-hidden">
                                        <div class="w-7 h-7 rounded bg-red-50 flex items-center justify-center text-red-500 border border-red-100 shrink-0">
                                            <i class="fa-solid fa-file-pdf text-xs"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-600 truncate">{{ $fileName }}</span>
                                    </div>
                                    <i class="fa-solid fa-download text-slate-300 group-hover:text-red-600 transition-colors text-xs shrink-0"></i>
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
                <div class="bg-white rounded-xl p-6 border border-amber-250 shadow-sm relative overflow-hidden group">
                    <h3 class="text-amber-600 font-black text-xs mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation animate-bounce"></i> การพิจารณาของคุณ
                    </h3>
                    
                    <div class="space-y-3">
                        <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'approve', this)"
                            class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-black shadow-sm transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-check-circle text-base"></i> อนุมัติคำร้องนี้
                        </button>

                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'correct', this)"
                                class="py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg font-bold border border-purple-200 transition-all flex items-center justify-center gap-1.5 text-xs">
                                <i class="fa-solid fa-rotate-left"></i> ส่งกลับแก้ไข
                            </button>
                            <button type="button" onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'reject', this)"
                                class="py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg font-bold border border-rose-200 transition-all flex items-center justify-center gap-1.5 text-xs">
                                <i class="fa-solid fa-circle-xmark"></i> ไม่อนุมัติ
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Current Status Alert Banner -->
                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <h3 class="text-slate-400 font-bold text-xs mb-3">สถานะปัจจุบัน</h3>
                    
                    @php
                        $officialStatus = \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->send_status, $type);
                        $isRejected = ($item->send_status == 5);
                    @endphp

                    <div class="{{ $isRejected ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-blue-50/50 border-blue-200 text-blue-755' }} border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $isRejected ? 'bg-rose-500' : 'bg-blue-500' }} text-white flex items-center justify-center shrink-0 shadow-sm">
                            <i class="fa-solid {{ $isRejected ? 'fa-circle-xmark' : 'fa-circle-dot animate-pulse' }} text-sm"></i>
                        </div>
                        <span class="font-bold text-sm leading-relaxed">
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
                <div class="bg-white rounded-xl p-6 border border-purple-200 shadow-sm relative overflow-hidden">
                    <h3 class="text-purple-600 font-black text-xs mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info"></i> ข้อมูลที่ต้องการให้แก้ไข
                    </h3>
                    
                    @if($latestCorrectionComment)
                    <div class="mb-4 p-3 bg-purple-50/50 rounded-lg border border-purple-100 text-xs text-purple-900 leading-relaxed font-medium">
                        <i class="fa-solid fa-quote-left text-purple-300 mr-1.5"></i>
                        {{ $latestCorrectionComment }}
                    </div>
                    @endif

                    <div class="space-y-3">
                        <a href="{{ $editUrl }}"
                            class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-black shadow-sm transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="fa-solid fa-pen-to-square"></i> แก้ไขข้อมูลและส่งใหม่
                        </a>
                        <p class="text-[9px] text-center text-slate-400 font-medium">
                            * เมื่อส่งข้อมูลกลับมาใหม่ กระบวนการอนุมัติจะเริ่มต้นใหม่อีกครั้ง
                        </p>
                    </div>
                </div>
                @endif

                <!-- Modern Status Stepper (Clinical Variant) -->
                <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
                    <h3 class="text-slate-800 font-black text-base mb-6 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <div class="w-1 h-5 bg-orange-500 rounded-full"></div>
                        สถานะกระบวนการ
                    </h3>

                    <div class="relative space-y-0 pl-1">
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
                        <div class="absolute left-[19.5px] top-0 bottom-0 w-[1px] bg-slate-200 z-0"></div>

                        {{-- Step 1: Submission --}}
                        <div class="relative flex gap-4 pb-8 group">
                            <div class="absolute left-[19.5px] top-10 bottom-0 w-[2px] bg-emerald-500 z-0"></div>
                            <div class="relative z-10 w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-black text-slate-350 uppercase tracking-wider mb-0.5">Step 01</span>
                                <h4 class="text-sm font-black text-slate-800 tracking-tight">ยื่นคำร้อง</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 font-mono">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y | H:i') }} น.</p>
                            </div>
                        </div>

                        {{-- Step 2: Commander --}}
                        <div class="relative flex gap-4 pb-8 group">
                            <div class="absolute left-[19.5px] top-10 bottom-0 w-[2px] {{ $step3Done || $step3Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div>
                            @php
                                $s2Color = 'bg-white border border-slate-200 text-slate-300';
                                if ($step2Active) $s2Color = 'bg-white border-2 border-amber-400 text-amber-500';
                                if ($step2Done) {
                                    $s2Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && $item->commander_status == 2) $s2Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && $item->commander_status == 2) $s2Color = 'bg-purple-500 text-white';
                                }
                            @endphp
                            <div class="relative z-10 w-10 h-10 rounded-full {{ $s2Color }} flex items-center justify-center shadow-sm">
                                <i class="fa-solid {{ $isLeave ? 'fa-building-user' : 'fa-user-tie' }} text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-black text-slate-350 uppercase tracking-wider mb-0.5">Step 02</span>
                                <h4 class="text-sm font-black {{ $step2Done ? ($status == 5 ? 'text-rose-600' : ($status == 4 ? 'text-purple-600' : 'text-slate-800')) : 'text-slate-400' }} tracking-tight">
                                    @if($isLeave) ผจก. แผนกฯ @else ผู้บังคับบัญชา @endif
                                </h4>
                                @if($isLeave)
                                    @if($item->managerHams)
                                        <p class="text-[10px] font-bold text-slate-650 mt-0.5">โดย {{ $item->managerHams->fullname }}</p>
                                        <div class="flex gap-1.5 items-center mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $item->managerhams_status == 1 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                                {{ $item->managerhams_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->managerhams_date) <span class="text-[9px] text-slate-450 font-mono">{{ \Carbon\Carbon::parse($item->managerhams_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $step2Active ? 'รอฝ่ายจัดการตรวจสอบ...' : ($item->managerhams_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @else
                                    @if($item->commander)
                                        <p class="text-[10px] font-bold text-slate-650 mt-0.5">โดย {{ $item->commander->fullname }}</p>
                                        <div class="flex gap-1.5 items-center mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $item->commander_status == 1 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                                {{ $item->commander_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->commander_date) <span class="text-[9px] text-slate-450 font-mono">{{ \Carbon\Carbon::parse($item->commander_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                        @if($item->commander_comment)
                                            <div class="mt-2 p-2 bg-slate-50 border-l border-slate-300 rounded text-[10px] text-slate-600 italic">
                                                "{{ $item->commander_comment }}"
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $step2Active ? 'รอการพิจารณา...' : ($item->commander_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 3: HAMS Manager / Committee for Leave --}}
                        <div class="relative flex gap-4 pb-8 group">
                            <div class="absolute left-[19.5px] top-10 bottom-0 w-[2px] {{ $step4Done || $step4Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div>
                            @php
                                $s3Color = 'bg-white border border-slate-200 text-slate-300';
                                if ($step3Active) $s3Color = 'bg-white border-2 border-amber-400 text-amber-500';
                                if ($step3Done) {
                                    $s3Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && ($isLeave ? $item->Committee_status == 2 : $item->managerhams_status == 2)) $s3Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && ($isLeave ? $item->Committee_status == 2 : $item->managerhams_status == 2)) $s3Color = 'bg-purple-500 text-white';
                                }
                            @endphp
                            <div class="relative z-10 w-10 h-10 rounded-full {{ $s3Color }} flex items-center justify-center shadow-sm">
                                <i class="fa-solid {{ $isLeave ? 'fa-users-gear' : 'fa-building-user' }} text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-black text-slate-350 uppercase tracking-wider mb-0.5">Step 03</span>
                                <h4 class="text-sm font-black {{ $step3Done ? ($status == 5 ? 'text-rose-600' : ($status == 4 ? 'text-purple-600' : 'text-slate-800')) : 'text-slate-400' }} tracking-tight">
                                    @if($isLeave) คณะกรรมการย่อย @else ผจก. แผนกฯ @endif
                                </h4>
                                @if($isLeave)
                                    @if($item->committee)
                                        <p class="text-[10px] font-bold text-slate-650 mt-0.5">โดย {{ $item->committee->fullname }}</p>
                                        <div class="flex gap-1.5 items-center mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $item->Committee_status == 1 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                                {{ $item->Committee_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->Committee_date) <span class="text-[9px] text-slate-450 font-mono">{{ \Carbon\Carbon::parse($item->Committee_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                        @if($item->Committee_comment)
                                            <div class="mt-2 p-2 bg-slate-50 border-l border-slate-300 rounded text-[10px] text-slate-600 italic">
                                                "{{ $item->Committee_comment }}"
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $step3Active ? 'รอมติการพิจารณา...' : ($item->Committee_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @else
                                    @if($item->managerHams)
                                        <p class="text-[10px] font-bold text-slate-650 mt-0.5">โดย {{ $item->managerHams->fullname }}</p>
                                        <div class="flex gap-1.5 items-center mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $item->managerhams_status == 1 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                                {{ $item->managerhams_status == 1 ? 'เห็นสมควร' : 'REJECTED' }}
                                            </span>
                                            @if($item->managerhams_date) <span class="text-[9px] text-slate-450 font-mono">{{ \Carbon\Carbon::parse($item->managerhams_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $step3Active ? 'รอฝ่ายจัดการตรวจสอบ...' : ($item->managerhams_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 4: Final Consideration --}}
                        <div class="relative flex gap-4 {{ $isLeave ? '' : 'pb-8' }} group">
                            @if(!$isLeave) <div class="absolute left-[19.5px] top-10 bottom-0 w-[2px] {{ $step5Done || $step5Active ? 'bg-emerald-500' : 'bg-slate-200' }} z-0"></div> @endif
                            @php
                                $s4Color = 'bg-white border border-slate-200 text-slate-300';
                                if ($step4Active) $s4Color = 'bg-white border-2 border-amber-400 text-amber-500';
                                if ($step4Done) {
                                    $s4Color = 'bg-emerald-500 text-white';
                                    if ($status == 5 && ($isLeave ? false : $item->Committee_status == 2)) $s4Color = 'bg-rose-500 text-white';
                                    if ($status == 4 && ($isLeave ? false : $item->Committee_status == 2)) $s4Color = 'bg-purple-500 text-white';
                                }
                                
                                $s4Icon = $isLeave ? 'fa-circle-check' : 'fa-users-gear';
                                $s4Label = $isLeave ? 'ดำเนินการเสร็จสิ้น' : 'คณะกรรมการย่อย';
                                if ($isGuest) $s4Label = 'ผลการพิจารณา';
                            @endphp
                            <div class="relative z-10 w-10 h-10 rounded-full {{ $step4Done ? ($isLeave ? 'bg-blue-600 text-white shadow-blue-200' : $s4Color) : $s4Color }} flex items-center justify-center shadow-sm">
                                <i class="fa-solid {{ $s4Icon }} text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-black text-slate-350 uppercase tracking-wider mb-0.5">Step 04</span>
                                <h4 class="text-sm font-black {{ $step4Done ? 'text-slate-800' : 'text-slate-400' }} tracking-tight">{{ $s4Label }}</h4>
                                @if($isLeave)
                                    @if($step4Done)
                                        <p class="text-[10px] font-bold text-blue-600 mt-0.5">เสร็จสิ้นกระบวนการ</p>
                                    @else
                                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 italic">รอดำเนินการ...</p>
                                    @endif
                                @else
                                    @if($item->committee)
                                        <p class="text-[10px] font-bold text-slate-650 mt-0.5">โดย {{ $item->committee->fullname }}</p>
                                        <div class="flex gap-1.5 items-center mt-1">
                                            <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase {{ $item->Committee_status == 1 ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                                {{ $item->Committee_status == 1 ? 'APPROVED' : 'REJECTED' }}
                                            </span>
                                            @if($item->Committee_date) <span class="text-[9px] text-slate-450 font-mono">{{ \Carbon\Carbon::parse($item->Committee_date)->format('d/m/Y') }}</span> @endif
                                        </div>
                                    @else
                                        <p class="text-[10px] font-bold text-amber-500 mt-0.5">{{ $step4Active ? 'รอมติการพิจารณา...' : ($item->Committee_id ? 'ไม่พบข้อมูลผู้ใช้' : '-') }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- Step 5: Final / Assignment (Only for enrollment) --}}
                        @if(!$isLeave)
                        <div class="relative flex gap-4 group">
                            @php
                                $s5Color = 'bg-white border border-slate-200 text-slate-300';
                                if ($step5Active) $s5Color = 'bg-white border-2 border-blue-400 text-blue-500';
                                if ($step5Done) $s5Color = 'bg-blue-600 text-white shadow-blue-200';
                                
                                $s5Icon = $isRequest ? 'fa-key' : 'fa-circle-check';
                                $s5Label = $isRequest ? 'มอบหมายห้องพัก' : 'ดำเนินการเสร็จสิ้น';
                                $s5Step = $isRequest ? 'Step 05' : ($isGuest || $isAgreement ? 'Step 05' : 'Step 04');
                            @endphp
                            <div class="relative z-10 w-10 h-10 rounded-full {{ $s5Color }} flex items-center justify-center shadow-sm">
                                <i class="fa-solid {{ $s5Icon }} text-sm"></i>
                            </div>
                            <div class="flex flex-col justify-center">
                                <span class="text-[10px] font-black text-slate-350 uppercase tracking-wider mb-0.5">{{ $s5Step }}</span>
                                <h4 class="text-sm font-black {{ $step5Done ? 'text-slate-800' : 'text-slate-400' }} tracking-tight">{{ $s5Label }}</h4>
                                @if($step5Done)
                                    <p class="text-[10px] font-bold text-blue-600 mt-0.5">เสร็จสิ้นกระบวนการ</p>
                                @else
                                    <p class="text-[10px] font-bold text-slate-400 mt-0.5 italic">รอดำเนินการ...</p>
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
            50% { transform: scale(1.01); box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.05); }
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
