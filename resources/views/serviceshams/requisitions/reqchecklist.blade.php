@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-full mx-auto rounded-lg">
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
    <div class="card-body overflow-x-auto">
        <table class="table table-sm">
            <thead class="bg-base-200">
                <tr>
                    <th>เลขที่ใบเบิก</th>
                    <th>ชื่อผู้เบิก</th>
                    <th>สายงาน</th>
                    <th>ฝ่าย</th>
                    <th>แผนก</th>
                    <th>วันที่เบิก</th>
                    <th>จำนวนรายการ</th>
                    <th>ราคารวม</th>
                    <th>สถานะ</th>
                    <th class="w-48">ตรวจสอบ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requisitions as $requisition)
                <tr>
                    <td>{{ $requisition->requisitions_code }}</td>
                    <td>{{ $requisition->user->fullname ?? "-" }}</td>
                    <td>{{ $requisition->user->section->section_code  ?? "-" }}</td>
                    <td>{{ $requisition->user->division->division_name  ?? "-" }}</td>
                    <td>{{ $requisition->user->department->department_name  ?? "-" }}</td>
                    <td>{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') ?? "-" }}</td>
                    <td class="text-sm">
                        {{ $requisition->requisition_items->count() ?? 0 }} รายการ
                    </td>
                    <td>
                        {{ number_format($requisition->total_price, 2) }} บาท
                    </td>
                    <td>
                        @php
                            $status = $requisition->status ?? null;
                            $statusOptions = defined(get_class($requisition).'::statusOptions')
                                ? constant(get_class($requisition).'::statusOptions')
                                : [];
                            $opt = $status ? ($statusOptions[$status] ?? null) : null;
                        @endphp

                        @if($opt)
                            <span class="{{ $opt['class'] }} px-2 py-1 rounded-full text-xs">
                                <i class="{{ $opt['icon'] }}"></i> {{ $opt['label'] }}
                            </span>
                        @else
                            <span class="badge bg-secondary">ไม่ระบุสถานะ</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}" class="btn btn-warning btn-sm" title="ดูรายละเอียดเพิ่มเติม">
                            <i class="fas fa-eye text-white"></i> ตรวจสอบรายการ
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($requisitions->isEmpty())
                <tr>
                    <td colspan="9" class="text-center">ไม่มีข้อมูลในขณะนี้</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>    
    </div>    
</div>

@endsection