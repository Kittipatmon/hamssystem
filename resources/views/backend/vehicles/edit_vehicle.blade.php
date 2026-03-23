@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-car-rear text-red-600"></i>
                    แก้ไขข้อมูลรถ: {{ $vehicle->name }}
                </h2>
                <p class="text-slate-500 mt-1">อัปเดตรายละเอียดและรูปภาพของรถส่วนกลาง</p>
            </div>
            <a href="{{ route('backend.vehicles.table') }}" class="btn btn-sm btn-ghost gap-2">
                <i class="fa-solid fa-arrow-left"></i> กลับ
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('backend.vehicles.update', $vehicle->vehicle_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 md:p-8 space-y-8">
                    <!-- Basic Info Section -->
                    <section class="space-y-4">
                        <h3 class="font-bold text-slate-700 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500"></i> ข้อมูลพื้นฐาน
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">ชื่อรถ / ทะเบียน</span></label>
                                <input type="text" name="name" value="{{ old('name', $vehicle->name) }}" 
                                    class="input input-bordered w-full focus:ring-2 focus:ring-red-500" required>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">ยี่ห้อ (Brand)</span></label>
                                <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" 
                                    class="input input-bordered w-full focus:ring-2 focus:ring-red-500">
                                @error('brand') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">รุ่น (Model Name)</span></label>
                                <input type="text" name="model_name" value="{{ old('model_name', $vehicle->model_name) }}" 
                                    class="input input-bordered w-full focus:ring-2 focus:ring-red-500">
                                @error('model_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">ประเภทรถ</span></label>
                                <select name="type" class="select select-bordered w-full focus:ring-2 focus:ring-red-500">
                                    <option value="เก๋ง" {{ old('type', $vehicle->type) == 'เก๋ง' ? 'selected' : '' }}>เก๋ง</option>
                                    <option value="กระบะ" {{ old('type', $vehicle->type) == 'กระบะ' ? 'selected' : '' }}>กระบะ</option>
                                    <option value="รถตู้" {{ old('type', $vehicle->type) == 'รถตู้' ? 'selected' : '' }}>รถตู้</option>
                                    <option value="SUV" {{ old('type', $vehicle->type) == 'SUV' ? 'selected' : '' }}>SUV</option>
                                    <option value="อื่นๆ" {{ old('type', $vehicle->type) == 'อื่นๆ' ? 'selected' : '' }}>อื่นๆ</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    <!-- Specifications Section -->
                    <section class="space-y-4">
                        <h3 class="font-bold text-slate-700 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-purple-500"></i> ข้อมูลทางเทคนิค
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">จำนวนที่นั่ง</span></label>
                                <input type="number" name="seat" value="{{ old('seat', $vehicle->seat) }}" 
                                    class="input input-bordered w-full focus:ring-2 focus:ring-red-500">
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-semibold text-slate-600">เชื้อเพลิงที่รองรับ</span></label>
                                <input type="text" name="filling_type" value="{{ old('filling_type', $vehicle->filling_type) }}" 
                                    placeholder="เช่น เบนซิน 95, ดีเซล B7" 
                                    class="input input-bordered w-full focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold text-slate-600">รายละเอียดเพิ่มเติม (Description)</span></label>
                            <textarea name="desciption" class="textarea textarea-bordered h-24 focus:ring-2 focus:ring-red-500">{{ old('desciption', $vehicle->desciption) }}</textarea>
                        </div>
                    </section>

                    <!-- Image Section -->
                    <section class="space-y-4">
                        <h3 class="font-bold text-slate-700 pb-2 border-b border-slate-100 flex items-center gap-2">
                            <i class="fa-solid fa-image text-green-500"></i> รูปภาพรถ
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div>
                                <label class="label mb-2"><span class="label-text font-semibold text-slate-600">อัปโหลดรูปภาพใหม่</span></label>
                                <div class="flex items-center gap-4">
                                    <input type="file" name="image" id="imageInput" accept="image/*"
                                        class="file-input file-input-bordered file-input-error w-full max-w-xs"
                                        onchange="previewImage(this)">
                                </div>
                                <p class="text-[11px] text-slate-400 mt-2 italic">* ไฟล์ภาพขนาดไม่เกิน 5MB (jpeg, png, jpg, gif)</p>
                            </div>

                            <div class="flex flex-col items-center">
                                <label class="label self-start"><span class="label-text font-semibold text-slate-600">รูปภาพปัจจุบัน / ตัวอย่าง</span></label>
                                <div id="imagePreviewContainer" class="w-full h-48 bg-slate-50 border border-dashed border-slate-300 rounded-xl flex items-center justify-center overflow-hidden">
                                    @php
                                        $images = is_string($vehicle->images) ? json_decode($vehicle->images, true) : $vehicle->images;
                                        $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                                        $currentImage = null;
                                        if ($firstImage) {
                                            $paths = ['images/vehicle/', 'images/', ''];
                                            foreach($paths as $path) {
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
                                            <i class="fa-regular fa-image text-4xl mb-2"></i>
                                            <span class="text-sm italic">ยังไม่มีรูปภาพ</span>
                                        </div>
                                        <img id="previewImg" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Form Footer -->
                <div class="p-6 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                    <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white border-0 px-8 shadow-md">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูล
                    </button>
                    <a href="{{ route('backend.vehicles.table') }}" class="btn btn-outline border-slate-300 text-slate-600 bg-white">
                        ยกเลิก
                    </a>
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
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
