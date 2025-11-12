@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="card">
        <!-- <div class="card-header text-center" style="background: linear-gradient(90deg,rgb(206, 76, 76) 0%,rgb(237, 87, 87) 100%);"> -->
        <div class="card-header text-center" style="background: linear-gradient(90deg,rgb(232, 123, 34) 0%,rgb(237, 202, 87) 100%);">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: none; padding: 0;">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" style="color:rgb(255, 255, 255); font-weight: 500; font-size: 1.10rem; text-decoration: none;">Home</a>
                    </li>
                    <li class="breadcrumb-separator" aria-hidden="true" style="display: flex; align-items: center; color: #fff;">
                        &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;&nbsp;&nbsp;
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color:rgb(255, 255, 255); font-weight: 500; font-size: 1.10rem;">
                        รายละเอียดในการเบิกของ (อยู่ระหว่างจัดเตรียมของ) <i class="fa-solid fa-box-open"></i>
                    </li>

                </ol>
            </nav>
        </div>
        <div class="card-body" width="100%">
            @php
            $has_unit = $requisition->requisition_items->where('quantity', '>', 0)->count() > 0;
            $has_pack = $requisition->requisition_items->where('quantity_pack', '>', 0)->count() > 0;
            $total_unit = 0;
            $total_pack = 0;
            @endphp
            <table class="table table-bordered mb-0" style="background: #f8fbff;">
                <thead style="background: #d9eaff;">
                    <tr class="text-center">
                        <th style="width: 3%; background-color:rgb(247, 237, 182);">ลำดับ</th>
                        <th style="width: 20%;">รายการอุปกรณ์</th>
                        <th style="width: 8%;">จำนวน</th>
                        <th style="width: 10%;">ราคา(ชิ้น)</th>
                        <th style="width: 12%;">ราคารวม(ชิ้น)</th>
                        <th style="background-color:rgb(247, 237, 182);">&nbsp;</th>
                        <th style="width: 10%;">จำนวน (แพ็ค)</th>
                        <th style="width: 12%;">ราคา(แพ็ค)</th>
                        <th style="width: 15%;">ราคารวม(แพ็ค)</th>
                        <th style="width: 10%; background-color:rgb(247, 237, 182);">
                            <!-- ใช้คำว่าอะไรดี -->
                            <span class="text-center">ตรวจสอบ</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($requisition->requisition_items as $requisition_item)
                    <tr>
                        <td style="background-color:rgb(247, 237, 182);">{{ $i++ }}</td>
                        <td>{{ $requisition_item->item->name ?? '-' }}</td>
                        <td class="text-end">
                            @if($requisition_item->quantity > 0)
                            {{ $requisition_item->quantity }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">
                            @if($requisition_item->quantity > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_unit, 2) }} บาท
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">
                            @if($requisition_item->quantity > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_unit * $requisition_item->quantity, 2) }} บาท
                            @php $total_unit += $requisition_item->item->per_unit * $requisition_item->quantity; @endphp
                            @else
                            -
                            @endif
                        </td>
                        <td style="background-color:rgb(247, 237, 182);">&nbsp;</td>
                        <td class="text-end">
                            @if($requisition_item->quantity_pack > 0)
                            {{ $requisition_item->quantity_pack }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">
                            @if($requisition_item->quantity_pack > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_pack, 2) }} บาท
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-end">
                            @if($requisition_item->quantity_pack > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_pack * $requisition_item->quantity_pack, 2) }} บาท
                            @php $total_pack += $requisition_item->item->per_pack * $requisition_item->quantity_pack; @endphp
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center" style="background-color:rgb(247, 237, 182);">
                            <input type="checkbox"
                                class="form-check-input check-item-checkbox"
                                data-id="{{ $requisition_item->requistionitem_id }}"
                                @if($requisition_item->check_item) checked @endif
                            >
                            <!-- <i class="fa-solid fa-check text-success ms-2 check-icon"
                                                                       style="display: {{ $requisition_item->check_item ? 'inline' : 'none' }}"></i> -->
                            @if($requisition_item->check_item == '1')
                            <i class="fa-solid fa-check text-success ms-2 check-icon"></i>
                            @endif
                        </td>
                        <script>
                            $(document).ready(function() {
                                $('#item_{{ $requisition_item->item_id }}').on('change', function() {
                                    if ($(this).is(':checked')) {
                                        $('#check_icon_{{ $requisition_item->item_id }}').show();
                                    } else {
                                        $('#check_icon_{{ $requisition_item->item_id }}').hide();
                                    }
                                });
                            });
                        </script>
                    </tr>
                    @endforeach
                    <tr style="background: #fff8dc;">
                        <td colspan="9" class="text-end fw-bold" style="background-color:rgb(247, 237, 182);">ราคารวมทั้งหมด</td>
                        <td class="fw-bold" style="background-color:rgb(247, 237, 182);">
                            {{ number_format($requisition->total_price, 2) }} บาท
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-3">
                <a href="#" class="btn btn-success btn-submit-req" data-id="{{ $requisition->requisitions_id }}" title="คลิกเมื่อจัดเตรียมอุปกรณ์เสร็จสิ้น">
                    <i class="fa-solid fa-check"></i> จัดเตรียมเสร็จสิ้น
                </a>
                <a href="#" class="btn btn-danger btn-cancel-req" data-id="{{ $requisition->requisitions_id }}" title="ยกเลิกการจัดเตรียม">
                    <i class="fa-solid fa-ban"></i> ยกเลิก
                </a>
                <form id="submit-req-form-{{ $requisition->requisitions_id }}" action="{{ route('requisition.submit', $requisition->requisitions_id) }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="packing_staff_comment" value="">
                </form>
                <form id="cancel-req-form-{{ $requisition->requisitions_id }}" action="{{ route('requisition.cancel', $requisition->requisitions_id) }}" method="POST" style="display:none;">
                    @csrf
                    <input type="hidden" name="packing_staff_comment" value="">
                </form>
            </div>
            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).ready(function() {
                    $('.btn-submit-req').on('click', function(e) {
                        e.preventDefault();
                        const id = $(this).data('id');
                        Swal.fire({
                            title: 'ยืนยันการจัดเตรียมเสร็จสิ้น?',
                            text: "คุณต้องการส่งรายการนี้หรือไม่",
                            icon: 'question',
                            input: 'textarea',
                            // inputLabel: 'กรุณากรอกหมายเหตุการจัดเตรียม',
                            inputPlaceholder: 'ระบุหมายเหตุ...',
                            inputAttributes: {
                                'aria-label': 'ระบุหมายเหตุ'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'ใช่, ส่งรายการ',
                            cancelButtonText: 'ยกเลิก',
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'กรุณากรอกหมายเหตุ';
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#submit-req-form-' + id + ' input[name="packing_staff_comment"]').val(result.value);
                                $('#submit-req-form-' + id).submit();
                            }
                        });
                    });

                    $('.btn-cancel-req').on('click', function(e) {
                        e.preventDefault();
                        const id = $(this).data('id');
                        Swal.fire({
                            title: 'ยืนยันการยกเลิก?',
                            text: "คุณต้องการยกเลิกรายการนี้ใช่หรือไม่",
                            icon: 'warning',
                            input: 'textarea',
                            // inputLabel: 'กรุณากรอกเหตุผลการยกเลิก',
                            inputPlaceholder: 'ระบุเหตุผล...',
                            inputAttributes: {
                                'aria-label': 'ระบุเหตุผล'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'ใช่, ยกเลิก',
                            cancelButtonText: 'กลับ',
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'กรุณากรอกเหตุผล';
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#cancel-req-form-' + id + ' input[name="packing_staff_comment"]').val(result.value);
                                $('#cancel-req-form-' + id).submit();
                            }
                        });
                    });
                });
            </script>
            @endpush
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.btn-expand').on('click', function() {
            const button = $(this);
            const id = button.data('id');
            const expandRow = $('#expandRow' + id);

            // Toggle visibility
            expandRow.toggle();

            // Toggle icon
            const icon = button.find('i');
            if (expandRow.is(':visible')) {
                icon.removeClass('fa-plus').addClass('fa-minus');
            } else {
                icon.removeClass('fa-minus').addClass('fa-plus');
            }
        });

        $('.check-item-checkbox').on('change', function() {
            const id = $(this).data('id');
            const checked = $(this).is(':checked') ? 1 : 0;
            const $icon = $(this).siblings('.check-icon');
            $.ajax({
                url: '/requisition_items/' + id + '/check',
                type: 'PATCH',
                data: {
                    check_item: checked,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (checked) {
                        $icon.show();
                    } else {
                        $icon.hide();
                    }
                },
                error: function(xhr) {
                    alert('เกิดข้อผิดพลาดในการอัปเดตสถานะ');
                }
            });
        });
    });
</script>
@endpush