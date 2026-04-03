@extends('layouts.bookingcar.appcar')
@section('content')
    <div class="max-w-7xl mx-auto">

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success shadow-lg mb-4">
                <div>
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @php
            $isReturnError = $errors->any() && collect($errors->keys())->contains(fn($k) => str_starts_with($k, 'mileage_') || str_starts_with($k, 'attachment_') || str_starts_with($k, 'note_returning'));
        @endphp

        @if(session('error'))
            <div class="alert alert-error shadow-lg mb-4">
                <div>
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Main Container -->
        <div class="flex flex-col-reverse lg:grid lg:grid-cols-4 gap-6">

            <!-- Left Column: Vehicle Information (Bottom on Mobile) -->
            <div class="lg:col-span-1 space-y-4">
                <h2 class="text-xl font-bold text-slate-800"><i class="fa-solid fa-car text-[#c31919] mr-2"></i>
                    ข้อมูลรถส่วนกลาง</h2>
                @foreach($previewVehicles as $vehicle)
                    <div class="card bg-white shadow-sm border border-slate-200 p-0 transition-transform hover:scale-[1.02]">
                        <!-- Vehicle Image -->
                        <figure class="h-32 bg-slate-100 relative border-b border-slate-200 flex items-center justify-center">
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
                                    <i class="fa-regular fa-image text-2xl mb-1"></i>
                                    <span class="text-xs">ไม่มีรูปภาพ</span>
                                </div>
                            @endif
                        </figure>

                        <!-- Vehicle Info -->
                        <div class="p-4">
                            <h3 class="font-bold text-[#c31919] uppercase text-sm mb-1">{{ $vehicle->name }}
                                ({{ $vehicle->model_name }})</h3>
                            <p class="text-[11px] text-slate-600 mb-2 truncate" title="{{ $vehicle->brand }}">
                                ยี่ห้อ: {{ $vehicle->brand ?? '-' }} | ทะเบียนรถ: {{ $vehicle->name }}
                            </p>

                            <div class="space-y-1 mb-3 text-xs text-slate-700">
                                <p class="flex items-center"><i class="fa-solid fa-users w-5 text-center text-slate-400"></i>
                                    <span>ที่นั่ง: <span class="font-medium">{{ $vehicle->seat }}</span> ที่นั่ง</span>
                                </p>
                                <p class="flex items-start"><i
                                        class="fa-solid fa-gas-pump w-5 text-center text-slate-400 mt-1"></i>
                                    <span class="break-words whitespace-normal flex-1">เชื้อเพลิง:
                                        {{ $vehicle->filling_type ?? '-' }}</span>
                                </p>
                                <p class="flex items-start"><i
                                        class="fa-solid fa-car-side w-5 text-center text-slate-400 mt-1"></i>
                                    <span class="break-words whitespace-normal flex-1">ประเภท:
                                        {{ $vehicle->type ?? '-' }}</span>
                                </p>
                            </div>

                            @if($vehicle->desciption)
                                <div class="mt-2 text-xs text-slate-600 line-clamp-2" title="{{ $vehicle->desciption }}">
                                    <i class="fa-solid fa-circle-info text-slate-400 mr-1"></i> {{ $vehicle->desciption }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right Column: Calendar & Bookings (Top on Mobile) -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Calendar Card -->
                <div class="border border-gray-200/60 rounded-xl shadow-lg p-4 md:p-6 bg-slate-50">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fa-solid fa-calendar-days text-[#c31919] mr-2"></i> ปฏิทินจองรถส่วนกลาง
                        </h2>
                        <button onclick="showBookingCarModal()"
                            class="btn btn-sm bg-gradient-to-r from-[#e53935] to-[#c62828] hover:from-[#d32f2f] hover:to-[#b71c1c] text-white border-0 shadow-md">
                            <i class="fa-solid fa-plus mr-1"></i> เพิ่มการจอง
                        </button>
                    </div>
                    <div id='calendar' class="fc-theme-standard"></div>
                </div>

                <!-- Upcoming/Pending Bookings Board -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/60 overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-[#c31919] to-red-800 p-3 md:p-4 shrink-0 shadow-md relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/5"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <h3 class="text-white text-md md:text-lg font-bold tracking-tight flex items-center">
                                <i
                                    class="fa-solid fa-clipboard-list mr-2 text-red-200"></i>รายการจองเตรียมเดินทาง/อยู่ระหว่างเดินทาง
                            </h3>
                            <div class="bg-white/20 px-2.5 py-1 text-xs text-white rounded-full font-medium">
                                {{ $upcomingBookings->count() }} รายการ
                            </div>
                        </div>
                    </div>

                    <div class="p-3 md:p-5 bg-slate-50">
                        <div class="space-y-3 md:space-y-4 max-h-[500px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar">
                            @forelse ($upcomingBookings as $booking)
                                @php
                                    $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                    $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                    $isOverdue = $bookingEnd->isPast() && $booking->return_status === 'ยังไม่ส่งคืน';
                                @endphp

                                <div
                                    class="bg-white p-3 md:p-4 rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                                    <div
                                        class="absolute top-0 left-0 w-1.5 h-full {{ $isOverdue ? 'bg-orange-500' : 'bg-[#c31919]' }} rounded-l-xl">
                                    </div>
                                    <div class="absolute top-3 right-3 md:right-4">
                                        @if($isOverdue)
                                            <span
                                                class="inline-flex flex-col items-center bg-orange-50 text-orange-600 px-2.5 py-1 rounded-lg border border-orange-100 shadow-sm relative overflow-hidden animate-pulse">
                                                <span
                                                    class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-orange-800 mb-0.5">แจ้งเตือน</span>
                                                <span class="text-[10px] md:text-xs font-bold whitespace-nowrap"><i
                                                        class="fa-solid fa-triangle-exclamation mr-1"></i>ยังไม่ส่งคืนรถ</span>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex flex-col items-center bg-green-50 text-green-600 px-2.5 py-1 rounded-lg border border-green-100 shadow-sm relative overflow-hidden">
                                                <span
                                                    class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-green-800 mb-0.5">สถานะ</span>
                                                <span class="text-[10px] md:text-xs font-bold whitespace-nowrap"><i
                                                        class="fa-solid fa-check-circle mr-1"></i>{{ $booking->status }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col xl:flex-row gap-3 md:gap-4 xl:gap-5">
                                        <!-- Time Block -->
                                        <div
                                            class="xl:w-28 shrink-0 py-1 xl:border-r border-slate-100 xl:pr-3 flex xl:flex-col gap-2 justify-between items-center xl:items-start text-sm border-b xl:border-b-0 pb-2 xl:pb-0 mb-2 xl:mb-0">
                                            <div
                                                class="text-left flex xl:flex-col justify-start items-center xl:items-start gap-1.5 md:gap-2">
                                                <div
                                                    class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 text-right xl:text-left">
                                                    <i class="fa-regular fa-calendar-check mr-1 hidden md:inline"></i>เริ่ม
                                                </div>
                                                <div class="font-bold text-slate-800 text-xs md:text-sm">
                                                    {{ $bookingStart->format('d M y') }}</div>
                                                <div
                                                    class="text-[10px] md:text-xs font-medium text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded text-center xl:text-left">
                                                    <i class="fa-regular fa-clock mr-1"></i>{{ $bookingStart->format('H:i') }}
                                                    น.</div>
                                            </div>
                                            <i class="fa-solid fa-arrow-right text-slate-300 xl:hidden"></i>
                                            <div
                                                class="text-left flex xl:flex-col justify-start items-center xl:items-start gap-1.5 md:gap-2">
                                                <div
                                                    class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5 text-right xl:text-left">
                                                    <i class="fa-regular fa-calendar-xmark mr-1 hidden md:inline"></i>ถึง</div>
                                                <div class="font-bold text-slate-800 text-xs md:text-sm">
                                                    {{ $bookingEnd->format('d M y') }}</div>
                                                <div
                                                    class="text-[10px] md:text-xs font-medium text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded text-center xl:text-left">
                                                    <i class="fa-regular fa-clock mr-1"></i>{{ $bookingEnd->format('H:i') }} น.
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Details Block -->
                                        <div class="flex-1 min-w-0 pr-16 md:pr-24">
                                            <div class="flex items-center gap-2 mb-2 mt-1">
                                                <h4 class="text-sm md:text-md font-black text-slate-800 truncate group-hover:text-red-600 transition-colors">
                                                    {{ $booking->vehicle->name ?? 'รถส่วนกลาง' }}
                                                </h4>
                                                @if($booking->vehicle->model_name)
                                                    <span class="badge badge-xs bg-slate-100 text-slate-500 border-none px-2 py-2 h-auto rounded font-bold">
                                                        {{ $booking->vehicle->model_name }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 gap-y-1.5 gap-x-3">
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-user-tie w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>ผู้จอง:</strong>
                                                        {{ ($booking->user->first_name ?? 'N/A') . ' ' . ($booking->user->last_name ?? '') }}</span>
                                                </div>
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-briefcase w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>เจ้าของงาน:</strong> {{ $booking->requester_name ?? '-' }}</span>
                                                </div>

                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-users w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>จำนวนผู้โดยสาร:</strong> {{ $booking->passenger_count ?? 1 }} คน</span>
                                                </div>

                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-location-dot w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"
                                                        title="{{ $booking->destination }} (อ.{{ $booking->district }} จ.{{ $booking->province }})">
                                                        <strong>ปลายทาง:</strong>
                                                        {{ $booking->destination }} 
                                                        <span class="text-slate-400 font-medium">
                                                            (อ.{{ $booking->district }} จ.{{ $booking->province }})
                                                        </span>
                                                    </span>
                                                </div>

                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-regular fa-comment-dots w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate italic ml-1" title="{{ $booking->purpose ?? '-' }}">
                                                        "{{ $booking->purpose ?? '-' }}"
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 px-4 rounded-xl bg-white border border-slate-100">
                                    <div
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3 border border-slate-100">
                                        <i class="fa-regular fa-folder-open text-xl text-slate-400"></i>
                                    </div>
                                    <h4 class="text-sm md:text-md font-bold text-slate-700 mb-1">ยังไม่มีคิวการจองเตรียมเดินทาง
                                    </h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recently Returned Bookings Board -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200/60 overflow-hidden mt-6">
                    <div
                        class="bg-gradient-to-r from-emerald-600 to-teal-700 p-3 md:p-4 shrink-0 shadow-md relative overflow-hidden">
                        <div class="absolute inset-0 bg-black/5"></div>
                        <div class="relative z-10 flex items-center justify-between">
                            <h3 class="text-white text-md md:text-lg font-bold tracking-tight flex items-center">
                                <i class="fa-solid fa-square-check mr-2 text-emerald-200"></i>บันทึกการเดินทางที่เสร็จสิ้น
                                (ล่าสุด)
                            </h3>
                            <div class="bg-white/20 px-2.5 py-1 text-xs text-white rounded-full font-medium">
                                {{ $returnedBookings->count() }} รายการ
                            </div>
                        </div>
                    </div>

                    <div class="p-3 md:p-5 bg-slate-50">
                        <div class="space-y-3 md:space-y-4 max-h-[400px] overflow-y-auto pr-1 md:pr-2 custom-scrollbar">
                            @forelse ($returnedBookings as $booking)
                                @php
                                    $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                    $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                    $returnedAt = $booking->returned_at ? \Carbon\Carbon::parse($booking->returned_at) : null;
                                @endphp

                                <div
                                    class="bg-white p-3 md:p-4 rounded-xl border border-slate-100 shadow-sm opacity-80 hover:opacity-100 transition-all duration-300 relative overflow-hidden group">
                                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500 rounded-l-xl"></div>
                                    <div class="absolute top-3 right-3 md:right-4">
                                        <span
                                            class="inline-flex flex-col items-center bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-lg border border-emerald-100 shadow-sm relative overflow-hidden">
                                            <span
                                                class="text-[9px] md:text-[10px] font-bold uppercase tracking-wider text-emerald-800 mb-0.5">สถานะ</span>
                                            <span class="text-[10px] md:text-xs font-bold whitespace-nowrap"><i
                                                    class="fa-solid fa-circle-check mr-1"></i>ส่งคืนรถแล้ว</span>
                                        </span>
                                    </div>

                                    <div class="flex flex-col xl:flex-row gap-3 md:gap-4 xl:gap-5">
                                        <!-- Details Block -->
                                        <div class="flex-1 min-w-0 pr-16 md:pr-24">
                                            <div class="flex items-center gap-2 mb-2 mt-1">
                                                <h4 class="text-sm md:text-md font-black text-slate-800 truncate group-hover:text-emerald-600 transition-colors">
                                                    {{ $booking->vehicle->name ?? 'รถส่วนกลาง' }}
                                                </h4>
                                                @if($booking->vehicle->model_name)
                                                    <span class="badge badge-xs bg-slate-100 text-slate-500 border-none px-2 py-2 h-auto rounded font-bold">
                                                        {{ $booking->vehicle->model_name }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-1 gap-y-1.5 gap-x-3">
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-user-tie w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>ผู้จอง:</strong>
                                                        {{ ($booking->user->first_name ?? 'N/A') . ' ' . ($booking->user->last_name ?? '') }}</span>
                                                </div>
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-briefcase w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>เจ้าของงาน:</strong> {{ $booking->requester_name ?? '-' }}</span>
                                                </div>
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-users w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1 mr-4"><strong>จำนวนผู้โดยสาร:</strong> {{ $booking->passenger_count ?? 1 }} คน</span>
                                                    <span class="badge badge-xs {{ $booking->driver_request ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-50 text-slate-400 border-slate-100' }} rounded-lg font-bold px-2 py-2">
                                                        <i class="fa-solid fa-steering-wheel mr-1"></i> {{ $booking->driver_request ? 'ขอพนักงานขับรถ' : 'ขับเอง' }}
                                                    </span>
                                                </div>
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-location-dot w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"
                                                        title="{{ $booking->destination }} (อ.{{ $booking->district }} จ.{{ $booking->province }})">
                                                        <strong>ปลายทาง:</strong>
                                                        {{ $booking->destination }} 
                                                        <span class="text-slate-400 font-medium">
                                                            (อ.{{ $booking->district }} จ.{{ $booking->province }})
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-xs md:text-sm flex items-start text-slate-600">
                                                    <i class="fa-solid fa-clock w-4 mt-0.5 text-slate-400"></i>
                                                    <span class="truncate ml-1"><strong>เวลา:</strong>
                                                        {{ $bookingStart->format('d/m/Y H:i') }} -
                                                        {{ $bookingStart->isSameDay($bookingEnd) ? $bookingEnd->format('H:i') : $bookingEnd->format('d/m/Y H:i') }}</span>
                                                </div>
                                                @if($returnedAt)
                                                    <div class="text-xs md:text-sm flex items-start text-emerald-600 font-medium">
                                                        <i class="fa-solid fa-calendar-check w-4 mt-0.5 text-emerald-400"></i>
                                                        <span class="truncate ml-1"><strong>คืนเมื่อ:</strong>
                                                            {{ $returnedAt->format('d/m/Y H:i') }} น.</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 px-4 rounded-xl bg-white border border-slate-100">
                                    <h4 class="text-sm md:text-md font-bold text-slate-400 mb-1 italic">
                                        ไม่มีบันทึกการเดินทางที่เสร็จสิ้นล่าสุด</h4>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Booking Modal -->
    <dialog id="booking_modal" class="modal">
        <div class="modal-box w-11/12 max-w-4xl p-6 relative overflow-y-auto overflow-x-hidden">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                onclick="document.getElementById('booking_modal').close()"><i class="fa-solid fa-xmark"></i></button>
            <h3 class="font-bold text-lg mb-4 text-[#c31919] pb-2 border-b border-slate-100">
                <i class="fa-solid fa-car mr-2"></i> แบบฟอร์มจองรถส่วนกลาง (คำร้อง)
            </h3>

            <form action="{{ route('bookingcar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                    @if ($errors->any() && !$isReturnError)
                        <div
                            class="alert alert-error text-sm text-white bg-red-500 rounded-lg p-3 mb-4 flex gap-2 w-full col-span-full">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                <p class="font-bold mb-1">เกิดข้อผิดพลาดในการบันทึกข้อมูล:</p>
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                        <!-- Column 1: Core Information -->
                        <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <h4 class="font-bold text-slate-700 text-sm mb-2"><i
                                    class="fa-solid fa-circle-info text-slate-400 mr-1"></i>
                                ข้อมูลหลักการจอง</h4>

                            <div class="form-control mb-1">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">ผู้จอง</span></label>
                                <input type="text"
                                    value="{{ Auth::user() ? Auth::user()->employee_code . ' - ' . Auth::user()->first_name . ' ' . Auth::user()->last_name : 'Guest' }}"
                                    class="input input-sm input-bordered w-full bg-slate-200 text-slate-500 text-[13px]"
                                    readonly />
                            </div>

                            <!-- รถส่วนกลาง -->
                            <div class="form-control mb-1">
                                <label class="label py-1"><span class="label-text font-semibold text-red-600">รถส่วนกลาง
                                        <span class="text-red-500">*</span></span></label>
                                <select name="vehicle_id" id="vehicle_select"
                                    class="select select-sm select-bordered w-full border-red-200 focus:border-red-400 text-[13px]"
                                    required>
                                    <option value="" disabled {{ old('vehicle_id', request('vehicle_id')) ? '' : 'selected' }}>
                                        -- เลือกรถส่วนกลาง --
                                    </option>
                                    @foreach($vehicles as $vehicle)
                                        @php
                                            $origText = "$vehicle->name ($vehicle->model_name - $vehicle->seat ที่นั่ง)";
                                        @endphp
                                        <option value="{{ $vehicle->vehicle_id }}" data-original-text="{{ $origText }}" {{ old('vehicle_id', request('vehicle_id')) == $vehicle->vehicle_id ? 'selected' : '' }}>
                                            {{ $origText }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Project Owner -->
                            <div class="form-control mb-1">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">ชื่อเจ้าของงาน /
                                        ผู้มอบหมายภารกิจ</span></label>
                                <input type="text" name="requester_name" value="{{ old('requester_name') }}"
                                    class="input input-sm input-bordered w-full text-[13px]"
                                    placeholder="เช่น ชื่อผู้จัดการฝ่ายฯ" />
                            </div>

                            <!-- Attachment -->
                            <div class="form-control mb-1 mt-2">
                                <label class="label py-1"><span class="label-text font-semibold text-slate-700">แนบไฟล์
                                        (เอกสารประกอบการจอง)</span></label>
                                <input type="file" name="attachment"
                                    class="file-input file-input-bordered w-full text-[13px] h-[36px]" />
                            </div>
                        </div>

                        <!-- Column 2: Additional Details -->
                        <div class="space-y-3 p-4 bg-white rounded-xl border border-slate-100">
                            <h4 class="font-bold text-slate-700 text-sm mb-2"><i
                                    class="fa-solid fa-list-ul text-slate-400 mr-1"></i>
                                รายละเอียดการเดินทาง</h4>

                            <!-- วันที่และเวลาที่จอง -->
                            <div class="form-control mb-4">
                                <label class="label py-1 flex justify-between items-center w-full">
                                    <span class="label-text font-semibold text-red-600">ระบุวันเวลาที่เดินทาง <span
                                            class="text-red-500">*</span></span>
                                    <button type="button" class="btn btn-xs btn-outline btn-info text-[11px] font-normal"
                                        onclick="document.getElementById('time_info_modal').showModal()">
                                        <i class="fa-regular fa-clock mr-1"></i> ตัวอย่างเวลา
                                    </button>
                                </label>

                                <div class="grid grid-cols-2 gap-3 mt-1">
                                    <div class="w-full">
                                        <div class="text-[12px] text-[#c31919] font-bold mb-1"><i
                                                class="fa-solid fa-calendar-day mr-1"></i>วันที่เริ่มเดินทาง</div>
                                        <input type="date" name="booking_date" id="booking_date"
                                            value="{{ old('booking_date') }}"
                                            class="input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                                            required />
                                    </div>
                                    <div class="w-full">
                                        <div class="text-[12px] text-[#c31919] font-bold mb-1"><i
                                                class="fa-solid fa-calendar-check mr-1"></i>วันที่สิ้นสุด</div>
                                        <input type="date" name="booking_date_end" id="booking_date_end"
                                            value="{{ old('booking_date_end') }}"
                                            class="input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                                            required />
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mt-1">
                                    <!-- Departure Time Picker -->
                                    <div class="w-full">
                                        <div class="text-[12px] text-slate-500 font-medium mb-1">เวลาออกเดินทาง</div>
                                        <div class="custom-time-picker" id="tp_start_container">
                                            <div class="time-display input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                                                onclick="toggleTimePicker('start')">
                                                <span id="start_time_text">{{ old('start_time', '08:30') }}</span>
                                                <i class="fa-regular fa-clock text-slate-400"></i>
                                            </div>
                                            <input type="hidden" name="start_time" id="start_time"
                                                value="{{ old('start_time', '08:30') }}">

                                            <div id="tp_start_dropdown" class="time-picker-dropdown">
                                                <div class="tp-column">
                                                    <div class="tp-header" id="tp_start_h_header">08</div>
                                                    <div class="tp-options" id="tp_start_h_options">
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <div class="tp-option"
                                                                onclick="setTimePart('start', 'h', '{{ sprintf('%02d', $i) }}')">
                                                                {{ sprintf('%02d', $i) }}</div>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="tp-column">
                                                    <div class="tp-header" id="tp_start_m_header">30</div>
                                                    <div class="tp-options" id="tp_start_m_options">
                                                        @for ($i = 0; $i < 60; $i++)
                                                            <div class="tp-option"
                                                                onclick="setTimePart('start', 'm', '{{ sprintf('%02d', $i) }}')">
                                                                {{ sprintf('%02d', $i) }}</div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Return Time Picker -->
                                    <div class="w-full">
                                        <div class="text-[12px] text-slate-500 font-medium mb-1">เวลากลับโดยประมาณ</div>
                                        <div class="custom-time-picker" id="tp_end_container">
                                            <div class="time-display input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                                                onclick="toggleTimePicker('end')">
                                                <span id="end_time_text">{{ old('end_time', '17:00') }}</span>
                                                <i class="fa-regular fa-clock text-slate-400"></i>
                                            </div>
                                            <input type="hidden" name="end_time" id="end_time"
                                                value="{{ old('end_time', '17:00') }}">

                                            <div id="tp_end_dropdown" class="time-picker-dropdown">
                                                <div class="tp-column">
                                                    <div class="tp-header" id="tp_end_h_header">17</div>
                                                    <div class="tp-options" id="tp_end_h_options">
                                                        @for ($i = 0; $i < 24; $i++)
                                                            <div class="tp-option"
                                                                onclick="setTimePart('end', 'h', '{{ sprintf('%02d', $i) }}')">
                                                                {{ sprintf('%02d', $i) }}</div>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="tp-column">
                                                    <div class="tp-header" id="tp_end_m_header">00</div>
                                                    <div class="tp-options" id="tp_end_m_options">
                                                        @for ($i = 0; $i < 60; $i++)
                                                            <div class="tp-option"
                                                                onclick="setTimePart('end', 'm', '{{ sprintf('%02d', $i) }}')">
                                                                {{ sprintf('%02d', $i) }}</div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Destination -->
                            <div class="form-control mb-1">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-red-600">จุดหมายปลายทางของการเดินทาง
                                        <span class="text-red-500">*</span></span></label>
                                <input type="text" name="destination" value="{{ old('destination') }}"
                                    class="input input-sm input-bordered w-full border-red-200 focus:border-red-400 text-[13px]"
                                    placeholder="ระบุสถานที่ปลายทางที่จะเดินทางไป" required />
                            </div>

                            <!-- District & Province -->
                            <div class="grid grid-cols-2 gap-3 mb-1">
                                <div class="form-control">
                                    <label class="label py-1"><span
                                            class="label-text font-semibold text-red-600">จังหวัดที่จะเดินทางไป <span
                                                class="text-red-500">*</span></span></label>
                                    <select name="province" id="province_select" 
                                        class="select select-sm select-bordered w-full border-red-200 focus:border-red-400 text-[13px]" 
                                        required>
                                        <option value="" disabled {{ old('province') ? '' : 'selected' }}>-- เลือกจังหวัด --</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="label py-1"><span
                                            class="label-text font-semibold text-red-600">อำเภอที่เดินทางไป <span
                                                class="text-red-500">*</span></span></label>
                                    <select name="district" id="district_select" 
                                        class="select select-sm select-bordered w-full border-red-200 focus:border-red-400 text-[13px]" 
                                        required {{ old('province') ? '' : 'disabled' }}>
                                        <option value="" disabled {{ old('district') ? '' : 'selected' }}>-- เลือกอำเภอ --</option>
                                        @if(old('district'))
                                            <option value="{{ old('district') }}" selected>{{ old('district') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="form-control mb-1">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">วัตถุประสงค์ของการใช้รถ</span></label>
                                <textarea name="purpose" class="textarea textarea-bordered text-[13px] h-20"
                                    placeholder="ระบุวัตถุประสงค์ในการเดินทาง...">{{ old('purpose') }}</textarea>
                            </div>

                            <!-- Participants -->
                            <div class="grid grid-cols-2 gap-3 mb-1">
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-semibold text-slate-700">จำนวนผู้โดยสาร (ท่าน)</span></label>
                                    <input type="number" name="passenger_count"
                                        class="input input-sm input-bordered w-full text-[13px]" placeholder="ระบุจำนวนคน" min="1"
                                        value="{{ old('passenger_count', 1) }}" />
                                </div>
                                <div class="form-control">
                                    <label class="label py-1"><span class="label-text font-semibold text-slate-700">พนักงานขับรถ</span></label>
                                    <div class="flex items-center h-[36px] px-3 bg-white border border-slate-200 rounded-lg shadow-sm gap-2 mt-0.5">
                                        <input type="checkbox" name="driver_request" id="driver_request" value="1" 
                                            class="checkbox checkbox-xs checkbox-error" {{ old('driver_request') ? 'checked' : '' }} />
                                        <label for="driver_request" class="text-[12px] font-bold text-red-600 cursor-pointer select-none">ต้องการพนักงานขับรถ</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Column 3: Return Information (Optional/System) -->
                    <div class="col-span-full mt-4 space-y-4 p-5 bg-orange-50/50 rounded-2xl border border-orange-200/60">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-flag-checkered text-orange-500"></i>
                                ข้อมูลหลังการเดินทาง (สำหรับการคืนรถ)
                            </h4>
                        </div>

                        <!-- Notice Box -->
                        <div class="bg-white/80 border-l-4 border-orange-500 p-3 rounded-r-lg shadow-sm mb-4">
                            <p class="text-[12px] text-orange-800 font-medium leading-relaxed">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                                <span class="font-bold underline">ชี้แจง:</span> ข้อมูลในส่วนนี้ <span class="text-red-600 font-bold italic">ไม่ต้องกรอกในขณะที่ทำการจอง</span> 
                                และจะถูกปิดไว้เพื่อป้องกันความสับสน โดยจะใช้สำหรับบันทึกข้อมูลตอนคืนรถเท่านั้น
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-70 grayscale-[0.2]">
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-500">เลขไมล์ก่อนเดินทาง</span>
                                </label>
                                <input type="number" name="mileage_before" value="{{ old('mileage_before') }}" disabled
                                    class="input input-sm input-bordered w-full text-[13px] bg-slate-50 border-orange-100 cursor-not-allowed"
                                    placeholder="ล็อกไว้ (รอคืนรถ)" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-500">เลขไมล์หลังเดินทาง</span>
                                </label>
                                <input type="number" name="mileage_after" value="{{ old('mileage_after') }}" disabled
                                    class="input input-sm input-bordered w-full text-[13px] bg-slate-50 border-orange-100 cursor-not-allowed"
                                    placeholder="ล็อกไว้ (รอคืนรถ)" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-500">แนบรูปภาพรถตอนไป</span>
                                </label>
                                <input type="file" name="attachment_going[]" multiple accept="image/*" disabled
                                    class="file-input file-input-bordered file-input-xs w-full text-[13px] bg-slate-50 border-orange-100 cursor-not-allowed" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-500">แนบรูปภาพรถตอนกลับ</span>
                                </label>
                                <input type="file" name="attachment_returning[]" multiple accept="image/*" disabled
                                    class="file-input file-input-bordered file-input-xs w-full text-[13px] bg-slate-50 border-orange-100 cursor-not-allowed" />
                            </div>
                        </div>
                        <div class="form-control mb-1 mt-2 opacity-70 grayscale-[0.2]">
                            <label class="label py-1">
                                <span class="label-text font-semibold text-slate-500">หมายเหตุคืนรถ</span>
                            </label>
                            <textarea name="note_returning" disabled
                                class="textarea textarea-bordered text-[11px] h-14 bg-slate-50 border-orange-100 cursor-not-allowed"
                                placeholder="ล็อกไว้ (รอคืนรถ)...">{{ old('note_returning') }}</textarea>
                        </div>

                        <!-- Checkbox at the bottom -->
                        <div class="mt-4 pt-4 border-t border-orange-200/50 flex justify-center">
                            <div class="flex items-center gap-3 bg-white px-6 py-3 rounded-2xl border-2 border-orange-400 shadow-md hover:border-orange-500 transition-all group">
                                <input type="checkbox" id="ack_return_info" name="ack_return_info" required class="checkbox checkbox-md checkbox-warning ring-orange-200 ring-offset-2 focus:ring-2" />
                                <label for="ack_return_info" class="text-[14px] font-black text-orange-900 cursor-pointer select-none">
                                    รับทราบข้อมูล (ส่วนนี้ใช้สำหรับบันทึกข้อมูลตอนคืนรถเท่านั้น) <span class="text-red-500 ml-1 italic">* จำเป็น</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-action mt-6 pt-4 border-t border-slate-100 col-span-full">
                        <button type="button" class="btn btn-sm"
                            onclick="document.getElementById('booking_modal').close()">ยกเลิก</button>
                        <button type="submit" id="submit_booking"
                            class="btn btn-sm bg-gradient-to-r from-[#e53935] to-[#c62828] hover:from-[#d32f2f] hover:to-[#b71c1c] text-white border-0 shadow-[0_4px_10px_rgba(229,57,53,0.3)] disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            <i class="fa-solid fa-paper-plane mr-1 text-[12px]"></i> เสนอใบคำร้องจองรถ
                        </button>
                    </div>
                </form>
            </div>
        </dialog>
        <!-- Return Car Modal (For Users) -->
        <dialog id="return_car_modal" class="modal">
            <div class="modal-box w-11/12 max-w-2xl p-0 overflow-hidden rounded-2xl border-0 shadow-2xl">
                <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6">
                    <div class="flex justify-between items-center text-white">
                        <h3 class="font-bold text-xl flex items-center gap-2">
                            <i class="fa-solid fa-flag-checkered"></i> บันทึกข้อมูลการคืนรถ
                        </h3>
                        <button type="button" class="btn btn-sm btn-circle btn-ghost text-white hover:bg-white/20"
                            onclick="document.getElementById('return_car_modal').close()">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <form id="return_car_form" method="POST" enctype="multipart/form-data" class="p-6 bg-white">
                    @csrf
                    <input type="hidden" name="booking_id_hidden" id="ret_booking_id" value="{{ old('booking_id_hidden') }}">

                    @if ($isReturnError)
                        <div
                            class="alert alert-error text-sm text-white bg-red-500 rounded-lg p-3 mb-4 flex gap-2 w-full">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                <p class="font-bold mb-1">เกิดข้อผิดพลาดในการบันทึกข้อมูลการคืนรถ:</p>
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 mb-6">
                        <h4 class="font-bold text-slate-700 text-sm mb-4">
                            <i class="fa-solid fa-info-circle text-orange-400 mr-1"></i>
                            ข้อมูลหลังการเดินทาง (สำหรับการคืนรถ)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-700">เลขไมล์ก่อนเดินทาง</span>
                                </label>
                                <input type="number" name="mileage_before" id="ret_mileage_before"
                                    class="input input-bordered w-full text-[13px] h-[38px] border-orange-200 focus:border-orange-400 focus:ring-1 focus:ring-orange-400 shadow-sm"
                                    placeholder="ระบุเลขไมล์ก่อนใช้งาน" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-700">เลขไมล์หลังเดินทาง <span class="text-red-500">*</span></span>
                                </label>
                                <input type="number" name="mileage_after" id="ret_mileage_after" required
                                    class="input input-bordered w-full text-[13px] h-[38px] border-orange-200 focus:border-orange-400 focus:ring-1 focus:ring-orange-400 shadow-sm"
                                    placeholder="ระบุเลขไมล์หลังใช้งาน" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-700">แนบรูปภาพรถตอนไป</span>
                                </label>
                                <input type="file" name="attachment_going[]" multiple accept="image/*"
                                    class="file-input file-input-bordered file-input-sm w-full text-[13px] border-orange-200" />
                            </div>
                            <div class="form-control mb-1">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-slate-700">แนบรูปภาพรถตอนกลับ</span>
                                </label>
                                <input type="file" name="attachment_returning[]" multiple accept="image/*"
                                    class="file-input file-input-bordered file-input-sm w-full text-[13px] border-orange-200" />
                            </div>
                        </div>
                        <div class="form-control mb-1 mt-3">
                            <label class="label py-1">
                                <span class="label-text font-semibold text-slate-700">หมายเหตุคืนรถ</span>
                            </label>
                            <textarea name="note_returning" id="ret_note"
                                class="textarea textarea-bordered text-[13px] h-20 border-orange-200 focus:border-orange-400 focus:ring-1 focus:ring-orange-400"
                                placeholder="ระบุหมายเหตุเพิ่มเติม (ถ้ามี) เช่น รถมีรอยขีดข่วน..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-slate-100">
                        <button type="button" class="btn btn-ghost btn-sm text-slate-500"
                            onclick="document.getElementById('return_car_modal').close()">ยกเลิก</button>
                        <button type="submit"
                            class="btn btn-sm bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white border-0 shadow-lg px-6">
                            <i class="fa-solid fa-check-circle mr-1"></i> ยืนยันการคืนรถ
                        </button>
                    </div>
                </form>
            </div>
        </dialog>

        <!-- Time Info Modal -->
        <dialog id="time_info_modal" class="modal">
            <div class="modal-box w-11/12 max-w-4xl p-6 relative">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                    onclick="document.getElementById('time_info_modal').close()"><i class="fa-solid fa-xmark"></i></button>
                <h3 class="font-bold text-lg mb-4 text-blue-800 pb-2 border-b border-slate-100">
                    <i class="fa-regular fa-clock mr-2"></i> ตัวอย่างเวลาการเทียบรูปแบบ a.m. / p.m.
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- AM Section -->
                    <div>
                        <div class="bg-blue-50 p-3 rounded-lg mb-3">
                            <h4 class="font-bold text-blue-700">a.m. (Ante Meridiem)</h4>
                            <p class="text-[13px] text-slate-600 mt-1">
                                คำที่มาจากภาษาลาตินหมายถึง <span class="font-semibold">"ก่อนเที่ยงวัน"</span>
                                เป็นตัวบอกเวลาในช่วงตั้งแต่ 01.00 น. - 12.00 น. ตามเวลาที่เราคุ้นเคย (เที่ยงคืนถึงเที่ยงวัน)
                            </p>
                        </div>
                        <table class="table table-xs table-zebra w-full text-[12px] border border-slate-200">
                            <thead class="bg-slate-100 text-slate-600">
                                <tr>
                                    <th>รูปแบบ a.m.</th>
                                    <th>เวลา (น.)</th>
                                    <th>ภาษาพูด (ไทย)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1:00 am</td>
                                    <td>01:00 น.</td>
                                    <td>ตีหนึ่ง</td>
                                </tr>
                                <tr>
                                    <td>2:00 am</td>
                                    <td>02:00 น.</td>
                                    <td>ตีสอง</td>
                                </tr>
                                <tr>
                                    <td>3:00 am</td>
                                    <td>03:00 น.</td>
                                    <td>ตีสาม</td>
                                </tr>
                                <tr>
                                    <td>4:00 am</td>
                                    <td>04:00 น.</td>
                                    <td>ตีสี่</td>
                                </tr>
                                <tr>
                                    <td>5:00 am</td>
                                    <td>05:00 น.</td>
                                    <td>ตีห้า</td>
                                </tr>
                                <tr>
                                    <td>6:00 am</td>
                                    <td>06:00 น.</td>
                                    <td>หกโมงเช้า</td>
                                </tr>
                                <tr>
                                    <td>7:00 am</td>
                                    <td>07:00 น.</td>
                                    <td>เจ็ดโมงเช้า</td>
                                </tr>
                                <tr>
                                    <td>8:00 am</td>
                                    <td>08:00 น.</td>
                                    <td>แปดโมงเช้า</td>
                                </tr>
                                <tr>
                                    <td>9:00 am</td>
                                    <td>09:00 น.</td>
                                    <td>เก้าโมง</td>
                                </tr>
                                <tr>
                                    <td>10:00 am</td>
                                    <td>10:00 น.</td>
                                    <td>สิบโมง</td>
                                </tr>
                                <tr>
                                    <td>11:00 am</td>
                                    <td>11:00 น.</td>
                                    <td>สิบเอ็ดโมง</td>
                                </tr>
                                <tr class="bg-red-50 text-red-600 font-medium">
                                    <td>12:00 pm</td>
                                    <td>12:00 น.</td>
                                    <td>เที่ยงวัน</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PM Section -->
                    <div>
                        <div class="bg-amber-50 p-3 rounded-lg mb-3">
                            <h4 class="font-bold text-amber-700">p.m. (Post Meridiem)</h4>
                            <p class="text-[13px] text-slate-600 mt-1">
                                คำมาจากภาษาลาตินหมายถึง <span class="font-semibold">"หลังเที่ยงวัน"</span> เป็นช่วงเวลาตั้งแต่
                                13.00 น. - 24.00 น. (เที่ยงวันถึงเที่ยงคืน)
                            </p>
                        </div>
                        <table class="table table-xs table-zebra w-full text-[12px] border border-slate-200">
                            <thead class="bg-slate-100 text-slate-600">
                                <tr>
                                    <th>รูปแบบ p.m.</th>
                                    <th>เวลา (น.)</th>
                                    <th>ภาษาพูด (ไทย)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1:00 pm</td>
                                    <td>13:00 น.</td>
                                    <td>บ่ายโมง</td>
                                </tr>
                                <tr>
                                    <td>2:00 pm</td>
                                    <td>14:00 น.</td>
                                    <td>บ่ายสอง</td>
                                </tr>
                                <tr>
                                    <td>3:00 pm</td>
                                    <td>15:00 น.</td>
                                    <td>บ่ายสาม</td>
                                </tr>
                                <tr>
                                    <td>4:00 pm</td>
                                    <td>16:00 น.</td>
                                    <td>สี่โมงเย็น</td>
                                </tr>
                                <tr>
                                    <td>5:00 pm</td>
                                    <td>17:00 น.</td>
                                    <td>ห้าโมงเย็น</td>
                                </tr>
                                <tr>
                                    <td>6:00 pm</td>
                                    <td>18:00 น.</td>
                                    <td>หกโมงเย็น</td>
                                </tr>
                                <tr>
                                    <td>7:00 pm</td>
                                    <td>19:00 น.</td>
                                    <td>หนึ่งทุ่ม</td>
                                </tr>
                                <tr>
                                    <td>8:00 pm</td>
                                    <td>20:00 น.</td>
                                    <td>สองทุ่ม</td>
                                </tr>
                                <tr>
                                    <td>9:00 pm</td>
                                    <td>21:00 น.</td>
                                    <td>สามทุ่ม</td>
                                </tr>
                                <tr>
                                    <td>10:00 pm</td>
                                    <td>22:00 น.</td>
                                    <td>สี่ทุ่ม</td>
                                </tr>
                                <tr>
                                    <td>11:00 pm</td>
                                    <td>23:00 น.</td>
                                    <td>ห้าทุ่ม</td>
                                </tr>
                                <tr class="bg-indigo-50 text-indigo-700 font-medium">
                                    <td>12:00 am</td>
                                    <td>00:00 น. / 24:00 น.</td>
                                    <td>เที่ยงคืน</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-sm btn-outline"
                        onclick="document.getElementById('time_info_modal').close()">ปิดหน้าต่าง</button>
                </div>
            </div>
        </dialog>

        <!-- Add FontAwesome since it may not be globally included -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Add SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Add jQuery & Select2 -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            /* Custom Calendar Styling */
            .fc {
                --fc-page-bg-color: transparent;
                --fc-neutral-bg-color: #f8fafc;
                --fc-neutral-text-color: #334155;
                --fc-border-color: #e2e8f0;
                --fc-today-bg-color: #fef2f2;
            }

            .fc .fc-col-header-cell-cushion {
                padding: 8px 4px;
                color: #475569;
                font-weight: 600;
            }

            .fc .fc-daygrid-day-number {
                color: #334155;
                font-weight: 500;
                padding: 4px;
                padding-right: 8px;
            }

            .fc .fc-button-primary {
                background-color: white;
                border-color: #cbd5e1;
                color: #334155;
                font-size: 0.875rem;
                text-transform: capitalize;
            }

            .fc .fc-button-primary:hover {
                background-color: #f8fafc;
                border-color: #94a3b8;
                color: #1e293b;
            }

            .fc .fc-button-primary:not(:disabled):active,
            .fc .fc-button-primary:not(:disabled).fc-button-active {
                background-color: #f1f5f9;
                border-color: #94a3b8;
                color: #0f172a;
            }

            .fc-event {
                border-radius: 6px;
                border: none;
                padding: 2px 4px;
                margin: 1px 2px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                cursor: pointer;
            }

            .fc-event-main {
                color: white;
                font-weight: 500;
                font-size: 0.75rem;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .fc-daygrid-event-dot {
                border-color: white;
            }

            .fc .fc-toolbar-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1e293b;
            }

            /* Custom Time Picker UI - Image 1 Style */
            .custom-time-picker {
                position: relative;
                display: inline-block;
                width: 100%;
            }

            .time-display {
                display: flex;
                align-items: center;
                justify-content: space-between;
                cursor: pointer;
            }

            .time-picker-dropdown {
                position: absolute;
                top: 100%;
                left: 0;
                z-index: 1000;
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                display: none;
                padding: 10px;
                margin-top: 5px;
                min-width: 160px;
                left: 50%;
                transform: translateX(-50%);
            }

            .time-picker-dropdown.active {
                display: flex;
                gap: 10px;
            }

            .tp-column {
                flex: 1;
                display: flex;
                flex-direction: column;
                width: 60px;
            }

            .tp-header {
                background-color: #3b82f6;
                color: white;
                font-weight: bold;
                font-size: 1.125rem;
                padding: 8px 0;
                text-align: center;
                border-radius: 0.5rem;
                margin-bottom: 5px;
            }

            .tp-options {
                height: 180px;
                overflow-y: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .tp-options::-webkit-scrollbar {
                display: none;
            }

            .tp-option {
                padding: 8px 0;
                text-align: center;
                cursor: pointer;
                font-size: 0.875rem;
                color: #475569;
                border-radius: 0.375rem;
                transition: all 0.2s;
            }

            .tp-option:hover {
                background-color: #f1f5f9;
                color: #1e293b;
            }

            .tp-option.selected {
                background-color: #eff6ff;
                color: #3b82f6;
                font-weight: 600;
            }

            /* Select2 Custom Styling to match Tailwind/DaisyUI */
            .select2-container--default .select2-selection--single {
                border-color: #fecaca !important; /* border-red-200 */
                height: 32px !important; /* select-sm */
                border-radius: var(--rounded-btn, 0.5rem) !important;
                background-color: white !important;
                display: flex !important;
                align-items: center !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 32px !important;
                font-size: 13px !important;
                padding-left: 12px !important;
                color: #374151 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 30px !important;
                right: 5px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9ca3af !important;
            }
            .select2-dropdown {
                border: 1px solid #e2e8f0 !important;
                border-radius: 0.75rem !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
                overflow: hidden !important;
                z-index: 9999 !important;
            }
            .select2-search__field {
                border-radius: 0.5rem !important;
                border: 1px solid #e2e8f0 !important;
                padding: 6px 10px !important;
                font-size: 13px !important;
            }
            .select2-results__option {
                font-size: 13px !important;
                padding: 8px 12px !important;
            }
            .select2-results__option--highlighted[aria-selected] {
                background-color: #e53935 !important;
            }
            .select2-container--default.select2-container--disabled .select2-selection--single {
                background-color: #f3f4f6 !important;
                cursor: not-allowed !important;
                border-color: #e5e7eb !important;
            }
            .select2-container--open {
                z-index: 9999999 !important;
            }
        </style>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                window.showReturnCarModal = function(bookingId, mileageBefore) {
                    const modal = document.getElementById('return_car_modal');
                    const form = document.getElementById('return_car_form');
                    const mileageBeforeInput = document.getElementById('ret_mileage_before');

                    // Set the action URL dynamically
                    form.action = `/bookingcar/${bookingId}/return`;

                    // Pre-fill mileage if available
                    if (mileageBefore && mileageBeforeInput) {
                        mileageBeforeInput.value = mileageBefore;
                    }

                    // Set hidden booking id for persistence
                    document.getElementById('ret_booking_id').value = bookingId;

                    modal.showModal();
                };

                // Function to open booking modal with date
                window.showBookingCarModal = function(dateStr) {
                    const modal = document.getElementById('booking_modal');
                    const dateInput = document.getElementById('booking_date');

                    if (dateStr) {
                        dateInput.value = dateStr;
                    } else if (!dateInput.value) {
                        // Default to today if empty
                        const today = new Date().toISOString().split('T')[0];
                        dateInput.value = today;
                    }

                    modal.showModal();
                    if (typeof checkCarAvailability === 'function') {
                        checkCarAvailability();
                    }
                };

                // Custom Time Picker Logic
                window.toggleTimePicker = function(type) {
                    const dropdown = document.getElementById(`tp_${type}_dropdown`);
                    const otherDropdown = type === 'start' ? document.getElementById('tp_end_dropdown') : document.getElementById('tp_start_dropdown');

                    otherDropdown.classList.remove('active');
                    dropdown.classList.toggle('active');

                    if (dropdown.classList.contains('active')) {
                        const currentTime = document.getElementById(`${type}_time`).value || (type === 'start' ? '08:30' : '17:00');
                        const [h, m] = currentTime.split(':');
                        scrollToValue(type, 'h', h);
                        scrollToValue(type, 'm', m);
                    }
                }

                window.setTimePart = function(type, part, value) {
                    const header = document.getElementById(`tp_${type}_${part}_header`);
                    header.innerText = value;

                    const h = document.getElementById(`tp_${type}_h_header`).innerText;
                    const m = document.getElementById(`tp_${type}_m_header`).innerText;
                    const newTime = `${h}:${m}`;

                    document.getElementById(`${type}_time`).value = newTime;
                    document.getElementById(`${type}_time_text`).innerText = newTime;

                    options.forEach(opt => {
                        opt.classList.toggle('selected', opt.innerText === value);
                    });

                    // Trigger availability check when time changes
                    if (typeof checkCarAvailability === 'function') {
                        checkCarAvailability();
                    }
                }

                function scrollToValue(type, part, value) {
                    const optionsContainer = document.getElementById(`tp_${type}_${part}_options`);
                    const options = optionsContainer.querySelectorAll('.tp-option');
                    let target = null;

                    options.forEach(opt => {
                        if (opt.innerText === value) {
                            opt.classList.add('selected');
                            target = opt;
                        } else {
                            opt.classList.remove('selected');
                        }
                    });

                    if (target) {
                        optionsContainer.scrollTop = target.offsetTop - optionsContainer.offsetTop - 70;
                    }

                    document.getElementById(`tp_${type}_${part}_header`).innerText = value;
                }

                // Close pickers when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.custom-time-picker')) {
                        document.querySelectorAll('.time-picker-dropdown').forEach(d => d.classList.remove('active'));
                    }
                });

                // Initialize Select2
                function initSelect2() {
                    if (typeof $ !== 'undefined' && $.fn.select2) {
                        $('#province_select, #district_select, #vehicle_select').select2({
                            width: '100%',
                            dropdownParent: $('#booking_modal .modal-box')
                        });
                    }
                }

                // Province & District Dependent Dropdown
                const provinceSelect = document.getElementById('province_select');
                const districtSelect = document.getElementById('district_select');

                if (provinceSelect && districtSelect) {
                    initSelect2();

                    $('#province_select').on('change', function() {
                        const province = this.value;
                        if (!province) {
                            districtSelect.innerHTML = '<option value="" disabled selected>-- เลือกอำเภอ --</option>';
                            districtSelect.disabled = true;
                            $(districtSelect).trigger('change');
                            return;
                        }

                        districtSelect.disabled = true;
                        districtSelect.innerHTML = '<option value="" disabled selected>กำลังโหลด...</option>';
                        $(districtSelect).trigger('change');

                        fetch(`/bookingcar/get-districts?province=${encodeURIComponent(province)}`)
                            .then(response => response.json())
                            .then(data => {
                                districtSelect.innerHTML = '<option value="" disabled selected>-- เลือกอำเภอ --</option>';
                                data.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district;
                                    option.textContent = district;
                                    districtSelect.appendChild(option);
                                });
                                districtSelect.disabled = false;
                                $(districtSelect).trigger('change'); // Notify Select2
                            })
                            .catch(error => {
                                console.error('Error fetching districts:', error);
                                districtSelect.innerHTML = '<option value="" disabled selected>โหลดข้อมูลผิดพลาด</option>';
                                $(districtSelect).trigger('change');
                            });
                    });

                    // Trigger district load if province is already selected (e.g. old input)
                    if (provinceSelect.value && districtSelect.options.length <= 2) {
                        const province = provinceSelect.value;
                        const currentDistrict = "{{ old('district') }}";
                        
                        fetch(`/bookingcar/get-districts?province=${encodeURIComponent(province)}`)
                            .then(response => response.json())
                            .then(data => {
                                districtSelect.innerHTML = '<option value="" disabled selected>-- เลือกอำเภอ --</option>';
                                data.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district;
                                    option.textContent = district;
                                    if (district === currentDistrict) {
                                        option.selected = true;
                                    }
                                    districtSelect.appendChild(option);
                                });
                                districtSelect.disabled = false;
                                $(districtSelect).trigger('change'); // Notify Select2
                            });
                    }
                }

                // Dynamic Car Availability Check
                function checkCarAvailability() {
                    const startDate = document.getElementById('booking_date').value;
                    const endDate = document.getElementById('booking_date_end').value;
                    const startTime = document.getElementById('start_time').value;
                    const endTime = document.getElementById('end_time').value;
                    const vehicleSelect = document.getElementById('vehicle_select');

                    if (!startDate || !endDate || !startTime || !endTime || !vehicleSelect) return;

                    fetch(`/bookingcar/check-availability?start_date=${startDate}&end_date=${endDate}&start_time=${startTime}&end_time=${endTime}`)
                        .then(response => response.json())
                        .then(data => {
                            const occupiedIds = data.occupied_vehicle_ids.map(id => id.toString());
                            const options = vehicleSelect.options;

                            for (let i = 0; i < options.length; i++) {
                                const opt = options[i];
                                if (opt.value === "" || !opt.dataset.originalText) continue;

                                if (occupiedIds.includes(opt.value)) {
                                    opt.disabled = true;
                                    opt.innerHTML = opt.dataset.originalText + ' (รถคันนี้ไม่ว่างช่วงเวลานี้)';
                                    opt.classList.add('text-slate-400', 'bg-slate-50');
                                } else {
                                    opt.disabled = false;
                                    opt.innerHTML = opt.dataset.originalText;
                                    opt.classList.remove('text-slate-400', 'bg-slate-50');
                                }
                            }
                        })
                        .catch(error => console.error('Error checking availability:', error));
                }

                // Expose the function globally
                window.checkCarAvailability = checkCarAvailability;

                // Attach listeners to inputs
                document.getElementById('booking_date').addEventListener('change', checkCarAvailability);
                document.getElementById('booking_date_end').addEventListener('change', checkCarAvailability);
                $('#vehicle_select').on('change', checkCarAvailability);

                // Auto-open modal or show sweet alerts if there are session messages or errors
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: @json(session('success')),
                        confirmButtonColor: '#e53935'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อผิดพลาด',
                        text: @json(session('error')),
                        confirmButtonColor: '#e53935'
                    });
                @endif

                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'ข้อมูลไม่ครบถ้วน',
                        text: 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน',
                        confirmButtonColor: '#e53935'
                    });

                    @if($isReturnError)
                        // Re-open return modal if return-related errors exist
                        const bookingId = "{{ old('booking_id_hidden') }}";
                        if (bookingId) {
                            const form = document.getElementById('return_car_form');
                            form.action = `/bookingcar/${bookingId}/return`;
                            document.getElementById('return_car_modal').showModal();
                        } else {
                            document.getElementById('booking_modal').showModal();
                        }
                    @else
                        document.getElementById('booking_modal').showModal();
                    @endif
                @endif

                @if(request()->has('vehicle_id') && !$errors->any())
                    document.getElementById('booking_modal').showModal();
                @endif

                const currentUserId = {{ $currentUserId ?? 'null' }};
                const isHamsOrAdmin = {{ $isHamsOrAdmin ? 'true' : 'false' }};

                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'th',
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    events: @json($calendarBookings),
                    eventTextColor: '#ffffff',
                    eventDisplay: 'block',
                    displayEventTime: true,
                    displayEventEnd: true,
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    height: 'auto',
                    selectable: true,
                    selectAllow: function (selectInfo) {
                        return true;
                    },
                    dateClick: function (info) {
                        const clickedDate = new Date(info.dateStr);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (clickedDate < today) {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สามารถจองย้อนหลังได้',
                                text: 'กรุณาเลือกวันที่ปัจจุบันหรือวันที่ในอนาคต',
                                confirmButtonColor: '#e53935'
                            });
                            return;
                        }
                        showBookingCarModal(info.dateStr);
                    },
                    eventClick: function (info) {
                        try {
                        const props = info.event.extendedProps;
                        const bookingId = info.event.id;

                        let actionButtonsHtml = '';

                        // Show status badge
                        if (props.status === 'รออนุมัติ') {
                            actionButtonsHtml += `
                                <div class="mt-2 w-full bg-orange-50 text-orange-600 py-2 rounded-lg font-medium text-center text-sm border border-orange-100">
                                    <i class="fa-solid fa-clock mr-1"></i> สถานะ: รออนุมัติ
                                </div>
                            `;
                        }

                        const eventStart = info.event.start;
                        const isPast = new Date(eventStart) < new Date();

                        // Show cancel button ONLY when status is 'รออนุมัติ', the user owns the booking, and it hasn't started
                        if (currentUserId == props.user_id && props.status === 'รออนุมัติ' && !isPast) {
                            actionButtonsHtml += `
                                <button id="btn-cancel-booking" class="mt-2 w-full bg-red-100 text-red-600 hover:bg-red-200 py-2 rounded-lg font-medium transition-colors text-sm border border-red-200">
                                    <i class="fa-solid fa-xmark mr-1"></i> ยกเลิกการจอง
                                </button>
                            `;
                        }

                        // Show return button if user is the booker AND booking is approved AND not returned yet
                        if (currentUserId == props.user_id && props.status === 'อนุมัติแล้ว' && props.return_status === 'ยังไม่ส่งคืน') {
                            actionButtonsHtml += `
                                <button id="btn-user-return-car" class="mt-2 w-full bg-orange-100 text-orange-700 hover:bg-orange-200 py-2 rounded-lg font-bold transition-colors text-sm border border-orange-200">
                                    <i class="fa-solid fa-flag-checkered mr-1"></i> คืนรถ (บันทึกข้อมูลหลังการเดินทาง)
                                </button>
                            `;
                        }

                        // Show return button if user is admin/HAMS and booking is approved
                        if (isHamsOrAdmin && props.status === 'อนุมัติแล้ว' && props.return_status === 'ยังไม่ส่งคืน') {
                            // We can keep the admin return button too, or let them use the same new modal.
                            // For now, let's keep it but maybe they want the same detailed form too.
                            actionButtonsHtml += `
                                <button id="btn-return-car" class="mt-2 w-full bg-red-100 text-red-700 hover:bg-red-200 py-2 rounded-lg font-bold transition-colors text-sm border border-red-200">
                                    <i class="fa-solid fa-xmark mr-1"></i> คืนรถ (สำหรับ Admin)
                                </button>
                            `;
                        } else if (props.return_status === 'ส่งคืนแล้ว') {
                            actionButtonsHtml += `
                                <div class="mt-2 w-full bg-green-50 text-green-600 py-2 rounded-lg font-medium text-center text-sm border border-green-100">
                                    <i class="fa-solid fa-check-circle mr-1"></i> คืนรถแล้ว
                                </div>
                            `;
                        }

                        Swal.fire({
                            title: 'รายละเอียดการจองรถส่วนกลาง',
                            html: `
                                <div class="text-center space-y-1 mt-4 text-slate-700">
                                    <p><strong>ชื่อผู้จอง:</strong> ${props.user}</p>
                                    <p><strong>เจ้าของงาน:</strong> ${props.requester_name}</p>
                                    <p><strong>วันที่เริ่ม:</strong> ${props.start_time_formatted}</p>
                                    <p><strong>วันที่สิ้นสุด:</strong> ${props.end_time_formatted}</p>
                                    <p class="text-red-600 font-bold">
                                        รถ: ทะเบียน: ${props.vehicle_name} รุ่น: ${props.vehicle_model} ${props.vehicle_type}
                                    </p>
                                    <p><strong>สถานที่ปลายทาง:</strong> ${props.destination}</p>
                                    <p><strong>จังหวัด:</strong> ${props.province}</p>
                                    <p><strong>จำนวนผู้โดยสาร:</strong> ${props.passenger_count} คน</p>
                                    <p class="text-emerald-600 font-bold">สถานะ: ${props.status}</p>
                                    <p class="text-amber-500 font-bold">สถานะคืนรถ: ${props.return_status}</p>
                                    <div class="pt-4">
                                        ${actionButtonsHtml}
                                    </div>
                                </div>
                            `,
                            confirmButtonText: 'ปิด',
                            confirmButtonColor: '#94a3b8',
                            didRender: () => {
                                const btnCancel = document.getElementById('btn-cancel-booking');
                                if (btnCancel) {
                                    btnCancel.addEventListener('click', () => {
                                        Swal.fire({
                                            title: 'ยืนยันการยกเลิก?',
                                            text: "คุณต้องการยกเลิกการจองรถครั้งนี้ใช่หรือไม่?",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#94a3b8',
                                            confirmButtonText: 'ใช่, ยกเลิกการจอง',
                                            cancelButtonText: 'กลับ'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const form = document.createElement('form');
                                                form.method = 'POST';
                                                form.action = `/bookingcar/${bookingId}/cancel`;
                                                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                                                document.body.appendChild(form);
                                                form.submit();
                                            }
                                        });
                                    });
                                }

                                const btnUserReturn = document.getElementById('btn-user-return-car');
                                if (btnUserReturn) {
                                    btnUserReturn.addEventListener('click', () => {
                                        showReturnCarModal(bookingId, props.mileage_before);
                                        Swal.close();
                                    });
                                }

                                const btnReturn = document.getElementById('btn-return-car');
                                if (btnReturn) {
                                    btnReturn.addEventListener('click', () => {
                                        Swal.fire({
                                            title: 'ยืนยันการคืนรถ?',
                                            text: "ระบบจะบันทึกสถานะว่าคืนรถเรียบร้อยแล้ว",
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonColor: '#10b981',
                                            cancelButtonColor: '#94a3b8',
                                            confirmButtonText: 'ใช่, คืนรถแล้ว',
                                            cancelButtonText: 'ยกเลิก'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const form = document.createElement('form');
                                                form.method = 'POST';
                                                form.action = `/bookingcar/${bookingId}/return`;
                                                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                                                document.body.appendChild(form);
                                                form.submit();
                                            }
                                        });
                                    });
                                }
                            }
                        });
                        } catch(e) {
                            console.error('eventClick error:', e);
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถแสดงรายละเอียดการจองได้: ' + e.message,
                                confirmButtonColor: '#e53935'
                            });
                        }
                    }
                });
                calendar.render();
                
                const ackCheckbox = document.getElementById('ack_return_info');
                const submitBtn = document.getElementById('submit_booking');
                
                if (ackCheckbox && submitBtn) {
                    // Initial state
                    submitBtn.disabled = !ackCheckbox.checked;

                    ackCheckbox.addEventListener('change', function() {
                        submitBtn.disabled = !this.checked;
                    });
                }
            });
        </script>
@endsection