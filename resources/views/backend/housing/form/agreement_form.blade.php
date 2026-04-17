@extends('layouts.housing.apphousing')
@section('title', isset($item) ? 'แก้ไขข้อตกลงเข้าพัก' : 'ข้อตกลงเข้าพัก (QF-HAMS-03)')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-500 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200 shrink-0">
                <i class="fa-solid fa-file-signature text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ isset($item) ? 'แก้ไขแบบฟอร์มข้อตกลงการเข้าพักอาศัย' : 'แบบฟอร์มข้อตกลงการเข้าพักอาศัย' }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">QF-HAMS-03 Rev.00 • กรุณากรอกข้อมูลให้ครบทุกช่องที่มีเครื่องหมาย <span class="text-red-500">*</span></p>
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

    <form action="{{ isset($item) ? route('housing.agreement.update', $item->agreement_id) : route('housing.agreement.store') }}" method="POST">
        @csrf
        @if(isset($item)) @method('PUT') @endif
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

            {{-- ข้อมูลพนักงาน --}}
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-user-tie text-blue-400"></i> ข้อมูลพนักงาน</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">คำนำหน้า <span class="text-red-500">*</span></label>
                        <select name="title" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- เลือก --</option>
                            @foreach(['นาย', 'นาง', 'นางสาว'] as $t)
                                <option value="{{ $t }}" {{ old('title', $item->title ?? ($userRequest->title ?? ($user->prefix ?? ''))) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $item->full_name ?? ($userRequest ? ($userRequest->first_name . ' ' . $userRequest->last_name) : ((Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? '')))) }}"
                            class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ตำแหน่ง <span class="text-red-500">*</span></label>
                        <input type="text" name="position" value="{{ old('position', $item->position ?? ($userRequest->position ?? ($user->position ?? ''))) }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">แผนก <span class="text-red-500">*</span></label>
                        <input type="text" name="department" value="{{ old('department', $item->department ?? ($userRequest->department ?? ($user->department->department_name ?? ''))) }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">ฝ่าย</label>
                        <input type="text" name="section" value="{{ old('section', $item->section ?? ($userRequest->section ?? ($user->division->division_name ?? ''))) }}" class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            {{-- ข้อมูลบ้านพัก --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-house text-teal-400"></i> ข้อมูลบ้านพัก</h3>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">ห้อง / ที่อยู่บ้านพัก</label>
                    <input type="text" name="residence_address" 
                        value="{{ old('residence_address', $item->residence_address ?? ($userStay ? ($userStay->room->residence->name . ' ห้อง ' . $userStay->room->room_number) : '')) }}" 
                        placeholder="เช่น A101"
                        class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">ชั้น</label>
                    <input type="text" name="residence_floor" 
                        value="{{ old('residence_floor', $item->residence_floor ?? ($userStay->room->floor ?? '')) }}" 
                        class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">จำนวนผู้อาศัย</label>
                    <input type="number" name="number_of_residents" 
                        value="{{ old('number_of_residents', $item->number_of_residents ?? ($userRequest->number_of_residents ?? 1)) }}" min="1"
                        class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- ข้อตกลงและเงื่อนไข --}}
            <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i class="fa-solid fa-scale-balanced text-amber-400"></i> ข้อตกลงและเงื่อนไข</h3>
            </div>
            <div class="p-5">
                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 space-y-2 mb-4">
                    <ol class="list-decimal pl-5 space-y-2 text-[13px] leading-relaxed">
                        <li>ผู้พักอาศัยต้องดูแลรักษาความสะอาดบ้านพักและบริเวณบ้านพักให้คงสภาพดีเสมอ</li>
                        <li>ผู้พักอาศัยต้องใช้อุปกรณ์เครื่องใช้ไฟฟ้าที่ได้มาตรฐาน เพื่อป้องกันเหตุไฟฟ้าลัดวงจรและอัคคีภัยโดยจัดให้มีการตรวจสอบเป็นประจำทุกเดือน</li>
                        <li>ผู้พักอาศัยดูแลรักษาทรัพย์สินภายในบ้านพัก บริเวณบ้านพัก รวมทั้งดูแลทรัพย์สินที่บริษัทฯ จัดให้เป็นสวัสดิการของส่วนกลาง</li>
                        <li>ผู้พักอาศัยต้องเข้าร่วมการทำความสะอาดตามตารางที่กำหนดไว้ รวมทั้งการซ่อมบำรุงรักษาทรัพย์สินของบริษัทฯ ตามที่คณะกรรมการบ้านพักนัดหมาย</li>
                        <li>ห้ามเปลี่ยนแปลง ต่อเติมหรือกระทำการใดๆ ในบ้านพัก ก่อนที่จะได้รับการอนุญาต</li>
                        <li>หากพบเห็นอุปกรณ์ ทรัพย์สินบ้านพักเกิดความชำรุดเสียหาย ให้ดำเนินการแจ้งคณะกรรมการบ้านพักทันที</li>
                        <li>ผู้พักอาศัย ต้องประกอบอาหารในบริเวณพื้นที่ส่วนกลางที่บริษัทฯ จัดไว้ให้เท่านั้น</li>
                        <li>ห้ามนำสัตว์เลี้ยงทุกชนิดเข้ามาภายในบริเวณบ้านพัก</li>
                        <li>ห้ามนำเข้าและเสพสิ่งเสพติดทุกชนิด</li>
                        <li>ห้ามก่อการทะเลาะวิวาท ไม่กระทำการใดๆ ให้มีเสียงดัง จนเป็นที่เดือดร้อนรำคาญแก่ผู้อื่น</li>
                        <li>ห้ามนำอาวุธทุกชนิด อาทิเช่น ปืน กระสุนปืน วัตถุระเบิด และเชื้อเพลิงไวไฟ เข้ามาในบริเวณบ้านพักโดยเด็ดขาด</li>
                        <li>ห้ามสูบบุหรี่ บุหรี่ไฟฟ้าและอื่นๆ ภายในบริเวณบ้านพักโดยเด็ดขาด ยกเว้นบริเวณพื้นที่ที่จัดไว้ให้</li>
                        <li>ห้ามเล่นการพนันทุกชนิด</li>
                        <li>ห้ามบุคคลภายนอกที่ไม่ได้รับอนุญาตเข้ามาภายในพื้นที่บ้านพักโดยเด็ดขาด</li>
                    </ol>
                </div>
                <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" id="accept_terms" required class="checkbox checkbox-error checkbox-sm mt-0.5" onchange="toggleSubmit(this.checked)">
                        <span class="text-sm text-red-800 font-medium">ข้าพเจ้ายอมรับข้อตกลงทั้งหมดข้างต้น <span class="text-red-500">*</span></span>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ route('housing.welcome') }}" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> ยกเลิก
                </a>
                <button type="submit" id="submit_btn" disabled class="px-8 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl text-sm font-bold opacity-50 cursor-not-allowed shadow-lg shadow-blue-200 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> {{ isset($item) ? 'บันทึกการแก้ไข' : 'ส่งข้อตกลง' }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleSubmit(checked) {
        const btn = document.getElementById('submit_btn');
        if (checked) {
            btn.removeAttribute('disabled');
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.classList.add('hover:from-blue-600', 'hover:to-blue-700');
        } else {
            btn.setAttribute('disabled', 'disabled');
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.classList.remove('hover:from-blue-600', 'hover:to-blue-700');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const position = document.querySelector('input[name="position"]').value;
        const department = document.querySelector('input[name="department"]').value;

        if (!position || !department) {
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลของคุณยังไม่ครบถ้วน',
                text: 'ตรวจพบว่าข้อมูล ตำแหน่ง หรือ แผนก ของคุณยังไม่มีในระบบ กรุณาตรวจสอบและระบุข้อมูลเพิ่มให้ครบถ้วนก่อนส่งสัญญา',
                confirmButtonColor: '#ff9800',
            });
        }
    });
</script>
@endsection