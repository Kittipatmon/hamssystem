@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[1400px] mx-auto px-4 py-8 space-y-8">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 animate-zoom-in">
            <!-- Main Title & Context -->
            <div
                class="lg:col-span-2 flex flex-col justify-center bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                        <i class="fa-solid fa-list-check text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none">ประวัติการเบิกอุปกรณ์</h1>
                        <p class="text-sm text-slate-400 font-semibold mt-1.5">
                            ติดตามสถานะและตรวจสอบรายการใบเบิกทั้งหมดของคุณ</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Total Requisitions -->
            <div
                class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-blue-100 transition-colors">
                <div
                    class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">คำขอทั้งหมด</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-slate-800">{{ number_format($requisitions->count()) }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">ใบ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Completed Requisitions -->
            @php $completedCount = $requisitions->where('status', 'endprogress')->count(); @endphp
            <div
                class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-emerald-100 transition-colors">
                <div
                    class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">เสร็จสิ้นแล้ว</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-emerald-600">{{ number_format($completedCount) }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">รายการ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar: Title & Search -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 animate-zoom-in"
            style="animation-delay: 0.1s">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-red-600 rounded-full"></div>
                <h2 class="text-xl font-bold text-slate-800">รายการใบเบิกพัสดุรายบุคคล</h2>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                <!-- New Search Input -->
                <div class="relative w-full sm:w-80 group">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                    <input type="text" id="globalSearch" placeholder="ค้นหาตามรายชื่อ หรือรหัสใบเบิก..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all shadow-sm">
                </div>

                <a href="{{ route('items.itemsalllist') }}"
                    class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-red-600 font-bold rounded-2xl border border-red-50 transition-all active:scale-95 text-sm shadow-sm flex items-center justify-center">
                    <i class="fa-solid fa-plus-circle mr-2"></i> ไปหน้าเลือกอุปกรณ์
                </a>
            </div>
        </div>

        <!-- Content Area: Responsive Dual-View -->
        <div class="space-y-8 animate-zoom-in" style="animation-delay: 0.2s">

            <!-- 1. Desktop View -->
            <div class="hidden lg:block bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8">
                    <table id="reqTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase rounded-l-2xl text-center">
                                    ลำดับ</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase">รหัสใบเบิกพัสดุ</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase">ข้อมูลผู้เบิก</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">จำนวน / ยอดรวม</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">สถานะการดำเนินการ</th>
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center rounded-r-2xl">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($requisitions as $req)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-5 text-center text-slate-400 font-bold">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 font-bold rounded-lg border border-slate-200 uppercase text-sm group-hover:bg-white transition-colors">
                                            {{ $req->requisitions_code ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-[15px] font-bold text-slate-800 leading-tight">คุณ{{ $req->user->fullname }}</span>
                                            <span
                                                class="text-xs font-semibold text-slate-400 flex items-center gap-1.5 mt-1 uppercase">
                                                {{ optional($req->request_date)->locale('th')->isoFormat('D MMM YYYY') ?: '-' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="px-3 py-0.5 bg-slate-800 text-white rounded-lg font-bold text-[11px] mb-1">{{ $req->requisition_items->count() }}
                                                รายการ</span>
                                            <span
                                                class="text-base font-bold text-red-600">฿{{ number_format((float) $req->total_price, 2) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col items-center gap-1.5">
                                            {{-- Packing Status --}}
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $req->packing_status_class }} text-[10px] font-bold uppercase shadow-sm border border-white/20 w-fit">
                                                <i class="{{ $req->packing_status_icon }} text-[9px]"></i>
                                                {{ $req->packing_status_label ?: '—' }}
                                            </span>
                                            {{-- Global Status --}}
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $req->status_class }} text-[10px] font-bold uppercase opacity-60 w-fit">
                                                {{ $req->status_label ?: '—' }}
                                            </span>
                                            @if($req->packing_staff)
                                                <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">จัดโดย: คุณ{{ $req->packing_staff->fullname }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 border border-slate-800 text-white text-[11px] font-bold rounded-xl shadow-lg shadow-slate-100 transition-all hover:-translate-y-0.5 active:scale-95 uppercase">
                                            <i class="fa-solid fa-magnifying-glass-chart text-[11px] opacity-50"></i>
                                            <span>ดูรายละเอียด</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Mobile View -->
            <div class="lg:hidden grid grid-cols-1 gap-4" id="mobileList">
                @forelse($requisitions as $req)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-6 group animate-fade-in requisition-card"
                        data-search="{{ strtolower($req->user->fullname . ' ' . $req->requisitions_code) }}">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1.5">
                                <span
                                    class="text-[10px] font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg border border-red-100 w-fit">{{ $req->requisitions_code ?: 'ไม่มีรหัส' }}</span>
                                <h3 class="text-xl font-bold text-slate-800 leading-tight">คุณ{{ $req->user->fullname }}</h3>
                                <p class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5 uppercase">
                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                    {{ optional($req->request_date)->locale('th')->isoFormat('D MMM YYYY') ?: '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-6 rounded-3xl border border-slate-100">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">จำนวน</span>
                                <span
                                    class="text-base font-bold text-slate-800">{{ number_format($req->requisition_items->count()) }}
                                    <span class="text-[10px] font-normal text-slate-400">รายการ</span></span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200 pl-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">รวมมูลค่า</span>
                                <span
                                    class="text-base font-bold text-red-600">฿{{ number_format((float) $req->total_price, 0) }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex items-center justify-between px-1">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="{{ $req->packing_status_class }} text-[10px] font-bold uppercase px-3 py-1 rounded-lg border border-white/20">
                                        {{ $req->packing_status_label ?: '—' }}
                                    </span>
                                    @if($req->packing_staff)
                                        <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">จัดโดย: คุณ{{ $req->packing_staff->fullname }}</span>
                                    @endif
                                </div>
                                <span class="{{ $req->status_class }} text-[10px] font-bold uppercase opacity-60">
                                    {{ $req->status_label ?: '—' }}
                                </span>
                            </div>
                            <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                class="h-14 flex items-center justify-center bg-slate-900 text-white font-bold rounded-2xl shadow-xl shadow-slate-100 text-[12px] uppercase">
                                ตรวจสอบสถานะและรายละเอียด
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[2.5rem] p-20 shadow-sm border border-slate-100 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-box-open text-2xl text-slate-200"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-xs">ไม่พบรายการใบเบิกของคุณ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

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

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 2rem !important;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 12px;
            padding: 4px 12px;
            border: 1px solid #f1f5f9;
            background-color: #f8fafc;
            font-weight: 600;
            font-size: 13px;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        /* Hide default search bar to use our custom one */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1e293b !important;
            border-color: #1e293b !important;
            color: white !important;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            padding: 6px 16px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8fafc !important;
            border-color: transparent !important;
            color: #dc2626 !important;
            border-radius: 12px;
        }

        table.dataTable thead th {
            border-bottom: 2px solid #f8fafc !important;
        }

        .dataTables_wrapper .dataTables_info {
            font-weight: 700;
            color: #94a3b8 !important;
            font-size: 11px;
            text-transform: uppercase;
        }
    </style>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reqTable = $('#reqTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'desc']],
                columnDefs: [{ orderable: false, targets: [0, 5] }],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' }
            });

            // Global Search Implementation for both Desktop and Mobile
            $('#globalSearch').on('keyup input', function () {
                const value = $(this).val().toLowerCase();

                // 1. Filter DataTable (Desktop)
                reqTable.search(value).draw();

                // 2. Filter Mobile Cards
                $('.requisition-card').each(function () {
                    const searchData = $(this).data('search');
                    if (searchData.includes(value)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Handle Empty Mobile State
                if ($('.requisition-card:visible').length === 0 && value !== '') {
                    if ($('#mobileEmptyState').length === 0) {
                        $('#mobileList').append(`
                                <div id="mobileEmptyState" class="bg-white rounded-[2.5rem] p-20 shadow-sm border border-slate-100 text-center col-span-1">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fa-solid fa-magnifying-glass text-2xl text-slate-200"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold uppercase text-xs">ไม่พบคำขอที่คุณค้นหา</p>
                                </div>
                            `);
                    }
                } else {
                    $('#mobileEmptyState').remove();
                }
            });
        });
    </script>
@endpush