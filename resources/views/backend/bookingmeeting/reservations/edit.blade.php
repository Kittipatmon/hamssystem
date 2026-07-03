@extends('layouts.navmeeting.app')

@section('title', 'แก้ไขการจองห้องประชุม')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="text-xs breadcrumbs text-slate-500 px-2">
            <ul>
                <li><a href="{{ route('backend.bookingmeeting.reservations.index') }}" class="hover:text-red-700 font-semibold"><i
                            class="fa-solid fa-list-check mr-2"></i> รายการจองห้องประชุม</a></li>
                <li class="text-slate-800 font-bold">แก้ไขข้อมูลการจอง</li>
            </ul>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">แก้ไขแบบฟอร์มการจองห้องประชุม</h2>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">Ref: {{ $reservation->reservation_code }}</p>
                </div>
            </div>

            <form action="{{ route('backend.bookingmeeting.reservations.update', $reservation->reservation_id) }}"
                method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">

                    <!-- Room Selection -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">ห้องประชุมที่จองใช้งาน <span class="text-red-500">*</span></label>
                        <select name="room_id" required
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                            @foreach($rooms as $room)
                                <option value="{{ $room->room_id }}" {{ old('room_id', $reservation->room_id) == $room->room_id ? 'selected' : '' }}>
                                    {{ $room->room_name }} (จุได้ {{ $room->capacity }} คน)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Topic -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">หัวข้อการจัดประชุม <span class="text-red-500">*</span></label>
                        <input type="text" name="topic" value="{{ old('topic', $reservation->topic) }}" required
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Dates -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">วันที่เริ่มเดินทาง/เริ่มต้นประชุม <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar text-slate-400"></i>
                            </div>
                            <input type="date" name="reservation_date"
                                value="{{ old('reservation_date', $reservation->reservation_date) }}" required
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">วันที่สิ้นสุดการประชุม <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar-check text-slate-400"></i>
                            </div>
                            <input type="date" name="reservation_dateend"
                                value="{{ old('reservation_dateend', $reservation->reservation_dateend ?? $reservation->reservation_date) }}" required
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">เวลาเริ่มต้นประชุม <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                            </div>
                            <input type="time" name="start_time"
                                value="{{ old('start_time', substr($reservation->start_time, 0, 5)) }}" required
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">เวลาสิ้นสุดการประชุม <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                            </div>
                            <input type="time" name="end_time"
                                value="{{ old('end_time', substr($reservation->end_time, 0, 5)) }}" required
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- Participant Count -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">จำนวนผู้เข้าร่วมประชุม <span class="text-red-500">*</span></label>
                        <input type="number" name="participant_count"
                            value="{{ old('participant_count', $reservation->participant_count) }}" required min="1"
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Requester Name -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">ชื่อผู้เรียนเสนอ (เรียนเสนอผู้พิจารณา) <span class="text-red-500">*</span></label>
                        <input type="text" name="requester_name"
                            value="{{ old('requester_name', $reservation->requester_name) }}" required
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Status Selection -->
                    <div class="col-span-1 md:col-span-2 mt-3 p-4 bg-slate-50 border border-slate-200 rounded">
                        <label class="block font-bold text-slate-700 mb-2.5">สถานะการจอง (สิทธิ์สำหรับผู้อนุมัติ) <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4 font-semibold">
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="status" value="pending" {{ old('status', $reservation->status) == 'pending' ? 'checked' : '' }}
                                    class="radio radio-warning radio-sm">
                                <span class="text-amber-600">รอการตรวจสอบ/อนุมัติ</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="status" value="acknowledge" {{ old('status', $reservation->status) == 'acknowledge' ? 'checked' : '' }}
                                    class="radio radio-success radio-sm">
                                <span class="text-green-600">อนุมัติแล้ว</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="status" value="rejected" {{ old('status', $reservation->status) == 'rejected' ? 'checked' : '' }}
                                    class="radio radio-error radio-sm">
                                <span class="text-red-600">ไม่อนุมัติ</span>
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="radio" name="status" value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'checked' : '' }}
                                    class="radio radio-info radio-sm">
                                <span class="text-slate-500">ยกเลิก</span>
                            </label>
                        </div>
                        @error('status') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-5 border-t border-slate-200 flex items-center justify-end gap-3 font-semibold text-xs">
                    <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                        class="px-5 py-2 rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-5 py-2 rounded bg-amber-500 hover:bg-amber-600 text-white shadow transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection