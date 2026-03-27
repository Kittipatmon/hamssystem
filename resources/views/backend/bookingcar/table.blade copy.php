@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[95%] xl:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">

        <!-- New Premium Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100">
                    <i class="fa-solid fa-car text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-slate-800">จัดการข้อมูลรถส่วนกลาง</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        เพิ่ม ลบ หรือแก้ไขข้อมูลรถยนต์ในระบบจองรถ
                    </p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('backend.bookingcar.dashboard') }}"
                    class="btn btn-ghost border-slate-200 text-slate-600 hover:bg-slate-100 rounded-2xl px-6">
                    <i class="fa-solid fa-chart-line mr-2"></i> แดชบอร์ด
                </a>
                <button onclick="add_vehicle_modal.showModal()"
                    class="btn bg-red-600 hover:bg-red-700 text-white border-0 shadow-lg shadow-red-200 rounded-2xl px-8 transition-all hover:scale-105 active:scale-95">
                    <i class="fa-solid fa-plus mr-2 text-lg"></i> เพิ่มข้อมูลรถใหม่
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs tabs-boxed bg-slate-100/50 p-1.5 rounded-2xl mb-8 inline-flex backdrop-blur-sm border border-slate-200/50" role="tablist">
            <a class="tab tab-active font-bold text-[13px] h-11 px-8 rounded-xl transition-all gap-2"
                onclick="showTab('tab-vehicles')" id="btn-vehicles">
                <i class="fa-solid fa-car-side"></i> ข้อมูลรถยนต์
            </a>
            <a class="tab font-bold text-[13px] h-11 px-8 rounded-xl transition-all gap-2" onclick="showTab('tab-bookings')"
                id="btn-bookings">
                <i class="fa-solid fa-calendar-check"></i> ข้อมูลการจอง
            </a>
            <a class="tab font-bold text-[13px] h-11 px-8 rounded-xl transition-all gap-2" onclick="showTab('tab-inspections')"
                id="btn-inspections">
                <i class="fa-solid fa-microscope"></i> ตรวจเช็คสภาพ
            </a>
        </div>

        <!-- Tab 1: Vehicles -->
        <div id="tab-vehicles" class="tab-pane active space-y-4">
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-list-ul text-red-500 text-lg"></i>
                        <h3 class="font-bold text-slate-700 text-lg">รายชื่อรถทั้งหมดในระบบ</h3>
                    </div>
                    <span class="bg-slate-50 text-slate-500 px-4 py-1.5 rounded-full text-xs font-bold border border-slate-100">
                        {{ $vehicles->count() }} คัน
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table w-full min-w-[1200px]">
                        <thead class="bg-slate-50/50 text-slate-400 font-bold text-[11px] uppercase tracking-widest border-b border-slate-100">
                            <tr>
                                <th class="py-5 pl-8 bg-transparent">รูปภาพ</th>
                                <th class="py-5 bg-transparent text-center">ทะเบียน / ชื่อเรียก</th>
                                <th class="py-5 bg-transparent">ยี่ห้อ & รุ่น</th>
                                <th class="py-5 bg-transparent text-center">ประเภท</th>
                                <th class="py-5 bg-transparent text-center">สถานะ</th>
                                <th class="py-5 pr-8 bg-transparent text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-600">
                            @forelse($vehicles as $vehicle)
                                <tr class="hover:bg-slate-50/30 transition-colors group">
                                    <td class="py-6 pl-8">
                                        <div class="w-20 h-14 rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-slate-50 flex items-center justify-center">
                                            @php
                                                $images = json_decode($vehicle->images, true);
                                                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                            @endphp
                                            @if($firstImage)
                                                <img src="{{ asset('images/vehicle/' . $firstImage) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <i class="fa-solid fa-car text-slate-200 text-2xl"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <div class="flex flex-col items-center">
                                            <span class="font-black text-slate-800 text-[16px]">{{ $vehicle->name }}</span>
                                            <span class="text-[11px] font-bold text-slate-400 mt-0.5">ID: {{ $vehicle->vehicle_id }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-700 text-[14px] uppercase tracking-wide">{{ $vehicle->brand ?? '-' }}</span>
                                            <span class="text-[12px] text-slate-400 font-medium">{{ $vehicle->model_name ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 text-center">
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            {{ $vehicle->type ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-6 text-center">
                                        @if($vehicle->status === 'available')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-green-50 text-green-600 border border-green-100 ring-4 ring-green-50/50">
                                                ว่าง
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[12px] font-bold bg-orange-50 text-orange-600 border border-orange-100">
                                                ไม่ว่าง
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-6 pr-8 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('backend.bookingcar.edit', $vehicle->vehicle_id) }}" 
                                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm border border-blue-100">
                                                <i class="fa-solid fa-edit text-sm"></i>
                                            </a>
                                            <button onclick="confirmDelete({{ $vehicle->vehicle_id }}, '{{ $vehicle->name }}')" 
                                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm border border-red-100">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                                <i class="fa-solid fa-car-side text-3xl"></i>
                                            </div>
                                            <span class="text-slate-400 font-medium">ไม่พบข้อมูลรถยนต์ในระบบ</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Bookings -->
        <div id="tab-bookings" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <i class="fa-solid fa-receipt text-purple-500 text-lg"></i>
                    <h3 class="font-bold text-slate-700">ตาราง vehicle_bookings</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-[12px] min-w-[1700px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white whitespace-nowrap">ลำดับ</th>
                                <th class="bg-white whitespace-nowrap">เลขที่การจอง</th>
                                <th class="bg-white whitespace-nowrap">ชื่อผู้จอง</th>
                                <th class="bg-white whitespace-nowrap">เจ้าของงาน</th>
                                <th class="bg-white whitespace-nowrap">แผนก</th>
                                <th class="bg-white whitespace-nowrap">วันที่เวลาออกเดินทาง</th>
                                <th class="bg-white whitespace-nowrap">วันที่สิ้นสุดการจอง</th>
                                <th class="bg-white whitespace-nowrap">สถานที่ปลายทาง</th>
                                <th class="bg-white whitespace-nowrap">จังหวัด</th>
                                <th class="bg-white whitespace-nowrap text-center">จำนวนผู้โดยสาร</th>
                                <th class="bg-white whitespace-nowrap">สถานะจอง</th>
                                <th class="bg-white whitespace-nowrap">สถานะคืนรถ</th>
                                <th class="bg-white whitespace-nowrap">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td class="text-center font-bold">{{ $loop->iteration }}</td>
                                    <td class="font-mono text-[10px] whitespace-nowrap">{{ $booking->booking_code }}</td>
                                    <td class="whitespace-nowrap">{{ $booking->user->fullname ?? 'N/A' }}</td>
                                    <td>{{ $booking->requester_name ?? '-' }}</td>
                                    <td><span class="badge badge-outline badge-ghost text-[10px]">{{ $booking->user->department->department_name ?? '-' }}</span></td>
                                    <td class="whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-regular fa-circle-play text-green-500 text-[10px]"></i>
                                            <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <i class="fa-regular fa-circle-stop text-red-400 text-[10px]"></i>
                                            <span>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
                                            <span class="text-slate-400">{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="max-w-[200px] truncate"><i class="fa-solid fa-location-dot text-red-500 mr-1 text-[10px]"></i> {{ $booking->destination ?? '-' }}</td>
                                    <td class="whitespace-nowrap"><span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 text-[11px]">{{ $booking->province ?? '-' }}</span></td>
                                    <td class="text-center">
                                        <span class="badge badge-primary badge-sm gap-1">
                                            <i class="fa-solid fa-users text-[10px]"></i>
                                            {{ $booking->passenger_count ?? 0 }} คน
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $bStatus = match ($booking->status) {
                                                'อนุมัติแล้ว' => 'text-green-600',
                                                'รออนุมัติ' => 'text-orange-600',
                                                'ไม่อนุมัติ', 'ยกเลิก' => 'text-red-600',
                                                default => 'text-slate-600'
                                            };
                                        @endphp
                                        <span class="{{ $bStatus }} font-medium whitespace-nowrap">{{ $booking->status }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-xs whitespace-nowrap {{ $booking->return_status === 'ส่งคืนแล้ว' ? 'text-blue-600 font-semibold' : 'text-slate-500' }}">
                                            {{ $booking->return_status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.bookingcar.edit', $booking->vehicle_id) }}" 
                                           class="btn btn-ghost btn-xs text-purple-600 hover:bg-purple-50">
                                            <i class="fa-solid fa-edit text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: Inspections -->
        <div id="tab-inspections" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <i class="fa-solid fa-wrench text-orange-500 text-lg"></i>
                    <h3 class="font-bold text-slate-700">ตาราง vehicle_inspections</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-[12px] min-w-[1200px]">
                        <thead class="bg-white text-slate-500 font-medium border-b border-slate-100">
                            <tr>
                                <th class="bg-white"># ID</th>
                                <th class="bg-white">รถ (vehicle_id)</th>
                                <th class="bg-white">วันที่ตรวจ (inspection_date)</th>
                                <th class="bg-white">เลขไมล์ (mileage)</th>
                                <th class="bg-white">ผู้ตรวจเช็ค (inspector_name)</th>
                                <th class="bg-white">สถานะ (status)</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-600">
                            @forelse($inspections as $insp)
                                <tr class="hover:bg-slate-50/50 border-b border-slate-50">
                                    <td>{{ $insp->inspection_id }}</td>
                                    <td>Id: {{ $insp->vehicle_id }} - {{ $insp->vehicle->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($insp->inspection_date)->format('d/m/Y') }}</td>
                                    <td>{{ number_format((float) $insp->mileage) }} กม.</td>
                                    <td>{{ $insp->inspector_name ?? '-' }}</td>
                                    <td>
                                        @if($insp->status == 0)
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-green-100 text-green-700">ดำเนินการเสร็จสิ้น</span>
                                        @else
                                            <span
                                                class="px-2 py-1 rounded text-[10px] font-medium bg-orange-100 text-orange-700">รอดำเนินการ</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-slate-400">ไม่พบข้อมูล</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Add Vehicle Modal -->
    <dialog id="add_vehicle_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box p-0 bg-white max-w-2xl overflow-hidden rounded-[2.5rem] border border-slate-100 shadow-2xl">
            <!-- Modal Header -->
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-600 shadow-inner">
                        <i class="fa-solid fa-car-side text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-slate-800">เพิ่มข้อมูลรถยนต์ใหม่</h3>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Vehicle Registration</p>
                    </div>
                </div>
                <form method="dialog">
                    <button class="btn btn-circle btn-ghost btn-sm text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </form>
            </div>

            <form action="{{ route('backend.bookingcar.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Side: Basic Info -->
                    <div class="space-y-5">
                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">ทะเบียน / ชื่อเรียก <span class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="name" required placeholder="เช่น บบ 4622"
                                class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-medium">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ยี่ห้อรถ</span>
                                </label>
                                <input type="text" name="brand" placeholder="เช่น TOYOTA"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-medium uppercase text-sm">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รุ่นรถ</span>
                                </label>
                                <input type="text" name="model_name" placeholder="เช่น Hilux Vigo"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทรถ</span>
                                </label>
                                <select name="type" class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-bold text-slate-700">
                                    <option value="เก๋ง">เก๋ง</option>
                                    <option value="กระบะ">กระบะ</option>
                                    <option value="ตู้">ตู้</option>
                                    <option value="SUV">SUV</option>
                                    <option value="อื่นๆ">อื่นๆ</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ปีรถ (พ.ศ./ค.ศ.)</span>
                                </label>
                                <input type="text" name="year" placeholder="เช่น 2024"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 focus:ring-4 focus:ring-red-100 transition-all font-medium">
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Secondary Info & Image -->
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">จำนวนที่นั่ง</span>
                                </label>
                                <input type="number" name="seat" placeholder="เช่น 4"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทน้ำมัน</span>
                                </label>
                                <input type="text" name="filling_type" placeholder="เช่น ดีเซล"
                                    class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">รูปภาพรถ</span>
                            </label>
                            <input type="file" name="image" class="file-input file-input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 file:bg-slate-200 file:text-slate-700 file:border-0 hover:file:bg-slate-300 transition-all">
                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">รายละเอียดเพิ่มเติม</span>
                            </label>
                            <textarea name="desciption" rows="2" class="textarea textarea-bordered w-full rounded-2xl bg-slate-50 border-slate-200 focus:border-red-500 transition-all font-medium" placeholder="เช่น สีขาว, ประกันภัยชั้น 1..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-10">
                    <form method="dialog">
                        <button class="btn btn-ghost rounded-2xl px-6 font-bold text-slate-500">ยกเลิก</button>
                    </form>
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-0 shadow-lg shadow-red-200 rounded-2xl px-10 transition-all">
                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete_modal" class="modal">
        <div class="modal-box bg-white rounded-3xl border border-slate-100 shadow-2xl p-0 overflow-hidden max-w-sm">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-600 mx-auto mb-4 border border-red-100 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="font-black text-xl text-slate-800 mb-2">ยืนยันการลบข้อมูล?</h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">
                    คุณกำลังจะลบข้อมูลรถ <span id="delete_vehicle_name" class="text-red-600 font-bold underline decoration-red-200"></span> 
                    ออกจากระบบอย่างถาวร ไม่สามารถย้อนกลับได้
                </p>
            </div>
            <div class="bg-slate-50 p-6 flex gap-3">
                <form method="dialog" class="flex-1">
                    <button class="btn btn-ghost w-full rounded-2xl font-bold text-slate-500">ยกเลิก</button>
                </form>
                <form id="delete_form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-0 w-full rounded-2xl shadow-md">
                        ยืนยันการลบ
                    </button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
            <button>close</button>
        </form>
    </dialog>

    <script>
        function showTab(tabId) {
            // Hide all panes
            document.querySelectorAll('.tab-pane').forEach(el => {
                el.classList.add('hidden');
            });

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(el => {
                el.classList.remove('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
            });

            // Show selected pane
            const target = document.getElementById(tabId);
            target.classList.remove('hidden');

            // Add active styling to selected tab
            const activeTabStr = tabId.replace('tab-', 'btn-');
            const activeTab = document.getElementById(activeTabStr);
            if (activeTab) {
                activeTab.classList.add('tab-active', 'bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
            }
        }

        function confirmDelete(id, name) {
            const modal = document.getElementById('delete_modal');
            const form = document.getElementById('delete_form');
            const nameSpan = document.getElementById('delete_vehicle_name');
            
            nameSpan.innerText = name;
            form.action = `/backend/bookingcar/${id}`;
            modal.showModal();
        }

        // Initialize first tab
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('btn-vehicles');
            if (btn) btn.classList.add('bg-white', 'text-slate-800', 'shadow-sm', 'ring-1', 'ring-slate-200');
        });
    </script>
@endsection