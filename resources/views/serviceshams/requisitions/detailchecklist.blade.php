@extends('layouts.serviceitem.appservice')

@section('content')
    <div class="max-w-[1400px] mx-auto px-4 py-8 space-y-8">

        <!-- Header Section -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                    <i class="fa-solid fa-boxes-packing text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 leading-none">กำลังจัดเตรียมพัสดุ</h1>
                    <p class="text-sm text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                        <span
                            class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded border border-slate-200">{{ $requisition->requisitions_code }}</span>
                        <span>•</span>
                        <span class="tracking-wide text-slate-500">โปรดตรวจสอบพัสดุให้ครบถ้วนก่อนบันทึก</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 no-print">
                <a href="{{ route('requisitions.reqchecklist') }}"
                    class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-600 font-black rounded-2xl border border-slate-100 transition-all active:scale-95 text-[13px] tracking-wide">
                    <i class="fa-solid fa-arrow-left mr-2 opacity-50"></i> กลับไปหน้ารวม
                </a>
            </div>
        </div>

        <!-- User Info & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 animate-zoom-in" style="animation-delay: 0.1s">
            <div
                class="md:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-user-tie text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none mb-1.5 tracking-widest">ผู้ขอเบิก (Requester)</p>
                    <div class="flex flex-col">
                        <span
                            class="text-xl font-black text-slate-700 leading-tight">คุณ{{ $requisition->user->fullname }}</span>
                        <span
                            class="text-[12px] font-bold text-slate-400 mt-1 uppercase">{{ $requisition->user->department->department_name ?? "-" }}
                            / {{ $requisition->user->section->section_code ?? "-" }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5 border-l-4 border-l-orange-400">
                <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-list-check text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none mb-1.5 tracking-widest">ความคืบหน้า (Progress)</p>
                    <div class="flex items-baseline gap-1">
                        <span id="progress-text" class="text-3xl font-black text-slate-800">
                            {{ $requisition->requisition_items->where('check_item', 1)->count() }} /
                            {{ $requisition->requisition_items->count() }}
                        </span>
                        <span class="text-[10px] font-black text-slate-400 uppercase">Items</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-user-check text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none mb-1.5 tracking-widest">เจ้าหน้าที่จัดเตรียม (Staff)</p>
                    <p class="text-lg font-black text-slate-700 leading-none">
                        คุณ{{ $requisition->packing_staff->fullname ?? Auth::user()->fullname }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Interactive Checklist -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-zoom-in"
            style="animation-delay: 0.2s">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
                    <h2 class="text-lg font-black text-slate-800 tracking-tight leading-none">รายการพัสดุที่ต้องจัด (Packing List)
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ติ๊กถูกเพื่อยืนยันรายการพัสดุ</span>
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center">
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-300 animate-bounce"></i>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-8 overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-100/30">
                            <th
                                class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl text-center w-20">
                                #</th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">ชื่อรายการพัสดุ / SKU
                            </th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">จำนวนที่เบิก
                            </th>
                            <th class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">มูลค่ารวม</th>
                            <th
                                class="px-6 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl w-48">
                                การตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisition->requisition_items as $index => $item)
                            <tr class="hover:bg-slate-50 transition-all duration-200 group {{ $item->check_item ? 'bg-emerald-50/10' : '' }}"
                                id="row-{{ $item->requistionitem_id }}">
                                <td class="px-6 py-6 text-center text-slate-300 font-black uppercase">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-6 font-black text-slate-700">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[15px] group-hover:text-red-600 transition-colors leading-tight">{{ $item->item->name ?? '-' }}</span>
                                        <span
                                            class="text-[10px] text-slate-400 font-black uppercase mt-2 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 w-fit group-hover:bg-white transition-colors tracking-widest">SKU:
                                            {{ $item->item->item_code ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <span
                                        class="inline-block px-5 py-2 bg-slate-900 text-white rounded-xl font-black text-[16px] shadow-lg shadow-slate-100">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-right font-black text-slate-400 italic">
                                    ฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <label class="inline-flex items-center cursor-pointer group/label relative">
                                            <input type="checkbox" class="sr-only check-item-checkbox peer"
                                                data-id="{{ $item->requistionitem_id }}" @if($item->check_item) checked @endif>
                                            <div
                                                class="w-12 h-12 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-center transition-all group-hover/label:border-red-400 group-hover/label:shadow-lg shadow-sm
                                                        peer-checked:bg-emerald-500 peer-checked:border-emerald-500 peer-checked:shadow-emerald-100 peer-checked:scale-110">
                                                <i
                                                    class="fa-solid fa-check text-slate-100 peer-checked:text-white text-xl transition-all {{ $item->check_item ? 'text-white' : '' }}"></i>
                                            </div>
                                        </label>
                                        <span
                                            class="check-indicator text-[9px] font-black uppercase tracking-widest {{ $item->check_item ? 'text-emerald-500' : 'text-slate-300' }}">
                                            {{ $item->check_item ? 'ตรวจสอบแล้ว' : 'รอตรวจสอบ' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-8 bg-slate-900 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center border border-white/5">
                        <i class="fa-solid fa-calculator text-slate-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-slate-500 uppercase leading-none mb-1.5 tracking-widest">สรุปมูลค่าประเมินพัสดุ (Valuation)</p>
                        <p class="text-[13px] font-bold text-slate-500 leading-none tracking-wide opacity-80">รวมมูลค่าทั้งหมดของใบเบิกฉบับนี้</p>
                    </div>
                </div>
                <div class="text-right">
                    <span
                        class="text-4xl font-black text-orange-400 decoration-slate-700 decoration-wavy decoration-2 underline-offset-[12px] underline">
                        ฿{{ number_format($requisition->total_price, 2) }}
                    </span>
                </div>
            </div>

            @php $allChecked = $requisition->requisition_items->where('check_item', '!=', 1)->count() === 0; @endphp

            <!-- Footer Actions -->
            <div
                class="p-10 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-center gap-6 no-print">
                <button
                    class="w-full md:w-96 h-20 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-3xl shadow-2xl shadow-emerald-100 transition-all active:scale-95 flex items-center justify-center gap-4 btn-submit-req {{ $allChecked ? '' : 'hidden' }}"
                    data-id="{{ $requisition->requisitions_id }}">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane text-xl"></i>
                    </div>
                    <div class="flex flex-col items-start leading-none gap-1.5">
                        <span class="text-[18px] font-black">จัดเตรียมเรียบร้อยแล้ว</span>
                        <span class="text-[10px] opacity-80 uppercase font-bold tracking-widest">Finalize & Ship Now</span>
                    </div>
                </button>
                <button
                    class="w-full md:w-96 h-20 bg-white border-2 border-slate-200 text-slate-400 hover:border-red-400 hover:text-red-500 font-black rounded-3xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-4 btn-cancel-req group"
                    data-id="{{ $requisition->requisitions_id }}">
                    <div
                        class="w-10 h-10 bg-slate-50 group-hover:bg-red-50 rounded-xl flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    </div>
                    <div class="flex flex-col items-start leading-none gap-1.5">
                        <span class="text-[18px] font-black">พบปัญหา / ยกเลิกการจัด</span>
                        <span class="text-[10px] opacity-80 uppercase font-bold tracking-widest">Report Problem or Cancel</span>
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
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.4s ease-out forwards;
        }

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
            border-radius: 1.2rem !important;
            font-weight: 800 !important;
            padding: 0.8rem 1.8rem !important;
        }
    </style>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = @json(csrf_token());
            const updateBaseUrl = @json(url('checklist/updatecheckitem'));
            const totalItems = {{ $requisition->requisition_items->count() }};

            // Theme-aware SweetAlert Prompt
            async function premiumPrompt({ title, text, confirmText, cancelText, placeholder, icon, confirmColor }) {
                return Swal.fire({
                    title: `<span class="text-slate-800 font-black tracking-tight">${title}</span>`,
                    html: `<p class="text-sm text-slate-500 font-bold leading-relaxed">${text}</p>`,
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
                        popup: 'rounded-[2.5rem] border-none shadow-2xl',
                        confirmButton: 'rounded-2xl px-10 py-3.5 font-black text-[13px] tracking-wide',
                        cancelButton: 'rounded-2xl px-10 py-3.5 font-black text-[13px] tracking-wide',
                        input: 'rounded-2xl border-slate-100 focus:ring-red-100 font-bold'
                    },
                    inputValidator: (value) => {
                        if (!value && icon === 'error') return 'กรุณาระบุเหตุผลในการยกเลิก';
                    }
                });
            }

            // Action Handlers
            document.querySelector('.btn-submit-req').addEventListener('click', function (e) {
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

            document.querySelector('.btn-cancel-req').addEventListener('click', function (e) {
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

            // Interactive Checkboxes
            function refreshUI() {
                const checkboxes = Array.from(document.querySelectorAll('.check-item-checkbox'));
                const checkedCount = checkboxes.filter(cb => cb.checked).length;
                const allDone = checkedCount === totalItems;

                document.getElementById('progress-text').textContent = `${checkedCount} / ${totalItems}`;

                const submitBtn = document.querySelector('.btn-submit-req');
                if (allDone) {
                    submitBtn.classList.remove('hidden');
                    submitBtn.classList.add('animate-zoom-in');
                } else {
                    submitBtn.classList.add('hidden');
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
                                indicator.classList.replace('text-slate-300', 'text-emerald-500');
                                iIcon.classList.add('text-white');
                                boxDiv.classList.add('bg-emerald-500', 'border-emerald-500', 'scale-110');
                            } else {
                                row.classList.remove('bg-emerald-50/10');
                                indicator.textContent = 'รอตรวจสอบ';
                                indicator.classList.replace('text-emerald-500', 'text-slate-300');
                                iIcon.classList.remove('text-white');
                                boxDiv.classList.remove('bg-emerald-500', 'border-emerald-500', 'scale-110');
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'พบข้อผิดพลาด',
                                text: 'ไม่สามารถบันทึกข้อมูลไปยังเซิร์ฟเวอร์ได้',
                                customClass: { popup: 'rounded-[2rem]' }
                            });
                            cb.checked = !cb.checked;
                        })
                        .finally(() => {
                            cb.disabled = false;
                            refreshUI();
                        });
                });
            });

            // Session Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    customClass: { popup: 'rounded-[2rem]' }
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'ไม่สำเร็จ',
                    text: @json(session('error')),
                    customClass: { popup: 'rounded-[2rem]' }
                });
            @endif
        });
    </script>

    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.4s ease-out forwards;
        }

        .check-item-checkbox:checked+div {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            transform: scale(1.1);
        }

        .check-item-checkbox:checked+div i {
            color: white !important;
        }
    </style>
@endpush