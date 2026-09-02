@extends('layouts.parking.app')

@section('content')
<!-- Add jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<style>
    /* Custom Select2 Styling to match modern Tailwind UI */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single {
        border-color: #cbd5e1;
        border-radius: 0.75rem;
        height: 3rem;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
        box-shadow: none;
        transition: border-color 0.15s ease;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #ef4444 !important;
        outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1e293b;
        font-weight: 500;
        font-size: 0.95rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        right: 12px;
    }
    .select2-dropdown {
        border-color: #cbd5e1;
        border-radius: 0.75rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 4px;
        z-index: 9999;
    }
    .select2-search__field {
        border-radius: 0.5rem !important;
        border-color: #cbd5e1 !important;
        padding: 8px 12px !important;
        outline: none;
    }
    .select2-search__field:focus {
        border-color: #ef4444 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #ef4444 !important;
        color: white;
    }
    .select2-results__option {
        padding: 8px 16px;
        font-size: 0.95rem;
    }
</style>

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-square-plus text-red-600"></i> ลงทะเบียนจอดรถพนักงาน
                </h2>
                <p class="text-slate-500 mt-1 font-medium">บันทึกข้อมูลช่องจอดและทะเบียนรถของพนักงาน (รองรับการลงทะเบียนพร้อมกันหลายคน)</p>
            </div>
            <a href="{{ route('parking.employees.index') }}" class="btn btn-ghost border border-slate-200 bg-white hover:bg-slate-50 rounded-xl text-slate-600 font-bold px-6">
                <i class="fa-solid fa-arrow-left mr-2"></i> ย้อนกลับ
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-red-500 to-rose-600"></div>
            
            <form action="{{ route('parking.employees.store') }}" method="POST" class="p-8">
                @csrf
                
                @if($errors->any())
                    <div class="alert alert-error mb-6 rounded-xl bg-red-50 text-red-700 border border-red-200 p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                            <div>
                                <h4 class="font-bold mb-1">เกิดข้อผิดพลาดในการบันทึกข้อมูล</h4>
                                <ul class="list-disc pl-5 text-sm space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Excel Import Tool (Client Side Excel Parsing via SheetJS) -->
                <div class="mb-8 p-6 bg-slate-50 rounded-2xl border border-slate-200/60">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-file-excel text-emerald-600 text-lg"></i> นำเข้าข้อมูลด่วนผ่านไฟล์ Excel
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 font-medium">รองรับไฟล์ประเภท .xlsx, .xls และ .csv โดยระบบจะกรอกข้อมูลจากคอลัมน์ชื่อ, ทะเบียน และช่องจอดให้โดยอัตโนมัติ</p>
                        </div>
                        <span id="importStatus" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-200/70 text-slate-600">
                            ยังไม่ได้เลือกไฟล์
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-4 items-center">
                        <input type="file" id="excelFileInput" accept=".xlsx, .xls, .csv" class="file-input file-input-bordered file-input-sm rounded-xl w-full max-w-xs bg-white text-slate-600 font-bold border-slate-200" />
                        <div class="text-[11px] text-slate-400 font-semibold leading-relaxed">
                            💡 แนะนำหัวตาราง: <span class="bg-slate-200/40 text-slate-700 px-1 py-0.5 rounded font-bold">ชื่อพนักงาน</span> | <span class="bg-slate-200/40 text-slate-700 px-1 py-0.5 rounded font-bold">เลขทะเบียนรถ</span> | <span class="bg-slate-200/40 text-slate-700 px-1 py-0.5 rounded font-bold">เลขที่ช่องจอด</span>
                        </div>
                    </div>
                </div>

                <!-- Container of dynamic entries -->
                <div id="parkingsContainer">
                    
                    <!-- Entry 1 (Default) -->
                    <div class="parking-row border border-slate-200 rounded-2xl p-6 bg-slate-50/50 mb-6 relative transform transition-all duration-300" data-index="0">
                        <button type="button" class="remove-row btn btn-circle btn-xs absolute top-4 right-4 bg-red-50 border-none text-red-500 hover:bg-red-100" style="display:none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold text-slate-700">ชื่อพนักงาน <span class="text-red-500">*</span></span></label>
                                <select name="parkings[0][user_id]" class="user-select select select-bordered rounded-xl border-slate-200" required>
                                    <option value="">-- เลือกพนักงาน --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->fullname }} ({{ $user->dept_name ?? 'ไม่มีแผนก' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold text-slate-700">เลขทะเบียนรถ <span class="text-red-500">*</span></span></label>
                                <input type="text" name="parkings[0][car_registration]" class="input input-bordered rounded-xl border-slate-200 font-bold" placeholder="เช่น กข 1234 กทม" required>
                            </div>

                            <div class="form-control">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-2">
                                    <label class="label p-0"><span class="label-text font-bold text-slate-700">เลขที่ช่องจอด (1 - 74) <span class="text-red-500">*</span></span></label>
                                    <button type="button" onclick="openMapModal(this)" class="btn btn-xs bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg flex items-center justify-center gap-1 px-3 py-1.5 h-auto font-bold self-start sm:self-auto">
                                        <i class="fa-solid fa-map-location-dot"></i> แผนผัง
                                    </button>
                                </div>
                                <select name="parkings[0][slot_number]" class="select select-bordered rounded-xl border-slate-200 font-bold" required>
                                    <option value="">-- เลือกช่องจอด --</option>
                                    <optgroup label="ลานจอดรถสำนักงานใหญ่ (Outdoor)">
                                        @for($i = 1; $i <= 74; $i++)
                                            @php
                                                $isOccupied = in_array((string)$i, $occupiedSlots) || in_array($i, $occupiedSlots);
                                            @endphp
                                            <option value="{{ $i }}" {{ request()->query('slot') == (string)$i ? 'selected' : '' }} {{ $isOccupied ? 'disabled class=text-slate-400' : '' }}>
                                                ช่องจอดที่ {{ $i }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                            </option>
                                        @endfor
                                    </optgroup>
                                    <optgroup label="พื้นที่จอดรถในอาคาร (Indoor)">
                                        @foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] as $bay)
                                            @php $slotsCount = ($bay == 12) ? 1 : (($bay == 8) ? 2 : 3); @endphp
                                            @for($j = 1; $j <= $slotsCount; $j++)
                                                @php 
                                                    $slotVal = "B{$bay}_{$j}"; 
                                                    $isOccupied = in_array($slotVal, $occupiedSlots);
                                                @endphp
                                                <option value="{{ $slotVal }}" {{ request()->query('slot') == $slotVal ? 'selected' : '' }} {{ $isOccupied ? 'disabled class=text-slate-400' : '' }}>
                                                    ช่อง {{ $bay }} คันที่ {{ $j }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                                </option>
                                            @endfor
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Add Row Button -->
                <button type="button" id="addRowBtn" class="btn btn-ghost hover:bg-red-50 rounded-2xl text-red-600 font-bold border border-dashed border-red-300 w-full mb-6 py-4 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus-circle text-lg"></i> เพิ่มแถวลงทะเบียนพนักงานอีกคน
                </button>

                <!-- Actions -->
                <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                    <a href="{{ route('parking.employees.index') }}" class="btn btn-ghost rounded-xl text-slate-500 hover:bg-slate-100">ยกเลิก</a>
                    <button type="submit" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-8 border-none shadow-xl shadow-slate-200">
                        <i class="fa-solid fa-save mr-2"></i> บันทึกข้อมูลทั้งหมด
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        let index = 1;

        function initSelect2(element) {
            $(element).select2({
                placeholder: "-- เลือกพนักงาน --",
                allowClear: true
            });
        }

        // Initialize the default row
        initSelect2('.user-select');

        // Add Row Action
        $('#addRowBtn').click(function() {
            const container = $('#parkingsContainer');
            // Extract the original select options HTML
            const optionsHtml = $('.user-select').first().html();
            
            const newRow = `
            <div class="parking-row border border-slate-200 rounded-2xl p-6 bg-slate-50/50 mb-6 relative transform transition-all duration-300 scale-95 opacity-0" data-index="${index}">
                <button type="button" class="remove-row btn btn-circle btn-xs absolute top-4 right-4 bg-red-50 border-none text-red-500 hover:bg-red-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">ชื่อพนักงาน <span class="text-red-500">*</span></span></label>
                        <select name="parkings[index][user_id]" class="user-select select select-bordered rounded-xl border-slate-200" required>
                            ${optionsHtml}
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label"><span class="label-text font-bold text-slate-700">เลขทะเบียนรถ <span class="text-red-500">*</span></span></label>
                        <input type="text" name="parkings[index][car_registration]" class="input input-bordered rounded-xl border-slate-200 font-bold" placeholder="เช่น กข 1234 กทม" required>
                    </div>

                    <div class="form-control">
                        <div class="flex justify-between items-center mb-1">
                            <label class="label p-0"><span class="label-text font-bold text-slate-700">เลขที่ช่องจอด (1 - 74) <span class="text-red-500">*</span></span></label>
                            <button type="button" onclick="openMapModal(this)" class="btn btn-xs bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg flex items-center gap-1 px-2 font-bold">
                                <i class="fa-solid fa-map-location-dot"></i> แผนผัง
                            </button>
                        </div>
                        <select name="parkings[index][slot_number]" class="select select-bordered rounded-xl border-slate-200 font-bold" required>
                            <option value="">-- เลือกช่องจอด --</option>
                            <optgroup label="ลานจอดรถสำนักงานใหญ่ (Outdoor)">
                                @for($i = 1; $i <= 74; $i++)
                                    @php
                                        $isOccupied = in_array((string)$i, $occupiedSlots) || in_array($i, $occupiedSlots);
                                    @endphp
                                    <option value="{{ $i }}" {{ $isOccupied ? 'disabled class=text-slate-400' : '' }}>
                                        ช่องจอดที่ {{ $i }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                    </option>
                                @endfor
                            </optgroup>
                            <optgroup label="พื้นที่จอดรถในอาคาร (Indoor)">
                                @foreach([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] as $bay)
                                    @php $slotsCount = ($bay == 12) ? 1 : (($bay == 8) ? 2 : 3); @endphp
                                    @for($j = 1; $j <= $slotsCount; $j++)
                                        @php 
                                            $slotVal = "B{$bay}_{$j}"; 
                                            $isOccupied = in_array($slotVal, $occupiedSlots);
                                        @endphp
                                        <option value="{{ $slotVal }}" {{ $isOccupied ? 'disabled class=text-slate-400' : '' }}>
                                            ช่อง {{ $bay }} คันที่ {{ $j }} {{ $isOccupied ? '(มีรถจอดแล้ว)' : '' }}
                                        </option>
                                    @endfor
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                </div>
            </div>
            `.replace(/\[index\]/g, `[${index}]`);

            const $newRow = $(newRow);
            container.append($newRow);

            // Trigger animation
            setTimeout(() => {
                $newRow.removeClass('scale-95 opacity-0');
            }, 50);

            // Initialize Select2 on the new dropdown
            initSelect2($newRow.find('.user-select'));
            
            index++;
            toggleRemoveButtons();
        });

        // Excel file upload parser
        $('#excelFileInput').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const status = $('#importStatus');
            status.html('<i class="fa-solid fa-spinner fa-spin mr-1 text-blue-500"></i> กำลังนำเข้า...');
            status.removeClass('bg-slate-200/70 text-slate-600 bg-red-100 text-red-700 bg-emerald-100 text-emerald-800 bg-red-50 bg-emerald-50 bg-blue-50 text-blue-700 border border-blue-100');
            status.addClass('bg-blue-50 text-blue-700 border border-blue-100');

            const reader = new FileReader();
            reader.onload = function(evt) {
                try {
                    const data = new Uint8Array(evt.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    const json = XLSX.utils.sheet_to_json(worksheet, {header: 1});

                    if (json.length < 2) {
                        status.html('<i class="fa-solid fa-circle-xmark mr-1 text-red-500"></i> ไม่พบข้อมูลในไฟล์');
                        status.addClass('bg-red-50 text-red-700 border border-red-100');
                        return;
                    }

                    const headers = json[0].map(h => String(h || '').trim().toLowerCase());
                    console.log('Imported Excel Headers:', headers);
                    
                    let empNameIdx = headers.findIndex(h => h.includes('ชื่อ') || h.includes('name') || h.includes('พนักงาน'));
                    let carRegIdx = headers.findIndex(h => h.includes('ทะเบียน') || h.includes('plate') || h.includes('car') || h.includes('รถ'));
                    let slotIdx = headers.findIndex(h => h.includes('ช่อง') || h.includes('slot') || h.includes('ที่จอด'));

                    if (empNameIdx === -1) empNameIdx = 0;
                    if (carRegIdx === -1) carRegIdx = 1;
                    if (slotIdx === -1) slotIdx = 2;

                    const rows = json.slice(1);
                    let importedCount = 0;

                    rows.forEach((row, rIdx) => {
                        const empName = String(row[empNameIdx] || '').trim();
                        const carReg = String(row[carRegIdx] || '').trim();
                        const slotNum = String(row[slotIdx] || '').trim();

                        if (!empName && !carReg && !slotNum) return; // Skip empty row

                        alert(`Debug Row ${rIdx}: Name=${empName}, Car=${carReg}, Slot=${slotNum}`);

                        let targetIdx = importedCount;
                        if (targetIdx > 0) {
                            $('#addRowBtn').click();
                        }

                        // Populate the target row fields
                        setTimeout(() => {
                            try {
                                const rowContainer = $(`.parking-row[data-index="${targetIdx}"]`);
                                if (rowContainer.length) {
                                    console.log(`Populating row ${targetIdx} with Name: ${empName}, Car: ${carReg}, Slot: ${slotNum}`);
                                    
                                    // 1. Match employee name in select
                                    const userSelect = rowContainer.find('.user-select');
                                    if (userSelect.length && userSelect[0].options) {
                                        const options = Array.from(userSelect[0].options);
                                        const matchedOpt = options.find(o => o.textContent.toLowerCase().includes(empName.toLowerCase()));
                                        if (matchedOpt) {
                                            userSelect.val(matchedOpt.value).trigger('change');
                                        } else {
                                            console.warn(`No matching employee found for: ${empName}`);
                                        }
                                    }

                                    // 2. Populate plate number
                                    const carInput = rowContainer.find('input[name$="[car_registration]"]');
                                    if (carInput.length) {
                                        carInput.val(carReg);
                                    }

                                    // 3. Populate slot select
                                    const slotSelect = rowContainer.find('select[name$="[slot_number]"]');
                                    if (slotSelect.length && slotSelect[0].options) {
                                        const slotOpts = Array.from(slotSelect[0].options);
                                        const matchedSlotOpt = slotOpts.find(o => o.value.toLowerCase() === slotNum.toLowerCase() || o.textContent.toLowerCase().includes(slotNum.toLowerCase()));
                                        if (matchedSlotOpt) {
                                            slotSelect.val(matchedSlotOpt.value);
                                        } else {
                                            slotSelect.val(slotNum);
                                        }
                                        slotSelect.trigger('change');
                                    }
                                } else {
                                    console.error(`Row container not found for index ${targetIdx}`);
                                }
                            } catch (err) {
                                console.error(`Error populating row index ${targetIdx}:`, err);
                            }
                        }, targetIdx * 150); // Increased delay slightly to allow DOM layout & Select2 init

                        importedCount++;
                    });

                    setTimeout(() => {
                        status.html(`<i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i> สำเร็จ ${importedCount} รายการ`);
                        status.removeClass('bg-blue-50 text-blue-700 border-blue-100');
                        status.addClass('bg-emerald-50 text-emerald-800 border border-emerald-100');
                    }, (importedCount + 1) * 150);

                } catch(err) {
                    console.error(err);
                    status.html('<i class="fa-solid fa-circle-xmark mr-1 text-red-500"></i> เกิดข้อผิดพลาดในการประมวลผล');
                    status.addClass('bg-red-50 text-red-700 border border-red-100');
                }
            };
            reader.readAsArrayBuffer(file);
        });

        // Remove Row Action
        $(document).on('click', '.remove-row', function() {
            const row = $(this).closest('.parking-row');
            row.addClass('scale-95 opacity-0');
            setTimeout(() => {
                row.remove();
                toggleRemoveButtons();
            }, 300);
        });

        function toggleRemoveButtons() {
            const rows = $('.parking-row');
            if (rows.length > 1) {
                $('.remove-row').show();
            } else {
                $('.remove-row').hide();
            }
        }
    });
</script>

<style>
  #mapSelectorModal .board-hq {
    position: relative;
    width: 100%;
    aspect-ratio: 2200/530;
    background: #ffffff;
  }
  #mapSelectorModal .slot rect {
    fill: #ffffff;
    stroke: #c4d2e0;
    stroke-width: 1.5;
    cursor: pointer;
    transition: fill .2s;
  }
  #mapSelectorModal .slot text {
    fill: #5c7590;
    font-weight: 700;
    font-family: 'IBM Plex Mono', monospace;
    text-anchor: middle;
    dominant-baseline: middle;
    pointer-events: none;
  }
  #mapSelectorModal .slot.state-avail rect {
    fill: rgba(63, 168, 124, 0.15);
    stroke: #3fa87c;
    stroke-width: 2.2;
  }
  #mapSelectorModal .slot.state-avail:hover rect {
    fill: rgba(63, 168, 124, 0.3);
  }
  #mapSelectorModal .slot.state-avail text {
    fill: #3fa87c;
  }
  #mapSelectorModal .slot.state-occupied rect {
    fill: rgba(224, 112, 63, 0.15);
    stroke: #e0703f;
    stroke-width: 2.2;
    cursor: not-allowed;
  }
  #mapSelectorModal .slot.state-occupied text {
    fill: #e0703f;
  }
  #mapSelectorModal .slot.state-reserved rect {
    fill: rgba(168, 182, 196, 0.15);
    stroke: #a8b6c4;
    stroke-width: 2.2;
    cursor: not-allowed;
  }
  #mapSelectorModal .slot.state-reserved text {
    fill: #a8b6c4;
  }
  #mapSelectorModal .board-building {
    position: relative;
    width: 100%;
    min-width: 1100px;
    aspect-ratio: 1280/660;
    background: #ffffff;
    overflow: hidden;
  }
  #mapSelectorModal .pct {
    position: absolute;
  }
  #mapSelectorModal .bay {
    background: #ffffff;
    border: 1px solid #9aa2aa;
  }
  #mapSelectorModal .triple-bay {
    display: flex;
    align-items: flex-end;
    gap: 2%;
    height: 100%;
    padding: 20% 4% 5% 4%;
  }
  #mapSelectorModal .car-slot {
    flex: 1;
    height: 100%;
    border: 2px solid #6fbf6a;
    border-radius: 5px;
    background: rgba(111,191,106,.10);
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
  }
  #mapSelectorModal .car-slot:hover {
    filter: brightness(1.05);
  }
  #mapSelectorModal .car-slot.occupied {
    border-color: #e03b3b;
    background: rgba(224,59,59,.16);
    cursor: not-allowed;
  }
  #mapSelectorModal .badge {
    position: absolute;
    top: 6px;
    left: 6px;
    background: #2f8fd4;
    color: #fff;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    font-size: 13px;
    width: 22px;
    height: 22px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0,0,0,.25);
    z-index: 5;
  }
  #mapSelectorModal .room {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: .8rem;
    font-weight: 600;
    color: #1c3550;
    border: 1px solid #1c3550;
    padding: 4px;
  }
  #mapSelectorModal .room.gray { background: #c9ced3; }
  #mapSelectorModal .room.yellow { background: #f4d03f; }
  #mapSelectorModal .room.green { background: #6fbf6a; color: #fff; }
  #mapSelectorModal .room.blue { background: #2f8fd4; color: #fff; }
  #mapSelectorModal .room.lightblue { background: #bfe3f7; }
  #mapSelectorModal .room.exec-label {
    align-items: flex-end;
    padding-bottom: 6px;
    font-weight: 600;
    color: #1c3550;
    background: #bfe3f7;
  }
  #mapSelectorModal .vtext {
    writing-mode: vertical-rl;
    text-orientation: mixed;
  }
  #mapSelectorModal .title-tag {
    background: #2f8fd4;
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    padding: 6px 16px;
    border-radius: 0 0 0 8px;
  }
  #mapSelectorModal .stripe-red { background: #e03b3b; }
  #mapSelectorModal .stripe-green { background: #4caf50; }
  #mapSelectorModal .stair-hatch {
    background-image: repeating-linear-gradient(0deg, #d7dbdf 0 4px, #eceff1 4px 8px);
    border: 1px solid #1c3550;
  }
  #mapSelectorModal .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #e03b3b;
  }
</style>

<!-- Map Selector Modal -->
<div id="mapSelectorModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[999] flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0" id="mapModalContent">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-amber-500"></i> เลือกช่องจอดจากแผนผัง
                </h3>
                <p class="text-slate-500 text-xs mt-1 font-medium">กรุณาเลือกช่องจอดที่ว่าง (สีเขียว) จากแผนผังด้านล่าง</p>
            </div>
            <button type="button" onclick="closeMapModal()" class="btn btn-sm btn-circle btn-ghost text-slate-400 hover:text-slate-700">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="overflow-y-auto flex-1 bg-slate-100" style="padding: 0;">
            <!-- Modal Tab Switcher -->
            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex gap-2">
                <button type="button" onclick="switchMapTab('hq')" id="tab_hq" class="btn btn-xs sm:btn-sm rounded-xl font-bold bg-amber-500 text-white border-none shadow flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0">
                    ลานจอดรถสำนักงานใหญ่ (HQ)
                </button>
                <button type="button" onclick="switchMapTab('building')" id="tab_building" class="btn btn-xs sm:btn-sm rounded-xl font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0">
                    ในอาคารจอดรถ (Building)
                </button>
            </div>

            <!-- HQ Map Container -->
            <div id="map_hq_container" class="w-full h-[350px] md:h-[540px] bg-white shadow-inner overflow-hidden">
                <iframe src="{{ route('parking.map.full') }}?select_mode=1" class="w-full h-full border-none"></iframe>
            </div>

            <!-- Building Map Container -->
            <div id="map_building_container" class="w-full h-[350px] md:h-[540px] bg-white shadow-inner overflow-hidden hidden">
                <iframe src="{{ route('parking.map.building') }}?select_mode=1" class="w-full h-full border-none"></iframe>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 md:p-6 border-t border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50">
            <!-- Selected Info -->
            <div class="text-sm font-bold text-slate-700 text-center sm:text-left">
                สถานะการเลือก: <span id="modal_selected_slot_text" class="text-amber-600">ยังไม่ได้เลือกช่องจอด</span>
            </div>
            <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 justify-end">
                <button type="button" id="confirm_slot_btn" onclick="confirmSlotSelection()" class="btn bg-slate-900 hover:bg-slate-800 text-white rounded-xl px-6 border-none shadow w-full sm:w-auto" disabled>
                    ยืนยันเลือกช่องจอดนี้
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentSelectedSlotNumber = null;
let targetSelect = null;

// Listen for selection messages from iframes
window.addEventListener('message', function(event) {
    if (event.data && event.data.type === 'slot_selected') {
        const slotNumber = event.data.slot;
        selectSlotFromMap(slotNumber);
        confirmSlotSelection();
    }
});

function openMapModal(btn) {
    const row = btn.closest('.parking-row');
    targetSelect = row.querySelector('select[name$="[slot_number]"]');
    
    currentSelectedSlotNumber = null;
    document.getElementById('modal_selected_slot_text').textContent = 'ยังไม่ได้เลือกช่องจอด';
    document.getElementById('confirm_slot_btn').disabled = true;
    
    // Now open modal
    const modal = document.getElementById('mapSelectorModal');
    const content = document.getElementById('mapModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95', 'opacity-0');
    }, 50);
}

function closeMapModal() {
    const modal = document.getElementById('mapSelectorModal');
    const content = document.getElementById('mapModalContent');
    modal.classList.add('opacity-0');
    content.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function switchMapTab(tab) {
    const tabHq = document.getElementById('tab_hq');
    const tabBuilding = document.getElementById('tab_building');
    const hqContainer = document.getElementById('map_hq_container');
    const buildingContainer = document.getElementById('map_building_container');
    
    const activeClass = 'btn btn-xs sm:btn-sm rounded-xl font-bold bg-amber-500 text-white border-none shadow flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0 focus:outline-none';
    const inactiveClass = 'btn btn-xs sm:btn-sm rounded-xl font-bold bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 flex-1 text-[11px] sm:text-xs md:text-sm py-2.5 h-auto min-h-0 focus:outline-none';
    
    if (tab === 'hq') {
        tabHq.className = activeClass;
        tabBuilding.className = inactiveClass;
        hqContainer.classList.remove('hidden');
        buildingContainer.classList.add('hidden');
    } else {
        tabBuilding.className = activeClass;
        tabHq.className = inactiveClass;
        buildingContainer.classList.remove('hidden');
        hqContainer.classList.add('hidden');
    }
}

function selectSlotFromMap(slotNumber) {
    if (!targetSelect) return;
    
    currentSelectedSlotNumber = slotNumber;
    const infoText = document.getElementById('modal_selected_slot_text');
    infoText.innerHTML = `<span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-bold">ช่องจอด: ${slotNumber}</span>`;
    document.getElementById('confirm_slot_btn').disabled = false;
}

function confirmSlotSelection() {
    if (!currentSelectedSlotNumber || !targetSelect) return;
    
    targetSelect.value = currentSelectedSlotNumber;
    targetSelect.dispatchEvent(new Event('change'));
    
    closeMapModal();
}
</script>
@endsection
