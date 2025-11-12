@extends('layouts.serviceitem.appservice')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header text-center" style="background: linear-gradient(90deg,rgb(76, 174, 206) 0%,rgb(237, 87, 227) 100%);">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: none; padding: 0;">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}" style="color:rgb(255, 255, 255); font-weight: 500; font-size: 1.10rem; text-decoration: none;">Home</a>
                    </li>
                    <li class="breadcrumb-separator" aria-hidden="true" style="display: flex; align-items: center; color: #fff;">
                        &nbsp;&nbsp;&nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;&nbsp;&nbsp;
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color:rgb(255, 255, 255); font-weight: 500; font-size: 1.10rem;">
                        รอดำเนินการเบิกของ
                    </li>

                </ol>
            </nav>
        </div>
        <div class="card-body" width="100%">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div
                class="table-responsive-xl">
                <table
                    class="table  table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <!-- <th scope="col" style="width: 8%;">เพิ่มเติม</th> -->
                            <!-- <th scope="col">#</th> -->
                            <th scope="col">เลขที่ใบเบิก</th>
                            <th scope="col">ชื่อผู้เบิก</th>
                            <th scope="col">ชื่อแผนก</th>
                            <th scope="col">วันที่เบิก</th>
                            <th scope="col">สถานะ</th>
                            <th scope="col">ราคารวม</th>
                            <th scope="col" style="width: 13%;">ตรวจสอบ</th>
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
                            <td>{{ $requisition->user->name ?? "-" }}</td>
                            <td>{{ $requisition->user->department->code  ?? "-" }}</td>
                            <td>{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') ?? "-" }}</td>
                            <td>
                                @switch($requisition->status)
                                @case('pending')
                                <span class="badge text-white bg-info">รอดำเนินการ</span>
                                @break
                                @case('approved')
                                <span class="badge text-white bg-warning">กำลังดำเนินการ</span>
                                @break
                                @case('rejected')
                                <span class="badge text-white bg-danger">ยกเลิก</span>
                                @if ($requisition->approve_status == '2')
                                {{ $requisition->approve_user->name ." / " .  "ผู้อนุมัติ" }} <br>
                                <p style="color:red;">หมายเหตุ : {{ $requisition->approve_comment}}</p>
                                @endif

                                @break
                                @case('returned')
                                <span class="badge text-white bg-info">ส่งคืน</span>
                                @break
                                @case('cancelled')
                                <span class="badge text-error bg-warning">ยกเลิก</span>
                                <!-- APPROVE_STATUS_REJECTED -->
                                @if ($requisition->approve_status == '0' && $requisition->packing_staff_status == '0')
                                ผู้ขอยกเลิก <br>
                                <span style="color: red;"> หมายเหตุ : {{ $requisition->requester_comment ?? "-"}}</span>
                                @endif

                                @if ($requisition->packing_staff_status == '2')
                                {{ $requisition->packing_staff->name ." / " .  "ผู้จัดของ" }} <br>
                                <p style="color:red;">หมายเหตุ : {{ $requisition->packing_staff_comment}}</p>
                                @endif

                                @break
                                @case('endprogress')
                                <span class="badge text-where bg-success">ดำเนินการเสร็จสิ้น</span>
                                @break
                                @default
                                <span class="badge text-white bg-secondary">ไม่ทราบสถานะ</span>
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
                                <a href="{{ route('requisitions.pdf' , $requisition->requisitions_id) }}" class="btn btn-info btn-sm" target="_blank"><i class="fa-solid fa-file-pdf text-white"></i></a>
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}" class="btn btn-sm btn-warning" title="ดูรายละเอียดเพิ่มเติม">
                                    <i class="fas fa-eye text-white"></i> ตรวจสอบรายการ
                                </a>
                            </td>
                        </tr>

                        @endforeach
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