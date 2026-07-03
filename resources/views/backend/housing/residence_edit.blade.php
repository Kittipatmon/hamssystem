@extends('layouts.housing.apphousing')
@section('title', 'แก้ไขข้อมูลบ้านพักและห้องพัก')

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
                    <i class="fa-solid fa-building-circle-gear text-red-650 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">ระบบแก้ไขข้อมูลอาคารบ้านพักและห้องพัก</h2>
                    <p class="text-xs text-slate-400 mt-0.5">แก้ไขรายละเอียดอาคารและห้องพัก หรือเพิ่มห้องพักใหม่ในอาคารดังกล่าว</p>
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

    <form action="{{ route('housing.residence.update_all', $residence->residence_id) }}" method="POST" enctype="multipart/form-data">
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
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-655 mb-1.5">ชื่ออาคาร/โครงการ <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="residence_name" required value="{{ old('name', $residence->name) }}" placeholder="เช่น อาคารสวัสดิการ D, บ้านพักบางใหญ่"
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-semibold">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-655 mb-1.5">ที่ตั้ง / ที่อยู่โครงการ</label>
                                <textarea name="address" rows="3" placeholder="ระบุที่อยู่ของโครงการบ้านพัก..."
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm p-3 transition-all">{{ old('address', $residence->address) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-655 mb-1.5">รูปแผนผังรายละเอียด (ถ้ามี)</label>
                                @if($residence->blueprint_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($residence->blueprint_image) }}" alt="แผนผังรายละเอียด" class="w-32 h-20 object-cover rounded-lg border border-slate-300 shadow-sm mb-1">
                                        <a href="{{ asset($residence->blueprint_image) }}" target="_blank" class="text-[10px] font-bold text-sky-600 hover:underline flex items-center gap-0.5">
                                            <i class="fa-solid fa-up-right-from-square"></i> ดูรูปขนาดเต็ม
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="blueprint_image" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-655 mb-1.5">รูปปกอาคาร (ถ้ามี)</label>
                                @if($residence->cover_image)
                                    <div class="mb-2">
                                        <img src="{{ asset($residence->cover_image) }}" alt="รูปปกอาคาร" class="w-32 h-20 object-cover rounded-lg border border-slate-300 shadow-sm mb-1">
                                        <a href="{{ asset($residence->cover_image) }}" target="_blank" class="text-[10px] font-bold text-sky-600 hover:underline flex items-center gap-0.5">
                                            <i class="fa-solid fa-up-right-from-square"></i> ดูรูปขนาดเต็ม
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="cover_image" accept="image/*"
                                    class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border file:border-slate-300 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-655 mb-1.5">จำนวนชั้นทั้งหมด <span class="text-red-500">*</span></label>
                                <input type="number" name="total_floors" id="total_floors" required min="1" value="{{ old('total_floors', $residence->total_floors) }}"
                                    class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-mono font-semibold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-655 mb-1.5">จำนวนห้องพักรวม <span class="text-red-500">*</span></label>
                            <input type="number" name="total_rooms" id="total_rooms" required min="{{ $residence->rooms->count() }}" value="{{ old('total_rooms', $residence->total_rooms) }}"
                                class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all font-mono font-bold">
                            <span class="text-[10px] text-slate-400 mt-1 block">ปัจจุบันมีห้องพักในระบบแล้ว: <b>{{ $residence->rooms->count() }} ห้อง</b> (สามารถแก้ไขหรือเพิ่มจำนวนได้)</span>
                        </div>

                        <!-- Floor-by-floor numbering direction -->
                        <div class="border-t border-slate-200 pt-3">
                            <label class="block text-xs font-bold text-slate-600 mb-2">ทิศทางการเรียงห้องแต่ละชั้น (สำหรับห้องใหม่)</label>
                            <div id="floor-directions-list" class="space-y-2 bg-slate-50 p-3 rounded-lg border border-slate-200">
                                <!-- Floor direction dropdowns will be loaded here dynamically -->
                            </div>
                        </div>
                        
                        <div class="pt-2">
                            <button type="button" onclick="syncRoomRows()" 
                                class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 border border-slate-300 text-slate-700 font-bold rounded-lg text-xs transition-all flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-arrows-rotate"></i> อัปเดตรายการกรอกห้องพัก
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
                            {{ $residence->total_rooms }} ห้อง
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
                                {{-- Existing rooms rendered here first --}}
                                @foreach($residence->rooms as $index => $room)
                                <tr class="group hover:bg-slate-50/50 transition-colors" data-existing="true">
                                    <td class="py-3 text-xs font-bold text-slate-400 text-center font-mono">
                                        {{ $index + 1 }}
                                        <input type="hidden" name="rooms[{{ $index }}][residence_room_id]" value="{{ $room->residence_room_id }}">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="text" name="rooms[{{ $index }}][room_number]" required value="{{ old('rooms.'.$index.'.room_number', $room->room_number) }}" placeholder="เช่น 101"
                                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-bold text-slate-700 transition-all">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number" name="rooms[{{ $index }}][floor]" required min="1" max="{{ $residence->total_floors }}" value="{{ old('rooms.'.$index.'.floor', $room->floor) }}"
                                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number" name="rooms[{{ $index }}][capacity]" required min="1" value="{{ old('rooms.'.$index.'.capacity', $room->capacity) }}"
                                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="number" name="rooms[{{ $index }}][price]" required min="0" value="{{ old('rooms.'.$index.'.price', $room->price) }}" step="any"
                                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-mono font-bold text-slate-700 transition-all">
                                    </td>
                                    <td class="py-3 px-2">
                                        @if($room->image)
                                            <div class="mb-1 flex items-center gap-1.5">
                                                <a href="{{ asset($room->image) }}" target="_blank" class="text-[9px] font-bold text-sky-600 hover:underline flex items-center gap-0.5">
                                                    <i class="fa-solid fa-image"></i> รูปปัจจุบัน
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" name="rooms[{{ $index }}][image]" accept="image/*"
                                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                                    </td>
                                    <td class="py-3 pl-2">
                                        <input type="text" name="rooms[{{ $index }}][note]" placeholder="หมายเหตุห้อง..." value="{{ old('rooms.'.$index.'.note', $room->note) }}"
                                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 text-slate-650 transition-all">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('housing.houselist') }}" class="px-5 py-2.5 text-xs font-bold text-slate-550 hover:bg-slate-100 rounded-lg transition-colors border border-slate-200 bg-white">ยกเลิก</a>
                        <button type="submit" class="px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm transition-all flex items-center gap-1">
                            <i class="fa-solid fa-floppy-disk"></i> บันทึกข้อมูลทั้งหมด
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    const initialRoomCount = {{ $residence->rooms->count() }};

    function updateFloorDirectionsUI() {
        const container = document.getElementById('floor-directions-list');
        const totalFloors = parseInt(document.getElementById('total_floors').value) || 1;

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
                <select class="floor-direction-select rounded-lg border border-slate-300 px-2 py-1 text-xs focus:ring-1 focus:ring-red-500 focus:border-red-500 outline-none font-bold" data-floor="${f}" onchange="syncRoomRows()">
                    <option value="ltr" ${savedVal === 'ltr' ? 'selected' : ''}>ซ้าย ➔ ขวา (101, 102)</option>
                    <option value="rtl" ${savedVal === 'rtl' ? 'selected' : ''}>ขวา ➔ ซ้าย (106, 105)</option>
                </select>
            `;
            container.appendChild(item);
        }
        
        syncRoomRows();
    }

    let isInitialLoad = true;

    function syncRoomRows() {
        const totalRoomsInput = document.getElementById('total_rooms');
        let count = parseInt(totalRoomsInput.value) || initialRoomCount;
        
        // Prevent setting room count lower than initial
        if (count < initialRoomCount) {
            count = initialRoomCount;
            totalRoomsInput.value = initialRoomCount;
        }

        const tbody = document.getElementById('rooms-tbody');
        document.getElementById('room-counter-badge').innerText = `${count} ห้อง`;

        const totalFloors = parseInt(document.getElementById('total_floors').value) || 1;

        // Remove all dynamically added rows (non-existing rows)
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            if (!row.getAttribute('data-existing')) {
                row.remove();
            }
        });

        const roomsToGenerate = count - initialRoomCount;

        if (roomsToGenerate > 0) {
            // Distribute new rooms across floors
            const newRoomsPerFloor = Math.ceil(roomsToGenerate / totalFloors);

            for (let i = 0; i < roomsToGenerate; i++) {
                // Predict floor level for this new room
                let predictedFloor = 1;
                if (totalFloors > 1) {
                    predictedFloor = Math.min(totalFloors, Math.floor(i / newRoomsPerFloor) + 1);
                }

                const globalIndex = initialRoomCount + i;

                const row = document.createElement('tr');
                row.className = 'group hover:bg-slate-50/50 transition-colors';
                row.innerHTML = `
                    <td class="py-3 text-xs font-bold text-slate-400 text-center font-mono">${globalIndex + 1}</td>
                    <td class="py-3 px-2">
                        <input type="text" name="rooms[${globalIndex}][room_number]" required value="" placeholder="เช่น 101"
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-bold text-slate-700 transition-all">
                    </td>
                    <td class="py-3 px-2">
                        <input type="number" name="rooms[${globalIndex}][floor]" required min="1" max="${totalFloors}" value="${predictedFloor}"
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                    </td>
                    <td class="py-3 px-2">
                        <input type="number" name="rooms[${globalIndex}][capacity]" required min="1" value="4"
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2 font-mono font-bold text-slate-700 text-center transition-all">
                    </td>
                    <td class="py-3 px-2">
                        <input type="number" name="rooms[${globalIndex}][price]" required min="0" value="0" step="any"
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 font-mono font-bold text-slate-700 transition-all">
                    </td>
                    <td class="py-3 px-2">
                        <input type="file" name="rooms[${globalIndex}][image]" accept="image/*"
                            class="w-full text-[10px] text-slate-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer">
                    </td>
                    <td class="py-3 pl-2">
                        <input type="text" name="rooms[${globalIndex}][note]" placeholder="หมายเหตุห้อง..."
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs h-9 px-2.5 text-slate-650 transition-all">
                    </td>
                `;
                tbody.appendChild(row);
            }
        }

        applyRoomNumbering();
    }

    function applyRoomNumbering() {
        const tbody = document.getElementById('rooms-tbody');
        const rows = tbody.querySelectorAll('tr');
        const totalFloors = parseInt(document.getElementById('total_floors').value) || 1;

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
                // If it is the initial load, do not modify the existing rooms in the database
                if (isInitialLoad && row.getAttribute('data-existing')) {
                    return;
                }

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

    document.addEventListener('DOMContentLoaded', function() {
        updateFloorDirectionsUI();
        isInitialLoad = false; // Turn off initial load protection after DOMContentLoaded setup

        // Listen for inputs
        document.getElementById('total_rooms').addEventListener('input', syncRoomRows);
        document.getElementById('total_floors').addEventListener('input', updateFloorDirectionsUI);
    });
</script>
@endsection
