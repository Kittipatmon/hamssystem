<div class="space-y-4">
    @foreach($items as $item)
        <div class="bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div class="flex-1 min-w-[200px]">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-emerald-100 italic">
                            #{{ $item->repair_code }}
                        </span>
                        <h4 class="font-bold text-slate-800 dark:text-white">{{ $item->title }}</h4>
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <i class="fa-solid fa-location-dot text-red-400 w-3 text-center"></i>
                            <span>ห้อง {{ $item->room->room_number ?? '-' }} ({{ $item->room->residence->name ?? '-' }})</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <i class="fa-solid fa-user text-blue-400 w-3 text-center"></i>
                            <span>ผู้แจ้ง: {{ $item->user->fullname ?? '-' }}</span>
                        </div>
                        <div class="mt-2 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl text-xs text-slate-600 dark:text-slate-400 border border-slate-100 dark:border-slate-800">
                            {{ $item->description }}
                        </div>
                        
                        @if($item->images)
                            <div class="flex gap-2 mt-2">
                                @foreach($item->images as $img)
                                    <a href="{{ asset($img) }}" target="_blank" class="w-12 h-12 rounded-lg border border-slate-200 overflow-hidden hover:scale-105 transition-transform shadow-sm">
                                        <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" 
                        onclick="finishRepairTask({{ $item->id }})"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-100 hover:shadow-xl transition-all flex items-center gap-2">
                        <i class="fa-solid fa-check-double text-sm"></i>
                        ถ่ายรูปและปิดงานซ่อม
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
