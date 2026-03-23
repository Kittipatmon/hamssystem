@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('bookingcar.dashboard') }}"
                class="btn btn-sm btn-circle btn-ghost text-slate-500 hover:bg-slate-200">
                <i class="fa-solid fa-arrow-left text-lg"></i>
            </a>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800">
                จัดการคำร้องขอใช้รถ
            </h2>
        </div>

        @if ($errors->any())
            <div class="alert alert-error shadow-sm mb-6 bg-red-50 text-red-800 border-red-200">
                <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                <div>
                    <ul class="text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex flex-wrap justify-between items-center gap-4">
                <div>
                    <div class="text-[11px] font-semibold tracking-widest text-slate-400 uppercase mb-1">เลขที่ใบจอง</div>
                    <div class="font-mono text-lg font-bold text-slate-700">{{ $booking->booking_code }}</div>
                </div>
                <div>
                    <div class="text-[11px] font-semibold tracking-widest text-slate-400 uppercase mb-1">สถานะการจองปัจจุบัน
                    </div>
                    @php
                        $statusClass = match ($booking->status) {
                            'อนุมัติแล้ว' => 'bg-green-500 border-green-600',
                            'รออนุมัติ' => 'bg-orange-500 border-orange-600',
                            'ไม่อนุมัติ', 'ยกเลิก' => 'bg-red-500 border-red-600',
                            default => 'bg-slate-500 border-slate-600'
                        };
                    @endphp
                    <span
                        class="inline-flex items-center px-3 py-1.5 rounded-full text-white text-[12px] font-bold shadow-sm border {{ $statusClass }}">
                        {{ $booking->status }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <!-- Approval Action Form -->
                <form action="{{ route('bookingcar.approve', $booking->booking_id) }}" method="POST"
                    class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    @csrf
                    @method('PUT')
                    <h3 class="font-bold text-blue-800 text-sm mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check"></i> อนุมัติ / ปฏิเสธ คำร้อง
                    </h3>
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="w-full md:w-auto flex-1">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700 text-xs text-blue-700">เปลี่ยนสถานะ</span></label>
                            <select name="status" class="select select-sm select-bordered w-full border-blue-200">
                                <option value="รออนุมัติ" {{ $booking->status == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ
                                </option>
                                <option value="อนุมัติแล้ว" {{ $booking->status == 'อนุมัติแล้ว' ? 'selected' : '' }}>
                                    อนุมัติแล้ว</option>
                                <option value="ไม่อนุมัติ" {{ $booking->status == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ
                                </option>
                                <option value="ยกเลิก" {{ $booking->status == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="btn btn-sm bg-blue-600 hover:bg-blue-700 text-white border-0 shadow-sm w-full md:w-auto">
                            บันทึกสถานะ
                        </button>
                    </div>
                </form>

                <hr class="border-slate-100 mb-6">

                <!-- Edit Details Form -->
                <form action="{{ route('bookingcar.update', $booking->booking_id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h3 class="font-bold text-slate-700 text-md mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-slate-400"></i> แก้ไขข้อมูลการจอง และ คืนรถ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Vehicle -->
                        <div class="form-control mb-2">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700">รถส่วนกลาง</span></label>
                            <select name="vehicle_id" class="select select-sm select-bordered w-full" required>
                                @foreach($vehicles as $vehicle)
                                    <option value="{{ $vehicle->vehicle_id }}" {{ $booking->vehicle_id == $vehicle->vehicle_id ? 'selected' : '' }}>
                                        {{ $vehicle->name }} ({{ $vehicle->model_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Destination -->
                        <div class="form-control mb-2">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700">จุดหมายปลายทาง</span></label>
                            <input type="text" name="destination" value="{{ old('destination', $booking->destination) }}"
                                class="input input-sm input-bordered w-full" required />
                        </div>

                        <!-- Date/Time -->
                        <div class="form-control mb-2">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700">เวลาออกเดินทาง</span></label>
                            <input type="datetime-local" name="start_time"
                                value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d\TH:i')) }}"
                                class="input input-sm input-bordered w-full" required />
                        </div>
                        <div class="form-control mb-2">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700">เวลากลับโดยประมาณ</span></label>
                            <input type="datetime-local" name="end_time"
                                value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i')) }}"
                                class="input input-sm input-bordered w-full" required />
                        </div>

                        <!-- Current Attachments -->
                        <div class="col-span-full form-control mb-2 p-3 bg-slate-50 rounded-lg">
                            <label class="label py-1"><span
                                    class="label-text font-semibold text-slate-700">เอกสารแนบประกอบการจอง
                                    (ถ้ามี)</span></label>
                            @if($booking->attachment)
                                <a href="{{ asset($booking->attachment) }}" target="_blank"
                                    class="text-blue-500 hover:underline text-sm flex items-center gap-1"><i
                                        class="fa-solid fa-paperclip"></i> ดูเอกสารแนบ</a>
                            @else
                                <span class="text-slate-400 text-sm">ไม่มีไฟล์แนบ</span>
                            @endif
                        </div>
                    </div>

                    <!-- Return Section -->
                    <div class="mt-6 p-5 bg-orange-50 rounded-xl border border-orange-100">
                        <h4 class="font-bold text-orange-800 text-sm mb-4"><i class="fa-solid fa-flag-checkered mr-1"></i>
                            ข้อมูลการคืนรถ</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">สถานะการส่งคืน</span></label>
                                <select name="return_status"
                                    class="select select-sm select-bordered w-full border-orange-200">
                                    <option value="ยังไม่ส่งคืน" {{ $booking->return_status == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>ยังไม่ส่งคืน</option>
                                    <option value="ส่งคืนแล้ว" {{ $booking->return_status == 'ส่งคืนแล้ว' ? 'selected' : '' }}>ส่งคืนแล้ว</option>
                                    <option value="มีปัญหา" {{ $booking->return_status == 'มีปัญหา' ? 'selected' : '' }}>
                                        มีปัญหา</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">เลขไมล์ก่อนเดินทาง</span></label>
                                <input type="number" name="mileage_before"
                                    value="{{ old('mileage_before', $booking->mileage_before) }}"
                                    class="input input-sm input-bordered w-full border-orange-200" />
                            </div>

                            <div class="form-control">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">เลขไมล์หลังเดินทาง</span></label>
                                <input type="number" name="mileage_after"
                                    value="{{ old('mileage_after', $booking->mileage_after) }}"
                                    class="input input-sm input-bordered w-full border-orange-200" />
                            </div>

                            <div class="form-control col-span-full">
                                <label class="label py-1"><span
                                        class="label-text font-semibold text-slate-700">หมายเหตุหลังใช้งาน</span></label>
                                <textarea name="note_returning"
                                    class="textarea textarea-bordered w-full border-orange-200 h-16">{{ old('note_returning', $booking->note_returning) }}</textarea>
                            </div>

                            <div class="form-control">
                                <label class="label py-1 pb-0">
                                    <span class="label-text font-semibold text-slate-700">รูปตอนไป</span>
                                </label>
                                @if($booking->attachment_going)
                                    @php
                                        $going_paths = json_decode($booking->attachment_going, true);
                                        if (!is_array($going_paths))
                                            $going_paths = [$booking->attachment_going];
                                    @endphp
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($going_paths as $idx => $path)
                                            <a href="{{ asset($path) }}" target="_blank"
                                                class="text-blue-500 text-xs hover:underline bg-blue-50 px-2 py-1 rounded inline-block">
                                                <i class="fa-solid fa-image"></i> ดูรูปเดิมที่ {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" name="attachment_going[]" multiple accept="image/*"
                                    class="file-input file-input-bordered file-input-sm w-full border-orange-200" />
                            </div>

                            <div class="form-control">
                                <label class="label py-1 pb-0">
                                    <span class="label-text font-semibold text-slate-700">รูปตอนกลับ</span>
                                </label>
                                @if($booking->attachment_returning)
                                    @php
                                        $returning_paths = json_decode($booking->attachment_returning, true);
                                        if (!is_array($returning_paths))
                                            $returning_paths = [$booking->attachment_returning];
                                    @endphp
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($returning_paths as $idx => $path)
                                            <a href="{{ asset($path) }}" target="_blank"
                                                class="text-blue-500 text-xs hover:underline bg-blue-50 px-2 py-1 rounded inline-block">
                                                <i class="fa-solid fa-image"></i> ดูรูปเดิมที่ {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" name="attachment_returning[]" multiple accept="image/*"
                                    class="file-input file-input-bordered file-input-sm w-full border-orange-200" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('bookingcar.dashboard') }}" class="btn btn-sm btn-outline">ยกเลิก</a>
                            <button type="submit" class="btn btn-sm bg-slate-800 hover:bg-slate-900 text-white border-0">
                                <i class="fa-solid fa-save mr-1"></i> บันทึกข้อมูล
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection