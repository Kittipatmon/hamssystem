@extends('layouts.sidebar')

@section('title', 'แก้ไขประกาศ')

@section('content')
<div class="min-h-screen bg-[#F8F8F9] dark:bg-[#161D31] px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto space-y-6 font-sans">
        
        <div class="flex items-center justify-between">
            <a href="{{ route('backend.announcement.index') }}" 
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-transparent border-0 rounded text-sm text-slate-500 hover:text-[#7367F0] hover:bg-[#7367F0]/10 transition-colors font-bold">
                <i class="fa-solid fa-arrow-left text-xs"></i> 
                <span>กลับไปหน้ารายการ</span>
            </a>
        </div>

        <div class="bg-white dark:bg-[#283046] border-0 rounded-lg shadow-sm overflow-hidden">
            <div class="bg-transparent border-b border-slate-200 dark:border-zinc-700 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-[#7367F0]"></i>
                    <span>แก้ไขประกาศประชาสัมพันธ์</span>
                </h2>
            </div>

            <form action="{{ route('backend.announcement.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">หัวข้อประกาศ *</label>
                    <input type="text" name="title" required
                        value="{{ old('title', $announcement->title) }}"
                        placeholder="พิมพ์หัวข้อหลักประชาสัมพันธ์ที่นี่..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-[#7367F0] focus:border-[#7367F0] outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">รูปภาพประกอบ</label>
                        <input type="file" name="image"
                            class="w-full px-3 py-2 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-[#7367F0] focus:border-[#7367F0] outline-none transition-all text-sm text-slate-900 dark:text-white font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded file:border file:border-slate-300 dark:file:border-zinc-700 file:text-xs file:font-semibold file:bg-white dark:file:bg-zinc-900 dark:file:text-white file:text-slate-700 hover:file:bg-slate-50">
                        
                        @if($announcement->image_path)
                            <div class="mt-3 relative group w-40 border border-slate-300 dark:border-zinc-700 rounded p-1 bg-slate-50 dark:bg-zinc-850">
                                <img src="{{ asset($announcement->image_path) }}" alt="Preview" class="rounded object-cover h-24 w-full">
                                <div class="text-[10px] text-center text-slate-500 font-bold mt-1">รูปภาพปัจจุบัน</div>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">วันที่เผยแพร่ประกาศ</label>
                        <input type="date" name="published_date" required
                            value="{{ old('published_date', $announcement->published_date ? $announcement->published_date->format('Y-m-d') : date('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-[#7367F0] focus:border-[#7367F0] outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">รายละเอียด / เนื้อหาประกาศ</label>
                    <textarea name="content" rows="6"
                        placeholder="พิมพ์เนื้อหาที่ต้องการแจ้งให้พนักงานทราบ..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-[#7367F0] focus:border-[#7367F0] outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">{{ old('content', $announcement->content) }}</textarea>
                </div>

                <div class="bg-transparent border border-slate-200 dark:border-zinc-700 p-5 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded border border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 flex items-center justify-center">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
                        </div>
                        <div>
                            <span class="block text-sm font-bold text-slate-800 dark:text-white">ประกาศระดับเร่งด่วน</span>
                            <span class="text-xs text-slate-500 dark:text-zinc-400">เน้นการแจ้งเตือนด้วยสีแดงบนหน้าหลักเพื่อให้สังเกตได้ง่าย</span>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_urgent" class="sr-only peer" {{ $announcement->is_urgent ? 'checked' : '' }} value="1">
                        <div class="relative w-16 h-6 bg-slate-200 dark:bg-slate-600 rounded-full peer peer-focus:outline-none peer-checked:bg-[#7367F0] transition-all duration-200 ease-in-out after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-[20px] after:shadow-sm"></div>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-zinc-800 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-[#7367F0] hover:bg-[#6357E0] text-white font-bold rounded border-0 text-sm shadow-md shadow-[#7367F0]/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>อัปเดตประกาศ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection