@extends('layouts.housing.apphousing')

@section('title', 'รายการบ้านพัก')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="text-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center justify-center gap-2">
            <i class="fa-solid fa-house-chimney text-red-500"></i> รายการบ้านพัก
        </h2>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center justify-center gap-4">
        <div class="flex items-center gap-2">
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">เลือกพื้นที่:</label>
            <select id="filterSite" onchange="filterRooms()"
                class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm h-9 px-3 min-w-[160px]">
                <option value="all">ทุกพื้นที่</option>
                @foreach($residences as $res)
                    <option value="{{ $res->residence_id }}">{{ $res->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm font-semibold text-gray-600 dark:text-gray-300">เลือกชั้น:</label>
            <select id="filterFloor" onchange="filterRooms()"
                class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm h-9 px-3 min-w-[120px]">
                <option value="all">ทุกชั้น</option>
                @php
                    $allFloors = $residences->flatMap(fn($r) => $r->rooms->pluck('floor'))->unique()->sort();
                @endphp
                @foreach($allFloors as $f)
                    <option value="{{ $f }}">ชั้น {{ $f }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Legend --}}
    <div class="flex items-center justify-center gap-6 text-sm">
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> ว่าง</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> ไม่ว่าง</span>
        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span> ซ่อม/ปรับปรุง</span>
    </div>

    {{-- KPI Summary --}}
    <div class="flex flex-wrap items-center justify-center gap-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-3 text-center min-w-[100px] shadow-sm">
            <p class="text-2xl font-bold text-emerald-600">{{ $availableRooms }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ห้องว่าง</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-3 text-center min-w-[100px] shadow-sm">
            <p class="text-2xl font-bold text-red-600">{{ $occupiedRooms }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ห้องไม่ว่าง</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-3 text-center min-w-[100px] shadow-sm">
            <p class="text-2xl font-bold text-amber-600">{{ $maintenanceRooms }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ซ่อม/ปรับปรุง</p>
        </div>
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-6 py-3 text-center min-w-[100px] shadow-sm">
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalRooms }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ห้องทั้งหมด</p>
        </div>
    </div>

    {{-- Room Grid by Residence --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($residences as $res)
        <div class="residence-block bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm"
             data-residence-id="{{ $res->residence_id }}">

            {{-- Residence Header --}}
            <div class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 px-5 py-3 border-b border-gray-200 dark:border-gray-600">
                <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-building text-red-500"></i>
                    ห้องพักที่{{ $res->name }}
                </h3>
            </div>

            <div class="p-4 space-y-4">
                @php
                    $roomsByFloor = $res->rooms->groupBy('floor');
                @endphp

                @foreach($roomsByFloor as $floor => $rooms)
                <div class="floor-group" data-floor="{{ $floor }}">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wider">ชั้น {{ $floor }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($rooms as $room)
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

                            if ($status == 0) {
                                $bgColor = 'bg-emerald-500';
                                $borderColor = 'border-emerald-400';
                                $statusText = 'ห้องว่าง';
                                $statusIcon = 'fa-solid fa-check-circle';
                            } elseif ($status == 1) {
                                $bgColor = 'bg-red-500';
                                $borderColor = 'border-red-400';
                                $statusText = 'ไม่ว่าง';
                                $statusIcon = 'fa-solid fa-user';
                            } else {
                                $bgColor = 'bg-amber-500';
                                $borderColor = 'border-amber-400';
                                $statusText = 'ซ่อม/ปรับปรุง';
                                $statusIcon = 'fa-solid fa-wrench';
                            }
                        @endphp
                        <div class="room-card {{ $bgColor }} rounded-xl p-3 text-white shadow-md hover:shadow-lg transition-all hover:scale-[1.02] active:scale-95 cursor-pointer relative overflow-hidden group"
                             data-status="{{ $status }}"
                             onclick="window.location='{{ route('housing.room_detail', $room->residence_room_id) }}'">
                            {{-- Status indicator dot --}}
                            <div class="absolute top-2 right-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-white/40 block"></span>
                            </div>

                            {{-- Room name --}}
                            <p class="text-xs font-bold mb-2 bg-white/20 rounded-md px-2 py-0.5 inline-block">
                                ห้อง {{ $room->room_number }}
                            </p>

                            {{-- Bed icon --}}
                            <div class="flex justify-center my-2">
                                <i class="fa-solid fa-bed text-3xl text-white/80 group-hover:scale-110 transition-transform"></i>
                            </div>

                            {{-- Status text --}}
                            <p class="text-xs font-semibold text-center">
                                <i class="{{ $statusIcon }} mr-1"></i>{{ $statusText }}
                            </p>

                            {{-- Occupant info --}}
                            @if($status == 1 && $currentStay)
                            <div class="mt-2 pt-2 border-t border-white/20 text-[10px] space-y-0.5">
                                <p class="font-bold"><i class="fa-solid fa-user-check mr-1"></i>
                                    {{ $currentStay->resident->full_name ?? ($currentStay->latestRequest ? $currentStay->latestRequest->first_name . ' ' . $currentStay->latestRequest->last_name : 'กำลังดำเนินการ') }}
                                </p>
                                <p><i class="fa-solid fa-calendar-check mr-1"></i>เข้าพักเมื่อ {{ $currentStay->check_in ? \Carbon\Carbon::parse($currentStay->check_in)->format('d/m/Y') : '-' }}</p>
                            </div>
                            @endif

                            @if($status == 2)
                            <div class="mt-2 pt-2 border-t border-white/20 text-[10px]">
                                <p><i class="fa-solid fa-info-circle mr-1"></i>{{ $room->note ?? 'อยู่ระหว่างปรับปรุง' }}</p>
                            </div>
                            @endif

                            {{-- Detail link --}}
                            <a href="{{ route('housing.room_detail', $room->residence_room_id) }}" 
                               class="text-[10px] text-white/60 mt-2 text-center hover:text-white/90 transition-colors block">
                                <i class="fa-solid fa-eye mr-1"></i>ดูรายละเอียด & มอบหมายห้อง →
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                @if($res->rooms->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>
                    <p class="text-sm">ยังไม่มีห้องพักในบ้านพักนี้</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @if($residences->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center">
        <i class="fa-solid fa-building-circle-xmark text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500 dark:text-gray-400">ยังไม่มีข้อมูลบ้านพักในระบบ</p>
    </div>
    @endif
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
</script>
@endsection