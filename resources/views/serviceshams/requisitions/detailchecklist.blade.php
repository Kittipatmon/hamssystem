@extends('layouts.serviceitem.appservice')

@section('content')
    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6 uppercase tracking-tight text-xs">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded border border-slate-200 shadow-sm animate-zoom-in">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                    <i class="fa-solid fa-boxes-packing text-lg"></i>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-800 leading-none">กำลังจัดเตรียมพัสดุ</h1>
                    <p class="text-[11px] text-slate-400 font-semibold mt-1 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded border border-slate-250 font-mono">{{ $requisition->requisitions_code }}</span>
                        <span>•</span>
                        <span class="text-slate-500">โปรดตรวจสอบพัสดุให้ครบถ้วนก่อนบันทึก</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 no-print">
                <a href="{{ route('requisitions.reqchecklist') }}"
                    class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold rounded border border-slate-200 transition-all active:scale-95 text-xs">
                    <i class="fa-solid fa-arrow-left mr-1.5 opacity-70"></i> กลับไปหน้ารวม
                </a>
                <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                    class="w-8 h-8 flex items-center justify-center bg-white border border-red-200 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                    title="ดาวน์โหลด PDF">
                    <i class="fa-solid fa-file-pdf"></i>
                </a>
            </div>
        </div>

        <!-- User Info & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 animate-zoom-in">
            <div class="md:col-span-2 bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ผู้ขอเบิก (Requester)</p>
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-slate-800">คุณ{{ $requisition->user->fullname }}</span>
                        <span class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">{{ $requisition->user->department->department_name ?? "-" }} / {{ $requisition->user->section->section_code ?? "-" }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 border-l-4 border-l-orange-550">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded border border-orange-105 flex items-center justify-center">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ความคืบหน้า (Progress)</p>
                    <div class="flex items-baseline gap-1">
                        <span id="progress-text" class="text-lg font-black text-slate-800">
                            {{ $requisition->requisition_items->where('check_item', 1)->count() }} / {{ $requisition->requisition_items->count() }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase">รายการ</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded border border-emerald-100 flex items-center justify-center">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">เจ้าหน้าที่จัดเตรียม (Staff)</p>
                    <p class="text-sm font-black text-slate-800 leading-none">
                        คุณ{{ $requisition->packing_staff->fullname ?? Auth::user()->fullname }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Interactive Checklist -->
        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden animate-zoom-in">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    <h2 class="font-bold text-slate-700">รายการพัสดุที่ต้องจัด (Packing List)</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-bold text-slate-400 uppercase">ติ๊กถูกเพื่อยืนยันรายการพัสดุ</span>
                </div>
            </div>

            <div class="p-4 overflow-x-auto">
                <table class="w-full text-left border-collapse border border-slate-200">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-2.5 px-3 border-r border-slate-200 text-center w-16">#</th>
                            <th class="py-2.5 px-3 border-r border-slate-200">ชื่อรายการพัสดุ / SKU</th>
                            <th class="py-2.5 px-3 border-r border-slate-200 text-center w-28">จำนวนที่เบิก</th>
                            <th class="py-2.5 px-3 border-r border-slate-200 text-right w-32">มูลค่ารวม</th>
                            <th class="py-2.5 px-3 text-center w-40">การตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($requisition->requisition_items as $index => $item)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ $item->check_item ? 'bg-emerald-50/10' : '' }}" id="row-{{ $item->requistionitem_id }}">
                                <td class="py-2.5 px-3 border-r border-slate-200 text-center font-bold text-slate-400">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-2.5 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800">{{ $item->item->name ?? '-' }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">SKU: {{ $item->item->item_code ?? 'N/A' }}</div>
                                </td>
                                <td class="py-2.5 px-3 border-r border-slate-200 text-center">
                                    <span class="inline-block px-2.5 py-1 bg-slate-850 text-slate-700 rounded border border-slate-200 font-bold text-xs">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 border-r border-slate-200 text-right font-bold text-slate-450 font-mono">
                                    ฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        <label class="inline-flex items-center cursor-pointer relative">
                                            <input type="checkbox" class="sr-only check-item-checkbox peer"
                                                data-id="{{ $item->requistionitem_id }}" @if($item->check_item) checked @endif>
                                            <div class="w-8 h-8 bg-white border border-slate-350 rounded flex items-center justify-center transition-all shadow-sm
                                                        peer-checked:bg-emerald-600 peer-checked:border-emerald-600 peer-checked:scale-105">
                                                <i class="fa-solid fa-check text-slate-200 peer-checked:text-white text-xs {{ $item->check_item ? 'text-white' : '' }}"></i>
                                            </div>
                                        </label>
                                        <span class="check-indicator text-[9px] font-bold uppercase {{ $item->check_item ? 'text-emerald-700' : 'text-slate-400' }}">
                                            {{ $item->check_item ? 'ตรวจสอบแล้ว' : 'รอตรวจสอบ' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-5 bg-slate-800 text-white flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded flex items-center justify-center border border-white/5">
                        <i class="fa-solid fa-calculator text-slate-350"></i>
                    </div>
                    <div class="leading-normal">
                        <p class="text-[9px] font-bold text-slate-400 uppercase leading-none">สรุปมูลค่าประเมินพัสดุ (Valuation)</p>
                        <p class="text-[10px] text-slate-500 leading-none">รวมมูลค่าทั้งหมดของใบเบิกฉบับนี้</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black text-orange-400 font-mono">
                        ฿{{ number_format($requisition->total_price, 2) }}
                    </span>
                </div>
            </div>

            @php $allChecked = $requisition->requisition_items->where('check_item', '!=', 1)->count() === 0; @endphp

            <!-- Footer Actions -->
            <div class="p-6 bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row items-center justify-center gap-4 no-print">
                <button
                    class="w-full md:w-80 h-14 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded shadow transition-all active:scale-95 flex items-center justify-center gap-3 btn-submit-req {{ $allChecked ? '' : 'hidden' }}"
                    data-id="{{ $requisition->requisitions_id }}">
                    <i class="fa-solid fa-paper-plane text-base"></i>
                    <div class="flex flex-col items-start leading-none gap-0.5">
                        <span class="text-sm font-bold">จัดเตรียมเรียบร้อยแล้ว</span>
                        <span class="text-[8px] opacity-80 uppercase tracking-wider">Finalize & Ship Now</span>
                    </div>
                </button>
                <button
                    class="w-full md:w-80 h-14 bg-white border border-slate-200 text-slate-500 hover:border-red-400 hover:text-red-600 font-bold rounded shadow-sm transition-all active:scale-95 flex items-center justify-center gap-3 btn-cancel-req"
                    data-id="{{ $requisition->requisitions_id }}">
                    <i class="fa-solid fa-triangle-exclamation text-base"></i>
                    <div class="flex flex-col items-start leading-none gap-0.5">
                        <span class="text-sm font-bold">พบปัญหา / ยกเลิกการจัด</span>
                        <span class="text-[8px] opacity-80 uppercase tracking-wider">Report Problem or Cancel</span>
                    </div>
                </button>

                <form id="submit-req-form-{{ $requisition->requisitions_id }}"
                    action="{{ route('checklist.submitreq', $requisition->requisitions_id) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="packing_staff_comment" value="">
                </form>
                <form id="cancel-req-form-{{ $requisition->requisitions_id }}"
                    action="{{ route('checklist.cancelreq', $requisition->requisitions_id) }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="packing_staff_comment" value="">
                </form>
            </div>
        </div>
    </div>

    <style>
        .swal2-title {
            font-family: inherit !important;
            font-weight: 800 !important;
        }

        .swal2-html-container {
            font-family: inherit !important;
            font-weight: 600 !important;
            font-size: 0.9rem !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 0.5rem !important;
            font-weight: 800 !important;
            padding: 0.6rem 1.4rem !important;
        }
    </style>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = @json(csrf_token());
            const updateBaseUrl = @json(url('checklist/updatecheckitem'));
            const totalItems = {{ $requisition->requisition_items->count() }};

            async function premiumPrompt({ title, text, confirmText, cancelText, placeholder, icon, confirmColor }) {
                return Swal.fire({
                    title: `<span class="text-slate-800 font-bold">${title}</span>`,
                    html: `<p class="text-xs text-slate-500 font-bold leading-relaxed">${text}</p>`,
                    icon: icon || 'warning',
                    input: 'textarea',
                    inputPlaceholder: placeholder || 'ระบุหมายเหตุที่นี่...',
                    showCancelButton: true,
                    confirmButtonText: confirmText || 'ตกลง',
                    cancelButtonText: cancelText || 'ยกเลิก',
                    confirmButtonColor: confirmColor || '#10b981',
                    cancelButtonColor: '#1e293b',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded border border-slate-200 shadow-xl',
                        confirmButton: 'rounded px-6 py-2 font-bold text-xs',
                        cancelButton: 'rounded px-6 py-2 font-bold text-xs',
                        input: 'rounded border border-slate-200 focus:ring-red-100 font-semibold text-xs'
                    },
                    inputValidator: (value) => {
                        if (!value && icon === 'error') return 'กรุณาระบุเหตุผลในการยกเลิก';
                    }
                });
            }

            const submitBtn = document.querySelector('.btn-submit-req');
            if (submitBtn) {
                submitBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    premiumPrompt({
                        title: 'ยืนยันการจัดส่งอุปกรณ์?',
                        text: 'คุณได้ตรวจสอบพัสดุทุกรายการครบถ้วนแล้วใช่หรือไม่?',
                        confirmText: 'ยืนยันการส่งของ',
                        cancelText: 'ยังไม่เสร็จ',
                        placeholder: 'เพิ่มบันทึกเพิ่มเติม (ไม่บังคับ)...',
                        confirmColor: '#10b981'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('submit-req-form-' + id);
                            form.querySelector('input[name="packing_staff_comment"]').value = result.value || '';
                            form.submit();
                        }
                    });
                });
            }

            const cancelBtn = document.querySelector('.btn-cancel-req');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    premiumPrompt({
                        title: 'ยืนยันการยกเลิกใบเบิก?',
                        text: 'โปรดระบุสาเหตุที่ไม่สามารถจัดพัสดุตามใบเบิกนี้ได้',
                        confirmText: 'ยืนยันการยกเลิก',
                        cancelText: 'กลับไปทำงานต่อ',
                        placeholder: 'เช่น สินค้าหมด, สเปคไม่ถูกต้อง...',
                        icon: 'error',
                        confirmColor: '#ef4444'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('cancel-req-form-' + id);
                            form.querySelector('input[name="packing_staff_comment"]').value = result.value || '';
                            form.submit();
                        }
                    });
                });
            }

            function refreshUI() {
                const checkboxes = Array.from(document.querySelectorAll('.check-item-checkbox'));
                const checkedCount = checkboxes.filter(cb => cb.checked).length;
                const allDone = checkedCount === totalItems;

                document.getElementById('progress-text').textContent = `${checkedCount} / ${totalItems}`;

                const sBtn = document.querySelector('.btn-submit-req');
                if (sBtn) {
                    if (allDone) {
                        sBtn.classList.remove('hidden');
                    } else {
                        sBtn.classList.add('hidden');
                    }
                }
            }

            document.querySelectorAll('.check-item-checkbox').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    const cb = this;
                    const id = cb.dataset.id;
                    const checked = cb.checked ? 1 : 0;
                    const row = document.getElementById('row-' + id);
                    const indicator = row.querySelector('.check-indicator');
                    const iIcon = row.querySelector('.fa-check');
                    const boxDiv = cb.nextElementSibling;

                    cb.disabled = true;

                    fetch(`${updateBaseUrl}/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ check_item: checked })
                    })
                        .then(res => res.json())
                        .then(() => {
                            if (checked) {
                                row.classList.add('bg-emerald-50/10');
                                indicator.textContent = 'ตรวจสอบแล้ว';
                                indicator.classList.replace('text-slate-400', 'text-emerald-700');
                                iIcon.classList.add('text-white');
                                boxDiv.classList.add('bg-emerald-600', 'border-emerald-600');
                            } else {
                                row.classList.remove('bg-emerald-50/10');
                                indicator.textContent = 'รอตรวจสอบ';
                                indicator.classList.replace('text-emerald-700', 'text-slate-400');
                                iIcon.classList.remove('text-white');
                                boxDiv.classList.remove('bg-emerald-600', 'border-emerald-600');
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'พบข้อผิดพลาด',
                                text: 'ไม่สามารถบันทึกข้อมูลไปยังเซิร์ฟเวอร์ได้',
                                customClass: { popup: 'rounded border border-slate-200' }
                            });
                            cb.checked = !cb.checked;
                        })
                        .finally(() => {
                            cb.disabled = false;
                            refreshUI();
                        });
                });
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    customClass: { popup: 'rounded border border-slate-200' }
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สำเร็จ',
                    text: @json(session('error')),
                    customClass: { popup: 'rounded border border-slate-200' }
                });
            @endif
        });
    </script>
@endpush