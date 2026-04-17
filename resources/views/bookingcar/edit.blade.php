@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6 animate-fadeIn">
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

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10">
                    <!-- Left Column: Booking Info & Return Info -->
                    <div class="space-y-8">
                        <!-- 1. Booking Info -->
                        <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100 shadow-sm">
                            <h3 class="text-slate-800 font-bold mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-circle-info text-blue-400"></i> ข้อมูลหลักการจอง
                            </h3>

                            <div class="space-y-4">
                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">ผู้จอง</span>
                                    </label>
                                    <input type="text" value="{{ $booking->user->emp_code }} - {{ $booking->user->first_name }} {{ $booking->user->last_name }}" 
                                        class="input input-bordered w-full bg-slate-200/50 text-slate-500 font-medium" disabled />
                                </div>

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

                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-base">ชื่อเจ้าของงาน / ผู้มอบหมายภารกิจ</span>
                                    </label>
                                    <input type="text" name="requester_name" value="{{ old('requester_name', $booking->requester_name) }}" 
                                        placeholder="เช่น ชื่อผู้จัดการฝ่ายฯ"
                                        class="input input-bordered w-full border-slate-200 focus:border-blue-400 font-medium text-slate-700" />
                                </div>
                            </div>
                        </div>

                        <!-- 2. Post-travel / Return Information (VERTICAL ALIGNMENT) -->
                        <div class="bg-emerald-50/50 rounded-2xl p-6 border border-emerald-100 shadow-sm border-l-4 border-l-emerald-500">
                            <h3 class="text-emerald-800 font-bold mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-rotate-left text-emerald-500"></i> ข้อมูลหลังการเดินทาง / คืนรถ
                            </h3>

                            <div class="space-y-5">
                                <div class="form-control w-full">
                                    <label class="label pb-1.5">
                                        <span class="label-text font-bold text-slate-700">สถานะการคืนรถ *</span>
                                    </label>
                                    <select name="return_status" class="select select-bordered w-full border-emerald-200 focus:border-emerald-400 font-medium text-slate-700 shadow-sm" required>
                                        <option value="ยังไม่ส่งคืน" {{ $booking->return_status == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>ยังไม่ส่งคืน</option>
                                        <option value="ส่งคืนแล้ว" {{ $booking->return_status == 'ส่งคืนแล้ว' ? 'selected' : '' }}>ส่งคืนแล้ว</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control w-full">
                                        <label class="label pb-1.5">
                                            <span class="label-text font-bold text-slate-700 text-xs">เลขไมล์ก่อนเดินทาง</span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-gauge-high text-slate-400 text-xs group-focus-within:text-emerald-500 transition-colors"></i>
                                            </div>
                                            <input type="number" name="mileage_before" value="{{ old('mileage_before', $booking->mileage_before) }}" 
                                                placeholder="0"
                                                class="input input-bordered w-full pl-8 h-10 border-slate-200 font-medium text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 shadow-sm" />
                                        </div>
                                    </div>
                                    <div class="form-control w-full">
                                        <label class="label pb-1.5">
                                            <span class="label-text font-bold text-slate-700 text-xs">เลขไมล์หลังเดินทาง</span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fa-solid fa-gauge-simple-high text-slate-400 text-xs group-focus-within:text-emerald-500 transition-colors"></i>
                                            </div>
                                            <input type="number" name="mileage_after" value="{{ old('mileage_after', $booking->mileage_after) }}" 
                                                placeholder="0"
                                                class="input input-bordered w-full pl-8 h-10 border-slate-200 font-medium text-slate-700 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 shadow-sm" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pb-1.5">
                                        <span class="label-text font-bold text-slate-700">หมายเหตุการคืนรถ</span>
                                    </label>
                                    <textarea name="note_returning" class="textarea textarea-bordered w-full border-slate-200 h-24" 
                                        placeholder="ระบุหมายเหตุหรือปัญหา...">{{ old('note_returning', $booking->note_returning) }}</textarea>
                                </div>

                                <div class="space-y-3">
                                    <label class="label pb-0">
                                        <span class="label-text font-bold text-slate-700 text-xs">อัปโหลดรูปภาพ (ก่อน/หลัง)</span>
                                    </label>
                                    <input type="file" name="attachment_going[]" multiple class="file-input file-input-bordered file-input-sm w-full border-slate-200" />
                                    <input type="file" name="attachment_returning[]" multiple class="file-input file-input-bordered file-input-sm w-full border-slate-200 mt-1" />
                                </div>
                            </div>
                        </div>

                        <!-- 3. Current Status & Approval -->
                        <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 shadow-sm">
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
                                <span class="badge {{ $statusClass }} text-white border-0 font-bold shadow-sm">{{ $booking->status }}</span>
                            </div>
                            <div class="flex gap-2">
                                <select name="status" class="select select-bordered select-sm flex-1 border-blue-200 focus:border-blue-400 font-medium">
                                    <option value="รออนุมัติ" {{ $booking->status == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ</option>
                                    <option value="อนุมัติแล้ว" {{ $booking->status == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                                    <option value="ไม่อนุมัติ" {{ $booking->status == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                                    <option value="ยกเลิก" {{ $booking->status == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Status Stepper & Travel Details -->
                    <div class="space-y-8">
                        <!-- Modern Status Stepper -->
                        <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-16 -mt-16 blur-3xl transition-all group-hover:bg-indigo-100/50"></div>
                            <h3 class="text-slate-800 font-black text-lg mb-8 flex items-center gap-3 relative z-10">
                                <div class="w-2 h-6 bg-indigo-600 rounded-full"></div>
                                สถานะการดำเนินการ
                            </h3>

                            <div class="relative z-10 space-y-0">
                                @php
                                    $isApproved = in_array($booking->status, ['อนุมัติแล้ว']);
                                    $isRejected = in_array($booking->status, ['ไม่อนุมัติ', 'ยกเลิก']);
                                    $isReturned = $booking->return_status === 'ส่งคืนแล้ว';
                                    $isPending = $booking->status === 'รออนุมัติ';
                                @endphp

                                <div class="flex gap-4 min-h-[70px]">
                                    <div class="flex flex-col items-center">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg z-10">
                                            <i class="fa-solid fa-file-signature text-[10px]"></i>
                                        </div>
                                        <div class="flex-1 w-[2px] bg-indigo-600"></div>
                                    </div>
                                    <div class="pb-4">
                                        <p class="text-[13px] font-black text-slate-800 tracking-tight">ยื่นคำขอจองรถ</p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-4 min-h-[70px]">
                                    <div class="flex flex-col items-center">
                                        @if($isPending)
                                            <div class="w-7 h-7 rounded-full bg-white border-4 border-amber-400 text-amber-500 flex items-center justify-center z-10 animate-pulse">
                                                <i class="fa-solid fa-spinner fa-spin text-[10px]"></i>
                                            </div>
                                        @elseif($isApproved || $isReturned)
                                            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg z-10">
                                                <i class="fa-solid fa-check text-[10px]"></i>
                                            </div>
                                        @elseif($isRejected)
                                            <div class="w-7 h-7 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-lg z-10">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                            </div>
                                        @else
                                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center z-10">
                                                <i class="fa-solid fa-ellipsis text-[10px]"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 w-[2px] {{ $isApproved || $isReturned ? 'bg-emerald-500' : ($isRejected ? 'bg-rose-500' : 'bg-slate-100') }}"></div>
                                    </div>
                                    <div class="pb-4">
                                        <p class="text-[13px] font-black {{ $isPending ? 'text-amber-600' : ($isRejected ? 'text-rose-600' : 'text-slate-800') }} tracking-tight tracking-tight">พิจารณาคำขอ</p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5">สถานะ: {{ $booking->status }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        @if($isReturned)
                                            <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-lg z-10">
                                                <i class="fa-solid fa-flag-checkered text-[10px]"></i>
                                            </div>
                                        @else
                                            <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-300 flex items-center justify-center z-10">
                                                <i class="fa-solid fa-car-side text-[10px]"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black {{ $isReturned ? 'text-blue-600' : 'text-slate-800' }} tracking-tight">ดำเนินการเสร็จสิ้น</p>
                                        <p class="text-[11px] font-bold text-slate-400 mt-0.5">{{ $booking->return_status }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Travel Details Card -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                            <h3 class="text-slate-800 font-bold mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-list-ul text-blue-400"></i> รายละเอียดการเดินทาง
                            </h3>

                            <div class="space-y-6">
                                <!-- Date/Time Section -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text font-bold text-slate-700 text-xs">วันที่เริ่มเดินทาง</span>
                                        </label>
                                        <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d')) }}"
                                            class="input input-bordered w-full h-10 border-slate-100 font-medium" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text font-bold text-slate-700 text-xs">วันที่สิ้นสุด</span>
                                        </label>
                                        <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d')) }}"
                                            class="input input-bordered w-full h-10 border-slate-100 font-medium" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text font-bold text-slate-400 text-xs">เวลาออกเดินทาง</span>
                                        </label>
                                        <input type="text" name="start_time_only" 
                                            value="{{ old('start_time_only', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}"
                                            class="input input-bordered w-full h-10 border-slate-100 font-medium" />
                                    </div>
                                    <div class="form-control">
                                        <label class="label py-1">
                                            <span class="label-text font-bold text-slate-400 text-xs">เวลากลับโดยประมาณ</span>
                                        </label>
                                        <input type="text" name="end_time_only" 
                                            value="{{ old('end_time_only', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}"
                                            class="input input-bordered w-full h-10 border-slate-100 font-medium" />
                                    </div>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-red-600 text-xs">จุดหมายปลายทาง *</span>
                                    </label>
                                    <input type="text" name="destination" value="{{ old('destination', $booking->destination) }}"
                                        class="input input-bordered w-full h-10 border-red-100 font-medium" required />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label pb-1">
                                            <span class="label-text font-bold text-red-600 text-xs">จังหวัด *</span>
                                        </label>
                                        <select name="province" id="province_select" class="select select-bordered select-sm w-full font-medium" required>
                                            <option value="" disabled>-- เลือกจังหวัด --</option>
                                            @foreach($provinces as $prov)
                                                <option value="{{ $prov }}" {{ (old('province', $booking->province) == $prov) ? 'selected' : '' }}>{{ $prov }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-control">
                                        <label class="label pb-1">
                                            <span class="label-text font-bold text-red-600 text-xs">อำเภอ *</span>
                                        </label>
                                        <select name="district" id="district_select" class="select select-bordered select-sm w-full font-medium" required>
                                            <option value="" disabled>-- เลือกอำเภอ --</option>
                                            @if($booking->district)
                                                <option value="{{ $booking->district }}" selected>{{ $booking->district }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-xs">วัตถุประสงค์ของการใช้รถ</span>
                                    </label>
                                    <textarea name="purpose" class="textarea textarea-bordered w-full h-20 border-slate-100" 
                                        placeholder="ระบุวัตถุประสงค์...">{{ old('purpose', $booking->purpose) }}</textarea>
                                </div>

                                <div class="form-control w-full">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700 text-xs">จำนวนผู้โดยสาร (ท่าน)</span>
                                    </label>
                                    <input type="number" name="passenger_count" value="{{ old('passenger_count', $booking->passenger_count ?? 1) }}" 
                                        class="input input-bordered w-full h-10 border-slate-100 font-medium" min="1" />
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

        if (provinceSelect.value) {
            loadDistricts(provinceSelect.value, "{{ old('district', $booking->district) }}");
        }
    }
});
</script>
@endpush
