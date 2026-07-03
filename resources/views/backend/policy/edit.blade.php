@extends('layouts.sidebar')

@section('title', 'แก้ไขข้อมูล' . ($policy->type === 'operation' ? 'ขั้นตอนการดำเนินงาน' : 'นโยบาย'))

@section('content')
<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto space-y-6 font-noto">
        
        <div class="flex items-center justify-between">
            <a href="{{ route('backend.policy.index', ['type' => $policy->type]) }}" 
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 rounded text-sm text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800/80 transition-colors font-bold">
                <i class="fa-solid fa-arrow-left text-xs"></i> 
                <span>กลับไปหน้ารายการ</span>
            </a>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="bg-slate-50 dark:bg-zinc-800 border-b border-slate-300 dark:border-zinc-700 px-6 py-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-red-600"></i>
                    <span>แก้ไขข้อมูล{{ $policy->type === 'operation' ? 'ขั้นตอนการดำเนินงาน' : 'นโยบาย' }}</span>
                </h2>
            </div>

            <form action="{{ route('backend.policy.update', $policy) }}" method="POST" class="p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">หัวข้อเรื่อง</label>
                    <input type="text" name="title" value="{{ old('title', $policy->title) }}" required
                        placeholder="พิมพ์หัวข้อเรื่องที่นี่..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">เนื้อหา / รายละเอียด</label>
                    <textarea name="content" rows="6"
                        placeholder="กรอกข้อมูลรายละเอียดของนโยบายหรือกระบวนการปฏิบัติงาน..."
                        class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">{{ old('content', $policy->content) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">ประเภทเอกสาร</label>
                        <select name="type" required
                            class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                            <option value="policy" {{ $policy->type === 'policy' ? 'selected' : '' }}>นโยบาย (Policy)</option>
                            <option value="operation" {{ $policy->type === 'operation' ? 'selected' : '' }}>ขั้นตอนการทำงาน (Operation)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">ลำดับการจัดเรียง (Order)</label>
                        <input type="number" name="order" value="{{ old('order', $policy->order) }}" min="0" required
                            class="w-full px-4 py-3 rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none transition-all text-sm text-slate-900 dark:text-white font-medium">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-zinc-800 flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded border border-red-700 text-sm shadow-sm transition-all flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>อัปเดตข้อมูลระบบ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
