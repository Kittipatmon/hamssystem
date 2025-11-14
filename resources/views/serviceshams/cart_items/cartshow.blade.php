@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="card bg-base-100 shadow-md border border-base-200">
        
        <div class="card-body pt-0">
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a>หน้าหลัก</a></li>
                    <li><a href="{{ route('items.itemsalllist') }}">รายการอุปกรณ์</a></li>
                    <li>
                        <a class="font-bold text-red-500">ตะกร้าอุปกรณ์ที่เลือก</a>
                    </li>
                </ul>
            </div>
            @if (session('success'))
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: @json(session('success')),
                        confirmButtonColor: '#3085d6'
                    });
                });
            </script>
            @endpush
            @endif
            @if (session('error'))
            @push('scripts')
            <script>
                (function run() {
                    if (typeof Swal === 'undefined') {
                        // Fallback if SweetAlert2 isn't ready
                        alert(@json(session('error')));
                        return;
                    }
                    if (document.readyState !== 'loading') {
                        Swal.fire({
                            icon: 'error',
                            title: 'ไม่สำเร็จ',
                            text: @json(session('error')),
                            confirmButtonColor: '#d33'
                        });
                    } else {
                        document.addEventListener('DOMContentLoaded', function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'ไม่สำเร็จ',
                                text: @json(session('error')),
                                confirmButtonColor: '#d33'
                            });
                        });
                    }
                })();
            </script>
            @endpush
            @endif

            @if ($cart_items->count() == 0)
            <div class="flex flex-col items-center justify-center py-24">
                <h3 class="text-2xl font-semibold text-base-content mb-6">ยังไม่มีสินค้าในตะกร้าของคุณ</h3>
                <div class="text-primary mb-6"><i class="fa fa-shopping-cart fa-3x" style="color: #7a1b1b"></i></div>
                <a href="{{ route('items.itemsalllist') }}" class="pill pill-active w-72 font-bold flex justify-center">เลือกซื้อสินค้าต่อ</a>
            </div>
            @endif

            @if ($cart_items->count() > 0)
            <div class="">
                <span class="text-sm text-base-content/70">เลือกทั้งหมด ({{ $cart_items->count() }})</span>
            </div>
            <div class="card bg-base-100 border border-base-200 mb-6">
                <div class="card-body pb-0">
                    <span class="text-sm font-semibold">ตะกร้าอุปกรณ์ที่ต้องการเบิก</span>
                </div>
                <div class="divide-y">
                    @foreach ($cart_items as $cart_item)
                    <div class="flex flex-wrap items-center gap-4 py-4 px-5">
                        <div class="flex items-center">
                            <input type="checkbox" checked disabled class="checkbox checkbox-primary" />
                        </div>
                        <div class="flex items-center">

                            @if(isset($cart_item->item->item_pic) && $cart_item->item->item_pic)
                            <a href="javascript:void(0);" onclick="showImageModal('{{ asset('images/' . $cart_item->item->item_pic) }}', '{{ $cart_item->item->name }}', `{{ addslashes($cart_item->item->description) }}`)" class="block">
                                <img src="{{ asset('images/items/' . $cart_item->item->item_pic) }}" alt="{{ $cart_item->item->name }}" class="w-20 h-20 rounded-md border" />
                            </a>
                            @else
                            <a href="javascript:void(0);" onclick="showImageModal('{{ asset('images/no-image.png') }}', 'No image', `{{ addslashes($cart_item->item->description ?? '') }}`)" class="block">
                                <img src="{{ asset('images/no-image.png') }}" alt="No image" class="w-20 h-20 rounded-md border" />
                            </a>
                            @endif

                            <!-- modal defined once at bottom -->
                        </div>
                        <div class="flex-1 min-w-[220px]">
                            <div class="font-semibold text-base-content text-sm">
                                {{ optional($cart_item->item)->name ?? "-" }}
                            </div>
                            <div class="text-xs text-base-content/60">{{ optional($cart_item->item)->item_code ?? "" }}</div>
                            <div class="mt-2">
                                <form action="{{ route('cartitem.update', $cart_item->cart_id) }}" method="POST" class="cart-qty-form inline-flex items-center" data-max-qty="{{ optional($cart_item->item)->quantity ?? 999999 }}" data-min-qty="1">
                                    @csrf
                                    <div class="flex flex-wrap items-center gap-4">
                                        <div class="join">
                                            <button type="button" class="btn btn-outline join-item btn-qty btn-sm" data-action="decrement" aria-label="ลดจำนวน" title="ลดจำนวน" {{ $cart_item->cart_quantity <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" name="display_quantity" value="{{ $cart_item->cart_quantity ?? 1 }}" readonly class="input input-bordered join-item w-16 text-center h-8" />
                                            <button type="button" class="btn btn-outline join-item btn-qty btn-sm" data-action="increment" aria-label="เพิ่มจำนวน" title="เพิ่มจำนวน" {{ (optional($cart_item->item)->quantity ?? 0) > 0 && $cart_item->cart_quantity >= (optional($cart_item->item)->quantity ?? 0) ? 'disabled' : '' }}>
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <span class="join-item px-2 text-sm font-medium">ชิ้น</span>
                                        </div>
                                        <!-- Quantity by pack -->
                                        <!-- <div class="input-group input-group-sm" style="width: 170px;">
                                            <button type="button" class="btn btn-outline-success btn-qty-pack" data-action="decrement" {{ $cart_item->cart_quantity_pack <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" name="display_items_per_pack" value="{{ $cart_item->cart_quantity_pack ?? 1 }}" class="form-control text-center" style="max-width: 60px;" readonly>
                                            <button type="button" class="btn btn-outline-success btn-qty-pack" data-action="increment">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <span class="input-group-text bg-white border-0" style="font-weight: 500;">แพ็ค</span>
                                        </div> -->
                                    </div>
                                    <span class="qty-loading ml-3 hidden min-w-[120px] font-bold text-error tracking-wide text-sm">
                                        <span class="loading loading-spinner loading-xs align-middle"></span>
                                        <span class="ml-2">กำลังโหลดข้อมูล</span>
                                    </span>
                                    <input type="hidden" name="quantity" value="{{ $cart_item->cart_quantity ?? 1 }}">
                                    <input type="hidden" name="items_per_pack" value="{{ $cart_item->cart_quantity_pack ?? 1 }}">
                                </form>
                            </div>
                        </div>
                        <div class="text-right min-w-[110px]">
                            <div class="font-semibold text-primary">฿ {{ $cart_item->item ? number_format($cart_item->item->per_unit, 2) : "-" }}</div>
                            <div class="text-xs text-primary/70">ราคาต่อ(ชิ้น)</div>
                        </div>
                        <div class="text-right min-w-[140px]">
                            <div class="font-semibold text-primary">฿ {{ $cart_item->item ? number_format($cart_item->item->per_unit * $cart_item->cart_quantity, 2) : "-" }}</div>
                            <div class="text-xs text-primary/70">ราคารวม(ชิ้น)</div>
                        </div>
                        <div>
                            <form action="{{ route('cartitem.destroy', $cart_item->cart_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error btn-sm" title="ลบสินค้า" onclick="return confirm('Are you sure you want to remove this item from the cart?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-body border-t">
                    <div class="flex flex-wrap items-center justify-end gap-4">
                        <div class="text-lg font-bold text-base-content">จำนวนรวมทั้งหมด</div>
                        <div class="text-xl font-bold text-primary">
                            ฿ {{ number_format($cart_items->sum(function($item) { return ((optional($item->item)->per_unit ?? 0) * ($item->cart_quantity ?? 0)); }), 2) }}
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <a href="#" id="checkout-btn" class="btn btn-success text-white w-64 font-bold">ยืนยันการเบิกอุปกรณ์</a>
                        <form id="checkout-form" action="{{ route('cartitem.checkout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Helper: run when DOM is ready (handles late-inserted scripts too)
    function onReady(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    function showImageModal(src, title, description = '') {
        const dialog = document.getElementById('imageModal');
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModalLabel').textContent = title;
        document.getElementById('modalDescription').innerHTML = description ? description : '<span class="text-base-content/50">ไม่มีรายละเอียด</span>';
        if (dialog) dialog.showModal();
    }

    onReady(function() {
        document.querySelectorAll('.cart-qty-form').forEach(function(form) {
            // ชิ้น
            const minusBtn = form.querySelector('.btn-qty[data-action="decrement"]');
            const plusBtn = form.querySelector('.btn-qty[data-action="increment"]');
            const qtyInput = form.querySelector('input[name="display_quantity"]');
            const hiddenQty = form.querySelector('input[name="quantity"]');
            const minQty = parseInt(form.dataset.minQty || '1');
            const maxQty = parseInt(form.dataset.maxQty || '2147483647');
            // แพ็ค
            const minusBtnPack = form.querySelector('.btn-qty-pack[data-action="decrement"]');
            const plusBtnPack = form.querySelector('.btn-qty-pack[data-action="increment"]');
            const qtyInputPack = form.querySelector('input[name="display_items_per_pack"]');
            const hiddenQtyPack = form.querySelector('input[name="items_per_pack"]');
            const loading = form.querySelector('.qty-loading');

            function submitForm(newQty, newQtyPack) {
                if (hiddenQty) hiddenQty.value = newQty;
                if (qtyInput) qtyInput.value = newQty;
                if (hiddenQtyPack) hiddenQtyPack.value = newQtyPack;
                if (qtyInputPack) qtyInputPack.value = newQtyPack;
                if (loading) loading.classList.remove('hidden');
                if (minusBtn) minusBtn.disabled = true;
                if (plusBtn) plusBtn.disabled = true;
                if (minusBtnPack) minusBtnPack.disabled = true;
                if (plusBtnPack) plusBtnPack.disabled = true;
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(response => {
                    if (loading) loading.classList.add('hidden');
                    if (response.ok) {
                        location.reload();
                    } else {
                        if (minusBtn) minusBtn.disabled = false;
                        if (plusBtn) plusBtn.disabled = false;
                        if (minusBtnPack) minusBtnPack.disabled = false;
                        if (plusBtnPack) plusBtnPack.disabled = false;
                    }
                });
            }

            function updateButtonsState(currentQty) {
                if (minusBtn) minusBtn.disabled = currentQty <= minQty;
                if (plusBtn) plusBtn.disabled = currentQty >= maxQty && maxQty > 0;
            }

            // ตั้งค่าเริ่มต้นของปุ่มตาม min/max
            let initQty = parseInt(qtyInput?.value || '1');
            updateButtonsState(initQty);

            if (minusBtn) {
                minusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack?.value || '1');
                    if (qty > minQty) {
                        submitForm(qty - 1, qtyPack);
                    } else {
                        updateButtonsState(qty);
                    }
                });
            }
            if (plusBtn) {
                plusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack?.value || '1');
                    if (maxQty > 0 && qty >= maxQty) {
                        // แจ้งเตือนว่าเกินสต็อก
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'จำนวนเกินสต็อก',
                                text: `เลือกได้สูงสุด ${maxQty} ชิ้น`,
                                position: 'top-end',
                                toast: true,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                        updateButtonsState(qty);
                        return;
                    }
                    submitForm(qty + 1, qtyPack);
                });
            }
            if (minusBtnPack) {
                minusBtnPack.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack.value) || 1;
                    if (qtyPack > 1) {
                        submitForm(qty, qtyPack - 1);
                    }
                });
            }
            if (plusBtnPack) {
                plusBtnPack.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack.value) || 1;
                    submitForm(qty, qtyPack + 1);
                });
            }
        });
    });
    // Ensure the button exists before binding (handles cases where scripts load before HTML)
    onReady(function() {
        const checkoutBtn = document.getElementById('checkout-btn');
        if (!checkoutBtn) return;
        checkoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // If SweetAlert2 isn't available, fallback to a simple prompt
            if (typeof Swal === 'undefined') {
                const remarks = prompt('กรุณากรอกหมายเหตุ (remarks)');
                if (!remarks || !remarks.trim()) return;
                const form = document.getElementById('checkout-form');
                if (!form) return;
                let remarksInput = form.querySelector('input[name="remarks"]');
                if (!remarksInput) {
                    remarksInput = document.createElement('input');
                    remarksInput.type = 'hidden';
                    remarksInput.name = 'remarks';
                    form.appendChild(remarksInput);
                }
                remarksInput.value = remarks;
                form.submit();
                return;
            }
            Swal.fire({
                title: 'ยืนยันการเบิกอุปกรณ์?',
                // text: "คุณต้องการดำเนินการต่อหรือไม่",
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'กรุณากรอกหมายเหตุ (remarks)',
                inputPlaceholder: 'โปรดระบุหมายเหตุ... (จำเป็นต้องกรอก)',
                inputAttributes: {
                    'aria-label': 'ระบุหมายเหตุ'
                },
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                preConfirm: (remarks) => {
                    if (!remarks || !remarks.trim()) {
                        Swal.showValidationMessage('กรุณากรอกหมายเหตุ');
                        return false;
                    }
                    return remarks;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // เพิ่ม input remarks ลงในฟอร์มก่อน submit
                    const form = document.getElementById('checkout-form');
                    if (!form) return; // safety guard
                    let remarksInput = form.querySelector('input[name="remarks"]');
                    if (!remarksInput) {
                        remarksInput = document.createElement('input');
                        remarksInput.type = 'hidden';
                        remarksInput.name = 'remarks';
                        form.appendChild(remarksInput);
                    }
                    remarksInput.value = result.value || '';
                    form.submit();
                }
            });
        });
    });
    </script>
    <dialog id="imageModal" class="modal">
        <div class="modal-box max-w-xl">
            <h3 class="font-bold text-lg" id="imageModalLabel"></h3>
            <img id="modalImage" src="" alt="" class="mt-4 rounded-md max-h-[400px] mx-auto" />
            <div class="mt-4">
                <span class="font-semibold">รายละเอียด :</span>
                <div id="modalDescription" class="mt-2 whitespace-pre-line text-sm"></div>
            </div>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">ปิด</button>
                </form>
            </div>
        </div>
    </dialog>
@endpush