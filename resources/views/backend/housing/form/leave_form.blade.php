@extends('layouts.housing.apphousing')
@section('title', isset($item) ? 'แก้ไขคำร้องขอย้ายออกจากบ้านพัก' : 'คำร้องขอย้ายออกจากบ้านพัก')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-red-500 transition-colors mb-3 uppercase tracking-wider">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-right-from-bracket text-slate-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">{{ isset($item) ? 'แก้ไขคำร้องขอย้ายออกจากบ้านพัก' : 'คำร้องขอย้ายออกจากบ้านพัก' }}</h2>
                <p class="text-xs text-slate-400 mt-0.5">Move-out Request • กรุณากรอกข้อมูลให้ครบทุกช่องที่มีเครื่องหมาย <span class="text-red-500">*</span></p>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-550 text-red-800 px-4 py-3 rounded-r-lg text-xs mb-6 border border-red-200">
            <p class="font-bold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>กรุณาตรวจสอบข้อมูล:</p>
            <ul class="list-disc pl-5 text-[11px] space-y-0.5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($item) ? route('housing.leave.update', $item->residence_leaves_id) : route('housing.leave.store') }}" method="POST">
        @csrf
        @if(isset($item)) @method('PUT') @endif
        <input type="hidden" name="residence_room_id" value="{{ $currentStay->room->residence_room_id ?? '' }}">
        
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-user text-red-550"></i> ข้อมูลผู้ขอย้ายออก</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">คำนำหน้า <span class="text-red-500">*</span></label>
                        <select name="prefix" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                            <option value="">-- เลือก --</option>
                            @foreach(['นาย', 'นาง', 'นางสาว'] as $t)
                                <option value="{{ $t }}" {{ old('prefix', $item->prefix ?? ($snapshot->title ?? ($snapshot->prefix ?? ($user->prefix ?? '')))) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ชื่อ <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $item->first_name ?? (Auth::user()->first_name ?? '')) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $item->last_name ?? (Auth::user()->last_name ?? '')) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ตำแหน่ง</label>
                        <input type="text" name="position" value="{{ old('position', $item->position ?? ($snapshot->position ?? ($user->position ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">แผนก</label>
                        <input type="text" name="department" value="{{ old('department', $item->department ?? ($snapshot->department ?? ($user->department->department_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ฝ่าย</label>
                        <input type="text" name="section" value="{{ old('section', $item->section ?? ($snapshot->section ?? ($user->division->division_name ?? ''))) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
            </div>

            <div class="px-5 py-3 bg-slate-50 border-t border-b border-slate-200">
                <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-house-circle-xmark text-red-500"></i> ข้อมูลการย้ายออก</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ประเภทบ้านพัก <span class="text-red-500">*</span></label>
                        <select name="residence_type" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                            <option value="">-- เลือกบ้านพัก --</option>
                            @foreach($residences as $r)
                                <option value="{{ $r->name }}" {{ old('residence_type', $item->residence_type ?? ($currentStay->room->residence->name ?? '')) == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">เลขที่ห้อง <span class="text-red-500">*</span></label>
                        <input type="text" name="room_number" value="{{ old('room_number', $item->room_number ?? ($currentStay->room->room_number ?? '')) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">ชั้น</label>
                        <input type="text" name="floor" value="{{ old('floor', $item->floor ?? ($currentStay->room->floor ?? '')) }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">วันที่ต้องการย้ายออก <span class="text-red-500">*</span></label>
                        <input type="date" name="move_out_date" value="{{ old('move_out_date', $item->move_out_date ?? '') }}" class="w-full rounded-lg border border-slate-300 text-xs h-10 px-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-650 mb-1.5">เหตุผลที่ขอย้ายออก <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="3" class="w-full rounded-lg border border-slate-300 text-xs p-3 focus:ring-1 focus:ring-red-500 focus:border-red-500" placeholder="ระบุเหตุผลที่ขอย้ายออกจากบ้านพัก..." required>{{ old('reason', $item->reason ?? '') }}</textarea>
                </div>
            </div>

            <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                <a href="{{ route('housing.welcome') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-lg text-xs font-bold hover:bg-slate-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> ยกเลิก
                </a>
                <button type="submit" class="px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-paper-plane"></i> {{ isset($item) ? 'บันทึกการแก้ไข' : 'ส่งคำร้อง' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
        const position = document.querySelector('input[name="position"]').value;
        const department = document.querySelector('input[name="department"]').value;

        if (!position || !department) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลของคุณยังไม่ครบถ้วน',
                text: 'ตรวจพบว่าข้อมูล ตำแหน่ง หรือ แผนก ของคุณยังไม่มีในระบบ กรุณาตรวจสอบและระบุข้อมูลเพิ่มให้ครบถ้วนก่อนส่งคำร้อง',
                confirmButtonColor: '#ef4444',
            });
        }
    });
});
</script>
@endsection
