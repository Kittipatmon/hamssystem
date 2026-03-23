@extends('layouts.navmeeting.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-8 border-b border-slate-100 pb-6">
            <h2 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-3">
                <i class="fa-solid fa-door-open text-[#c31919]"></i> ข้อมูลห้องประชุม
            </h2>
            <p class="text-slate-500 text-lg">แสดงรายการห้องประชุมที่มีในระบบ สามารถดูรายละเอียดและสเปคห้องประชุมได้ที่นี่</p>
        </div>

        <!-- Search and Count Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" id="roomSearch" 
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-[#c31919] transition-all text-slate-600 placeholder-slate-400" 
                    placeholder="ค้นหาชื่อห้อง, สถานที่, หรือสเปค...">
            </div>
            <div class="flex items-center gap-3 text-slate-600 px-4 py-2 bg-red-50/50 rounded-lg border border-red-100/50">
                <span class="text-sm font-medium">พบทั้งหมด:</span>
                <span id="roomCount" class="text-xl font-bold text-[#c31919] tabular-nums">{{ $rooms->count() }}</span>
                <span class="text-sm font-medium text-slate-500">ห้อง</span>
            </div>
        </div>

        <!-- Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($rooms as $room)
                <div
                    class="room-card card bg-white shadow-md border border-slate-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Room Image Placeholder (If images were empty, show default) -->
                    <figure class="h-48 bg-slate-100 relative border-b border-slate-200 flex items-center justify-center">
                        @php
                            $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                            $firstImage = !empty($images) && is_array($images) ? $images[0] : null;

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
                            <img src="{{ $imagePathUrl }}" alt="{{ $room->room_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
                        @else
                            <div class="text-slate-400 flex flex-col items-center">
                                <i class="fa-regular fa-image text-4xl mb-2"></i>
                                <span class="text-sm">ไม่มีรูปภาพ</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($room->status == 1)
                                <span class="badge badge-success text-white border-0 shadow-sm text-xs py-2 px-3"><i
                                        class="fa-solid fa-circle-check mr-1"></i> พร้อมใช้งาน</span>
                            @else
                                <span class="badge badge-error text-white border-0 shadow-sm text-xs py-2 px-3"><i
                                        class="fa-solid fa-circle-xmark mr-1"></i> ปิดปรับปรุง</span>
                            @endif
                        </div>
                    </figure>

                    <div class="card-body p-5">
                        <h3 class="card-title text-xl font-bold text-[#c31919] mb-1 break-words whitespace-normal leading-snug">
                            {{ $room->room_name }}
                        </h3>

                        <p class="text-xs text-slate-500 mb-4 pb-3 border-b border-slate-100 flex flex-wrap">
                            <span
                                class="inline-block px-2 py-1 bg-slate-100 rounded text-slate-600 mr-1 break-words whitespace-normal">{{ $room->room_type ?? 'ไม่ระบุประเภท' }}</span>
                        </p>

                        <div class="space-y-2 text-sm text-slate-600">
                            <div class="flex items-start">
                                <div class="flex-1 min-w-0 break-words whitespace-normal">
                                    <i class="fa-solid fa-users w-6 text-center text-slate-400 mt-1"></i>
                                    <span class="font-semibold block text-slate-700">ความจุ</span>
                                    {{ $room->capacity }} ท่าน
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-1 min-w-0 break-words whitespace-normal">
                                    <i class="fa-solid fa-map-location-dot w-6 text-center text-slate-400 mt-1"></i>
                                    <span class="font-semibold block text-slate-700">สถานที่</span>
                                    {{ $room->location ?? '-' }}
                                    @if($room->floor)
                                        (ชั้น {{ $room->floor }})
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-1 min-w-0 break-words whitespace-normal">
                                    <i class="fa-solid fa-toolbox w-6 text-center text-slate-400 mt-1"></i>
                                    <span class="font-semibold block text-slate-700">อุปกรณ์</span>
                                    <div class="flex flex-wrap gap-2 mt-1">
                                        @if($room->has_projector)
                                            <span
                                                class="text-[11px] bg-red-50 text-red-600 px-2 py-1 rounded border border-red-100 break-words whitespace-normal">
                                                <i class="fa-solid fa-video"></i> โปรเจคเตอร์
                                            </span>
                                        @endif
                                        @if($room->has_video_conf)
                                            <span
                                                class="text-[11px] bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-100 break-words whitespace-normal">
                                                <i class="fa-solid fa-satellite-dish"></i> Video Conference
                                            </span>
                                        @endif
                                        @if(!$room->has_projector && !$room->has_video_conf)
                                            <span class="text-slate-400 text-xs break-words whitespace-normal">- ไม่มีอุปกรณ์พิเศษ -</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($room->description)
                            <div class="mt-4 pt-3 border-t border-slate-100 text-sm text-slate-600">
                                <p class="line-clamp-2" title="{{ $room->description }}"><i
                                        class="fa-solid fa-circle-info text-slate-400 mr-1"></i> {{ $room->description }}</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            @if($room->status == 1)
                                <a href="{{ route('reservations.welcomemeeting') }}?room={{ $room->room_id }}"
                                    class="btn btn-sm w-full bg-red-200 hover:bg-red-300 text-red-600 border-0 transition-colors">
                                    <i class="fa-solid fa-calendar-check mr-1"></i> ไปหน้าจองห้อง
                                </a>
                                
                            @else
                                <button class="btn btn-sm btn-disabled w-full bg-slate-200 text-slate-500">
                                    ไม่สามารถจองได้
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- No Results Placeholder -->
        <div id="noResults" class="hidden text-center py-16 px-4 bg-white rounded-xl border border-slate-200 shadow-sm mt-6">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700">ไม่พบห้องประชุมที่ตรงกับการค้นหา</h3>
            <p class="text-slate-500 mt-1">ลองเปลี่ยนคำค้นหา หรือล้างคำค้นหาดูนะคะ</p>
        </div>

        @if($rooms->isEmpty())
            <div class="text-center py-12 px-4 bg-white rounded-xl border border-slate-200 shadow-sm mt-6">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-door-closed text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-700">ไม่มีข้อมูลห้องประชุม</h3>
                <p class="text-slate-500 mt-1">ยังไม่มีการเพิ่มข้อมูลห้องประชุมในระบบ</p>
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
            // Find specific text blocks to search within for better precision
            const roomName = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
            const location = card.querySelector('.fa-map-location-dot')?.parentElement?.textContent.toLowerCase() || '';
            const type = card.querySelector('.inline-block.bg-slate-100')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.line-clamp-2')?.textContent.toLowerCase() || '';
            
            const matches = roomName.includes(term) || 
                          location.includes(term) || 
                          type.includes(term) || 
                          description.includes(term);

            if (matches) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update count
        roomCount.textContent = visibleCount;

        // Show/Hide no results message
        if (visibleCount === 0 && cards.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    });
});
</script>
@endpush