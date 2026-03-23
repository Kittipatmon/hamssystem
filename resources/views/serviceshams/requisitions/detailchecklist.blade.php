@extends('layouts.serviceitem.appservice')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                <i class="fa-solid fa-boxes-packing text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter italic leading-none">กำลังจัดเตรียมพัสดุ</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded font-mono border border-slate-200">{{ $requisition->requisitions_code }}</span>
                    <span>•</span>
                    <span class="italic">โปรดตรวจสอบพัสดุให้ครบถ้วนก่อนบันทึก</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('requisitions.reqchecklist') }}" 
               class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl border border-slate-100 transition-all active:scale-95 text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้ารายการ
            </a>
        </div>
    </div>

    <!-- User Info & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user-tie text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">ผู้ขอเบิก (Requester)</p>
                <p class="text-[15px] font-black text-slate-700 leading-tight">
                    {{ $requisition->user->fullname }}
                    <span class="block text-[10px] text-slate-400 mt-1 italic font-medium">{{ $requisition->user->department->department_name ?? "-" }}</span>
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-list-check text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">ความคืบหน้า</p>
                <p id="progress-text" class="text-[18px] font-black text-slate-800 font-mono italic">
                    {{ $requisition->requisition_items->where('check_item', 1)->count() }} / {{ $requisition->requisition_items->count() }}
                </p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-calendar-day text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">วันที่ขอ (Date)</p>
                <p class="text-[15px] font-black text-slate-700 italic leading-none">{{ optional($requisition->request_date)->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    @if (session('success') || session('error'))
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 animate-pulse text-center text-sm font-bold text-slate-500 italic">
            <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Initializing System Message...
        </div>
    @endif

    <!-- Interactive Checklist -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-800 tracking-tight italic uppercase">รายการที่ต้องจัดสิ่ง</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Check all item to finish</span>
                <i class="fa-solid fa-circle-down text-slate-200 animate-bounce"></i>
            </div>
        </div>

        <div class="p-4 md:p-8 overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-100/50">
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest rounded-l-3xl text-center">#</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">ชื่อรายการพัสดุ</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">ต้องเตรียม (ชิ้น)</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">ยอดรวม</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-3xl">ตรวจสอบ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($requisition->requisition_items as $index => $item)
                    <tr class="hover:bg-red-50/20 transition-all duration-200 group {{ $item->check_item ? 'bg-emerald-50/30' : '' }}" id="row-{{ $item->requistionitem_id }}">
                        <td class="px-6 py-6 text-center text-slate-400 font-bold italic">{{ $index + 1 }}</td>
                        <td class="px-6 py-6 font-black text-slate-700">
                             <div class="flex flex-col">
                                <span class="text-[15px] group-hover:text-red-600 transition-colors">{{ $item->item->name ?? '-' }}</span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase italic mt-1 leading-none">SKU: {{ $item->item->item_code ?? 'N/A' }}</span>
                             </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <span class="inline-block px-4 py-1.5 bg-slate-800 text-white rounded-xl font-black text-[16px] italic shadow-lg shadow-slate-100">
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-right font-black text-slate-800 font-mono italic">
                            ฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}
                        </td>
                        <td class="px-6 py-6 text-center">
                            <label class="inline-flex items-center cursor-pointer group/label">
                                <div class="relative">
                                    <input type="checkbox"
                                        class="sr-only check-item-checkbox"
                                        data-id="{{ $item->requistionitem_id }}"
                                        @if($item->check_item) checked @endif
                                    >
                                    <div class="w-12 h-12 bg-white border-2 border-slate-200 rounded-2xl flex items-center justify-center transition-all group-hover/label:border-red-400 shadow-sm
                                                peer-checked:bg-emerald-500 peer-checked:border-emerald-500 peer-checked:shadow-emerald-100 peer-checked:scale-110">
                                        <i class="fa-solid fa-check text-slate-200 peer-checked:text-white text-xl transition-all {{ $item->check_item ? 'text-white' : '' }}"></i>
                                    </div>
                                    <input type="checkbox" class="sr-only peer" @if($item->check_item) checked @endif disabled> {{-- Visual peer for style --}}
                                </div>
                            </label>
                            <div class="check-indicator mt-1 text-[9px] font-black uppercase {{ $item->check_item ? 'text-emerald-500' : 'text-slate-300' }} italic">
                                {{ $item->check_item ? 'READY' : 'WAITING' }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-800 text-white shadow-2xl">
                        <td colspan="3" class="px-8 py-8 rounded-l-[2rem] font-black text-right tracking-[0.2em] italic uppercase">TOTAL ESTIMATED VALUE</td>
                        <td colspan="2" class="px-8 py-8 rounded-r-[2rem] text-right text-3xl font-black italic font-mono text-orange-400 decoration-slate-600 decoration-4 underline-offset-8">
                            ฿{{ number_format($requisition->total_price, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @php $allChecked = $requisition->requisition_items->where('check_item', '!=', 1)->count() === 0; @endphp

        <!-- Footer Actions -->
        <div class="p-10 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-center gap-6">
            <button class="w-full md:w-80 h-16 bg-emerald-500 hover:bg-emerald-600 text-white font-black rounded-2xl shadow-xl shadow-emerald-100 transition-all active:scale-95 flex items-center justify-center gap-3 btn-submit-req {{ $allChecked ? '' : 'hidden' }}"
                    data-id="{{ $requisition->requisitions_id }}">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span>จัดเตรียมเรียบร้อยแล้ว</span>
            </button>
            <button class="w-full md:w-80 h-16 bg-white border-2 border-red-100 text-red-500 hover:bg-red-500 hover:text-white font-black rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-3 btn-cancel-req"
                    data-id="{{ $requisition->requisitions_id }}">
                <i class="fa-solid fa-ban"></i>
                <span>มีปัญหา / ยกเลิกรายการ</span>
            </button>
            
            <form id="submit-req-form-{{ $requisition->requisitions_id }}" action="{{ route('checklist.submitreq', $requisition->requisitions_id) }}" method="POST" class="hidden"> 
                @csrf
                <input type="hidden" name="packing_staff_comment" value="">
            </form>
            <form id="cancel-req-form-{{ $requisition->requisitions_id }}" action="{{ route('checklist.cancelreq', $requisition->requisitions_id) }}" method="POST" class="hidden"> 
                @csrf
                <input type="hidden" name="packing_staff_comment" value="">
            </form>
        </div>
    </div>
</div>

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
                title: `<span class="text-slate-800 font-black tracking-tighter italic uppercase">${title}</span>`,
                html: `<p class="text-[13px] text-slate-500 font-bold italic leading-relaxed uppercase">${text}</p>`,
                icon: icon || 'warning',
                input: 'textarea',
                inputPlaceholder: placeholder || 'Type your message here...',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                confirmButtonColor: confirmColor || '#10b981',
                cancelButtonColor: '#1e293b',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] border-none shadow-2xl',
                    confirmButton: 'rounded-xl px-10 py-4 font-black uppercase text-sm',
                    cancelButton: 'rounded-xl px-10 py-4 font-black uppercase text-sm',
                    input: 'rounded-2xl border-slate-100 focus:ring-red-100 font-medium'
                },
                inputValidator: (value) => {
                    if (!value && icon === 'error') return 'Please provide a reason for cancellation';
                }
            });
        }

        // Action Handlers
        document.querySelector('.btn-submit-req').addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            premiumPrompt({
                title: 'Confirm Preparation?',
                text: 'Have you double-checked all items in this pack?',
                confirmText: 'YES, SHIP IT',
                cancelText: 'NOT YET',
                placeholder: 'Add optional notes for the requester...',
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
                title: 'Cancel Request?',
                text: 'Please state the reason for rejecting or cancelling this pack.',
                confirmText: 'CONFIRM CANCEL',
                cancelText: 'KEEP WORKING',
                placeholder: 'e.g. Items out of stock, incorrect spec...',
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
                        row.classList.add('bg-emerald-50/30');
                        indicator.textContent = 'READY';
                        indicator.classList.replace('text-slate-300', 'text-emerald-500');
                        iIcon.classList.add('text-white');
                        boxDiv.classList.add('bg-emerald-500', 'border-emerald-500', 'scale-110');
                    } else {
                        row.classList.remove('bg-emerald-50/30');
                        indicator.textContent = 'WAITING';
                        indicator.classList.replace('text-emerald-500', 'text-slate-300');
                        iIcon.classList.remove('text-white');
                        boxDiv.classList.remove('bg-emerald-500', 'border-emerald-500', 'scale-110');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to synchronize with server', 'error');
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
                title: 'SUCCESS', 
                text: @json(session('success')),
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif
        @if(session('error'))
            Swal.fire({ 
                icon: 'error', 
                title: 'FAILED', 
                text: @json(session('error')),
                customClass: { popup: 'rounded-[2rem]' }
            });
        @endif
    });
</script>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
    .check-item-checkbox:checked + div {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        transform: scale(1.1);
    }
    .check-item-checkbox:checked + div i {
        color: white !important;
    }
</style>
@endpush
@endsection