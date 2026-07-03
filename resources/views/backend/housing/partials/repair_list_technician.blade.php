<div class="overflow-x-auto border border-slate-200 rounded-lg">
    <table class="clinical-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 120px;">Code</th>
                <th class="text-left" style="width: 180px;">ห้องพัก - โครงการ</th>
                <th class="text-left">หัวข้อ & รายละเอียด</th>
                <th class="text-left" style="width: 160px;">ผู้แจ้ง</th>
                <th class="text-center" style="width: 140px;">จัดการงาน</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td class="font-mono text-xs font-bold text-slate-800">
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 border border-slate-200 font-mono">
                            #{{ $item->repair_code }}
                        </span>
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700">ห้อง {{ $item->room->room_number ?? '-' }}</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">{{ $item->room->residence->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1">
                            <span class="font-bold text-slate-800">{{ $item->title }}</span>
                            <div class="p-2 bg-slate-50 border border-slate-200 rounded text-xs text-slate-600">
                                {{ $item->description }}
                            </div>
                            @if($item->images)
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @foreach($item->images as $img)
                                        <a href="{{ asset($img) }}" target="_blank" class="w-8 h-8 rounded overflow-hidden border border-slate-200 shadow-sm hover:scale-105 transition-all">
                                            <img src="{{ asset($img) }}" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="text-xs font-bold text-slate-700">{{ $item->user->fullname ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @if($item->status == 1)
                            <button onclick="finishRepairTask({{ $item->id }})" 
                                class="h-8 px-3 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-sm transition-all w-full">
                                <i class="fa-solid fa-check-double text-xs"></i> ปิดงานซ่อม
                            </button>
                        @else
                            <span class="text-xs font-bold text-slate-400">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                        ไม่มีงานซ่อมบำรุงที่ได้รับมอบหมาย
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
