@extends('layouts.housing.apphousing')
@section('title', isset($item) ? 'แก้ไขขออนุญาตนำญาติเข้าพัก' : 'ขออนุญาตนำญาติเข้าพัก (QF-HAMS-05)')

@section('content')
<div class="max-w-4xl mx-auto py-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-red-500 transition-colors mb-3">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-people-arrows text-slate-650 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">{{ isset($item) ? 'แก้ไขแบบฟอร์มขออนุญาตนำบุคคลภายนอก/ญาติเข้าพักอาศัย' : 'แบบฟอร์มขออนุญาตนำบุคคลภายนอก/ญาติเข้าพักอาศัย' }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">QF-HAMS-05 Rev.00 • กรุณากรอกข้อมูลให้ครบทุกช่องที่มีเครื่องหมาย <span class="text-red-500">*</span></p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-800 px-4 py-3 rounded-lg text-xs mb-6 border border-red-200 shadow-sm">
            <p class="font-bold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>กรุณาตรวจสอบข้อมูล:</p>
            <ul class="list-disc pl-5 text-[11px] space-y-0.5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('housing.guest.update', $item->resident_guest_id) : route('housing.guest.store') }}" method="POST">
        @csrf
        @if(isset($item)) @method('PUT') @endif
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">

            {{-- ข้อมูลผู้ขออนุญาต --}}
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2"><i class="fa-solid fa-user text-red-500"></i> ข้อมูลผู้ขออนุญาต</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">คำนำหน้า</label>
                        <select name="prefix" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                            <option value="">-- เลือก --</option>
                            @foreach(['นาย', 'นาง', 'นางสาว'] as $t)
                                <option value="{{ $t }}" {{ old('prefix', $item->prefix ?? ($userRequest->title ?? ($user->prefix ?? ''))) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ชื่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $item->first_name ?? ($userRequest->first_name ?? ($user->first_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $item->last_name ?? ($userRequest->last_name ?? ($user->last_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ตำแหน่ง</label>
                        <input type="text" name="position" value="{{ old('position', $item->position ?? ($userRequest->position ?? ($user->position ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">แผนก</label>
                        <input type="text" name="department" value="{{ old('department', $item->department ?? ($userRequest->department ?? ($user->department->department_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ฝ่าย</label>
                        <input type="text" name="section" value="{{ old('section', $item->section ?? ($userRequest->section ?? ($user->division->division_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ความสัมพันธ์กับผู้เข้าพัก</label>
                        <input type="text" name="relationship" value="{{ old('relationship', $item->relationship ?? '') }}" placeholder="เช่น บิดา, มารดา" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
            </div>

            {{-- ข้อมูลบ้านพักและช่วงเวลา --}}
            <div class="px-5 py-3 bg-slate-50 border-t border-b border-slate-200">
                <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2"><i class="fa-solid fa-calendar-days text-red-500"></i> ข้อมูลบ้านพักและช่วงเวลา</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ประเภทบ้านพัก <span class="text-red-500">*</span></label>
                        <select name="residence_type" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                            <option value="">-- เลือกบ้านพัก --</option>
                            @foreach($residences as $r)
                                <option value="{{ $r->name }}" {{ old('residence_type', $item->residence_type ?? ($userStay->room->residence->name ?? '')) == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">เลขที่ห้อง</label>
                        <input type="text" name="room_number" value="{{ old('room_number', $item->room_number ?? ($userStay->room->room_number ?? '')) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">วันที่เริ่ม <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date', $item->start_date ?? '') }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">เวลาเริ่ม <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" value="{{ old('start_time', $item->start_time ?? '08:00') }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">วันที่สิ้นสุด <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date', $item->end_date ?? '') }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">เวลาสิ้นสุด <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" value="{{ old('end_time', $item->end_time ?? '17:00') }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                </div>
            </div>

            {{-- รายชื่อผู้เข้าพัก --}}
            <div class="px-5 py-3 bg-slate-50 border-t border-b border-slate-200 flex items-center justify-between">
                <h3 class="font-bold text-slate-700 text-sm flex items-center gap-2"><i class="fa-solid fa-users text-red-500"></i> รายชื่อผู้เข้าพัก</h3>
                <button type="button" id="add-guest-btn" class="text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                    <i class="fa-solid fa-plus mr-1"></i> เพิ่มรายชื่อ
                </button>
            </div>
            <div class="p-5" id="guest-members-container">
                @if(isset($item) && $item->members->count() > 0)
                    @foreach($item->members as $index => $member)
                        <div class="guest-row bg-slate-50 rounded-lg p-4 transition-all border border-slate-200 scale-100 opacity-100 mb-3">
                            <div class="flex items-center justify-between mb-3 border-b border-slate-200 pb-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center row-number">{{ $index + 1 }}</span>
                                    <span class="text-xs font-bold text-slate-500">ข้อมูลผู้เข้าพัก</span>
                                </div>
                                <button type="button" class="remove-guest text-red-500 hover:text-red-700 transition-colors">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                                <div class="md:col-span-5">
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                                    <input type="text" name="guest_name[]" value="{{ $member->full_name }}" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">อายุ <span class="text-red-500">*</span></label>
                                    <input type="number" name="guest_age[]" value="{{ $member->age }}" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">ความสัมพันธ์ <span class="text-red-500">*</span></label>
                                    <input type="text" name="guest_relation[]" value="{{ $member->relation }}" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                                </div>
                                <div class="md:col-span-3">
                                    <label class="block text-[10px] font-bold text-slate-500 mb-1">เบอร์โทรศัพท์</label>
                                    <input type="text" name="guest_phone[]" value="{{ $member->phone }}" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- Submit --}}
            <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <a href="{{ route('housing.welcome') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> ยกเลิก
                </a>
                <button type="submit" class="px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane"></i> {{ isset($item) ? 'บันทึกการแก้ไข' : 'ส่งข้อมูล' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateRowNumbers() {
    document.querySelectorAll('.guest-row').forEach((row, index) => {
        const num = row.querySelector('.row-number');
        if (num) num.textContent = index + 1;
    });
}

function addGuest() {
    const c = document.getElementById('guest-members-container');
    if (!c) return;
    
    const index = document.querySelectorAll('.guest-row').length;
    const row = document.createElement('div');
    row.className = 'guest-row bg-slate-50 rounded-lg p-4 transition-all duration-350 border border-slate-200 scale-95 opacity-0 mb-3';
    row.innerHTML = `
        <div class="flex items-center justify-between mb-3 border-b border-slate-200 pb-2">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded bg-slate-200 text-slate-700 text-[10px] font-bold flex items-center justify-center row-number">${index + 1}</span>
                <span class="text-xs font-bold text-slate-500">ข้อมูลผู้เข้าพัก</span>
            </div>
            <button type="button" class="remove-guest text-red-500 hover:text-red-700 transition-colors">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-5">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                <input type="text" name="guest_name[]" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">อายุ <span class="text-red-500">*</span></label>
                <input type="number" name="guest_age[]" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">ความสัมพันธ์ <span class="text-red-500">*</span></label>
                <input type="text" name="guest_relation[]" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">เบอร์โทรศัพท์</label>
                <input type="text" name="guest_phone[]" class="w-full rounded border border-slate-300 text-xs h-9 px-2.5 focus:ring-1 focus:ring-red-500 focus:border-red-500">
            </div>
        </div>
    `;
    c.appendChild(row);
    
    // Animation
    setTimeout(() => {
        row.classList.remove('scale-95', 'opacity-0');
        row.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Bind remove button
    row.querySelector('.remove-guest').addEventListener('click', function() {
        row.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            row.remove();
            updateRowNumbers();
        }, 300);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // Bind Add button click
    const addGuestBtn = document.getElementById('add-guest-btn');
    if (addGuestBtn) {
        addGuestBtn.addEventListener('click', addGuest);
    }

    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        const position = document.querySelector('input[name="position"]').value;
        const department = document.querySelector('input[name="department"]').value;

        if (!position || !department) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลของคุณยังไม่ครบถ้วน',
                text: 'ตรวจพบว่าข้อมูล ตำแหน่ง หรือ แผนก ของคุณยังไม่มีในระบบ กรุณาตรวจสอบและระบุข้อมูลเพิ่มให้ครบถ้วนก่อนส่งคำขอ',
                confirmButtonColor: '#ef4444',
            });
        }
    });
});
</script>
@endpush
