@extends('layouts.parking.app')

@section('content')
<!-- Add jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Custom Select2 Styling to match modern Tailwind UI */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        border-color: #cbd5e1;
        border-radius: 0.75rem;
        height: 3rem;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
        box-shadow: none;
        transition: border-color 0.15s ease;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #ef4444 !important;
        outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 12px;
    }
    .select2-dropdown {
        border-color: #cbd5e1;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 4px;
    }
    .select2-search__field {
        border-radius: 0.5rem !important;
        border-color: #cbd5e1 !important;
        padding: 8px 12px !important;
        outline: none;
    }
    .select2-search__field:focus {
        border-color: #ef4444 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ef4444 !important;
        color: white;
    }
    .select2-results__option {
        padding: 8px 16px;
        font-size: 0.95rem;
    }
</style>

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-edit text-red-600"></i> แก้ไขข้อมูลการจอดรถพนักงาน
            </h2>
            <p class="text-slate-500 mt-1 font-medium">ปรับปรุงช่องจอด ทะเบียนรถ หรือสถานะการจอด</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-red-500 to-rose-600"></div>
            
            <form action="{{ route('parking.employees.update', $parking->id) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
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
                    
                    <!-- Section 1: Employee Info -->
                    <div class="col-span-full">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">
                            <i class="fa-solid fa-user-tie text-slate-400 mr-2"></i>ข้อมูลพนักงาน (Employee Info)
                        </h3>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ชื่อพนักงาน <span class="text-red-500">*</span></span></label>
                        <select name="user_id" id="user_select" class="select select-bordered rounded-xl border-slate-200 focus:border-red-500 focus:ring focus:ring-red-200" required>
                            <option value="">-- เลือกพนักงาน --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $parking->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->fullname }} ({{ $user->dept_name ?? 'ไม่มีแผนก' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">เลขทะเบียนรถ <span class="text-red-500">*</span></span></label>
                        <input type="text" name="car_registration" value="{{ old('car_registration', $parking->car_registration) }}" class="input input-bordered rounded-xl border-slate-200 focus:border-red-500 focus:ring focus:ring-red-200 font-bold" placeholder="เช่น กข 1234 กทม" required>
                    </div>

                    <!-- Section 2: Parking Slot & Status -->
                    <div class="col-span-full mt-4">
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4">
                            <i class="fa-solid fa-square-parking text-slate-400 mr-2"></i>ระบุช่องจอดและสถานะ (Parking Slot & Status)
                        </h3>
                    </div>

                    <div class="form-control">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                            <label class="label p-0"><span class="label-text font-bold text-slate-700">เลขที่ช่องจอด (1 - 74) <span class="text-red-500">*</span></span></label>
                            <button type="button" onclick="openMapModal(this)" class="btn btn-xs bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg flex items-center justify-center gap-1 px-3 py-1.5 h-auto font-bold self-start sm:self-auto">
                                <i class="fa-solid fa-map-location-dot"></i> แผนผัง
                            </button>
                        </div>
                        <select name="slot_number" id="slot_number_select" class="select select-bordered rounded-xl border-slate-200 font-bold" required>
                            <option value="">-- เลือกช่องจอด --</option>
                            <optgroup label="ลานจอดรถสำนักงานใหญ่ (Outdoor)">
                                @for($i = 1; $i <= 74; $i++)
                                    @php
                                        $isOccupied = in_array((string)$i, $occupiedSlots) || in_array($i, $occupiedSlots);
                                    @endphp
                                    <option value="{{ $i }}" {{ old('slot_number', $parking->slot ? $parking->slot->slot_number : '') == $i ? 'selected' : ($isOccupied ? 'disabled class=text-slate-400' : '') }}>
                                        ช่องจอดที่ {{ $i }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                    </option>
                                @endfor
                            </optgroup>
                            <optgroup label="พื้นที่จอดรถในอาคาร (Indoor)">
                                @foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] as $bay)
                                    @php $slotsCount = ($bay == 12) ? 1 : (($bay == 8) ? 2 : 3); @endphp
                                    @for($j = 1; $j <= $slotsCount; $j++)
                                        @php 
                                            $slotVal = "B{$bay}_{$j}"; 
                                            $isOccupied = in_array($slotVal, $occupiedSlots);
                                        @endphp
                                        <option value="{{ $slotVal }}" {{ old('slot_number', $parking->slot ? $parking->slot->slot_number : '') == $slotVal ? 'selected' : ($isOccupied ? 'disabled class=text-slate-400' : '') }}>
                                            ช่อง {{ $bay }} คันที่ {{ $j }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                        </option>
                                    @endfor
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">สถานะการจอด <span class="text-red-500">*</span></span></label>
                        <select name="status" class="select select-bordered rounded-xl border-slate-200 focus:border-red-500 focus:ring focus:ring-red-200 font-bold" required>
                            <option value="parking" {{ old('status', $parking->status) == 'parking' ? 'selected' : '' }}>กำลังจอด</option>
                            <option value="left" {{ old('status', $parking->status) == 'left' ? 'selected' : '' }}>ออกแล้ว</option>
                        </select>
                    </div>

                </div>

                <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('parking.employees.index') }}" class="btn btn-ghost rounded-xl text-slate-500 hover:bg-slate-100">ยกเลิก</a>
                    <button type="submit" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 border-none shadow-xl shadow-slate-200">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกการแก้ไข
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
            <!-- Modal Tab Switcher -->
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex gap-2">
                <button type="button" onclick="switchMapTab('hq')" id="tab_hq" class="btn btn-xs sm:btn-sm rounded-xl font-bold bg-amber-500 text-white border-none shadow flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0">
                    ลานจอดรถสำนักงานใหญ่ (HQ)
                </button>
                <button type="button" onclick="switchMapTab('building')" id="tab_building" class="btn btn-xs sm:btn-sm rounded-xl font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0">
                    ในอาคารจอดรถ (Building)
                </button>
            </div>

            <!-- HQ Map Container -->
            <div id="map_hq_container" class="w-full h-[350px] md:h-[540px] bg-white shadow-inner overflow-hidden">
                <iframe src="{{ route('parking.map.full') }}?select_mode=1" class="w-full h-full border-none"></iframe>
            </div>

            <!-- Building Map Container -->
            <div id="map_building_container" class="w-full h-[350px] md:h-[540px] bg-white shadow-inner overflow-hidden hidden">
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
    let currentSelectedSlotNumber = null;
    let targetSelect = null;

    $(document).ready(function() {
        $('#user_select').select2({
            placeholder: "-- เลือกพนักงาน --",
            allowClear: true
        });
    });

    // Listen for selection messages from iframes
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'slot_selected') {
            const slotNumber = event.data.slot;
            selectSlotFromMap(slotNumber);
            confirmSlotSelection();
        }
    });

    function openMapModal(btn) {
        targetSelect = document.getElementById('slot_number_select');
        currentSelectedSlotNumber = null;
        document.getElementById('modal_selected_slot_text').textContent = 'ยังไม่ได้เลือกช่องจอด';
        document.getElementById('confirm_slot_btn').disabled = true;
        
        // Open modal
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

    function switchMapTab(tab) {
        const tabHq = document.getElementById('tab_hq');
        const tabBuilding = document.getElementById('tab_building');
        const hqContainer = document.getElementById('map_hq_container');
        const buildingContainer = document.getElementById('map_building_container');
        
        const activeClass = 'btn btn-xs sm:btn-sm rounded-xl font-bold bg-amber-500 text-white border-none shadow flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0 focus:outline-none';
        const inactiveClass = 'btn btn-xs sm:btn-sm rounded-xl font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0 focus:outline-none';
        
        if (tab === 'hq') {
            tabHq.className = activeClass;
            tabBuilding.className = inactiveClass;
            hqContainer.classList.remove('hidden');
            buildingContainer.classList.add('hidden');
        } else {
            tabBuilding.className = activeClass;
            tabHq.className = inactiveClass;
            buildingContainer.classList.remove('hidden');
            hqContainer.classList.add('hidden');
        }
    }

    function selectSlotFromMap(slotNumber) {
        currentSelectedSlotNumber = slotNumber;
        const infoText = document.getElementById('modal_selected_slot_text');
        infoText.innerHTML = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-bold">ช่องจอด: ${slotNumber}</span>`;
        document.getElementById('confirm_slot_btn').disabled = false;
    }

    function confirmSlotSelection() {
        if (!currentSelectedSlotNumber || !targetSelect) return;
        
        targetSelect.value = currentSelectedSlotNumber;
        targetSelect.dispatchEvent(new Event('change'));
        
        closeMapModal();
    }
</script>
@endsection
