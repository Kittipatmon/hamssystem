@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="lg:col-span-2 flex flex-col justify-center bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-box-open text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">รายการรอดำเนินการจัดเตรียม</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">จัดการและตรวจสอบพัสดุตามใบเบิกที่ได้รับอนุมัติแล้ว</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Pending Packing -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-200 flex items-center justify-center">
                    <i class="fa-solid fa-boxes-packing"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">รอจัดของ (Pending)</div>
                    <div class="text-lg font-black text-slate-800 mt-0.5">
                        {{ number_format($requisitions->count()) }} <span class="text-xs font-normal text-slate-400">ฉบับ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Priority -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded border border-red-200 flex items-center justify-center">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">นโยบายจัดเตรียม (Priority)</div>
                    <div class="text-sm font-black text-red-600 mt-0.5 uppercase">
                        FIFO <span class="text-[9px] font-normal text-slate-400 block tracking-tight">First-In First-Out</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar: Title -->
        <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                <h2 class="font-bold text-slate-700">รายการใบเบิกที่ต้องจัดเตรียม</h2>
            </div>
            <div>
                <span class="px-3 py-1 bg-slate-50 border border-slate-200 rounded text-[10px] font-bold text-slate-400 uppercase">
                    <i class="fa-solid fa-shield-halved mr-1 opacity-65"></i> HAMS Internal System
                </span>
            </div>
        </div>

        <!-- Content Area: Responsive Dual-View -->
        <div class="space-y-6">

            <!-- 1. Desktop View -->
            <div class="hidden lg:block bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 overflow-x-auto">
                    <table id="checklistTable" class="w-full text-left border-collapse border border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-48">เลขที่ใบเบิก / วันที่เบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200">ผู้ขอเบิก / แผนก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">จำนวน / มูลค่าประเมิน</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-40">สถานะ</th>
                                <th class="py-3 px-3 text-center">การตรวจสอบ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($requisitions as $requisition)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3 border-r border-slate-200 text-center leading-normal">
                                        <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 block w-fit mx-auto">{{ $requisition->requisitions_code }}</span>
                                        <span class="text-[10px] text-slate-400 mt-1.5 flex items-center justify-center gap-1 font-bold uppercase">
                                            <i class="fa-regular fa-calendar-check"></i>
                                            {{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 leading-normal">
                                        <div class="font-bold text-slate-800">คุณ{{ $requisition->user->fullname ?? "-" }}</div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-bold text-slate-400 px-1.5 py-0.5 bg-slate-50 rounded border border-slate-150 uppercase">{{ $requisition->user->department->department_name ?? "-" }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 opacity-60">{{ $requisition->user->division->division_name ?? "-" }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center leading-normal font-bold">
                                        <span class="text-slate-700">{{ $requisition->requisition_items->count() }} <span class="text-[9px] text-slate-400 font-bold uppercase">รายการ</span></span>
                                        <span class="text-[11px] text-red-600 block mt-1">฿{{ number_format((float) $requisition->total_price, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        @php
                                            $status = $requisition->status ?? null;
                                            $statusOptions = defined(get_class($requisition) . '::statusOptions')
                                                ? constant(get_class($requisition) . '::statusOptions')
                                                : [];
                                            $opt = $status ? ($statusOptions[$status] ?? null) : null;
                                        @endphp
                                        @if($opt)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $opt['class'] }} text-[10px] font-bold uppercase border border-white/20 whitespace-nowrap shadow-sm">
                                                <i class="{{ $opt['icon'] }} text-[9px]"></i>
                                                {{ $opt['label'] }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-400 text-[10px] font-bold uppercase opacity-55">ไม่ทราบสถานะ</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}"
                                                class="px-3 py-1.5 bg-slate-900 border border-slate-800 text-white text-[11px] font-bold rounded shadow transition-all flex items-center gap-1.5">
                                                <i class="fa-solid fa-clipboard-check text-[10px] opacity-75"></i>
                                                <span>ตรวจสอบและจัดเตรียม</span>
                                            </a>
                                            <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                                                title="ดาวน์โหลด PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Mobile View -->
            <div class="lg:hidden grid grid-cols-1 gap-3">
                @forelse($requisitions as $requisition)
                    <div class="bg-white rounded border border-slate-200 p-4 shadow-sm space-y-3">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1.5">
                                <span class="text-[10px] font-mono font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 w-fit uppercase tracking-wider block">{{ $requisition->requisitions_code }}</span>
                                <h3 class="text-xs font-bold text-slate-800 leading-none pt-1">คุณ{{ $requisition->user->fullname ?? "-" }}</h3>
                                <p class="text-[9px] font-bold text-slate-400 uppercase">
                                    {{ $requisition->user->department->department_name ?? "-" }} / {{ $requisition->user->division->division_name ?? "-" }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded border border-slate-100 text-xs font-bold">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider mb-0.5">วันที่เบิก</span>
                                <span class="text-slate-700">{{ \Carbon\Carbon::parse($requisition->request_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200 pl-3">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider mb-0.5">รวมมูลค่า</span>
                                <span class="text-red-600 font-mono">฿{{ number_format((float) $requisition->total_price, 0) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 text-xs font-bold">
                            <div class="flex flex-col px-1">
                                <span class="text-[8px] text-slate-400 uppercase tracking-wider mb-0.5">จำนวน</span>
                                <span class="text-slate-700 leading-none">{{ $requisition->requisition_items->count() }} <span class="text-[9px] font-normal text-slate-400">รายการ</span></span>
                            </div>
                            <div class="flex gap-1.5">
                                <a href="{{ route('requisitions.detailchecklist', $requisition->requisitions_id) }}"
                                    class="px-3 h-9 flex items-center justify-center bg-slate-900 text-white rounded text-xs">
                                    ตรวจสอบ
                                </a>
                                <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                                    class="w-9 h-9 bg-red-50 text-red-600 rounded flex items-center justify-center border border-red-100 shadow-sm">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded border border-slate-200 p-12 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-box-open text-xl text-slate-350"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">ไม่มีรายการรอจัดเตรียมในขณะนี้</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        #checklistTable_wrapper .dataTables_length select {
            border-radius: 12px;
            padding: 4px 12px;
            border: 1px solid #f1f5f9;
            background-color: #f8fafc;
            font-weight: 600;
        }

        #checklistTable_wrapper .dataTables_filter input {
            border-radius: 16px;
            padding: 10px 20px;
            border: 1px solid #f1f5f9;
            background-color: #f8fafc;
            font-weight: 500;
            font-size: 14px;
            min-width: 250px;
        }

        #checklistTable_wrapper .dataTables_paginate .paginate_button.current {
            background: #dc2626 !important;
            border-color: #dc2626 !important;
            color: white !important;
            border-radius: 12px;
            font-weight: bold;
        }

        table.dataTable thead th {
            border-bottom: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && typeof $.fn.DataTable === 'function') {
                $('#checklistTable').DataTable({
                    pageLength: 25,
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' },
                    order: [[0, 'desc']]
                });
            }
        });
    </script>
@endpush