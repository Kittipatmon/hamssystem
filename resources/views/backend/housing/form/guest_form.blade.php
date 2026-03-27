@extends('layouts.housing.apphousing')
@section('title', 'ขออนุญาตนำญาติเข้าพัก (QF-HAMS-05)')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-purple-500 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200 shrink-0">
                <i class="fa-solid fa-people-arrows text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">แบบฟอร์มขออนุญาตนำบุคคลภายนอก/ญาติเข้าพักอาศัย</h2>
                <p class="text-xs text-gray-400 mt-0.5">QF-HAMS-05 Rev.00 • กรุณากรอกข้อมูลให้ครบทุกช่องที่มีเครื่องหมาย <span class="text-red-500">*</span></p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-xl text-sm mb-6">
            <p class="font-bold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>กรุณาตรวจสอบข้อมูล:</p>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('housing.guest.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

            {{-- ข้อมูลผู้ขออนุญาต --}}
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-user text-purple-400"></i> ข้อมูลผู้ขออนุญาต</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">คำนำหน้า</label>
                        <select name="prefix" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">-- เลือก --</option>
                            @foreach(['นาย', 'นาง', 'นางสาว'] as $t)
                                <option value="{{ $t }}" {{ old('prefix', $user->prefix ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ชื่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->first_name ?? '') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', Auth::user()->last_name ?? '') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ตำแหน่ง</label>
                        <input type="text" name="position" value="{{ old('position', $user->position ?? '') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">แผนก</label>
                        <input type="text" name="department" value="{{ old('department', $user->department->department_name ?? '') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ฝ่าย</label>
                        <input type="text" name="section" value="{{ old('section', $user->division->division_name ?? '') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ความสัมพันธ์กับผู้เข้าพัก</label>
                        <input type="text" name="relationship" value="{{ old('relationship') }}" placeholder="เช่น บิดา, มารดา" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
            </div>

            {{-- ข้อมูลบ้านพักและช่วงเวลา --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-calendar-days text-indigo-400"></i> ข้อมูลบ้านพักและช่วงเวลา</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ประเภทบ้านพัก <span class="text-red-500">*</span></label>
                        <select name="residence_type" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                            <option value="">-- เลือกบ้านพัก --</option>
                            @foreach($residences as $r)
                                <option value="{{ $r->name }}" {{ old('residence_type') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">เลขที่ห้อง</label>
                        <input type="text" name="room_number" value="{{ old('room_number') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">วันที่เริ่ม <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">เวลาเริ่ม <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" value="{{ old('start_time', '08:00') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">วันที่สิ้นสุด <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">เวลาสิ้นสุด <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" value="{{ old('end_time', '17:00') }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                </div>
            </div>

            {{-- รายชื่อผู้เข้าพัก --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-users text-teal-400"></i> รายชื่อผู้เข้าพัก</h3>
                <button type="button" onclick="addGuest()" class="text-xs font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i> เพิ่มรายชื่อ
                </button>
            </div>
            <div class="p-5" id="guests-container">
                <div class="guest-row bg-gray-50 rounded-xl p-4">
                    <div class="grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">ชื่อ-นามสกุล</label>
                            <input type="text" name="guest_name[]" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">อายุ</label>
                            <input type="number" name="guest_age[]" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="col-span-4">
                            <label class="block text-xs font-semibold text-gray-500 mb-1">ความสัมพันธ์</label>
                            <input type="text" name="guest_relation[]" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <button type="button" onclick="this.closest('.guest-row').remove()" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ route('housing.welcome') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> ยกเลิก
                </a>
                <button type="submit" class="px-8 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl text-sm font-bold hover:from-purple-600 hover:to-purple-700 shadow-lg shadow-purple-200 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> ส่งคำขอ
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function addGuest() {
    const c = document.getElementById('guests-container');
    const row = document.createElement('div');
    row.className = 'guest-row bg-gray-50 rounded-xl p-4 mt-3';
    row.innerHTML = `
        <div class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-5"><input type="text" name="guest_name[]" placeholder="ชื่อ-นามสกุล" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500"></div>
            <div class="col-span-2"><input type="number" name="guest_age[]" placeholder="อายุ" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500"></div>
            <div class="col-span-4"><input type="text" name="guest_relation[]" placeholder="ความสัมพันธ์" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-purple-500 focus:border-purple-500"></div>
            <div class="col-span-1 flex justify-center"><button type="button" onclick="this.closest('.guest-row').remove()" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 transition-colors flex items-center justify-center"><i class="fa-solid fa-trash-can text-xs"></i></button></div>
        </div>
    `;
    c.appendChild(row);
}
</script>
@endsection
