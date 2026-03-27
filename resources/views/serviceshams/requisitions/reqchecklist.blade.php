@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-[1400px] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section with Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 animate-zoom-in">
        <!-- Main Title & Context -->
        <div class="lg:col-span-2 flex flex-col justify-center bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                    <i class="fa-solid fa-box-open text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tighter leading-none">รายการรอดำเนินการจัดเตรียม</h1>
                    <p class="text-[13px] text-slate-400 font-bold mt-1.5 uppercase">จัดการและตรวจสอบพัสดุตามใบเบิกที่ได้รับอนุมัติแล้ว</p>
                </div>
            </div>
        </div>

        <!-- Stats 1: Pending Packing -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-blue-100 transition-colors">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                <i class="fa-solid fa-boxes-packing text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">รอแพ็กของ (Pending)</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-slate-800">{{ number_format($requisitions->count()) }}</span>
                    <span class="text-[10px] font-black text-slate-300 uppercase">Packs</span>
                </div>
            </div>
        </div>

        <!-- Stats 2: Priority -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-red-100 transition-colors">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                <i class="fa-solid fa-bolt text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">ลำดับความสำคัญ (Priority)</p>
                <div class="flex flex-col">
                    <span class="text-[14px] font-black text-red-600 uppercase tracking-tight">First-In First-Out</span>
                    <span class="text-[9px] font-bold text-slate-300 uppercase mt-0.5">Automated sorting applied</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Title -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 animate-zoom-in" style="animation-delay: 0.1s">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-red-600 rounded-full"></div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight uppercase">Worklist: ใบเบิกที่ต้องจัดเตรียม</h2>
        </div>
        <div class="flex items-center gap-4">
             <span class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-full text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <i class="fa-solid fa-shield-halved mr-2 opacity-50"></i> HAMS internal system
             </span>
        </div>
    </div>

    <!-- Content Area: Responsive Dual-View -->
    <div class="space-y-8 animate-zoom-in" style="animation-delay: 0.2s">
        
        <!-- 1. Desktop View -->
        <div class="hidden lg:block bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8">
                <table id="checklistTable" class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl">Requisition / Date</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Requester / Department</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Volume / Value</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Current Status</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl w-64">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisitions as $requisition)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-[13px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200 w-fit group-hover:bg-white transition-colors">{{ $requisition->requisitions_code }}</span>
                                    <span class="text-[11px] text-slate-400 flex items-center gap-1.5 ml-1 font-bold uppercase">
                                        <i class="fa-regular fa-calendar-check text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-[15px] font-black text-slate-800 leading-tight">คุณ{{ $requisition->user->fullname ?? "-" }}</span>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[9px] font-black text-slate-400 px-2 py-0.5 bg-slate-50 rounded-lg border border-slate-100 uppercase tracking-wider group-hover:bg-white transition-colors">{{ $requisition->user->department->department_name ?? "-" }}</span>
                                        <span class="text-[9px] font-bold text-slate-300 uppercase">{{ $requisition->user->division->division_name ?? "-" }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[14px] font-black text-slate-700">{{ $requisition->requisition_items->count() }} <span class="text-[10px] text-slate-400 font-bold uppercase">Units</span></span>
                                    <span class="text-[12px] font-black text-red-600 font-mono mt-0.5 tracking-tight border-t border-slate-100 pt-0.5 w-full">฿{{ number_format((float)$requisition->total_price, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @php
                                    $status = $requisition->status ?? null;
                                    $statusOptions = defined(get_class($requisition).'::statusOptions')
                                        ? constant(get_class($requisition).'::statusOptions')
                                        : [];
                                    $opt = $status ? ($statusOptions[$status] ?? null) : null;
                                @endphp
                                @if($opt)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $opt['class'] }} text-[9px] font-black uppercase shadow-sm border border-white/20">
                                        <i class="{{ $opt['icon'] }} text-[8px]"></i>
                                        {{ $opt['label'] }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest opacity-50">Untracked</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}" 
                                   class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 border border-slate-800 text-white text-[11px] font-black rounded-xl shadow-lg shadow-slate-100 transition-all hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest">
                                    <i class="fa-solid fa-clipboard-check text-[10px] opacity-50"></i>
                                    <span>verify & start packing</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Mobile View -->
        <div class="lg:hidden grid grid-cols-1 gap-4">
            @forelse($requisitions as $requisition)
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6 group active:bg-slate-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="space-y-2">
                        <span class="text-[10px] font-mono font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-lg border border-red-100 w-fit uppercase tracking-widest">{{ $requisition->requisitions_code }}</span>
                        <h3 class="text-xl font-black text-slate-800 tracking-tighter leading-none pt-1">คุณ{{ $requisition->user->fullname ?? "-" }}</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">{{ $requisition->user->department->department_name ?? "-" }} / {{ $requisition->user->division->division_name ?? "-" }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-6 rounded-[1.5rem] border border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Request Date</span>
                        <span class="text-[15px] font-black text-slate-700">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex flex-col border-l border-slate-200 pl-4">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Items Value</span>
                        <span class="text-[16px] font-black text-red-600 font-mono">฿{{ number_format((float)$requisition->total_price, 0) }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest mb-0.5 leading-none">Load Factor</span>
                        <span class="text-[14px] font-black text-slate-700 leading-none">{{ $requisition->requisition_items->count() }} <span class="text-[9px] font-normal text-slate-400">Items</span></span>
                    </div>
                    <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}" 
                       class="flex-1 h-14 flex items-center justify-center bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-100 text-[12px] uppercase tracking-widest">
                       verify checklist <i class="fa-solid fa-arrow-right ml-2 text-[10px] opacity-30"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2.5rem] p-20 shadow-sm border border-slate-100 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-box-open text-2xl text-slate-200"></i>
                </div>
                <p class="text-slate-400 font-extrabold tracking-widest uppercase text-xs leading-relaxed">"ไม่มีรายการรอจัดเตรียมในขณะนี้"</p>
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
