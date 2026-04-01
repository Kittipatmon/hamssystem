@extends('layouts.serviceitem.appservice')

@push('styles')
<style>
    :root {
        --premium-red: #dc2626;
        --premium-slate: #1e293b;
        --card-radius: 40px;
    }
    
    body {
        background-color: #f8fafc;
    }

    @keyframes entryReveal {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .reveal-item {
        animation: entryReveal 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* 📦 Header Container Frame */
    .header-frame {
        background: white;
        border-radius: 30px;
        padding: 30px 40px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 4px 20px -5px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    @media (min-width: 768px) {
        .header-frame {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }

    .header-icon-box {
        width: 52px;
        height: 52px;
        background: var(--premium-red);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.3);
        flex-shrink: 0;
    }

    .breadcrumb-item {
        font-size: 13px;
        font-weight: 700;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .breadcrumb-item.active {
        color: var(--premium-red);
    }

    /* 🏷️ Item Cards */
    .item-card {
        background: white;
        border-radius: var(--card-radius);
        padding: 25px;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.02);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
    }

    @media (min-width: 640px) {
        .item-card {
            flex-direction: row;
            padding: 35px;
            gap: 30px;
        }
    }

    .item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px -15px rgba(0,0,0,0.06);
        border-color: rgba(220, 38, 38, 0.1);
    }

    .image-box {
        width: 100%;
        height: 180px;
        background: #fbfbfc;
        border: 2px solid #f1f5f9;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        flex-shrink: 0;
    }

    @media (min-width: 640px) {
        .image-box {
            width: 140px;
            height: 140px;
        }
    }

    .image-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .qty-pill {
        display: inline-flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 4px;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #475569;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
    }

    .qty-btn:hover:not(:disabled) {
        background: var(--premium-red);
        color: white;
        border-color: var(--premium-red);
    }

    .qty-input {
        width: 50px;
        text-align: center;
        font-weight: 800;
        font-size: 16px;
        border: none;
        background: transparent;
        color: var(--premium-slate);
    }

    /* 🏷️ Sidebar */
    .sidebar-frame {
        background: white;
        border-radius: var(--card-radius);
        padding: 40px;
        border: 1px solid rgba(220, 38, 38, 0.05);
        box-shadow: 0 20px 50px -10px rgba(0,0,0,0.05);
        position: sticky;
        top: 100px;
    }

    .btn-checkout {
        background: linear-gradient(135deg, var(--premium-red) 0%, #991b1b 100%);
        color: white;
        width: 100%;
        padding: 22px;
        border-radius: 25px;
        font-weight: 800;
        font-size: 18px;
        box-shadow: 0 15px 35px -5px rgba(220, 38, 38, 0.35);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .btn-checkout:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px -5px rgba(220, 38, 38, 0.4);
    }

    .btn-add-more {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 800;
        color: #64748b;
        transition: all 0.3s;
    }

    .btn-add-more:hover {
        border-color: var(--premium-red);
        color: var(--premium-red);
        background: #fef2f2;
        transform: translateX(-5px);
    }

    .price-text {
        color: var(--premium-red);
        font-size: 32px;
        font-weight: 950;
        font-family: 'Inter', system-ui;
        letter-spacing: -1.5px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- 🏷️ HEADER FRAME SECTION -->
    <div class="header-frame reveal-item">
        <div class="flex items-center gap-5">
            <div class="header-icon-box">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="space-y-1">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none uppercase md:normal-case">ตะกร้าอุปกรณ์</h1>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('welcome') }}" class="breadcrumb-item hover:text-red-600 transition-colors">หน้าหลัก</a>
                    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
                    <a href="{{ route('items.itemsalllist') }}" class="breadcrumb-item hover:text-red-600 transition-colors">รายการอุปกรณ์</a>
                    <i class="fa-solid fa-chevron-right text-[8px] text-slate-300"></i>
                    <span class="breadcrumb-item active italic">ตรวจสอบตะกร้า</span>
                </div>
            </div>
        </div>
        
        <a href="{{ route('items.itemsalllist') }}" class="btn-add-more group w-fit">
            <i class="fa-solid fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
            เลือกอุปกรณ์เพิ่ม
        </a>
    </div>

    @if ($cart_items->count() == 0)
        <!-- 🛒 EMPTY STATE -->
        <div class="bg-white rounded-[3rem] p-20 shadow-xl border border-slate-50 flex flex-col items-center text-center reveal-item">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                <i class="fa-solid fa-cart-plus text-4xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800 mb-2">ตะกร้าของคุณยังไม่มีอุปกรณ์</h2>
            <p class="text-slate-400 mb-10 max-w-sm leading-relaxed">กรุณาเลือกอุปกรณ์ที่คุณต้องการเบิกใช้งานเข้าระบบก่อนทำรายการครับ</p>
            <a href="{{ route('items.itemsalllist') }}" class="btn-checkout max-w-xs mx-auto">
                ไปที่หน้าอุปกรณ์ <i class="fa-solid fa-magnifying-glass text-sm opacity-50"></i>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- 📦 ITEM LIST (LEFT) -->
            <div class="lg:col-span-8 space-y-5">
                <div class="flex items-center justify-between px-4">
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-[0.3em]">Items In Cart</span>
                    <span class="text-xs font-bold text-slate-400">{{ $cart_items->count() }} Items Selected</span>
                </div>

                @foreach ($cart_items as $cart_item)
                <div class="item-card reveal-item" style="animation-delay: {{ $loop->index * 0.1 }}s">
                    
                    <!-- Image -->
                    <div class="image-box">
                        @if(isset($cart_item->item->item_pic) && $cart_item->item->item_pic)
                            <img src="{{ asset('images/items/' . $cart_item->item->item_pic) }}" 
                                 onclick="showImageModal('{{ asset('images/items/' . $cart_item->item->item_pic) }}', '{{ $cart_item->item->name }}', `{{ addslashes($cart_item->item->description) }}`)">
                        @else
                            <i class="fa-solid fa-cube text-3xl text-slate-100"></i>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0 pr-0 sm:pr-10">
                        <div class="flex justify-between items-start mb-2 group-hover:bg-slate-50">
                            <div>
                                <span class="text-[10px] font-black text-red-500 uppercase tracking-widest block">{{ optional($cart_item->item)->item_code }}</span>
                                <h3 class="text-2xl font-black text-slate-800 tracking-tight leading-tight mt-1">{{ optional($cart_item->item)->name }}</h3>
                            </div>
                            <form action="{{ route('cartitem.destroy', $cart_item->cart_id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-slate-200 hover:text-red-500 transition-colors" onclick="return confirm('ลบสินค้านี้ใช่หรือไม่?')">
                                    <i class="fa-solid fa-trash-can text-lg"></i>
                                </button>
                            </form>
                        </div>
                        
                        <p class="text-sm text-slate-400 italic mb-6 line-clamp-1 opacity-80">{{ optional($cart_item->item)->description ?: 'ไม่มีรายละเอียดพัสดุอุปกรณ์' }}</p>

                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <form action="{{ route('cartitem.update', $cart_item->cart_id) }}" method="POST" class="cart-qty-form" data-max-qty="{{ optional($cart_item->item)->quantity ?? 999999 }}" data-min-qty="1">
                                    @csrf
                                    <div class="qty-pill">
                                        <button type="button" class="qty-btn" data-action="decrement" {{ $cart_item->cart_quantity <= 1 ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                        </button>
                                        <input type="text" name="display_quantity" value="{{ $cart_item->cart_quantity ?? 1 }}" readonly class="qty-input" />
                                        <button type="button" class="qty-btn" data-action="increment" {{ (optional($cart_item->item)->quantity ?? 0) > 0 && $cart_item->cart_quantity >= (optional($cart_item->item)->quantity ?? 0) ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="quantity" value="{{ $cart_item->cart_quantity ?? 1 }}">
                                </form>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Units</span>
                            </div>

                            <div class="text-left sm:text-right">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest block">Total unit cost</span>
                                <div class="price-text">
                                    <span class="text-[0.6em] mr-1">฿</span>{{ number_format((optional($cart_item->item)->per_unit ?? 0) * $cart_item->cart_quantity, 2) }}
                                </div>
                                <span class="text-[11px] font-bold text-slate-300">@ ฿{{ number_format(optional($cart_item->item)->per_unit, 2) }} each</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- 💰 SUMMARY SIDEBAR (RIGHT) -->
            <div class="lg:col-span-4">
                <div class="sidebar-frame reveal-item" style="animation-delay: 0.3s">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                            <i class="fa-solid fa-receipt text-sm"></i>
                        </div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">สรุปรายการเบิก</h2>
                    </div>

                    <div class="space-y-4 mb-10">
                        <div class="flex justify-between items-center px-2">
                            <span class="text-sm font-bold text-slate-400">จำนวนรวมทั้งหมด</span>
                            <span class="text-sm font-black text-slate-800 bg-slate-50 px-4 py-1 rounded-full border border-slate-100">{{ $cart_items->sum('cart_quantity') }} ชิ้น</span>
                        </div>
                        <div class="flex justify-between items-center px-2">
                            <span class="text-sm font-bold text-slate-400">ภาษีมูลค่าเพิ่ม (7%)</span>
                            <span class="text-[10px] font-black text-slate-300 italic uppercase">Included</span>
                        </div>
                        
                        <div class="py-8 bg-slate-900 border-4 border-white shadow-2xl rounded-[2.5rem] flex flex-col items-center justify-center mt-6">
                            <span class="text-[10px] font-black text-white/50 uppercase tracking-[0.4em] mb-2 leading-none">GRAND TOTAL</span>
                            <div class="text-[40px] font-black text-white font-mono tracking-tighter leading-none">
                                ฿{{ number_format($cart_items->sum(function($item) { return ((optional($item->item)->per_unit ?? 0) * ($item->cart_quantity ?? 0)); }), 2) }}
                            </div>
                        </div>
                    </div>

                    <button id="checkout-btn" class="btn-checkout">
                        ยืนยันการเบิก <i class="fa-solid fa-arrow-right-long text-xs opacity-50"></i>
                    </button>
                    
                    <p class="text-[10px] text-center text-slate-400 mt-6 leading-relaxed italic px-4">
                        คุณตกลงตามเงื่อนไขการเบิกพัสดุและรหัสพนักงานของคุณจะถูกใช้เพื่อยืนยันตัวตนเข้าระบบ
                    </p>

                    <form id="checkout-form" action="{{ route('cartitem.checkout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- 🖼️ MODAL -->
<dialog id="imageModal" class="modal backdrop-blur-md">
    <div class="modal-box rounded-[3rem] p-0 overflow-hidden max-w-2xl bg-white">
        <div class="p-8 bg-slate-900 flex justify-between items-center text-white">
            <h3 class="font-black text-xl flex items-center gap-3">
                <i class="fa-solid fa-eye text-red-500"></i>
                <span id="imageModalLabel"></span>
            </h3>
            <form method="dialog"><button class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-all">✕</button></form>
        </div>
        <div class="p-10">
            <div class="w-full h-80 rounded-[2rem] overflow-hidden bg-slate-50 mb-8 border border-slate-100 shadow-inner group">
                <img id="modalImage" src="" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-1000" />
            </div>
            <div id="modalDescription" class="text-slate-600 leading-relaxed text-sm bg-slate-50 p-8 rounded-[2rem] border border-slate-200 italic font-medium"></div>
        </div>
        <div class="px-10 pb-10">
            <form method="dialog">
                <button class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-black transition-all shadow-xl active:scale-95 text-xs uppercase tracking-widest">
                    ตกลง
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
        document.getElementById('modalDescription').textContent = description || 'ข้อมูลรายอุปกรณ์จากฐานข้อมูลพัสดุส่วนกลาง';
        if (dialog) dialog.showModal();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // QTY Logic
        document.querySelectorAll('.cart-qty-form').forEach(form => {
            const minusBtn = form.querySelector('.qty-btn[data-action="decrement"]');
            const plusBtn = form.querySelector('.qty-btn[data-action="increment"]');
            const displayInput = form.querySelector('.qty-input');
            const hiddenInput = form.querySelector('input[name="quantity"]');
            const maxQty = parseInt(form.dataset.maxQty || '999999');

            function performUpdate(newVal) {
                displayInput.value = newVal;
                hiddenInput.value = newVal;
                document.body.style.cursor = 'wait';
                [minusBtn, plusBtn].forEach(b => { if(b) b.disabled = true; });

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                }).then(() => location.reload());
            }

            minusBtn?.addEventListener('click', () => { if (parseInt(displayInput.value) > 1) performUpdate(parseInt(displayInput.value) - 1); });
            plusBtn?.addEventListener('click', () => { 
                let val = parseInt(displayInput.value);
                if (val < maxQty) performUpdate(val + 1);
                else {
                    Swal.fire({ icon: 'warning', title: 'เกินสต็อก', text: `คุณเลือกได้สูงสุด ${maxQty} ชิ้น`, toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                }
            });
        });

        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'ยืนยันรายการเบิก?',
                    text: 'กรุณากรอกหมายเหตุเพื่อความโปร่งใสในการตรวจสอบระบบ',
                    icon: 'info',
                    input: 'textarea',
                    inputLabel: 'หมายเหตุการเบิก (Reason for requisition)',
                    inputPlaceholder: 'ระบุวัตถุประสงค์การเบิกพัสดุในครั้งนี้...',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'ยืนยันรายการเบิก',
                    cancelButtonText: 'ยกเลิก',
                    customClass: { popup: 'rounded-[3rem] p-8', confirmButton: 'rounded-2xl font-black px-10 py-4', cancelButton: 'rounded-2xl font-black px-10 py-4' },
                    preConfirm: (val) => {
                        if (!val || !val.trim()) { Swal.showValidationMessage('กรุณาระบุหมายเหตุเพื่อยืนยันรายการครับ'); }
                        return val;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('checkout-form');
                        let input = document.createElement('input');
                        input.type = 'hidden'; input.name = 'remarks'; input.value = result.value;
                        form.appendChild(input);
                        checkoutBtn.disabled = true;
                        checkoutBtn.innerHTML = '<span class="loading loading-spinner"></span> ประมวลผล...';
                        form.submit();
                    }
                });
            });
        }
    });
</script>
@endpush