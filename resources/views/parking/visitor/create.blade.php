@extends('layouts.parking.app')

@section('content')
<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-calendar-plus text-amber-500"></i> จองที่จอดรถให้แขก
            </h2>
            <p class="text-slate-500 mt-1 font-medium">กรอกข้อมูลผู้ติดต่อเพื่อทำการจองช่องจอดล่วงหน้า</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-amber-400 to-amber-500"></div>
            
            <form action="{{ route('parking.visitors.store') }}" method="POST" class="p-8">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <!-- Section 1: Guest Info -->
                    <div class="col-span-full">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-address-card text-slate-400 mr-2"></i>ข้อมูลแขก (Visitor Information)</h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ชื่อ-นามสกุล แขก <span class="text-red-500">*</span></span></label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200" placeholder="ระบุชื่อแขก" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">เบอร์โทรศัพท์ติดต่อ <span class="text-red-500">*</span></span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200" placeholder="08X-XXX-XXXX" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">บริษัท / หน่วยงาน</span></label>
                        <input type="text" name="company" value="{{ old('company') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200" placeholder="ระบุชื่อบริษัท (ถ้ามี)">
                    </div>

                    <div class="form-control col-span-full">
                        <label class="label"><span class="label-text font-bold text-slate-700">รายละเอียดการติดต่อ (เรื่องที่มาติดต่อ)</span></label>
                        <textarea name="contact_details" rows="2" class="textarea textarea-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200 text-base" placeholder="ระบุรายละเอียด เช่น มาประชุม, ส่งเอกสาร, ซ่อมบำรุง, ฯลฯ">{{ old('contact_details') }}</textarea>
                    </div>

                    <!-- Section 2: Vehicle Info -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-car text-slate-400 mr-2"></i>ข้อมูลรถและช่องจอด (Vehicle & Parking)</h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ทะเบียนรถ <span class="text-red-500">*</span></span></label>
                        <input type="text" name="car_registration" value="{{ old('car_registration') }}" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200 font-bold" placeholder="เช่น กข 1234 กทม" required>
                    </div>

                    <div class="form-control">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                            <label class="label p-0"><span class="label-text font-bold text-slate-700">เลือกช่องจอด (ระบุหรือไม่ก็ได้)</span></label>
                            <button type="button" onclick="openMapModal()" class="btn btn-xs bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg flex items-center justify-center gap-1 px-3 py-1.5 h-auto font-bold self-start sm:self-auto">
                                <i class="fa-solid fa-map-location-dot"></i> เลือกจากแผนผัง
                            </button>
                        </div>
                        <select id="slot_id_select" name="slot_id" class="select select-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200">
                            <option value="">-- ให้ระบบจัดสรรช่องจอดให้ (Auto) --</option>
                            @foreach($availableSlots as $slot)
                                <option value="{{ $slot->id }}" data-slot-number="{{ $slot->slot_number }}" {{ (old('slot_id') == $slot->id || (isset($selectedSlotId) && $selectedSlotId == $slot->id)) ? 'selected' : '' }}>
                                    ช่อง {{ $slot->slot_number }} ({{ $slot->zone->building ?? '' }} ชั้น {{ $slot->zone->floor ?? '' }} โซน {{ $slot->zone->zone ?? '' }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="slot_number_manual" id="slot_number_manual">
                        <p id="selected_map_slot_text" class="text-xs text-emerald-600 mt-1.5 font-bold hidden">
                            <i class="fa-solid fa-circle-check"></i> เลือกช่องจอดจากแผนผังสำเร็จแล้ว: ช่อง <span id="selected_slot_name"></span>
                        </p>
                    </div>

                    <!-- Section 3: Time -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4"><i class="fa-solid fa-clock text-slate-400 mr-2"></i>เวลาที่คาดว่าจะใช้งาน (Schedule)</h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">วันที่และเวลาที่เข้า <span class="text-red-500">*</span></span></label>
                        <input type="datetime-local" name="checkin_datetime" value="{{ old('checkin_datetime', \Carbon\Carbon::now('Asia/Bangkok')->format('Y-m-d\TH:i')) }}" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200" required>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ระยะเวลาที่จอด (ชั่วโมง)</span></label>
                        <input type="number" name="duration_hours" value="{{ old('duration_hours', 2) }}" min="1" class="input input-bordered rounded-xl border-slate-200 focus:border-amber-400 focus:ring focus:ring-amber-200">
                    </div>

                </div>

                <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('parking.visitors.index') }}" class="btn btn-ghost rounded-xl text-slate-500 hover:bg-slate-100">ยกเลิก</a>
                    <button type="submit" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 border-none shadow-xl shadow-slate-200">
                        <i class="fa-solid fa-save mr-2"></i> ยืนยันการจองที่จอดรถ
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<style>
  #mapSelectorModal .board-hq {
    position: relative;
    width: 100%;
    aspect-ratio: 2200/530;
    background: #ffffff;
  }
  #mapSelectorModal .slot rect {
    fill: #ffffff;
    stroke: #c4d2e0;
    stroke-width: 1.5;
    cursor: pointer;
    transition: fill .2s;
  }
  #mapSelectorModal .slot text {
    fill: #5c7590;
    font-weight: 700;
    font-family: 'IBM Plex Mono', monospace;
    text-anchor: middle;
    dominant-baseline: middle;
    pointer-events: none;
  }
  #mapSelectorModal .slot.state-avail rect {
    fill: rgba(63, 168, 124, 0.15);
    stroke: #3fa87c;
    stroke-width: 2.2;
  }
  #mapSelectorModal .slot.state-avail:hover rect {
    fill: rgba(63, 168, 124, 0.3);
  }
  #mapSelectorModal .slot.state-avail text {
    fill: #3fa87c;
  }
  #mapSelectorModal .slot.state-occupied rect {
    fill: rgba(224, 112, 63, 0.15);
    stroke: #e0703f;
    stroke-width: 2.2;
    cursor: not-allowed;
  }
  #mapSelectorModal .slot.state-occupied text {
    fill: #e0703f;
  }
  #mapSelectorModal .slot.state-reserved rect {
    fill: rgba(168, 182, 196, 0.15);
    stroke: #a8b6c4;
    stroke-width: 2.2;
    cursor: not-allowed;
  }
  #mapSelectorModal .slot.state-reserved text {
    fill: #a8b6c4;
  }
  #mapSelectorModal .board-building {
    position: relative;
    width: 100%;
    min-width: 1100px;
    aspect-ratio: 1280/660;
    background: #ffffff;
    overflow: hidden;
  }
  #mapSelectorModal .pct {
    position: absolute;
  }
  #mapSelectorModal .bay {
    background: #ffffff;
    border: 1px solid #9aa2aa;
  }
  #mapSelectorModal .triple-bay {
    display: flex;
    align-items: flex-end;
    gap: 2%;
    height: 100%;
    padding: 20% 4% 5% 4%;
  }
  #mapSelectorModal .car-slot {
    flex: 1;
    height: 100%;
    border: 2px solid #6fbf6a;
    border-radius: 5px;
    background: rgba(111,191,106,.10);
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
  }
  #mapSelectorModal .car-slot:hover {
    filter: brightness(1.05);
  }
  #mapSelectorModal .car-slot.occupied {
    border-color: #e03b3b;
    background: rgba(224,59,59,.16);
    cursor: not-allowed;
  }
  #mapSelectorModal .badge {
    position: absolute;
    top: 6px;
    left: 6px;
    background: #2f8fd4;
    color: #fff;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    font-size: 13px;
    width: 22px;
    height: 22px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0,0,0,.25);
    z-index: 5;
  }
  #mapSelectorModal .room {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: .8rem;
    font-weight: 600;
    color: #1c3550;
    border: 1px solid #1c3550;
    padding: 4px;
  }
  #mapSelectorModal .room.gray { background: #c9ced3; }
  #mapSelectorModal .room.yellow { background: #f4d03f; }
  #mapSelectorModal .room.green { background: #6fbf6a; color: #fff; }
  #mapSelectorModal .room.blue { background: #2f8fd4; color: #fff; }
  #mapSelectorModal .room.lightblue { background: #bfe3f7; }
  #mapSelectorModal .room.exec-label {
    align-items: flex-end;
    padding-bottom: 6px;
    font-weight: 600;
    color: #1c3550;
    background: #bfe3f7;
  }
  #mapSelectorModal .vtext {
    writing-mode: vertical-rl;
    text-orientation: mixed;
  }
  #mapSelectorModal .title-tag {
    background: #2f8fd4;
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    padding: 6px 16px;
    border-radius: 0 0 0 8px;
  }
  #mapSelectorModal .stripe-red { background: #e03b3b; }
  #mapSelectorModal .stripe-green { background: #4caf50; }
  #mapSelectorModal .stair-hatch {
    background-image: repeating-linear-gradient(0deg, #d7dbdf 0 4px, #eceff1 4px 8px);
    border: 1px solid #1c3550;
  }
  #mapSelectorModal .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #e03b3b;
  }
</style>

<!-- Map Selector Modal -->
<div id="mapSelectorModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[999] flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="mapModalContent">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-amber-500"></i> เลือกช่องจอดจากแผนผัง
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
                สถานะการเลือก: <span id="modal_selected_slot_text" class="text-amber-600">ยังไม่ได้เลือกช่องจอด</span>
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
const availableSelectOptions = Array.from(document.querySelectorAll('#slot_id_select option')).map(o => o.getAttribute('data-slot-number')).filter(Boolean);
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

// Tab switching removed — Building map only for guests

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
        document.getElementById('slot_number_manual').value = ""; // Clear manual
    } else {
        // Slot is not in dropdown, use manual hidden input
        select.value = "";
        select.dispatchEvent(new Event('change'));
        document.getElementById('slot_number_manual').value = currentSelectedSlotNumber;
    }
    document.getElementById('selected_slot_name').textContent = currentSelectedSlotNumber;
    document.getElementById('selected_map_slot_text').classList.remove('hidden');
    
    closeMapModal();
}

document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('slot_id_select');
    if (select && select.value) {
        const option = select.options[select.selectedIndex];
        const slotNum = option.getAttribute('data-slot-number');
        if (slotNum) {
            document.getElementById('selected_slot_name').textContent = slotNum;
            document.getElementById('selected_map_slot_text').classList.remove('hidden');
        }
    }
});
</script>
@endsection
