@extends('layouts.navmeeting.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6 border-b border-slate-200 pb-5">
            <h2 class="text-2xl font-black text-slate-800 flex items-center gap-2.5 uppercase tracking-wide">
                <i class="fa-solid fa-door-open text-[#c31919]"></i> ข้อมูลรายละเอียดห้องประชุมส่วนกลาง
            </h2>
            <p class="text-slate-500 text-xs mt-1 font-semibold">แสดงรายการห้องประชุมที่มีในระบบจอง ค้นหาตามขนาดความจุ อุปกรณ์สนับสนุน หรือข้อมูลสถานที่</p>
        </div>

        <!-- Search and Count Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 bg-white p-4 rounded border border-slate-200 shadow-sm text-xs font-semibold">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" id="roomSearch" 
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all placeholder-slate-400 font-semibold text-slate-700" 
                    placeholder="พิมพ์ค้นหาชื่อห้อง, สถานที่ตั้ง, หรืออุปกรณ์สนับสนุน...">
            </div>
            <div class="flex items-center gap-2 text-slate-600 px-3 py-1.5 bg-red-50/50 rounded border border-red-100/50">
                <span class="text-xs font-bold">ห้องประชุมทั้งหมด:</span>
                <span id="roomCount" class="text-base font-black text-[#c31919] tabular-nums">{{ $rooms->count() }}</span>
                <span class="text-xs font-bold text-slate-400">ห้อง</span>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
                <div class="room-card bg-white border border-slate-200 rounded overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-0.5 flex flex-col justify-between">
                    <!-- Room Image -->
                    <figure class="h-44 bg-slate-50 relative border-b border-slate-200 flex items-center justify-center overflow-hidden">
                        @php
                            $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                            $firstImage = !empty($images) && is_array($images) ? $firstImage = $images[0] : null;

                            $imagePathUrl = null;
                            if ($firstImage) {
                                if (file_exists(public_path('images/room/' . $firstImage))) {
                                    $imagePathUrl = asset('images/room/' . $firstImage);
                                } elseif (file_exists(public_path('images/' . $firstImage))) {
                                    $imagePathUrl = asset('images/' . $firstImage);
                                } elseif (file_exists(public_path($firstImage))) {
                                    $imagePathUrl = asset($firstImage);
                                }
                            }
                        @endphp

                        @if($imagePathUrl)
                            <img src="{{ $imagePathUrl }}" alt="{{ $room->room_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-slate-300 flex flex-col items-center">
                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                <span class="text-[10px] font-bold">ไม่มีรูปภาพประกอบ</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-2.5 right-2.5">
                            @if($room->status == 1)
                                @if(in_array($room->room_id, $occupiedRoomIds ?? []))
                                    <span class="bg-amber-500 text-white font-bold text-[9px] py-1 px-2.5 rounded shadow flex items-center gap-1 animate-pulse" title="ขณะนี้ห้องประชุมนี้กำลังใช้งานอยู่">
                                        <i class="fa-solid fa-clock"></i> อยู่ระหว่างการใช้งาน
                                    </span>
                                @else
                                    <span class="bg-green-600 text-white font-bold text-[9px] py-1 px-2.5 rounded shadow flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check"></i> พร้อมใช้งาน
                                    </span>
                                @endif
                            @else
                                <span class="bg-red-600 text-white font-bold text-[9px] py-1 px-2.5 rounded shadow flex items-center gap-1">
                                    <i class="fa-solid fa-circle-xmark"></i> ปิดปรับปรุง
                                </span>
                            @endif
                        </div>
                    </figure>

                    <div class="p-4 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-black text-[#c31919] uppercase tracking-wide leading-tight truncate">
                                        {{ $room->room_name }}
                                    </h3>
                                    <p class="text-[10px] text-slate-400 font-bold mt-1">
                                        {{ $room->room_type ?? 'ประเภททั่วไป' }}
                                    </p>
                                </div>
                                
                                <!-- Booking History Magnifying Glass -->
                                <div class="relative group/history room-history-container shrink-0">
                                    <button type="button" class="history-toggle-btn w-8 h-8 rounded-full bg-slate-100 hover:bg-[#c31919] hover:text-white text-slate-500 flex items-center justify-center transition-all shadow-sm focus:outline-none" title="ดูประวัติการจอง">
                                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                    </button>
                                    
                                    <!-- Popover box -->
                                    <div class="history-popover absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-lg shadow-xl z-30 hidden md:group-hover/history:block transition-all duration-200">
                                        <div class="p-3 border-b border-slate-100 bg-slate-50 rounded-t-lg">
                                            <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                                <i class="fa-solid fa-clock-rotate-left text-slate-500"></i>
                                                รายการจอง (ปัจจุบัน - อนาคต)
                                            </h4>
                                        </div>
                                        <div class="max-h-60 overflow-y-auto p-2 space-y-2">
                                            @forelse($room->reservations as $res)
                                                <div class="p-2.5 rounded bg-slate-50 border border-slate-100 text-[11px] font-semibold text-slate-700">
                                                    <div class="flex justify-between items-start gap-2 mb-1">
                                                        <span class="font-bold text-slate-800 line-clamp-1" title="{{ $res->topic }}">{{ $res->topic }}</span>
                                                        @if($res->status == 'acknowledge')
                                                            <span class="text-[9px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold">อนุมัติ</span>
                                                        @elseif($res->status == 'pending')
                                                            <span class="text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-bold">รออนุมัติ</span>
                                                        @else
                                                            <span class="text-[9px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-bold">{{ $res->status }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-[10px] text-slate-500 space-y-0.5 font-medium">
                                                        <div><i class="fa-regular fa-calendar-days w-3.5 text-slate-400"></i> {{ \Carbon\Carbon::parse($res->reservation_date)->format('d/m/Y') }} @if($res->reservation_dateend && $res->reservation_dateend != $res->reservation_date) - {{ \Carbon\Carbon::parse($res->reservation_dateend)->format('d/m/Y') }} @endif</div>
                                                        <div><i class="fa-regular fa-clock w-3.5 text-slate-400"></i> {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }} น.</div>
                                                        <div><i class="fa-regular fa-user w-3.5 text-slate-400"></i> {{ $res->requester_name ?? ($res->user ? $res->user->first_name . ' ' . $res->user->last_name : '-') }}</div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-4 text-slate-400 text-[10px] font-medium">
                                                    <i class="fa-solid fa-calendar-xmark text-lg mb-1 block"></i>
                                                    ไม่มีรายการจองล่วงหน้า
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Specs clinical-sheet styled -->
                            <div class="mt-3 border border-slate-200 rounded divide-y divide-slate-100 text-xs font-semibold">
                                <div class="p-2 flex justify-between items-center bg-slate-50/50">
                                    <span class="text-slate-500"><i class="fa-solid fa-users w-4 text-center"></i> ความจุรองรับ</span>
                                    <span class="text-slate-800 font-bold">{{ $room->capacity }} ท่าน</span>
                                </div>
                                <div class="p-2 flex justify-between items-center">
                                    <span class="text-slate-500"><i class="fa-solid fa-map-location-dot w-4 text-center"></i> สถานที่ตั้ง</span>
                                    <span class="text-slate-800 font-bold">
                                        {{ $room->location ?? '-' }} @if($room->floor) (ชั้น {{ $room->floor }}) @endif
                                    </span>
                                </div>
                                <div class="p-2 flex flex-col gap-1.5 bg-slate-50/50">
                                    <span class="text-slate-500 block"><i class="fa-solid fa-toolbox w-4 text-center"></i> อุปกรณ์ประชุม</span>
                                    <div class="flex flex-wrap gap-1.5 mt-0.5">
                                        @if($room->has_projector)
                                            <span class="text-[9px] bg-red-50 text-red-600 px-2 py-0.5 rounded border border-red-100 font-bold">
                                                <i class="fa-solid fa-video"></i> โปรเจคเตอร์
                                            </span>
                                        @endif
                                        @if($room->has_video_conf)
                                            <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded border border-blue-100 font-bold">
                                                <i class="fa-solid fa-satellite-dish"></i> Video Conference
                                            </span>
                                        @endif
                                        @if(!$room->has_projector && !$room->has_video_conf)
                                            <span class="text-slate-400 text-[10px] font-normal italic">- ไม่มีอุปกรณ์พิเศษ -</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($room->description)
                                <div class="mt-3 pt-2 text-[11px] text-slate-500 leading-normal border-t border-slate-100 font-semibold italic">
                                    <i class="fa-solid fa-circle-info text-slate-400 mr-0.5"></i> {{ $room->description }}
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-slate-100 text-xs font-bold">
                            @if($room->status == 1)
                                <a href="{{ route('reservations.welcomemeeting') }}?room={{ $room->room_id }}"
                                    class="w-full h-8 rounded bg-[#c31919] hover:bg-[#a61515] text-white border-0 transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                                    <i class="fa-solid fa-calendar-check text-[11px]"></i> ไปยังปฏิทินเพื่อจองห้อง
                                </a>
                            @else
                                <button disabled class="w-full h-8 rounded bg-slate-100 text-slate-400 cursor-not-allowed flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-ban"></i> ระงับสิทธิ์การจองชั่วคราว
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Placeholder -->
        <div id="noResults" class="hidden text-center py-12 px-4 bg-white rounded border border-slate-200 shadow-sm mt-6 font-semibold">
            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-150">
                <i class="fa-solid fa-magnifying-glass text-slate-300"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-700">ไม่พบห้องประชุมที่ตรงกับการค้นหา</h3>
            <p class="text-slate-400 text-xs mt-1 font-medium">โปรดระบุคำค้นหาอื่น เช่น ความจุ หรือชื่ออาคาร</p>
        </div>

        @if($rooms->isEmpty())
            <div class="text-center py-12 px-4 bg-white rounded border border-slate-200 shadow-sm mt-6 font-semibold">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-door-closed text-slate-400"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">ไม่มีข้อมูลห้องประชุม</h3>
                <p class="text-slate-400 text-xs mt-1 font-medium">ยังไม่มีการลงทะเบียนข้อมูลห้องประชุมในระบบ</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('roomSearch');
    const roomCount = document.getElementById('roomCount');
    const noResults = document.getElementById('noResults');
    const cards = document.querySelectorAll('.room-card');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const roomName = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const specText = card.querySelector('.divide-y')?.textContent.toLowerCase() || '';
            const type = card.querySelector('.text-[10px]')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.italic')?.textContent.toLowerCase() || '';
            
            const matches = roomName.includes(term) || 
                          specText.includes(term) || 
                          type.includes(term) || 
                          description.includes(term);

            if (matches) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        roomCount.textContent = visibleCount;

        if (visibleCount === 0 && cards.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    });

    // Toggle history popovers on click (useful for mobile and desktop click fallbacks)
    const toggleBtns = document.querySelectorAll('.history-toggle-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const container = btn.closest('.room-history-container');
            const popover = container.querySelector('.history-popover');
            
            // Close all other popovers first
            document.querySelectorAll('.history-popover').forEach(p => {
                if (p !== popover) {
                    p.classList.add('hidden');
                    p.classList.remove('block');
                }
            });

            // Toggle current
            if (popover.classList.contains('hidden')) {
                popover.classList.remove('hidden');
                popover.classList.add('block');
            } else {
                popover.classList.add('hidden');
                popover.classList.remove('block');
            }
        });
    });

    // Close popover when clicking anywhere else
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.room-history-container')) {
            document.querySelectorAll('.history-popover').forEach(p => {
                p.classList.add('hidden');
                p.classList.remove('block');
            });
        }
    });
});
</script>
@endpush