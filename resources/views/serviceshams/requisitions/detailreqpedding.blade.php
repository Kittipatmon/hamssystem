@extends('layouts.serviceitem.appservice')
@section('content')

    @php
        $isOwner = Auth::check() && $requisition->requester_id === Auth::id();
        $isHamsOrAdmin = Auth::check() && (
            in_array(Auth::user()->role, ['admin', 'editor']) ||
            in_array(Auth::user()->dept_id, [14, 16])
        );
    @endphp

    @if($isOwner || $isHamsOrAdmin)
        <div class="max-w-5xl mx-auto px-4 py-6 space-y-6 uppercase tracking-tight text-xs">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-black text-slate-800 leading-none">ใบเบิกพัสดุ ({{ $requisition->status_label }})</h1>
                        <p class="text-[11px] text-slate-400 font-semibold mt-1 flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded border border-slate-250 font-mono">{{ $requisition->requisitions_code }}</span>
                            <span>•</span>
                            <span>
                                @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                                    กำลังรอฝ่ายพัสดุตรวจสอบ
                                @elseif($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_APPROVED)
                                    เจ้าหน้าที่ได้รับการพิจารณาและกำลังเตรียมพัสดุ
                                @else
                                    ดำเนินการเสร็จสิ้นเรียบร้อยแล้ว
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('requisitions.reqlistpending') }}"
                        class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded border border-slate-200 transition-all active:scale-95 text-xs shadow-sm flex items-center gap-1.5">
                        <i class="fa-solid fa-arrow-left"></i> กลับหน้ารอเบิก
                    </a>
                    @if($requisition->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                        <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                            class="w-8 h-8 flex items-center justify-center bg-white border border-red-200 text-red-600 rounded hover:bg-red-50/50 transition-colors shadow-sm"
                            title="ดาวน์โหลด PDF">
                            <i class="fa-solid fa-file-pdf"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status Timeline & Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 bg-white p-5 rounded border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ความคืบหน้าปัจจุบัน</h3>
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center">
                            <!-- Step 1: Submit -->
                            <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-sm relative">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>

                            <!-- Connector 1-2 -->
                            <div class="w-0.5 h-10 {{ $requisition->approve_status == 1 ? 'bg-emerald-500' : 'bg-slate-200' }} relative">
                            </div>

                            <!-- Step 2: Approval -->
                            @if($requisition->approve_status == 0)
                                <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center border border-orange-200 animate-pulse">
                                    <i class="fa-solid fa-hourglass-half text-xs"></i>
                                </div>
                            @elseif($requisition->approve_status == 1)
                                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-rose-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </div>
                            @endif

                            <!-- Connector 2-3 -->
                            <div class="w-0.5 h-10 {{ $requisition->packing_staff_status == 1 ? 'bg-emerald-500' : 'bg-slate-200' }} relative">
                            </div>

                            <!-- Step 3: Packing -->
                            @if($requisition->packing_staff_status == 1)
                                <div class="w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-sm">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @elseif($requisition->approve_status == 1)
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center border border-blue-200 animate-pulse">
                                    <i class="fa-solid fa-boxes-packing text-xs"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-slate-100 text-slate-350 rounded-full flex items-center justify-center border border-slate-200">
                                    <i class="fa-solid fa-box-open text-xs"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between h-[150px] py-0.5 leading-normal">
                            <!-- Label 1 -->
                            <div>
                                <p class="font-bold text-slate-800 text-xs">ส่งคำขอเบิกแล้ว</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">
                                    {{ optional($requisition->created_at)->locale('th')->isoFormat('D MMM YYYY | HH:mm') }} น.
                                </p>
                            </div>

                            <!-- Label 2 -->
                            <div>
                                <p class="font-bold {{ $requisition->approve_status == 1 ? 'text-slate-800' : 'text-slate-400' }} text-xs">พิจารณาอนุมัติรายการ</p>
                                <p class="text-[9px] font-bold mt-0.5 uppercase {{ $requisition->approve_status == 1 ? 'text-emerald-600' : 'text-orange-500' }}">
                                    {{ $requisition->approve_status == 1 ? 'Approved' : ($requisition->approve_status == 2 ? 'Rejected' : 'In Queue for Approval') }}
                                </p>
                                @if($requisition->approve_user)
                                    <p class="text-[9px] font-bold text-emerald-600 uppercase tracking-tighter mt-0.5">
                                        ผู้อนุมัติ: คุณ{{ $requisition->approve_user->fullname }}
                                    </p>
                                @endif
                            </div>

                            <!-- Label 3 -->
                            <div>
                                <p class="font-bold {{ $requisition->packing_staff_status == 1 ? 'text-slate-800' : 'text-slate-400' }} text-xs">รอดำเนินการจัดอุปกรณ์</p>
                                <p class="text-[9px] font-bold mt-0.5 uppercase {{ $requisition->packing_staff_status == 1 ? 'text-emerald-600' : 'text-blue-500' }}">
                                    {{ $requisition->packing_staff_status == 1 ? 'Packed & Ready' : 'Pending Packing' }}
                                </p>
                                @if($requisition->packing_staff_id && $requisition->packing_staff)
                                    <p class="text-[9px] font-bold text-blue-600 uppercase tracking-tighter mt-0.5">
                                        เจ้าหน้าที่: คุณ{{ $requisition->packing_staff->fullname }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-800 p-5 rounded border border-slate-700 text-white flex flex-col justify-between shadow-sm">
                    <div>
                        <i class="fa-solid fa-quote-left text-2xl text-slate-600 mb-3 opacity-30"></i>
                        <p class="text-[11px] font-semibold text-slate-300 leading-relaxed">"ใบเบิกของคุณได้รับการจัดลำดับแล้ว กรุณารอการติดต่อกลับ หรือสถานะการอัปเดตจากเจ้าหน้าที่ HAMS"</p>
                    </div>
                    <div class="pt-4 border-t border-slate-700 mt-4 leading-normal">
                        <p class="text-[9px] font-bold text-slate-500 uppercase">ยอดรวมประเมิน</p>
                        <p class="text-2xl font-black text-orange-400 font-mono">฿{{ number_format($requisition->total_price, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Items List (Registry Bordered Table) -->
            <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                        <h2 class="font-bold text-slate-700">รายการพัสดุที่ขอเบิก</h2>
                    </div>
                    <span class="px-2.5 py-0.5 bg-slate-100 text-[10px] font-bold text-slate-500 rounded border border-slate-250">{{ $requisition->requisition_items->count() }} รายการ</span>
                </div>
                <div class="p-4 overflow-x-auto">
                    <table class="w-full text-left border-collapse border border-slate-200">
                        <thead>
                            <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                                <th class="py-2.5 px-3 border-r border-slate-200 text-center w-16">#</th>
                                <th class="py-2.5 px-3 border-r border-slate-200">ชื่อรายการพัสดุ / SKU</th>
                                <th class="py-2.5 px-3 border-r border-slate-200 text-center w-28">จำนวนเบิก</th>
                                <th class="py-2.5 px-3 border-r border-slate-200 text-right w-32">ราคาต่อหน่วย</th>
                                <th class="py-2.5 px-3 text-right w-32">รวมเป็นเงิน</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach ($requisition->requisition_items as $index => $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-2.5 px-3 border-r border-slate-200 text-center font-bold text-slate-400">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="py-2.5 px-3 border-r border-slate-200 leading-normal">
                                        <div class="font-bold text-slate-800">{{ $item->item->name ?? '-' }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase">CODE: {{ $item->item->item_code ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 border-r border-slate-200 text-center font-bold">
                                        <span class="inline-block px-2.5 py-1 bg-slate-850 text-slate-700 rounded border border-slate-200 text-xs">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 border-r border-slate-200 text-right font-bold text-slate-450 font-mono">
                                        ฿{{ number_format($item->item->per_unit ?? 0, 2) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-bold text-slate-800 font-mono">
                                        ฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Footer Action -->
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4">
                    @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                        <div class="flex items-center gap-2 text-slate-400">
                            <i class="fa-solid fa-circle-info text-base opacity-75"></i>
                            <p class="text-[11px] font-semibold leading-relaxed">
                                คุณยังสามารถยกเลิกใบเบิกนี้ได้ก่อนที่เจ้าหน้าที่จะทำการเริ่มขั้นตอน <span class="text-orange-600 font-bold">"กำลังดำเนินการ"</span>
                            </p>
                        </div>
                        <button
                            class="w-full md:w-auto px-5 py-2.5 bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white font-bold rounded shadow-sm transition-all active:scale-95 flex items-center justify-center gap-1.5 btn-cancel-req"
                            data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}">
                            <i class="fa-solid fa-xmark"></i>
                            <span>ยกเลิกใบเบิกฉบับนี้</span>
                        </button>
                    @else
                        <div class="flex items-center gap-2 text-emerald-700 bg-emerald-50 px-4 py-3 rounded border border-emerald-200 w-full">
                            <i class="fa-solid fa-circle-check text-base"></i>
                            <p class="text-[11px] font-bold leading-relaxed uppercase tracking-wider">ใบเบิกนี้ได้รับการยืนยันแล้ว และไม่สามารถยกเลิกรายการได้ในขณะนี้</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const cancelBtn = document.querySelector('.btn-cancel-req');
                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.dataset.href;
                        Swal.fire({
                            title: '<span class="text-slate-800 font-bold">ยืนยันการยกเลิก?</span>',
                            html: '<p class="text-sm text-slate-500 font-medium leading-relaxed">ใบเบิกของคุณจะถูกระงับทันที และไม่สามารถเรียกกลับมาได้</p>',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'ยืนยัน ยกเลิกใบเบิก',
                            cancelButtonText: 'ปิดตัวช่วย',
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#1e293b',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-[1rem] p-6',
                                confirmButton: 'rounded px-6 py-2.5 font-bold text-xs',
                                cancelButton: 'rounded px-6 py-2.5 font-bold text-xs'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    });
                }
            });
        </script>
    @else
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4 border border-red-100">
                <i class="fa-solid fa-lock text-2xl"></i>
            </div>
            <h1 class="text-lg font-bold text-slate-800 mb-1">เข้าถึงไม่ได้ / ACCESS DENIED</h1>
            <p class="text-slate-400 mb-6 max-w-md text-xs">คุณไม่มีสิทธิ์ในการเข้าชมใบเบิกพัสดุฉบับนี้ หรือใบเบิกนี้ไม่ได้เป็นของคุณ</p>
            <a href="{{ route('welcome') }}"
                class="px-5 py-2.5 bg-slate-900 text-white font-bold rounded shadow transition-all active:scale-95 text-xs">
                กลับหน้าหลัก
            </a>
        </div>
    @endif

@endsection