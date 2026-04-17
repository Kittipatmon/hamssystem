@extends('layouts.navmeeting.app')

@section('title', 'รายละเอียดการจองห้องประชุม')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs text-slate-500 mb-4 px-2">
            <ul>
                <li><a href="{{ route('backend.bookingmeeting.reservations.index') }}" class="hover:text-blue-600"><i
                            class="fa-solid fa-list-check mr-2"></i> รายการจองห้องประชุม</a></li>
                <li class="text-slate-700 font-medium">รายละเอียด</li>
            </ul>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-6 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-lg shadow-inner">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">รายละเอียดการจอง: {{ $reservation->reservation_code }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">ถูกสร้างเมื่อ
                            {{ $reservation->created_at->locale('th')->addYears(543)->translatedFormat('d/m/Y H:i') }} น.
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    @if($reservation->status == 'pending')
                        <span
                            class="bg-amber-100 text-amber-700 border border-amber-200 px-4 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                            <i class="fa-regular fa-clock"></i> รออนุมัติ
                        </span>
                    @elseif($reservation->status == 'acknowledge')
                        <span
                            class="bg-green-100 text-green-700 border border-green-200 px-4 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-check"></i> อนุมัติแล้ว
                        </span>
                    @elseif($reservation->status == 'rejected')
                        <span
                            class="bg-red-100 text-red-700 border border-red-200 px-4 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-xmark"></i> ไม่อนุมัติ
                        </span>
                    @elseif($reservation->status == 'cancelled')
                        <span
                            class="bg-orange-100 text-orange-700 border border-orange-200 px-4 py-1.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                            <i class="fa-solid fa-ban"></i> ยกเลิก
                        </span>
                    @endif

                    @if ($reservation->approvedBy)
                        <div class="text-[11px] font-bold mt-1 {{ $reservation->status == 'rejected' ? 'text-red-500' : 'text-emerald-600' }}">
                            <i class="fa-solid fa-user-check mr-1"></i>โดย: {{ $reservation->approvedBy->fullname }}
                        </div>
                        @if ($reservation->approved_at)
                            <div class="text-[9px] text-slate-400">
                                เมื่อ: {{ \Carbon\Carbon::parse($reservation->approved_at)->locale('th')->addYears(543)->translatedFormat('d M Y H:i') }} น.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                <!-- Project Details -->
                <div>
                    <h3
                        class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i> ข้อมูลการประชุม
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">ห้องประชุม</p>
                            <p class="font-medium text-slate-800 mt-1">{{ $reservation->room->room_name ?? 'N/A' }} <span
                                    class="text-slate-500 text-sm font-normal">({{ $reservation->room->location ?? '' }})</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">หัวข้อการประชุม</p>
                            <p class="font-medium text-slate-800 mt-1">{{ $reservation->topic }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">วันที่</p>
                            <p class="font-medium text-slate-800 mt-1"><i
                                    class="fa-regular fa-calendar text-slate-400 mr-1"></i>
                                {{ \Carbon\Carbon::parse($reservation->reservation_date)->locale('th')->addYears(543)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">เวลา</p>
                            <p class="font-medium text-slate-800 mt-1"><i
                                    class="fa-regular fa-clock text-slate-400 mr-1"></i>
                                {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }} น.
                            </p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">จำนวนผู้เข้าร่วม
                            </p>
                            <p class="font-medium text-slate-800 mt-1">{{ $reservation->participant_count }} คน</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">ผู้ขอจอง</p>
                            <p class="font-medium text-slate-800 mt-1">{{ $reservation->requester_name }} <span
                                    class="text-slate-500 text-sm font-normal">({{ $reservation->user->emp_code ?? '' }})</span>
                            </p>
                        </div>
                    </div>

                    @if($reservation->objective || $reservation->details)
                        <div class="mt-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            @if($reservation->objective)
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">วัตถุประสงค์</p>
                                <p class="text-sm text-slate-700 mb-3">{{ $reservation->objective }}</p>
                            @endif
                            @if($reservation->details)
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">
                                    รายละเอียดเพิ่มเติม</p>
                                <p class="text-sm text-slate-700">{{ $reservation->details }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Catering Services -->
                @if($reservation->break_morning || $reservation->break_afternoon || $reservation->lunch || $reservation->dinner)
                    <div>
                        <h3
                            class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-mug-saucer text-amber-500"></i> บริการจัดเลี้ยง
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if($reservation->break_morning)
                                <div class="flex gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-mug-hot"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-slate-800">เบรกเช้า</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->break_morning_detail ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($reservation->lunch)
                                <div class="flex gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-bowl-food"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-slate-800">อาหารกลางวัน</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->lunch_detail ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($reservation->break_afternoon)
                                <div class="flex gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-mug-hot"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-slate-800">เบรกบ่าย</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->break_afternoon_detail ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if($reservation->dinner)
                                <div class="flex gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-utensils"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-slate-800">อาหารเย็น</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $reservation->dinner_detail ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Attachments -->
                @if($reservation->attached_file || $reservation->budget_file)
                    <div>
                        <h3
                            class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-paperclip text-slate-500"></i> ไฟล์แนบ
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            @if($reservation->attached_file)
                                <a href="{{ asset('documents/reservations/' . $reservation->attached_file) }}" target="_blank"
                                    class="flex items-center gap-3 px-4 py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors group">
                                    <div class="text-blue-500 text-xl group-hover:scale-110 transition-transform"><i
                                            class="fa-solid fa-file-pdf"></i></div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">เอกสารแนบการประชุม</p>
                                        <p class="text-[10px] text-slate-400">คลิกเพื่อดูไฟล์</p>
                                    </div>
                                </a>
                            @endif

                            @if($reservation->budget_file)
                                <a href="{{ asset('documents/reservations/' . $reservation->budget_file) }}" target="_blank"
                                    class="flex items-center gap-3 px-4 py-3 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors group">
                                    <div class="text-green-500 text-xl group-hover:scale-110 transition-transform"><i
                                            class="fa-solid fa-file-invoice-dollar"></i></div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">เอกสารอนุมัติงบประมาณ</p>
                                        <p class="text-[10px] text-slate-400">คลิกเพื่อดูไฟล์</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <!-- Footer Actions -->
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                    class="px-5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-600 font-medium hover:bg-slate-50 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> กลับ
                </a>

                <div class="flex gap-2">
                    <a href="{{ route('backend.bookingmeeting.reservations.edit', $reservation->reservation_id) }}"
                        class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-medium hover:bg-amber-600 shadow-md shadow-amber-200 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> แก้ไขการจอง
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
