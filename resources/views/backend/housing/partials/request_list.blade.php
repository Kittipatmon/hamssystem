<table class="w-full text-sm">
    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest border-b border-slate-100">
        <tr>
            <th class="px-4 py-4 text-left">เลขที่คำขอ</th>
            <th class="px-4 py-4 text-left">วันที่ยื่นคำขอ</th>
            <th class="px-4 py-4 text-left">สถานะปัจจุบัน</th>
            <th class="px-4 py-4 text-center">จัดการ</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-50">
        @forelse($items as $item)
            @php
                $code = match($type) {
                    'request' => $item->requests_code,
                    'agreement' => $item->agreement_code,
                    'guest' => $item->resident_guest_code,
                    'leave' => $item->residence_leaves_code,
                };
                $date = match($type) {
                    'request' => $item->request_date,
                    'agreement' => $item->agreement_date,
                    'guest' => $item->request_date,
                    'leave' => $item->request_date,
                };
            @endphp
            <tr class="hover:bg-red-50/20 transition-colors">
                <td class="px-4 py-5">
                    <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md">{{ $code }}</span>
                </td>
                <td class="px-4 py-5 text-slate-500 font-medium">
                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </td>
                <td class="px-4 py-5">
                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->send_status) }} shadow-sm">
                        <i class="fa-solid fa-circle-dot mr-1 animate-pulse"></i>
                        {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->send_status) }}
                    </span>
                </td>
                <td class="px-4 py-5 text-center">
                    <button class="bg-white hover:bg-red-50 text-slate-400 hover:text-red-500 w-8 h-8 rounded-full border border-slate-200 hover:border-red-200 transition-all shadow-sm">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-4 py-20 text-center text-slate-300">
                    <div class="mb-3">
                        <i class="fa-regular fa-folder-open text-4xl"></i>
                    </div>
                    <p class="text-sm font-medium">ไม่มีรายการคำขอในหมวดนี้</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
