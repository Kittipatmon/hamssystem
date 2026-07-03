@extends('layouts.sidebar')

@section('title', 'เพิ่มประกาศใหม่')

@section('content')
<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto space-y-6 font-noto">
        
        <div class="flex items-center justify-between">
            <a href="{{ route('backend.announcement.index') }}" 
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 rounded text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800/80 transition-colors font-bold">
                <i class="fa-solid fa-arrow-left text-xs"></i> 
                <span>กลับไปหน้ารายการ</span>
            </a>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-red-600"></i>
                    <span>สร้างประกาศใหม่ประชาสัมพันธ์</span>
                </h2>
            </div>

            <form action="{{ route('backend.announcement.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">หัวข้อประกาศ *</label>
                    <input type="text" name="title" required
                        value="{{ old('title') }}"
                        placeholder="พิมพ์หัวข้อหลักประชาสัมพันธ์ที่นี่..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">รูปภาพประกอบ</label>
                        <input type="file" name="image"
                            class="w-full px-3 py-2 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded file:border file:border-slate-300 dark:file:border-zinc-700 file:text-xs file:font-semibold file:bg-white dark:file:bg-zinc-900 dark:file:text-white file:text-slate-700 hover:file:bg-slate-50">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">วันที่เผยแพร่ประกาศ</label>
                        <input type="date" name="published_date" required
                            value="{{ old('published_date', date('Y-m-d')) }}"
                            class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">รายละเอียด / เนื้อหาประกาศ</label>
                    <textarea name="content" rows="6"
                        placeholder="พิมพ์เนื้อหาที่ต้องการแจ้งให้พนักงานทราบ..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">{{ old('content') }}</textarea>
                </div>

                <div class="bg-slate-50 dark:bg-zinc-800/60 border border-slate-300 dark:border-zinc-700 p-5 rounded flex items-center justify-between">
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
                        <input type="checkbox" name="is_urgent" class="sr-only peer" value="1">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none dark:bg-zinc-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-zinc-800 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded border border-red-700 text-sm shadow-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>บันทึกและเผยแพร่</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
