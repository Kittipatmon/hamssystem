@extends('layouts.sidebar')

@section('title', $type === 'policy' ? 'จัดการนโยบาย' : 'จัดการขั้นตอนการดำเนินงาน')

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
                    <span class="text-[10px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest">SYSTEM DATA CONTROL</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    {{ $type === 'policy' ? 'จัดการนโยบายระบบ (Policies)' : 'จัดการขั้นตอนการดำเนินงาน (Operations)' }}
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    จัดการบันทึกและปรับปรุงข้อมูล{{ $type === 'policy' ? 'นโยบายส่วนกลาง' : 'ขั้นตอนการทำงานมาตรฐาน' }} ที่แสดงในหน้าบริการระบบหลัก
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-5 py-3 rounded min-w-[150px]">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL RECORDS</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white leading-none">
                                {{ $policies->count() }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">รายการ</span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('backend.policy.create', ['type' => $type]) }}"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded border border-red-700 text-sm font-bold shadow-sm transition-all duration-150 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>เพิ่มข้อมูลใหม่</span>
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
                            <th class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider w-20 text-center border-r border-slate-700 dark:border-zinc-700">ลำดับ</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider border-r border-slate-700 dark:border-zinc-700">หัวข้อ / รายละเอียดสำคัญ</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider w-44 text-center border-r border-slate-700 dark:border-zinc-700">ประเภทข้อมูล</th>
                            <th class="py-3.5 px-6 text-xs font-bold uppercase tracking-wider w-36 text-center">เครื่องมือการจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($policies as $item)
                            <tr class="odd:bg-white even:bg-slate-50/50 dark:odd:bg-zinc-900 dark:even:bg-zinc-900/40 hover:bg-red-50/10 dark:hover:bg-red-950/5 transition-colors">
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="text-sm font-bold text-slate-700 dark:text-zinc-300">
                                        #{{ $item->order }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white mb-1 leading-snug">{{ $item->title }}</div>
                                    <div class="text-xs text-slate-500 dark:text-zinc-400 line-clamp-2 leading-relaxed font-medium">{{ strip_tags($item->content) }}</div>
                                </td>
                                <td class="py-4 px-6 text-center border-r border-slate-200 dark:border-zinc-800">
                                    @if($item->type === 'policy')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-blue-50 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900 text-xs font-bold">
                                            <i class="fa-solid fa-scroll mr-1.5 text-[10px]"></i> นโยบาย
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 text-xs font-bold">
                                            <i class="fa-solid fa-list-check mr-1.5 text-[10px]"></i> การดำเนินงาน
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('backend.policy.edit', $item) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded border border-slate-300 dark:border-zinc-700 bg-white hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 dark:bg-zinc-800 dark:hover:bg-amber-950/30 dark:hover:border-amber-800 text-slate-600 dark:text-zinc-400 transition-colors"
                                            title="แก้ไขข้อมูล">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('backend.policy.destroy', $item) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข้อมูลนี้ออกจากระบบ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded border border-slate-300 dark:border-zinc-700 bg-white hover:bg-red-50 hover:text-red-700 hover:border-red-300 dark:bg-zinc-800 dark:hover:bg-red-950/30 dark:hover:border-red-800 text-slate-600 dark:text-zinc-400 transition-colors"
                                                title="ลบข้อมูล">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900/40 italic font-bold">
                                    <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                                    ไม่พบข้อมูลใดๆ ในระบบ
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
