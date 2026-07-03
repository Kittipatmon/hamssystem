@extends('layouts.serviceitem.appservice')

@push('styles')
    <style>
        :root {
            --accent-red: #dc2626;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
        }

        body {
            background-color: #fafbfd;
        }

        .hosp-qty-control {
            display: inline-flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
            background: #f8fafc;
        }

        .hosp-qty-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            background: #f1f5f9;
            border: none;
            cursor: pointer;
            transition: all 0.1s;
        }

        .hosp-qty-btn:hover:not(:disabled) {
            background: #cbd5e1;
            color: #0f172a;
        }

        .hosp-qty-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .hosp-qty-input {
            width: 38px;
            height: 28px;
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            border-top: none;
            border-bottom: none;
            outline: none;
            background: white;
            color: #0f172a;
        }

        .billing-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .billing-row:last-of-type {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">ตะกร้าจัดเตรียมรายการเบิกพัสดุ</h1>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">เลือกอุปกรณ์และกำหนดจำนวนเพื่อบันทึกคำขอเบิกพัสดุเข้าระบบ</p>
                </div>
            </div>
            <div>
                <a href="{{ route('items.itemsalllist') }}"
                    class="flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-slate-200 text-slate-600 font-bold rounded shadow-sm hover:bg-slate-50 transition-all text-xs">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                    <span>ย้อนกลับไปเลือกอุปกรณ์</span>
                </a>
            </div>
        </div>

        @if ($cart_items->count() == 0)
            <!-- 🛒 EMPTY STATE -->
            <div class="bg-white p-16 rounded border border-slate-200 shadow-sm flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-slate-50 rounded border border-slate-200 flex items-center justify-center text-slate-300 mb-4">
                    <i class="fa-solid fa-cart-flatbed text-2xl"></i>
                </div>
                <h2 class="text-lg font-bold text-slate-800 mb-1">ไม่พบรายการเบิกพัสดุในตะกร้า</h2>
                <p class="text-slate-400 mb-6 max-w-sm text-xs">
                    กรุณาคลิกเลือกพัสดุที่ต้องการเบิกใช้งานเข้าระบบก่อนการทำรายการยืนยันครับ
                </p>
                <a href="{{ route('items.itemsalllist') }}"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-all text-xs">
                    <span>ไปเลือกอุปกรณ์พัสดุ</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- 📦 ITEM LIST (LEFT) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex items-center justify-between px-2 text-xs font-semibold">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dispensing Items</span>
                        <span class="text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1 rounded">เลือกไว้ทั้งหมด {{ $cart_items->count() }} รายการ</span>
                    </div>

                    <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
                        <!-- Table header design -->
                        <div class="hidden sm:grid sm:grid-cols-12 gap-4 bg-slate-50/80 px-5 py-3 border-b border-slate-200 text-[10px] font-bold uppercase text-slate-500 tracking-wider">
                            <div class="col-span-2">รูปภาพ</div>
                            <div class="col-span-6">รายการ/รหัสพัสดุ</div>
                            <div class="col-span-4 text-right">จำนวน/ราคา</div>
                        </div>

                        <div class="divide-y divide-slate-200">
                            @foreach ($cart_items as $cart_item)
                                <div class="p-5 flex flex-col sm:grid sm:grid-cols-12 gap-4 items-center hover:bg-slate-50/40 transition-colors">
                                    
                                    <!-- Image -->
                                    <div class="col-span-2 flex justify-center">
                                        <div class="w-20 h-20 border border-slate-200 rounded p-1.5 bg-white flex items-center justify-center relative shadow-sm">
                                            @if(isset($cart_item->item->item_pic) && $cart_item->item->item_pic)
                                                <img src="{{ asset('images/items/' . $cart_item->item->item_pic) }}"
                                                     class="max-w-full max-h-full object-contain cursor-pointer"
                                                     onclick="showImageModal('{{ asset('images/items/' . $cart_item->item->item_pic) }}', '{{ $cart_item->item->name }}', `{{ addslashes($cart_item->item->description) }}`)">
                                            @else
                                                <i class="fa-solid fa-box text-2xl text-slate-300"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Details -->
                                    <div class="col-span-6 space-y-1.5 w-full text-center sm:text-left">
                                        <div>
                                            <span class="font-mono font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 text-[10px]">
                                                {{ optional($cart_item->item)->item_code }}
                                            </span>
                                            <h3 class="text-sm font-bold text-slate-800 tracking-tight leading-snug mt-1">
                                                {{ optional($cart_item->item)->name }}
                                            </h3>
                                        </div>
                                        <p class="text-xs text-slate-400 line-clamp-1 leading-relaxed">
                                            {{ optional($cart_item->item)->description ?: 'ไม่มีข้อมูลรายละเอียดเพิ่มเติม' }}
                                        </p>
                                    </div>

                                    <!-- Quantity and Actions -->
                                    <div class="col-span-4 flex flex-col sm:items-end justify-between gap-3 w-full">
                                        <div class="flex justify-between sm:justify-end items-center w-full gap-3">
                                            
                                            <!-- Delete button -->
                                            <form action="{{ route('cartitem.destroy', $cart_item->cart_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>

                                            <!-- Quantity control -->
                                            <form action="{{ route('cartitem.update', $cart_item->cart_id) }}" method="POST"
                                                  class="cart-qty-form" onsubmit="event.preventDefault();"
                                                  data-max-qty="{{ optional($cart_item->item)->quantity ?? 999999 }}" data-min-qty="1"
                                                  data-unit-price="{{ optional($cart_item->item)->per_unit ?? 0 }}">
                                                @csrf
                                                <div class="hosp-qty-control">
                                                    <button type="button" class="hosp-qty-btn" data-action="decrement" {{ $cart_item->cart_quantity <= 1 ? 'disabled' : '' }}>
                                                        <i class="fa-solid fa-minus"></i>
                                                    </button>
                                                    <input type="text" name="display_quantity"
                                                           value="{{ $cart_item->cart_quantity ?? 1 }}" class="hosp-qty-input" />
                                                    <button type="button" class="hosp-qty-btn" data-action="increment" {{ (optional($cart_item->item)->quantity ?? 0) > 0 && $cart_item->cart_quantity >= (optional($cart_item->item)->quantity ?? 0) ? 'disabled' : '' }}>
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="quantity" value="{{ $cart_item->cart_quantity ?? 1 }}">
                                            </form>
                                        </div>

                                        <div class="text-left sm:text-right border-t border-dashed border-slate-200 pt-2 w-full">
                                            <div class="text-sm font-bold text-slate-800 font-mono item-total-price">
                                                ฿{{ number_format((optional($cart_item->item)->per_unit ?? 0) * $cart_item->cart_quantity, 2) }}
                                            </div>
                                            <span class="text-[10px] font-semibold text-slate-400 block">@ ฿{{ number_format(optional($cart_item->item)->per_unit, 2) }} / ชิ้น</span>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 💰 SUMMARY SIDEBAR (RIGHT) -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                        <div class="bg-slate-100/70 border-b border-slate-200 px-4 py-3 flex items-center gap-2 text-slate-700 text-xs font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-file-invoice-dollar text-slate-500"></i>
                            <span>ใบแจ้งความต้องการเบิก</span>
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="space-y-1">
                                <div class="billing-row">
                                    <span>รหัสพนักงาน</span>
                                    <span class="text-slate-800 font-bold font-mono">{{ Auth::user()->emp_code }}</span>
                                </div>
                                <div class="billing-row">
                                    <span>ชื่อผู้ส่งคำขอ</span>
                                    <span class="text-slate-800 font-bold">คุณ{{ Auth::user()->fullname }}</span>
                                </div>
                                <div class="billing-row">
                                    <span>จำนวนอุปกรณ์รวม</span>
                                    <span id="total-items-count" class="text-slate-800 font-bold">{{ $cart_items->sum('cart_quantity') }} ชิ้น</span>
                                </div>
                                <div class="billing-row">
                                    <span>ภาษีมูลค่าเพิ่ม (7%)</span>
                                    <span class="text-slate-400 font-semibold italic text-[10px]">รวมอยู่ในราคาสินค้า</span>
                                </div>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded p-4 text-center">
                                <span class="text-[9px] font-bold text-red-500 uppercase tracking-widest block">ราคารวมสุทธิ</span>
                                <div id="grand-total-price" class="text-2xl font-black text-red-600 font-mono mt-1">
                                    ฿{{ number_format($cart_items->sum(function ($item) {
                                        return ((optional($item->item)->per_unit ?? 0) * ($item->cart_quantity ?? 0)); }), 2) }}
                                </div>
                            </div>

                            <button id="checkout-btn" class="w-full flex items-center justify-center gap-1.5 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-all text-xs">
                                <i class="fa-solid fa-clipboard-check text-xs"></i>
                                <span>ยืนยันคำขอเบิกพัสดุ</span>
                            </button>

                            <p class="text-[9px] text-center text-slate-400 leading-relaxed italic mt-2">
                                * ข้อมูลประวัติการส่งคำขอจะถูกบันทึกเพื่อตรวจสอบความโปร่งใสในระบบคลังอุปกรณ์
                            </p>

                            <form id="checkout-form" action="{{ route('cartitem.checkout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>

    <!-- 🖼/ MODAL -->
    <dialog id="imageModal" class="modal backdrop-blur-sm">
        <div class="modal-box rounded border border-slate-200 shadow-2xl p-0 overflow-hidden max-w-2xl bg-white">
            <div class="p-4 bg-slate-900 flex justify-between items-center text-white">
                <h3 class="font-bold text-sm flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-red-500"></i>
                    <span id="imageModalLabel"></span>
                </h3>
                <form method="dialog">
                    <button class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all">✕</button>
                </form>
            </div>
            <div class="p-5 space-y-4">
                <div class="w-full h-80 rounded border border-slate-200 overflow-hidden bg-slate-50 flex items-center justify-center">
                    <img id="modalImage" src="" class="max-w-full max-h-full object-contain" />
                </div>
                <div id="modalDescription" class="text-slate-500 leading-relaxed text-xs bg-slate-50 p-4 rounded border border-slate-200 font-semibold italic">
                </div>
            </div>
            <div class="px-5 pb-5">
                <form method="dialog">
                    <button class="w-full py-2.5 bg-slate-900 text-white font-bold rounded hover:bg-black transition-all active:scale-95 text-xs uppercase">
                        ปิดหน้าต่างนี้
                    </button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/40"><button>close</button></form>
    </dialog>
@endsection

@push('scripts')
    <script>
        function showImageModal(src, title, description = '') {
            const dialog = document.getElementById('imageModal');
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModalLabel').textContent = title;
            document.getElementById('modalDescription').textContent = description || 'ไม่มีรายละเอียดพัสดุอุปกรณ์';
            if (dialog) dialog.showModal();
        }

        document.addEventListener('DOMContentLoaded', function () {
            function updateGlobalSummary() {
                let totalItems = 0;
                let grandTotal = 0;
                document.querySelectorAll('.cart-qty-form').forEach(f => {
                    const qty = parseInt(f.querySelector('.hosp-qty-input').value) || 0;
                    const price = parseFloat(f.dataset.unitPrice) || 0;
                    totalItems += qty;
                    grandTotal += qty * price;
                });

                const totalItemsEl = document.getElementById('total-items-count');
                if (totalItemsEl) {
                    totalItemsEl.textContent = totalItems + ' ชิ้น';
                }

                const grandTotalEl = document.getElementById('grand-total-price');
                if (grandTotalEl) {
                    const formattedGrandTotal = new Intl.NumberFormat('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(grandTotal);
                    grandTotalEl.textContent = '฿' + formattedGrandTotal;
                }
            }

            // Quantity Increment/Decrement Ajax
            document.querySelectorAll('.cart-qty-form').forEach(form => {
                const minusBtn = form.querySelector('.hosp-qty-btn[data-action="decrement"]');
                const plusBtn = form.querySelector('.hosp-qty-btn[data-action="increment"]');
                const displayInput = form.querySelector('.hosp-qty-input');
                const hiddenInput = form.querySelector('input[name="quantity"]');
                const maxQty = parseInt(form.dataset.maxQty || '999999');

                function updateButtonStates(val) {
                    if (minusBtn) minusBtn.disabled = (val <= 1);
                    if (plusBtn) plusBtn.disabled = (val >= maxQty);
                }

                function performUpdate(newVal) {
                    const originalVal = displayInput.value;
                    displayInput.value = newVal;
                    hiddenInput.value = newVal;
                    document.body.style.cursor = 'wait';
                    [minusBtn, plusBtn].forEach(b => { if (b) b.disabled = true; });

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.body.style.cursor = 'default';
                        
                        // Update this item's total price
                        const unitPrice = parseFloat(form.dataset.unitPrice || 0);
                        const itemTotalEl = form.closest('.col-span-4')?.querySelector('.item-total-price');
                        if (itemTotalEl) {
                            const formattedItemTotal = new Intl.NumberFormat('th-TH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(newVal * unitPrice);
                            itemTotalEl.textContent = '฿' + formattedItemTotal;
                        }

                        // Update global counters
                        updateGlobalSummary();

                        // Update button disabled state
                        updateButtonStates(newVal);
                    })
                    .catch(error => {
                        document.body.style.cursor = 'default';
                        displayInput.value = originalVal;
                        hiddenInput.value = originalVal;
                        updateButtonStates(parseInt(originalVal));
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: error.message || 'ไม่สามารถอัปเดตจำนวนสินค้าได้',
                            confirmButtonColor: '#dc2626'
                        });
                    });
                }

                minusBtn?.addEventListener('click', () => { 
                    if (parseInt(displayInput.value) > 1) performUpdate(parseInt(displayInput.value) - 1); 
                });
                plusBtn?.addEventListener('click', () => {
                    let val = parseInt(displayInput.value);
                    if (val < maxQty) performUpdate(val + 1);
                    else {
                        Swal.fire({ 
                            icon: 'warning', 
                            title: 'สินค้าคงเหลือไม่เพียงพอ', 
                            text: `อุปกรณ์รายการนี้เหลือในสต๊อกเพียง ${maxQty} ชิ้น`, 
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                });
                displayInput?.addEventListener('change', (e) => {
                    let val = parseInt(e.target.value);
                    if (isNaN(val) || val < 1) val = 1;
                    
                    if (val > maxQty) {
                        val = maxQty;
                        Swal.fire({ 
                            icon: 'warning', 
                            title: 'สินค้าคงเหลือไม่เพียงพอ', 
                            text: `อุปกรณ์รายการนี้เหลือในสต๊อกเพียง ${maxQty} ชิ้น`, 
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                    if (val !== parseInt(hiddenInput.value)) {
                        performUpdate(val);
                    } else {
                        displayInput.value = val;
                    }
                });

                displayInput?.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.target.blur(); // Triggers the change event
                    }
                });
            });

            // Checkout flow
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'ยืนยันรายการเบิก?',
                        text: 'กรุณากรอกหมายเหตุวัตถุประสงค์เพื่อการอนุมัติและตรวจสอบความโปร่งใส',
                        icon: 'info',
                        input: 'textarea',
                        inputLabel: 'ระบุจุดประสงค์/สถานที่ใช้งานอุปกรณ์...',
                        inputPlaceholder: 'ตัวอย่าง: เพื่อใช้สำหรับงานบำรุงรักษาในส่วนงาน...',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'ยืนยันส่งคำขอเบิก',
                        cancelButtonText: 'ยกเลิก',
                        customClass: { 
                            popup: 'rounded border border-slate-200 text-xs font-semibold', 
                            confirmButton: 'rounded font-bold px-4 py-2 bg-red-600 border border-red-600 text-white shadow-sm hover:bg-red-700 transition-all text-xs', 
                            cancelButton: 'rounded font-bold px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all text-xs' 
                        },
                        preConfirm: (val) => {
                            if (!val || !val.trim()) { 
                                Swal.showValidationMessage('กรุณาระบุวัตถุประสงค์เพื่อยืนยันรายการคำขอครับ'); 
                            }
                            return val;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('checkout-form');
                            let input = document.createElement('input');
                            input.type = 'hidden'; 
                            input.name = 'remarks'; 
                            input.value = result.value;
                            form.appendChild(input);
                            checkoutBtn.disabled = true;
                            checkoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังประมวลผล...';
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush