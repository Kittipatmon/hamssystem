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
                $code = match ($type) {
                    'request' => $item->requests_code,
                    'agreement' => $item->agreement_code,
                    'guest' => $item->resident_guest_code,
                    'leave' => $item->residence_leaves_code,
                };
                $date = match ($type) {
                    'request' => $item->request_date,
                    'agreement' => $item->agreement_date,
                    'guest' => $item->request_date,
                    'leave' => $item->request_date,
                };
            @endphp
            <tr class="hover:bg-red-50/20 transition-colors">
                <td class="px-4 py-5">
                    <span
                        class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md">{{ $code }}</span>
                </td>
                <td class="px-4 py-5 text-slate-500 font-medium">
                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </td>
                <td class="px-4 py-5">
                    <span
                        class="px-3 py-1.5 rounded-xl text-[10px] font-black border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->send_status) }} shadow-sm">
                        <i class="fa-solid fa-circle-dot mr-1 animate-pulse"></i>
                        {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->send_status, $type) }}
                    </span>
                </td>
                <td class="px-4 py-5 text-center">
                    @php
                        $itemId = match ($type) {
                            'request' => $item->id,
                            'agreement' => $item->agreement_id,
                            'guest' => $item->resident_guest_id,
                            'leave' => $item->residence_leaves_id,
                        };

                        // Approval logic
                        $currentVal = null;
                        if ($item->send_status < 3) {
                            if ($type == 'leave') {
                                if ($item->send_status == 0) $currentVal = $item->managerhams_id;
                                elseif ($item->send_status == 2) $currentVal = $item->Committee_id;
                            } else {
                                if ($item->send_status == 0) $currentVal = $item->commander_id;
                                elseif ($item->send_status == 1) $currentVal = $item->managerhams_id;
                                elseif ($item->send_status == 2) $currentVal = $item->Committee_id;
                            }
                        }
                        $isCurrentApprover = (Auth::id() == $currentVal);
                        
                        // Hide buttons if specifically told we are in a tracking context and not pending
                        if (isset($is_pending) && !$is_pending) {
                            $isCurrentApprover = false;
                        }
                    @endphp

                    <div class="flex flex-col items-center gap-1">
                        @if($isCurrentApprover)
                            <span class="block text-[8px] text-red-500 font-bold mb-1 animate-bounce px-2 py-0.5 bg-red-50 rounded-full border border-red-100"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                            <div class="flex gap-1 mb-1" id="action-buttons-{{ $type }}-{{ $itemId }}">
                                <button type="button" 
                                    onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'approve', this)"
                                    class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center border border-emerald-200 transition-all shadow-sm" title="อนุมัติ">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </button>
                                <button type="button" 
                                    onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'correct', this)"
                                    class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white flex items-center justify-center border border-purple-200 transition-all shadow-sm" title="ส่งกลับแก้ไข">
                                    <i class="fa-solid fa-rotate-left text-xs"></i>
                                </button>
                                <button type="button" 
                                    onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'reject', this)"
                                    class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white flex items-center justify-center border border-red-200 transition-all shadow-sm" title="ไม่อนุมัติ">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </div>
                        @endif

                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('housing.request_detail', ['type' => $type, 'id' => $itemId]) }}"
                                class="inline-flex items-center justify-center bg-white hover:bg-red-50 text-slate-400 hover:text-red-500 w-8 h-8 rounded-full border border-slate-200 hover:border-red-200 transition-all shadow-sm"
                                title="ดูรายละเอียด">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            @php
                                $pdfRoute = match ($type) {
                                    'request' => route('housing.request.pdf', $itemId),
                                    'agreement' => route('housing.agreement.pdf', $itemId),
                                    'guest' => route('housing.guest.pdf', $itemId),
                                    'leave' => route('housing.leave.pdf', $itemId),
                                    default => null
                                };
                            @endphp
                            @if($pdfRoute)
                            <a href="{{ $pdfRoute }}" target="_blank" 
                            class="inline-flex items-center justify-center bg-white hover:bg-blue-50 text-slate-400 hover:text-blue-500 w-8 h-8 rounded-full border border-slate-200 hover:border-blue-200 transition-all shadow-sm" title="พิมพ์ PDF">
                                <i class="fa-solid fa-file-pdf text-xs"></i>
                            </a>
                            @endif
                            
                            @if($item->send_status == 4 && Auth::id() == $item->user_id)
                            @php
                                $editRoute = match ($type) {
                                    'request' => route('housing.request.edit', $itemId),
                                    'agreement' => route('housing.agreement.edit', $itemId),
                                    'guest' => route('housing.guest.edit', $itemId),
                                    'leave' => route('housing.leave.edit', $itemId),
                                    default => null
                                };
                            @endphp
                            @if($editRoute)
                            <a href="{{ $editRoute }}" 
                                class="inline-flex items-center justify-center bg-white hover:bg-amber-50 text-slate-400 hover:text-amber-500 w-8 h-8 rounded-full border border-slate-200 hover:border-amber-200 transition-all shadow-sm" title="แก้ไขข้อมูล">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </a>
                            @endif
                            @endif
                            
                            @if($item->send_status == 0)
                            <button type="button" 
                                onclick="confirmCancel('{{ $itemId }}', '{{ $code }}', '{{ $type }}')"
                                class="inline-flex items-center justify-center bg-white hover:bg-orange-50 text-slate-400 hover:text-orange-500 w-8 h-8 rounded-full border border-slate-200 hover:border-orange-200 transition-all shadow-sm" title="ยกเลิกคำขอ">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            <form id="cancel-form-{{ $type }}-{{ $itemId }}" action="{{ route('housing.destroy', ['type' => $type, 'id' => $itemId]) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>
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

@once
@push('scripts')
<script>
function confirmCancel(id, code, type) {
    Swal.fire({
        title: 'ยืนยันการยกเลิก?',
        text: "คุณต้องการยกเลิกคำขอหมายเลข " + code + " ใช่หรือไม่? การดำเนินการนี้ไม่สามารถย้อนกลับได้",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'ใช่, ยกเลิกเลย',
        cancelButtonText: 'ยกเลิก',
        reverseButtons: true,
        borderRadius: '1.5rem',
        customClass: {
            popup: 'rounded-[2rem]',
            confirmButton: 'rounded-xl px-6 py-3 font-bold',
            cancelButton: 'rounded-xl px-6 py-3 font-bold'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form-' + type + '-' + id).submit();
        }
    });
}
</script>
@endpush
@endonce