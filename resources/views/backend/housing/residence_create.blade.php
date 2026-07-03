@extends('layouts.housing.apphousing')
@section('title', 'เพิ่มข้อมูลบ้านพักและห้องพัก')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.houselist') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-red-500 transition-colors mb-3 uppercase tracking-wider">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> ย้อนกลับไปหน้าทำเนียบ
        </a>
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-building-circle-add text-red-650 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">ระบบเพิ่มข้อมูลอาคารบ้านพักและห้องพัก</h2>
                    <p class="text-xs text-slate-400 mt-0.5">ระบุรายละเอียดของอาคารและเพิ่มข้อมูลห้องพักทั้งหมดของอาคารดังกล่าวในครั้งเดียว</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('housing.residence.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left column: Residence details --}}
            <div class="space-y-6 lg:col-span-1">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-info-circle text-red-500"></i> ข้อมูลอาคาร/โครงการ
                        </h3>
                    </div>
                    
                    <div class="p-5 space-y-4">
                        <!-- Mode Selector (Segment style) -->
                        <div class="bg-slate-100 p-1 rounded-xl border border-slate-200 grid grid-cols-2 text-center text-xs font-bold mb-2">
                            <label class="cursor-pointer py-2 rounded-lg transition-all select-none bg-white text-slate-800 shadow-sm" id="mode-new-label">
                                <input type="radio" name="mode" value="new" checked class="hidden" onchange="toggleFormMode('new')">
                                <span>สร้างอาคารใหม่</span>
                            </label>
                            <label class="cursor-pointer py-2 rounded-lg transition-all select-none text-slate-500" id="mode-existing-label">
                                <input type="radio" name="mode" value="existing" class="hidden" onchange="toggleFormMode('existing')">
                                <span>เพิ่มในอาคารเดิม</span>
                            </label>
                        </div>

                        <!-- Mode 1: New Residence Fields -->
                        <div id="new-residence-fields" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">ชื่ออาคาร/โครงการใหม่ <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="residence_name" required value="{{ old('name') }}" placeholder="เช่น อาคารสวัสดิการ D, บ้านพักบางใหญ่"
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-semibold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">ที่ตั้ง / ที่อยู่โครงการ</label>
                                <textarea name="address" rows="3" placeholder="ระบุที่อยู่ของโครงการบ้านพัก..."
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm p-3 transition-all">{{ old('address') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">รูปแผนผังรายละเอียด (ถ้ามี)</label>
                                <input type="file" name="blueprint_image" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">รูปปกอาคาร (ถ้ามี)</label>
                                <input type="file" name="cover_image" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">จำนวนชั้นทั้งหมด <span class="text-red-500">*</span></label>
                                <input type="number" name="total_floors" id="total_floors" required min="1" value="{{ old('total_floors', 1) }}"
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-mono font-bold font-semibold">
                            </div>
                        </div>

                        <!-- Mode 2: Existing Residence Fields (Hidden by default) -->
                        <div id="existing-residence-fields" class="space-y-4 hidden">
                            <div>
                                <label class="block text-xs font-bold text-slate-650 mb-1.5">เลือกอาคาร/โครงการที่มีอยู่แล้ว <span class="text-red-500">*</span></label>
                                <select name="residence_id" id="residence_select" onchange="onResidenceSelectChange()"
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-semibold">
                                    <option value="" data-floors="1">-- เลือกอาคาร --</option>
                                    @foreach($residences as $res)
                                        <option value="{{ $res->residence_id }}" data-floors="{{ $res->total_floors }}" {{ old('residence_id') == $res->residence_id ? 'selected' : '' }}>
                                            {{ $res->name }} (ชั้นรวม: {{ $res->total_floors }} ชั้น, ปัจจุบันมี: {{ $res->rooms->count() }} ห้อง)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Store existing floors as a read-only parameter --}}
                            <input type="hidden" id="existing_total_floors" value="1">
                        </div>

                        <!-- Shared Room Count Input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-650 mb-1.5">จำนวนห้องพักที่จะเพิ่ม <span class="text-red-500">*</span></label>
                            <input type="number" name="total_rooms" id="total_rooms" required min="1" value="{{ old('total_rooms', 1) }}"
                                class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-mono font-bold">
                        </div>

                        <!-- Floor-by-floor numbering direction -->
                        <div class="border-t border-slate-200 pt-3">
                            <label class="block text-xs font-bold text-slate-600 mb-2">ทิศทางการเรียงห้องแต่ละชั้น</label>
                            <div id="floor-directions-list" class="space-y-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                <!-- Floor direction dropdowns will be loaded here dynamically -->
                            </div>
                        </div>
                        
                        <div class="pt-2">
                            <button type="button" onclick="generateRoomRows()" 
                                class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 font-bold rounded-lg text-xs transition-all flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-arrows-rotate"></i> สร้างรายการกรอกห้องพัก
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column: Dynamic Rooms setup --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-list text-red-500"></i> ตั้งค่ารายละเอียดห้องพักรายห้อง
                        </h3>
                        <span id="room-counter-badge" class="text-[9px] font-black bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full border border-slate-300">
                            1 ห้อง
                        </span>
                    </div>
                    
                    <div class="p-6 flex-1 overflow-x-auto min-h-[350px]">
                        <table class="w-full border-collapse text-left" id="rooms-table">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="pb-3 text-xs font-black text-slate-550 uppercase tracking-wider w-10 text-center">#</th>
                                    <th class="pb-3 px-2 text-xs font-black text-slate-550 uppercase tracking-wider w-28">เลข/ชื่อห้อง *</th>
                                    <th class="pb-3 px-2 text-xs font-black text-slate-550 uppercase tracking-wider w-20">ชั้น *</th>
                                    <th class="pb-3 px-2 text-xs font-black text-slate-550 uppercase tracking-wider w-20">ความจุ *</th>
                                    <th class="pb-3 px-2 text-xs font-black text-slate-550 uppercase tracking-wider w-28">ราคา (บาท) *</th>
                                    <th class="pb-3 px-2 text-xs font-black text-slate-550 uppercase tracking-wider w-40">รูปห้อง (ถ้ามี)</th>
                                    <th class="pb-3 pl-2 text-xs font-black text-slate-550 uppercase tracking-wider">หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody id="rooms-tbody" class="divide-y divide-slate-100">
                                {{-- Rows generated dynamically via JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('housing.houselist') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-lg transition-colors border border-slate-200 bg-white">ยกเลิก</a>
                        <button type="submit" class="px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm transition-all flex items-center gap-1">
                            <i class="fa-solid fa-floppy-disk"></i> บันทึกและสร้างทั้งหมด
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let currentMode = 'new';

    function toggleFormMode(mode) {
        currentMode = mode;
        const newFields = document.getElementById('new-residence-fields');
        const existingFields = document.getElementById('existing-residence-fields');
        const newLabel = document.getElementById('mode-new-label');
        const existingLabel = document.getElementById('mode-existing-label');
        
        const nameInput = document.getElementById('residence_name');
        const floorsInput = document.getElementById('total_floors');
        const selectEl = document.getElementById('residence_select');

        if (mode === 'new') {
            newFields.classList.remove('hidden');
            existingFields.classList.add('hidden');
            
            newLabel.className = "cursor-pointer py-2 rounded-lg transition-all select-none bg-white text-slate-800 shadow-sm";
            existingLabel.className = "cursor-pointer py-2 rounded-lg transition-all select-none text-slate-500";
            
            nameInput.required = true;
            floorsInput.required = true;
            selectEl.required = false;
        } else {
            newFields.classList.add('hidden');
            existingFields.classList.remove('hidden');
            
            newLabel.className = "cursor-pointer py-2 rounded-lg transition-all select-none text-slate-500";
            existingLabel.className = "cursor-pointer py-2 rounded-lg transition-all select-none bg-white text-slate-800 shadow-sm";
            
            nameInput.required = false;
            floorsInput.required = false;
            selectEl.required = true;
        }
        
        updateFloorDirectionsUI();
    }

    function onResidenceSelectChange() {
        const select = document.getElementById('residence_select');
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption) {
            const floors = selectedOption.dataset.floors || 1;
            document.getElementById('existing_total_floors').value = floors;
            updateFloorDirectionsUI();
        }
    }

    function updateFloorDirectionsUI() {
        const container = document.getElementById('floor-directions-list');
        let totalFloors = 1;
        if (currentMode === 'new') {
            totalFloors = parseInt(document.getElementById('total_floors').value) || 1;
        } else {
            totalFloors = parseInt(document.getElementById('existing_total_floors').value) || 1;
        }

        // Store current selections to preserve them if floors count changes
        const oldSelections = {};
        document.querySelectorAll('.floor-direction-select').forEach(sel => {
            oldSelections[sel.dataset.floor] = sel.value;
        });

        container.innerHTML = '';

        for (let f = 1; f <= totalFloors; f++) {
            const savedVal = oldSelections[f] || 'ltr';
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between text-xs font-bold text-slate-700 py-1.5';
            item.innerHTML = `
                <span>ชั้นที่ ${f}:</span>
                <select class="floor-direction-select rounded-lg border border-slate-300 px-2 py-1 text-xs focus:ring-1 focus:ring-red-500 focus:border-red-500 outline-none font-bold" data-floor="${f}" onchange="generateRoomRows()">
                    <option value="ltr" ${savedVal === 'ltr' ? 'selected' : ''}>ซ้าย ➔ ขวา (101, 102)</option>
                    <option value="rtl" ${savedVal === 'rtl' ? 'selected' : ''}>ขวา ➔ ซ้าย (106, 105)</option>
                </select>
            `;
            container.appendChild(item);
        }
        
        generateRoomRows();
    }

    function generateRoomRows() {
        const totalRoomsInput = document.getElementById('total_rooms');
        const count = parseInt(totalRoomsInput.value) || 1;
        const tbody = document.getElementById('rooms-tbody');
        
        let totalFloors = 1;
        if (currentMode === 'new') {
            totalFloors = parseInt(document.getElementById('total_floors').value) || 1;
        } else {
            totalFloors = parseInt(document.getElementById('existing_total_floors').value) || 1;
        }
        
        tbody.innerHTML = '';
        document.getElementById('room-counter-badge').innerText = `${count} ห้อง`;

        const roomsPerFloor = Math.ceil(count / totalFloors);

        for (let i = 0; i < count; i++) {
            // Predict floor level
            let predictedFloor = 1;
            if (totalFloors > 1) {
                predictedFloor = Math.min(totalFloors, Math.floor(i / roomsPerFloor) + 1);
            }
            
            // Build default room number based on index and floor sort direction
            let roomIndexOnFloor = (i % roomsPerFloor) + 1;
            
            // Get direction value for this specific floor
            const floorSel = document.querySelector(`.floor-direction-select[data-floor="${predictedFloor}"]`);
            const direction = floorSel ? floorSel.value : 'ltr';
            
            let predictedRoomNumIndex;
            if (direction === 'ltr') {
                predictedRoomNumIndex = roomIndexOnFloor;
            } else {
                let roomsOnThisFloor = roomsPerFloor;
                if (predictedFloor === totalFloors && count % roomsPerFloor !== 0) {
                    roomsOnThisFloor = count % roomsPerFloor;
                }
                predictedRoomNumIndex = roomsOnThisFloor - roomIndexOnFloor + 1;
            }
            
            let predictedRoomNum = `${predictedFloor}${String(predictedRoomNumIndex).padStart(2, '0')}`;

            const row = document.createElement('tr');
            row.className = 'group hover:bg-slate-50/50 transition-colors';
            row.innerHTML = `
                <td class="py-3 text-xs font-bold text-slate-400 text-center font-mono">${i + 1}</td>
                <td class="py-3 px-2">
                    <input type="text" name="rooms[${i}][room_number]" required value="" placeholder="เช่น 101"
                        class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-bold text-slate-700 transition-all">
                </td>
                <td class="py-3 px-2">
                    <input type="number" name="rooms[${i}][floor]" required min="1" max="${totalFloors}" value="${predictedFloor}"
                        class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                </td>
                <td class="py-3 px-2">
                    <input type="number" name="rooms[${i}][capacity]" required min="1" value="4"
                        class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                </td>
                <td class="py-3 px-2">
                    <input type="number" name="rooms[${i}][price]" required min="0" value="0" step="any"
                        class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-mono font-bold text-slate-700 transition-all">
                </td>
                <td class="py-3 px-2">
                    <input type="file" name="rooms[${i}][image]" accept="image/*"
                        class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                </td>
                <td class="py-3 pl-2">
                    <input type="text" name="rooms[${i}][note]" placeholder="หมายเหตุห้อง..."
                        class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 text-slate-650 transition-all">
                </td>
            `;
            tbody.appendChild(row);
        }

        applyRoomNumbering();
    }

    function applyRoomNumbering() {
        const tbody = document.getElementById('rooms-tbody');
        const rows = tbody.querySelectorAll('tr');
        
        let totalFloors = 1;
        if (currentMode === 'new') {
            totalFloors = parseInt(document.getElementById('total_floors').value) || 1;
        } else {
            totalFloors = parseInt(document.getElementById('existing_total_floors').value) || 1;
        }

        const rowsByFloor = {};
        for (let f = 1; f <= totalFloors; f++) {
            rowsByFloor[f] = [];
        }

        rows.forEach(row => {
            const floorInput = row.querySelector('input[name$="[floor]"]');
            if (floorInput) {
                const f = parseInt(floorInput.value);
                if (rowsByFloor[f]) {
                    rowsByFloor[f].push(row);
                }
            }
        });

        for (let f = 1; f <= totalFloors; f++) {
            const floorSel = document.querySelector(`.floor-direction-select[data-floor="${f}"]`);
            const direction = floorSel ? floorSel.value : 'ltr';
            const floorRows = rowsByFloor[f];
            const numRooms = floorRows.length;

            floorRows.forEach((row, index) => {
                const roomNumInput = row.querySelector('input[name$="[room_number]"]');
                if (roomNumInput) {
                    let suffix;
                    if (direction === 'ltr') {
                        suffix = index + 1;
                    } else {
                        suffix = numRooms - index;
                    }
                    const predictedNum = `${f}${String(suffix).padStart(2, '0')}`;
                    roomNumInput.value = predictedNum;
                }
            });
        }
    }

    // Auto generate rows on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initial setup
        const select = document.getElementById('residence_select');
        if (select.value) {
            onResidenceSelectChange();
        } else {
            updateFloorDirectionsUI();
        }
        
        // Regenerate dynamically when input counts change
        document.getElementById('total_rooms').addEventListener('input', generateRoomRows);
        document.getElementById('total_floors').addEventListener('input', function() {
            updateFloorDirectionsUI();
        });
    });
</script>
@endsection
