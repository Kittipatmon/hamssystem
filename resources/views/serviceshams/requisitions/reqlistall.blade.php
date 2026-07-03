@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-10">
            <!-- Main Title & Context -->
            <div class="lg:col-span-2 md:col-span-2 flex flex-col justify-center bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-list-check text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">ประวัติการเบิกอุปกรณ์</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">ติดตามสถานะและตรวจสอบรายการใบเบิกทั้งหมดของคุณ</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Total Requisitions -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-200 flex items-center justify-center">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">คำขอทั้งหมด</div>
                    <div class="text-lg font-black text-slate-800 mt-0.5">
                        {{ number_format($requisitions->count()) }} <span class="text-xs font-normal text-slate-400">ใบ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Completed Requisitions -->
            @php $completedCount = $requisitions->where('status', 'endprogress')->count(); @endphp
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded border border-emerald-200 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">เสร็จสิ้นแล้ว</div>
                    <div class="text-lg font-black text-emerald-600 mt-0.5">
                        {{ number_format($completedCount) }} <span class="text-xs font-normal text-slate-400">รายการ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 3: Total Value -->
            @php $totalValue = $requisitions->whereNotIn('status', ['pending', 'cancelled'])->sum('total_price'); @endphp
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded border border-red-200 flex items-center justify-center">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">มูลค่ารวมทั้งหมด</div>
                    <div class="text-lg font-black text-red-600 mt-0.5">
                        {{ number_format($totalValue, 0) }} <span class="text-xs font-normal text-slate-400">บาท</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar: Title & Search -->
        <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold">
            <div class="flex items-center gap-2 mr-2">
                <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                <h2 class="font-bold text-slate-700">รายการใบเบิกพัสดุรายบุคคล</h2>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                <!-- Year Filter -->
                <div class="relative w-full sm:w-40">
                    <select id="yearFilter"
                        class="w-full pl-8 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all appearance-none cursor-pointer">
                        <option value="">ทั้งหมด (ทุกปี)</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                ปี {{ $y + 543 }} ({{ $y }})
                            </option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-calendar-days absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                </div>

                <!-- Global Search Input -->
                <div class="relative w-full sm:w-64">
                    <input type="text" id="globalSearch" placeholder="ค้นหาตามรายชื่อ หรือรหัสใบเบิก..."
                        class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                </div>

                <button type="button" onclick="openExportModal()"
                    class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded shadow transition-all">
                    <i class="fa-solid fa-file-excel text-[10px]"></i>
                    <span>Export สรุปรายเดือน</span>
                </button>

                <a href="{{ route('items.itemsalllist') }}"
                    class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-all">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>ไปหน้าเลือกอุปกรณ์</span>
                </a>
            </div>
        </div>

        <!-- Content Area: Responsive Dual-View -->
        <div class="space-y-6">

            <!-- 1. Desktop View -->
            <div class="hidden lg:block bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-4 overflow-x-auto">
                    <table id="reqTable" class="w-full text-left border-collapse border border-slate-200 text-xs">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-16">ลำดับ</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-36">รหัสใบเบิกพัสดุ</th>
                                <th class="py-3 px-3 border-r border-slate-200">ข้อมูลผู้เบิก</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-32">จำนวน / ยอดรวม</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-40">สถานะการดำเนินการ</th>
                                <th class="py-3 px-3 border-r border-slate-200 text-center w-44">สถานะการอนุมัติ</th>
                                @if($isHamsOrAdmin)
                                    <th class="py-3 px-3 border-r border-slate-200 text-center w-28">ผู้พิจารณา</th>
                                @endif
                                <th class="py-3 px-3 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($requisitions as $req)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3 border-r border-slate-200 text-center text-slate-400 font-bold">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                            {{ $req->requisitions_code ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 leading-normal">
                                        <div class="font-bold text-slate-800">คุณ{{ optional($req->user)->fullname ?? 'ไม่ระบุตัวตน' }}</div>
                                        <span class="text-[9px] font-bold text-slate-400 flex items-center gap-1 mt-1 uppercase">
                                            {{ optional($req->request_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') ?: '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center leading-normal font-bold">
                                        <span class="px-2 py-0.5 bg-slate-800 text-white rounded text-[9px]">{{ $req->requisition_items->count() }} รายการ</span>
                                        <span class="text-red-600 block mt-1">฿{{ number_format((float) $req->total_price, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            {{-- Packing Status --}}
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $req->packing_status_class }} text-[9px] font-bold uppercase border border-white/20 w-fit">
                                                <i class="{{ $req->packing_status_icon }} text-[9px]"></i>
                                                {{ $req->packing_status_label ?: '—' }}
                                            </span>
                                            {{-- Global Status --}}
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $req->status_class }} text-[9px] font-bold uppercase opacity-65 w-fit">
                                                {{ $req->status_label ?: '—' }}
                                            </span>
                                            @if($req->packing_staff)
                                                <span class="text-[8px] font-bold text-slate-400 mt-1 uppercase">จัดโดย: คุณ{{ $req->packing_staff->fullname }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 border-r border-slate-200 text-center leading-normal">
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            @if($req->approve_status == 1) {{-- Approved --}}
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[9px] font-bold border border-emerald-250 uppercase">
                                                    <i class="fa-solid fa-check text-[9px]"></i> อนุมัติแล้ว
                                                </span>
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-0.5">
                                                    โดย: {{ optional($req->approve_user)->fullname ?: '-' }}
                                                </span>
                                            @elseif($req->approve_status == 2) {{-- Rejected --}}
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-red-50 text-red-700 rounded-full text-[9px] font-bold border border-red-250 uppercase">
                                                    <i class="fa-solid fa-xmark text-[9px]"></i> ปฏิเสธแล้ว
                                                </span>
                                            @else {{-- Pending --}}
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[9px] font-bold border border-amber-200 uppercase whitespace-nowrap">
                                                        <i class="fa-regular fa-clock text-[9px]"></i> รออนุมัติ
                                                    </span>

                                                    @if($isHamsOrAdmin || $req->approve_id === Auth::id())
                                                        <div class="flex items-center bg-slate-100 border border-slate-200 rounded p-0.5 shadow-sm mt-0.5">
                                                            <button type="button"
                                                                class="w-5 h-5 flex items-center justify-center text-emerald-600 bg-white hover:bg-emerald-600 hover:text-white rounded transition-colors btn-quick-approve"
                                                                data-id="{{ $req->requisitions_id }}" data-status="1" title="อนุมัติ">
                                                                <i class="fa-solid fa-check text-[9px]"></i>
                                                            </button>
                                                            <div class="w-px h-3 bg-slate-250 mx-0.5"></div>
                                                            <button type="button"
                                                                class="w-5 h-5 flex items-center justify-center text-rose-600 bg-white hover:bg-rose-600 hover:text-white rounded transition-colors btn-quick-approve"
                                                                data-id="{{ $req->requisitions_id }}" data-status="2" title="ปฏิเสธ">
                                                                <i class="fa-solid fa-xmark text-[9px]"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    @if($isHamsOrAdmin)
                                        <td class="py-3 px-3 border-r border-slate-200 text-center">
                                            <button type="button"
                                                class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 {{ $req->approve_id ? 'text-blue-600 border-blue-300 bg-blue-50/50' : 'text-slate-400' }} rounded transition-colors shadow-sm btn-assign-approver mx-auto"
                                                data-id="{{ $req->requisitions_id }}" data-code="{{ $req->requisitions_code }}"
                                                data-approve="{{ $req->approve_id }}" title="ระบุผู้อนุมัติ">
                                                <i class="fa-solid fa-users-gear text-sm"></i>
                                            </button>
                                        </td>
                                    @endif
                                    <td class="py-3 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                                class="px-2.5 py-1.5 bg-slate-900 border border-slate-800 text-white text-[10px] font-bold rounded shadow transition-all flex items-center gap-1">
                                                <i class="fa-solid fa-magnifying-glass-chart text-[9px] opacity-75"></i>
                                                <span>ดูรายละเอียด</span>
                                            </a>
                                            @if($req->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                                                <a href="{{ route('requisitions.detail.pdf', $req->requisitions_id) }}"
                                                    class="w-7 h-7 flex items-center justify-center bg-white border border-red-200 hover:border-red-400 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
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
            <div class="lg:hidden grid grid-cols-1 gap-3" id="mobileList">
                @forelse($requisitions as $req)
                    <div class="bg-white rounded border border-slate-200 p-4 shadow-sm space-y-3 requisition-card animate-fade-in"
                        data-search="{{ strtolower(optional($req->user)->fullname . ' ' . $req->requisitions_code) }}">
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100 w-fit block">{{ $req->requisitions_code ?: 'ไม่มีรหัส' }}</span>
                                <h3 class="text-xs font-bold text-slate-800 leading-none pt-1">คุณ{{ optional($req->user)->fullname ?? 'ไม่ระบุตัวตน' }}</h3>
                                <p class="text-[9px] font-bold text-slate-400 flex items-center gap-1 uppercase">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ optional($req->request_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') ?: '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-slate-50 p-3 rounded border border-slate-100 text-xs font-bold">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider mb-0.5">จำนวน</span>
                                <span class="text-slate-700">{{ number_format($req->requisition_items->count()) }} <span class="text-[9px] font-normal text-slate-400">รายการ</span></span>
                            </div>
                            <div class="flex flex-col border-l border-slate-200 pl-3">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider mb-0.5">รวมมูลค่า</span>
                                <span class="text-red-600 font-mono">฿{{ number_format((float) $req->total_price, 0) }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 text-xs font-bold">
                            <div class="flex items-center justify-between px-1">
                                <div class="flex flex-col">
                                    <span class="{{ $req->packing_status_class }} text-[9px] font-bold uppercase px-2 py-0.5 rounded border border-white/20 w-fit">
                                        {{ $req->packing_status_label ?: '—' }}
                                    </span>
                                    @if($req->packing_staff)
                                        <span class="text-[8px] font-bold text-slate-400 mt-0.5">จัดโดย: คุณ{{ $req->packing_staff->fullname }}</span>
                                    @endif
                                </div>
                                <span class="{{ $req->status_class }} text-[9px] font-bold uppercase opacity-65">
                                    {{ $req->status_label ?: '—' }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 pt-1">
                                <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}"
                                    class="flex-1 h-9 flex items-center justify-center bg-slate-900 text-white rounded text-xs">
                                    ดูรายละเอียด
                                </a>
                                @if($req->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                                    <a href="{{ route('requisitions.detail.pdf', $req->requisitions_id) }}"
                                        class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded border border-red-150 shadow-sm">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </a>
                                @endif
                                @if($isHamsOrAdmin)
                                    <button type="button"
                                        class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded border border-blue-150 shadow-sm btn-assign-approver"
                                        data-id="{{ $req->requisitions_id }}" data-code="{{ $req->requisitions_code }}"
                                        data-approve="{{ $req->approve_id }}">
                                        <i class="fa-solid fa-users-gear text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded border border-slate-200 p-12 text-center">
                        <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-box-open text-xl text-slate-350"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-[10px]">ไม่พบรายการใบเบิกของคุณ</p>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

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

    <!-- Export Modal -->
    <div id="exportModal" class="fixed inset-0 z-[100] hidden bg-slate-900/40 backdrop-blur-sm animate-fade-in transition-all items-center justify-center p-4">
        <div class="w-full max-w-md scale-95 opacity-0 transition-all duration-300 bg-white rounded-[2rem] shadow-2xl overflow-hidden animate-zoom-in" id="exportModalContent">
            <div class="bg-emerald-600 p-6 text-white relative">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                        <i class="fa-solid fa-file-excel text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black leading-none">Export สรุปรายเดือน</h3>
                        <p class="text-[11px] font-bold opacity-80 mt-1">เลือกช่วงเวลาที่ต้องการสรุปข้อมูล</p>
                    </div>
                </div>
                <button type="button" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-lg transition-all" onclick="closeExportModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="{{ route('requisitions.export_summary') }}" method="GET" class="p-6 space-y-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700">ตั้งแต่เดือน</label>
                            <input type="text" name="start_month" required value="{{ date('Y-m') }}" class="month-picker w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:border-emerald-600 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-700">ถึงเดือน</label>
                            <input type="text" name="end_month" required value="{{ date('Y-m') }}" class="month-picker w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:bg-white focus:border-emerald-600 outline-none">
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeExportModal()" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">ยกเลิก</button>
                    <button type="submit" onclick="closeExportModal()" class="flex-1 px-4 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-download"></i> ดาวน์โหลด
                    </button>
                </div>
            </form>
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

            flatpickr(".month-picker", {
                locale: "th",
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        dateFormat: "Y-m", 
                        altFormat: "F Y",
                    })
                ]
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

            window.openExportModal = function() {
                $('#exportModal').removeClass('hidden').addClass('flex');
                setTimeout(() => {
                    $('#exportModalContent').addClass('scale-100 opacity-100').removeClass('scale-95 opacity-0');
                }, 10);
            };

            window.closeExportModal = function() {
                $('#exportModalContent').addClass('scale-95 opacity-0').removeClass('scale-100 opacity-100');
                setTimeout(() => {
                    $('#exportModal').addClass('hidden').removeClass('flex');
                }, 300);
            };

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