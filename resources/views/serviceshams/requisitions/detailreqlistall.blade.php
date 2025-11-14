@extends('layouts.serviceitem.appservice')
@section('content')
<div class="p-4">
    <div class="card bg-base-100 shadow-xl rounded-lg">
        <div class="text-center bg-gradient-to-r from-orange-600 to-orange-700 rounded-t-lg">
            <div class="breadcrumbs text-sm text-white">
                <ul class="flex items-center justify-center gap-2">
                    <li>
                        <a href="{{ route('requisitions.reqlistall') }}" class="text-white/90 hover:text-white font-medium">กลับไปหน้ารายการทั้งหมด</a>
                    </li>
                    <li class="font-medium">
                        รายละเอียดในการเบิกของ 
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            @php
            $has_unit = $requisition->requisition_items->where('quantity', '>', 0)->count() > 0;
            $has_pack = $requisition->requisition_items->where('quantity_pack', '>', 0)->count() > 0;
            $total_unit = 0;
            $total_pack = 0;
            @endphp
            <div class="overflow-x-auto">
                <table class="table table-sm w-full">
                    <thead class="bg-base-200">
                        <tr class="text-center">
                            <th class="w-12">ลำดับ</th>
                            <th class="w-1/4">รายการอุปกรณ์</th>
                            <th class="w-24">จำนวน(ชิ้น)</th>
                            <th class="w-28">ราคา/(ชิ้น)</th>
                            <th class="w-32">ราคารวมทั้งหมด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($requisition->requisition_items as $requisition_item)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td> {{-- จัดกลาง --}}
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
                            
                        </tr>
                        @endforeach
                        
                        {{-- ย้าย .bg-amber-100 และ .font-bold มาไว้ที่ <tr> --}}
                        <tr class="bg-amber-100 font-bold">
                            <td colspan="4" class="text-right">ราคารวมทั้งหมด</td>
                            <td class="text-right"> {{-- เพิ่ม text-right --}}
                                {{ number_format($requisition->total_price, 2) }} บาท
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- 
    ลบ JavaScript ที่ไม่ถูกใช้งานออก ( .btn-expand และ .check-item-checkbox ) 
    เนื่องจากไม่มีองค์ประกอบ HTML ที่เรียกใช้ class เหล่านี้
--}}
@endpush