@extends('layouts.serviceitem.appservice')
@section('content')
    <div class="max-w-4xl mx-auto py-8 lg:py-18 px-4 space-y-8 uppercase tracking-tight">

        <!-- Header Section -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-slate-800 rounded-3xl flex items-center justify-center shadow-lg shadow-slate-200">
                    <i class="fa-solid fa-pen-to-square text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 leading-none">แก้ไขข้อมูลอุปกรณ์</h1>
                    <p class="text-[13px] text-slate-400 font-bold mt-1.5 uppercase">EDIT EXISTING ITEM CATALOG</p>
                </div>
                <a href="{{ route('items.index') }}"
                    class="ml-auto w-12 h-12 flex items-center justify-center bg-slate-50 text-slate-400 rounded-2xl hover:bg-slate-100 transition-all active:scale-95">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-2 border-red-100 p-6 rounded-[2rem] animate-zoom-in">
                <div class="flex items-center gap-3 text-red-600 mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <span class="font-black text-sm uppercase">พบข้อผิดพลาดในการกรอกข้อมูล</span>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-xs font-bold text-red-500 leading-relaxed uppercase">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Section -->
        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in"
            style="animation-delay: 0.1s">
            <form action="{{ route('items.update', $item->item_id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Item Code -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="item_code">รหัสพัสดุ (ITEM CODE)</label>
                        <input type="text" id="item_code" name="item_code" value="{{ old('item_code', $item->item_code) }}"
                            placeholder="เช่น HAMS-001"
                            class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none placeholder:text-slate-300"
                            required>
                    </div>

                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="name">ชื่อเรียกอุปกรณ์ (NAME)</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}"
                            placeholder="ระบุชื่อพัสดุอุปกรณ์"
                            class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none placeholder:text-slate-300"
                            required>
                    </div>

                    <!-- Type -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="type_id">ประเภทพัสดุ (CATEGORY)</label>
                        <div class="relative">
                            <select id="type_id" name="type_id"
                                class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none appearance-none cursor-pointer"
                                required>
                                <option value="">-- เลือกประเภทพัสดุ --</option>
                                @foreach ($items_types as $type)
                                    <option value="{{ $type->item_type_id }}" @selected(old('type_id', $item->type_id) == $type->item_type_id)>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="quantity">จำนวนคงเหลือ (QTY IN STOCK)</label>
                        <input type="number" id="quantity" name="quantity" min="0"
                            value="{{ old('quantity', $item->quantity) }}"
                            class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none"
                            required>
                    </div>

                    <!-- Price per Unit -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="per_unit">ราคาต่อหน่วย (UNIT PRICE)</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold">฿</span>
                            <input type="number" step="0.01" id="per_unit" name="per_unit"
                                value="{{ old('per_unit', $item->per_unit) }}" placeholder="0.00"
                                class="w-full h-14 pl-12 pr-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none placeholder:text-slate-300"
                                required>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                            for="item_pic">รูปภาพประกอบ (THUMBNAIL)</label>
                        <div class="flex items-center gap-4">
                            @if ($item->item_pic)
                                <div class="w-14 h-14 rounded-xl overflow-hidden border-2 border-slate-100 flex-shrink-0">
                                    <img src="{{ asset('images/items/' . $item->item_pic) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="relative flex-1">
                                <input type="file" id="item_pic" name="item_pic" accept="image/*"
                                    class="w-full h-14 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none file:hidden flex items-center pt-3.5 cursor-pointer">
                                <i
                                    class="fa-solid fa-cloud-arrow-up absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1"
                        for="description">รายละเอียดเพิ่มเติม (DESCRIPTION)</label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="ระบุคุณสมบัติหรือข้อมูลอื่นๆ ของพัสดุอุปกรณ์"
                        class="w-full p-6 bg-slate-50 border-2 border-slate-50 rounded-[2rem] text-slate-700 font-bold focus:bg-white focus:border-red-500 transition-all outline-none placeholder:text-slate-300 leading-relaxed">{{ old('description', $item->description) }}</textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4 pt-4 border-t border-slate-50 mt-12">
                    <button type="submit"
                        class="w-full sm:flex-[2] h-16 bg-slate-800 hover:bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-100 transition-all active:scale-95 flex items-center justify-center gap-3 group">
                        <i class="fa-solid fa-floppy-disk group-hover:scale-110 transition-transform"></i>
                        อัปเดตข้อมูลพัสดุ
                    </button>
                    <a href="{{ route('items.index') }}"
                        class="w-full sm:flex-1 h-16 bg-white border-2 border-slate-100 text-slate-400 font-black rounded-2xl hover:bg-slate-50 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-xmark"></i>
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.4s ease-out forwards;
        }

        /* Custom scrollbar for textarea */
        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: transparent;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #f1f5f9;
            border-radius: 10px;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #e2e8f0;
        }
    </style>
@endsection