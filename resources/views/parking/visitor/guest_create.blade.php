<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ลงทะเบียนจองที่จอดรถแขก (Visitor Parking)</title>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400;600&family=Prompt:wght@200;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Prompt', 'Kanit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --avail: #3fa87c;
            --occupied: #e0703f;
            --reserved: #a8b6c4;
            --blue: #2f8fd4;
            --green: #6fbf6a;
            --red: #e03b3b;
            --yellow: #f4d03f;
            --lightblue: #bfe3f7;
            --ink: #1c3550;
            --ink-dim: #5c7590;
            --gray: #c9ced3;
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
            font-family: monospace;
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

        /* Building Map */
        .board-building {
            position: relative;
            width: 100%;
            min-width: 1100px;
            aspect-ratio: 1280/660;
            background: #ffffff;
            overflow: hidden;
        }
        .pct {
            position: absolute;
        }
        .bay {
            background: #ffffff;
            border: 1px solid #9aa2aa;
        }
        .triple-bay {
            display: flex;
            align-items: flex-end;
            gap: 2%;
            height: 100%;
            padding: 20% 4% 5% 4%;
        }
        .car-slot {
            flex: 1;
            height: 100%;
            border: 2px solid #6fbf6a;
            border-radius: 5px;
            background: rgba(111,191,106,.10);
            cursor: pointer;
            transition: background .15s ease, border-color .15s ease;
        }
        .car-slot:hover {
            filter: brightness(1.05);
        }
        .car-slot.occupied {
            border-color: #e03b3b;
            background: rgba(224,59,59,.16);
            cursor: not-allowed;
        }
        .badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: #2f8fd4;
            color: #fff;
            font-family: monospace;
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
        .room {
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
        .room.gray { background: #c9ced3; }
        .room.yellow { background: #f4d03f; }
        .room.green { background: #6fbf6a; color: #fff; }
        .room.blue { background: #2f8fd4; color: #fff; }
        .room.lightblue { background: #bfe3f7; }
        .room.exec-label {
            align-items: flex-end;
            padding-bottom: 6px;
            font-weight: 600;
            color: #1c3550;
            background: #bfe3f7;
        }
        .vtext {
            writing-mode: vertical-rl;
            text-orientation: mixed;
        }
        .title-tag {
            background: #2f8fd4;
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            padding: 6px 16px;
            border-radius: 0 0 0 8px;
        }
        .stripe-red { background: #e03b3b; }
        .stripe-green { background: #4caf50; }
        .stair-hatch {
            background-image: repeating-linear-gradient(0deg, #d7dbdf 0 4px, #eceff1 4px 8px);
            border: 1px solid #1c3550;
        }
        .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #e03b3b;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800">

    <!-- Header Brand Section -->
    <header class="bg-white border-b border-red-100 py-4 px-6 shadow-sm sticky top-0 z-[50]">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-[#b81515] to-[#901010] text-white font-bold text-xl shadow-md">
                    K
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-lg font-black tracking-tight text-[#b81515] leading-none">Kumwell</span>
                    <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase mt-0.5">Visitor Parking Registration</span>
                </div>
            </div>
            <span class="text-xs font-semibold px-3 py-1 bg-red-50 text-[#b81515] rounded-full border border-red-100">
                <i class="fa-solid fa-qrcode mr-1"></i> จองด้วยตัวเองผ่านมือถือ
            </span>
        </div>
    </header>

    <div class="max-w-3xl mx-auto px-4 py-12">
        <!-- Title card -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">ลงทะเบียนจองที่จอดรถแขก</h1>
            <p class="text-slate-500 mt-2 font-medium">กรุณากรอกข้อมูลรถยนต์และรายละเอียดเพื่อจองที่จอดรถล่วงหน้า</p>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-red-500 via-amber-500 to-emerald-500"></div>
            
            <form action="{{ route('parking.visitors.guestStore') }}" method="POST" class="p-8 space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-1"></i>
                            <div>
                                <h4 class="font-bold mb-1">เกิดข้อผิดพลาดในการลงทะเบียน</h4>
                                <ul class="list-disc pl-5 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Section 1: Guest Info -->
                <div>
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-user-tag text-[#b81515]"></i> ข้อมูลส่วนตัวของคุณ (Visitor Profile)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                            <input type="text" name="guest_name" value="{{ old('guest_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" placeholder="ระบุชื่อแขก" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" inputmode="numeric" pattern="[0-9]*" maxlength="10" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" placeholder="0XXXXXXXXX" required oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1">บริษัท / หน่วยงานของคุณ</label>
                            <input type="text" name="company" value="{{ old('company') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" placeholder="ระบุชื่อบริษัท (ถ้ามี)">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Contact Person -->
                <div class="pt-2">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-address-book text-[#b81515]"></i> บุคคลภายในสำนักงานที่คุณต้องการติดต่อ <span class="text-red-500">*</span>
                    </h3>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">ชื่อผู้ที่จะมาติดต่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" placeholder="ระบุชื่อพนักงานที่ต้องการติดต่อ" required>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-bold text-slate-700 mb-1">รายละเอียดการติดต่อ (เรื่องที่มาติดต่อ)</label>
                        <textarea name="contact_details" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" placeholder="ระบุรายละเอียด เช่น มาประชุม, ส่งเอกสาร, ซ่อมบำรุง, ฯลฯ">{{ old('contact_details') }}</textarea>
                    </div>
                </div>

                <!-- Section 3: Vehicle & Slot -->
                <div class="pt-2">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-car text-[#b81515]"></i> ข้อมูลรถและช่องจอดที่จอง
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">ทะเบียนรถ <span class="text-red-500">*</span></label>
                            <input type="text" name="car_registration" value="{{ old('car_registration') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-bold text-slate-800 placeholder:font-normal uppercase" placeholder="เช่น 1กข 1234 กทม." required>
                        </div>
                        <div>
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                                <label class="text-sm font-bold text-slate-700">เลือกช่องจอดที่ต้องการ</label>
                                <button type="button" onclick="openMapModal()" class="text-xs bg-amber-500 hover:bg-amber-600 text-white font-bold px-3 py-1.5 rounded flex items-center justify-center gap-1 transition-all self-start sm:self-auto h-auto">
                                    <i class="fa-solid fa-map-location-dot"></i> เลือกจากแผนผัง
                                </button>
                            </div>
                            <select id="slot_id_select" name="slot_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium">
                                <option value="">-- ระบบเลือกช่องอัตโนมัติ (Auto) --</option>
                                @foreach($availableSlots as $slot)
                                    <option value="{{ $slot->id }}" data-slot-number="{{ $slot->slot_number }}" {{ (old('slot_id') == $slot->id || (isset($selectedSlotId) && $selectedSlotId == $slot->id)) ? 'selected' : '' }}>
                                        ช่อง {{ $slot->slot_number }} ({{ $slot->zone->zone ?? 'ลานจอดรถ' }})
                                    </option>
                                @endforeach
                            </select>
                            {{-- Hidden: fallback เมื่อเลือกจากแผนผังแล้วไม่มีใน dropdown --}}
                            <input type="hidden" id="slot_number_manual" name="slot_number_manual" value="">
                            <p id="selected_map_slot_text" class="text-xs text-emerald-600 mt-1.5 font-bold hidden">
                                <i class="fa-solid fa-circle-check"></i> แผนผังระบุช่องจอดแล้ว: ช่อง <span id="selected_slot_name"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Schedule -->
                <div class="pt-2">
                    <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-[#b81515]"></i> เวลาที่เข้าจอด
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">วันที่และเวลาที่จะเข้า <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="checkin_datetime" value="{{ old('checkin_datetime', \Carbon\Carbon::now('Asia/Bangkok')->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">ระยะเวลาจอดโดยประมาณ (ชั่วโมง)</label>
                            <input type="number" name="duration_hours" value="{{ old('duration_hours', 2) }}" min="1" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all font-medium">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-[#b81515] hover:bg-[#901010] text-white font-bold rounded-2xl shadow-lg transition-all text-center">
                        <i class="fa-solid fa-square-parking mr-2"></i> ยืนยันการลงทะเบียนจอง
                    </button>
                </div>
            </form>
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
                <button type="button" onclick="closeMapModal()" class="text-slate-400 hover:text-slate-700 transition-colors">
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
                <div class="text-sm font-bold text-slate-700 text-center sm:text-left">
                    สถานะการเลือก: <span id="modal_selected_slot_text" class="text-amber-600">ยังไม่ได้เลือกช่องจอด</span>
                </div>
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 justify-end">
                    <button type="button" id="confirm_slot_btn" onclick="confirmSlotSelection()" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold transition-all text-xs w-full sm:w-auto" disabled>
                        ยืนยันเลือกช่องจอดนี้
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
    const availableSelectOptions = Array.from(document.querySelectorAll('#slot_id_select option')).map(o => o.getAttribute('data-slot-number')).filter(Boolean);
    let currentSelectedSlotNumber = null;

    // Listen for selection messages from iframes
    window.addEventListener('message', function(event) {
        if (event.data && event.data.type === 'slot_selected') {
            const slotNumber = event.data.slot;
            // Only highlight the selection — user must click confirm button manually
            selectSlotFromMap(slotNumber, true);
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
        infoText.innerHTML = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-bold text-xs">ช่องจอด: ${slotNumber}</span>`;
        document.getElementById('confirm_slot_btn').disabled = false;
    }

    function confirmSlotSelection() {
        if (!currentSelectedSlotNumber) return;

        const select = document.getElementById('slot_id_select');
        const option = Array.from(select.options).find(o => o.getAttribute('data-slot-number') === currentSelectedSlotNumber);

        if (option) {
            // Found in dropdown — use the slot_id
            select.value = option.value;
            document.getElementById('slot_number_manual').value = '';
        } else {
            // Not in dropdown — send slot number directly via hidden input
            select.value = '';
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

</body>
</html>
