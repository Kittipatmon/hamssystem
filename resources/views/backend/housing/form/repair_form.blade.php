@extends('layouts.housing.apphousing')
@section('title', 'แจ้งซ่อมบ้านพัก')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-500 transition-colors mb-3">
            <i class="fa-solid fa-arrow-left"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200 shrink-0">
                <i class="fa-solid fa-screwdriver-wrench text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">แบบฟอร์มแจ้งซ่อมบ้านพัก</h2>
                <p class="text-xs text-gray-400 mt-0.5">กรุณาระบุรายละเอียดความเสียหายเพื่อดำเนินการแก้ไข</p>
            </div>
        </div>
    </div>

    @if($currentStay)
    <form action="{{ route('housing.repair.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="room_id" value="{{ $currentStay->residence_room_id }}">
        
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-emerald-500"></i> ข้อมูลการแจ้งซ่อม
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Room Info Display --}}
                <div class="bg-emerald-50/50 rounded-xl p-4 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">ห้องที่เข้าพักปัจจุบัน</p>
                        <p class="text-lg font-black text-slate-800">ห้อง {{ $currentStay->room->room_number }}</p>
                        <p class="text-xs text-slate-500">{{ $currentStay->room->residence->name }} @if($currentStay->room->floor) ชั้น {{ $currentStay->room->floor }} @endif</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-emerald-500 shadow-sm">
                        <i class="fa-solid fa-house-chimney text-xl"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">หัวข้อการแจ้งซ่อม <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="เช่น ท่อน้ำรั่ว, ไฟดับ, กลอนประตูเสีย"
                            class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm h-11">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">รายละเอียดความเสียหาย <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required placeholder="อธิบายรายละเอียดความเสียหายหรือปัญหาที่พบ..."
                            class="w-full rounded-xl border-gray-200 focus:ring-emerald-500 focus:border-emerald-500 text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">รูปภาพประกอบ (ถ้ามี)</label>
                        <div class="space-y-4">
                            <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-2xl hover:border-emerald-400 hover:bg-emerald-50/10 transition-all cursor-pointer group relative" id="dropzone">
                                <div class="space-y-2 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 group-hover:bg-emerald-100 flex items-center justify-center mx-auto transition-colors">
                                        <i class="fa-solid fa-images text-2xl text-gray-400 group-hover:text-emerald-500"></i>
                                    </div>
                                    <div class="flex text-sm text-gray-600">
                                        <span class="relative cursor-pointer bg-transparent rounded-md font-bold text-emerald-600 hover:text-emerald-500">เลือกรูปภาพ</span>
                                        <p class="pl-1">หรือลากไฟล์มาวางที่นี่</p>
                                    </div>
                                    <p class="text-[10px] text-gray-400">รองรับ PNG, JPG, JPEG (อัปโหลดได้หลายรูปพร้อมกัน)</p>
                                </div>
                                <input type="file" name="repair_images[]" id="image-upload" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>

                            {{-- Preview Area --}}
                            <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-4 gap-3 hidden">
                                <!-- Preview items will be injected here -->
                            </div>
                        </div>

                        <script>
                            document.getElementById('image-upload').addEventListener('change', function(e) {
                                const preview = document.getElementById('image-preview');
                                preview.innerHTML = '';
                                
                                if (this.files && this.files.length > 0) {
                                    preview.classList.remove('hidden');
                                    
                                    Array.from(this.files).forEach((file, index) => {
                                        const reader = new FileReader();
                                        const container = document.createElement('div');
                                        container.className = 'relative group aspect-square rounded-xl overflow-hidden border border-gray-100 shadow-sm transition-transform hover:scale-[1.05]';
                                        
                                        reader.onload = function(event) {
                                            container.innerHTML = `
                                                <img src="${event.target.result}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <span class="text-[10px] text-white font-bold px-2 truncate w-full text-center">${file.name}</span>
                                                </div>
                                            `;
                                        }
                                        
                                        reader.readAsDataURL(file);
                                        preview.appendChild(container);
                                    });
                                } else {
                                    preview.classList.add('hidden');
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('housing.welcome') }}" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition-colors">ยกเลิก</a>
                <button type="submit" class="px-10 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 hover:shadow-xl hover:scale-[1.02] transition-all">
                    ส่งข้อมูลแจ้งซ่อม
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
        <div class="w-20 h-20 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mx-auto mb-6">
            <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800">ไม่พบข้อมูลการเข้าพัก</h3>
        <p class="text-gray-500 mt-2 max-w-xs mx-auto">ขออภัย คุณต้องมีข้อมูลการเข้าพักในระบบก่อนจึงจะสามารถส่งเรื่องแจ้งซ่อมได้</p>
        <a href="{{ route('housing.welcome') }}" class="mt-8 inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-house"></i> กลับสู่หน้าหลัก
        </a>
    </div>
    @endif
</div>
@endsection
