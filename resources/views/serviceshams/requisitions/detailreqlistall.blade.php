@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-[1400px] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                <i class="fa-solid fa-file-invoice text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter leading-none">รายละเอียดใบเบิกพัสดุ</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-500 font-mono">{{ $requisition->requisitions_code }}</span>
                    <span>•</span>
                    <span>{{ optional($requisition->request_date)->format('d/m/Y') }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3 no-print">
            <a href="{{ route('requisitions.reqlistall') }}" 
               class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl border border-slate-100 transition-all active:scale-95 text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้ารวม
            </a>
            <button onclick="window.print()" class="w-12 h-12 flex items-center justify-center bg-white border-2 border-slate-100 text-slate-400 rounded-2xl hover:text-red-600 hover:border-red-100 transition-all shadow-sm">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Requisition Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Info Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-zoom-in" style="animation-delay: 0.1s">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-user-tag text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">ผู้ส่งคำขอเบิก (Requester)</p>
                        <p class="text-[16px] font-black text-slate-700 leading-tight">คุณ{{ $requisition->user->fullname }}</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">{{ $requisition->user->department->department_name ?? '-' }} / {{ $requisition->user->section->section_code ?? '-' }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-truck-fast text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">สถานะจัดส่งพัสดุ</p>
                        <span class="{{ $requisition->packing_status_class }} px-3 py-1 rounded-lg text-[10px] font-black uppercase inline-flex items-center gap-1.5 border border-white/20 shadow-sm">
                            <i class="{{ $requisition->packing_status_icon }} text-[9px]"></i>
                            {{ $requisition->packing_status_label ?: '—' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-zoom-in" style="animation-delay: 0.2s">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
                        <h2 class="text-lg font-black text-slate-800 tracking-tight uppercase">รายการพัสดุที่ขอเบิก</h2>
                    </div>
                    <span class="px-4 py-1.5 bg-slate-50 rounded-full text-[11px] font-black text-slate-400 uppercase border border-slate-100">{{ $requisition->requisition_items->count() }} Items</span>
                </div>
                
                <div class="p-4 md:p-8">
                    <!-- Desktop View Table -->
                    <div class="hidden md:block overflow-x-auto text-[13px]">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl text-center">#</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">ชื่อรายการอุปกรณ์ / SKU</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">จำนวน (ชิ้น)</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">ราคาต่อหน่วย</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right rounded-r-2xl">รวมเป็นเงิน</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($requisition->requisition_items as $index => $item)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-5 text-center text-slate-300 font-black font-mono">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-700 decoration-slate-100 decoration-2 underline-offset-4 underline">{{ $item->item->name ?? '-' }}</span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase mt-1">CODE: {{ $item->item->item_code ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="px-4 py-1.5 bg-slate-800 text-white rounded-xl font-black text-[15px] shadow-lg shadow-slate-100">
                                            {{ $item->quantity > 0 ? $item->quantity : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-slate-400 font-mono">
                                        ฿{{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-slate-800 font-mono text-[16px]">
                                        ฿{{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit * $item->quantity, 2) : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile View Cards -->
                    <div class="md:hidden space-y-4">
                        @foreach ($requisition->requisition_items as $index => $item)
                        <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-300 font-black font-mono text-xs border border-slate-100">{{ $index + 1 }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">QTY: {{ $item->quantity }}</span>
                            </div>
                            <div>
                                <h4 class="font-black text-slate-800 leading-tight">{{ $item->item->name ?? '-' }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">CODE: {{ $item->item->item_code ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200/50">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Total Value</span>
                                <span class="font-black text-slate-800 font-mono">฿{{ number_format($item->item->per_unit * $item->quantity, 2) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-8 bg-slate-900 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                            <i class="fa-solid fa-calculator text-slate-400"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">ยอดรวมประเมิน (Grand Total)</p>
                            <p class="text-[14px] font-bold text-slate-400 leading-none">TOTAL ESTIMATED VALUE</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-black font-mono text-orange-400 decoration-white/20 decoration-4 underline-offset-8">
                            ฿{{ number_format($requisition->total_price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Status & Logs -->
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in" style="animation-delay: 0.3s">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">สถานะกระบวนการ</h3>
                </div>
                
                <div class="space-y-8 relative before:absolute before:left-5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-50">
                    <!-- Request Step -->
                    <div class="relative pl-12">
                        <div class="absolute left-0 w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-50 z-10">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">STEP 01</p>
                            <h4 class="text-[14px] font-black text-slate-800 leading-none">ส่งคำขอเบิก</h4>
                            <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase">{{ optional($requisition->request_date)->format('d M Y | H:i') }}</p>
                        </div>
                    </div>

                    <!-- Approval Step -->
                    <div class="relative pl-12">
                        <div class="absolute left-0 w-10 h-10 {{ $requisition->approve_date ? 'bg-emerald-500' : 'bg-slate-100' }} text-{{ $requisition->approve_date ? 'white' : 'slate-300' }} rounded-full flex items-center justify-center shadow-lg z-10">
                            <i class="fa-solid fa-signature text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">STEP 02</p>
                            <h4 class="text-[14px] font-black {{ $requisition->approve_date ? 'text-slate-800' : 'text-slate-300' }} leading-none">การพิจารณาอนุมัติ</h4>
                            <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase">
                                {{ $requisition->approve_date ? optional($requisition->approve_date)->format('d M Y | H:i') : 'WAITING FOR APPROVAL' }}
                            </p>
                            @if($requisition->approve_comment)
                                <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] font-medium text-slate-500 leading-relaxed">
                                    "{{ $requisition->approve_comment }}"
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Packing Step -->
                    <div class="relative pl-12">
                        <div class="absolute left-0 w-10 h-10 {{ $requisition->packing_date ? 'bg-emerald-500' : 'bg-slate-100' }} text-{{ $requisition->packing_date ? 'white' : 'slate-300' }} rounded-full flex items-center justify-center shadow-lg z-10">
                            <i class="fa-solid fa-boxes-packing text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">STEP 03</p>
                            <h4 class="text-[14px] font-black {{ $requisition->packing_date ? 'text-slate-800' : 'text-slate-300' }} leading-none">จัดเตรียมสิ่งของ</h4>
                            <p class="text-[11px] font-bold text-slate-400 mt-1 uppercase">
                                {{ $requisition->packing_date ? optional($requisition->packing_date)->format('d M Y | H:i') : 'PENDING PACKING' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($requisition->remarks)
            <div class="bg-amber-50 p-8 rounded-[2.5rem] border border-amber-100 animate-zoom-in" style="animation-delay: 0.4s">
                <div class="flex items-center gap-3 mb-4">
                    <i class="fa-solid fa-comment-dots text-amber-500"></i>
                    <h3 class="text-xs font-black text-amber-700 uppercase tracking-widest">บันทึกเพิ่มเติมจากผู้ขอ</h3>
                </div>
                <p class="text-[13px] font-bold text-amber-900 leading-relaxed">"{{ $requisition->remarks }}"</p>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
    @media print {
        body { background: white !important; padding: 0 !important; }
        .no-print { display: none !important; }
        .shadow-sm, .shadow-xl, .shadow-lg, .shadow-red-100 { shadow: none !important; box-shadow: none !important; }
        .max-w-5xl, .max-w-[1400px] { max-width: 100% !important; margin: 0 !important; }
        .bg-slate-900 { background: #1e293b !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
</style>

@endsection
