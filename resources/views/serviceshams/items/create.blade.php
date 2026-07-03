@extends('layouts.serviceitem.appservice')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 text-xs font-semibold">
        <!-- Breadcrumb -->
        <div class="text-xs breadcrumbs text-slate-500 px-2">
            <ul>
                <li><a href="{{ route('items.index') }}" class="hover:text-red-700 font-semibold"><i
                            class="fa-solid fa-boxes-stacked mr-2"></i> คลังอุปกรณ์</a></li>
                <li class="text-slate-800 font-bold">เพิ่มพัสดุอุปกรณ์ใหม่</li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 p-4 rounded text-xs">
                <div class="flex items-center gap-2 text-red-600 mb-2">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span class="font-bold uppercase">พบข้อผิดพลาดในการบันทึกข้อมูล</span>
                </div>
                <ul class="list-disc pl-5 space-y-1 text-red-500">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-slate-50 border-b border-slate-200 p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-red-50 text-red-600 flex items-center justify-center text-lg shadow-inner">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">ลงทะเบียนบันทึกอุปกรณ์ใหม่</h2>
                        <p class="text-[11px] text-slate-400 font-semibold mt-0.5">กรอกข้อมูลเพื่อขึ้นทะเบียนพัสดุชิ้นใหม่ลงในระบบพัสดุกลาง</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Item Code -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="item_code">รหัสพัสดุ (Item Code) <span class="text-red-500">*</span></label>
                        <input type="text" id="item_code" name="item_code" value="{{ old('item_code') }}" required
                            placeholder="เช่น HAMS-001"
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="name">ชื่อเรียกพัสดุอุปกรณ์ <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="ระบุชื่อพัสดุอุปกรณ์"
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="type_id">หมวดหมู่ประเภทพัสดุ <span class="text-red-500">*</span></label>
                        <select id="type_id" name="type_id" required
                            class="w-full h-9 px-3 bg-white border border-slate-300 rounded focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-bold text-slate-800">
                            <option value="">-- เลือกประเภทพัสดุ --</option>
                            @foreach ($items_types as $type)
                                <option value="{{ $type->item_type_id }}" @selected(old('type_id') == $type->item_type_id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="quantity">จำนวนเริ่มต้นในคลังสต็อก <span class="text-red-500">*</span></label>
                        <input type="number" id="quantity" name="quantity" min="0" value="{{ old('quantity', 0) }}" required
                            class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-semibold text-slate-800">
                    </div>

                    <!-- Price per Unit -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="per_unit">ราคากลางต่อหน่วย (บาท) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">฿</span>
                            <input type="number" step="0.01" id="per_unit" name="per_unit" value="{{ old('per_unit') }}" required
                                placeholder="0.00"
                                class="w-full h-9 pl-8 pr-3 rounded border border-slate-300 bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-semibold text-slate-800">
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5" for="item_pic">ภาพพัสดุอุปกรณ์ประกอบ</label>
                        <input type="file" id="item_pic" name="item_pic" accept="image/*"
                            class="file-input file-input-bordered file-input-sm w-full text-slate-800">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5" for="description">ระบุข้อมูลจำเพาะ / คำอธิบายเพิ่มเติม</label>
                    <textarea id="description" name="description" rows="3"
                        placeholder="ระบุคุณสมบัติ ยี่ห้อ ขนาด หรือเงื่อนไขเพิ่มเติมของพัสดุ..."
                        class="w-full p-3 rounded border border-slate-300 bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all font-semibold text-slate-800 leading-normal">{{ old('description') }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="pt-5 border-t border-slate-200 flex items-center justify-end gap-3 font-semibold text-xs">
                    <a href="{{ route('items.index') }}"
                        class="px-5 py-2 rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">
                        ยกเลิก
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded shadow transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-save"></i> บันทึกข้อมูลพัสดุ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection