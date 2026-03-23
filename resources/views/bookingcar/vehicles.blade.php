@extends('layouts.bookingcar.appcar')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 border-b border-slate-100 pb-6">
            <h2 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-3">
                <i class="fa-solid fa-car-side text-red-600"></i> ข้อมูลรถส่วนกลางทั้งหมด
            </h2>
            <p class="text-slate-500 text-lg">แสดงรายการรถส่วนกลางที่มีในระบบ สามารถดูรายละเอียดและสเปครถได้ที่นี่</p>
        </div>

        <!-- Search and Count Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                </div>
                <input type="text" id="vehicleSearch" 
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all text-slate-600 placeholder-slate-400" 
                    placeholder="ค้นหายี่ห้อ, รุ่น, ประเภทรถ...">
            </div>
            <div class="flex items-center gap-3 text-slate-600 px-4 py-2 bg-red-50/50 rounded-lg border border-red-100/50">
                <span class="text-sm font-medium">พบทั้งหมด:</span>
                <span id="vehicleCount" class="text-xl font-bold text-red-600 tabular-nums">{{ $vehicles->count() }}</span>
                <span class="text-sm font-medium text-slate-500">คัน</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($vehicles as $vehicle)
                <div
                    class="vehicle-card card bg-white shadow-md border border-slate-200 p-0 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 overflow-hidden flex flex-col h-full">
                    <!-- Vehicle Image -->
                    <figure
                        class="h-48 bg-slate-100 relative border-b border-slate-200 flex items-center justify-center shrink-0">
                        @php
                            $images = is_string($vehicle->images) ? json_decode($vehicle->images, true) : $vehicle->images;
                            $firstImage = !empty($images) && is_array($images) ? $images[0] : null;

                            $imagePathUrl = null;
                            if ($firstImage) {
                                if (file_exists(public_path('images/vehicle/' . $firstImage))) {
                                    $imagePathUrl = asset('images/vehicle/' . $firstImage);
                                } elseif (file_exists(public_path('images/' . $firstImage))) {
                                    $imagePathUrl = asset('images/' . $firstImage);
                                } elseif (file_exists(public_path($firstImage))) {
                                    $imagePathUrl = asset($firstImage);
                                }
                            }
                        @endphp

                        @if($imagePathUrl)
                            <img src="{{ $imagePathUrl }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover"
                                onerror="this.style.display='none'">
                        @else
                            <div class="text-slate-400 flex flex-col items-center">
                                <i class="fa-regular fa-image text-4xl mb-2"></i>
                                <span class="text-sm">ไม่มีรูปภาพ</span>
                            </div>
                        @endif

                        <div
                            class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-md text-xs font-bold text-slate-700 shadow-sm border border-slate-200">
                            {{ $vehicle->name }}
                        </div>
                    </figure>

                    <!-- Vehicle Info -->
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="font-bold text-red-600 uppercase text-lg mb-1 leading-tight">
                            {{ $vehicle->brand ?? 'ไม่ระบุยี่ห้อ' }}
                        </h3>
                        <p class="text-sm text-slate-800 font-semibold mb-3">{{ $vehicle->model_name ?? '-' }}</p>

                        <div
                            class="space-y-2 mb-4 text-sm text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100 flex-grow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-500 shrink-0">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-400 font-medium">จำนวนที่นั่ง</span>
                                    <span class="font-semibold">{{ $vehicle->seat ?? '-' }} ที่นั่ง</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-500 shrink-0">
                                    <i class="fa-solid fa-gas-pump"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-400 font-medium">เชื้อเพลิง</span>
                                    <span class="font-semibold">{{ $vehicle->filling_type ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-500 shrink-0">
                                    <i class="fa-solid fa-car-side"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] text-slate-400 font-medium">ประเภทรถ</span>
                                    <span class="font-semibold">{{ $vehicle->type ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($vehicle->desciption)
                            <div class="mt-auto pt-3 border-t border-slate-100 text-xs text-slate-600">
                                <span class="font-semibold text-slate-700 mb-1 block">รายละเอียดเพิ่มเติม:</span>
                                <p class="line-clamp-2" title="{{ $vehicle->desciption }}">{{ $vehicle->desciption }}</p>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <a href="{{ route('bookingcar.welcome') }}?vehicle_id={{ $vehicle->vehicle_id }}"
                                class="btn btn-sm w-full bg-red-50 hover:bg-red-100 text-red-600 border-0 transition-colors">
                                <i class="fa-solid fa-calendar-check mr-1"></i> จองรถคันนี้
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-12 flex flex-col items-center justify-center bg-white rounded-xl border border-slate-200 shadow-sm text-slate-500">
                    <i class="fa-solid fa-car-slash text-5xl mb-4 text-slate-300"></i>
                    <p class="text-lg font-medium">ยังไม่มีข้อมูลรถส่วนกลางในระบบ</p>
                </div>
            @endforelse
        </div>

        <!-- No Results Placeholder -->
        <div id="noResults" class="hidden text-center py-16 px-4 bg-white rounded-xl border border-slate-200 shadow-sm mt-6">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700">ไม่พบรถส่วนกลางที่ตรงกับการค้นหา</h3>
            <p class="text-slate-500 mt-1">ลองเปลี่ยนคำค้นหา หรือล้างคำค้นหาดูนะคะ</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('vehicleSearch');
    const vehicleCount = document.getElementById('vehicleCount');
    const noResults = document.getElementById('noResults');
    const cards = document.querySelectorAll('.vehicle-card');

    searchInput.addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const matches = text.includes(term);

            if (matches) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        vehicleCount.textContent = visibleCount;

        if (visibleCount === 0 && cards.length > 0) {
            noResults.classList.remove('hidden');
        } else {
            noResults.classList.add('hidden');
        }
    });
});
</script>
@endpush