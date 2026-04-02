<div class="p-5 bg-zinc-50/50 dark:bg-zinc-900/50 rounded-2xl border-l-4 {{ $statusType == 'pending' ? 'border-amber-500' : ($statusType == 'success' ? 'border-emerald-500' : 'border-red-500') }} transition-all hover:bg-white dark:hover:bg-zinc-900/20 hover:shadow-md group">
    <div class="flex justify-between items-start mb-3">
        <div class="flex-1">
            <span class="text-[10px] font-black {{ $statusType == 'pending' ? 'text-amber-500' : ($statusType == 'success' ? 'text-emerald-500' : 'text-red-500') }} uppercase tracking-[0.15em] block leading-none mb-1">
                @if($type == 'requisitions') REQ-{{ $item->requisitions_code ?? $item->requisitions_id }}
                @elseif($type == 'reservations') RES-{{ $item->reservation_code }}
                @elseif($type == 'vehicleBookings') VEH-{{ $item->booking_code }}
                @elseif($type == 'repairs') RPR-{{ $item->repair_code }}
                @endif
            </span>
            <h3 class="text-[13px] font-black text-zinc-900 dark:text-white transition-colors uppercase leading-tight line-clamp-1 w-full truncate">
                @if($type == 'requisitions') คำขอเบิกพัสดุ
                @elseif($type == 'reservations') {{ $item->topic }}
                @elseif($type == 'vehicleBookings') {{ $item->purpose }}
                @elseif($type == 'repairs') {{ $item->title }}
                @endif
            </h3>
        </div>
        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tighter border {{ $statusType == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : ($statusType == 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100') }}">
            @if($type == 'requisitions') {{ $item->status_label ?? $item->status }}
            @else {{ $item->status ?? 'เสร็จสิ้น' }}
            @endif
        </span>
    </div>

    {{-- Details Row --}}
    <div class="space-y-2 mt-4">
        @if($type == 'reservations')
            <p class="text-[11px] text-zinc-500 font-bold uppercase tracking-wider flex items-center gap-1.5 px-3 py-2 bg-emerald-50/20 dark:bg-emerald-900/10 rounded-lg">
                <span class="text-zinc-600 dark:text-zinc-400">ห้อง:</span> {{ $item->room->room_name ?? 'ROOM' }}
            </p>
        @elseif($type == 'vehicleBookings')
            <p class="text-[11px] text-zinc-500 font-bold uppercase tracking-wider flex items-center gap-1.5 px-3 py-2 bg-amber-50/20 dark:bg-amber-900/10 rounded-lg">
                <span class="text-zinc-600 dark:text-zinc-400">ไป:</span> {{ $item->destination }} ({{ $item->province }})
            </p>
            <p class="text-[11px] text-zinc-400 font-bold uppercase tracking-widest flex items-center gap-2 mt-2 px-1">
                <span class="text-zinc-500">รถ:</span> {{ $item->vehicle->car_brand ?? '-' }} {{ $item->vehicle->car_license ?? '-' }}
            </p>
        @elseif($type == 'repairs')
            <p class="text-[11px] text-zinc-500 font-bold uppercase tracking-wider flex items-center gap-1.5 px-3 py-2 bg-purple-50/20 dark:bg-purple-900/10 rounded-lg">
                <span class="text-zinc-600 dark:text-zinc-400">พิกัด:</span> {{ $item->room->room_number ?? 'HOME' }}
            </p>
        @elseif($type == 'requisitions')
             <p class="text-[11px] text-zinc-500 font-bold uppercase tracking-wider flex items-center gap-1.5 px-3 py-2 bg-blue-50/20 dark:bg-blue-900/10 rounded-lg">
                <span class="text-zinc-600 dark:text-zinc-400">มูลค่า:</span> ฿{{ number_format($item->total_price ?? 0, 2) }}
            </p>
        @endif

        <div class="flex items-center justify-between text-[10px] text-zinc-400 font-black uppercase tracking-widest pt-2 px-1">
            <div class="flex items-center gap-1.5">
                <span class="text-zinc-500 font-bold">วันที่:</span>
                {{ $item->created_at->format('d M / H:i น.') }}
            </div>
            @if($type == 'reservations' || $type == 'vehicleBookings')
                <div class="flex items-center gap-1.5 text-zinc-300 decoration-zinc-200">
                    <span class="text-zinc-500 font-bold">เวลา:</span>
                    {{ $item->start_time ?? '-' }} - {{ $item->end_time ?? '-' }}
                </div>
            @endif
        </div>
    </div>
</div>
