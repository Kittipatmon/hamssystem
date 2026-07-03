@extends('layouts.serviceitem.appservice')
@section('content')
    <div class="max-w-[90rem] mx-auto px-4 py-4 lg:py-18 space-y-8 uppercase tracking-tight">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="lg:col-span-2 flex flex-col justify-center bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-rotate text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">รายการที่ต้องรอดำเนินการ</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">รอการอนุมัติหรือการจัดเตรียมจากฝ่ายพัสดุ</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: My Pending -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded border border-orange-200 flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-start"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">รายการที่รออนุมัติ</div>
                    <div class="text-lg font-black text-slate-800 mt-0.5">
                        {{ number_format($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING)->count()) }} <span class="text-xs font-normal text-slate-400">ใบ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Processed Items -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-200 flex items-center justify-center">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">คำขอที่อนุมัติแล้ว</div>
                    <div class="text-lg font-black text-blue-600 mt-0.5">
                        {{ number_format($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS])->count()) }} <span class="text-xs font-normal text-slate-400">ใบ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: รายการที่ทาง HAMS กำลังรอตรวจสอบ (Pending) -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-6 bg-orange-500 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-700">รายการที่รอพัสดุตรวจสอบ</h2>
            </div>

            <!-- Desktop View (Pending) -->
            <div class="hidden lg:block bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-40">เลขที่ใบเบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">วันที่เบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">ยอดรวมพัสดุ</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-44">สถานะปัจจุบัน</th>
                                <th class="py-3 px-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING) as $requisition)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded border border-slate-200">{{ $requisition->requisitions_code }}</span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-semibold text-slate-600">
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-bold text-slate-700">
                                        ฿{{ number_format((float) $requisition->total_price, 2) }}
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                                            <span class="px-2.5 py-0.5 rounded-full bg-orange-50 text-orange-700 border border-orange-200 text-[10px] font-bold uppercase shadow-sm">
                                                รอดำเนินการ
                                            </span>
                                        @elseif($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_APPROVED)
                                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase shadow-sm">
                                                รอดำเนินการจัดอุปกรณ์
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 border border-slate-200 text-[10px] font-bold uppercase shadow-sm">
                                                {{ $requisition->status_label }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($requisition->approve_id === Auth::id() && $requisition->approve_status == 0)
                                                <div class="flex items-center bg-slate-100 border border-slate-200 rounded p-0.5 shadow-sm">
                                                    <button type="button" 
                                                        class="w-6 h-6 flex items-center justify-center text-emerald-600 bg-white hover:bg-emerald-600 hover:text-white rounded transition-colors btn-quick-approve"
                                                        data-id="{{ $requisition->requisitions_id }}" data-status="1" title="อนุมัติ">
                                                        <i class="fa-solid fa-check text-[10px]"></i>
                                                    </button>
                                                    <div class="w-px h-3 bg-slate-250 mx-0.5"></div>
                                                    <button type="button" 
                                                        class="w-6 h-6 flex items-center justify-center text-rose-600 bg-white hover:bg-rose-600 hover:text-white rounded transition-colors btn-quick-approve"
                                                        data-id="{{ $requisition->requisitions_id }}" data-status="2" title="ปฏิเสธ">
                                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            
                                            <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 hover:border-slate-400 text-slate-700 rounded transition-colors shadow-sm"
                                                title="ดูรายละเอียด">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 hover:border-red-400 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                                                title="ดาวน์โหลด PDF">
                                                <i class="fa-solid fa-file-pdf text-xs"></i>
                                            </a>
                                            
                                            @if($requisition->requester_id === Auth::id())
                                            <button
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 hover:bg-red-600 hover:border-red-600 hover:text-white text-red-600 rounded transition-colors shadow-sm btn-cancel-req"
                                                data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                                title="ยกเลิกใบเบิก">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-6 text-center text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                        ไม่มีรายการที่รอการตรวจสอบ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View (Pending) -->
            <div class="lg:hidden grid grid-cols-1 gap-3">
                @foreach($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING) as $requisition)
                    <div class="bg-white rounded border border-slate-200 p-4 shadow-sm space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span
                                    class="text-[10px] font-mono font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100 w-fit">{{ $requisition->requisitions_code }}</span>
                                <h3 class="text-xs font-bold text-slate-800 tracking-tight leading-none pt-1">
                                    @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                                        รอดำเนินการ
                                    @elseif($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_APPROVED)
                                        รอดำเนินการจัดอุปกรณ์
                                    @else
                                        {{ $requisition->status_label }}
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded border border-slate-100 text-xs font-bold">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">วันที่เบิก</span>
                                <span
                                    class="text-slate-700 ">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200 pl-3 text-right">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">ยอดรวม</span>
                                <span
                                    class="text-orange-600 ">฿{{ number_format((float) $requisition->total_price, 0) }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if($requisition->approve_id === Auth::id() && $requisition->approve_status == 0)
                                <button type="button" 
                                    class="flex-1 h-9 flex items-center justify-center bg-emerald-600 text-white font-bold rounded shadow btn-quick-approve text-xs"
                                    data-id="{{ $requisition->requisitions_id }}" data-status="1">
                                    <i class="fa-solid fa-check mr-1.5"></i> อนุมัติ
                                </button>
                                <button type="button" 
                                    class="flex-1 h-9 flex items-center justify-center bg-rose-600 text-white font-bold rounded shadow btn-quick-approve text-xs"
                                    data-id="{{ $requisition->requisitions_id }}" data-status="2">
                                    <i class="fa-solid fa-xmark mr-1.5"></i> ปฏิเสธ
                                </button>
                            @else
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                    class="flex-[3] h-9 flex items-center justify-center bg-slate-800 text-white font-bold rounded text-xs">รายละเอียด</a>
                            @endif
                            
                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-500 rounded shadow-sm"><i
                                    class="fa-solid fa-file-pdf"></i></a>
                            
                            @if($requisition->requester_id === Auth::id())
                            <button data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                class="w-9 h-9 flex items-center justify-center bg-white border border-red-200 text-red-500 rounded shadow-sm btn-cancel-req"><i
                                    class="fa-solid fa-trash-can"></i></button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: รายการที่ส่งเข้ามาแล้ว (Approved / In Progress) -->
        <div class="space-y-4 pt-4">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-700">ตรวจสอบรายการที่ส่งเข้ามาแล้ว</h2>
            </div>

            <!-- Desktop View (Approved) -->
            <div class="hidden lg:block bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-40">เลขที่ใบเบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">วันที่เบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">ยอดรวมพัสดุ</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-44">ความคืบหน้า</th>
                                <th class="py-3 px-3 text-center">รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS]) as $requisition)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded border border-slate-200">{{ $requisition->requisitions_code }}</span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-semibold text-slate-600">
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center font-bold text-blue-600">
                                        ฿{{ number_format((float) $requisition->total_price, 2) }}
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS)
                                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-600 text-white text-[9px] font-bold uppercase shadow-sm">
                                                    <i class="fa-solid fa-circle-check mr-1"></i> จัดเตรียมเรียบร้อยแล้ว
                                                </span>
                                                <span class="text-[9px] font-semibold text-emerald-500 uppercase tracking-tight">กรุณาติดต่อรับพัสดุ</span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full bg-blue-600 text-white text-[9px] font-bold uppercase shadow-sm">
                                                    <i class="fa-solid fa-boxes-packing mr-1"></i> ได้รับการอนุมัติแล้ว
                                                </span>
                                                <span class="text-[9px] font-semibold text-blue-500 uppercase tracking-tight">พัสดุกำลังเตรียมอุปกรณ์</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-[11px] font-bold rounded shadow transition-colors flex items-center gap-1.5">
                                                <i class="fa-solid fa-magnifying-glass text-[9px]"></i> ตรวจสอบ
                                            </a>
                                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 hover:border-red-400 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                                                title="ดาวน์โหลด PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="py-6 text-center text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                        ไม่พบรายการที่อนุมัติแล้วในส่วนนี้</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View (Approved) -->
            <div class="lg:hidden grid grid-cols-1 gap-3">
                @foreach($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS]) as $requisition)
                    <div class="bg-white rounded border border-slate-200 p-4 shadow-sm border-l-4 border-l-blue-500 space-y-3">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-[10px] font-mono font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $requisition->requisitions_code }}</span>
                            @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS)
                                <span class="text-[10px] font-bold text-emerald-600 uppercase">จัดเตรียมเสร็จสิ้น</span>
                            @else
                                <span class="text-[10px] font-bold text-blue-600 uppercase">ได้รับการอนุมัติ</span>
                            @endif
                        </div>
                        <div class="flex items-end justify-between text-xs">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-0.5">ยอดรวมพัสดุ</p>
                                <p class="text-sm font-bold text-slate-800">
                                    ฿{{ number_format((float) $requisition->total_price, 0) }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                    class="h-9 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded font-bold flex items-center justify-center text-xs shadow-sm">
                                    ตรวจสอบ
                                </a>
                                <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                    class="h-9 w-9 bg-white border border-slate-200 text-blue-600 rounded flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quick Approve Handler
            document.querySelectorAll('.btn-quick-approve').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    const id = this.dataset.id;
                    const status = this.dataset.status;
                    const statusName = status == 1 ? 'อนุมัติ' : 'ปฏิเสธ';
                    const confirmButtonColor = status == 1 ? '#10b981' : '#f43f5e';
                    
                    Swal.fire({
                        title: `ยืนยันการ${statusName}?`,
                        text: `ต้องการ${statusName}ใบเบิกพัสดุนี้ใช่หรือไม่?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'ยืนยัน',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonColor: confirmButtonColor,
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ route('requisitions.quick_approve') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ id: id, status: status })
                            })
                            .then(response => response.json())
                            .then(res => {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'ดำเนินการสำเร็จ',
                                        text: `ได้ทำการ${statusName}รายการเรียบร้อยแล้ว`,
                                        customClass: { popup: 'rounded-[2rem]' },
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                });
            });

            document.querySelectorAll('.btn-cancel-req').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const url = this.dataset.href;
                    Swal.fire({
                        title: '<span class="text-slate-800 font-black tracking-tight">ยืนยันการยกเลิกใบเข้าเบิก?</span>',
                        html: '<p class="text-sm text-slate-500 font-medium leading-relaxed ">"หากคุณยกเลิก ใบเบิกฉบับนี้จะถูกส่งคืนและไม่สามารถดำเนินการต่อได้"</p>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ใช่, ฉันต้องการยกเลิก',
                        cancelButtonText: 'ไม่, ปิดตัวช่วยนี้',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#1e293b',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-6 py-3 font-bold',
                            cancelButton: 'rounded-xl px-6 py-3 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>

@endsection

@push('styles')
    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.4s ease-out forwards;
        }
    </style>
@endpush