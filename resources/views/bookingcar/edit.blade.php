@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-6 animate-fadeIn">
        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-slate-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-car text-xl"></i>
                    </div>
                    <h1 class="text-xl font-bold text-red-600 tracking-tight">แบบฟอร์มจองรถส่วนกลาง (คำร้อง)</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('bookingcar.dashboard') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </a>
                </div>
            </div>

            <form action="{{ route('bookingcar.update', $booking->booking_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column: Booking Info -->
                    <div class="space-y-6">
                        <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100">
                            <h3 class="text-slate-800 font-bold mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-blue-400"></i> ข้อมูลหลักการจอง
                            </h3>

                            <div class="space-y-4">
                                <!-- Booker -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">ผู้จอง</span>
                                    </label>
                                    <input type="text" value="{{ $booking->user->employee_code }} - {{ $booking->user->first_name }} {{ $booking->user->last_name }}" 
                                        class="input input-bordered w-full bg-slate-200/50 text-slate-500 font-medium" disabled />
                                </div>

                                <!-- Vehicle -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-red-600 text-base">รถส่วนกลาง *</span>
                                    </label>
                                    <select name="vehicle_id" class="select select-bordered w-full border-red-200 focus:border-red-400 focus:ring-red-100 font-medium text-slate-700" required>
                                        <option value="" disabled>-- เลือกรถส่วนกลาง --</option>
                                        @foreach($vehicles as $vehicle)
                                            <option value="{{ $vehicle->vehicle_id }}" {{ $booking->vehicle_id == $vehicle->vehicle_id ? 'selected' : '' }}>
                                                {{ $vehicle->name }} ({{ $vehicle->model_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Assignee -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">ชื่อเจ้าของงาน / ผู้มอบหมายภารกิจ</span>
                                    </label>
                                    <input type="text" name="requester_name" value="{{ old('requester_name', $booking->requester_name) }}" 
                                        placeholder="เช่น ชื่อผู้จัดการฝ่ายฯ"
                                        class="input input-bordered w-full border-slate-200 focus:border-blue-400 font-medium text-slate-700" />
                                </div>

                                <!-- Attachment -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">แนบไฟล์ (เอกสารประกอบการจอง)</span>
                                    </label>
                                    <div class="flex flex-col gap-2">
                                        <input type="file" name="attachment" class="file-input file-input-bordered w-full border-slate-200" />
                                        @if($booking->attachment)
                                            <a href="{{ asset($booking->attachment) }}" target="_blank" class="text-blue-600 text-sm hover:underline flex items-center gap-1 px-1">
                                                <i class="fa-solid fa-paperclip"></i> ดูเอกสารปัจจุบัน
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status & Approval (Preserving existing functionality) -->
                        <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100">
                            <h3 class="text-blue-800 font-bold mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-check"></i> สถานะและการอนุมัติ
                            </h3>
                            <div class="flex items-center gap-4 mb-4">
                                <span class="text-slate-500 text-sm">สถานะปัจจุบัน:</span>
                                @php
                                    $statusClass = match($booking->status) {
                                        'อนุมัติแล้ว' => 'bg-green-500',
                                        'รออนุมัติ' => 'bg-orange-500',
                                        default => 'bg-red-500'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} text-white border-0 font-bold">{{ $booking->status }}</span>
                            </div>
                            
                            <div class="flex gap-2">
                                <select name="status" class="select select-bordered select-sm flex-1 border-blue-200">
                                    <option value="รออนุมัติ" {{ $booking->status == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ</option>
                                    <option value="อนุมัติแล้ว" {{ $booking->status == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                                    <option value="ไม่อนุมัติ" {{ $booking->status == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                                    <option value="ยกเลิก" {{ $booking->status == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Travel Details -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl p-6 border border-blue-100 h-full">
                            <h3 class="text-slate-800 font-bold mb-6 flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-list-ul text-blue-400"></i> รายละเอียดการเดินทาง
                                </div>
                            </h3>

                            <div class="space-y-6">
                                <!-- Date/Time Section -->
                                <div>
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-red-600 font-bold text-lg">ระบุวันเวลาที่เดินทาง *</h4>
                                        <button type="button" class="btn btn-outline btn-info btn-xs rounded-lg gap-1 lowercase font-medium">
                                            <i class="fa-regular fa-clock"></i> ตัวอย่างเวลา
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text font-bold text-slate-700"><i class="fa-solid fa-calendar-days text-red-500 mr-1"></i> วันที่เริ่มเดินทาง</span>
                                            </label>
                                            <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d')) }}"
                                                class="input input-bordered w-full border-red-100" />
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text font-bold text-slate-700"><i class="fa-solid fa-calendar-days text-red-500 mr-1"></i> วันที่สิ้นสุด</span>
                                            </label>
                                            <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d')) }}"
                                                class="input input-bordered w-full border-red-100" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text font-bold text-slate-400 text-xs">เวลาออกเดินทาง</span>
                                            </label>
                                            <div class="relative time-picker-container" id="start-time-picker">
                                                <input type="text" name="start_time_only" id="start_time_input"
                                                    value="{{ old('start_time_only', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}"
                                                    class="input input-bordered w-full pr-10 border-slate-200 cursor-pointer" readonly />
                                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors time-picker-toggle">
                                                    <i class="fa-regular fa-clock text-lg"></i>
                                                </button>
                                                <!-- Custom Time Picker Menu -->
                                                <div class="time-picker-menu absolute top-full left-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 z-[100] hidden p-4 min-w-[180px] animate-fadeIn">
                                                    <div class="flex flex-col gap-4">
                                                        <!-- Header Info -->
                                                        <div class="flex gap-2 justify-center pb-2 border-b border-slate-50">
                                                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-xl current-hour-header">08</div>
                                                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-xl current-min-header">30</div>
                                                        </div>
                                                        <!-- Selection Columns -->
                                                        <div class="flex gap-4 justify-center h-48 overflow-hidden relative">
                                                            <!-- Hour Column -->
                                                            <div class="flex-1 overflow-y-auto no-scrollbar hour-column text-center space-y-2 py-12 scroll-smooth">
                                                                @for($i=0; $i<=23; $i++)
                                                                    <div class="hour-item py-1 cursor-pointer transition-all rounded-lg hover:bg-slate-50 text-slate-400 font-medium" data-hour="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</div>
                                                                @endfor
                                                            </div>
                                                            <!-- Minute Column -->
                                                            <div class="flex-1 overflow-y-auto no-scrollbar min-column text-center space-y-2 py-12 scroll-smooth">
                                                                @for($i=0; $i<=59; $i++)
                                                                    <div class="min-item py-1 cursor-pointer transition-all rounded-lg hover:bg-slate-50 text-slate-400 font-medium" data-min="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</div>
                                                                @endfor
                                                            </div>
                                                            <!-- Selection Overlay -->
                                                            <div class="absolute top-1/2 left-0 w-full h-10 -translate-y-1/2 bg-blue-500/10 pointer-events-none rounded-lg border-y border-blue-500/20"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text font-bold text-slate-400 text-xs">เวลากลับโดยประมาณ</span>
                                            </label>
                                            <div class="relative time-picker-container" id="end-time-picker">
                                                <input type="text" name="end_time_only" id="end_time_input"
                                                    value="{{ old('end_time_only', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}"
                                                    class="input input-bordered w-full pr-10 border-slate-200 cursor-pointer" readonly />
                                                <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors time-picker-toggle">
                                                    <i class="fa-regular fa-clock text-lg"></i>
                                                </button>
                                                <!-- Custom Time Picker Menu -->
                                                <div class="time-picker-menu absolute top-full right-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 z-[100] hidden p-4 min-w-[180px] animate-fadeIn">
                                                    <div class="flex flex-col gap-4">
                                                        <!-- Header Info -->
                                                        <div class="flex gap-2 justify-center pb-2 border-b border-slate-50">
                                                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-xl current-hour-header">17</div>
                                                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white font-bold text-xl current-min-header">00</div>
                                                        </div>
                                                        <!-- Selection Columns -->
                                                        <div class="flex gap-4 justify-center h-48 overflow-hidden relative">
                                                            <!-- Hour Column -->
                                                            <div class="flex-1 overflow-y-auto no-scrollbar hour-column text-center space-y-2 py-12 scroll-smooth">
                                                                @for($i=0; $i<=23; $i++)
                                                                    <div class="hour-item py-1 cursor-pointer transition-all rounded-lg hover:bg-slate-50 text-slate-400 font-medium" data-hour="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</div>
                                                                @endfor
                                                            </div>
                                                            <!-- Minute Column -->
                                                            <div class="flex-1 overflow-y-auto no-scrollbar min-column text-center space-y-2 py-12 scroll-smooth">
                                                                @for($i=0; $i<=59; $i++)
                                                                    <div class="min-item py-1 cursor-pointer transition-all rounded-lg hover:bg-slate-50 text-slate-400 font-medium" data-min="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</div>
                                                                @endfor
                                                            </div>
                                                            <!-- Selection Overlay -->
                                                            <div class="absolute top-1/2 left-0 w-full h-10 -translate-y-1/2 bg-blue-500/10 pointer-events-none rounded-lg border-y border-blue-500/20"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Destination -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-red-600 text-base">จุดหมายปลายทางของการเดินทาง *</span>
                                    </label>
                                    <input type="text" name="destination" value="{{ old('destination', $booking->destination) }}"
                                        placeholder="ระบุสถานที่ปลายทางที่จะเดินทางไป" 
                                        class="input input-bordered w-full border-red-200" required />
                                </div>

                                <!-- District & Province -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label pb-1">
                                            <span class="label-text font-bold text-red-600 text-base">จังหวัดที่จะเดินทางไป *</span>
                                        </label>
                                        <select name="province" id="province_select" 
                                            class="select select-bordered w-full border-red-200 focus:border-red-400 font-medium text-slate-700" 
                                            required>
                                            <option value="" disabled>-- เลือกจังหวัด --</option>
                                            @foreach($provinces as $prov)
                                                <option value="{{ $prov }}" {{ (old('province', $booking->province) == $prov) ? 'selected' : '' }}>{{ $prov }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control">
                                        <label class="label pb-1">
                                            <span class="label-text font-bold text-red-600 text-base">อำเภอที่เดินทางไป *</span>
                                        </label>
                                        <select name="district" id="district_select" 
                                            class="select select-bordered w-full border-red-200 focus:border-red-400 font-medium text-slate-700" 
                                            required>
                                            <option value="" disabled>-- เลือกอำเภอ --</option>
                                            @if($booking->district)
                                                <option value="{{ $booking->district }}" selected>{{ $booking->district }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Purpose -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">วัตถุประสงค์ของการใช้รถ</span>
                                    </label>
                                    <textarea name="purpose" class="textarea textarea-bordered w-full border-slate-200 h-24" 
                                        placeholder="ระบุวัตถุประสงค์ในการเดินทาง...">{{ old('purpose', $booking->purpose) }}</textarea>
                                </div>

                                <!-- Passenger Count -->
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">จำนวนผู้โดยสาร (ท่าน)</span>
                                    </label>
                                    <input type="number" name="passenger_count" value="{{ old('passenger_count', $booking->passenger_count ?? 1) }}" 
                                        class="input input-bordered w-full border-slate-200" min="1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post-travel / Return Information -->
                <div class="px-6 pb-8">
                    <div class="bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100">
                        <h3 class="text-emerald-800 font-bold mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-rotate-left text-emerald-500"></i> ข้อมูลหลังการเดินทาง / คืนรถ
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Return Status -->
                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700 text-base">สถานะการคืนรถ *</span>
                                </label>
                                <select name="return_status" class="select select-bordered w-full border-emerald-200 focus:border-emerald-400 font-medium text-slate-700" required>
                                    <option value="ยังไม่ส่งคืน" {{ $booking->return_status == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>ยังไม่ส่งคืน</option>
                                    <option value="ส่งคืนแล้ว" {{ $booking->return_status == 'ส่งคืนแล้ว' ? 'selected' : '' }}>ส่งคืนแล้ว</option>
                                </select>
                            </div>

                            <!-- Mileage Before -->
                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700 text-base">เลขไมล์ก่อนเดินทาง</span>
                                </label>
                                <input type="number" name="mileage_before" value="{{ old('mileage_before', $booking->mileage_before) }}" 
                                    placeholder="0"
                                    class="input input-bordered w-full border-slate-200 font-medium text-slate-700" />
                            </div>

                            <!-- Mileage After -->
                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700 text-base">เลขไมล์หลังเดินทาง</span>
                                </label>
                                <input type="number" name="mileage_after" value="{{ old('mileage_after', $booking->mileage_after) }}" 
                                    placeholder="0"
                                    class="input input-bordered w-full border-slate-200 font-medium text-slate-700" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Note Returning -->
                            <div class="form-control w-full">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700 text-base">หมายเหตุการคืนรถ</span>
                                </label>
                                <textarea name="note_returning" class="textarea textarea-bordered w-full border-slate-200 h-32" 
                                    placeholder="ระบุหมายเหตุหรือปัญหาที่พบระหว่างการเดินทาง...">{{ old('note_returning', $booking->note_returning) }}</textarea>
                            </div>

                            <!-- File Uploads -->
                            <div class="space-y-4">
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">รูปภาพก่อนเดินทาง (Going)</span>
                                    </label>
                                    <input type="file" name="attachment_going[]" multiple class="file-input file-input-bordered w-full border-slate-200" />
                                    @if($booking->attachment_going)
                                        @php $goingFiles = json_decode($booking->attachment_going, true); @endphp
                                        @if(is_array($goingFiles))
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach($goingFiles as $file)
                                                    <a href="{{ asset($file) }}" target="_blank" class="badge badge-outline gap-1 py-3 hover:bg-slate-100">
                                                        <i class="fa-solid fa-image"></i> ดูรูป
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <a href="{{ asset($booking->attachment_going) }}" target="_blank" class="badge badge-outline gap-1 py-3 mt-2 hover:bg-slate-100">
                                                <i class="fa-solid fa-image"></i> ดูรูปปัจจุบัน
                                            </a>
                                        @endif
                                    @endif
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">รูปภาพหลังเดินทาง (Returning)</span>
                                    </label>
                                    <input type="file" name="attachment_returning[]" multiple class="file-input file-input-bordered w-full border-slate-200" />
                                    @if($booking->attachment_returning)
                                        @php $returningFiles = json_decode($booking->attachment_returning, true); @endphp
                                        @if(is_array($returningFiles))
                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach($returningFiles as $file)
                                                    <a href="{{ asset($file) }}" target="_blank" class="badge badge-outline gap-1 py-3 hover:bg-slate-100">
                                                        <i class="fa-solid fa-image"></i> ดูรูป
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <a href="{{ asset($booking->attachment_returning) }}" target="_blank" class="badge badge-outline gap-1 py-3 mt-2 hover:bg-slate-100">
                                                <i class="fa-solid fa-image"></i> ดูรูปปัจจุบัน
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('bookingcar.dashboard') }}" class="btn btn-outline border-slate-300 text-slate-600">ยกเลิก</a>
                    <button type="submit" class="btn bg-slate-800 hover:bg-slate-900 text-white border-0 px-8">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Additional sections like Return Info could be placed here if needed, 
         but kept in the main form for simplicity matching the mockup -->
@endsection

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .time-picker-menu {
        animation: slideDown 0.2s ease-out;
        transform-origin: top;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px) scaleY(0.9); }
        to { opacity: 1; transform: translateY(0) scaleY(1); }
    }

    .hour-item.active, .min-item.active {
        @apply bg-blue-500 text-white font-bold scale-110 shadow-md;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const containers = document.querySelectorAll('.time-picker-container');
    
    containers.forEach(container => {
        const toggle = container.querySelector('.time-picker-toggle');
        const input = container.querySelector('input');
        const menu = container.querySelector('.time-picker-menu');
        const hourHeader = container.querySelector('.current-hour-header');
        const minHeader = container.querySelector('.current-min-header');
        const hourItems = container.querySelectorAll('.hour-item');
        const minItems = container.querySelectorAll('.min-item');
        
        // Parse initial value (24h format hh:mm)
        const initialValue = input.value;
        let [hour, min] = initialValue.split(':');

        let currentHour = hour || '08';
        let currentMin = min || '00';

        const updateInput = () => {
            input.value = `${currentHour}:${currentMin}`;
            hourHeader.textContent = currentHour;
            minHeader.textContent = currentMin;
        };

        const setActive = (items, value, attr) => {
            items.forEach(item => {
                if(item.getAttribute(attr) === value) {
                    item.classList.add('active');
                    // Auto scroll to active item
                    const column = item.parentElement;
                    const scrollOffset = item.offsetTop - column.offsetTop - (column.clientHeight / 2) + (item.clientHeight / 2);
                    column.scrollTo({ top: scrollOffset, behavior: 'instant' });
                } else {
                    item.classList.remove('active');
                }
            });
        };

        // Initialize active states and scroll
        const initPicker = () => {
            setActive(hourItems, currentHour, 'data-hour');
            setActive(minItems, currentMin, 'data-min');
            hourHeader.textContent = currentHour;
            minHeader.textContent = currentMin;
        };

        const toggleMenu = (e) => {
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');
            document.querySelectorAll('.time-picker-menu').forEach(m => m.classList.add('hidden'));
            if(!isOpen) {
                menu.classList.remove('hidden');
                initPicker(); // Scroll to current values when opened
            }
        };

        toggle.addEventListener('click', toggleMenu);
        input.addEventListener('click', toggleMenu);

        hourItems.forEach(item => {
            item.addEventListener('click', () => {
                currentHour = item.getAttribute('data-hour');
                setActive(hourItems, currentHour, 'data-hour');
                updateInput();
            });
        });

        minItems.forEach(item => {
            item.addEventListener('click', () => {
                currentMin = item.getAttribute('data-min');
                setActive(minItems, currentMin, 'data-min');
                updateInput();
            });
        });
    });

    // Province & District Dependent Dropdown
    const provinceSelect = document.getElementById('province_select');
    const districtSelect = document.getElementById('district_select');

    if (provinceSelect && districtSelect) {
        const loadDistricts = (province, currentDistrict = null) => {
            if (!province) {
                districtSelect.innerHTML = '<option value="" disabled selected>-- เลือกอำเภอ --</option>';
                districtSelect.disabled = true;
                return;
            }

            districtSelect.disabled = true;
            districtSelect.innerHTML = '<option value="" disabled selected>กำลังโหลด...</option>';

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
                })
                .catch(error => {
                    console.error('Error fetching districts:', error);
                    districtSelect.innerHTML = '<option value="" disabled selected>โหลดข้อมูลผิดพลาด</option>';
                });
        };

        provinceSelect.addEventListener('change', function() {
            loadDistricts(this.value);
        });

        // Initial load for Edit Page
        if (provinceSelect.value) {
            loadDistricts(provinceSelect.value, "{{ old('district', $booking->district) }}");
        }
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.time-picker-menu').forEach(m => m.classList.add('hidden'));
    });

    document.querySelectorAll('.time-picker-menu').forEach(m => {
        m.addEventListener('click', e => e.stopPropagation());
    });
});
</script>
@endpush