@extends('layouts.parking.app')

@section('content')
<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-car-side text-amber-500"></i> จองที่จอดรถในอาคาร (พนักงาน)
            </h2>
            <p class="text-slate-500 mt-1 font-medium">กรอกข้อมูลการจองที่จอดรถ และระบุแผนกเพื่อให้ระบบส่งคำขอไปยังหัวหน้าแผนก</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-emerald-400 to-emerald-500"></div>
            
            <form action="{{ route('parking.employee_reservations.store') }}" method="POST" class="p-8">
                @csrf
                
                @if($errors->any())
                    <div class="alert alert-error mb-6 rounded-xl bg-red-50 text-red-700 border border-red-200 p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                <h4 class="font-bold mb-1">เกิดข้อผิดพลาดในการบันทึกข้อมูล</h4>
                                <ul class="list-disc pl-5 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error mb-6 rounded-xl bg-red-50 text-red-700 border border-red-200 p-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <h4 class="font-bold m-0">{{ session('error') }}</h4>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <!-- Section 1: Employee Info -->
                    <div class="col-span-full">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-address-card text-slate-400 mr-2"></i>ข้อมูลผู้ขอจอง (Requestor Info)</h3>
                    </div>

                    <div class="form-control col-span-full md:col-span-1">
                        <label class="label"><span class="label-text font-bold text-slate-700">ชื่อพนักงานที่ทำการขอ</span></label>
                        <input type="text" value="{{ Auth::user()->fullname }}" class="input input-bordered rounded-xl border-slate-200 bg-slate-50 text-slate-500 font-bold" readonly disabled>
                        <p class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-info-circle"></i> ระบบใช้ชื่อบัญชีของคุณโดยอัตโนมัติ</p>
                    </div>

                    <!-- Section 2: Vehicle Info -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-car text-slate-400 mr-2"></i>ข้อมูลรถและช่องจอด (Vehicle & Parking)</h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ทะเบียนรถ <span class="text-red-500">*</span></span></label>
                        <input type="text" name="car_registration" value="{{ old('car_registration') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200 font-bold" placeholder="เช่น กข 1234 กทม" required>
                    </div>

                    <div class="form-control col-span-full">
                        <label class="label"><span class="label-text font-bold text-slate-700">รายละเอียด / เหตุผลที่ขอจอด</span></label>
                        <textarea name="details" class="textarea textarea-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200" placeholder="ระบุรายละเอียดเพิ่มเติม (ถ้ามี)" rows="2">{{ old('details') }}</textarea>
                    </div>

                    <div class="form-control">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                            <label class="label p-0"><span class="label-text font-bold text-slate-700">เลือกช่องจอด <span class="text-red-500">*</span></span></label>
                            <button type="button" onclick="openMapModal()" class="btn btn-xs bg-emerald-500 hover:bg-emerald-600 text-white border-none rounded-lg flex items-center justify-center gap-1 px-3 py-1.5 h-auto font-bold self-start sm:self-auto">
                                <i class="fa-solid fa-map-location-dot"></i> เลือกจากแผนผัง
                            </button>
                        </div>
                        <select id="slot_id_select" name="slot_id" class="select select-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200" required>
                            <option value="">-- กรุณาเลือกช่องจอด --</option>
                            @foreach($availableSlots as $slot)
                                <option value="{{ $slot->id }}" data-slot-number="{{ $slot->slot_number }}" {{ (old('slot_id') == $slot->id || (isset($selectedSlotId) && $selectedSlotId == $slot->id)) ? 'selected' : '' }}>
                                    ช่อง {{ $slot->slot_number }} ({{ $slot->zone->building ?? '' }} ชั้น {{ $slot->zone->floor ?? '' }} โซน {{ $slot->zone->zone ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        <p id="selected_map_slot_text" class="text-xs text-emerald-600 mt-1.5 font-bold hidden">
                            <i class="fa-solid fa-circle-check"></i> เลือกช่องจอดจากแผนผังสำเร็จแล้ว: ช่อง <span id="selected_slot_name"></span>
                        </p>
                    </div>
                    
                    <!-- Section 2: Department Info -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-building text-slate-400 mr-2"></i>ระบุแผนกและผู้อนุมัติ (Department Approval)</h3>
                    </div>

                    <div class="form-control col-span-full">
                        <label class="label"><span class="label-text font-bold text-slate-700">แผนกของท่าน <span class="text-red-500">*</span></span></label>
                        <select id="dept_id_select" name="dept_id" class="select select-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200" required>
                            <option value="">-- กรุณาเลือกแผนก --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control col-span-full" id="manager_info_box" style="display: none;">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-4">
                            <div class="mt-1 bg-white p-2 rounded-lg shadow-sm">
                                <i class="fa-solid fa-user-tie text-xl text-slate-400"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wide">ผู้ที่มีสิทธิ์อนุมัติของแผนก</p>
                                <p class="text-lg font-bold text-slate-800 mt-1" id="manager_name_display">รอการโหลดข้อมูล...</p>
                                <p class="text-sm text-slate-500 mt-1" id="manager_message"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Time -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-clock text-slate-400 mr-2"></i>เวลาที่คาดว่าจะใช้งาน (Schedule)</h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">วันที่และเวลาที่เข้า <span class="text-red-500">*</span></span></label>
                        <input type="datetime-local" name="checkin_datetime" value="{{ old('checkin_datetime', \Carbon\Carbon::now('Asia/Bangkok')->format('Y-m-d\TH:i')) }}" class="input input-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">วันที่และเวลาออก (ถ้ามี)</span></label>
                        <input type="datetime-local" name="checkout_datetime" value="{{ old('checkout_datetime') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-emerald-400 focus:ring focus:ring-emerald-200">
                    </div>

                </div>

                <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('parking.map.building') }}" class="btn btn-ghost rounded-xl text-slate-500 hover:bg-slate-100">ยกเลิก</a>
                    <button type="submit" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 border-none shadow-xl shadow-slate-200">
                        <i class="fa-solid fa-paper-plane mr-2"></i> ส่งคำขอจองที่จอดรถ
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<!-- Map Selector Modal -->
<div id="mapSelectorModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[999] flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="mapModalContent">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-500"></i> เลือกช่องจอดจากแผนผัง
                </h3>
                <p class="text-slate-500 text-xs mt-1 font-medium">กรุณาเลือกช่องจอดที่ว่าง (สีเขียว) จากแผนผังด้านล่าง</p>
            </div>
            <button type="button" onclick="closeMapModal()" class="btn btn-sm btn-circle btn-ghost text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto flex-1 bg-slate-100" style="padding: 0;">
            <!-- Building Map Only -->
            <div id="map_building_container" class="w-full h-[350px] md:h-[580px] bg-white shadow-inner overflow-hidden">
                <iframe src="{{ route('parking.map.building') }}?select_mode=1" class="w-full h-full border-none"></iframe>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 md:p-6 border-t border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50">
            <!-- Selected Info -->
            <div class="text-sm font-bold text-slate-700 text-center sm:text-left">
                สถานะการเลือก: <span id="modal_selected_slot_text" class="text-emerald-600">ยังไม่ได้เลือกช่องจอด</span>
            </div>
            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 justify-end">
                <button type="button" id="confirm_slot_btn" onclick="confirmSlotSelection()" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-6 border-none shadow w-full sm:w-auto" disabled>
                    ยืนยันเลือกช่องจอดนี้
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentSelectedSlotNumber = null;

// Listen for selection messages from iframes
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'slot_selected') {
        const slotNumber = event.data.slot;
        selectSlotFromMap(slotNumber, true);
        confirmSlotSelection();
    }
});

function openMapModal() {
    const modal = document.getElementById('mapSelectorModal');
    const content = document.getElementById('mapModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
    }, 50);
}

function closeMapModal() {
    const modal = document.getElementById('mapSelectorModal');
    const content = document.getElementById('mapModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function selectSlotFromMap(slotNumber, isAvailable) {
    currentSelectedSlotNumber = slotNumber;
    const infoText = document.getElementById('modal_selected_slot_text');
    infoText.innerHTML = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-bold">ช่องจอด: ${slotNumber}</span>`;
    document.getElementById('confirm_slot_btn').disabled = false;
}

function confirmSlotSelection() {
    if (!currentSelectedSlotNumber) return;
    
    const select = document.getElementById('slot_id_select');
    const option = Array.from(select.options).find(o => o.getAttribute('data-slot-number') === currentSelectedSlotNumber);
    
    if (option) {
        select.value = option.value;
        select.dispatchEvent(new Event('change'));
        document.getElementById('selected_slot_name').textContent = currentSelectedSlotNumber;
        document.getElementById('selected_map_slot_text').classList.remove('hidden');
    }
    
    closeMapModal();
}

document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('slot_id_select');
    if (select && select.value) {
        const option = select.options[select.selectedIndex];
        if (option) {
            const slotNum = option.getAttribute('data-slot-number');
            if (slotNum) {
                document.getElementById('selected_slot_name').textContent = slotNum;
                document.getElementById('selected_map_slot_text').classList.remove('hidden');
            }
        }
    }
    
    // Department Selection -> Fetch Manager
    const deptSelect = document.getElementById('dept_id_select');
    const managerBox = document.getElementById('manager_info_box');
    const managerNameDisplay = document.getElementById('manager_name_display');
    const managerMessage = document.getElementById('manager_message');
    
    function fetchManager(deptId) {
        if (!deptId) {
            managerBox.style.display = 'none';
            return;
        }
        
        managerBox.style.display = 'block';
        managerNameDisplay.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> กำลังโหลด...';
        managerNameDisplay.classList.remove('text-red-500', 'text-slate-800');
        managerNameDisplay.classList.add('text-slate-500');
        managerMessage.textContent = '';
        
        fetch(`/parking/api/departments/${deptId}/manager`)
            .then(res => res.json())
            .then(data => {
                managerNameDisplay.classList.remove('text-slate-500');
                if (data.success) {
                    managerNameDisplay.classList.add('text-slate-800');
                    managerNameDisplay.textContent = data.manager_name;
                    managerMessage.innerHTML = '<span class="text-emerald-500"><i class="fa-solid fa-circle-check"></i> ระบบจะส่งคำขอไปยังหัวหน้าแผนกท่านนี้</span>';
                } else {
                    managerNameDisplay.classList.add('text-red-500');
                    managerNameDisplay.textContent = 'ไม่พบข้อมูลหัวหน้าแผนก';
                    managerMessage.innerHTML = '<span class="text-red-500"><i class="fa-solid fa-circle-exclamation"></i> ' + data.message + '</span>';
                }
            })
            .catch(err => {
                managerNameDisplay.classList.remove('text-slate-500');
                managerNameDisplay.classList.add('text-red-500');
                managerNameDisplay.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อ';
                managerMessage.textContent = 'กรุณาลองใหม่อีกครั้ง';
            });
    }
    
    deptSelect.addEventListener('change', function() {
        fetchManager(this.value);
    });
    
    // Initial fetch if already selected
    if (deptSelect.value) {
        fetchManager(deptSelect.value);
    }
});
</script>
@endsection
