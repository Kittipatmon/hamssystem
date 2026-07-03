@extends('layouts.housing.apphousing')

@section('title', 'รายการบ้านพัก')

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
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn space-y-8">

        <!-- New Premium Header (Clinical Theme) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                    <i class="fa-solid fa-list-check text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">รายการบ้านพักพนักงาน</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        ตรวจสอบรายชื่อผู้เข้าพักอาศัย ค้นหาตำแหน่งห้องพัก และห้องว่างซ่อมบำรุง
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]) || Auth::user()->is_hams_editor)
                    <a href="{{ route('housing.residence.create') }}" 
                        class="btn bg-red-600 hover:bg-red-700 text-white rounded-2xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2 border-0 shadow-sm transition-all hover:scale-102 active:scale-95">
                        <i class="fa-solid fa-square-plus"></i> เพิ่มอาคารและห้องพัก
                    </a>
                @endif
                <a href="{{ route('housing.welcome') }}" 
                    class="btn btn-ghost border-slate-200 text-slate-600 hover:bg-slate-100 rounded-2xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2">
                    <i class="fa-solid fa-chevron-left"></i> ย้อนกลับ
                </a>
            </div>
        </div>

        <!-- Filters & Legend (Clinical Panels) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Filters Panel -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col md:flex-row lg:flex-col gap-4 lg:col-span-2">
                <h4 class="text-xs font-black text-slate-500 uppercase tracking-wider mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-filter text-sky-500"></i> ตัวกรองข้อมูลทำเนียบ (FILTERS)
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                    <div class="form-control">
                        <label class="label pb-1"><span class="label-text font-bold text-slate-700">เลือกพื้นที่โครงการ:</span></label>
                        <select id="filterSite" onchange="filterRooms()"
                            class="select select-bordered select-sm rounded-xl border-slate-300 focus:border-red-500 transition-all font-bold text-slate-700 h-10">
                            <option value="all">ทุกพื้นที่โครงการ</option>
                            @foreach($residences as $res)
                                <option value="{{ $res->residence_id }}">{{ $res->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label pb-1"><span class="label-text font-bold text-slate-700">ระดับชั้นห้องพัก:</span></label>
                        <select id="filterFloor" onchange="filterRooms()"
                            class="select select-bordered select-sm rounded-xl border-slate-300 focus:border-red-500 transition-all font-bold text-slate-700 h-10">
                            <option value="all">ทุกระดับชั้น</option>
                            @php
                                $allFloors = $residences->flatMap(fn($r) => $r->rooms->pluck('floor'))->unique()->sort();
                            @endphp
                            @foreach($allFloors as $f)
                                <option value="{{ $f }}">ระดับชั้นที่ {{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Legend Panel -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                <h4 class="text-xs font-black text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-question text-purple-500"></i> คำอธิบายสีสถานะ (LEGEND)
                </h4>
                <div class="space-y-2.5">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 bg-emerald-50/50 p-2 rounded-lg border border-emerald-100">
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 inline-block border-2 border-white shadow-sm"></span> 
                            <span>ห้องว่างพร้อมให้บริการ (AVAILABLE)</span>
                        </span>
                        <span class="font-mono text-emerald-700 font-black">{{ $availableRooms }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 bg-rose-50/50 p-2 rounded-lg border border-rose-100">
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-rose-500 inline-block border-2 border-white shadow-sm"></span> 
                            <span>มีผู้เข้าพักอาศัยแล้ว (OCCUPIED)</span>
                        </span>
                        <span class="font-mono text-rose-700 font-black">{{ $occupiedRooms }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-bold text-slate-700 bg-amber-50/50 p-2 rounded-lg border border-amber-100">
                        <span class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-amber-500 inline-block border-2 border-white shadow-sm"></span> 
                            <span>งดให้บริการชั่วคราว/ซ่อมแซม (MAINTENANCE)</span>
                        </span>
                        <span class="font-mono text-amber-700 font-black">{{ $maintenanceRooms }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Clinical KPI Summary Banner --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid fa-square-poll-horizontal"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-wider mb-0.5">จำนวนห้องพักทั้งหมด</p>
                    <p class="text-xl font-mono font-black text-slate-800">{{ $totalRooms }} <span class="text-xs font-bold text-slate-500">ห้อง</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-emerald-700 text-[9px] font-black uppercase tracking-wider mb-0.5">สถานะห้องว่างรวม</p>
                    <p class="text-xl font-mono font-black text-slate-800">{{ $availableRooms }} <span class="text-xs font-bold text-slate-500">ห้อง</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid fa-house-user"></i>
                </div>
                <div>
                    <p class="text-rose-700 text-[9px] font-black uppercase tracking-wider mb-0.5">จำนวนผู้เข้าพักรวม</p>
                    <p class="text-xl font-mono font-black text-slate-800">{{ $occupiedRooms }} <span class="text-xs font-bold text-slate-500">ห้อง</span></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shadow-inner">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <p class="text-amber-700 text-[9px] font-black uppercase tracking-wider mb-0.5">ห้องปิดปรับปรุง/ชำรุด</p>
                    <p class="text-xl font-mono font-black text-slate-800">{{ $maintenanceRooms }} <span class="text-xs font-bold text-slate-500">ห้อง</span></p>
                </div>
            </div>
        </div>

        {{-- Room Grid by Residence (Hospital Layout) --}}
        <div class="space-y-10">
            @foreach($residences as $res)
                <div class="residence-block bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-md"
                    data-residence-id="{{ $res->residence_id }}">

                    {{-- Residence Header Bar --}}
                    <div class="bg-slate-800 px-6 py-4 border-b border-slate-300 flex justify-between items-center text-white">
                        <h3 class="font-black flex items-center gap-2.5 text-sm uppercase tracking-wider">
                            <i class="fa-solid fa-building text-sky-400"></i>
                            อาคารบ้านพักสวัสดิการพนักงาน: {{ $res->name }}
                            @if($res->blueprint_image)
                                <button type="button" onclick="openBlueprintModal('{{ asset($res->blueprint_image) }}', '{{ addslashes($res->name) }}')"
                                    class="text-slate-400 hover:text-sky-400 transition-colors ml-2 cursor-pointer focus:outline-none" title="ดูแผนผังรายละเอียดอาคาร">
                                    <i class="fa-solid fa-map text-xs text-sky-400"></i>
                                </button>
                            @endif
                            @if(Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]) || Auth::user()->is_hams_editor)
                                <a href="{{ route('housing.residence.edit', $res->residence_id) }}" 
                                    class="text-slate-400 hover:text-sky-400 transition-colors ml-2" title="แก้ไขข้อมูลอาคาร">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <button type="button" onclick="confirmDeleteResidence('{{ $res->residence_id }}', '{{ addslashes($res->name) }}')"
                                    class="text-slate-400 hover:text-rose-500 transition-colors ml-2 cursor-pointer focus:outline-none bg-transparent border-0 p-0" style="vertical-align: middle;" title="ลบอาคารและห้องพักทั้งหมด">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                                <form id="delete-residence-form-{{ $res->residence_id }}" action="{{ route('housing.destroy', ['type' => 'residence', 'id' => $res->residence_id]) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </h3>
                        <span class="text-[9px] font-black bg-slate-700 text-sky-400 px-3 py-1 rounded-full border border-slate-600">
                            {{ $res->rooms->count() }} ห้องพักทั้งหมด
                        </span>
                    </div>

                    <div class="p-6 space-y-8 bg-slate-50/30">
                        @php
                            $roomsByFloor = $res->rooms->groupBy('floor')->sortKeysDesc();
                        @endphp

                        @foreach($roomsByFloor as $floor => $rooms)
                            <div class="floor-group pb-8 last:pb-0" data-floor="{{ $floor }}">
                                
                                {{-- Prominent Long Bar Header --}}
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-1.5 h-6 bg-red-650 rounded-full"></div>
                                    <h5 class="text-base font-black text-slate-800">ระดับชั้นที่ {{ $floor }}</h5>
                                </div>

                                <div class="flex flex-wrap justify-center gap-4">
                                    @foreach($rooms as $room)
                                        @php
                                            $currentStay = $room->stays->where('is_current', 1)->first();
                                            $hasOccupant = !empty($currentStay);
                                            $status = $room->residence_room_status;

                                            // Fallback logic
                                            if ($status == 1 && !$currentStay) {
                                                $status = 0;
                                            }
                                            if ($status == 0 && $currentStay) {
                                                $status = 1;
                                            }

                                            // Distinct color configurations based on status
                                            if ($status == 2) {
                                                $cardBg = 'bg-amber-50/60 border-amber-300 hover:border-amber-500';
                                                $badgeBg = 'bg-amber-100/90 text-amber-800 border-amber-300';
                                                $indicatorColor = 'bg-amber-600';
                                                $statusLabel = 'ซ่อมบำรุง';
                                            } elseif ($status == 1 || $hasOccupant) {
                                                $cardBg = 'bg-rose-50/50 border-rose-300 hover:border-rose-500';
                                                $badgeBg = 'bg-rose-100/90 text-rose-800 border-rose-300';
                                                $indicatorColor = 'bg-rose-600';
                                                $statusLabel = 'มีผู้เข้าพัก';
                                            } else {
                                                $cardBg = 'bg-emerald-50/50 border-emerald-300 hover:border-emerald-500';
                                                $badgeBg = 'bg-emerald-100/90 text-emerald-800 border-emerald-300';
                                                $indicatorColor = 'bg-emerald-600';
                                                $statusLabel = 'ห้องว่าง';
                                            }
                                        @endphp
                                        <div class="room-card group/card {{ $cardBg }} rounded-lg border-2 hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col justify-between min-h-[140px] cursor-pointer w-[140px] shrink-0"
                                            onclick="window.location.href='{{ route('housing.room_detail', $room->residence_room_id) }}'">
                                            
                                            {{-- Floor Level Tag inside card --}}
                                            <div class="absolute top-0.5 right-0.5 bg-slate-200/60 border border-slate-350 text-slate-700 text-[8px] font-extrabold px-1.5 py-0.5 rounded-md">
                                                ชั้น {{ $room->floor }}
                                            </div>

                                            <div class="p-4 flex-1">
                                                <span class="text-slate-400 text-[10px] font-black block tracking-wider mb-1 uppercase">ROOM</span>
                                                <h4 class="text-lg font-black text-slate-800 group-hover/card:text-red-650 transition-colors leading-none">
                                                    {{ $room->room_number }}
                                                </h4>

                                                {{-- Status Indicators (Hospital Clean) --}}
                                                <div class="mt-4 flex flex-col gap-1.5">
                                                    <span class="inline-flex items-center justify-center gap-1.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $badgeBg }} w-full text-center border">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $indicatorColor }} {{ $status == 2 ? 'animate-pulse' : '' }}"></span>
                                                        {{ $statusLabel }}
                                                    </span>
                                                    @if($hasOccupant)
                                                        <span class="text-[9px] font-bold text-slate-500 mt-1 truncate text-center block" title="{{ $currentStay->resident->firstname ?? '-' }} {{ $currentStay->resident->lastname ?? '-' }}">
                                                            {{ $currentStay->resident->firstname ?? '-' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Details Strip --}}
                                            <div class="bg-slate-50 border-t border-slate-200 px-3 py-1.5 flex items-center justify-between text-[9px] font-bold text-slate-550 rounded-b-lg">
                                                <span class="flex items-center gap-0.5"><i class="fa-solid fa-users text-slate-400"></i> {{ $room->capacity }} คน</span>
                                                <span class="text-slate-700 font-extrabold">{{ number_format($room->price) }} ฿</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @if($res->rooms->isEmpty())
                            <div class="text-center py-12 text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 block opacity-30"></i>
                                <p class="text-xs font-bold">ไม่พบทะเบียนห้องพักประจำบ้านพักสวัสดิการหลังนี้</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($residences->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                <i class="fa-solid fa-building-circle-xmark text-4xl text-slate-350 mb-3 block"></i>
                <p class="text-slate-500 font-bold">ไม่พบข้อมูลอาคาร/บ้านพักพนักงานในระบบ</p>
            </div>
        @endif
    </div>

    <!-- Blueprint Viewer Modal -->
    <div id="blueprintModal" class="hidden fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeBlueprintModal()"></div>
            <div class="relative inline-block align-middle bg-[#111111] rounded-xl text-left shadow-2xl transform transition-all max-w-5xl w-full overflow-hidden border border-neutral-800">
                <div class="bg-[#1a1a1a] px-4 py-3 flex items-center justify-between text-white">
                    <span class="text-xs font-bold tracking-wider text-neutral-300" id="blueprint-modal-title">แผนผังรายละเอียด</span>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="zoomBlueprint(0.25)" class="text-neutral-450 hover:text-white transition-colors focus:outline-none p-1" title="ขยาย (Zoom In)">
                            <i class="fa-solid fa-magnifying-glass-plus text-sm"></i>
                        </button>
                        <button type="button" onclick="zoomBlueprint(-0.25)" class="text-neutral-450 hover:text-white transition-colors focus:outline-none p-1" title="ย่อ (Zoom Out)">
                            <i class="fa-solid fa-magnifying-glass-minus text-sm"></i>
                        </button>
                        <button type="button" onclick="resetZoomBlueprint()" class="text-neutral-450 hover:text-white transition-colors focus:outline-none p-1" title="ขนาดปกติ (Reset)">
                            <i class="fa-solid fa-arrows-rotate text-xs"></i>
                        </button>
                        <span class="text-neutral-600">|</span>
                        <button type="button" onclick="closeBlueprintModal()" class="text-neutral-400 hover:text-white transition-colors focus:outline-none">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="bg-white overflow-auto max-h-[80vh] p-4 relative text-center" id="blueprint-scroll-container">
                    <div class="inline-block min-w-full align-middle">
                        <img id="blueprint-modal-img" src="" alt="Blueprint" class="w-full h-auto object-contain transition-all duration-200 ease-out" style="max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterRooms() {
            const site = document.getElementById('filterSite').value;
            const floor = document.getElementById('filterFloor').value;

            document.querySelectorAll('.residence-block').forEach(block => {
                const resId = block.dataset.residenceId;
                const siteMatch = (site === 'all' || resId === site);
                block.style.display = siteMatch ? '' : 'none';

                if (siteMatch) {
                    block.querySelectorAll('.floor-group').forEach(fg => {
                        const floorVal = fg.dataset.floor;
                        const floorMatch = (floor === 'all' || floorVal === floor);
                        fg.style.display = floorMatch ? '' : 'none';
                    });
                }
            });
        }

        let currentZoom = 100;

        function zoomBlueprint(amount) {
            currentZoom = Math.min(Math.max(100, currentZoom + (amount * 100)), 300);
            const img = document.getElementById('blueprint-modal-img');
            if (currentZoom > 100) {
                img.style.maxWidth = 'none';
                img.style.width = `${currentZoom}%`;
                img.style.cursor = 'zoom-out';
            } else {
                img.style.maxWidth = '100%';
                img.style.width = '100%';
                img.style.cursor = '';
            }
        }

        function resetZoomBlueprint() {
            currentZoom = 100;
            const img = document.getElementById('blueprint-modal-img');
            img.style.maxWidth = '100%';
            img.style.width = '100%';
            img.style.cursor = '';
        }

        function openBlueprintModal(imgUrl, title) {
            document.getElementById('blueprint-modal-img').src = imgUrl;
            document.getElementById('blueprint-modal-title').innerText = 'แผนผังรายละเอียด: ' + title;
            document.getElementById('blueprintModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeBlueprintModal();
            }
        });

        function closeBlueprintModal() {
            document.getElementById('blueprintModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('blueprint-modal-img').src = '';
            resetZoomBlueprint();
        }

        function confirmDeleteResidence(id, name) {
            Swal.fire({
                title: 'ยืนยันการลบอาคาร?',
                text: `คุณต้องการลบอาคาร "${name}" และห้องพักทั้งหมดในอาคารนี้ใช่หรือไม่? การดำเนินการนี้ไม่สามารถย้อนกลับได้`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ยืนยันการลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'กำลังดำเนินการ...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('delete-residence-form-' + id).submit();
                }
            });
        }
    </script>
@endsection