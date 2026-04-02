<div class="py-12 flex flex-col items-center justify-center text-center animate-zoom-in">
    <div class="w-16 h-16 bg-zinc-50 dark:bg-zinc-900 rounded-full flex items-center justify-center mb-4 border border-zinc-100 dark:border-zinc-800 shadow-inner">
        <i class="fa-solid {{ $icon ?? 'fa-inbox' }} text-zinc-300 text-2xl"></i>
    </div>
    <span class="text-xs font-black text-zinc-400 uppercase tracking-widest leading-none">{{ $text ?? 'ไม่มีข้อมูล' }}</span>
    <p class="text-[9px] text-zinc-300 font-bold mt-2 uppercase tracking-tight">Try checking back later</p>
</div>
