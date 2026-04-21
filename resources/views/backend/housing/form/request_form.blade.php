@extends('layouts.housing.apphousing')
@section('title', isset($item) ? 'แก้ไขคำขอเข้าพักบ้านพักพนักงาน' : 'คำขอเข้าพักบ้านพักพนักงาน (QF-HAMS-02)')

@section('content')
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('housing.welcome') }}"
                class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-500 transition-colors mb-3">
                <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
            </a>
            <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg shadow-red-200 shrink-0">
                    <i class="fa-solid fa-file-circle-plus text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ isset($item) ? 'แก้ไขแบบฟอร์มขอเข้าอยู่อาศัยบ้านพักพนักงาน' : 'แบบฟอร์มขอเข้าอยู่อาศัยบ้านพักพนักงาน' }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">QF-HAMS-02 Rev.00 • กรุณากรอกข้อมูลให้ครบทุกช่องที่มีเครื่องหมาย
                        <span class="text-red-500">*</span></p>
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

        <form action="{{ isset($item) ? route('housing.request.update', $item->id) : route('housing.request.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

                {{-- ข้อมูลส่วนตัว --}}
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i
                            class="fa-solid fa-user text-red-400"></i> ข้อมูลส่วนตัว</h3>
                </div>
                <div class="p-5 space-y-4 ">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">ด้วยข้าพเจ้ามีความต้องการเข้าพักอาศัยในบ้านพักของพนักงาน ที่ <span
                                class="text-red-500">*</span></label>
                        <div class="flex gap-6">
                            @foreach(['โรงงานบางใหญ่', 'โรงงานไทรใหญ่'] as $site)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="site" value="{{ $site }}" class="radio radio-sm radio-error" {{ old('site', $item->site ?? ($user->workplace ?? '')) == $site ? 'checked' : '' }} required>
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-red-600 transition-colors">{{ $site }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">คำนำหน้า <span
                                    class="text-red-500">*</span></label>
                            <select name="title"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                                <option value="">-- เลือก --</option>
                                @foreach(['นาย', 'นาง', 'นางสาว'] as $t)
                                    <option value="{{ $t }}" {{ old('title', $item->title ?? ($user->prefix ?? '')) == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">ชื่อ <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', $item->first_name ?? (Auth::user()->first_name ?? '')) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">นามสกุล <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', $item->last_name ?? (Auth::user()->last_name ?? '')) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">อายุงาน (ปี)</label>
                            @php
                                $workYears = '';
                                if (!empty($user->startwork_date)) {
                                    $workYears = \Carbon\Carbon::parse($user->startwork_date)->diffInYears(now());
                                }
                            @endphp
                            <input type="text" name="age_work" value="{{ old('age_work', $item->age_work ?? $workYears) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">ตำแหน่ง <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="position"
                                value="{{ old('position', $item->position ?? ($user->position ?? '')) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">แผนก <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="department"
                                value="{{ old('department', $item->department ?? ($user->department->department_name ?? '')) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">ฝ่าย <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="section"
                                value="{{ old('section', $item->section ?? ($user->division->division_name ?? '')) }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">หมายเลขโทรศัพท์ <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $item->phone ?? '') }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                placeholder="0xx-xxx-xxxx" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-2">สถานภาพ <span
                                    class="text-red-500">*</span></label>
                            <div class="flex gap-6">
                                @foreach(['โสด', 'สมรส'] as $ms)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="radio" name="marital_status" value="{{ $ms }}"
                                            class="radio radio-sm radio-error" {{ old('marital_status', $item->marital_status ?? '') == $ms ? 'checked' : '' }}
                                            required onchange="toggleSpouse()">
                                        <span
                                            class="text-sm text-gray-600 group-hover:text-red-600 transition-colors">{{ $ms }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ที่อยู่ --}}
                <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                    <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i
                            class="fa-solid fa-location-dot text-blue-400"></i> ที่อยู่</h3>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">ที่อยู่ตามทะเบียนบ้าน</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">บ้านเลขที่ / ที่อยู่ <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="address_original"
                                    value="{{ old('address_original', $item->address_original ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">แขวง/ตำบล</label>
                                <input type="text" name="address_original_subdistrict"
                                    value="{{ old('address_original_subdistrict', $item->address_original_subdistrict ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">เขต/อำเภอ</label>
                                <input type="text" name="address_original_district"
                                    value="{{ old('address_original_district', $item->address_original_district ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">จังหวัด</label>
                                <input type="text" name="address_original_province"
                                    value="{{ old('address_original_province', $item->address_original_province ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200"></div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">ที่อยู่ปัจจุบัน</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">บ้านเลขที่ / ที่อยู่</label>
                                <input type="text" name="address_current" value="{{ old('address_current', $item->address_current ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">แขวง/ตำบล</label>
                                <input type="text" name="address_current_subdistrict"
                                    value="{{ old('address_current_subdistrict', $item->address_current_subdistrict ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">เขต/อำเภอ</label>
                                <input type="text" name="address_current_district"
                                    value="{{ old('address_current_district', $item->address_current_district ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-600 mb-1">จังหวัด</label>
                                <input type="text" name="address_current_province"
                                    value="{{ old('address_current_province', $item->address_current_province ?? '') }}"
                                    class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-2">ลักษณะที่พักอาศัยปัจจุบัน</label>
                        <div class="flex gap-6">
                            @foreach(['บ้านเช่า', 'บ้านตนเอง', 'อื่นๆ'] as $htype)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="current_house_type" value="{{ $htype }}"
                                        class="radio radio-sm radio-error" {{ old('current_house_type', $item->current_house_type ?? '') == $htype ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-gray-600 group-hover:text-red-600 transition-colors">{{ $htype }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ข้อมูลคู่สมรส (แสดงเมื่อเลือก สมรส) --}}
                <div id="spouse-section" class="transition-all duration-300 overflow-hidden"
                    style="{{ old('marital_status', $item->marital_status ?? '') == 'สมรส' ? '' : 'max-height:0;opacity:0;' }}">
                    <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i
                                class="fa-solid fa-heart text-purple-400"></i> ข้อมูลคู่สมรส</h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">ชื่อคู่สมรส</label>
                            <input type="text" name="spouse_name" value="{{ old('spouse_name', $item->spouse_name ?? '') }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">อาชีพ</label>
                            <input type="text" name="spouse_occupation" value="{{ old('spouse_occupation', $item->spouse_occupation ?? '') }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">หมายเลขโทรศัพท์</label>
                            <input type="text" name="spouse_phone" value="{{ old('spouse_phone', $item->spouse_phone ?? '') }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">สถานที่ทำงาน</label>
                            <input type="text" name="workplace_spouse" value="{{ old('workplace_spouse', $item->workplace_spouse ?? '') }}"
                                class="w-full rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500">
                        </div>
                    </div>
                </div>

                {{-- ข้อมูลผู้พักอาศัยร่วม --}}
                <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-people-group text-emerald-400"></i> ข้อมูลผู้พักอาศัยร่วม
                        </h3>
                        <button type="button" id="add-dependent-btn"
                            class="text-xs font-bold text-red-600 hover:text-red-700 flex items-center gap-1 transition-colors">
                            <i class="fa-solid fa-plus-circle"></i> เพิ่มรายชื่อ
                        </button>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">จำนวนคนที่จะเข้าพักอาศัย <span class="text-red-500">*</span></label>
                            <div class="flex items-center gap-3">
                                <input type="number" name="number_of_residents" value="{{ old('number_of_residents', $item->number_of_residents ?? 1) }}" min="1"
                                    class="w-24 rounded-lg border-gray-200 text-sm h-10 focus:ring-red-500 focus:border-red-500 text-center font-bold" required>
                                <span class="text-sm text-gray-500 font-medium">คน (รวมผู้ขอเข้าพัก)</span>
                            </div>
                        </div>
                    </div>

                    <div id="dependents-container" class="space-y-3">
                        @if(isset($item) && $item->dependents->count() > 0)
                            @foreach($item->dependents as $index => $dep)
                                <div class="dep-row bg-gray-50 rounded-xl p-4 transition-all duration-300 border border-transparent hover:border-red-100 scale-100 opacity-100">
                                    <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-2">
                                        <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-[10px] font-bold flex items-center justify-center dep-number">คนที่ {{ $index + 2 }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ผู้พักอาศัยร่วม</span>
                                    </div>
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-12 md:col-span-5">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ชื่อ-นามสกุล</label>
                                            <input type="text" name="dep_name[]" value="{{ $dep->full_name }}" placeholder="ระบุชื่อ-นามสกุล" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                                        </div>
                                        <div class="col-span-4 md:col-span-2">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">อายุ</label>
                                            <input type="number" name="dep_age[]" value="{{ $dep->age }}" placeholder="อายุ" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                                        </div>
                                        <div class="col-span-6 md:col-span-4">
                                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ความสัมพันธ์</label>
                                            <input type="text" name="dep_relation[]" value="{{ $dep->relation }}" placeholder="เช่น บิดา, มารดา, บุตร" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                                        </div>
                                        <div class="col-span-2 md:col-span-1 flex justify-center">
                                            <button type="button" class="remove-dep w-8 h-8 rounded-lg bg-white border border-red-50 text-red-400 hover:bg-red-50 hover:text-red-500 transition-colors flex items-center justify-center shadow-sm">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- เหตุผลและเอกสารแนบ --}}
                <div class="px-5 py-3 bg-gray-50 border-t border-b border-gray-100">
                    <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2"><i
                            class="fa-solid fa-pen-fancy text-amber-400"></i> เหตุผลและเอกสารแนบ</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">เหตุผลที่ขอเข้าพัก <span
                                class="text-red-500">*</span></label>
                        <textarea name="residence_reason" rows="3"
                            class="w-full rounded-lg border-gray-200 text-sm focus:ring-red-500 focus:border-red-500"
                            placeholder="ระบุเหตุผลที่ขอเข้าพักอาศัยบ้านพัก..."
                            required>{{ old('residence_reason', $item->residence_reason ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">แนบเอกสาร</label>
                        <p class="text-xs text-gray-400 mb-2">สำเนาบัตรประชาชน, สำเนาทะเบียนบ้าน ฯลฯ (รองรับ PDF, JPG, PNG สูงสุด 20MB)</p>
                        <input type="file" name="requests_file[]" multiple
                            class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border rounded-lg border-gray-200 p-1">
                    </div>
                </div>

                {{-- Submit --}}
                <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('housing.welcome') }}"
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-100 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-xmark"></i> ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl text-sm font-bold hover:from-red-600 hover:to-red-700 shadow-lg shadow-red-200 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> {{ isset($item) ? 'บันทึกการแก้ไข' : 'ส่งคำร้อง' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleSpouse() {
            const el = document.getElementById('spouse-section');
            if (!el) return;
            const checked = document.querySelector('input[name="marital_status"]:checked');
            if (checked && checked.value === 'สมรส') {
                el.style.maxHeight = el.scrollHeight + 'px';
                el.style.opacity = '1';
            } else {
                el.style.maxHeight = '0';
                el.style.opacity = '0';
            }
        }

        function updateResidentNames() {
            const rows = document.querySelectorAll('.dep-row');
            rows.forEach((row, index) => {
                const label = row.querySelector('.dep-number');
                if (label) label.textContent = `คนที่ ${index + 2}`;
            });
        }

        function updateResidentCount() {
            const countInput = document.querySelector('input[name="number_of_residents"]');
            const rows = document.querySelectorAll('.dep-row').length;
            if (countInput) {
                countInput.value = rows + 1; // Applicant + co-residents
            }
        }

        function addDependent(manualAdjustment = false) {
            const container = document.getElementById('dependents-container');
            if (!container) return;
            
            const rowsCount = document.querySelectorAll('.dep-row').length;
            const row = document.createElement('div');
            row.className = 'dep-row bg-gray-50 rounded-xl p-4 transition-all duration-300 transform scale-95 opacity-0 border border-transparent hover:border-red-100';
            row.innerHTML = `
                <div class="flex items-center gap-2 mb-3 border-b border-gray-100 pb-2">
                    <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 text-[10px] font-bold flex items-center justify-center dep-number">คนที่ ${rowsCount + 2}</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ผู้พักอาศัยร่วม</span>
                </div>
                <div class="grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-12 md:col-span-5">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ชื่อ-นามสกุล</label>
                        <input type="text" name="dep_name[]" placeholder="ระบุชื่อ-นามสกุล" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div class="col-span-4 md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">อายุ</label>
                        <input type="number" name="dep_age[]" placeholder="อายุ" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div class="col-span-6 md:col-span-4">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">ความสัมพันธ์</label>
                        <input type="text" name="dep_relation[]" placeholder="เช่น บิดา, มารดา, บุตร" class="w-full rounded-lg border-gray-200 text-sm h-9 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div class="col-span-2 md:col-span-1 flex justify-center">
                        <button type="button" class="remove-dep w-8 h-8 rounded-lg bg-white border border-red-50 text-red-400 hover:bg-red-50 hover:text-red-500 transition-colors flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);
            
            // Animation
            setTimeout(() => {
                row.classList.remove('scale-95', 'opacity-0');
                row.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Add remove event
            row.querySelector('.remove-dep').addEventListener('click', function() {
                row.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    row.remove();
                    updateResidentNames();
                    updateResidentCount();
                }, 300);
            });

            if (!manualAdjustment) {
                updateResidentCount();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleSpouse();
            
            // Bind add button
            const addBtn = document.getElementById('add-dependent-btn');
            if (addBtn) {
                addBtn.addEventListener('click', () => addDependent(false));
            }

            // Sync Number Input with Rows
            const countInput = document.querySelector('input[name="number_of_residents"]');
            if (countInput) {
                countInput.addEventListener('change', function() {
                    let total = parseInt(this.value) || 1;
                    if (total < 1) { total = 1; this.value = 1; }
                    
                    const targetDeps = total - 1;
                    const currentDeps = document.querySelectorAll('.dep-row').length;

                    if (targetDeps > currentDeps) {
                        for (let i = 0; i < (targetDeps - currentDeps); i++) {
                            addDependent(true);
                        }
                    } else if (targetDeps < currentDeps) {
                        const allRows = Array.from(document.querySelectorAll('.dep-row'));
                        const diff = currentDeps - targetDeps;
                        for (let i = 0; i < diff; i++) {
                            allRows[currentDeps - 1 - i].remove();
                        }
                        updateResidentNames();
                    }
                });
            }
            
            // Initial bind for edit mode
            document.querySelectorAll('.remove-dep').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('.dep-row');
                    row.classList.add('scale-95', 'opacity-0');
                    setTimeout(() => {
                        row.remove();
                        updateResidentNames();
                        updateResidentCount();
                    }, 300);
                });
            });

            // Check for missing profile data on submit
            const form = document.getElementById('requestForm');
            form.addEventListener('submit', (e) => {
                const posInput = document.querySelector('input[name="position"]');
                const deptInput = document.querySelector('input[name="department"]');
                const sectInput = document.querySelector('input[name="section"]');

                const position = posInput ? posInput.value : '';
                const department = deptInput ? deptInput.value : '';
                const section = sectInput ? sectInput.value : '';

                if (!position || !department || !section) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'ข้อมูลของคุณยังไม่ครบถ้วน',
                        text: 'ตรวจพบว่าข้อมูล ตำแหน่ง, แผนก หรือฝ่าย ของคุณยังไม่มีในระบบ กรุณาตรวจสอบและระบุข้อมูลเพิ่มให้ครบถ้วนก่อนส่งคำร้อง',
                        confirmButtonColor: '#ff9800',
                    });
                }
            });
        });
    </script>
@endsection