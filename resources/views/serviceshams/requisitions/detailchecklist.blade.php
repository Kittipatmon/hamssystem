@extends('layouts.serviceitem.appservice')

@section('content')
<div>
    <div class="card w-full bg-base-100 shadow-xl">
        <div class="px-4 text-center rounded-t-2xl bg-gradient-to-r from-orange-500 to-yellow-400">
            <nav aria-label="breadcrumb">
                <div class="text-sm breadcrumbs text-white justify-center">
                    <ul>
                        <li>
                            <a href="{{ route('requisitions.reqchecklist') }}" class="text-white/90 hover:text-white font-medium">
                                รายการอุปกรณ์
                            </a>
                        </li>
                        <li>
                            <span class="font-medium text-white/80">
                                รายละเอียดในการเบิกของ (อยู่ระหว่างจัดเตรียมของ) <i class="fa-solid fa-box-open ml-2"></i>
                            </span>
                        </li>
                    </ul>
                </div>
            </nav>
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
        <div class="card-body overflow-x-auto"> {{-- card-body + overflow-x-auto for responsive table --}}
            @php
            $has_unit = $requisition->requisition_items->where('quantity', '>', 0)->count() > 0;
            $has_pack = $requisition->requisition_items->where('quantity_pack', '>', 0)->count() > 0;
            $total_unit = 0;
            $total_pack = 0;
            @endphp
            
            <table class="table table-sm"> {{-- table table-zebra --}}
                {{-- Table Head --}}
                <thead class="bg-blue-100">
                    <tr class="text-center">
                        <th class="w-[3%] bg-yellow-100">ลำดับ</th>
                        <th class="w-[20%]">รายการอุปกรณ์</th>
                        <th class="w-[8%]">จำนวน</th>
                        <th class="w-[10%]">ราคา(ชิ้น)</th>
                        <th class="w-[12%]">ราคารวม(ชิ้น)</th>
                        <th class="w-[10%] bg-yellow-100">
                            <span>ตรวจสอบ</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($requisition->requisition_items as $requisition_item)
                    <tr>
                        <td class="bg-yellow-100 text-center">{{ $i++ }}</td>
                        <td>{{ $requisition_item->item->name ?? '-' }}</td>
                        <td class="text-right">
                            @if($requisition_item->quantity > 0)
                            {{ $requisition_item->quantity }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-right">
                            @if($requisition_item->quantity > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_unit, 2) }} บาท
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-right">
                            @if($requisition_item->quantity > 0 && $requisition_item->item)
                            {{ number_format($requisition_item->item->per_unit * $requisition_item->quantity, 2) }} บาท
                            @php $total_unit += $requisition_item->item->per_unit * $requisition_item->quantity; @endphp
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center bg-yellow-100">
                            <input type="checkbox"
                                class="checkbox checkbox-primary check-item-checkbox" {{-- checkbox checkbox-primary --}}
                                data-id="{{ $requisition_item->requistionitem_id }}"
                                @if($requisition_item->check_item) checked @endif
                            >
                            @if($requisition_item->check_item == '1')
                            <i class="fa-solid fa-check text-success ml-2 check-icon"></i> {{-- ms-2 -> ml-2 --}}
                            @else
                            {{-- Add a hidden icon for the JS to toggle --}}
                            <i class="fa-solid fa-check text-success ml-2 check-icon" style="display: none;"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr class="bg-yellow-100">
                        <td colspan="5" class="text-right font-bold">ราคารวมทั้งหมด</td>
                        <td class="font-bold text-right">{{ number_format($requisition->total_price, 2) }} บาท</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-4 flex items-center justify-center gap-2">
                <a href="#" class="btn btn-success btn-submit-req text-white" data-id="{{ $requisition->requisitions_id }}" title="คลิกเมื่อจัดเตรียมอุปกรณ์เสร็จสิ้น">
                    <i class="fa-solid fa-check"></i> จัดเตรียมเสร็จสิ้น
                </a>
                <a href="#" class="btn btn-error btn-cancel-req" data-id="{{ $requisition->requisitions_id }}" title="ยกเลิกการจัดเตรียม">
                    <i class="fa-solid fa-ban"></i> ยกเลิก
                </a>
                
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
</div>
@endsection

@push('scripts')
<script>
    // Vanilla JS implementation (no jQuery dependency)
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = @json(csrf_token());
        const updateBaseUrl = @json(url('checklist/updatecheckitem'));

        // Helper: show prompt with SweetAlert2 or fallback
        function askText({ title, text, confirmText, cancelText, placeholder }) {
            if (window.Swal) {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    input: 'textarea',
                    inputPlaceholder: placeholder || '',
                    inputAttributes: { 'aria-label': placeholder || '' },
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'กรุณากรอกข้อมูล';
                        }
                    }
                });
            }
            // Fallback when Swal is not available
            return new Promise((resolve) => {
                const val = prompt(text || title || '');
                if (val) {
                    resolve({ isConfirmed: true, value: val });
                } else {
                    resolve({ isConfirmed: false });
                }
            });
        }

        // Submit done button
        document.querySelectorAll('.btn-submit-req').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                askText({
                    title: 'ยืนยันการจัดเตรียมเสร็จสิ้น?',
                    text: 'คุณต้องการส่งรายการนี้หรือไม่',
                    confirmText: 'ใช่, ส่งรายการ',
                    cancelText: 'ยกเลิก',
                    placeholder: 'ระบุหมายเหตุ...'
                }).then(function (result) {
                    if (result && result.isConfirmed) {
                        const form = document.getElementById('submit-req-form-' + id);
                        if (form) {
                            const input = form.querySelector('input[name="packing_staff_comment"]');
                            if (input) input.value = result.value || '';
                            form.submit();
                        }
                    }
                });
            });
        });

        // Cancel button
        document.querySelectorAll('.btn-cancel-req').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                askText({
                    title: 'ยืนยันการยกเลิก?',
                    text: 'คุณต้องการยกเลิกรายการนี้ใช่หรือไม่',
                    confirmText: 'ใช่, ยกเลิก',
                    cancelText: 'กลับ',
                    placeholder: 'ระบุเหตุผล...'
                }).then(function (result) {
                    if (result && result.isConfirmed) {
                        const form = document.getElementById('cancel-req-form-' + id);
                        if (form) {
                            const input = form.querySelector('input[name="packing_staff_comment"]');
                            if (input) input.value = result.value || '';
                            form.submit();
                        }
                    }
                });
            });
        });

        // Checkbox change (AJAX with fetch)
        document.querySelectorAll('.check-item-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const cb = this;
                const id = cb.getAttribute('data-id');
                const checked = cb.checked ? 1 : 0;
                const icon = cb.parentElement ? cb.parentElement.querySelector('.check-icon') : null;
                const url = updateBaseUrl + '/' + encodeURIComponent(id);

                cb.disabled = true;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ check_item: checked })
                })
                .then(function (res) {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json().catch(() => ({}));
                })
                .then(function () {
                    if (icon) {
                        icon.style.display = checked ? '' : 'none';
                    }
                })
                .catch(function () {
                    if (window.Swal) {
                        Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถอัปเดตสถานะได้', 'error');
                    } else {
                        alert('ไม่สามารถอัปเดตสถานะได้');
                    }
                    cb.checked = !cb.checked;
                })
                .finally(function () {
                    cb.disabled = false;
                });
            });
        });
    });
</script>
@endpush