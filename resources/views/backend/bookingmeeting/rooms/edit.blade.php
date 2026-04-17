@extends('layouts.navmeeting.app')

@section('title', 'แก้ไขข้อมูลห้องประชุม')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="text-sm breadcrumbs text-slate-500 mb-4 px-2">
            <ul>
                <li><a href="{{ route('backend.bookingmeeting.rooms.index') }}" class="hover:text-amber-600"><i
                            class="fa-solid fa-door-open mr-2"></i> จัดการข้อมูลห้องประชุม</a></li>
                <li class="text-slate-700 font-medium">แก้ไขข้อมูลห้องประชุม</li>
            </ul>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-6 flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-pen"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800">แก้ไขข้อมูลห้อง: {{ $room->room_name }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">อัปเดตรายละเอียดและสถานะการใช้งานของห้อง</p>
                </div>
            </div>

            <form action="{{ route('backend.bookingmeeting.rooms.update', $room->room_id) }}" method="POST"
                enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Room Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชื่อห้องประชุม <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="room_name" value="{{ old('room_name', $room->room_name) }}" required
                            placeholder="เช่น Meeting Room 1, ห้องประชุมใหญ่"
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm @error('room_name') border-red-500 @enderror">
                        @error('room_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ความจุ (คน) <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user-group text-slate-400"></i>
                            </div>
                            <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" required
                                min="1" placeholder="จำนวนที่นั่ง"
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm @error('capacity') border-red-500 @enderror">
                        </div>
                        @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">สถานที่ / อาคาร</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-building text-slate-400"></i>
                            </div>
                            <input type="text" name="location" value="{{ old('location', $room->location) }}"
                                placeholder="เช่น อาคาร A, สำนักงานใหญ่"
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Floor -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ชั้น</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-stairs text-slate-400"></i>
                            </div>
                            <input type="text" name="floor" value="{{ old('floor', $room->floor) }}"
                                placeholder="เช่น ชั้น 2"
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">สถานะการใช้งาน <span
                                class="text-red-500">*</span></label>
                        <select name="status"
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm">
                            <option value="1" {{ old('status', $room->status) == 1 ? 'selected' : '' }}>เปิดใช้งาน (ให้จองได้)
                            </option>
                            <option value="0" {{ old('status', $room->status) == '0' ? 'selected' : '' }}>ปิดใช้งานชั่วคราว
                            </option>
                        </select>
                    </div>

                    <!-- Room Type -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">ประเภทห้อง</label>
                        <input type="text" name="room_type" value="{{ old('room_type', $room->room_type) }}"
                            placeholder="เช่น ห้องประชุมผู้บริหาร, ห้องประชุมแผนก"
                            class="w-full h-11 px-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm">
                    </div>

                    <!-- Equipment Options -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">อุปกรณ์อำนวยความสะดวก <span
                                class="text-xs text-slate-400 font-normal">(เลือกถ้ามี)</span></label>

                        <div class="flex flex-wrap gap-4">
                            <label
                                class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="has_projector" value="1" {{ old('has_projector', $room->has_projector) ? 'checked' : '' }}
                                    class="w-5 h-5 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                        <i class="fa-solid fa-display"></i>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">Projector / จอภาพ</span>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="checkbox" name="has_video_conf" value="1" {{ old('has_video_conf', $room->has_video_conf) ? 'checked' : '' }}
                                    class="w-5 h-5 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-pink-50 text-pink-500 flex items-center justify-center">
                                        <i class="fa-solid fa-video"></i>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700">Video Conference</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รายละเอียดเพิ่มเติม</label>
                        <textarea name="description" rows="3" placeholder="ข้อมูลอื่นๆ ของห้องประชุม เช่น กฎการใช้งาน ฯลฯ"
                            class="w-full p-4 rounded-xl border border-slate-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all text-sm">{{ old('description', $room->description) }}</textarea>
                    </div>

                    <!-- Upload Images -->
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">รูปภาพห้องประชุม <span
                                class="text-xs text-slate-400 font-normal">(อัปโหลดเพิ่มได้)</span></label>

                        @php
                            $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                        @endphp

                        <!-- Existing Images -->
                        @if(!empty($images) && is_array($images))
                            <div class="mb-4">
                                <p class="text-xs text-slate-500 mb-2">รูปภาพปัจจุบัน:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
                                            <div
                                                class="relative w-full h-24 rounded-lg overflow-hidden border border-slate-200 shadow-sm group">
                                                <img src="{{ $imagePathUrl }}" class="w-full h-full object-cover">
                                                <!-- Optionally add a delete button inside the form logic later -->
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-amber-400 hover:bg-amber-50/50 transition-colors cursor-pointer"
                            onclick="document.getElementById('file-upload').click()">
                            <div class="space-y-1 text-center">
                                <i class="fa-regular fa-images text-4xl text-slate-400"></i>
                                <div class="flex items-center justify-center text-sm text-slate-600 mt-2">
                                    <span
                                        class="relative rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-500">
                                        <span>เพิ่มรูปภาพใหม่</span>
                                        <input id="file-upload" name="image_file[]" type="file" class="sr-only" multiple
                                            accept="image/*" onchange="previewFiles()">
                                    </span>
                                    <p class="pl-1">หรือลากไฟล์มาวางที่นี่</p>
                                </div>
                                <p class="text-xs text-slate-500">PNG, JPG, GIF ไม่เกิน 2MB</p>
                            </div>
                        </div>

                        <!-- Preview Container -->
                        <div id="preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>

                        @error('image_file.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('backend.bookingmeeting.rooms.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-medium hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-amber-500 text-white font-medium hover:bg-amber-600 shadow-md shadow-amber-200 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> บันทึกการแก้ไข
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
                            imgDiv.className = 'relative w-full h-24 rounded-lg overflow-hidden border border-slate-200 shadow-sm';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'w-full h-full object-cover';

                            // Add overlay with filename
                            const overlay = document.createElement('div');
                            overlay.className = 'absolute bottom-0 inset-x-0 bg-black/50 text-white text-[10px] p-1 truncate';
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