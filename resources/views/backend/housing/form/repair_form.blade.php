@extends('layouts.housing.apphousing')
@section('title', 'แจ้งซ่อมบ้านพัก')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-red-500 transition-colors mb-3 uppercase tracking-wider">
            <i class="fa-solid fa-chevron-left text-[10px]"></i> กลับหน้าหลัก
        </a>
        <div class="bg-white rounded-xl border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-screwdriver-wrench text-slate-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">แบบฟอร์มแจ้งซ่อมบ้านพัก</h2>
                <p class="text-xs text-slate-400 mt-0.5">กรุณาระบุรายละเอียดความเสียหายเพื่อดำเนินการแก้ไข</p>
            </div>
        </div>
    </div>

    @if($currentStay)
    <form action="{{ route('housing.repair.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="room_id" value="{{ $currentStay->residence_room_id }}">
        
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200">
                <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-info-circle text-red-500"></i> ข้อมูลการแจ้งซ่อม
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Room Info Display --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-slate-450 uppercase tracking-widest mb-1">ห้องที่เข้าพักปัจจุบัน</p>
                        <p class="text-lg font-mono font-black text-slate-800">ห้อง {{ $currentStay->room->room_number }}</p>
                        <p class="text-xs text-slate-500 font-bold mt-0.5">{{ $currentStay->room->residence->name }} @if($currentStay->room->floor) ชั้น {{ $currentStay->room->floor }} @endif</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 shadow-sm shrink-0">
                        <i class="fa-solid fa-house-chimney text-lg"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">หัวข้อการแจ้งซ่อม <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="เช่น ท่อน้ำรั่ว, ไฟดับ, กลอนประตูเสีย"
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm h-10 px-3 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-1.5">รายละเอียดความเสียหาย <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required placeholder="อธิบายรายละเอียดความเสียหายหรือปัญหาที่พบ..."
                            class="w-full rounded-lg border border-slate-300 focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm p-3 transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-650 mb-2">รูปภาพประกอบ (ถ้ามี)</label>
                        <div class="space-y-4">
                            <div class="flex justify-center px-6 pt-5 pb-6 border border-dashed border-slate-300 rounded-xl hover:border-slate-400 hover:bg-slate-50/50 transition-all cursor-pointer group relative" id="dropzone">
                                <div class="space-y-2 text-center">
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 group-hover:bg-slate-200 flex items-center justify-center mx-auto transition-colors border border-slate-200">
                                        <i class="fa-solid fa-images text-lg text-slate-500"></i>
                                    </div>
                                    <div class="flex text-xs text-slate-600 justify-center">
                                        <span class="relative cursor-pointer bg-transparent rounded-md font-bold text-red-600 hover:text-red-700">เลือกรูปภาพ</span>
                                        <p class="pl-1">หรือลากไฟล์มาวางที่นี่</p>
                                    </div>
                                    <p class="text-[9px] text-slate-400">รองรับ PNG, JPG, JPEG (อัปโหลดได้หลายรูปพร้อมกัน)</p>
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
                                        container.className = 'relative group aspect-square rounded-lg overflow-hidden border border-slate-200 shadow-sm transition-transform hover:scale-[1.02]';
                                        
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

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('housing.welcome') }}" class="px-5 py-2.5 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">ยกเลิก</a>
                <button type="submit" class="px-8 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg shadow-sm transition-all">
                    ส่งข้อมูลแจ้งซ่อม
                </button>
            </div>
        </div>
    </form>
    @else
    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center shadow-sm">
        <div class="w-16 h-16 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-500 mx-auto mb-5">
            <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800">ไม่พบข้อมูลการเข้าพัก</h3>
        <p class="text-xs text-slate-500 mt-2 max-w-xs mx-auto">ขออภัย คุณต้องมีข้อมูลการเข้าพักในระบบก่อนจึงจะสามารถส่งเรื่องแจ้งซ่อมได้</p>
        <a href="{{ route('housing.welcome') }}" class="mt-6 inline-flex items-center gap-1.5 px-5 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-lg hover:bg-slate-200 transition-colors text-xs border border-slate-200 shadow-sm">
            <i class="fa-solid fa-house-chimney text-slate-500"></i> กลับสู่หน้าหลัก
        </a>
    </div>
    @endif
</div>
@endsection
