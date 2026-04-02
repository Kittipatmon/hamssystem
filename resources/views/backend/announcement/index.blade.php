@extends('layouts.sidebar')

@section('title', 'จัดการประกาศ / แจ้งให้ทราบ')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6 px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Card -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-all hover:shadow-md">
            <div>
                <h2 class="text-2xl font-black text-zinc-900 dark:text-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500 flex items-center justify-center shadow-lg shadow-red-500/20">
                        <i class="fa-solid fa-bullhorn text-white text-lg"></i>
                    </div>
                    จัดการประกาศ / แจ้งให้ทราบ
                </h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 font-medium">จัดการข้อมูลประกาศที่แสดงในหน้าหลักของระบบ</p>
            </div>
            <a href="{{ route('backend.announcement.create') }}"
                class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl shadow-lg shadow-red-600/20 hover:shadow-red-600/40 transition-all duration-300 flex items-center justify-center gap-2 font-bold group">
                <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i>
                <span>เพิ่มประกาศใหม่</span>
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800">
                            <th class="px-6 py-4 text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center w-24">รูปภาพ</th>
                            <th class="px-6 py-4 text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest">หัวข้อประกาศ</th>
                            <th class="px-6 py-4 text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center">ความสำคัญ</th>
                            <th class="px-6 py-4 text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center">วันที่ประกาศ</th>
                            <th class="px-6 py-4 text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-widest text-center w-32">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($announcements as $item)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        @if($item->image_path)
                                            <img src="{{ asset($item->image_path) }}" class="w-12 h-12 rounded-lg object-cover shadow-sm border border-zinc-200 dark:border-zinc-800" alt="Thumbnail">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-400">
                                                <i class="fa-solid fa-image text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-zinc-900 dark:text-white group-hover:text-red-600 transition-colors">{{ $item->title }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 line-clamp-1 font-medium">{{ strip_tags($item->content) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->is_urgent)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold border bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800 uppercase tracking-tighter">
                                            เร่งด่วน
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold border bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-400 border-zinc-200 dark:border-zinc-700 uppercase tracking-tighter">
                                            ทั่วไป
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-xs font-bold text-zinc-600 dark:text-zinc-400">
                                        <i class="fa-regular fa-calendar-days mr-1.5 opacity-50"></i>
                                        {{ $item->published_date ? $item->published_date->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('backend.announcement.edit', $item) }}"
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm border border-amber-100 dark:border-amber-900/40 font-bold">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('backend.announcement.destroy', $item) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบประกาศนี้?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-sm border border-red-100 dark:border-red-900/40 font-bold">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-zinc-50 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-inbox text-zinc-300 text-2xl"></i>
                                        </div>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 font-bold italic">ไม่พบข้อมูลประกาศในขณะนี้</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
