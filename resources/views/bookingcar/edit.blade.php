@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-[1200px] mx-auto px-4 py-8 animate-fadeIn text-slate-800">
        
        <!-- Header Panel -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-5 mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-700 border border-red-100">
                    <i class="fa-solid fa-file-pen text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">แบบฟอร์มแก้ไขคำขอการใช้งานและจัดการคืนรถ</h1>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">แก้ไขรายละเอียดคำขอจองคิวรถยนต์ส่วนกลาง และบันทึกข้อมูลรายงานเลขไมล์หลังเสร็จภารกิจ</p>
                </div>
            </div>
            <a href="{{ route('bookingcar.dashboard') }}" 
                class="w-9 h-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors shadow-sm"
                title="กลับหน้าหลัก">
                <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <form action="{{ route('bookingcar.update', $booking->booking_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 rounded-lg p-4 mb-8 text-xs text-rose-700">
                    <div class="flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm mt-0.5"></i>
                        <div>
                            <span class="font-bold block mb-1">เกิดข้อผิดพลาดในการตรวจสอบข้อมูล:</span>
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Side: Registration Fields (70% or lg:col-span-8) -->
                <div class="lg:col-span-8 space-y-6">
                    
                    <!-- Box 1: Core booking details -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                        <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-5 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-red-700"></i> 1. ข้อมูลรายละเอียดผู้จองและรถยนต์
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Requester Code & Name -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ผู้ทำรายการจอง</label>
                                <input type="text" value="{{ $booking->user ? ($booking->user->emp_code . ' - ' . $booking->user->first_name . ' ' . $booking->user->last_name) : 'ไม่ระบุผู้จอง (N/A)' }}" 
                                    class="w-full bg-slate-100 border border-slate-200 rounded-md h-9 px-3 text-xs text-slate-500 outline-none" disabled />
                            </div>

                            <!-- Requester department -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">แผนก / สังกัดผู้จอง</label>
                                <input type="text" value="{{ ($booking->user && $booking->user->department) ? $booking->user->department->department_name : '-' }}" 
                                    class="w-full bg-slate-100 border border-slate-200 rounded-md h-9 px-3 text-xs text-slate-500 outline-none" disabled />
                            </div>

                            <!-- Vehicle Selection -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-red-700 mb-1.5">รถยนต์ส่วนกลางที่ได้รับมอบหมาย *</label>
                                <select name="vehicle_id" class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" required>
                                    <option value="" disabled>-- เลือกรถส่วนกลาง --</option>
                                    @foreach($vehicles as $vehicle)
                                        <option value="{{ $vehicle->vehicle_id }}" {{ $booking->vehicle_id == $vehicle->vehicle_id ? 'selected' : '' }}>
                                            {{ $vehicle->name }} ({{ $vehicle->model_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Project/Requester Owner -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ชื่อเจ้าของงาน / ผู้ใช้บริการหลัก (ถ้ามี)</label>
                                <input type="text" name="requester_name" value="{{ old('requester_name', $booking->requester_name) }}" 
                                    placeholder="ระบุชื่อผู้จัดการ หรือเจ้าของโครงการ..."
                                    class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" />
                            </div>
                        </div>
                    </div>

                    <!-- Box 2: Route details -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                        <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-5 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-red-700"></i> 2. กำหนดการเดินทางและสถานที่ปลายทาง
                        </h3>

                        <div class="space-y-4">
                            <!-- Date Range -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">วันที่เดินทางไป</label>
                                    <input type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d')) }}"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" />
                                </div>
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">วันที่เดินทางกลับ</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d')) }}"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" />
                                </div>
                            </div>

                            <!-- Time Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">เวลาออกเดินทาง (เช่น 08:30)</label>
                                    <input type="text" name="start_time_only" 
                                        value="{{ old('start_time_only', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" />
                                </div>
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">เวลาเดินทางกลับโดยประมาณ</label>
                                    <input type="text" name="end_time_only" 
                                        value="{{ old('end_time_only', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" />
                                </div>
                            </div>

                            <!-- Destination Input -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-red-700 mb-1.5">สถานที่ปลายทาง (โรงพยาบาล/หน่วยงานปลายทาง) *</label>
                                <input type="text" name="destination" value="{{ old('destination', $booking->destination) }}"
                                    class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" required />
                            </div>

                            <!-- Province & District Dropdown -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-red-700 mb-1.5">จังหวัดปลายทาง *</label>
                                    <select name="province" id="province_select" class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" required>
                                        <option value="" disabled>-- เลือกจังหวัด --</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov }}" {{ (old('province', $booking->province) == $prov) ? 'selected' : '' }}>{{ $prov }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-red-700 mb-1.5">อำเภอปลายทาง *</label>
                                    <select name="district" id="district_select" class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" required>
                                        <option value="" disabled>-- เลือกอำเภอ --</option>
                                        @if($booking->district)
                                            <option value="{{ $booking->district }}" selected>{{ $booking->district }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">วัตถุประสงค์ของการออกเดินทาง</label>
                                <textarea name="purpose" class="w-full bg-white border border-slate-200 rounded-md p-3 text-xs h-20 focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" 
                                    placeholder="ระบุรายละเอียดโครงการ หรือเหตุผลความจำเป็นในการขอใช้รถ...">{{ old('purpose', $booking->purpose) }}</textarea>
                            </div>

                            <!-- Passengers -->
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">จำนวนผู้โดยสารทั้งหมด (ท่าน)</label>
                                <input type="number" name="passenger_count" value="{{ old('passenger_count', $booking->passenger_count ?? 1) }}" 
                                    class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all" min="1" />
                            </div>
                        </div>
                    </div>

                    <!-- Box 3: Post travel register (Return status) -->
                    <div class="bg-emerald-50/40 border border-emerald-200 rounded-lg p-6">
                        <h3 class="text-xs font-black text-emerald-800 uppercase tracking-wider mb-5 pb-2 border-b border-emerald-100 flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-700"></i> 3. บันทึกและควบคุมข้อมูลรายงานเลขไมล์ / คืนรถ
                        </h3>

                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-600 mb-1.5">สถานะการส่งคืนรถยนต์ส่วนกลาง *</label>
                                <select name="return_status" class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all font-bold" required>
                                    <option value="ยังไม่ส่งคืน" {{ $booking->return_status == 'ยังไม่ส่งคืน' ? 'selected' : '' }}>ยังไม่ส่งคืน (Not Returned)</option>
                                    <option value="ส่งคืนแล้ว" {{ $booking->return_status == 'ส่งคืนแล้ว' ? 'selected' : '' }}>ส่งคืนแล้ว (Returned)</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">เลขไมล์ก่อนเดินทาง (กิโลเมตร)</label>
                                    <input type="number" name="mileage_before" value="{{ old('mileage_before', $booking->mileage_before) }}" 
                                        placeholder="0"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all" />
                                </div>
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">เลขไมล์หลังเดินทาง (กิโลเมตร)</label>
                                    <input type="number" name="mileage_after" value="{{ old('mileage_after', $booking->mileage_after) }}" 
                                        placeholder="0"
                                        class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all" />
                                </div>
                            </div>

                            <div class="form-control">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1.5">หมายเหตุการคืนรถ / ปัญหาที่พบในระหว่างใช้งาน</label>
                                <textarea name="note_returning" class="w-full bg-white border border-slate-200 rounded-md p-3 text-xs h-20 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition-all" 
                                    placeholder="ระบุอาการชำรุด หรือรายงานปัญหาคราบน้ำมัน ยางแบน และอื่น ๆ...">{{ old('note_returning', $booking->note_returning) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">ภาพถ่ายรถตอนรับรถ (ก่อนเดินทาง)</label>
                                    <input type="file" name="attachment_going[]" multiple class="w-full bg-white border border-slate-200 rounded-md text-xs py-1.5 px-3 outline-none" />
                                </div>
                                <div class="form-control">
                                    <label class="block text-[11px] font-bold text-slate-500 mb-1">ภาพถ่ายรถตอนส่งคืน (หลังเดินทาง)</label>
                                    <input type="file" name="attachment_returning[]" multiple class="w-full bg-white border border-slate-200 rounded-md text-xs py-1.5 px-3 outline-none" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Workflow stepper & Approval Status (30% or lg:col-span-4) -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Stepper Panel -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-5 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-route text-red-700"></i> ขั้นตอนการดำเนินการ
                        </h3>

                        <div class="space-y-6 relative pl-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                            @php
                                $isApproved = $booking->status === 'อนุมัติแล้ว';
                                $isRejected = in_array($booking->status, ['ไม่อนุมัติ', 'ยกเลิก']);
                                $isReturned = $booking->return_status === 'ส่งคืนแล้ว';
                                $isPending = $booking->status === 'รออนุมัติ';
                            @endphp

                            <!-- Step 1: Registered -->
                            <div class="relative">
                                <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-slate-900 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-white">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">ยื่นแบบฟอร์มคำขอจอง</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">วันที่ทำรายการ: {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Step 2: Approved / Disapproved -->
                            <div class="relative">
                                @if($isPending)
                                    <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-amber-400 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-white animate-pulse">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                @elseif($isApproved)
                                    <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-white">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                @elseif($isRejected)
                                    <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-rose-500 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-white">
                                        <i class="fa-solid fa-xmark"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xs font-bold {{ $isPending ? 'text-amber-600' : ($isRejected ? 'text-rose-600' : 'text-slate-800') }}">
                                        ผลพิจารณาอนุมัติคำขอ
                                    </h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">สถานะ: {{ $booking->status }}</p>
                                </div>
                            </div>

                            <!-- Step 3: Returned -->
                            <div class="relative">
                                @if($isReturned)
                                    <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-slate-900 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-white">
                                        <i class="fa-solid fa-flag-checkered"></i>
                                    </div>
                                @else
                                    <div class="absolute -left-6 top-1 w-4.5 h-4.5 rounded-full bg-slate-100 border-2 border-white shadow-sm flex items-center justify-center text-[8px] text-slate-300">
                                        <i class="fa-solid fa-car-side"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="text-xs font-bold {{ $isReturned ? 'text-slate-800' : 'text-slate-400' }}">บันทึกการส่งคืนรถเสร็จสมบูรณ์</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">สถานะคืนรถ: {{ $booking->return_status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Panel Form -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-clipboard-check text-red-700"></i> ควบคุมผลการพิจารณา
                        </h3>

                        <div class="form-control">
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ปรับเปลี่ยนสถานะใบอนุญาต</label>
                            <select name="status" class="w-full bg-white border border-slate-200 rounded-md h-9 px-3 text-xs focus:border-red-700 focus:ring-1 focus:ring-red-700 outline-none transition-all font-bold">
                                <option value="รออนุมัติ" {{ $booking->status == 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ (Pending)</option>
                                <option value="อนุมัติแล้ว" {{ $booking->status == 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว (Approved)</option>
                                <option value="ไม่อนุมัติ" {{ $booking->status == 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ (Rejected)</option>
                                <option value="ยกเลิก" {{ $booking->status == 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก (Cancelled)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="mt-8 pt-5 border-t border-slate-200 flex justify-center gap-2.5">
                <a href="{{ route('bookingcar.dashboard') }}" 
                    class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold h-9 text-xs rounded-md px-6 flex items-center justify-center transition-all">
                    ยกเลิก / กลับหน้าทะเบียน
                </a>
                <button type="submit" 
                    class="bg-red-700 hover:bg-red-800 text-white font-bold h-9 text-xs rounded-md px-8 shadow-sm transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-save"></i> บันทึกข้อมูลทะเบียน
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
