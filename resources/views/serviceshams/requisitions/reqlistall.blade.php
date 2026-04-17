@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[1400px] mx-auto px-4 py-8 space-y-8">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 animate-zoom-in mt-10">
            <!-- Main Title & Context -->
            <div
                class="lg:col-span-2 md:col-span-2 flex flex-col justify-center bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 ">
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

            <!-- Stats 3: Total Value -->
            @php $totalValue = $requisitions->whereNotIn('status', ['pending', 'cancelled'])->sum('total_price'); @endphp
            <div
                class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-red-100 transition-colors">
                <div
                    class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                    <i class="fa-solid fa-coins text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">มูลค่ารวมทั้งหมด</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-3xl font-bold text-red-600">{{ number_format($totalValue, 0) }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">บาท</span>
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
                <!-- Year Filter -->
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fa-solid fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-red-500 transition-colors"></i>
                    <select id="yearFilter"
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="">ทั้งหมด (ทุกปี)</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                ปี {{ $y + 543 }} ({{ $y }})
                            </option>
                        @endforeach
                    </select>
                </div>

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
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">จำนวน /
                                    ยอดรวม</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">
                                    สถานะการดำเนินการ</th>
                                <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">
                                    สถานะการอนุมัติ</th>
                                @if($isHamsOrAdmin)
                                    <th class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center">
                                        ผู้พิจารณาปัจจุบัน</th>
                                @endif
                                <th
                                    class="px-6 py-5 text-[11px] font-bold text-slate-400 uppercase text-center rounded-r-2xl">
                                    จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($requisitions as $req)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-5 text-center text-slate-400 font-bold">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <span
                                            class="px-3 py-1 bg-slate-100 text-slate-600 font-bold rounded-lg border border-slate-200 uppercase text-sm group-hover:bg-white transition-colors">
                                            {{ $req->requisitions_code ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-[15px] font-bold text-slate-800 leading-tight">คุณ{{ optional($req->user)->fullname ?? 'ไม่ระบุตัวตน' }}</span>
                                            <span
                                                class="text-xs font-semibold text-slate-400 flex items-center gap-1.5 mt-1 uppercase">
                                                {{ optional($req->request_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') ?: '-' }}
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
                                                <span
                                                    class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">จัดโดย:
                                                    คุณ{{ $req->packing_staff->fullname }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            @if($req->approve_status == 1) {{-- Approved --}}
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold border border-emerald-100 uppercase">
                                                    <i class="fa-solid fa-check text-[9px]"></i>
                                                    อนุมัติแล้ว
                                                </span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">
                                                    <i class="fa-solid fa-user-check mr-1 opacity-50"></i>
                                                    โดย: {{ optional($req->approve_user)->fullname ?: '-' }}
                                                </span>
                                            @elseif($req->approve_status == 2) {{-- Rejected --}}
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-bold border border-red-100 uppercase">
                                                    <i class="fa-solid fa-xmark text-[9px]"></i>
                                                    ปฏิเสธแล้ว
                                                </span>
                                            @else {{-- Pending --}}
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-bold border border-amber-100 uppercase whitespace-nowrap">
                                                        <i class="fa-regular fa-clock text-[9px]"></i>
                                                        รออนุมัติ
                                                    </span>

                                                    {{-- Only Shoow buttons IF is Hams/Admin OR is the assigned Approver --}}
                                                    @if($isHamsOrAdmin || $req->approve_id === Auth::id())
                                                        <div
                                                            class="flex items-center bg-white border border-slate-200 rounded-lg p-0.5 shadow-sm">
                                                            <button type="button"
                                                                class="w-7 h-7 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 rounded-md transition-all btn-quick-approve"
                                                                data-id="{{ $req->requisitions_id }}" data-status="1" title="อนุมัติ">
                                                                <i class="fa-solid fa-check text-[10px]"></i>
                                                            </button>
                                                            <div class="w-px h-3 bg-slate-200 mx-0.5"></div>
                                                            <button type="button"
                                                                class="w-7 h-7 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-md transition-all btn-quick-approve"
                                                                data-id="{{ $req->requisitions_id }}" data-status="2" title="ปฏิเสธ">
                                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    @if($isHamsOrAdmin)
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    class="w-10 h-10 flex items-center justify-center bg-white border border-slate-100 {{ $req->approve_id ? 'text-blue-500 border-blue-100 bg-blue-50/30' : 'text-slate-300' }} rounded-xl transition-all hover:bg-slate-50 active:scale-95 shadow-sm btn-assign-approver"
                                                    data-id="{{ $req->requisitions_id }}" data-code="{{ $req->requisitions_code }}"
                                                    data-approve="{{ $req->approve_id }}" title="ระบุผู้อนุมัติ">
                                                    <i class="fa-solid fa-users-gear text-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 border border-slate-800 text-white text-[11px] font-bold rounded-xl shadow-lg shadow-slate-100 transition-all hover:-translate-y-0.5 active:scale-95 uppercase">
                                                <i class="fa-solid fa-magnifying-glass-chart text-[11px] opacity-50"></i>
                                                <span>ดูรายละเอียด</span>
                                            </a>
                                            @if($req->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                                                <a href="{{ route('requisitions.detail.pdf', $req->requisitions_id) }}"
                                                    class="w-10 h-10 flex items-center justify-center bg-white border border-slate-100 text-red-500 rounded-xl hover:bg-red-50 hover:border-red-100 transition-all shadow-sm"
                                                    title="ดาวน์โหลด PDF">
                                                    <i class="fa-solid fa-file-pdf"></i>
                                                </a>
                                            @endif
                                        </div>
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
                        data-search="{{ strtolower(optional($req->user)->fullname . ' ' . $req->requisitions_code) }}">
                        <div class="flex items-start justify-between">
                            <div class="flex flex-col gap-1.5">
                                <span
                                    class="text-[10px] font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-lg border border-red-100 w-fit">{{ $req->requisitions_code ?: 'ไม่มีรหัส' }}</span>
                                <h3 class="text-xl font-bold text-slate-800 leading-tight">
                                    คุณ{{ optional($req->user)->fullname ?? 'ไม่ระบุตัวตน' }}</h3>
                                <p class="text-[11px] font-semibold text-slate-400 flex items-center gap-1.5 uppercase">
                                    <i class="fa-regular fa-calendar text-[10px]"></i>
                                    {{ optional($req->request_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') ?: '-' }}
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
                                        <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-tighter">จัดโดย:
                                            คุณ{{ $req->packing_staff->fullname }}</span>
                                    @endif
                                </div>
                                <span class="{{ $req->status_class }} text-[10px] font-bold uppercase opacity-60">
                                    {{ $req->status_label ?: '—' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                    class="flex-1 h-14 flex items-center justify-center bg-slate-900 text-white font-bold rounded-2xl shadow-xl shadow-slate-100 text-[12px] uppercase">
                                    ดูรายละเอียด
                                </a>
                                @if($req->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                                    <a href="{{ route('requisitions.detail.pdf', $req->requisitions_id) }}"
                                        class="w-14 h-14 flex items-center justify-center bg-red-50 text-red-600 rounded-2xl border border-red-100 shadow-sm">
                                        <i class="fa-solid fa-file-pdf text-lg"></i>
                                    </a>
                                @endif
                                @if($isHamsOrAdmin)
                                    <button type="button"
                                        class="w-14 h-14 flex items-center justify-center bg-blue-50 text-blue-600 rounded-2xl border border-blue-100 shadow-sm btn-assign-approver"
                                        data-id="{{ $req->requisitions_id }}" data-code="{{ $req->requisitions_code }}"
                                        data-approve="{{ $req->approve_id }}">
                                        <i class="fa-solid fa-users-gear text-lg"></i>
                                    </button>
                                @endif
                            </div>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #f1f5f9;
            border-radius: 1rem;
            height: 50px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 48px;
            padding-left: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #1e293b;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px;
            right: 15px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Approver Modal -->
    <div id="approverModal"
        class="fixed inset-0 z-[100] hidden bg-slate-900/40 backdrop-blur-sm animate-fade-in transition-all items-center justify-center p-4">
        <div class="w-full max-w-lg scale-95 opacity-0 transition-all duration-300 bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in"
            id="approverModalContent">
            <!-- Modal Header -->
            <div class="bg-emerald-600 p-8 text-white relative">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black leading-none">ระบุผู้พิจารณา</h3>
                        <p class="text-[13px] font-bold opacity-80 mt-1.5 tracking-wide uppercase">รหัสใบเบิก: <span
                                id="modalRequisitionCode" class="font-mono">#RA-XXXXXX</span></p>
                    </div>
                </div>
                <button type="button"
                    class="absolute top-8 right-8 w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-xl transition-all btn-close-modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-8 space-y-8 bg-slate-50/30">
                <form id="approverUpdateForm">
                    @csrf
                    <input type="hidden" name="id" id="modalRequisitionId">

                    <div class="space-y-6 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                        <!-- Target Approver -->
                        <div class="space-y-3">
                            <label class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                                    <i class="fa-solid fa-user-tie text-[12px]"></i>
                                </div>
                                <span class="text-[14px] font-black text-slate-700">ระบุผู้อนุมัติรายการ (Approver)</span>
                            </label>
                            <select name="approve_id" id="select_approver" class="approver-select w-full">
                                <option value="">เลือกผู้อนุมัติ...</option>
                                @foreach($approvers as $u)
                                    <option value="{{ $u->id }}" data-dept="{{ $u->department->department_name ?? '-' }}"
                                        data-role="{{ $u->role }}">
                                        {{ $u->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 font-medium ml-10">
                                รายชื่อบุคคลที่มีสิทธิ์พิจารณาอนุมัติใบเบิกพัสดุ</p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="p-8 bg-slate-50 border-t border-slate-100 flex gap-4">
                <button type="button"
                    class="flex-1 h-16 bg-white border border-slate-200 text-slate-600 font-black rounded-2xl hover:bg-slate-100 transition-all btn-close-modal">
                    ยกเลิก
                </button>
                <button type="button" id="btnSaveApprovers"
                    class="flex-[1.5] h-16 bg-indigo-600 text-white font-black rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-save"></i>
                    <span>บันทึกข้อมูล</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reqTable = $('#reqTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'desc']],
                columnDefs: [{ orderable: false, targets: [0, 5 @if($isHamsOrAdmin), 6, 7 @endif] }],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' }
            });

            // Initialize Select2
            function formatSelect2(state) {
                if (!state.id) return state.text;
                const dept = $(state.element).data('dept');
                const role = $(state.element).data('role');
                return $(`
                        <div class="flex flex-col leading-tight py-1">
                            <span class="font-bold text-slate-700 text-sm">${state.text}</span>
                            <span class="text-[10px] text-slate-400 mt-1 uppercase">${dept} | ${role}</span>
                        </div>
                    `);
            }

            $('.approver-select').select2({
                templateResult: formatSelect2,
                templateSelection: formatSelect2,
                dropdownParent: $('#approverModal')
            });

            // Modal Handlers
            window.openApproverModal = function (id, code, approve) {
                $('#modalRequisitionId').val(id);
                $('#modalRequisitionCode').text('#RA-' + code);
                $('#select_approver').val(approve).trigger('change');

                $('#approverModal').removeClass('hidden').addClass('flex');
                setTimeout(() => {
                    $('#approverModalContent').addClass('scale-100 opacity-100').removeClass('scale-95 opacity-0');
                }, 10);
            };

            function closeModal() {
                $('#approverModalContent').addClass('scale-95 opacity-0').removeClass('scale-100 opacity-100');
                setTimeout(() => {
                    $('#approverModal').addClass('hidden').removeClass('flex');
                }, 300);
            }

            $('.btn-assign-approver').on('click', function () {
                const id = $(this).data('id');
                const code = $(this).data('code');
                const approve = $(this).data('approve');
                openApproverModal(id, code, approve);
            });

            $('.btn-close-modal').on('click', closeModal);

            // Quick Approve Handler
            $('.btn-quick-approve').on('click', function () {
                const id = $(this).data('id');
                const status = $(this).data('status');
                const statusName = status === 1 ? 'อนุมัติ' : 'ปฏิเสธ';
                const confirmButtonColor = status === 1 ? '#10b981' : '#f43f5e';

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
                        $.ajax({
                            url: "{{ route('requisitions.quick_approve') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: id,
                                status: status
                            },
                            success: function (res) {
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
                            }
                        });
                    }
                });
            });

            // Save Handler
            $('#btnSaveApprovers').on('click', function () {
                const btn = $(this);
                const originalHtml = btn.html();

                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> กำลังบันทึก...');

                $.ajax({
                    url: "{{ route('requisitions.update_all_approvers') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $('#modalRequisitionId').val(),
                        approve_id: $('#select_approver').val()
                    },
                    success: function (res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'บันทึกสำเร็จ',
                                text: 'กำหนดผู้พิจารณาเรียบร้อยแล้ว',
                                customClass: { popup: 'rounded-[2rem]' },
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                        btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Year Filter Change
            $('#yearFilter').on('change', function () {
                const year = $(this).val();
                const url = new URL(window.location.href);
                if (year) {
                    url.searchParams.set('year', year);
                } else {
                    url.searchParams.delete('year');
                }
                window.location.href = url.toString();
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