<div class="overflow-x-auto border border-slate-200 rounded-lg">
    <table class="clinical-table">
        <thead>
            <tr>
                <th class="text-left" style="width: 140px;">เลขที่คำขอ</th>
                <th class="text-left" style="width: 140px;">วันที่ยื่นคำขอ</th>
                <th class="text-center" style="width: 180px;">สถานะปัจจุบัน</th>
                <th class="text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
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
                <tr>
                    <td class="font-mono text-xs font-bold text-slate-800">
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 border border-slate-200 font-mono">
                            {{ $code }}
                        </span>
                    </td>
                    <td class="font-mono text-xs text-slate-600 font-bold">
                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->send_status) }} whitespace-nowrap">
                            <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                            <span>{{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusShortLabel($item->send_status, $type) }}</span>
                        </span>
                    </td>
                    <td class="text-center">
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

                        <div class="flex flex-col items-center gap-1.5">
                            @if($isCurrentApprover)
                                <span class="block text-[9px] text-red-600 font-black animate-pulse px-2 py-0.5 bg-red-50 rounded border border-red-200"><i class="fa-solid fa-circle-exclamation"></i> ให้คุณพิจารณา</span>
                                <div class="flex gap-1 mb-1" id="action-buttons-{{ $type }}-{{ $itemId }}">
                                    <button type="button" 
                                        onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'approve', this)"
                                        class="w-8 h-8 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 flex items-center justify-center transition-all shadow-sm" title="อนุมัติ">
                                        <i class="fa-solid fa-check text-xs"></i>
                                    </button>
                                    <button type="button" 
                                        onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'correct', this)"
                                        class="w-8 h-8 rounded-lg bg-amber-500 text-white hover:bg-amber-600 flex items-center justify-center transition-all shadow-sm" title="ส่งกลับแก้ไข">
                                        <i class="fa-solid fa-rotate-left text-xs"></i>
                                    </button>
                                    <button type="button" 
                                        onclick="handleApproval('{{ $type }}', {{ $itemId }}, 'reject', this)"
                                        class="w-8 h-8 rounded-lg bg-red-600 text-white hover:bg-red-700 flex items-center justify-center transition-all shadow-sm" title="ไม่อนุมัติ">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            @endif

                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('housing.request_detail', ['type' => $type, 'id' => $itemId]) }}"
                                    class="inline-flex items-center justify-center bg-white hover:bg-slate-50 text-slate-500 w-8 h-8 rounded-lg border border-slate-200 transition-all"
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
                                class="inline-flex items-center justify-center bg-white hover:bg-blue-50 text-blue-600 w-8 h-8 rounded-lg border border-slate-200 hover:border-blue-200 transition-all" title="พิมพ์ PDF">
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
                                    class="inline-flex items-center justify-center bg-white hover:bg-amber-50 text-amber-600 w-8 h-8 rounded-lg border border-slate-200 hover:border-amber-200 transition-all" title="แก้ไขข้อมูล">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                @endif
                                @endif
                                
                                @if($item->send_status == 0)
                                <button type="button" 
                                    onclick="confirmCancel('{{ $itemId }}', '{{ $code }}', '{{ $type }}')"
                                    class="inline-flex items-center justify-center bg-white hover:bg-red-50 text-red-500 w-8 h-8 rounded-lg border border-slate-200 hover:border-red-200 transition-all" title="ยกเลิกคำขอ">
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
                    <td colspan="4" class="px-4 py-12 text-center text-slate-400">
                        ไม่มีรายการคำขอในหมวดนี้
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@once
@push('scripts')
<script>
function confirmCancel(id, code, type) {
    Swal.fire({
        title: 'ยืนยันการยกเลิก?',
        text: "คุณต้องการยกเลิกคำขอหมายเลข " + code + " ใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'ยืนยันยกเลิก',
        cancelButtonText: 'ปิดหน้าต่าง',
        reverseButtons: true,
        borderRadius: '8px'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('cancel-form-' + type + '-' + id).submit();
        }
    });
}
</script>
@endpush
@endonce