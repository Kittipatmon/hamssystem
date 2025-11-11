@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: none; padding: 0;">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" style="color: #2d2e4a; font-weight: 500; font-size: 1.10rem; text-decoration: none;">Home</a>
                    </li>
                    <li class="breadcrumb-separator" aria-hidden="true" style="display: flex; align-items: center;">
                        &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;&nbsp;&nbsp;
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #2d2e4a; font-weight: 500; font-size: 1.10rem;">
                        Shopping Cart
                    </li>

                </ol>
            </nav>
        </div>
        <div class="card-body" width="100%">

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

            @if ($cart_items->count() == 0)
            <div class="text-center" style="padding: 60px 0;">
                <h3 style="color: #2d2e4a; margin-bottom: 30px;">ยังไม่มีสินค้าในตะกร้าของคุณ</h3>
                <div>
                    <i class="fa fa-shopping-cart fa-2x"></i>
                </div>
                <br>
                <a href="{{ url('/') }}" class="btn" style="background: linear-gradient(180deg, #19a0ff 0%, #0066e6 100%); color: #fff; font-weight: bold; padding: 10px 0; width: 280px; border: none; border-radius: 8px; box-shadow: none; text-align: center;">
                    เลือกซื้อสินค้าต่อ
                </a>
            </div>
            @endif

            @if ($cart_items->count() > 0)
            <div class="mb-3">
                <label>
                    <!-- <input type="checkbox" checked disabled> -->
                    เลือกทั้งหมด ({{ $cart_items->count() }})
                </label>
            </div>
            <div class="card mb-4" style="border: 1px solid #e0e0e0;">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-bold" style="font-size: 1.1rem;">ตะกร้าอุปกรณ์ที่ต้องการเบิก</span>
                        <!-- <div style="font-size: 0.95rem; color: #6c757d;">
                                แพ็คสินค้าและนำส่งให้บริษัทขนส่งโดย OfficeMate
                            </div> -->
                    </div>
                </div>
                <div class="card-body p-0">
                    @foreach ($cart_items as $cart_item)
                    <div class="row align-items-center py-3 px-4 border-bottom">
                        <div class="col-auto">
                            <input type="checkbox" checked disabled>
                        </div>
                        <div class="col-auto">

                            @if(isset($cart_item->item->item_pic) && $cart_item->item->item_pic)
                            <a href="javascript:void(0);" onclick="showImageModal('{{ asset('images/' . $cart_item->item->item_pic) }}', '{{ $cart_item->item->name }}', `{{ addslashes($cart_item->item->description) }}`)">
                                <img src="{{ asset('images/' . $cart_item->item->item_pic) }}" alt="{{ $cart_item->item->name }}" class="img-fluid" style="width: 80px; height: 80px; border-radius: 8px; border: 1px solid #eee;">
                            </a>
                            @else
                            <a href="javascript:void(0);" onclick="showImageModal('{{ asset('images/no-image.png') }}', 'No image', `{{ addslashes($item->description) }}`)">
                                <img src="{{ asset('images/no-image.png') }}" alt="No image" class="img-fluid" style="width: 80px; height: 80px; border-radius: 8px; border: 1px solid #eee;">
                            </a>
                            @endif

                            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel"></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img id="modalImage" src="" alt="" class="img-fluid" style="max-height: 400px;">
                                            <div class="d-flex align-items-start gap-2 mt-3">
                                                <span class="fw-bold mt-1">รายละเอียด :</span>
                                                <div id="modalDescription" style="text-align: left; white-space: pre-line;" class="mt-1"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div style="font-weight: 600; font-size: 1.1rem; color: #2d2e4a;">
                                {{ $cart_item->item->name ?? "-" }}
                            </div>
                            <div style="color: #6c757d; font-size: 0.95rem;">
                                {{ $cart_item->item->item_code ?? "" }}
                            </div>
                            <div class="mt-1 d-flex align-items-center gap-2">
                                <form action="{{ route('cartitem.update', $cart_item->cart_id) }}" method="POST" class="d-inline-flex align-items-center cart-qty-form">
                                    @csrf
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <!-- Quantity by piece -->
                                        <div class="input-group input-group-sm" style="width: 170px;">
                                            <button type="button" class="btn btn-outline-primary btn-qty" data-action="decrement" {{ $cart_item->cart_quantity <= 1 ? 'disabled' : '' }}>
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" name="display_quantity" value="{{ $cart_item->cart_quantity ?? 1 }}" class="form-control text-center" style="max-width: 60px;" readonly>
                                            <button type="button" class="btn btn-outline-primary btn-qty" data-action="increment">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <span class="input-group-text bg-white border-0" style="font-weight: 500;">ชิ้น</span>
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
                                    <span class="ms-2 qty-loading" style="display:none; min-width:120px;">
                                        <span style="font-weight: bold; color:rgb(255, 73, 73); letter-spacing: 2px;">
                                            <span style="display:inline-block; vertical-align:middle;">
                                                <span class="spinner-border spinner-border-sm text-danger" role="status" aria-hidden="true"></span>
                                            </span>
                                            <span style="margin-left:8px;">กำลังโหลดข้อมูล</span>
                                        </span>
                                    </span>
                                    <input type="hidden" name="quantity" value="{{ $cart_item->cart_quantity ?? 1 }}">
                                    <input type="hidden" name="items_per_pack" value="{{ $cart_item->cart_quantity_pack ?? 1 }}">
                                </form>
                            </div>

                        </div>
                        <div class="col-auto text-end">
                            <div style="font-weight: 600; font-size: 1.1rem; color:#0d6efd;"  >
                                ฿ {{ $cart_item->item ? number_format($cart_item->item->per_unit, 2) : "-" }}
                            </div>
                            <div style="color: #0d6efd; font-size: 0.95rem;">
                                ราคาต่อ(ชิ้น)
                            </div>

                             <!-- <div style="font-weight: 600; font-size: 1.1rem; color: #198754;">
                                ฿ {{ $cart_item->item ? number_format($cart_item->item->per_pack, 2) : "-" }}
                            </div>
                            <div style="color: #198754; font-size: 0.95rem;">
                                ราคาต่อ(แพ็ค)
                            </div> -->
                        </div>
                        <div class="col-auto text-end">
                            <div style="font-weight: 600; font-size: 1.1rem; color: #0d6efd;">
                                ฿ {{ $cart_item->item ? number_format($cart_item->item->per_unit * $cart_item->cart_quantity, 2) : "-" }}
                            </div>
                            <div style="color: #0d6efd; font-size: 0.95rem;">
                                ราคารวม(ชิ้น)
                            </div>
                             <!-- <div style="font-weight: 600; font-size: 1.1rem; color: #198754;">
                                ฿ {{ $cart_item->item ? number_format($cart_item->item->per_pack * $cart_item->cart_quantity_pack, 2) : "-" }}
                            </div>
                            <div style="color: #198754; font-size: 0.95rem;">
                                ราคารวม(แพ็ค)
                            </div> -->
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('cartitem.destroy', $cart_item->cart_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="ลบสินค้า" onclick="return confirm('Are you sure you want to remove this item from the cart?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer bg-white">
                    <div class="row justify-content-end">
                        <div class="col-auto">
                            <div style="font-weight: bold; font-size: 1.3rem; color: #2d2e4a;">จำนวนรวมทั้งหมด</div>
                        </div>
                        <div class="col-auto text-end">
                            <div style="font-weight: bold; font-size: 1.3rem;">
                                <!-- ฿ {{ number_format($cart_items->sum(function($item) {
                                    return ($item->item->per_unit * $item->cart_quantity) + ($item->item->per_pack * $item->cart_quantity_pack);
                                }), 2) }} -->

                                   ฿ {{ number_format($cart_items->sum(function($item) {
                                    return ($item->item->per_unit * $item->cart_quantity);
                                }), 2) }}

                            </div>
                        </div>

                    </div>
                    <div class="row justify-content-end mt-2">
                        <div class="col-auto">
                            <a href="#" id="checkout-btn" class="btn btn-sm" style="background: linear-gradient(180deg, #19a0ff 0%, #0066e6 100%); color: #fff; font-weight: bold; padding: 10px 0; width: 250px; border: none; border-radius: 8px; box-shadow: none; text-align: center;">
                                ยืนยันการเบิกอุปกรณ์
                            </a>
                            <form id="checkout-form" action="{{ route('cartitem.checkout') }}" method="POST" style="display:none;">
                                @csrf
                                
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showImageModal(src, title, description = '') {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModalLabel').textContent = title;

        document.getElementById('modalDescription').innerHTML = description ? description : '<span class="text-muted">ไม่มีรายละเอียด</span>';
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.cart-qty-form').forEach(function(form) {
            // ชิ้น
            const minusBtn = form.querySelector('.btn-qty[data-action="decrement"]');
            const plusBtn = form.querySelector('.btn-qty[data-action="increment"]');
            const qtyInput = form.querySelector('input[name="display_quantity"]');
            const hiddenQty = form.querySelector('input[name="quantity"]');
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
                loading.style.display = 'inline-block';
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
                    loading.style.display = 'none';
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

            if (minusBtn) {
                minusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack.value) || 1;
                    if (qty > 1) {
                        submitForm(qty - 1, qtyPack);
                    }
                });
            }
            if (plusBtn) {
                plusBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    let qty = parseInt(qtyInput.value) || 1;
                    let qtyPack = parseInt(qtyInputPack.value) || 1;
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
    document.getElementById('checkout-btn').addEventListener('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'ยืนยันการเบิกอุปกรณ์?',
            text: "คุณต้องการดำเนินการต่อหรือไม่",
            icon: 'question',
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
                let form = document.getElementById('checkout-form');
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
</script>
@endpush