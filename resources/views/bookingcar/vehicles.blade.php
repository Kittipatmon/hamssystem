@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[1600px] mx-auto px-4 lg:px-8 py-8 animate-fadeIn text-slate-800">
        
        <!-- Header Panel -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 border-b border-slate-200 pb-6">
            <div>
                <nav class="flex mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li><a href="{{ route('welcome') }}" class="hover:text-red-700 transition-colors">หน้าหลัก</a></li>
                        <li><i class="fa-solid fa-chevron-right mx-1 text-[8px]"></i></li>
                        <li class="text-slate-600">ยานพาหนะทั้งหมด</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-8 bg-red-700 rounded-sm"></div>
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                        สารสนเทศทำเนียบยานพาหนะส่วนกลาง
                    </h1>
                </div>
                <p class="text-slate-500 font-medium text-xs mt-1">
                    <i class="fa-solid fa-info-circle text-red-600/70 mr-1"></i>
                    รายละเอียดคุณลักษณะ สมรรถนะ และประเภทการใช้งานของรถยนต์ส่วนกลางในระบบ
                </p>
            </div>
        </div>

        <!-- Search and Filter Panel (Clinical Layout) -->
        <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8 shadow-sm">
            <div class="flex flex-col lg:flex-row gap-4 mb-4">
                <!-- Text Search -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-xs"></i>
                    </div>
                    <input type="text" id="vehicleSearch"
                        class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-md text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all placeholder-slate-400 h-9"
                        placeholder="พิมพ์ค้นหาชื่อรุ่นรถ, ยี่ห้อ, ทะเบียน...">
                </div>

                <!-- Found badge & Reset Button -->
                <div class="flex items-center gap-3 flex-wrap">
                    <div class="flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 rounded border border-red-200 text-xs font-bold h-9">
                        <span>ทะเบียนรถทั้งหมด:</span>
                        <span id="vehicleCount" class="font-black tabular-nums">{{ $vehicles->count() }}</span>
                        <span>คัน</span>
                    </div>
                    <button id="resetFilters" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold h-9 px-4 rounded-md text-xs flex items-center gap-1.5 transition-all">
                        <i class="fa-solid fa-rotate-right"></i> ล้างตัวกรอง
                    </button>
                </div>
            </div>

            <!-- Filter Controls Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                <!-- Type Filter -->
                <div class="form-control">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ประเภทรถยนต์</label>
                    <select id="filterType" class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                        <option value="">-- แสดงทั้งหมด --</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fuel Filter -->
                <div class="form-control">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ประเภทเชื้อเพลิง</label>
                    <select id="filterFuel" class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                        <option value="">-- แสดงทั้งหมด --</option>
                        @foreach($fuels as $f)
                            <option value="{{ $f }}">{{ $f }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Seat Filter -->
                <div class="form-control">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">จำนวนที่นั่งโดยสาร</label>
                    <select id="filterSeat" class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                        <option value="">-- แสดงทั้งหมด --</option>
                        @foreach($seats as $s)
                            <option value="{{ $s }}">{{ $s }} ที่นั่ง</option>
                        @endforeach
                    </select>
                </div>

                <!-- Usage Type Filter -->
                <div class="form-control">
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ประเภทสิทธิ์การใช้งาน</label>
                    <select id="filterUsage" class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all">
                        <option value="">-- แสดงทั้งหมด --</option>
                        @foreach($usageTypes as $u)
                            <option value="{{ $u }}">{{ $u == 1 ? 'รถส่วนกลาง' : ($u == 2 ? 'รถประจำตำแหน่ง' : 'อื่น ๆ') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Vehicles Grid (High contrast card deck) -->
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
                    class="vehicle-card bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden flex flex-col h-full hover:border-red-300 hover:shadow-md transition-all">
                    
                    <!-- Image container -->
                    <div class="h-44 bg-slate-50 relative border-b border-slate-100 flex items-center justify-center shrink-0">
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
                            <img src="{{ $imagePathUrl }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-slate-300 flex flex-col items-center">
                                <i class="fa-regular fa-image text-3xl mb-1.5"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">ไม่มีรูปภาพประกอบ</span>
                            </div>
                        @endif

                        <!-- Overdue Return Alert Badge -->
                        @if(in_array($vehicle->vehicle_id, $overdueVehicleIds ?? []))
                            <div class="absolute top-2.5 left-2.5 bg-red-600 text-white px-2.5 py-1 rounded text-[10px] font-black tracking-wide border border-red-500 animate-pulse flex items-center gap-1 shadow-md z-10" title="รถคันนี้เกินเวลากำหนดส่งคืน!">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>เลยกำหนดคืนรถ</span>
                            </div>
                        @endif

                        <!-- Plate Badge -->
                        <div class="absolute top-2.5 right-2.5 bg-slate-900/90 text-white px-2.5 py-1 rounded text-[10px] font-black tracking-wide border border-slate-800">
                            {{ $vehicle->name }}
                        </div>
                    </div>

                    <!-- Specs details -->
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-[10px] font-black text-red-700 uppercase tracking-widest block mb-1">
                            {{ $vehicle->brand ?? 'ไม่ระบุแบรนด์' }}
                        </span>
                        <h3 class="text-base font-black text-slate-900 leading-snug tracking-tight mb-3">
                            {{ $vehicle->model_name ?? '-' }}
                        </h3>

                        <!-- Feature block grid -->
                        <div class="grid grid-cols-3 gap-1 bg-slate-50 p-2.5 rounded border border-slate-200/60 mb-4 flex-grow items-center text-center">
                            <div>
                                <span class="text-[8px] text-slate-400 font-bold block uppercase">ที่นั่ง</span>
                                <span class="font-black text-xs text-slate-700 block mt-0.5">{{ $vehicle->seat ?? '-' }} ที่</span>
                            </div>
                            <div class="border-x border-slate-200">
                                <span class="text-[8px] text-slate-400 font-bold block uppercase">เชื้อเพลิง</span>
                                <span class="font-black text-[10px] text-slate-700 block mt-0.5 truncate px-1" title="{{ $vehicle->filling_type ?? '-' }}">
                                    {{ $vehicle->filling_type ?? '-' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-[8px] text-slate-400 font-bold block uppercase">ประเภท</span>
                                <span class="font-black text-[10px] text-slate-700 block mt-0.5 truncate px-1" title="{{ $vehicle->type ?? '-' }}">
                                    {{ $vehicle->type ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($vehicle->desciption)
                            <div class="border-t border-slate-100 pt-3 mb-4">
                                <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2" title="{{ $vehicle->desciption }}">
                                    {{ $vehicle->desciption }}
                                </p>
                            </div>
                        @endif

                        <!-- CTA Book button -->
                        <div class="mt-auto pt-2 border-t border-slate-100">
                            <a href="{{ route('bookingcar.welcome') }}?vehicle_id={{ $vehicle->vehicle_id }}"
                                class="w-full py-2 bg-red-700 hover:bg-red-800 text-white font-bold rounded text-xs flex items-center justify-center gap-1.5 transition-all shadow-sm">
                                <i class="fa-regular fa-calendar-plus text-xs"></i> เลือกและดำเนินการจองรถ
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white border border-slate-200 rounded-lg shadow-sm text-slate-400 italic">
                    <div class="flex flex-col items-center gap-3">
                        <i class="fa-solid fa-car-slash text-4xl opacity-20"></i>
                        <span>ไม่พบข้อมูลทะเบียนรถยนต์ส่วนกลางลงทะเบียนไว้ในสารบบ</span>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Hidden Alert on no match -->
        <div id="noResults" class="hidden py-16 text-center bg-white border border-slate-200 rounded-lg shadow-sm text-slate-400">
            <div class="w-12 h-12 bg-slate-50 border border-slate-150 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-magnifying-glass text-slate-300"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-700">ไม่พบยานพาหนะตามคำค้นหาและตัวกรองดังกล่าว</h4>
            <p class="text-xs text-slate-400 mt-1">กรุณาลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือกดล้างตัวกรอง</p>
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

                    const textMatch = !term || 
                                    cName.includes(term) || 
                                    cModel.includes(term) || 
                                    cBrand.includes(term);
                    
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