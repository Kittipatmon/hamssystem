@extends('layouts.navmeeting.app')
@section('content')
  <div class="max-w-7xl mx-auto">

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success shadow-lg mb-4">
        <div>
          <i class="fa-solid fa-circle-check"></i>
          <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error shadow-lg mb-4">
        <div>
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>{{ session('error') }}</span>
        </div>
      </div>
    @endif

    <!-- Main Container -->
    <div class="flex flex-col-reverse lg:grid lg:grid-cols-4 gap-6">

      <!-- Left Column: Rooms Information (Bottom on Mobile) -->
      <div class="lg:col-span-1 space-y-4">
        <h2 class="text-xl font-bold text-slate-800"><i class="fa-solid fa-door-open text-[#c31919] mr-2"></i>
          ข้อมูลห้องประชุม</h2>
        @foreach($sidebarRooms as $room)
          <div class="card bg-white shadow-sm border border-slate-200 p-0 transition-transform hover:scale-[1.02]">
            <!-- Room Image -->
            <figure class="h-32 bg-slate-100 relative border-b border-slate-200 flex items-center justify-center">
              @php
                $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                $firstImage = !empty($images) && is_array($images) ? $images[0] : null;

                $imagePathUrl = null;
                if ($firstImage) {
                  if (file_exists(public_path('images/room/' . $firstImage))) {
                    $imagePathUrl = asset('images/room/' . $firstImage);
                  } elseif (file_exists(public_path('images/' . $firstImage))) {
                    $imagePathUrl = asset('images/' . $firstImage);
                  } elseif (file_exists(public_path($firstImage))) {
                    $imagePathUrl = asset($firstImage);
                  }
                }
              @endphp

              @if($imagePathUrl)
                <img src="{{ $imagePathUrl }}" alt="{{ $room->room_name }}" class="w-full h-full object-cover"
                  onerror="this.style.display='none'">
              @else
                <div class="text-slate-400 flex flex-col items-center">
                  <i class="fa-regular fa-image text-2xl mb-1"></i>
                  <span class="text-xs">ไม่มีรูปภาพ</span>
                </div>
              @endif
            </figure>

            <!-- Room Info -->
            <div class="p-4">
              <h3 class="font-bold text-[#c31919] uppercase text-sm mb-1">{{ $room->room_name }}</h3>
              <p class="text-[11px] text-slate-600 mb-2 truncate" title="{{ $room->room_type }}">
                {{ $room->room_type ?? 'ห้องประชุมขนาดเล็ก' }}
              </p>

              <div class="space-y-1 mb-3 text-xs text-slate-700">
                <p class="flex items-center"><i class="fa-solid fa-users w-5 text-center text-slate-400"></i>
                  <span>ความจุ: <span class="font-medium">{{ $room->capacity }}</span> ท่าน</span>
                </p>
                <p class="flex items-start"><i class="fa-solid fa-map-location-dot w-5 text-center text-slate-400 mt-1"></i>
                  <span class="break-words whitespace-normal flex-1">สิ่งอำนวยความสะดวก: {{ $room->location ?? '-' }}
                    @if($room->floor) (ชั้น {{ $room->floor }}) @endif</span>
                </p>
                @if($room->has_projector)
                  <p class="flex items-start"><i class="fa-solid fa-video w-5 text-center text-slate-400 mt-1"></i> <span
                class="break-words whitespace-normal flex-1">มีโปรเจคเตอร์</span></p>@endif
                @if($room->has_video_conf)
                  <p class="flex items-start"><i class="fa-solid fa-satellite-dish w-5 text-center text-slate-400 mt-1"></i>
                    <span class="break-words whitespace-normal flex-1">มี Video Conference</span>
                </p>@endif
              </div>

              @if($room->description)
                <div class="mt-2 text-xs text-slate-600 line-clamp-2" title="{{ $room->description }}">
                  <i class="fa-solid fa-circle-info text-slate-400 mr-1"></i> {{ $room->description }}
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Right Column: Calendar (Top on Mobile) -->
      <div class="lg:col-span-3 border border-slate-200 rounded-xl shadow-lg p-6 bg-slate-200/20">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold text-slate-800 flex items-center">
            <i class="fa-solid fa-calendar-days text-[#c31919] mr-2"></i> ปฏิทินจองห้องประชุม
          </h2>
          <button onclick="showBookingModal()"
            class="flex items-center gap-2 px-4 py-2 bg-[#c31919] hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md shadow-red-200 transition-all hover:scale-105 active:scale-95">
            <i class="fa-solid fa-calendar-plus"></i> จองห้องประชุม (คำร้อง)
          </button>
        </div>
        <div id='calendar'></div>
      </div>
    </div>

  </div>

  <!-- Draggable Floating Notification Box -->
  <div id="floating_notif"
    class="fixed bottom-6 right-6 z-[100] w-72 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-red-100 overflow-hidden select-none">
    <!-- Header (Drag Handle) -->
    <div id="notif_header"
      class="bg-gradient-to-r from-red-600 to-red-500 p-3 cursor-move flex justify-between items-center text-white">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-grip-lines opacity-70"></i>
        <span class="font-semibold text-[13px]">ประกาศแจ้งเตือน</span>
      </div>
      <button onclick="document.getElementById('floating_notif').style.display='none'"
        class="hover:bg-red-700/50 rounded-full w-6 h-6 flex items-center justify-center transition-colors">
        <i class="fa-solid fa-xmark text-sm"></i>
      </button>
    </div>
    <!-- Body -->
    <div class="p-4 bg-slate-50/50">
      <div class="flex items-start gap-3">
        <div class="bg-red-100 p-2 rounded-full text-red-600 mt-1 shrink-0">
          <i class="fa-solid fa-bullhorn focus:animate-pulse"></i>
        </div>
        <div>
          <h4 class="font-bold text-slate-800 text-sm mb-1">การใช้งานห้องประชุม</h4>
          <p class="text-xs text-slate-600 leading-relaxed">กรุณาจองห้องประชุมล่วงหน้าอย่างน้อย 1 วัน
            และหากต้องการยกเลิกกรุณาแจ้งฝ่ายอาคารสถานที่</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Booking Modal -->
  <dialog id="booking_modal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl p-6 relative overflow-y-auto overflow-x-hidden">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
        onclick="document.getElementById('booking_modal').close()"><i class="fa-solid fa-xmark"></i></button>
      <h3 class="font-bold text-lg mb-4 text-[#c31919] pb-2 border-b border-slate-100">
        <i class="fa-solid fa-calendar-plus mr-2"></i> แบบฟอร์มจองห้องประชุม (คำร้อง)
      </h3>

      <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
          <div class="alert alert-error text-sm text-white bg-red-500 rounded-lg p-3 mb-4 flex gap-2 w-full col-span-full">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <div>
              <p class="font-bold mb-1">เกิดข้อผิดพลาดในการบันทึกข้อมูล:</p>
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

          <!-- Column 1: Core Information -->
          <div class="space-y-3 p-4 bg-slate-50 rounded-xl border border-slate-100">
            <h4 class="font-bold text-slate-700 text-sm mb-2"><i class="fa-solid fa-circle-info text-slate-400 mr-1"></i>
              ข้อมูลหลักการจอง</h4>

            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700">ผู้จอง</span></label>
              <input type="text"
                value="{{ Auth::user() ? Auth::user()->employee_code . ' - ' . Auth::user()->first_name . ' ' . Auth::user()->last_name : 'Guest' }}"
                class="input input-sm input-bordered w-full bg-slate-200 text-slate-500 text-[13px]" readonly />
            </div>

            <div class="grid grid-cols-2 gap-3 mb-1">
              <div class="form-control">
                <label class="label py-1"><span class="label-text font-semibold text-[#c31919] font-bold"><i
                      class="fa-solid fa-calendar-day mr-1"></i>วันที่เริ่มจอง <span
                      class="text-red-500">*</span></span></label>
                <input type="date" id="reservation_date" name="reservation_date"
                  class="input input-sm input-bordered w-full border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                  value="{{ old('reservation_date') }}" required />
              </div>
              <div class="form-control">
                <label class="label py-1"><span class="label-text font-semibold text-[#c31919] font-bold"><i
                      class="fa-solid fa-calendar-check mr-1"></i>วันที่สิ้นสุด <span
                      class="text-red-500">*</span></span></label>
                <input type="date" id="reservation_dateend" name="reservation_dateend"
                  class="input input-sm input-bordered w-full border-red-200 focus:border-red-400 text-[13px] bg-white cursor-pointer shadow-sm"
                  value="{{ old('reservation_dateend') }}" required />
              </div>
            </div>

            <!-- เวลาที่จอง -->
            <div class="form-control mb-2 mt-3 p-3 bg-red-50/50 border border-red-100 rounded-xl">
              <label class="label pt-0 pb-2"><span class="label-text font-bold text-red-600"><i
                    class="fa-regular fa-clock mr-1"></i> ระบุเวลาที่ต้องการจอง <span
                    class="text-red-500">*</span></span></label>

              <div class="flex flex-col gap-4">
                <!-- แบบด่วน (ตรงกลาง) -->
                <div class="text-center mx-auto w-full sm:w-3/4 md:w-2/3">
                  <div class="text-[12px] text-slate-500 font-medium mb-1">เลือกช่วงเวลาแบบเร่งด่วน</div>
                  <select id="time_slot"
                    class="select select-bordered w-full border-red-200 h-[36px] min-h-[36px] px-3 focus:border-red-400 text-[13px] bg-white shadow-sm">
                    <option value="" disabled selected>-- กำหนดเวลาเอง --</option>
                    <option value="morning">ช่วงเช้า (08:30 - 12:00 น.)</option>
                    <option value="afternoon">ช่วงบ่าย (13:00 - 17:00 น.)</option>
                    <option value="fullday">เต็มวัน (08:30 - 17:00 น.)</option>
                  </select>
                </div>

                <!-- ระบุเอง (ตบลงมา 1 บรรทัด) -->
                <div class="text-center mx-auto w-full sm:w-3/4 md:w-2/3">
                  <div class="text-[12px] text-slate-500 font-medium mb-1">หรือระบุเวลาเอง</div>
                  <div class="flex items-center justify-center gap-3">
                    <!-- Start Time Picker -->
                    <div class="custom-time-picker" id="tp_start_container">
                      <div
                        class="time-display input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white shadow-sm"
                        onclick="toggleTimePicker('start')">
                        <span id="start_time_text">{{ old('start_time', '08:30') }}</span>
                        <i class="fa-regular fa-clock text-slate-400"></i>
                      </div>
                      <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time', '08:30') }}">

                      <div id="tp_start_dropdown" class="time-picker-dropdown">
                        <div class="tp-column">
                          <div class="tp-header" id="tp_start_h_header">08</div>
                          <div class="tp-options" id="tp_start_h_options">
                            @for ($i = 0; $i < 24; $i++)
                              <div class="tp-option" onclick="setTimePart('start', 'h', '{{ sprintf('%02d', $i) }}')">
                                {{ sprintf('%02d', $i) }}
                              </div>
                            @endfor
                          </div>
                        </div>
                        <div class="tp-column">
                          <div class="tp-header" id="tp_start_m_header">30</div>
                          <div class="tp-options" id="tp_start_m_options">
                            @for ($i = 0; $i < 60; $i += 1)
                              <div class="tp-option" onclick="setTimePart('start', 'm', '{{ sprintf('%02d', $i) }}')">
                                {{ sprintf('%02d', $i) }}
                              </div>
                            @endfor
                          </div>
                        </div>
                      </div>
                    </div>

                    <span class="text-slate-400 font-medium text-lg">-</span>

                    <!-- End Time Picker -->
                    <div class="custom-time-picker" id="tp_end_container">
                      <div
                        class="time-display input input-bordered w-full h-[36px] px-3 border-red-200 focus:border-red-400 text-[13px] bg-white shadow-sm"
                        onclick="toggleTimePicker('end')">
                        <span id="end_time_text">{{ old('end_time', '17:00') }}</span>
                        <i class="fa-regular fa-clock text-slate-400"></i>
                      </div>
                      <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time', '17:00') }}">

                      <div id="tp_end_dropdown" class="time-picker-dropdown">
                        <div class="tp-column">
                          <div class="tp-header" id="tp_end_h_header">17</div>
                          <div class="tp-options" id="tp_end_h_options">
                            @for ($i = 0; $i < 24; $i++)
                              <div class="tp-option" onclick="setTimePart('end', 'h', '{{ sprintf('%02d', $i) }}')">
                                {{ sprintf('%02d', $i) }}
                              </div>
                            @endfor
                          </div>
                        </div>
                        <div class="tp-column">
                          <div class="tp-header" id="tp_end_m_header">00</div>
                          <div class="tp-options" id="tp_end_m_options">
                            @for ($i = 0; $i < 60; $i += 1)
                              <div class="tp-option" onclick="setTimePart('end', 'm', '{{ sprintf('%02d', $i) }}')">
                                {{ sprintf('%02d', $i) }}
                              </div>
                            @endfor
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-red-600">ห้องประชุม <span
                    class="text-red-500">*</span></span></label>
              <select name="room_id"
                class="select select-sm select-bordered w-full border-red-200 focus:border-red-400 text-[13px]" required>
                <option value="" disabled {{ old('room_id') ? '' : 'selected' }}>-- เลือกห้องประชุม --</option>
                @foreach($rooms as $room)
                  <option value="{{ $room->room_id }}" {{ old('room_id') == $room->room_id ? 'selected' : '' }}>
                    {{ $room->room_name }} (ความจุ {{ $room->capacity }} ท่าน)
                  </option>
                @endforeach
              </select>
            </div>

            <!-- เลือกสี -->
            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700"><i
                    class="fa-solid fa-palette text-slate-400 mr-1"></i> เลือกสีแสดงในปฏิทิน</span></label>
              <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="selectColor('#dc2626')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#dc2626" title="แดง"></button>
                <button type="button" onclick="selectColor('#ea580c')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#ea580c" title="ส้ม"></button>
                <button type="button" onclick="selectColor('#ca8a04')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#ca8a04" title="เหลือง"></button>
                <button type="button" onclick="selectColor('#16a34a')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#16a34a" title="เขียว"></button>
                <button type="button" onclick="selectColor('#2563eb')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#2563eb" title="น้ำเงิน"></button>
                <button type="button" onclick="selectColor('#7c3aed')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#7c3aed" title="ม่วง"></button>
                <button type="button" onclick="selectColor('#db2777')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#db2777" title="ชมพู"></button>
                <button type="button" onclick="selectColor('#475569')"
                  class="color-swatch w-7 h-7 rounded-full border-2 border-transparent hover:scale-110 transition-transform"
                  style="background-color:#475569" title="เทา"></button>
                <input type="color" id="color_custom" class="w-7 h-7 rounded-full cursor-pointer border-0 p-0"
                  value="{{ old('color', '#dc2626') }}" title="เลือกสีเอง" onchange="selectColor(this.value)" />
              </div>
              <input type="hidden" name="color" id="event_color" value="{{ old('color', '#dc2626') }}" />
            </div>

            <!-- Subject/Topic -->
            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-red-600">หัวข้อการประชุม <span
                    class="text-red-500">*</span></span></label>
              <input type="text" name="topic" value="{{ old('topic') }}"
                class="input input-sm input-bordered w-full border-red-200 focus:border-red-400 text-[13px]"
                placeholder="ระบุหัวข้อประชุม" required />
            </div>

            <!-- Requester Name -->
            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700">ชื่อผู้เรียนเสนอ
                  (เรียนใคร)</span></label>
              <input type="text" name="requester_name" value="{{ old('requester_name') }}"
                class="input input-sm input-bordered w-full text-[13px]" placeholder="เช่น เรียน ผู้จัดการฝ่ายฯ" />
            </div>

            <!-- Participants -->
            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700">จำนวนผู้เข้าร่วม
                  (ท่าน)</span></label>
              <input type="number" name="participant_count" class="input input-sm input-bordered w-full text-[13px]"
                placeholder="ระบุจำนวนคน" min="1" value="{{ old('participant_count', 1) }}" />
            </div>
          </div>

          <!-- Column 2: Additional Details -->
          <div class="space-y-3 p-4 bg-white rounded-xl border border-slate-100">
            <h4 class="font-bold text-slate-700 text-sm mb-2"><i class="fa-solid fa-list-ul text-slate-400 mr-1"></i>
              รายละเอียดเพิ่มเติม</h4>

            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700">วัตถุประสงค์
                  (Objective)</span></label>
              <textarea name="objective" class="textarea textarea-bordered text-[13px] h-16"
                placeholder="ระบุวัตถุประสงค์การจองห้อง...">{{ old('objective') }}</textarea>
            </div>

            <div class="form-control mb-1">
              <label class="label py-1"><span class="label-text font-semibold text-slate-700">รายละเอียด
                  (Details)</span></label>
              <textarea name="details" class="textarea textarea-bordered text-[13px] h-16"
                placeholder="ระบุรายละเอียดเพิ่มเติม (ถ้ามี)...">{{ old('details') }}</textarea>
            </div>

            <!-- Catering Checkboxes -->
            <div class="mt-4 pt-2 border-t border-slate-100">
              <h5 class="font-semibold text-slate-700 text-xs mb-3"><i
                  class="fa-solid fa-utensils text-slate-400 mr-1"></i> การบริการอาหารและเครื่องดื่ม</h5>

              <div class="grid grid-cols-2 gap-3 text-[13px]">
                <div class="space-y-2">
                  <label class="cursor-pointer label justify-start gap-2 py-0">
                    <input type="checkbox" name="break_morning" value="1" class="checkbox checkbox-sm checkbox-error" {{ old('break_morning') ? 'checked' : '' }} />
                    <span class="label-text">เบรคเช้า</span>
                  </label>
                  <input type="text" name="break_morning_detail" value="{{ old('break_morning_detail') }}"
                    class="input input-xs input-bordered w-full text-[12px]" placeholder="รายละเอียดเบรคเช้า..." />
                </div>

                <div class="space-y-2">
                  <label class="cursor-pointer label justify-start gap-2 py-0">
                    <input type="checkbox" name="lunch" value="1" class="checkbox checkbox-sm checkbox-error" {{ old('lunch') ? 'checked' : '' }} />
                    <span class="label-text">อาหารกลางวัน</span>
                  </label>
                  <input type="text" name="lunch_detail" value="{{ old('lunch_detail') }}"
                    class="input input-xs input-bordered w-full text-[12px]" placeholder="รายละเอียดอาหารกลางวัน..." />
                </div>

                <div class="space-y-2 mt-2">
                  <label class="cursor-pointer label justify-start gap-2 py-0">
                    <input type="checkbox" name="break_afternoon" value="1" class="checkbox checkbox-sm checkbox-error" {{ old('break_afternoon') ? 'checked' : '' }} />
                    <span class="label-text">เบรคบ่าย</span>
                  </label>
                  <input type="text" name="break_afternoon_detail" value="{{ old('break_afternoon_detail') }}"
                    class="input input-xs input-bordered w-full text-[12px]" placeholder="รายละเอียดเบรคบ่าย..." />
                </div>

                <div class="space-y-2 mt-2">
                  <label class="cursor-pointer label justify-start gap-2 py-0">
                    <input type="checkbox" name="dinner" value="1" class="checkbox checkbox-sm checkbox-error" {{ old('dinner') ? 'checked' : '' }} />
                    <span class="label-text">อาหารเย็น</span>
                  </label>
                  <input type="text" name="dinner_detail" value="{{ old('dinner_detail') }}"
                    class="input input-xs input-bordered w-full text-[12px]" placeholder="รายละเอียดอาหารเย็น..." />
                </div>
              </div>
            </div>

            <!-- File Uploads -->
            <div class="mt-4 pt-3 border-t border-slate-100 space-y-3">
              <div class="form-control">
                <label class="label py-1"><span class="label-text font-semibold text-slate-700 text-xs"><i
                      class="fa-solid fa-paperclip mr-1"></i> เอกสารแนบ (Optional)</span></label>
                <input type="file" name="attached_file"
                  class="file-input file-input-bordered file-input-sm w-full text-[12px]"
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" />
              </div>
              <div class="form-control">
                <label class="label py-1"><span class="label-text font-semibold text-red-600 text-xs"><i
                      class="fa-solid fa-file-invoice-dollar mr-1"></i> ไฟล์งบประมาณ <span
                      class="text-red-500">*</span></span></label>
                <input type="file" name="budget_file"
                  class="file-input file-input-bordered file-input-error file-input-sm w-full text-[12px]"
                  accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png" required />
              </div>
            </div>

          </div>
        </div>

        <div class="modal-action mt-6 pt-4 border-t border-slate-100 col-span-full">
          <button type="button" class="btn btn-sm"
            onclick="document.getElementById('booking_modal').close()">ยกเลิก</button>
          <button type="submit"
            class="btn btn-sm bg-gradient-to-r from-[#e53935] to-[#c62828] hover:from-[#d32f2f] hover:to-[#b71c1c] text-white border-0 shadow-[0_4px_10px_rgba(229,57,53,0.3)]">
            <i class="fa-solid fa-paper-plane mr-1 text-[12px]"></i> เสนอใบคำร้องจองห้อง
          </button>
        </div>
      </form>
    </div>
  </dialog>

  <!-- Add FontAwesome since it may not be globally included -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Add SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Custom Calendar Styling */
    .fc {
      --fc-page-bg-color: transparent;
      --fc-neutral-bg-color: #f8fafc;
      --fc-neutral-text-color: #334155;
      --fc-border-color: #e2e8f0;
      --fc-today-bg-color: #fef2f2;
    }

    .fc .fc-col-header-cell-cushion {
      padding: 8px 4px;
      color: #475569;
      font-weight: 600;
    }

    .fc .fc-daygrid-day-number {
      color: #334155;
      font-weight: 500;
      padding: 4px;
      padding-right: 8px;
    }

    .fc .fc-button-primary {
      background-color: white;
      border-color: #cbd5e1;
      color: #334155;
      font-size: 0.875rem;
      text-transform: capitalize;
    }

    .fc .fc-button-primary:hover {
      background-color: #f8fafc;
      border-color: #94a3b8;
      color: #1e293b;
    }

    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
      background-color: #f1f5f9;
      border-color: #94a3b8;
      color: #0f172a;
    }

    .fc-event {
      border-radius: 6px;
      border: none;
      padding: 2px 4px;
      margin: 1px 2px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      cursor: pointer;
    }

    .fc-event-main {
      color: white;
      font-weight: 500;
      font-size: 0.75rem;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    .fc-daygrid-event-dot {
      border-color: white;
    }

    .fc .fc-toolbar-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #1e293b;
    }

    /* Custom Time Picker UI - Image 1 Style */
    .custom-time-picker {
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .time-display {
      display: flex;
      align-items: center;
      justify-content: space-between;
      cursor: pointer;
    }

    .time-picker-dropdown {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 0.75rem;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      display: none;
      padding: 10px;
      margin-top: 5px;
      min-width: 160px;
      left: 50%;
      transform: translateX(-50%);
    }

    .time-picker-dropdown.active {
      display: flex;
      gap: 10px;
    }

    .tp-column {
      flex: 1;
      display: flex;
      flex-direction: column;
      width: 60px;
    }

    .tp-header {
      background-color: #3b82f6;
      color: white;
      font-weight: bold;
      font-size: 1.125rem;
      padding: 8px 0;
      text-align: center;
      border-radius: 0.5rem;
      margin-bottom: 5px;
    }

    .tp-options {
      height: 180px;
      overflow-y: auto;
      scrollbar-width: none;
      /* Firefox */
      -ms-overflow-style: none;
      /* IE and Edge */
    }

    .tp-options::-webkit-scrollbar {
      display: none;
      /* Chrome, Safari and Opera */
    }

    .tp-option {
      padding: 8px 0;
      text-align: center;
      cursor: pointer;
      font-size: 0.875rem;
      color: #475569;
      border-radius: 0.375rem;
      transition: all 0.2s;
    }

    .tp-option:hover {
      background-color: #f1f5f9;
      color: #1e293b;
    }

    .tp-option.selected {
      background-color: #eff6ff;
      color: #3b82f6;
      font-weight: 600;
    }
  </style>

  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {

      const startInput = document.getElementById("start_time");
      const endInput = document.getElementById("end_time");

      // Function to open booking modal with date
      window.showBookingModal = function (dateStr) {
        const modal = document.getElementById('booking_modal');
        const startDateInput = document.getElementById('reservation_date');
        const endDateInput = document.getElementById('reservation_dateend');

        if (dateStr) {
          startDateInput.value = dateStr;
          endDateInput.value = dateStr;
        } else if (!startDateInput.value) {
          // Default to today if absolutely empty
          const today = new Date().toISOString().split('T')[0];
          startDateInput.value = today;
          endDateInput.value = today;
        }

        modal.showModal();
      };

      const timeSlotSelect = document.getElementById('time_slot');
      if (timeSlotSelect) {
        timeSlotSelect.addEventListener('change', function () {
          if (this.value === 'morning') {
            updateCustomTime('start', '08:30');
            updateCustomTime('end', '12:00');
          } else if (this.value === 'afternoon') {
            updateCustomTime('start', '13:00');
            updateCustomTime('end', '17:00');
          } else if (this.value === 'fullday') {
            updateCustomTime('start', '08:30');
            updateCustomTime('end', '17:00');
          }
        });
      }

      // Custom Time Picker Logic
      window.toggleTimePicker = function (type) {
        const dropdown = document.getElementById(`tp_${type}_dropdown`);
        const otherDropdown = type === 'start' ? document.getElementById('tp_end_dropdown') : document.getElementById('tp_start_dropdown');

        otherDropdown.classList.remove('active');
        dropdown.classList.toggle('active');

        if (dropdown.classList.contains('active')) {
          // Scroll to current values
          const currentTime = document.getElementById(`${type}_time`).value || (type === 'start' ? '08:30' : '17:00');
          const [h, m] = currentTime.split(':');
          scrollToValue(type, 'h', h);
          scrollToValue(type, 'm', m);
        }
      }

      window.setTimePart = function (type, part, value) {
        const header = document.getElementById(`tp_${type}_${part}_header`);
        header.innerText = value;

        const h = document.getElementById(`tp_${type}_h_header`).innerText;
        const m = document.getElementById(`tp_${type}_m_header`).innerText;
        const newTime = `${h}:${m}`;

        document.getElementById(`${type}_time`).value = newTime;
        document.getElementById(`${type}_time_text`).innerText = newTime;

        // Visual feedback
        const options = document.querySelectorAll(`#tp_${type}_${part}_options .tp-option`);
        options.forEach(opt => {
          opt.classList.toggle('selected', opt.innerText === value);
        });
      }

      function scrollToValue(type, part, value) {
        const optionsContainer = document.getElementById(`tp_${type}_${part}_options`);
        const options = optionsContainer.querySelectorAll('.tp-option');
        let target = null;

        options.forEach(opt => {
          if (opt.innerText === value) {
            opt.classList.add('selected');
            target = opt;
          } else {
            opt.classList.remove('selected');
          }
        });

        if (target) {
          optionsContainer.scrollTop = target.offsetTop - optionsContainer.offsetTop - 70;
        }

        document.getElementById(`tp_${type}_${part}_header`).innerText = value;
      }

      window.updateCustomTime = function (type, time) {
        if (!time) return;
        const [h, m] = time.split(':');
        document.getElementById(`${type}_time`).value = time;
        document.getElementById(`${type}_time_text`).innerText = time;
        document.getElementById(`tp_${type}_h_header`).innerText = h;
        document.getElementById(`tp_${type}_m_header`).innerText = m;
      }

      // Close pickers when clicking outside
      document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-time-picker')) {
          document.querySelectorAll('.time-picker-dropdown').forEach(d => d.classList.remove('active'));
        }
      });

      // Color Swatch Selection
      function selectColor(hex) {
        document.getElementById('event_color').value = hex;
        document.getElementById('color_custom').value = hex;
        document.querySelectorAll('.color-swatch').forEach(function (btn) {
          btn.classList.remove('ring-2', 'ring-offset-2', 'ring-slate-400');
          var r = parseInt(hex.slice(1, 3), 16);
          var g = parseInt(hex.slice(3, 5), 16);
          var b = parseInt(hex.slice(5, 7), 16);
          var rgbStr = 'rgb(' + r + ', ' + g + ', ' + b + ')';
          if (btn.style.backgroundColor === hex || btn.style.backgroundColor === rgbStr) {
            btn.classList.add('ring-2', 'ring-offset-2', 'ring-slate-400');
          }
        });
      }
      window.selectColor = selectColor;
      selectColor(document.getElementById('event_color').value || '#dc2626');

      // Auto-open modal or show sweet alerts if there are session messages or errors
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: 'สำเร็จ',
          text: '{{ session('success') }}',
          confirmButtonColor: '#e53935'
        });
      @endif

      @if(session('error'))
        Swal.fire({
          icon: 'error',
          title: 'ข้อผิดพลาด',
          text: '{{ session('error') }}',
          confirmButtonColor: '#e53935'
        });
      @endif

      @if($errors->any())
        Swal.fire({
          icon: 'error',
          title: 'ข้อมูลไม่ครบถ้วน',
          text: 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน เช่น ไฟล์งบประมาณ',
          confirmButtonColor: '#e53935'
        });

        // Restore date display
        const oldDate = document.getElementById('reservation_date').value;
        if (oldDate) {
          document.getElementById('reservation_date_display').value = oldDate;
        }

        document.getElementById('booking_modal').showModal();
      @endif

              var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th',
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek'
        },
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        },
        slotLabelFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        },
        events: '{{ route('reservations.events') }}',
        eventTextColor: '#ffffff',
        eventDisplay: 'block',
        height: 'auto',
        selectable: true,
        selectAllow: function (selectInfo) {
          // Can restrict past dates here if needed
          return true;
        },
        dateClick: function (info) {
          const clickedDate = new Date(info.dateStr);
          const today = new Date();
          today.setHours(0, 0, 0, 0);

          if (clickedDate < today) {
            Swal.fire({
              icon: 'error',
              title: 'ไม่สามารถจองย้อนหลังได้',
              text: 'กรุณาเลือกวันที่ปัจจุบันหรือวันที่ในอนาคต',
              confirmButtonColor: '#e53935'
            });
            return;
          }
          showBookingModal(info.dateStr);
        },
        eventClick: function (info) {
          const props = info.event.extendedProps;
          const startTimeFormatted = props.start_time_formatted || '-';
          const endTimeFormatted = props.end_time_formatted || '-';
          const timeStr = `${startTimeFormatted} - ${endTimeFormatted} น.`;

          const bookerName = props.first_name && props.last_name ? props.first_name + ' ' + props.last_name : '-';
          const topic = props.topic || 'ไม่ระบุ';
          const objective = props.objective || 'ไม่ระบุ';
          const details = props.details || 'ไม่ระบุ';
          const participantCount = props.participant_count || '-';
          const requesterName = props.requester_name || 'ไม่ระบุ';

          let cateringHtml = '';
          if (props.break_morning || props.lunch || props.break_afternoon || props.dinner) {
            cateringHtml = `
                      <div class="mt-3 p-3 bg-white rounded-lg border border-slate-100 text-left">
                        <p class="font-bold text-slate-800 border-b border-slate-50 mb-2 pb-1 text-xs uppercase tracking-wider flex items-center gap-1.5">
                          <i class="fa-solid fa-utensils text-slate-400"></i> การบริการอาหารและเครื่องดื่ม
                        </p>
                    `;
            if (props.break_morning) cateringHtml += `<p class="text-[12.5px] leading-relaxed"><span class="font-medium">• เบรคเช้า:</span> <span class="text-slate-600">${props.break_morning_detail || 'ตามความเหมาะสม'}</span></p>`;
            if (props.lunch) cateringHtml += `<p class="text-[12.5px] leading-relaxed"><span class="font-medium">• อาหารกลางวัน:</span> <span class="text-slate-600">${props.lunch_detail || 'ตามความเหมาะสม'}</span></p>`;
            if (props.break_afternoon) cateringHtml += `<p class="text-[12.5px] leading-relaxed"><span class="font-medium">• เบรคบ่าย:</span> <span class="text-slate-600">${props.break_afternoon_detail || 'ตามความเหมาะสม'}</span></p>`;
            if (props.dinner) cateringHtml += `<p class="text-[12.5px] leading-relaxed"><span class="font-medium">• อาหารเย็น:</span> <span class="text-slate-600">${props.dinner_detail || 'ตามความเหมาะสม'}</span></p>`;
            cateringHtml += '</div>';
          }

          const currentUserId = {{ Auth::id() ?? 'null' }};
          const isOwner = currentUserId === props.user_id;
          const eventEnd = info.event.end || info.event.start; // Fallback to start if end is not set (e.g. all-day event)
          const isEnded = new Date(eventEnd) < new Date();
          const canCancel = isOwner && !isEnded;

          Swal.fire({
            title: '<h2 class="text-3xl font-black text-slate-800 text-center mb-0">รายละเอียดการจองห้องประชุม</h2>',
            html: `
                <div class="mt-8 text-center space-y-2.5 text-slate-700">
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">ชื่อผู้จอง:</span> ${bookerName}</p>
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">เจ้าของงาน:</span> ${requesterName}</p>
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">วันที่เริ่ม:</span> ${startTimeFormatted} น.</p>
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">วันที่สิ้นสุด:</span> ${endTimeFormatted} น.</p>
                    
                    <p class="text-[22px] font-bold text-red-600 mt-4">
                        ห้องประชุม: ${info.event.title}
                    </p>
                    
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">หัวข้อ:</span> ${topic}</p>
                    <p class="text-lg text-slate-500 font-medium">${objective}</p>
                    
                    <p class="text-xl font-medium"><span class="font-bold text-slate-900">จำนวนผู้เข้าประชุม:</span> ${participantCount} ท่าน</p>
                    
                    <p class="text-xl font-bold text-emerald-600 mt-4 leading-none">
                        สถานะ: อนุมัติแล้ว
                    </p>
                    
                    <div class="mt-8 mb-6">
                        <div class="w-full bg-emerald-50 border border-emerald-100 py-3 rounded-xl flex items-center justify-center gap-2">
                             <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                             <span class="text-xl font-bold text-emerald-600">ปกติ / ยืนยันแล้ว</span>
                        </div>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'ปิด',
            confirmButtonColor: '#94a3b8',
            showDenyButton: canCancel,
            denyButtonText: '<i class="fa-solid fa-trash-can text-sm mr-1"></i> ยกเลิกการจอง',
            denyButtonColor: '#e53935',
            customClass: {
              popup: 'rounded-[2rem] shadow-2xl border border-slate-100 p-8',
              confirmButton: 'shadow-lg rounded-xl px-12 py-3 text-lg font-bold',
              denyButton: 'shadow-lg rounded-xl px-8 py-3 text-sm font-bold'
            }
          }).then((result) => {
            if (result.isDenied) {
              Swal.fire({
                title: 'ยืนยันการยกเลิก?',
                text: "คุณแน่ใจหรือไม่ที่จะยกเลิกการจองนี้?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53935',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'ใช่, ยกเลิกเลย',
                cancelButtonText: 'ปิดหน้าต่าง'
              }).then((res) => {
                if (res.isConfirmed) {
                  fetch(`/reservations/cancel/${info.event.id}`, {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                  })
                    .then(response => response.json())
                    .then(data => {
                      if (data.success) {
                        Swal.fire({
                          title: 'สำเร็จ!',
                          text: data.message,
                          icon: 'success',
                          confirmButtonText: 'ตกลง',
                          confirmButtonColor: '#e53935'
                        }).then(() => {
                          info.event.remove(); // Remove from calendar display
                        });
                      } else {
                        Swal.fire('เกิดข้อผิดพลาด', data.message || 'ไม่สามารถยกเลิกได้', 'error');
                      }
                    })
                    .catch(error => {
                      Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อระบบได้', 'error');
                    });
                }
              });
            }
          });
        }
      });
      calendar.render();

      // Dragging Logic for Notification Box
      var notifBox = document.getElementById('floating_notif');
      var notifHeader = document.getElementById('notif_header');
      var isDragging = false;
      var currentX;
      var currentY;
      var initialX;
      var initialY;
      var xOffset = 0;
      var yOffset = 0;

      notifHeader.addEventListener("mousedown", dragStart);
      document.addEventListener("mouseup", dragEnd);
      document.addEventListener("mousemove", drag);

      // Touch events for mobile
      notifHeader.addEventListener("touchstart", dragStart, { passive: false });
      document.addEventListener("touchend", dragEnd);
      document.addEventListener("touchmove", drag, { passive: false });

      function dragStart(e) {
        if (e.type === "touchstart") {
          initialX = e.touches[0].clientX - xOffset;
          initialY = e.touches[0].clientY - yOffset;
        } else {
          initialX = e.clientX - xOffset;
          initialY = e.clientY - yOffset;
        }

        if (e.target === notifHeader || notifHeader.contains(e.target)) {
          isDragging = true;
        }
      }

      function dragEnd(e) {
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
      }

      function drag(e) {
        if (isDragging) {
          e.preventDefault();

          if (e.type === "touchmove") {
            currentX = e.touches[0].clientX - initialX;
            currentY = e.touches[0].clientY - initialY;
          } else {
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;
          }

          xOffset = currentX;
          yOffset = currentY;

          setTranslate(currentX, currentY, notifBox);
        }
      }

      function setTranslate(xPos, yPos, el) {
        el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
      }
    });
  </script>
@endsection