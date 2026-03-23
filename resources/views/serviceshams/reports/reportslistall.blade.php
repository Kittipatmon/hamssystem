@extends('layouts.serviceitem.appservice')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-slate-800 rounded-3xl flex items-center justify-center shadow-lg shadow-slate-200">
                <i class="fa-solid fa-chart-line text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter italic leading-none">รายงานการเบิกพัสดุ</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-mono">REPORTS CENTRE</span>
                    <span>•</span>
                    <span class="italic">สรุปข้อมูลการเบิกพัสดุและไฟล์นำออก</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button id="filter-button" class="px-6 py-3 bg-white border-2 border-slate-100 text-slate-600 font-black rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all active:scale-95 text-sm flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> ตัวกรอง (Filter)
            </button>
            <div class="h-10 w-px bg-slate-100 mx-2"></div>
            <a href="{{ route('requisitions.reportslistall.export.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
               class="w-12 h-12 flex items-center justify-center bg-red-50 text-red-600 rounded-2xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
            <a href="{{ route('requisitions.reportslistall.export.csv', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
               class="w-12 h-12 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm cursor-pointer">
                <i class="fa-solid fa-file-excel"></i>
            </a>
        </div>
    </div>

    <!-- Filter Section (Hidden by default) -->
    <div id="filter-section" class="bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-700 hidden animate-zoom-in">
        <form method="GET" action="{{ route('requisitions.reportslistall') }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2">วันที่เริ่มต้น (Start Date)</label>
                <input type="date" name="start_date" class="w-full h-14 bg-slate-900 border-none rounded-2xl px-6 text-white font-black text-sm focus:ring-2 focus:ring-red-500 transition-all" value="{{ request('start_date') }}">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2">วันที่สิ้นสุด (End Date)</label>
                <input type="date" name="end_date" class="w-full h-14 bg-slate-900 border-none rounded-2xl px-6 text-white font-black text-sm focus:ring-2 focus:ring-red-500 transition-all" value="{{ request('end_date') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full h-14 bg-red-600 hover:bg-red-700 text-white font-black rounded-2xl shadow-lg shadow-red-900/40 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-magnifying-glass"></i> APPLY FILTERS
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-file-invoice text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">จำนวนรายการ</p>
                <p class="text-[20px] font-black text-slate-700 leading-tight italic font-mono">{{ $requisitions->count() }} <span class="text-[10px] text-slate-300">REQ</span></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-baht-sign text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">มูลค่ารวม (Total)</p>
                <p class="text-[20px] font-black text-slate-700 leading-tight italic font-mono">฿{{ number_format($requisitions->sum('total_price'), 2) }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-box-open text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">เฉลี่ยต่อรายการ</p>
                <p class="text-[20px] font-black text-slate-700 leading-tight italic font-mono">฿{{ number_format($requisitions->count() > 0 ? $requisitions->sum('total_price') / $requisitions->count() : 0, 2) }}</p>
            </div>
        </div>
        <div class="bg-slate-800 p-6 rounded-[2rem] shadow-lg shadow-slate-100 flex items-center gap-4 text-white">
            <div class="w-12 h-12 bg-slate-700 text-slate-300 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">ช่วงเวลาที่แสดง</p>
                <p class="text-[14px] font-black italic">{{ request('start_date') ? date('d M', strtotime(request('start_date'))) : 'Earliest' }} - {{ request('end_date') ? date('d M Y', strtotime(request('end_date'))) : 'Today' }}</p>
            </div>
        </div>
    </div>

    <!-- Reports Table / Grid -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-800 tracking-tight italic uppercase">รายการเบิกทั้งหมดในระบบ</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden md:inline-block text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mr-4 italic">Interactive Reporting</span>
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button id="view-table-btn" class="p-2 px-4 rounded-lg bg-white shadow-sm text-slate-800 transition-all">
                        <i class="fa-solid fa-table-list"></i>
                    </button>
                    <button id="view-grid-btn" class="p-2 px-4 rounded-lg text-slate-400 hover:text-slate-600 transition-all">
                        <i class="fa-solid fa-grip"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <!-- Desktop Table View -->
            <div id="desktop-view" class="overflow-x-auto text-[13px]">
                <table id="reqlist-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl">เลขที่คำขอ</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">ข้อมูลผู้เบิก</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">สังกัด (สายงาน/ฝ่าย/แผนก)</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">วันที่ขอ</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">ยอดรวม</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะจัดส่ง</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisitions as $req)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-5 py-5">
                                <span class="font-black text-slate-800 font-mono italic decoration-slate-100 decoration-4 underline-offset-4 underline">{{ $req->requisitions_code ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                        <i class="fa-solid fa-user text-[10px]"></i>
                                    </div>
                                    <span class="font-black text-slate-600">คุณ{{ $req->user->fullname }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-5">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $req->user->section->section_code ?? '-' }}</span>
                                    <span class="text-[11px] font-bold text-slate-500 italic">{{ $req->user->division->division_name ?? '-' }}</span>
                                    <span class="text-[10px] font-medium text-slate-300">{{ $req->user->department->department_name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-5 text-center font-bold text-slate-400 italic font-mono uppercase">
                                {{ optional($req->request_date)->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-5 text-right font-black text-slate-700 italic font-mono">
                                ฿{{ number_format((float) ($req->total_price ?? 0), 2) }}
                            </td>
                            <td class="px-5 py-5 text-center">
                                <span class="{{ $req->packing_status_class }} text-[9px] font-black uppercase px-3 py-1 rounded-full flex items-center justify-center gap-1.5 w-fit mx-auto border border-white/20 shadow-sm">
                                    <i class="{{ $req->packing_status_icon }} text-[8px]"></i>
                                    {{ $req->packing_status_label ?? '—' }}
                                </span>
                            </td>
                            <td class="px-5 py-5 text-center">
                                <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" class="w-9 h-9 inline-flex items-center justify-center bg-white border border-slate-100 text-slate-400 rounded-xl hover:bg-slate-800 hover:text-white hover:border-slate-800 transition-all active:scale-95 shadow-sm">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (Hidden on Desktop) -->
            <div id="mobile-view" class="hidden">
                <div class="grid grid-cols-1 gap-4">
                    @foreach ($requisitions as $req)
                    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm space-y-5 animate-zoom-in">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg font-black font-mono text-xs italic">{{ $req->requisitions_code }}</span>
                            <span class="{{ $req->packing_status_class }} text-[9px] font-black uppercase px-3 py-1 rounded-full shadow-sm">
                                {{ $req->packing_status_label }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">ข้อมูลผู้เบิก</p>
                            <h3 class="text-[16px] font-black text-slate-800 italic underline decoration-slate-100 decoration-4 underline-offset-4">คุณ{{ $req->user->fullname }}</h3>
                            <p class="text-[11px] font-bold text-slate-400 italic mt-1">{{ $req->user->division->division_name ?? '-' }} / {{ $req->user->section->section_code }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-50">
                            <div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest font-mono">DATE</p>
                                <p class="text-[14px] font-black text-slate-600 italic">{{ optional($req->request_date)->format('d / m / Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest font-mono">TOTAL VALUE</p>
                                <p class="text-[15px] font-black text-red-600 italic font-mono">฿{{ number_format((float) ($req->total_price ?? 0), 2) }}</p>
                            </div>
                        </div>

                        <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" class="w-full py-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-95 text-xs uppercase tracking-widest italic border border-slate-100">
                            <i class="fa-regular fa-eye"></i> View Detailed Report
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
            color: #94a3b8 !important; font-size: 11px !important; font-weight: 800 !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; padding: 20px 0 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1e293b !important; color: white !important; border: none !important; border-radius: 12px !important; font-weight: 800 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important; border: none !important; color: #1e293b !important; border-radius: 12px !important;
        }
        table.dataTable thead th { border-bottom: 2px solid #f8fafc !important; }
        table.dataTable.no-footer { border-bottom: 1px solid #f8fafc !important; }
        @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // DataTables Initiation
            const table = $('#reqlist-table').DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json' },
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [],
                columnDefs: [{ orderable: false, targets: [6] }]
            });

            // Dual View Logic
            const desktopView = document.getElementById('desktop-view');
            const mobileView = document.getElementById('mobile-view');
            const tableBtn = document.getElementById('view-table-btn');
            const gridBtn = document.getElementById('view-grid-btn');

            function toggleView(isGrid) {
                if (isGrid) {
                    desktopView.classList.add('hidden');
                    mobileView.classList.remove('hidden');
                    gridBtn.classList.add('bg-white', 'shadow-sm', 'text-slate-800');
                    gridBtn.classList.remove('text-slate-400');
                    tableBtn.classList.remove('bg-white', 'shadow-sm', 'text-slate-800');
                    tableBtn.classList.add('text-slate-400');
                } else {
                    desktopView.classList.remove('hidden');
                    mobileView.classList.add('hidden');
                    tableBtn.classList.add('bg-white', 'shadow-sm', 'text-slate-800');
                    tableBtn.classList.remove('text-slate-400');
                    gridBtn.classList.remove('bg-white', 'shadow-sm', 'text-slate-800');
                    gridBtn.classList.add('text-slate-400');
                }
            }

            tableBtn.addEventListener('click', () => toggleView(false));
            gridBtn.addEventListener('click', () => toggleView(true));

            // Auto-switch based on resolution
            if (window.innerWidth < 1024) toggleView(true);

            // Toggle filter section
            const filterBtn = document.getElementById('filter-button');
            const filterSection = document.getElementById('filter-section');
            filterBtn.addEventListener('click', function () {
                filterSection.classList.toggle('hidden');
            });
        });
    </script>
@endpush