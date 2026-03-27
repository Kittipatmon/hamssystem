@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-5xl mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-orange-50 animate-zoom-in">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-orange-500 rounded-3xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fa-solid fa-clock-rotate-left text-white text-2xl animate-spin-pulse"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter  leading-none">ใบเบิกพัสดุ (รอดำเนินการ)</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-orange-50 text-orange-600 rounded font-mono border border-orange-100">{{ $requisition->requisitions_code }}</span>
                    <span>•</span>
                    <span class="">กำลังรอฝ่ายพัสดุตรวจสอบ</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('requisitions.reqlistpending') }}" 
               class="px-6 py-3 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl border border-slate-100 transition-all active:scale-95 text-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้ารอเบิก
            </a>
        </div>
    </div>

    <!-- Status Timeline & Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h3 class="text-xs font-black text-slate-300 uppercase tracking-[0.2em] mb-6">ความคืบหน้าปัจจุบัน</h3>
            <div class="flex items-center gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-100">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div class="w-0.5 h-8 bg-slate-100"></div>
                    <div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center animate-pulse">
                        <i class="fa-solid fa-hourglass-half text-xs"></i>
                    </div>
                </div>
                <div class="flex flex-col gap-9 pb-2">
                    <div>
                        <p class="text-[14px] font-black text-slate-700 leading-none">ส่งคำขอเบิกแล้ว</p>
                        <p class="text-[11px] text-slate-400 font-bold mt-1 uppercase ">{{ optional($requisition->request_date)->format('d M Y | H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[14px] font-black text-slate-400 leading-none">รอพิจารณา / ตรวจสอบพัสดุ</p>
                        <p class="text-[11px] text-orange-400 font-bold mt-1 uppercase ">In Queue for Approval</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-800 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200 text-white flex flex-col justify-between">
            <div>
                <i class="fa-solid fa-quote-left text-3xl text-slate-600 mb-4 opacity-30"></i>
                <p class="text-[13px] font-bold text-slate-300  leading-relaxed">"ใบเบิกของคุณได้รับการจัดลำดับแล้ว กรุณารอการติดต่อกลับ หรือสถานะการอัปเดตจากเจ้าหน้าที่ HAMS"</p>
            </div>
            <div class="pt-6 border-t border-slate-700 mt-6">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">ยอดรวมประเมิน</p>
                <p class="text-3xl font-black  font-mono text-orange-400">฿{{ number_format($requisition->total_price, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Items List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
                <h2 class="text-lg font-black text-slate-800 tracking-tight  uppercase">รายการพัสดุที่รอเบิก</h2>
            </div>
            <span class="px-4 py-1.5 bg-slate-50 rounded-full text-[11px] font-black text-slate-400 uppercase border border-slate-100">{{ $requisition->requisition_items->count() }} Items</span>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                @foreach ($requisition->requisition_items as $index => $item)
                <div class="flex items-center justify-between p-5 bg-slate-50/50 rounded-3xl border border-slate-100 hover:border-orange-200 transition-colors group">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 font-black  border border-slate-100 shadow-sm group-hover:text-orange-400 transition-colors">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <p class="text-[15px] font-black text-slate-700">{{ $item->item->name ?? '-' }}</p>
                            <p class="text-[11px] font-bold text-slate-400 uppercase ">Unit Price: ฿{{ number_format($item->item->per_unit ?? 0, 2) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-8 text-right">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Qty</span>
                            <span class="text-[16px] font-black text-slate-700 ">{{ $item->quantity }} <span class="text-[10px] text-slate-400 font-normal">ชิ้น</span></span>
                        </div>
                        <div class="flex flex-col w-24">
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Total</span>
                            <span class="text-[16px] font-black text-orange-600 font-mono ">฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Footer Action -->
        <div class="p-8 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-slate-400">
                <i class="fa-solid fa-circle-info text-xl opacity-30"></i>
                <p class="text-[12px] font-medium leading-relaxed ">คุณยังสามารถยกเลิกใบเบิกนี้ได้ก่อนที่เจ้าหน้าที่จะทำการเริ่มขั้นตอน <span class="text-orange-500 font-black ">"กำลังดำเนินการ"</span></p>
            </div>
            <button class="w-full md:w-auto px-10 py-4 bg-white border-2 border-red-50 text-red-500 hover:bg-red-600 hover:text-white font-black rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-3 btn-cancel-req"
                    data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}">
                <i class="fa-solid fa-xmark"></i>
                ยกเลิกใบเบิกฉบับนี้
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelector('.btn-cancel-req').addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.href;
            Swal.fire({
                title: '<span class="text-slate-800 font-black tracking-tight">ยืนยันการยกเลิก?</span>',
                html: '<p class="text-sm text-slate-500 font-medium leading-relaxed ">"ใบเบิกของคุณจะถูกระงับทันที และไม่สามารถเรียกกลับมาได้"</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน ยกเลิกใบเบิก',
                cancelButtonText: 'ปิดตัวช่วย',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#1e293b',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem]',
                    confirmButton: 'rounded-xl px-8 py-3.5 font-black',
                    cancelButton: 'rounded-xl px-8 py-3.5 font-black'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
    .animate-spin-pulse { animation: spin-pulse 4s infinite linear; }
    @keyframes spin-pulse { 
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.1); }
        100% { transform: rotate(360deg) scale(1); }
    }
</style>

@endsection
