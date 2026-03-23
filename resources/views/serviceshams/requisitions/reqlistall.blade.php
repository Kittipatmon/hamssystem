@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-[90rem] mx-auto px-4 py-8 space-y-8">

    <!-- Header Section with Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Main Title & Context -->
        <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                    <i class="fa-solid fa-list-check text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">ประวัติการเบิกอุปกรณ์</h1>
                    <p class="text-sm text-slate-500 font-medium">ติดตามสถานะและตรวจสอบรายการใบเบิกทั้งหมดของคุณ</p>
                </div>
            </div>
        </div>

        <!-- Stats 1: Total Requisitions -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-file-invoice text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">คำขอทั้งหมด</div>
                <div class="text-2xl font-black text-slate-800">{{ number_format($requisitions->count()) }} <span class="text-xs font-normal text-slate-400 ml-1">ฉบับ</span></div>
            </div>
        </div>

        <!-- Stats 2: Completed Requisitions -->
        @php $completedCount = $requisitions->where('status', 'endprogress')->count(); @endphp
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">เสร็จสิ้นแล้ว</div>
                <div class="text-2xl font-black text-emerald-600">{{ number_format($completedCount) }} <span class="text-xs font-normal text-slate-400 ml-1">ฉบับ</span></div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Title -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <span class="w-2 h-8 bg-red-600 rounded-full"></span>
            <h2 class="text-lg font-extrabold text-slate-700">รายการใบเบิกพัสดุ</h2>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('items.itemsalllist') }}" class="text-sm font-bold text-red-600 hover:underline flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> กลับไปเลือกอุปกรณ์
            </a>
        </div>
    </div>

    <!-- Content Area: Responsive Dual-View -->
    <div class="space-y-6">
        
        <!-- 1. Desktop View: Premium Table -->
        <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table id="reqTable" class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl text-center">ลำดับ</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">เลขที่ใบเบิก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">ผู้ขอ / วันที่เบิก</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">รายการ / ราคารวม</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะการจัดส่ง</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">สถานะคำขอ</th>
                            <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($requisitions as $req)
                        <tr class="hover:bg-red-50/20 transition-all duration-200 group">
                            <td class="px-6 py-4 text-center">
                                <span class="text-[13px] font-bold text-slate-400">{{ $loop->iteration }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[15px] font-mono font-black text-slate-700 bg-slate-100 px-3 py-1 rounded-xl border border-slate-200">{{ $req->requisitions_code ?: '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[15px] font-black text-slate-800">{{ $req->user->fullname }}</span>
                                    <span class="text-[12px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                        <i class="fa-regular fa-calendar text-[10px]"></i>
                                        {{ optional($req->request_date)->format('d/m/Y') ?: '-' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-[14px] font-bold text-slate-700">{{ $req->requisition_items->count() }} รายการ</span>
                                    <span class="text-[12px] font-black text-red-500 font-mono mt-0.5">฿{{ number_format((float)$req->total_price, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $req->packing_status_class }} text-[11px] font-black uppercase transition-all shadow-sm">
                                    <i class="{{ $req->packing_status_icon }} text-[10px]"></i>
                                    {{ $req->packing_status_label ?: '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $req->status_class }} text-[11px] font-black uppercase transition-all shadow-sm">
                                    <i class="{{ $req->status_icon }} text-[10px]"></i>
                                    {{ $req->status_label ?: '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" 
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-[13px] font-black rounded-xl shadow-lg shadow-slate-100 transition-all active:scale-95">
                                    <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                                    <span>รายละเอียด</span>
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
            @forelse($requisitions as $req)
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 space-y-5 group active:bg-slate-50 transition-colors">
                <!-- Mobile Header -->
                <div class="flex items-start justify-between">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-mono font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-lg border border-red-100 w-fit">{{ $req->requisitions_code ?: 'NO CODE' }}</span>
                        <h3 class="text-lg font-black text-slate-800 leading-tight">{{ $req->user->fullname }}</h3>
                        <p class="text-[11px] text-slate-400 italic">{{ optional($req->request_date)->format('d/m/Y') ?: '-' }}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[11px] font-black text-slate-300 uppercase leading-none">Status</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg {{ $req->status_class }} text-[10px] font-black uppercase">
                             {{ $req->status_label ?: '—' }}
                        </span>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-3 bg-red-50/20 p-4 rounded-2xl">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">จำนวนรายการ</span>
                        <span class="text-xl font-black text-slate-800">{{ number_format($req->requisition_items->count()) }} <span class="text-[10px] font-normal text-slate-400">รายการ</span></span>
                    </div>
                    <div class="flex flex-col border-l border-red-100 pl-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ราคารวม</span>
                        <span class="text-lg font-black text-red-600 font-mono italic">฿{{ number_format((float)$req->total_price, 0) }}</span>
                    </div>
                </div>

                <!-- Mobile Action -->
                <div class="flex items-center justify-between gap-4 pt-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-slate-300 uppercase">Shipping:</span>
                        <span class="{{ $req->packing_status_class }} text-[10px] font-black uppercase px-2 py-0.5 rounded-lg">
                           {{ $req->packing_status_label ?: '—' }}
                        </span>
                    </div>
                    <a href="{{ route('requisitions.detailreqlistall', $req->requisitions_id) }}" 
                       class="flex-1 h-12 flex items-center justify-center bg-slate-800 text-white font-black rounded-2xl shadow-lg shadow-slate-100">
                        ดูรายละเอียด
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-[2rem] p-12 shadow-sm border border-slate-100 text-center">
                <i class="fa-solid fa-box-open text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 font-bold">ไม่พบประวัติการขอเบิกอุปกรณ์</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .dataTables_wrapper .dataTables_length select { border-radius: 12px; padding: 4px 12px; border: 1px solid #f1f5f9; background-color: #f8fafc; font-weight: 600; }
    .dataTables_wrapper .dataTables_filter input { border-radius: 16px; padding: 10px 20px; border: 1px solid #f1f5f9; background-color: #f8fafc; font-weight: 500; font-size: 14px; min-width: 250px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #dc2626 !important; border-color: #dc2626 !important; color: white !important; border-radius: 12px; font-weight: bold; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #fee2e2 !important; border-color: transparent !important; color: #dc2626 !important; border-radius: 12px; }
    table.dataTable thead th { border-bottom: none !important; }
    .dataTables_wrapper .dataTables_info { font-weight: 700; color: #64748b !important; font-size: 13px; }
</style>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#reqTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'desc']],
                columnDefs: [ { orderable: false, targets: [0, 6] } ],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' }
            });
        });
    </script>
@endpush