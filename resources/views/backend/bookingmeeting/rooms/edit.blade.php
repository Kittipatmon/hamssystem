@extends('layouts.navmeeting.app')

@section('title', 'แก้ไขข้อมูลห้องประชุม')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="text-xs breadcrumbs text-slate-500 px-2">
            <ul>
                <li><a href="{{ route('backend.bookingmeeting.rooms.index') }}" class="hover:text-amber-600 font-semibold"><i
                            class="fa-solid fa-door-open mr-2"></i> จัดการข้อมูลห้องประชุม</a></li>
                <li class="text-slate-800 font-bold">แก้ไขข้อมูลห้องประชุม</li>
            </ul>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-5 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">แก้ไขรายละเอียดข้อมูลห้อง: {{ $room->room_name }}</h2>
                    <p class="text-[11px] text-slate-400 font-semibold mt-0.5">อัปเดตรายละเอียดคุณลักษณะ รูปภาพประกอบ และสถานะใช้งานระบบ</p>
                </div>
            </div>

            <form action="{{ route('backend.bookingmeeting.rooms.update', $room->room_id) }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <!-- Room Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">ชื่อเรียกห้องประชุม <span class="text-red-500">*</span></label>
                        <input type="text" name="room_name" value="{{ old('room_name', $room->room_name) }}" required
                            placeholder="เช่น Meeting Room 1, ห้องประชุมใหญ่ชั้น 2"
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">
                        @error('room_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">ความจุที่รองรับได้ (คน) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user-group text-slate-400"></i>
                            </div>
                            <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" required
                                min="1" placeholder="เช่น 15"
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">
                        </div>
                        @error('capacity') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">สถานที่ตั้ง / อาคารที่ตั้ง</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-building text-slate-400"></i>
                            </div>
                            <input type="text" name="location" value="{{ old('location', $room->location) }}"
                                placeholder="เช่น อาคาร A, สำนักงานใหญ่"
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- Floor -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">ระบุชั้นที่ตั้ง</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-stairs text-slate-400"></i>
                            </div>
                            <input type="text" name="floor" value="{{ old('floor', $room->floor) }}"
                                placeholder="เช่น ชั้น 3"
                                class="w-full h-9 pl-9 pr-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5">สถานะเปิดให้สิทธิ์จอง <span class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full h-9 px-3 bg-white border border-slate-300 rounded focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-bold text-slate-800">
                            <option value="1" {{ old('status', $room->status) == 1 ? 'selected' : '' }}>เปิดใช้งาน (ให้บุคลากรจองใช้งานได้)</option>
                            <option value="0" {{ old('status', $room->status) == '0' ? 'selected' : '' }}>ปิดการใช้งานชั่วคราว / ปรับปรุง</option>
                        </select>
                    </div>

                    <!-- Room Type -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">จำแนกประเภทห้องประชุม</label>
                        <input type="text" name="room_type" value="{{ old('room_type', $room->room_type) }}"
                            placeholder="เช่น ห้องประชุมย่อย, ห้องประชุมระดับผู้บริหาร, ห้องอบรมหลัก"
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Equipment Options -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-2">อุปกรณ์อำนวยความสะดวกสนับสนุนการประชุม</label>

                        <div class="flex flex-wrap gap-3 mt-1 font-semibold">
                            <label class="flex items-center gap-2 p-2.5 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="has_projector" value="1" {{ old('has_projector', $room->has_projector) ? 'checked' : '' }}
                                    class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded bg-indigo-50 text-indigo-500 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-display"></i>
                                    </div>
                                    <span>Projector / จอภาพฉาย</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2 p-2.5 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="has_video_conf" value="1" {{ old('has_video_conf', $room->has_video_conf) ? 'checked' : '' }}
                                    class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded bg-pink-50 text-pink-500 flex items-center justify-center text-xs">
                                        <i class="fa-solid fa-video"></i>
                                    </div>
                                    <span>ระบบประชุมทางไกล (Video Conference)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1.5">คำชี้แจง / รายละเอียดอื่นเพิ่มเติม</label>
                        <textarea name="description" rows="3" placeholder="ระบุกฎการจอง เงื่อนไขการใช้อุปกรณ์ หรือข้อความแนะนำอื่นของห้องประชุม..."
                            class="w-full p-3 rounded border border-slate-300 bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition-all font-semibold text-slate-800">{{ old('description', $room->description) }}</textarea>
                    </div>

                    <!-- Upload Images -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-slate-700 mb-2">รูปภาพห้องประชุมประกอบ (อัปโหลดเพิ่มภาพใหม่ได้)</label>

                        @php
                            $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                        @endphp

                        <!-- Existing Images -->
                        @if(!empty($images) && is_array($images))
                            <div class="mb-4">
                                <p class="text-[10px] text-slate-400 font-bold mb-2">ภาพห้องประชุมชุดปัจจุบัน:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($images as $img)
                                        @php
                                            $imagePathUrl = null;
                                            if (file_exists(public_path('images/room/' . $img))) {
                                                $imagePathUrl = asset('images/room/' . $img);
                                            } elseif (file_exists(public_path('images/' . $img))) {
                                                $imagePathUrl = asset('images/' . $img);
                                            } elseif (file_exists(public_path($img))) {
                                                $imagePathUrl = asset($img);
                                            }
                                        @endphp
                                        @if($imagePathUrl)
                                            <div class="relative w-full h-20 rounded overflow-hidden border border-slate-200 shadow-sm group">
                                                <img src="{{ $imagePathUrl }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:border-amber-400 hover:bg-amber-50/20 transition-colors cursor-pointer"
                            onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center font-bold text-slate-500">
                                <i class="fa-regular fa-images text-3xl text-slate-400"></i>
                                <div class="flex items-center justify-center text-[11px] mt-2">
                                    <span class="relative rounded font-bold text-amber-600 hover:text-amber-500">
                                        <span>อัปโหลดเพิ่มรูปภาพใหม่</span>
                                        <input id="file-upload" name="image_file[]" type="file" class="sr-only" multiple
                                            accept="image/*" onchange="previewFiles()">
                                    </span>
                                    <p class="pl-1">หรือลากไฟล์มาจัดวาง</p>
                                </div>
                                <p class="text-[10px] text-slate-400 font-normal">อนุญาตไฟล์ PNG, JPG ขนาดไม่เกิน 2MB ต่อไฟล์</p>
                            </div>
                        </div>

                        <!-- Preview Container -->
                        <div id="preview-container" class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-3 hidden"></div>

                        @error('image_file.*') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-5 border-t border-slate-200 flex items-center justify-end gap-3 font-semibold text-xs">
                    <a href="{{ route('backend.bookingmeeting.rooms.index') }}"
                        class="px-5 py-2 rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-5 py-2 rounded bg-amber-500 hover:bg-amber-600 text-white shadow transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-save"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewFiles() {
            const previewContainer = document.getElementById('preview-container');
            const files = document.getElementById('file-upload').files;

            previewContainer.innerHTML = '';

            if (files.length > 0) {
                previewContainer.classList.remove('hidden');

                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const imgDiv = document.createElement('div');
                            imgDiv.className = 'relative w-full h-20 rounded overflow-hidden border border-slate-200 shadow-sm';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-full object-cover';

                            const overlay = document.createElement('div');
                            overlay.className = 'absolute bottom-0 inset-x-0 bg-black/60 text-white text-[9px] p-1 truncate font-semibold';
                            overlay.textContent = file.name;

                            imgDiv.appendChild(img);
                            imgDiv.appendChild(overlay);
                            previewContainer.appendChild(imgDiv);
                        }

                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
@endsection