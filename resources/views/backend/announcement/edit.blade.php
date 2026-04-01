@extends('layouts.sidebar')

@section('title', 'แก้ไขประกาศ')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Breadcrumb & Header -->
    <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center bg-white dark:bg-zinc-950 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-sm gap-4 transition-all">
        <div>
            <h1 class="text-3xl font-black text-zinc-900 dark:text-white flex items-center justify-center sm:justify-start gap-4">
                <div class="p-2.5 bg-amber-500 rounded-xl shadow-xl shadow-amber-500/30">
                    <i class="fa-solid fa-pen-to-square text-white text-xl"></i>
                </div>
                <span>แก้ไขประกาศ</span>
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 font-medium">แก้ไขข้อมูลประกาศด้านล่างเพื่ออัปเดตข้อมูลลงในหน้าหลัก</p>
        </div>
        <a href="{{ route('backend.announcement.index') }}" 
           class="flex items-center gap-2 text-sm font-bold text-zinc-600 hover:text-red-600 transition-colors bg-zinc-100 dark:bg-zinc-900 px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800">
            <i class="fa-solid fa-arrow-left"></i>
            ย้อนกลับ
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-2xl overflow-hidden backdrop-blur-xl">
        <form action="{{ route('backend.announcement.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Title -->
                <div class="md:col-span-2 space-y-3">
                    <label for="title" class="text-sm font-black text-zinc-700 dark:text-zinc-300 flex items-center gap-2 px-1">
                        <i class="fa-solid fa-heading text-amber-500 text-xs"></i>
                        หัวข้อประกาศ <span class="text-red-500 font-black">*</span>
                    </label>
                    <input type="text" name="title" id="title" required placeholder="เช่น แจ้งปิดปรับปรุงระบบชั่วคราว..."
                        class="w-full bg-zinc-50 dark:bg-zinc-900 border-2 border-zinc-100 dark:border-zinc-800 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-5 py-4 transition-all duration-300 font-bold placeholder:text-zinc-400 dark:text-white"
                        value="{{ old('title', $announcement->title) }}">
                </div>

                <!-- Image -->
                <div class="space-y-3">
                    <label for="image" class="text-sm font-black text-zinc-700 dark:text-zinc-300 flex items-center gap-2 px-1">
                        <i class="fa-solid fa-image text-amber-500 text-xs"></i>
                        รูปภาพประกอบ
                    </label>
                    <input type="file" name="image" id="image" 
                        class="w-full bg-zinc-50 dark:bg-zinc-900 border-2 border-zinc-100 dark:border-zinc-800 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-5 py-3.5 transition-all duration-300 font-bold dark:text-white cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    @if($announcement->image_path)
                        <div class="mt-4 relative group w-48">
                            <img src="{{ asset($announcement->image_path) }}" alt="Preview" class="rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                <span class="text-white text-xs font-bold">รูปภาพปัจจุบัน</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Published Date -->
                <div class="space-y-3">
                    <label for="published_date" class="text-sm font-black text-zinc-700 dark:text-zinc-300 flex items-center gap-2 px-1">
                        <i class="fa-solid fa-calendar-check text-amber-500 text-xs"></i>
                        วันที่ประกาศ
                    </label>
                    <input type="date" name="published_date" id="published_date" required
                        class="w-full bg-zinc-50 dark:bg-zinc-900 border-2 border-zinc-100 dark:border-zinc-800 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-5 py-4 transition-all duration-300 font-bold dark:text-white cursor-pointer"
                        value="{{ old('published_date', $announcement->published_date ? $announcement->published_date->format('Y-m-d') : date('Y-m-d')) }}">
                </div>

                <!-- Content -->
                <div class="md:col-span-2 space-y-3">
                    <label for="content" class="text-sm font-black text-zinc-700 dark:text-zinc-300 flex items-center gap-2 px-1">
                        <i class="fa-solid fa-align-left text-amber-500 text-xs"></i>
                        เนื้อหาประกาศ
                    </label>
                    <textarea name="content" id="content" rows="6" placeholder="กรอกรายละเอียดประกาศที่ต้องการแจ้งให้พนักงานทราบ..."
                        class="w-full bg-zinc-50 dark:bg-zinc-900 border-2 border-zinc-100 dark:border-zinc-800 focus:border-amber-500 dark:focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 rounded-2xl px-5 py-4 transition-all duration-300 font-bold placeholder:text-zinc-400 dark:text-white resize-none">{{ old('content', $announcement->content) }}</textarea>
                </div>

                <!-- Urgent Status -->
                <div class="md:col-span-2 bg-zinc-50 dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-100 dark:border-zinc-800 flex items-center justify-between group transition-all hover:bg-amber-50/20 dark:hover:bg-amber-900/10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white dark:bg-zinc-800 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 group-hover:animate-pulse"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-black text-zinc-900 dark:text-white tracking-wide">เร่งด่วน</span>
                            <span class="text-xs text-zinc-500 font-bold">หากเป็นประกาศเร่งด่วน จะมีการเน้นสีให้เด่นชัดกว่าปกติ</span>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_urgent" class="sr-only peer" {{ $announcement->is_urgent ? 'checked' : '' }} value="1">
                        <div class="w-14 h-7 bg-zinc-200 peer-focus:outline-none dark:bg-zinc-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-zinc-300 after:border after:rounded-full after:h-[20px] after:w-[20px] after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" 
                    class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-amber-500/30 hover:shadow-amber-600/50 transform hover:-translate-y-1 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 text-lg group">
                    <i class="fa-solid fa-save transition-transform group-hover:scale-125"></i>
                    อัปเดตข้อมูลประกาศ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
