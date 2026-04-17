@extends('layouts.serviceitem.appservice')
@section('content')
    <div class="max-w-[90rem] mx-auto px-4 py-4 lg:py-18 space-y-8 uppercase tracking-tight">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
                <div class="flex items-center gap-4">
                    <div
                        class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-100">
                        <i class="fa-solid fa-rotate text-white text-2xl fa-spin-pulse"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight ">รายการที่ต้องรอดำเนินการ</h1>
                        <p class="text-sm text-slate-500 font-medium tracking-tight">
                            รอการอนุมัติหรือการจัดเตรียมจากฝ่ายพัสดุ</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: My Pending -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center border border-orange-100">
                    <i class="fa-solid fa-hourglass-start text-lg animate-pulse"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">รายการที่รออนุมัติ</div>
                    <div class="text-2xl font-black text-slate-800 tracking-tight">
                        {{ number_format($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING)->count()) }}
                        <span class="text-[11px] font-bold text-slate-300 ml-0.5 uppercase tracking-tighter">Items</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Processed Items -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-blue-100 flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center border border-blue-100">
                    <i class="fa-solid fa-truck-ramp-box text-lg"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                        คำขอที่อนุมัติแล้ว</div>
                    <div class="text-2xl font-black text-blue-600 tracking-tight">
                        {{ number_format($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS])->count()) }}
                        <span class="text-[11px] font-bold text-blue-300 ml-0.5 uppercase tracking-tighter">Done</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: รายการที่ทาง HAMS กำลังรอตรวจสอบ (Pending) -->
        <div class="space-y-6">
            <div class="flex items-center gap-2">
                <span class="w-2 h-8 bg-orange-500 rounded-full"></span>
                <h2 class="text-lg font-extrabold text-slate-700">รายการที่รอพัสดุตรวจสอบ</h2>
            </div>

            <!-- Desktop View (Pending) -->
            <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl">
                                    เลขที่ใบเบิก</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    วันที่เบิก</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    ยอดรวมพัสดุ</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    สถานะปัจจุบัน</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl w-48">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING) as $requisition)
                                <tr class="hover:bg-orange-50/20 transition-all duration-200 group">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-[14px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">{{ $requisition->requisitions_code }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-[14px] font-bold text-slate-600">
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center text-[15px] font-black text-orange-600 font-mono underline decoration-orange-100 p-1 underline-offset-4">
                                        ฿{{ number_format((float) $requisition->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                                            <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 border border-orange-100 text-[10px] font-black uppercase shadow-sm">
                                                รอดำเนินการ
                                            </span>
                                        @elseif($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_APPROVED)
                                            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase shadow-sm">
                                                รอดำเนินการจัดอุปกรณ์
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-600 border border-slate-100 text-[10px] font-black uppercase shadow-sm">
                                                {{ $requisition->status_label }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($requisition->approve_id === Auth::id() && $requisition->approve_status == 0)
                                                <div class="flex items-center bg-white border border-slate-200 rounded-xl p-0.5 shadow-sm">
                                                    <button type="button" 
                                                        class="w-10 h-10 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 rounded-lg transition-all btn-quick-approve"
                                                        data-id="{{ $requisition->requisitions_id }}" data-status="1" title="อนุมัติ">
                                                        <i class="fa-solid fa-check text-sm"></i>
                                                    </button>
                                                    <div class="w-px h-5 bg-slate-200 mx-1"></div>
                                                    <button type="button" 
                                                        class="w-10 h-10 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-lg transition-all btn-quick-approve"
                                                        data-id="{{ $requisition->requisitions_id }}" data-status="2" title="ปฏิเสธ">
                                                        <i class="fa-solid fa-xmark text-sm"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            
                                            <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 hover:border-slate-400 text-slate-800 rounded-xl transition-all shadow-sm group-hover:shadow-md"
                                                title="ดูรายละเอียด">
                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-white border border-red-100 text-red-500 rounded-xl hover:bg-red-50 transition-all shadow-sm group-hover:shadow-md"
                                                title="ดาวน์โหลด PDF">
                                                <i class="fa-solid fa-file-pdf text-sm"></i>
                                            </a>
                                            
                                            @if($requisition->requester_id === Auth::id())
                                            <button
                                                class="w-10 h-10 flex items-center justify-center bg-white border border-red-100 hover:bg-red-600 hover:text-white text-red-500 rounded-xl transition-all shadow-sm btn-cancel-req group-hover:shadow-md"
                                                data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                                title="ยกเลิกใบเบิก">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-slate-400 font-black uppercase tracking-widest text-xs">
                                        ไม่มีรายการที่รอการตรวจสอบ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View (Pending) -->
            <div class="lg:hidden grid grid-cols-1 gap-4">
                @foreach($requisitions->where('status', \App\Models\serviceshams\Requisitions::STATUS_PENDING) as $requisition)
                    <div class="bg-white rounded-[2.5rem] p-7 shadow-sm border border-slate-100 space-y-6">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span
                                    class="text-[10px] font-mono font-black text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100 w-fit">{{ $requisition->requisitions_code }}</span>
                                <h3 class="text-xl font-black text-slate-800 tracking-tighter leading-none pt-1">
                                    @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                                        Pending...
                                    @elseif($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_APPROVED)
                                        To be Packed
                                    @else
                                        {{ $requisition->status_label }}
                                    @endif
                                </h3>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-5 rounded-[1.5rem] border border-slate-100">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</span>
                                <span
                                    class="text-[14px] font-black text-slate-700 ">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200 pl-4 text-right">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Value</span>
                                <span
                                    class="text-[16px] font-black text-orange-600 ">฿{{ number_format((float) $requisition->total_price, 0) }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            @if($requisition->approve_id === Auth::id() && $requisition->approve_status == 0)
                                <button type="button" 
                                    class="flex-1 h-14 flex items-center justify-center bg-emerald-600 text-white font-black rounded-2xl shadow-lg btn-quick-approve"
                                    data-id="{{ $requisition->requisitions_id }}" data-status="1">
                                    <i class="fa-solid fa-check mr-2"></i> Approve
                                </button>
                                <button type="button" 
                                    class="flex-1 h-14 flex items-center justify-center bg-rose-600 text-white font-black rounded-2xl shadow-lg btn-quick-approve"
                                    data-id="{{ $requisition->requisitions_id }}" data-status="2">
                                    <i class="fa-solid fa-xmark mr-2"></i> Reject
                                </button>
                            @else
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                    class="flex-[3] h-14 flex items-center justify-center bg-slate-800 text-white font-black rounded-2xl shadow-lg shadow-slate-100">View
                                    Detail</a>
                            @endif
                            
                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                class="w-14 h-14 flex items-center justify-center bg-white border-2 border-slate-100 text-slate-500 rounded-2xl shadow-sm"><i
                                    class="fa-solid fa-file-pdf"></i></a>
                            
                            @if($requisition->requester_id === Auth::id())
                            <button data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                class="w-14 h-14 flex items-center justify-center bg-white border-2 border-red-50 text-red-500 rounded-2xl shadow-sm btn-cancel-req"><i
                                    class="fa-solid fa-trash-can"></i></button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: รายการที่ส่งเข้ามาแล้ว (Approved / In Progress) -->
        <div class="space-y-6 pt-10">
            <div class="flex items-center gap-2">
                <span class="w-2 h-8 bg-blue-500 rounded-full"></span>
                <h2 class="text-lg font-extrabold text-slate-700">ตรวจสอบรายการที่ส่งเข้ามาแล้ว</h2>
            </div>

            <!-- Desktop View (Approved) -->
            <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead>
                            <tr class="bg-blue-50/30">
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl">
                                    เลขที่ใบเบิก</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    วันที่เบิก</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    ยอดรวมพัสดุ</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    ความคืบหน้า</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl w-48">
                                    รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS]) as $requisition)
                                <tr class="hover:bg-blue-50/10 transition-all duration-200 group">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-[14px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">{{ $requisition->requisitions_code }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-[14px] font-bold text-slate-600 italic">
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-[15px] font-black text-blue-600 font-mono">
                                        ฿{{ number_format((float) $requisition->total_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS)
                                                <span class="px-4 py-1.5 rounded-full bg-emerald-600 text-white text-[10px] font-black uppercase shadow-md shadow-emerald-100">
                                                    <i class="fa-solid fa-circle-check mr-1"></i> จัดเตรียมเรียบร้อยแล้ว
                                                </span>
                                                <span class="text-[9px] font-bold text-emerald-400 uppercase tracking-tighter">กรุณาติดต่อรับพัสดุ</span>
                                            @else
                                                <span class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-[10px] font-black uppercase shadow-md shadow-blue-100">
                                                    <i class="fa-solid fa-boxes-packing mr-1"></i> ได้รับการอนุมัติแล้ว
                                                </span>
                                                <span class="text-[9px] font-bold text-blue-400 uppercase tracking-tighter">พัสดุกำลังเตรียมอุปกรณ์</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                                class="flex-1 flex items-center justify-center gap-2 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-black rounded-xl transition-all shadow-lg shadow-slate-100">
                                                <i class="fa-solid fa-magnifying-glass text-[10px]"></i> ตรวจสอบ
                                            </a>
                                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                                class="w-10 h-10 flex items-center justify-center bg-white border border-red-100 text-red-600 rounded-xl hover:bg-red-50 transition-all shadow-sm"
                                                title="ดาวน์โหลด PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-slate-300 font-black uppercase tracking-widest text-[11px]">
                                        ไม่พบรายการที่อนุมัติแล้วในส่วนนี้</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View (Approved) -->
            <div class="lg:hidden grid grid-cols-1 gap-4">
                @foreach($requisitions->whereIn('status', [\App\Models\serviceshams\Requisitions::STATUS_APPROVED, \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS]) as $requisition)
                    <div
                        class="bg-white rounded-[2.5rem] p-7 shadow-sm border border-slate-100 border-l-4 border-l-blue-500 overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="text-[10px] font-mono font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">{{ $requisition->requisitions_code }}</span>
                            @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS)
                                <span class="text-[10px] font-black text-emerald-500 uppercase">Packing Done</span>
                            @else
                                <span class="text-[10px] font-black text-blue-500 uppercase">Approved</span>
                            @endif
                        </div>
                        <div class="flex items-end justify-between">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Total Amount</p>
                                <p class="text-2xl font-black text-slate-800">
                                    ฿{{ number_format((float) $requisition->total_price, 0) }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}"
                                    class="h-12 flex-1 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                                    <i class="fa-solid fa-arrow-right mr-2"></i> View
                                </a>
                                <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                    class="h-12 w-12 bg-white border-2 border-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm">
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