@extends('layouts.serviceitem.appservice')
@section('content')
@php
    $isHamsOrAdmin = Auth::check() && ((Auth::user()->department && Auth::user()->department->department_name === 'HAMS') || Auth::user()->employee_code === '11648');
@endphp

@if(!$isHamsOrAdmin)
    <div class="max-w-[90rem] mx-auto px-4 py-20 flex flex-col items-center justify-center">
        <div class="bg-white p-12 rounded-[3rem] shadow-2xl border border-red-50 text-center max-w-lg w-full animate-zoom-in">
            <div class="w-24 h-24 bg-red-50 text-red-600 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-lg shadow-red-100">
                <i class="fa-solid fa-shield-halved text-4xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-800 mb-4 tracking-tight">สิทธิ์การเข้าถึงจำกัด</h2>
            <p class="text-slate-500 font-medium mb-10 leading-relaxed ">"เฉพาะพนักงานแผนก HAMS เท่านั้นที่สามารถจัดสรรและตรวจสอบรายการรอเบิกได้"</p>
            <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-slate-800 hover:bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-100 transition-all active:scale-95">
                <i class="fa-solid fa-house-chimney text-sm"></i>
                <span>กลับสู่หน้าหลัก</span>
            </a>
        </div>
    </div>
@else

<div class="max-w-[90rem] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section with Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Main Title & Context -->
        <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-100">
                    <i class="fa-solid fa-rotate text-white text-2xl fa-spin-pulse"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight ">รายการที่ต้องรอดำเนินการ</h1>
                    <p class="text-sm text-slate-500 font-medium">รอการอนุมัติหรือการจัดเตรียมจากฝ่ายพัสดุ</p>
                </div>
            </div>
        </div>

        <!-- Stats 1: My Pending -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-lg animate-pulse"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">รอกดเลือกอนุมัติ</div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($requisitions->count()) }} <span class="text-xs font-normal text-slate-400 ml-1">ฉบับ</span></div>
            </div>
        </div>

        <!-- Stats 2: Urgency (Placeholder or Simple Logic) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-fire text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">ดำเนินการทันที</div>
                <div class="text-2xl font-black text-red-600 font-mono ">{{ $requisitions->count() > 0 ? 'HIGH' : 'NORMAL' }}</div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Title -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="w-2 h-8 bg-orange-500 rounded-full"></span>
            <h2 class="text-lg font-extrabold text-slate-700">ตรวจสอบรายการที่ส่งเข้ามา</h2>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('items.itemsalllist') }}" class="text-sm font-bold text-orange-600 hover:underline flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> กลับไปเลือกอุปกรณ์
            </a>
        </div>
    </div>

    <!-- Content Area: Responsive Dual-View -->
    <div class="space-y-6">
        
        <!-- 1. Desktop View: Premium Table -->
        <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table id="pendingReqTable" class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl">เลขที่ใบเบิก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">ผู้ขอ / แผนก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">วันที่เบิก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">ยอดรวมพัสดุ</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะปัจจุบัน</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl w-48">ตรวจสอบใบเบิก</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisitions as $requisition)
                        <tr class="hover:bg-orange-50/20 transition-all duration-200 group">
                            <td class="px-6 py-4">
                                <span class="text-[14px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200">{{ $requisition->requisitions_code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[15px] font-black text-slate-800">{{ $requisition->user->fullname ?? "-" }}</span>
                                    <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="fa-solid fa-building text-[10px]"></i>
                                        {{ $requisition->user->department->department_name ?? "-" }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[14px] font-bold text-slate-600 ">
                                    {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') ?? "-" }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-[15px] font-black text-orange-600 font-mono  underline decoration-orange-100 p-1 underline-offset-4">
                                    ฿{{ number_format((float)$requisition->total_price, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    @php
                                        $badgeClass = '';
                                        $label = '';
                                        switch($requisition->status){
                                            case 'pending': $badgeClass = 'bg-orange-50 text-orange-600 border border-orange-100'; $label='รอดำเนินการ'; break;
                                            case 'approved': $badgeClass = 'bg-blue-50 text-blue-600 border border-blue-100'; $label='กำลังดำเนินการ'; break;
                                            case 'rejected': $badgeClass = 'bg-red-50 text-red-600 border border-red-100'; $label='ยกเลิกโดยพัสดุ'; break;
                                            case 'cancelled': $badgeClass = 'bg-slate-50 text-slate-400 border border-slate-100'; $label='ผู้ขอยกเลิก'; break;
                                            default: $badgeClass = 'bg-slate-100 text-slate-600'; $label=$requisition->status;
                                        }
                                    @endphp
                                    <span class="px-3 py-1 rounded-full {{ $badgeClass }} text-[10px] font-black uppercase shadow-sm">
                                        {{ $label }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}" 
                                       class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 hover:border-orange-500 hover:text-orange-600 text-slate-800 rounded-xl transition-all shadow-sm group-hover:shadow-md"
                                       title="ดูรายละเอียด">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <button class="w-10 h-10 flex items-center justify-center bg-white border border-red-100 hover:bg-red-600 hover:text-white text-red-500 rounded-xl transition-all shadow-sm btn-cancel-req group-hover:shadow-md"
                                            data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}"
                                            title="ยกเลิกใบเบิก">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Mobile View: Card List -->
        <div class="lg:hidden grid grid-cols-1 gap-4">
            @forelse($requisitions as $requisition)
            <div class="bg-white rounded-[2.5rem] p-7 shadow-sm border border-slate-100 space-y-6 active:scale-[0.98] transition-transform">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] font-mono font-black text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg border border-orange-100 w-fit">{{ $requisition->requisitions_code }}</span>
                        <h3 class="text-xl font-black text-slate-800 tracking-tighter leading-none pt-1">{{ $requisition->user->fullname ?? "-" }}</h3>
                        <p class="text-[11px] font-bold text-slate-400 ">Department: {{ $requisition->user->department->department_name ?? "-" }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[10px] font-black text-slate-300 uppercase">Wait Approval</span>
                        <i class="fa-solid fa-clock-rotate-left text-orange-300 text-lg animate-pulse"></i>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-5 rounded-[1.5rem] border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Date</span>
                        <span class="text-[14px] font-black text-slate-700 ">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-col border-l border-slate-200 pl-4 text-right">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Value</span>
                        <span class="text-[16px] font-black text-orange-600 ">฿{{ number_format((float)$requisition->total_price, 0) }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('requisitions.detailreqpedding', $requisition->requisitions_id) }}" 
                       class="flex-[3] h-14 flex items-center justify-center bg-slate-800 text-white font-black rounded-2xl shadow-lg shadow-slate-100">
                       <i class="fa-solid fa-magnifying-glass mr-2 text-xs opacity-50"></i> View Detail
                    </a>
                    <button data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}" 
                       class="flex-1 h-14 flex items-center justify-center bg-white border-2 border-red-50 text-red-500 rounded-2xl shadow-sm btn-cancel-req">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2rem] p-16 shadow-sm border border-slate-100 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-layer-group text-2xl text-slate-200"></i>
                </div>
                <p class="text-slate-400 font-extrabold tracking-tighter uppercase ">ไม่มีใบเบิกพัสดุที่รอการดําเนินการ</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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

@endif
@endsection

@push('styles')
<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
</style>
@endpush
