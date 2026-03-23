@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-5xl mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-red-600 rounded-3xl flex items-center justify-center shadow-lg shadow-red-100">
                <i class="fa-solid fa-file-invoice text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter italic leading-none">รายละเอียดใบเบิกพัสดุ</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-500 font-mono">{{ $requisition->requisitions_code }}</span>
                    <span>•</span>
                    <span>{{ optional($requisition->request_date)->format('d/m/Y') }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('requisitions.reqlistall') }}" 
               class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl border border-slate-100 transition-all active:scale-95 text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้ารวม
            </a>
            <button onclick="window.print()" class="w-12 h-12 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-2xl hover:text-red-600 hover:border-red-100 transition-all">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-user-tag text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">ผู้ส่งคำขอ</p>
                <p class="text-[15px] font-black text-slate-700 leading-tight">{{ $requisition->user->fullname }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-truck-fast text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">สถานะจัดส่ง</p>
                <span class="{{ $requisition->packing_status_class }} px-2 py-0.5 rounded text-[10px] font-black uppercase inline-block">{{ $requisition->packing_status_label ?: '—' }}</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">สถานะใบเบิก</p>
                <span class="{{ $requisition->status_class }} px-2 py-0.5 rounded text-[10px] font-black uppercase inline-block">{{ $requisition->status_label ?: '—' }}</span>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center gap-3">
            <div class="w-1.5 h-6 bg-red-600 rounded-full"></div>
            <h2 class="text-lg font-black text-slate-800 tracking-tight italic uppercase">รายการพัสดุที่ขอเบิก</h2>
        </div>
        <div class="p-4 overflow-x-auto text-[14px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl text-center">#</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">ชื่อรายการอุปกรณ์</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">จำนวน (ชิ้น)</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">ราคาต่อหน่วย</th>
                        <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right rounded-r-2xl">รวมเป็นเงิน</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($requisition->requisition_items as $index => $item)
                    <tr>
                        <td class="px-6 py-5 text-center text-slate-400 font-bold tracking-tighter">{{ $index + 1 }}</td>
                        <td class="px-6 py-5">
                            <span class="font-black text-slate-700 underline decoration-slate-100 decoration-4 underline-offset-4">{{ $item->item->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl font-black text-slate-600 italic">
                                {{ $item->quantity > 0 ? $item->quantity : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right font-bold text-slate-500 font-mono">
                            {{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit, 2) : '-' }}
                        </td>
                        <td class="px-6 py-5 text-right">
                           <span class="font-black text-slate-800 font-mono italic">
                               {{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit * $item->quantity, 2) : '-' }}
                           </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-red-600 text-white shadow-xl shadow-red-100">
                        <td colspan="4" class="px-8 py-6 rounded-l-[1.5rem] font-black text-right tracking-[0.2em] italic uppercase">GRAND TOTAL (บาท)</td>
                        <td class="px-8 py-6 rounded-r-[1.5rem] text-right text-2xl font-black italic font-mono decoration-white decoration-4 underline-offset-8">
                            {{ number_format($requisition->total_price, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
    @media print {
        body { background: white !important; padding: 0 !important; }
        .no-print { display: none !important; }
        .shadow-sm, .shadow-xl { shadow: none !important; }
    }
</style>

@endsection