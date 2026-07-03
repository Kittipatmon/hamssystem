@extends('layouts.sidebar')

@section('title', 'จัดการประกาศ / แจ้งให้ทราบ')

@section('content')
<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-6 font-noto">
        
        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (CLINICAL COMMAND STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border-l-4 border-l-red-600 border border-slate-300 dark:border-zinc-800 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded mb-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                    </span>
                    <span class="text-[10px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest">BULLETIN BOARD STATUS</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    จัดการประกาศข่าวสาร & แจ้งให้ทราบ
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    ลงทะเบียนประกาศใหม่และจัดการข่าวสารส่วนกลางเพื่อประชาสัมพันธ์ข้อมูลที่หน้าหลักของระบบ
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-5 py-3 rounded min-w-[160px]">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL POSTS</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white leading-none">
                                {{ $announcements->count() }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">ประกาศ</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('backend.announcement.create') }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded border border-red-700 text-sm font-bold shadow-sm transition-all duration-150 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>เพิ่มประกาศใหม่</span>
                </a>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             DATA TABLE (CLINICAL/LEDGER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-850 dark:bg-zinc-850 bg-slate-800 text-white border-b border-slate-300 dark:border-zinc-700">
                            <th class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider w-24 text-center border-r border-slate-700 dark:border-zinc-700">รูปภาพ</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider border-r border-slate-700 dark:border-zinc-700">หัวข้อประชาสัมพันธ์ / รายละเอียด</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider w-40 text-center border-r border-slate-700 dark:border-zinc-700">ระดับความสำคัญ</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider w-44 text-center border-r border-slate-700 dark:border-zinc-700 font-mono">วันที่เผยแพร่</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider w-36 text-center">เครื่องมือการจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($announcements as $item)
                            <tr class="odd:bg-white even:bg-slate-50/50 dark:odd:bg-zinc-900 dark:even:bg-zinc-900/40 hover:bg-red-50/10 dark:hover:bg-red-950/5 transition-colors">
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <div class="flex justify-center">
                                        @if($item->image_path)
                                            <img src="{{ asset($item->image_path) }}" class="w-12 h-12 rounded object-cover shadow-sm border border-slate-300 dark:border-zinc-700" alt="Thumbnail">
                                        @else
                                            <div class="w-12 h-12 rounded bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-400 border border-slate-300 dark:border-zinc-700">
                                                <i class="fa-solid fa-image text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white mb-1 leading-snug">{{ $item->title }}</div>
                                    <div class="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 leading-relaxed font-medium">{{ strip_tags($item->content) }}</div>
                                </td>
                                <td class="py-4 px-6 text-center border-r border-slate-200 dark:border-zinc-800">
                                    @if($item->is_urgent)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-red-50 text-red-800 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900 text-xs font-bold">
                                            <i class="fa-solid fa-triangle-exclamation mr-1.5 text-[10px]"></i> เร่งด่วน
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-slate-50 text-slate-800 dark:bg-zinc-800 dark:text-zinc-300 border border-slate-300 dark:border-zinc-700 text-xs font-bold">
                                            ทั่วไป
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="text-xs font-semibold text-slate-700 dark:text-zinc-300">
                                        <i class="fa-regular fa-calendar-days mr-1.5 opacity-60"></i>
                                        {{ $item->published_date ? $item->published_date->format('d/m/Y') : '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('backend.announcement.edit', $item) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-slate-300 dark:border-zinc-700 bg-white hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 dark:bg-zinc-800 dark:hover:bg-amber-950/30 dark:hover:border-amber-800 text-slate-600 dark:text-zinc-400 transition-colors"
                                            title="แก้ไขประกาศ">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('backend.announcement.destroy', $item) }}" method="POST" onsubmit="return confirm('คุณแน่ใจที่จะลบประกาศประชาสัมพันธ์นี้?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded border border-slate-300 dark:border-zinc-700 bg-white hover:bg-red-50 hover:text-red-700 hover:border-red-300 dark:bg-zinc-800 dark:hover:bg-red-950/30 dark:hover:border-red-800 text-slate-600 dark:text-zinc-400 transition-colors"
                                                title="ลบประกาศ">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900/40 italic font-bold">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-zinc-850 rounded border border-slate-300 dark:border-zinc-700 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-inbox text-slate-400 text-xl"></i>
                                    </div>
                                    ไม่พบข้อมูลประกาศในระบบ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
