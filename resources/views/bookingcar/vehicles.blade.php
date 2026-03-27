@extends('layouts.bookingcar.appcar')
@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 border-b border-slate-100 pb-6">
            <h2 class="text-3xl font-bold text-slate-800 flex items-center gap-3 mb-3">
                <i class="fa-solid fa-car-side text-red-600"></i> ข้อมูลรถส่วนกลางทั้งหมด
            </h2>
            <p class="text-slate-500 text-lg">แสดงรายการรถส่วนกลางที่มีในระบบ สามารถดูรายละเอียดและสเปครถได้ที่นี่</p>
        </div>

        <!-- Enhanced Search and Filter Header -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8 space-y-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Text Search -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                    </div>
                    <input type="text" id="vehicleSearch"
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-red-500/10 focus:border-red-600 transition-all text-slate-600 font-medium placeholder-slate-400"
                        placeholder="ค้นหาชื่อรถ, เลขทะเบียน, ยี่ห้อ...">
                </div>

                <!-- Filters Toggle / Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 rounded-xl border border-red-100">
                        <span class="text-xs font-black uppercase tracking-wider">พบทั้งหมด:</span>
                        <span id="vehicleCount" class="text-lg font-black tabular-nums">{{ $vehicles->count() }}</span>
                        <span class="text-xs font-bold uppercase tracking-wider">คัน</span>
                    </div>
                    <button id="resetFilters" class="btn btn-ghost btn-sm rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all">
                        <i class="fa-solid fa-rotate-left mr-1"></i> ล้างการค้นหา
                    </button>
                </div>
            </div>

            <!-- Detailed Filters Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-slate-50">
                <!-- Filter: Vehicle Type -->
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[11px] font-bold text-slate-400 uppercase">ประเภทรถ</span></label>
                    <select id="filterType" class="select select-sm select-bordered rounded-xl bg-slate-50 border-slate-200 focus:border-red-500 font-bold text-slate-700 h-10">
                        <option value="">ทั้งหมด</option>
                        @foreach($types as $t) <option value="{{ $t }}">{{ $t }}</option> @endforeach
                    </select>
                </div>

                <!-- Filter: Fuel Type -->
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[11px] font-bold text-slate-400 uppercase">ประเภทน้ำมัน</span></label>
                    <select id="filterFuel" class="select select-sm select-bordered rounded-xl bg-slate-50 border-slate-200 focus:border-red-500 font-bold text-slate-700 h-10">
                        <option value="">ทั้งหมด</option>
                        @foreach($fuels as $f) <option value="{{ $f }}">{{ $f }}</option> @endforeach
                    </select>
                </div>

                <!-- Filter: Seats -->
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[11px] font-bold text-slate-400 uppercase">จำนวนที่นั่ง</span></label>
                    <select id="filterSeat" class="select select-sm select-bordered rounded-xl bg-slate-50 border-slate-200 focus:border-red-500 font-bold text-slate-700 h-10">
                        <option value="">ทั้งหมด</option>
                        @foreach($seats as $s) <option value="{{ $s }}">{{ $s }} ที่นั่ง</option> @endforeach
                    </select>
                </div>

                <!-- Filter: Usage Type -->
                <div class="form-control">
                    <label class="label py-1"><span class="label-text text-[11px] font-bold text-slate-400 uppercase">ประเภทการใช้งาน</span></label>
                    <select id="filterUsage" class="select select-sm select-bordered rounded-xl bg-slate-50 border-slate-200 focus:border-red-500 font-bold text-slate-700 h-10">
                        <option value="">ทั้งหมด</option>
                        @foreach($usageTypes as $u)
                            <option value="{{ $u }}">{{ $u == 1 ? 'รถส่วนกลาง' : ($u == 2 ? 'รถประจำตำแหน่ง' : 'อื่น ๆ') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($vehicles as $vehicle)
                <div
                    data-brand="{{ strtolower($vehicle->brand) }}"
                    data-type="{{ $vehicle->type }}"
                    data-fuel="{{ $vehicle->filling_type }}"
                    data-seat="{{ $vehicle->seat }}"
                    data-usage="{{ $vehicle->status_vehicles }}"
                    data-name="{{ strtolower($vehicle->name) }}"
                    data-model="{{ strtolower($vehicle->model_name) }}"
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
        <div id="noResults"
            class="hidden text-center py-16 px-4 bg-white rounded-xl border border-slate-200 shadow-sm mt-6">
            <div
                class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700">ไม่พบรถส่วนกลางที่ตรงกับการค้นหา</h3>
            <p class="text-slate-500 mt-1">ลองเปลี่ยนคำค้นหา หรือล้างคำค้นหาดูนะคะ</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('vehicleSearch');
            const filterType = document.getElementById('filterType');
            const filterFuel = document.getElementById('filterFuel');
            const filterSeat = document.getElementById('filterSeat');
            const filterUsage = document.getElementById('filterUsage');
            const resetBtn = document.getElementById('resetFilters');
            
            const vehicleCount = document.getElementById('vehicleCount');
            const noResults = document.getElementById('noResults');
            const cards = document.querySelectorAll('.vehicle-card');

            function applyFilters() {
                const term = searchInput.value.toLowerCase().trim();
                const type = filterType.value;
                const fuel = filterFuel.value;
                const seat = filterSeat.value;
                const usage = filterUsage.value;

                let visibleCount = 0;

                cards.forEach(card => {
                    const cName = card.dataset.name || "";
                    const cModel = card.dataset.model || "";
                    const cBrand = card.dataset.brand || "";
                    const cType = card.dataset.type || "";
                    const cFuel = card.dataset.fuel || "";
                    const cSeat = card.dataset.seat || "";
                    const cUsage = card.dataset.usage || "";

                    // Multi-field text match
                    const textMatch = !term || 
                                    cName.includes(term) || 
                                    cModel.includes(term) || 
                                    cBrand.includes(term);
                    
                    // Category matches
                    const typeMatch = !type || cType === type;
                    const fuelMatch = !fuel || cFuel === fuel;
                    const seatMatch = !seat || cSeat === seat;
                    const usageMatch = !usage || cUsage === usage;

                    if (textMatch && typeMatch && fuelMatch && seatMatch && usageMatch) {
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
            }

            // Listeners
            searchInput.addEventListener('input', applyFilters);
            filterType.addEventListener('change', applyFilters);
            filterFuel.addEventListener('change', applyFilters);
            filterSeat.addEventListener('change', applyFilters);
            filterUsage.addEventListener('change', applyFilters);

            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterType.value = '';
                filterFuel.value = '';
                filterSeat.value = '';
                filterUsage.value = '';
                applyFilters();
            });
        });
    </script>
@endpush