@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 animate-fadeIn">
        
        <!-- New Premium Header (Clinical Theme) -->
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-sky-50 rounded-2xl flex items-center justify-center shadow-sm border border-sky-100 shrink-0">
                    <i class="fa-solid fa-file-medical text-sky-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">
                        แก้ไขรายละเอียดแฟ้มข้อมูลรถ (EDIT VEHICLE RECORD)
                    </h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-id-card-clip text-blue-500"></i>
                        กำลังดำเนินการปรับปรุงข้อมูลของรถยนต์: <span class="font-bold text-slate-700 underline decoration-sky-200">{{ $vehicle->name }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('backend.bookingcar.table') }}" 
                class="btn btn-sm bg-white text-slate-700 border-slate-200 hover:bg-slate-50 shadow-sm rounded-full px-5 h-10 min-h-0 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-200 overflow-hidden">
            <form action="{{ route('backend.bookingcar.update', $vehicle->vehicle_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="px-6 py-10 md:p-12 space-y-10">
                    
                    <!-- Section 1: Basic specifications (ข้อมูลพื้นฐานด้านทะเบียนและตัวถัง) -->
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-200 space-y-6">
                        <h3 class="font-black text-slate-800 pb-3 border-b border-slate-200 flex items-center gap-2.5 text-sm uppercase tracking-wider">
                            <i class="fa-solid fa-clipboard-check text-sky-600 text-lg"></i>
                            ข้อมูลพื้นฐานด้านทะเบียนและตัวถัง (BASIC IDENTIFICATION)
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ชื่อรถ / ข้อมูลทะเบียนรถ <span class="text-red-500">*</span></span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $vehicle->name) }}"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12" required>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ยี่ห้อผู้ผลิต (Brand)</span>
                                </label>
                                <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12">
                                @error('brand') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รุ่นโมเดล (Model Name)</span>
                                </label>
                                <input type="text" name="model_name" value="{{ old('model_name', $vehicle->model_name) }}"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12">
                                @error('model_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700">ปีที่ผลิต (Year)</span>
                                    </label>
                                    <input type="text" name="year" value="{{ old('year', $vehicle->year) }}"
                                        class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12">
                                    @error('year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700">ประเภทรถ</span>
                                    </label>
                                    <select name="type" class="select select-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-bold text-slate-700 h-12">
                                        <option value="เก๋ง" {{ old('type', $vehicle->type) == 'เก๋ง' ? 'selected' : '' }}>เก๋ง</option>
                                        <option value="กระบะ" {{ old('type', $vehicle->type) == 'กระบะ' ? 'selected' : '' }}>กระบะ</option>
                                        <option value="รถตู้" {{ old('type', $vehicle->type) == 'รถตู้' ? 'selected' : '' }}>รถตู้</option>
                                        <option value="SUV" {{ old('type', $vehicle->type) == 'SUV' ? 'selected' : '' }}>SUV</option>
                                        <option value="อื่นๆ" {{ old('type', $vehicle->type) == 'อื่นๆ' ? 'selected' : '' }}>อื่นๆ</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Section 2: Technical specifications (ข้อมูลด้านเทคนิคและมาตรวัด) -->
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-200 space-y-6">
                        <h3 class="font-black text-slate-800 pb-3 border-b border-slate-200 flex items-center gap-2.5 text-sm uppercase tracking-wider">
                            <i class="fa-solid fa-gauge text-purple-600 text-lg"></i>
                            ข้อมูลจำเพาะและมาตรวัดระยะทาง (TECHNICAL SPECIFICATIONS)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">จำนวนที่นั่งผู้โดยสาร</span>
                                </label>
                                <input type="number" name="seat" value="{{ old('seat', $vehicle->seat) }}"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12">
                            </div>

                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">ประเภทน้ำมันเชื้อเพลิง</span>
                                </label>
                                <input type="text" name="filling_type" value="{{ old('filling_type', $vehicle->filling_type) }}"
                                    placeholder="เช่น ดีเซล B7, เบนซิน 95"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium h-12">
                            </div>

                            <div class="form-control">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">เลขไมล์สะสมล่าสุด (กม.)</span>
                                </label>
                                <input type="number" name="latest_mileage" value="{{ old('latest_mileage', $vehicle->latest_mileage) }}"
                                    class="input input-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-mono font-bold text-orange-600 h-12">
                                <span class="text-[10px] text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation text-blue-500"></i> ค่านี้ได้รับการอัปเดตอัตโนมัติจากการบันทึกเลขไมล์ขากลับ
                                </span>
                            </div>

                        </div>

                        <div class="form-control">
                            <label class="label pb-1">
                                <span class="label-text font-bold text-slate-700">บันทึกเพิ่มเติม / หมายเหตุตัวถัง (Description)</span>
                            </label>
                            <textarea name="desciption" rows="3"
                                class="textarea textarea-bordered w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all font-medium"
                                placeholder="เช่น รายละเอียดด้านประกันภัย หรือประวัติการซ่อมบำรุงที่ผ่านมา...">{{ old('desciption', $vehicle->desciption) }}</textarea>
                        </div>
                    </div>

                    <!-- Section 3: Visual profile (รูปภาพและเอกสารประจำรถ) -->
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-200 space-y-6">
                        <h3 class="font-black text-slate-800 pb-3 border-b border-slate-200 flex items-center gap-2.5 text-sm uppercase tracking-wider">
                            <i class="fa-solid fa-image text-emerald-600 text-lg"></i>
                            ภาพถ่ายและสื่อประจำตัวรถ (VISUAL RECONNAISSANCE)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            
                            <div class="space-y-4">
                                <div class="form-control">
                                    <label class="label pb-1">
                                        <span class="label-text font-bold text-slate-700">อัปโหลดรูปภาพใหม่</span>
                                    </label>
                                    <input type="file" name="image" id="imageInput" accept="image/*"
                                        class="file-input file-input-bordered file-input-info w-full rounded-2xl bg-white border-slate-300 focus:border-sky-500 transition-all text-xs"
                                        onchange="previewImage(this)">
                                </div>
                                <div class="bg-slate-100 border-l-4 border-sky-500 p-4 rounded-xl">
                                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                        <i class="fa-solid fa-info-circle text-sky-600 mr-1"></i>
                                        โปรดเตรียมรูปถ่ายด้านหน้า หรือมุมข้างของรถยนต์ที่เห็นป้ายทะเบียนชัดเจน ไฟล์ภาพต้องมีขนาดไม่เกิน 5MB (รองรับสกุล .jpg, .jpeg, .png)
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <label class="label pb-1">
                                    <span class="label-text font-bold text-slate-700">รูปภาพปัจจุบัน (TECHNICAL PREVIEW)</span>
                                </label>
                                
                                <!-- Double Bordered Technical Spec Preview Slot -->
                                <div class="p-2 border border-slate-300 rounded-[1.5rem] bg-slate-50">
                                    <div id="imagePreviewContainer"
                                        class="w-full h-52 border-2 border-dashed border-slate-400 rounded-xl bg-white flex items-center justify-center overflow-hidden relative group">
                                        
                                        @php
                                            $images = is_string($vehicle->images) ? json_decode($vehicle->images, true) : $vehicle->images;
                                            $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                                            $currentImage = null;
                                            if ($firstImage) {
                                                $paths = ['images/vehicle/', 'images/', ''];
                                                foreach ($paths as $path) {
                                                    if (file_exists(public_path($path . $firstImage))) {
                                                        $currentImage = asset($path . $firstImage);
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if($currentImage)
                                            <img src="{{ $currentImage }}" id="previewImg" class="w-full h-full object-cover">
                                        @else
                                            <div id="placeholder" class="text-slate-400 flex flex-col items-center">
                                                <i class="fa-solid fa-circle-exclamation text-3xl text-slate-300 mb-2"></i>
                                                <span class="text-xs font-bold italic">ไม่พบรูปภาพในคลังสื่อ</span>
                                            </div>
                                            <img id="previewImg" class="w-full h-full object-cover hidden">
                                        @endif

                                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-black uppercase tracking-widest gap-2">
                                            <i class="fa-solid fa-camera"></i> Live Preview Area
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Form Footer (clinical panel theme) -->
                <div class="px-8 py-8 md:px-12 md:py-8 bg-slate-900 flex justify-end gap-3 items-center">
                    <a href="{{ route('backend.bookingcar.table') }}"
                        class="btn btn-ghost text-slate-400 hover:text-white rounded-2xl px-6 font-bold text-xs h-11 min-h-0">
                        ยกเลิกการแก้ไข
                    </a>
                    <button type="submit" class="btn bg-sky-500 hover:bg-sky-600 text-white border-0 rounded-2xl px-10 font-black text-xs shadow-lg shadow-sky-500/20 h-11 min-h-0 transition-transform hover:scale-[1.02] active:scale-95">
                        <i class="fa-solid fa-save mr-2 text-sm"></i> บันทึกรายละเอียดแฟ้มข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('previewImg');
            const placeholder = document.getElementById('placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection