@extends('layouts.navmeeting.app')

@section('title', 'แก้ไขการจองห้องประชุม')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs text-slate-500 mb-4 px-2">
            <ul>
                <li><a href="{{ route('backend.bookingmeeting.reservations.index') }}" class="hover:text-blue-600"><i
                            class="fa-solid fa-list-check mr-2"></i> รายการจองห้องประชุม</a></li>
                <li class="text-slate-700 font-medium">แก้ไขข้อมูล</li>
            </ul>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-6 flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">แก้ไขการจอง</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ref: {{ $reservation->reservation_code }}</p>
                </div>
            </div>

            <form action="{{ route('backend.bookingmeeting.reservations.update', $reservation->reservation_id) }}"
                method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Room Selection -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">เลือกห้องประชุม <span
                                class="text-red-500">*</span></label>
                        <select name="room_id" required
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                            @foreach($rooms as $room)
                                <option value="{{ $room->room_id }}" {{ old('room_id', $reservation->room_id) == $room->room_id ? 'selected' : '' }}>
                                    {{ $room->room_name }} (จุได้ {{ $room->capacity }} คน)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Topic -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">หัวข้อการประชุม <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="topic" value="{{ old('topic', $reservation->topic) }}" required
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                    </div>

                    <!-- Dates -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่เริ่ม <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar text-slate-400"></i>
                            </div>
                            <input type="date" name="reservation_date"
                                value="{{ old('reservation_date', $reservation->reservation_date) }}" required
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">วันที่สิ้นสุด <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar-check text-slate-400"></i>
                            </div>
                            <input type="date" name="reservation_dateend"
                                value="{{ old('reservation_dateend', $reservation->reservation_dateend ?? $reservation->reservation_date) }}" required
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">เวลาเริ่ม <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                            </div>
                            <input type="time" name="start_time"
                                value="{{ old('start_time', substr($reservation->start_time, 0, 5)) }}" required
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                        </div>
                    </div>

                    <!-- End Time -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">เวลาสิ้นสุด <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-clock text-slate-400"></i>
                            </div>
                            <input type="time" name="end_time"
                                value="{{ old('end_time', substr($reservation->end_time, 0, 5)) }}" required
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Participant Count -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">จำนวนผู้เข้าร่วม <span
                                class="text-red-500">*</span></label>
                        <input type="number" name="participant_count"
                            value="{{ old('participant_count', $reservation->participant_count) }}" required min="1"
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                    </div>

                    <!-- Requester Name -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อผู้ลงชื่อจอง <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="requester_name"
                            value="{{ old('requester_name', $reservation->requester_name) }}" required
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-sm">
                    </div>

                    <!-- Status -->
                    <div class="col-span-1 md:col-span-2 mt-4 p-5 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">สถานะการจอง <span
                                class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="pending" {{ old('status', $reservation->status) == 'pending' ? 'checked' : '' }}
                                    class="radio radio-warning radio-sm">
                                <span class="text-sm font-medium text-amber-600">รอการตรวจสอบ/รออนุมัติ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="acknowledge" {{ old('status', $reservation->status) == 'acknowledge' ? 'checked' : '' }}
                                    class="radio radio-success radio-sm">
                                <span class="text-sm font-medium text-green-600">อนุมัติแล้ว (จองสำเร็จ)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="rejected" {{ old('status', $reservation->status) == 'rejected' ? 'checked' : '' }}
                                    class="radio radio-error radio-sm">
                                <span class="text-sm font-medium text-red-600">ไม่อนุมัติ</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'checked' : '' }}
                                    class="radio radio-info radio-sm">
                                <span class="text-sm font-medium text-orange-600">ยกเลิก</span>
                            </label>
                        </div>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md shadow-blue-200 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection