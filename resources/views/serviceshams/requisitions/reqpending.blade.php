@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-full mx-auto p-2 md:p-4 rounded-lg">
    <div class="card bg-base-100 shadow">
        <div class="text-center px-5 bg-gradient-to-r from-orange-600 to-orange-700 rounded-lg">
            <div class="breadcrumbs text-sm text-white">
                <ul class="flex">
                    <li>
                        <a href="{{ route('items.itemsalllist') }}" class="text-white/90 hover:text-white font-medium">
                            รายการอุปกรณ์
                        </a>
                    </li>
                    <li class="font-medium">
                        <i class="fa-solid fa-rotate fa-spin mr-2"></i>
                        รอดำเนินการเบิกของ</li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead class="bg-base-200">
                        <tr>
                            <th>เลขที่ใบเบิก</th>
                            <th>ชื่อผู้เบิก</th>
                            <th>ชื่อแผนก</th>
                            <th>วันที่เบิก</th>
                            <th>ราคารวม</th>
                            <th>สถานะ</th>
                            <th class="w-48">ตรวจสอบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requisitions as $requisition)
                        <tr>
                            <!-- <td>
                                <button type="button" class="btn btn-sm btn-primary btn-expand" data-id="{{ $requisition->requisitions_id }}">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td> -->
                            <!-- <td>{{ $requisition->requisitions_id }}</td> -->
                            <td>{{ $requisition->requisitions_code }}</td>
                            <td>{{ $requisition->user->fullname ?? "-" }}</td>
                            <td>{{ $requisition->user->department->department_name  ?? "-" }}</td>
                            <td>{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') ?? "-" }}</td>
                            <td>
                                @switch($requisition->status)
                                    @case('pending')
                                        <span class="badge badge-warning">รอดำเนินการ</span>
                                        @break
                                    @case('approved')
                                        <span class="badge badge-info">กำลังดำเนินการ</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge badge-error">ยกเลิก</span>
                                        @if ($requisition->approve_status == '2')
                                            <div class="text-xs text-base-content/80 mt-1">{{ $requisition->approve_user->name ." / " .  "ผู้อนุมัติ" }}</div>
                                            <p class="text-xs text-error">หมายเหตุ : {{ $requisition->approve_comment}}</p>
                                        @endif
                                        @break
                                    @case('returned')
                                        <span class="badge badge-secondary">ส่งคืน</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge badge-neutral">ยกเลิก</span>
                                        @if ($requisition->approve_status == '0' && $requisition->packing_staff_status == '0')
                                            <div class="text-xs">ผู้ขอยกเลิก</div>
                                            <span class="text-xs text-error">หมายเหตุ : {{ $requisition->requester_comment ?? "-"}}</span>
                                        @endif
                                        @if ($requisition->packing_staff_status == '2')
                                            <div class="text-xs text-base-content/80">{{ $requisition->packing_staff->name ." / " .  "ผู้จัดของ" }}</div>
                                            <p class="text-xs text-error">หมายเหตุ : {{ $requisition->packing_staff_comment}}</p>
                                        @endif
                                        @break
                                    @case('endprogress')
                                        <span class="badge badge-success">ดำเนินการเสร็จสิ้น</span>
                                        @break
                                    @default
                                        <span class="badge">ไม่ทราบสถานะ</span>
                                @endswitch
                            </td>
                            <td>
                                @if($requisition->total_price > 0)
                                ฿ {{ number_format($requisition->total_price, 2) }}
                                @else
                                -
                                @endif
                            </td>
                            <td>
                                
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}" class="btn btn-warning btn-sm" title="ดูรายละเอียดเพิ่มเติม">
                                    <i class="fas fa-eye text-white"></i> 
                                </a>
                                <button href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}" 
                                   class="btn btn-error btn-sm btn-cancel-req" 
                                   data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                   title="ยกเลิกใบเบิกของ">
                                    <i class="fas fa-times text-white"></i>
                                </button>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        document.querySelectorAll('.btn-cancel-req').forEach(function (btn) {
                                            btn.addEventListener('click', function (e) {
                                                e.preventDefault();
                                                const url = this.dataset.href;
                                                Swal.fire({
                                                    title: 'ยืนยันการยกเลิก?',
                                                    text: 'คุณแน่ใจหรือไม่ว่าต้องการยกเลิกใบเบิกนี้',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'ใช่',
                                                    cancelButtonText: 'ไม่',
                                                    reverseButtons: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = url;
                                                    }
                                                });
                                            });
                                        });
                                    });
                                </script>
                            </td>
                        </tr>

                        @endforeach
                        @if($requisitions->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center">ไม่มีข้อมูลใบเบิกที่รอดำเนินการ</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
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
    });
</script>
@endpush