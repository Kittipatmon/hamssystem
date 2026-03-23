@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-[90rem] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section with Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Main Title & Context -->
        <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                    <i class="fa-solid fa-box-open text-white text-2xl animate-bounce"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight italic">รายการรอดำเนินการจัดเตรียม</h1>
                    <p class="text-sm text-slate-500 font-medium">จัดการและตรวจสอบพัสดุตามใบเบิกที่ได้รับอนุมัติแล้ว</p>
                </div>
            </div>
        </div>

        <!-- Stats 1: Pending Packing -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-boxes-packing text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">รอแพ็กของ</div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($requisitions->count()) }} <span class="text-xs font-normal text-slate-400 ml-1">ฉบับ</span></div>
            </div>
        </div>

        <!-- Stats 2: Priority (Simple check) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-bolt text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">ความเร่งด่วน</div>
                <div class="text-15px font-black text-red-600 italic">"First-In First-Out"</div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Title -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="w-2 h-8 bg-red-600 rounded-full animate-pulse"></span>
            <h2 class="text-lg font-extrabold text-slate-700 italic">Worklist: ใบเบิกที่ต้องจัดเตรียม</h2>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-[11px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100 italic">HAMS internal system</span>
        </div>
    </div>

    <!-- Content Area: Responsive Dual-View -->
    <div class="space-y-6">
        
        <!-- 1. Desktop View: Premium Table -->
        <div class="hidden lg:block bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table id="checklistTable" class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-3xl">ใบเบิก / วันที่</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">ข้อมูลผู้เบิก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">จำนวน / ราคารวม</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะการทำงาน</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-3xl w-56">ตรวจสอบรายการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisitions as $requisition)
                        <tr class="hover:bg-red-50/20 transition-all duration-200 group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="text-[14px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200 w-fit">{{ $requisition->requisitions_code }}</span>
                                    <span class="text-[12px] text-slate-400 flex items-center gap-1.5 ml-1 italic font-medium">
                                        <i class="fa-regular fa-calendar-check text-[10px]"></i>
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[15px] font-black text-slate-800">{{ $requisition->user->fullname ?? "-" }}</span>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-bold text-slate-400 px-1.5 py-0.5 bg-slate-50 rounded border border-slate-100 uppercase italic">{{ $requisition->user->department->department_name ?? "-" }}</span>
                                        <span class="text-[10px] font-bold text-slate-300 italic">{{ $requisition->user->division->division_name ?? "-" }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[14px] font-bold text-slate-700">{{ $requisition->requisition_items->count() }} รายการ</span>
                                    <span class="text-[13px] font-black text-red-600 font-mono mt-0.5">฿{{ number_format((float)$requisition->total_price, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $status = $requisition->status ?? null;
                                    $statusOptions = defined(get_class($requisition).'::statusOptions')
                                        ? constant(get_class($requisition).'::statusOptions')
                                        : [];
                                    $opt = $status ? ($statusOptions[$status] ?? null) : null;
                                @endphp
                                @if($opt)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $opt['class'] }} text-[10px] font-black uppercase shadow-sm">
                                        <i class="{{ $opt['icon'] }} text-[9px]"></i>
                                        {{ $opt['label'] }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-400 text-[10px] font-black uppercase">None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}" 
                                   class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-black rounded-xl shadow-lg shadow-slate-100 transition-all active:scale-95">
                                    <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                                    <span>ตรวจสอบและจัดของ</span>
                                </a>
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
            <div class="bg-white rounded-[2.5rem] p-7 shadow-sm border border-slate-100 space-y-6 active:scale-[0.98] transition-all">
                <!-- Card Header -->
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <span class="text-[10px] font-mono font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-lg border border-red-100 w-fit italic uppercase tracking-widest">{{ $requisition->requisitions_code }}</span>
                        <h3 class="text-xl font-black text-slate-800 tracking-tighter leading-none pt-2">{{ $requisition->user->fullname ?? "-" }}</h3>
                        <p class="text-[11px] font-bold text-slate-400 italic">{{ $requisition->user->department->department_name ?? "-" }} / {{ $requisition->user->division->division_name ?? "-" }}</p>
                    </div>
                </div>

                <!-- Specs -->
                <div class="grid grid-cols-2 gap-4 bg-red-50/30 p-5 rounded-[1.5rem] border border-red-50/50">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Request Date</span>
                        <span class="text-[14px] font-black text-slate-700 italic">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-col border-l border-red-100 pl-4">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Items Value</span>
                        <span class="text-[16px] font-black text-red-600 italic">฿{{ number_format((float)$requisition->total_price, 0) }}</span>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5">Capacity</span>
                        <span class="text-[12px] font-black text-slate-700 italic">{{ $requisition->requisition_items->count() }} Items</span>
                    </div>
                    <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}" 
                       class="flex-1 h-14 flex items-center justify-center bg-slate-800 text-white font-black rounded-2xl shadow-xl shadow-slate-100 text-[14px] italic">
                       START CHECKING <i class="fa-solid fa-arrow-right ml-2 text-xs opacity-50"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[3rem] p-16 shadow-sm border border-slate-100 text-center animate-pulse">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-box-open text-2xl text-slate-200"></i>
                </div>
                <p class="text-slate-400 font-extrabold tracking-tighter uppercase italic">"ไม่มีรายการรอจัดเตรียมในขณะนี้"</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    #checklistTable_wrapper .dataTables_length select { border-radius: 12px; padding: 4px 12px; border: 1px solid #f1f5f9; background-color: #f8fafc; font-weight: 600; }
    #checklistTable_wrapper .dataTables_filter input { border-radius: 16px; padding: 10px 20px; border: 1px solid #f1f5f9; background-color: #f8fafc; font-weight: 500; font-size: 14px; min-width: 250px; }
    #checklistTable_wrapper .dataTables_paginate .paginate_button.current { background: #dc2626 !important; border-color: #dc2626 !important; color: white !important; border-radius: 12px; font-weight: bold; }
    table.dataTable thead th { border-bottom: none !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(window.jQuery && typeof $.fn.DataTable === 'function') {
            $('#checklistTable').DataTable({
                pageLength: 25,
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' },
                order: [[0, 'desc']]
            });
        }
    });
</script>
@endpush