@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-4 animate-fadeIn">

        <!-- Print-only Header -->
        <div class="hidden print:block mb-8">
            <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 bg-slate-800 rounded-xl flex items-center justify-center text-white font-black text-2xl">
                        H
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">ระบบบริหารจัดการจองรถส่วนกลาง (HAMS)</h1>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Hospital Asset Management
                            System</p>
                    </div>
                </div>
                <div class="text-right text-[12px]">
                    <h2 class="text-lg font-bold text-slate-800 mb-1">รายงานสรุปข้อมูลการจองรถ</h2>
                    <p class="font-medium text-slate-600">วันที่ออกรายงาน: {{ now()->translatedFormat('d F Y') }}</p>
                    <p class="font-medium text-slate-600">เวลาที่เข้าถึงข้อมูล: {{ now()->format('H:i') }} น.</p>
                    <p class="font-medium text-slate-600">ผู้ออกรายงาน: {{ Auth::user()->first_name ?? 'เจ้าหน้าที่' }}
                        {{ Auth::user()->last_name ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Header Section -->
        <div
            class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-8 mb-8 text-center text-white relative overflow-hidden no-print">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <h1 class="text-2xl md:text-3xl font-bold tracking-wider mb-2">รายงานข้อมูลจองรถส่วนกลาง</h1>
                <p class="text-indigo-100 text-sm font-light">ข้อมูลจองรถส่วนกลางทั้งหมดในระบบ</p>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 no-print">
            <h3 class="text-blue-600 font-bold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-caret-down"></i> ตัวกรองข้อมูล
            </h3>
            <form action="{{ route('bookingcar.dashboard') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-7 gap-4 items-end">
                    <!-- Unified Search Box -->
                    <div class="form-control col-span-1 md:col-span-2 lg:col-span-2 xl:col-span-2">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">ค้นหาข้อมูล</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="รหัสการจอง, ชื่อผู้จอง, แผนก, สถานที่..."
                                class="input input-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 pl-9 focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span
                                class="label-text text-[10px] font-bold text-slate-400 uppercase">(กำหนด:วัน/เดือน/ปี)</span>
                        </label>
                        <input type="date" name="booking_date" value="{{ request('booking_date') }}"
                            class="input input-bordered w-full text-sm bg-slate-50 border-slate-200 h-10">
                    </div>

                    <!-- Month Filter -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">เดือน</span>
                        </label>
                        <select name="month"
                            class="select select-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 min-h-0">
                            <option value="">-- ทุกเดือน --</option>
                            @foreach($thaiMonths as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">ปี (พ.ศ.)</span>
                        </label>
                        <select name="year"
                            class="select select-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 min-h-0">
                            <option value="">-- ทุกปี --</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year + 543 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Booking Status -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">สถานะการจอง</span>
                        </label>
                        <select name="status"
                            class="select select-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 min-h-0">
                            <option value="">-- ทุกสถานะ --</option>
                            <option value="รออนุมัติ" {{ request('status') == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ
                            </option>
                            <option value="อนุมัติแล้ว" {{ request('status') == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว
                            </option>
                            <option value="ไม่อนุมัติ" {{ request('status') == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ
                            </option>
                            <option value="ยกเลิก" {{ request('status') == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                        </select>
                    </div>

                    <!-- Passenger Count -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">จำนวนผู้โดยสาร</span>
                        </label>
                        <select name="passenger_count"
                            class="select select-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 min-h-0">
                            <option value="">-- ทั้งหมด --</option>
                            @foreach($passengerCounts as $count)
                                <option value="{{ $count }}" {{ request('passenger_count') == $count ? 'selected' : '' }}>
                                    {{ $count }} คน
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Return Status -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">สถานะคืนรถ</span>
                        </label>
                        <select name="return_status"
                            class="select select-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 min-h-0">
                            <option value="">-- ทุกสถานะคืนรถ --</option>
                            <option value="ยังไม่ส่งคืน" {{ request('return_status') == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>
                                ยังไม่ส่งคืน</option>
                            <option value="ส่งคืนแล้ว" {{ request('return_status') == 'ส่งคืนแล้ว' ? 'selected' : '' }}>
                                ส่งคืนแล้ว</option>
                        </select>
                    </div>

                    <!-- Province Filter (Searchable) -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">จังหวัด</span>
                        </label>
                        <input type="text" name="province" value="{{ request('province') }}" list="province_list_dash"
                            placeholder="ค้นหาจังหวัด..."
                            class="input input-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 pl-3">
                        <datalist id="province_list_dash">
                            <option value="กรุงเทพมหานคร">
                            <option value="กระบี่">
                            <option value="กาญจนบุรี">
                            <option value="กาฬสินธุ์">
                            <option value="กำแพงเพชร">
                            <option value="ขอนแก่น">
                            <option value="จันทบุรี">
                            <option value="ฉะเชิงเทรา">
                            <option value="ชลบุรี">
                            <option value="ชัยนาท">
                            <option value="ชัยภูมิ">
                            <option value="ชุมพร">
                            <option value="เชียงราย">
                            <option value="เชียงใหม่">
                            <option value="ตรัง">
                            <option value="ตราด">
                            <option value="ตาก">
                            <option value="นครนายก">
                            <option value="นครปฐม">
                            <option value="นครพนม">
                            <option value="นครราชสีมา">
                            <option value="นครศรีธรรมราช">
                            <option value="นครสวรรค์">
                            <option value="นนทบุรี">
                            <option value="นราธิวาส">
                            <option value="น่าน">
                            <option value="บึงกาฬ">
                            <option value="บุรีรัมย์">
                            <option value="ปทุมธานี">
                            <option value="ประจวบคีรีขันธ์">
                            <option value="ปราจีนบุรี">
                            <option value="ปัตตานี">
                            <option value="พระนครศรีอยุธยา">
                            <option value="พังงา">
                            <option value="พัทลุง">
                            <option value="พิจิตร">
                            <option value="พิษณุโลก">
                            <option value="เพชรบุรี">
                            <option value="เพชรบูรณ์">
                            <option value="แพร่">
                            <option value="พะเยา">
                            <option value="ภูเก็ต">
                            <option value="มหาสารคาม">
                            <option value="มุกดาหาร">
                            <option value="แม่ฮ่องสอน">
                            <option value="ยโสธร">
                            <option value="ยะลา">
                            <option value="ร้อยเอ็ด">
                            <option value="ระนอง">
                            <option value="ระยอง">
                            <option value="ราชบุรี">
                            <option value="ลพบุรี">
                            <option value="ลำปาง">
                            <option value="ลำพูน">
                            <option value="เลย">
                            <option value="ศรีสะเกษ">
                            <option value="สกลนคร">
                            <option value="สงขลา">
                            <option value="สตูล">
                            <option value="สมุทรปราการ">
                            <option value="สมุทรสงคราม">
                            <option value="สมุทรสาคร">
                            <option value="สระแก้ว">
                            <option value="สระบุรี">
                            <option value="สิงห์บุรี">
                            <option value="สุโขทัย">
                            <option value="สุพรรณบุรี">
                            <option value="สุราษฎร์ธานี">
                            <option value="สุรินทร์">
                            <option value="หนองคาย">
                            <option value="หนองบัวลำภู">
                            <option value="อ่างทอง">
                            <option value="อุดรธานี">
                            <option value="อุทัยธานี">
                            <option value="อุตรดิตถ์">
                            <option value="อุบลราชธานี">
                            <option value="อำนาจเจริญ">
                        </datalist>
                    </div>

                    <!-- District Filter (Searchable) -->
                    <div class="form-control">
                        <label class="label py-0">
                            <span class="label-text text-[10px] font-bold text-slate-400 uppercase">อำเภอ</span>
                        </label>
                        <input type="text" name="district" value="{{ request('district') }}" list="district_list_dash"
                            placeholder="ค้นหาระบุอำเภอ..."
                            class="input input-bordered w-full text-sm bg-slate-50 border-slate-200 h-10 pl-3">
                        <datalist id="district_list_dash">
                            @foreach($existingDistricts as $dist)
                                <option value="{{ $dist }}">
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 col-span-1 md:col-span-2 lg:col-span-1 xl:col-span-1 2xl:col-span-2 min-w-fit">
                        <button type="submit"
                            class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 px-6 btn-sm h-10 flex-1 whitespace-nowrap shadow-md shadow-blue-500/20">
                            <i class="fa-solid fa-search mr-2"></i> ค้นหา
                        </button>
                        @if(request()->anyFilled(['search', 'booking_date', 'status', 'passenger_count', 'return_status', 'month', 'year', 'province', 'district']))
                            <a href="{{ route('bookingcar.dashboard') }}"
                                class="btn bg-slate-100 hover:bg-slate-200 text-slate-600 border-0 px-6 btn-sm h-10 flex-1 whitespace-nowrap">
                                <i class="fa-solid fa-rotate mr-2"></i> ล้างค่า
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Action Bar -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6 flex justify-between items-center no-print">
            <div class="flex items-center gap-2 text-cyan-600 font-bold">
                <i class="fa-solid fa-file-export"></i> ส่งออกข้อมูล
            </div>
            <div class="flex gap-2">
                <a href="{{ route('bookingcar.export.excel', request()->all()) }}"
                    class="btn btn-sm bg-emerald-600 hover:bg-emerald-700 text-white border-0 px-6">
                    <i class="fa-solid fa-file-excel mr-1"></i> Excel
                </a>
                <button onclick="window.print()" class="btn btn-sm bg-cyan-500 hover:bg-cyan-600 text-white border-0 px-6">
                    <i class="fa-solid fa-print mr-1"></i> พิมพ์
                </button>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-slate-200">
                <table class="table w-full text-[11px] table-compact">
                    <thead>
                        <tr class="bg-slate-50 text-slate-800 font-bold border-b border-slate-200">
                            <th class="py-3 text-center px-2">ลำดับ</th>
                            <th class="py-3 text-center px-2">เลขที่การจอง</th>
                            <th class="py-3 text-center px-2 min-w-[150px]">ชื่อผู้จอง</th>
                            <th class="py-3 text-center px-2">เจ้าของงาน</th>
                            <th class="py-3 text-center px-2">แผนก</th>
                            <th class="py-3 text-center px-2 min-w-[120px]">วันที่เวลาออกเดินทาง</th>
                            <th class="py-3 text-center px-2 min-w-[120px]">วันที่สิ้นสุดการจอง</th>
                            <th class="py-3 text-center px-2 min-w-[150px]">สถานที่ปลายทาง</th>
                            <th class="py-3 text-center px-2">จังหวัด</th>
                            <th class="py-3 text-center px-2">จำนวนผู้โดยสาร</th>
                            <th class="py-3 text-center px-2">สถานะการจอง</th>
                            <th class="py-3 text-center px-2">สถานะคืนรถ</th>
                            <th class="py-3 text-center px-2">รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        @forelse ($bookings as $index => $item)
                            <tr class="hover:bg-slate-50 transition-colors border-b border-slate-100">
                                <td class="text-center font-bold text-blue-600 px-2">{{ $bookings->firstItem() + $index }}</td>
                                <td class="text-center px-1.5">
                                    <span
                                        class="badge badge-xs rounded bg-cyan-400 text-white border-0 font-bold px-2 py-1 h-auto whitespace-nowrap">
                                        {{ $item->booking_code }}
                                    </span>
                                </td>
                                <td class="font-bold text-slate-800 text-center px-1.5 leading-none py-1">
                                    <span class="text-[10px]">{{ $item->user->first_name ?? 'N/A' }}</span><br>
                                    <span class="text-[10px]">{{ $item->user->last_name ?? '' }}</span>
                                </td>
                                <td class="text-center text-slate-500 px-1.5 truncate max-w-[70px] leading-tight"
                                    title="{{ $item->requester_name ?? '-' }}">
                                    {{ $item->requester_name ?? '-' }}
                                </td>
                                <td class="text-center px-1.5">
                                    <span
                                        class="px-1 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 rounded text-[9px] font-bold uppercase">
                                        {{ $item->user->department->department_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center px-1.5 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1 font-bold">
                                        <i class="fa-regular fa-circle-play text-green-500 text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="text-center px-1.5 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1 font-bold">
                                        <i class="fa-regular fa-circle-stop text-red-500 text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($item->end_time)->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="text-center px-1.5">
                                    <div class="flex items-center justify-center gap-1 text-red-500 font-bold">
                                        <i class="fa-solid fa-location-dot text-[9px]"></i>
                                        <span class="text-slate-700 truncate max-w-[100px] leading-tight"
                                            title="{{ $item->destination }}">{{ $item->destination }}</span>
                                    </div>
                                </td>
                                <td class="text-center px-1.5">
                                    <span
                                        class="badge badge-xs bg-slate-500 text-white border-0 rounded px-1.5 py-1 h-auto font-bold text-[9px]">
                                        {{ $item->province }}
                                    </span>
                                </td>
                                <td class="text-center px-1.5 whitespace-nowrap">
                                    <span
                                        class="badge badge-xs bg-blue-600 text-white border-0 rounded px-1.5 py-1 h-auto font-bold gap-1 text-[9px]">
                                        <i class="fa-solid fa-users text-[8px]"></i> {{ $item->passenger_count ?? 1 }} คน
                                    </span>
                                </td>
                                <td class="text-center px-1.5 py-3">
                                    <div class="flex flex-col items-center gap-2">
                                        @php
                                            $statusClass = match ($item->status) {
                                                'อนุมัติแล้ว' => 'bg-emerald-500 text-white',
                                                'รออนุมัติ' => 'bg-amber-400 text-white',
                                                'ไม่อนุมัติ', 'ยกเลิก' => 'bg-red-500 text-white',
                                                default => 'bg-slate-300 text-white'
                                            };
                                        @endphp
                                        <span
                                            class="badge badge-xs border-0 rounded-full px-2 py-1 h-auto font-bold gap-1 text-[9px] whitespace-nowrap {{ $statusClass }}">
                                            {{ $item->status === 'อนุมัติแล้ว' ? 'รับทราบแล้ว' : ($item->status === 'รออนุมัติ' ? 'รอเนินการ' : $item->status) }}
                                        </span>

                                        @if($item->status === 'รออนุมัติ')
                                            <div class="flex items-center bg-white border border-slate-200 rounded-lg p-0.5 shadow-sm no-print">
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="อนุมัติแล้ว">
                                                    <button type="submit" class="hover:bg-emerald-50 p-1.5 rounded transition-colors group" title="อนุมัติการจอง">
                                                        <i class="fa-solid fa-check text-emerald-500 text-[10px]"></i>
                                                    </button>
                                                </form>
                                                <div class="w-[1px] h-3 bg-slate-200 mx-0.5"></div>
                                                <form action="{{ route('bookingcar.approve', $item->booking_id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="ไม่อนุมัติ">
                                                    <button type="submit" class="hover:bg-red-50 p-1.5 rounded transition-colors group" title="ปฏิเสธการจอง">
                                                        <i class="fa-solid fa-xmark text-red-500 text-[10px]"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center px-2">
                                    @php
                                        $isInvalidStatus = in_array($item->status, ['ยกเลิก', 'ไม่อนุมัติ']);
                                        $retClass = $isInvalidStatus 
                                            ? 'bg-slate-200 text-slate-500' 
                                            : match ($item->return_status) {
                                                'ยังไม่ส่งคืน' => 'bg-amber-400 text-white',
                                                'ส่งคืนแล้ว' => 'bg-green-500 text-white',
                                                default => 'bg-slate-300 text-white'
                                            };
                                        $displayText = $isInvalidStatus ? '-' : $item->return_status;
                                    @endphp
                                    <span
                                        class="badge badge-xs border-0 rounded px-2 py-1.5 h-auto font-bold gap-1 text-[10px] whitespace-nowrap {{ $retClass }}">
                                        {{ $displayText }}
                                    </span>
                                </td>
                                <td class="text-center px-2">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($item->status === 'รออนุมัติ')
                                            <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                                class="btn btn-[10px] h-6 min-h-0 bg-cyan-500 hover:bg-cyan-600 text-white border-0 shadow-sm px-1.5">
                                                <i class="fa-solid fa-rotate"></i>
                                            </a>
                                            <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                                class="btn btn-[10px] h-6 min-h-0 bg-cyan-400 hover:bg-cyan-500 text-white border-0 shadow-sm px-1.5">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('bookingcar.edit', $item->booking_id) }}"
                                                class="btn btn-[10px] h-6 min-h-0 bg-cyan-400 hover:bg-cyan-500 text-white border-0 shadow-sm px-1.5">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <button
                                                class="btn btn-[10px] h-6 min-h-0 bg-red-400 text-white border-0 shadow-sm px-1.5 cursor-not-allowed opacity-70">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-3 grayscale opacity-30">
                                        <i class="fa-solid fa-table-list text-5xl"></i>
                                        <p class="font-bold text-lg">ไม่พบข้อมูลที่ต้องการ</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-center no-print">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                font-size: 10pt;
            }

            /* Hide unnecessary UI elements */
            .btn,
            form,
            h3,
            .bg-gradient-to-r,
            .no-print,
            nav,
            footer {
                display: none !important;
            }

            /* Layout adjustments */
            .max-w-[1400px] {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .rounded-2xl,
            .rounded-t-2xl,
            .rounded-b-2xl {
                border-radius: 0 !important;
            }

            /* Table Styles for Reporting */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                table-layout: auto !important;
            }

            th {
                background-color: #f8fafc !important;
                color: #1e293b !important;
                border: 1px solid #e2e8f0 !important;
                padding: 8px 4px !important;
                font-weight: bold !important;
                text-align: center !important;
                font-size: 9pt !important;
            }

            td {
                border: 1px solid #e2e8f0 !important;
                padding: 6px 4px !important;
                vertical-align: middle !important;
                font-size: 8.5pt !important;
            }

            /* Force badges to show in print */
            .badge {
                border: 1px solid #ddd !important;
                background-color: transparent !important;
                color: black !important;
                padding: 2px 4px !important;
                font-size: 8pt !important;
                height: auto !important;
                width: auto !important;
            }

            .badge-emerald-500,
            .bg-emerald-500 {
                background-color: #10b981 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
            }

            .badge-amber-400,
            .bg-amber-400 {
                background-color: #fbbf24 !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
            }

            .badge-red-500,
            .bg-red-500 {
                background-color: #ef4444 !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
            }

            .badge-cyan-400,
            .bg-cyan-400 {
                background-color: #22d3ee !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
            }

            .badge-blue-600,
            .bg-blue-600 {
                background-color: #2563eb !important;
                color: white !important;
                -webkit-print-color-adjust: exact !important;
            }

            /* Text specific for print */
            .text-blue-600 {
                color: #2563eb !important;
            }

            .text-slate-800 {
                color: #1e293b !important;
            }

            .text-slate-600 {
                color: #475569 !important;
            }

            /* Hide description truncation or specific columns if needed */
            .truncate {
                overflow: visible !important;
                white-space: normal !important;
                max-width: none !important;
            }

            /* Add footer placeholder for pagination or signature */
            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0;
                width: 100%;
                font-size: 8pt;
                text-align: right;
                border-top: 1px solid #eee;
                padding-top: 5px;
            }
        }
    </style>
    <div class="print-footer hidden print:block">
        หน้าที่ <span class="pageNumber"></span> ในระบบจองรถ HAMS
    </div>
@endsection