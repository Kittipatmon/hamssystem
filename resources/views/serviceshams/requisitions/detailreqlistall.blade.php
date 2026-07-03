@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6 uppercase tracking-tight text-xs">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded border border-slate-200 shadow-sm animate-zoom-in">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                    <i class="fa-solid fa-file-invoice text-lg"></i>
                </div>
                <div>
                    <h1 class="text-base font-black text-slate-800 leading-none">รายละเอียดใบเบิกพัสดุ</h1>
                    <p class="text-[11px] text-slate-400 font-semibold mt-1 flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-700 border border-slate-250 font-mono">{{ $requisition->requisitions_code }}</span>
                        <span>•</span>
                        <span>{{ optional($requisition->request_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 no-print">
                <a href="{{ route('requisitions.reqlistall') }}"
                    class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold rounded border border-slate-200 transition-all active:scale-95 text-xs">
                    <i class="fa-solid fa-arrow-left mr-1.5 opacity-70"></i> กลับหน้ารวม
                </a>
                @if($requisition->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                    class="w-8 h-8 flex items-center justify-center bg-white border border-red-200 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                    title="Download PDF">
                    <i class="fa-solid fa-file-pdf"></i>
                </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Requisition Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 animate-zoom-in">
                    <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-user-tag text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">ผู้ส่งคำขอเบิก (Requester)</p>
                            <p class="text-sm font-black text-slate-800 leading-tight">คุณ{{ optional($requisition->user)->fullname ?? 'ไม่ระบุตัวตน' }}</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">
                                {{ optional($requisition->user?->department)->department_name ?? '-' }} /
                                {{ optional($requisition->user?->section)->section_code ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded border border-emerald-100 flex items-center justify-center">
                            <i class="fa-solid fa-truck-fast text-base"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">สถานะจัดส่งพัสดุ</p>
                            <span class="{{ $requisition->packing_status_class }} px-2.5 py-0.5 rounded text-[10px] font-bold inline-flex items-center gap-1 border border-white/20 shadow-sm">
                                <i class="{{ $requisition->packing_status_icon }} text-[9px]"></i>
                                {{ $requisition->packing_status_label ?: '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Items Section -->
                <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden animate-zoom-in">
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                            <h2 class="font-bold text-slate-700">รายการพัสดุที่ขอเบิก</h2>
                        </div>
                        <span class="px-2.5 py-0.5 bg-slate-100 text-[10px] font-bold text-slate-500 rounded border border-slate-250">{{ $requisition->requisition_items->count() }} รายการ</span>
                    </div>

                    <div class="p-4">
                        <!-- Desktop View Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-slate-200">
                                <thead>
                                    <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                        <th class="py-2.5 px-3 border-r border-slate-200 text-center w-16">#</th>
                                        <th class="py-2.5 px-3 border-r border-slate-200">ชื่อรายการอุปกรณ์ / SKU</th>
                                        <th class="py-2.5 px-3 border-r border-slate-200 text-center w-28">จำนวน (ชิ้น)</th>
                                        <th class="py-2.5 px-3 border-r border-slate-200 text-right w-32">ราคาต่อหน่วย</th>
                                        <th class="py-2.5 px-3 text-right w-32">รวมเป็นเงิน</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach ($requisition->requisition_items as $index => $item)
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-2.5 px-3 border-r border-slate-200 text-center text-slate-400 font-bold">
                                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                            </td>
                                            <td class="py-2.5 px-3 border-r border-slate-200 leading-normal">
                                                <div class="font-bold text-slate-800">{{ $item->item->name ?? '-' }}</div>
                                                <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">CODE: {{ $item->item->item_code ?? 'N/A' }}</div>
                                            </td>
                                            <td class="py-2.5 px-3 border-r border-slate-200 text-center">
                                                <span class="inline-block px-2.5 py-1 bg-slate-850 text-slate-700 rounded border border-slate-200 font-bold text-xs">
                                                    {{ $item->quantity > 0 ? $item->quantity : '-' }}
                                                </span>
                                            </td>
                                            <td class="py-2.5 px-3 border-r border-slate-200 text-right font-bold text-slate-450 font-mono">
                                                ฿{{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit, 2) : '-' }}
                                            </td>
                                            <td class="py-2.5 px-3 text-right font-bold text-slate-800 font-mono">
                                                ฿{{ $item->quantity > 0 && $item->item ? number_format($item->item->per_unit * $item->quantity, 2) : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile View Cards -->
                        <div class="md:hidden space-y-3">
                            @foreach ($requisition->requisition_items as $index => $item)
                                <div class="bg-slate-50 p-4 rounded border border-slate-200 space-y-3">
                                    <div class="flex items-center justify-between text-[10px] font-bold">
                                        <span class="w-6 h-6 flex items-center justify-center bg-white rounded text-slate-400 border border-slate-200">{{ $index + 1 }}</span>
                                        <span class="text-slate-500 uppercase">QTY: {{ $item->quantity }}</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800 leading-tight">{{ $item->item->name ?? '-' }}</h4>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">CODE: {{ $item->item->item_code ?? 'N/A' }}</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2.5 border-t border-slate-200/50">
                                        <span class="text-[8px] font-bold text-slate-400 uppercase">รวมเป็นเงิน</span>
                                        <span class="font-bold text-slate-800 font-mono">฿{{ number_format($item->item->per_unit * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-5 bg-slate-800 text-white flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded flex items-center justify-center border border-white/5">
                                <i class="fa-solid fa-calculator text-slate-350"></i>
                            </div>
                            <div class="leading-normal">
                                <p class="text-[9px] font-bold text-slate-400 uppercase leading-none">ยอดรวมประเมิน (Grand Total)</p>
                                <p class="text-[10px] text-slate-500 leading-none">TOTAL ESTIMATED VALUE</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-black text-orange-400 font-mono">
                                ฿{{ number_format($requisition->total_price, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Status & Logs -->
            <div class="space-y-6">
                <div class="bg-white p-5 rounded border border-slate-200 shadow-sm animate-zoom-in">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                        <h3 class="font-bold text-slate-800 uppercase">สถานะกระบวนการ</h3>
                    </div>

                    <div class="space-y-6 relative before:absolute before:left-4 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                        <!-- Request Step -->
                        <div class="relative pl-10">
                            <div class="absolute left-4 top-8 bottom-[-24px] w-0.5 bg-emerald-500 overflow-hidden z-0">
                                <div class="w-full h-full animate-flow-line"></div>
                            </div>
                            <div class="absolute left-0 w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow z-10">
                                <i class="fa-solid fa-paper-plane text-[10px]"></i>
                            </div>
                            <div class="leading-normal">
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-0.5">STEP 01</p>
                                <h4 class="font-bold text-slate-850 leading-none">ส่งคำขอเบิก</h4>
                                <p class="text-[9px] font-bold text-red-600 mt-1 uppercase">ผู้ส่งคำขอ: คุณ{{ optional($requisition->user)->fullname ?? 'ไม่ระบุตัวตน' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">
                                    {{ optional($requisition->created_at)->locale('th')->addYears(543)->isoFormat('D MMM YYYY | HH:mm') }} น.
                                </p>
                            </div>
                        </div>

                        <!-- Approval Step -->
                        @php
                            $isApproved = $requisition->approve_id || $requisition->approve_status == \App\Models\serviceshams\Requisitions::APPROVE_STATUS_APPROVED || $requisition->status === \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS;
                        @endphp
                        <div class="relative pl-10">
                            @if ($isApproved)
                                <div class="absolute left-4 top-8 bottom-[-24px] w-0.5 bg-emerald-500 overflow-hidden z-0">
                                    <div class="w-full h-full animate-flow-line"></div>
                                </div>
                            @endif
                            <div class="absolute left-0 w-8 h-8 {{ $isApproved ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-350' }} rounded-full flex items-center justify-center border border-slate-200 z-10">
                                <i class="fa-solid fa-signature text-[10px]"></i>
                            </div>
                            <div class="leading-normal">
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-0.5">STEP 02</p>
                                <h4 class="font-bold {{ $isApproved ? 'text-slate-850' : 'text-slate-350' }} leading-none">การพิจารณาอนุมัติ</h4>
                                @if($requisition->approve_user)
                                    <p class="text-[9px] font-bold text-emerald-600 mt-1 uppercase">ผู้อนุมัติ: คุณ{{ optional($requisition->approve_user)->fullname ?? 'ไม่ระบุตัวตน' }}</p>
                                @endif
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">
                                    {{ $isApproved ? ($requisition->approve_date ? optional($requisition->approve_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY | HH:mm') . ' น.' : 'APPROVED') : 'WAITING FOR APPROVAL' }}
                                </p>
                                @if($requisition->approve_comment)
                                    <div class="mt-2.5 p-2 bg-slate-50 rounded border border-slate-150 text-[10px] font-semibold text-slate-500 leading-normal">
                                        "{{ $requisition->approve_comment }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Packing Step -->
                        @php
                            $isPacked = $requisition->packing_staff_id || $requisition->packing_staff_status == \App\Models\serviceshams\Requisitions::PACKING_STATUS_APPROVED || $requisition->status === \App\Models\serviceshams\Requisitions::STATUS_END_PROGRESS;
                        @endphp
                        <div class="relative pl-10">
                            <div class="absolute left-0 w-8 h-8 {{ $isPacked ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-350' }} rounded-full flex items-center justify-center border border-slate-200 z-10">
                                <i class="fa-solid fa-boxes-packing text-[10px]"></i>
                            </div>
                            <div class="leading-normal">
                                <p class="text-[9px] font-bold text-slate-400 uppercase mb-0.5">STEP 03</p>
                                <h4 class="font-bold {{ $isPacked ? 'text-slate-850' : 'text-slate-350' }} leading-none">จัดเตรียมสิ่งของ</h4>
                                @if($requisition->packing_staff)
                                    <p class="text-[9px] font-bold text-blue-600 mt-1 uppercase">ผู้จัดเตรียม: คุณ{{ optional($requisition->packing_staff)->fullname ?? 'ไม่ระบุตัวตน' }}</p>
                                @endif
                                <p class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">
                                    {{ $isPacked ? ($requisition->packing_staff_date ? optional($requisition->packing_staff_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY | HH:mm') . ' น.' : 'COMPLETED') : 'PENDING PACKING' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($requisition->remarks)
                    <div class="bg-amber-50 p-5 rounded border border-amber-250 leading-normal">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fa-solid fa-comment-dots text-amber-600"></i>
                            <h3 class="text-[9px] font-bold text-amber-700 uppercase tracking-wider">บันทึกเพิ่มเติมจากผู้ขอ</h3>
                        </div>
                        <p class="font-bold text-amber-900">"{{ $requisition->remarks }}"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            .max-w-[90rem] {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .bg-slate-900 {
                background: #1e293b !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

@endsection