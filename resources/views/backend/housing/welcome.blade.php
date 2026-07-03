@extends('layouts.housing.apphousing')

@section('title', 'ระบบจัดการบ้านพักพนักงาน')

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
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">

        <!-- New Premium Header (Clinical Theme) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                    <i class="fa-solid fa-house-chimney text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">ระบบจัดการบ้านพักพนักงาน (EMPLOYEE HOUSING)</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-hotel text-blue-500"></i>
                        ฐานข้อมูลอาคาร ห้องพักพนักงาน และคำขอเข้าพักอาศัย
                    </p>
                </div>
            </div>
            @if(Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16])))
                <div class="w-full md:w-auto">
                    <a href="{{ route('housing.management') }}" 
                        class="btn bg-slate-800 hover:bg-slate-900 text-white border-0 shadow-lg rounded-2xl px-6 transition-all hover:scale-105 active:scale-95 text-xs sm:text-sm h-11 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-chart-line text-red-400"></i> MANAGEMENT DASHBOARD
                    </a>
                </div>
            @endif
        </div>

        {{-- Next Step Notification Alert (Hospital Record Alert Card) --}}
        @if($userActiveRequest)
            @php
                $status = $userActiveRequest->send_status;
                $config = match ($status) {
                    0, 1, 2 => [
                        'icon' => 'fa-hourglass-half',
                        'color' => 'amber',
                        'border' => 'border-amber-200',
                        'bg' => 'bg-amber-50/20',
                        'text' => 'text-amber-800',
                        'title' => 'กำลังอยู่ระหว่างการพิจารณา (UNDER REVIEW)',
                        'desc' => 'คำขอหมายเลข <strong class="font-mono text-slate-800">' . $userActiveRequest->requests_code . '</strong> กำลังอยู่ในกระบวนการตรวจสอบเอกสารและสิทธิ์ของผู้เข้าพัก ขั้นตอนถัดไป: รอการอนุมัติจากผู้บังคับบัญชาตามลำดับขั้น',
                        'btn' => 'ติดตามผลตรวจ',
                        'route' => route('housing.my_requests')
                    ],
                    3 => [
                        'icon' => 'fa-circle-check',
                        'color' => 'emerald',
                        'border' => 'border-emerald-200',
                        'bg' => 'bg-emerald-50/20',
                        'text' => 'text-emerald-800',
                        'title' => 'ผ่านการอนุมัติขั้นแรกแล้ว (APPROVED)',
                        'desc' => 'คำขอเข้าพักของคุณผ่านการอนุมัติแล้ว ขั้นตอนถัดไป: รอเจ้าหน้าที่ประสานงานมอบหมายจัดสรรห้องพักที่เหมาะสมให้คุณ',
                        'btn' => 'ดูข้อมูลบันทึก',
                        'route' => route('housing.my_requests')
                    ],
                    4 => [
                        'icon' => 'fa-circle-exclamation',
                        'color' => 'rose',
                        'border' => 'border-rose-200',
                        'bg' => 'bg-rose-50/20',
                        'text' => 'text-rose-800',
                        'title' => 'คำขอถูกส่งกลับเพื่อแก้ไขข้อมูล (ACTION REQUIRED)',
                        'desc' => 'พบข้อผิดพลาดหรือข้อมูลไม่ครบถ้วนในคำขอหมายเลข <strong class="font-mono text-slate-800">' . $userActiveRequest->requests_code . '</strong> กรุณาตรวจสอบบันทึกข้อความและทำการแก้ไขความถูกต้อง',
                        'btn' => 'แก้ไขคำขอ',
                        'route' => route('housing.my_requests')
                    ],
                    7 => ($pendingAgreement) ? [
                        'icon' => 'fa-hourglass-half',
                        'color' => 'sky',
                        'border' => 'border-sky-200',
                        'bg' => 'bg-sky-50/20',
                        'text' => 'text-sky-800',
                        'title' => 'รอกรรมการลงนามตรวจสอบสัญญา (AGREEMENT PROCESSING)',
                        'desc' => 'ระบบได้รับแบบฟอร์มข้อตกลงฯ เรียบร้อยแล้ว ขั้นตอนถัดไป: รอการอนุมัติสัญญาอย่างเป็นทางการจากคณะกรรมการบริหารงานบุคคล',
                        'btn' => 'ตรวจสอบสถานะสัญญา',
                        'route' => route('housing.my_requests') . '?tab=agreements'
                    ] : [
                        'icon' => 'fa-file-signature',
                        'color' => 'red',
                        'border' => 'border-red-200',
                        'bg' => 'bg-red-50/20',
                        'text' => 'text-red-800',
                        'title' => 'ขั้นตอนสุดท้าย: ลงนามสัญญาข้อตกลงเข้าพัก (AGREEMENT REQUIREMENT)',
                        'desc' => 'ห้องพักของคุณได้รับการอนุมัติจัดสรรแล้ว! กรุณากรอกแบบฟอร์มข้อตกลงการเข้าพักอาศัย (QF-HAMS-03) เพื่อออกสัญญาข้อตกลงและรับกุญแจห้องพัก',
                        'btn' => 'กรอกแบบฟอร์มข้อตกลง',
                        'route' => route('housing.agreement.create')
                    ],
                    default => null
                };
            @endphp

            @if($config)
                <div class="max-w-5xl mx-auto mb-8">
                    <div class="bg-white rounded-2xl border-l-4 {{ $config['border'] }} shadow-md p-5 flex flex-col sm:flex-row items-center gap-5 justify-between bg-white relative overflow-hidden border">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl {{ $config['bg'] }} flex items-center justify-center {{ $config['text'] }} shadow-inner shrink-0">
                                <i class="fa-solid {{ $config['icon'] }} text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 tracking-tight">{{ $config['title'] }}</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{!! $config['desc'] !!}</p>
                            </div>
                        </div>
                        <a href="{{ $config['route'] }}"
                            class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2 whitespace-nowrap">
                            {{ $config['btn'] }} <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @endif
        @endif

        {{-- Residence Cards Section (Clinical Double-Border Design) --}}
        <div class="max-w-8xl mx-auto mb-6">
            <div class="text-center mb-6">
                {{-- <h3 class="text-lg font-black text-slate-800 uppercase tracking-widest">
                    อาคารที่พักพนักงาน (AVAILABLE BUILDINGS & RESIDENCES)
                </h3> --}}
                {{-- <div class="w-16 h-0.5 bg-red-500 mx-auto mt-2 rounded-full"></div> --}}
            </div>
            
            <div class="flex flex-col gap-6 max-w-8xl mx-auto px-4">
                @foreach($residences as $index => $res)
                    @php
                        $images = [
                            'images/housing/residence_bangyai.png',
                            'images/housing/residence_saiyai.png',
                        ];
                        $imgPath = $res->cover_image ? $res->cover_image : ($images[$index] ?? $images[0]);
                        $availCount = $res->rooms->where('residence_room_status', 0)->count();
                        $totalCount = $res->rooms->count();
                    @endphp
                    <div class="group bg-slate-50 rounded-1xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md hover:border-slate-300 transition-all duration-300 flex flex-col md:flex-row p-3 gap-5 w-full">
                        
                        {{-- Image area --}}
                        <div class="relative h-60 md:h-auto md:w-[40%] shrink-0 overflow-hidden border border-slate-200/60 bg-slate-100 flex items-center justify-center">
                            <img src="{{ asset($imgPath) }}" alt="{{ $res->name }}"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 via-transparent to-transparent pointer-events-none"></div>

                            {{-- Status badge --}}
                            <div class="absolute top-3 left-3 md:top-4 md:left-4">
                                @if($availCount > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 backdrop-blur-md bg-white/90 shadow-sm">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> มีห้องว่าง: {{ $availCount }} ห้อง
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 backdrop-blur-md bg-white/90 shadow-sm">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> ห้องเต็มแล้ว
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content area --}}
                        <div class="flex flex-col flex-1 py-2 md:py-4 pr-2 md:pr-4">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight transition-colors group-hover:text-red-700">
                                        อาคารบ้านพักสวัสดิการพนักงาน: {{ $res->name }}
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        อาคารที่พักพนักงานของบริษัท สำหรับอำนวยความสะดวกในการพักอาศัย
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                    <span class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-200 text-xs font-semibold px-3 py-2 rounded-xl">
                                        <div class="w-6 h-6 rounded-md bg-sky-100 flex items-center justify-center"><i class="fa-solid fa-layer-group text-sky-600"></i></div>
                                        จำนวนชั้น: {{ $res->total_floors }} ชั้น
                                    </span>
                                    <span class="inline-flex items-center gap-2 bg-white text-slate-700 border border-slate-200 text-xs font-semibold px-3 py-2 rounded-xl">
                                        <div class="w-6 h-6 rounded-md bg-purple-100 flex items-center justify-center"><i class="fa-solid fa-door-open text-purple-600"></i></div>
                                        ห้องพักรวม: {{ $totalCount }} ห้อง
                                    </span>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row gap-3 md:mt-auto">
                                <a href="{{ route('housing.residence.info', $res->residence_id) }}" 
                                    class="flex-1 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 py-2.5 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold transition-all shadow-sm">
                                    <i class="fa-solid fa-circle-info text-slate-400"></i> รายละเอียดห้องพัก
                                </a>
                                @php
                                    $user = auth()->user();
                                    $isHams = $user && $user->is_hams_admin;
                                @endphp
                                @if($isHams)
                                    <a href="{{ route('housing.houselist', ['residence_id' => $res->residence_id]) }}" 
                                        class="flex-1 bg-red-600 text-white hover:bg-red-700 py-2.5 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold transition-all shadow-sm">
                                        รายการห้องพัก <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @else
                                    <a href="{{ route('housing.request.create', ['site' => $res->name]) }}" 
                                        class="flex-1 bg-red-600 text-white hover:bg-red-700 py-2.5 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold transition-all shadow-sm">
                                        ยื่นคำร้องขอเข้าพัก <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions & Forms (Hospital Admin Section) --}}
        <div class="max-w-6xl mx-auto mt-12 bg-slate-50 border border-slate-200 rounded-[2.5rem] p-6 lg:p-8">
            <div class="text-center mb-6">
                <h4 class="text-xs font-black text-slate-500 tracking-[0.25em] uppercase">เอกสารและแบบฟอร์มขอรับบริการ (ADMINISTRATIVE FORMS)</h4>
                <p class="text-sm font-bold text-slate-850 mt-1">กรอกใบคำร้องหรือทำธุรกรรมประเมินเข้าพักและส่งคืนบ้านพักสวัสดิการ</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card action 1 -->
                <a href="{{ route('housing.request.create') }}"
                    class="group bg-white rounded-3xl border border-slate-200 p-5 flex flex-col justify-between min-h-[160px] shadow-sm hover:shadow-lg hover:border-red-400 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-file-circle-plus"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-black text-slate-800 group-hover:text-red-600 transition-colors">ใบคำขอเข้าพัก</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">QF-HAMS-02 • ขอเข้าพัก</p>
                    </div>
                </a>

                <!-- Card action 2 -->
                <a href="{{ route('housing.agreement.create') }}"
                    class="group bg-white rounded-3xl border border-slate-200 p-5 flex flex-col justify-between min-h-[160px] shadow-sm hover:shadow-lg hover:border-sky-400 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-black text-slate-800 group-hover:text-sky-600 transition-colors">หนังสือข้อตกลงเข้าพัก</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">QF-HAMS-03 • สัญญาข้อตกลง</p>
                    </div>
                </a>

                <!-- Card action 3 -->
                <a href="{{ route('housing.guest.create') }}"
                    class="group bg-white rounded-3xl border border-slate-200 p-5 flex flex-col justify-between min-h-[160px] shadow-sm hover:shadow-lg hover:border-purple-400 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-people-arrows"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-black text-slate-800 group-hover:text-purple-600 transition-colors">ขอนำบุคคลอื่นเข้าพัก</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">QF-HAMS-05 • นำญาติเข้าพัก</p>
                    </div>
                </a>

                <!-- Card action 4 -->
                <a href="{{ route('housing.leave.create') }}"
                    class="group bg-white rounded-3xl border border-slate-200 p-5 flex flex-col justify-between min-h-[160px] shadow-sm hover:shadow-lg hover:border-orange-400 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-lg shadow-inner group-hover:scale-110 transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-black text-slate-800 group-hover:text-orange-600 transition-colors">คำร้องขอย้ายออกจากห้องพัก</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-1">Move-out Request</p>
                    </div>
                </a>

            </div>
        </div>

    </div>
@endsection