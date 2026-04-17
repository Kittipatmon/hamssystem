@extends('layouts.serviceitem.appservice')
@section('content')

    @php
        $isOwner = Auth::check() && $requisition->requester_id === Auth::id();
        $isHamsOrAdmin = Auth::check() && (
            Auth::user()->role === 'admin' ||
            in_array(Auth::user()->dept_id, [14, 16])
        );
    @endphp

    @if($isOwner || $isHamsOrAdmin)
        <div class="max-w-5xl mx-auto px-4 py-8 lg:py-18 space-y-8">

            <!-- Header Section -->
            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-orange-50 animate-zoom-in">
                <div class="flex items-center gap-5">
                    <div
                        class="w-16 h-16 bg-orange-500 rounded-3xl flex items-center justify-center shadow-lg shadow-orange-100">
                        <i class="fa-solid fa-clock-rotate-left text-white text-2xl animate-spin-pulse"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 leading-none">ใบเบิกพัสดุ
                            ({{ $requisition->status_label }})</h1>
                        <p class="text-sm text-slate-400 font-semibold mt-1.5 flex items-center gap-2">
                            <span
                                class="px-2 py-0.5 bg-{{ $requisition->status_color }}-50 text-{{ $requisition->status_color }}-600 rounded border border-{{ $requisition->status_color }}-100">{{ $requisition->requisitions_code }}</span>
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
                <div class="flex items-center gap-3">
                    <a href="{{ route('requisitions.reqlistpending') }}"
                        class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-500 font-bold rounded-2xl border border-slate-100 transition-all active:scale-95 text-sm shadow-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i> กลับหน้ารอเบิก
                    </a>
                    @if($requisition->status !== \App\Models\serviceshams\Requisitions::STATUS_CANCELLED)
                        <a href="{{ route('requisitions.detail.pdf', $requisition->requisitions_id) }}"
                            class="w-12 h-12 flex items-center justify-center bg-white border-2 border-slate-100 text-red-500 rounded-2xl hover:bg-red-50 transition-all shadow-sm"
                            title="ดาวน์โหลด PDF">
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status Timeline & Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-xs font-bold text-slate-300 uppercase mb-6">ความคืบหน้าปัจจุบัน</h3>
                    <div class="flex items-start gap-4">
                        <div class="flex flex-col items-center">
                            <!-- Step 1: Submit -->
                            <div
                                class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-100 relative">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>

                            <!-- Connector 1-2 -->
                            <div
                                class="w-0.5 h-12 {{ $requisition->approve_status == 1 ? 'bg-emerald-500' : 'bg-slate-100' }} relative overflow-hidden">
                                @if($requisition->approve_status == 1)
                                    <div class="absolute inset-0 animate-flow-line"></div>
                                @endif
                            </div>

                            <!-- Step 2: Approval -->
                            @if($requisition->approve_status == 0)
                                <div
                                    class="w-10 h-10 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center animate-pulse border-2 border-orange-200">
                                    <i class="fa-solid fa-hourglass-half text-xs"></i>
                                </div>
                            @elseif($requisition->approve_status == 1)
                                <div
                                    class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-100">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-rose-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </div>
                            @endif

                            <!-- Connector 2-3 -->
                            <div
                                class="w-0.5 h-12 {{ $requisition->packing_staff_status == 1 ? 'bg-emerald-500' : 'bg-slate-100' }} relative overflow-hidden">
                                @if($requisition->packing_staff_status == 1)
                                    <div class="absolute inset-0 animate-flow-line"></div>
                                @endif
                            </div>

                            <!-- Step 3: Packing -->
                            @if($requisition->packing_staff_status == 1)
                                <div
                                    class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-100">
                                    <i class="fa-solid fa-check text-xs"></i>
                                </div>
                            @elseif($requisition->approve_status == 1)
                                <div
                                    class="w-10 h-10 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center animate-pulse border-2 border-blue-200">
                                    <i class="fa-solid fa-boxes-packing text-xs"></i>
                                </div>
                            @else
                                <div class="w-10 h-10 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-box-open text-xs"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-col justify-between h-[180px] py-1">
                            <!-- Label 1 -->
                            <div class="h-10 flex flex-col justify-center">
                                <p class="text-[13px] font-black text-slate-700 leading-none">ส่งคำขอเบิกแล้ว</p>
                                <p class="text-[10px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">
                                    {{ optional($requisition->created_at)->locale('th')->isoFormat('D MMM YYYY | HH:mm') }} น.
                                </p>
                            </div>

                            <!-- Label 2 -->
                            <div class="h-10 flex flex-col justify-center">
                                <p
                                    class="text-[13px] font-black {{ $requisition->approve_status == 1 ? 'text-slate-700' : 'text-slate-400' }} leading-none">
                                    พิจารณาอนุมัติรายการ
                                </p>
                                <p
                                    class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ $requisition->approve_status == 1 ? 'text-emerald-500' : 'text-orange-400' }}">
                                    {{ $requisition->approve_status == 1 ? 'Approved' : ($requisition->approve_status == 2 ? 'Rejected' : 'In Queue for Approval') }}
                                </p>
                                @if($requisition->approve_user)
                                    <p class="text-[9px] font-bold text-emerald-600 mt-1 uppercase tracking-tighter">
                                        ผู้อนุมัติ: คุณ{{ $requisition->approve_user->fullname }}
                                    </p>
                                @endif
                            </div>

                            <!-- Label 3 -->
                            <div class="h-10 flex flex-col justify-center">
                                <p
                                    class="text-[13px] font-black {{ $requisition->packing_staff_status == 1 ? 'text-slate-700' : 'text-slate-400' }} leading-none">
                                    รอดำเนินการจัดอุปกรณ์
                                </p>
                                <p
                                    class="text-[10px] font-bold mt-1 uppercase tracking-tighter {{ $requisition->packing_staff_status == 1 ? 'text-emerald-500' : 'text-blue-400' }}">
                                    {{ $requisition->packing_staff_status == 1 ? 'Packed & Ready' : 'Pending Packing' }}
                                </p>
                                @if($requisition->packing_staff_id && $requisition->packing_staff)
                                    <p class="text-[9px] font-bold text-blue-600 mt-1 uppercase tracking-tighter">
                                        เจ้าหน้าที่: คุณ{{ $requisition->packing_staff->fullname }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-slate-800 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200 text-white flex flex-col justify-between">
                    <div>
                        <i class="fa-solid fa-quote-left text-3xl text-slate-600 mb-4 opacity-30"></i>
                        <p class="text-[13px] font-bold text-slate-300  leading-relaxed">"ใบเบิกของคุณได้รับการจัดลำดับแล้ว
                            กรุณารอการติดต่อกลับ หรือสถานะการอัปเดตจากเจ้าหน้าที่ HAMS"</p>
                    </div>
                    <div class="pt-6 border-t border-slate-700 mt-6">
                        <p class="text-[10px] font-bold text-slate-500 uppercase mb-1">ยอดรวมประเมิน</p>
                        <p class="text-3xl font-bold text-orange-400">฿{{ number_format($requisition->total_price, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
                        <h2 class="text-lg font-bold text-slate-800 uppercase">รายการพัสดุที่รอเบิก</h2>
                    </div>
                    <span
                        class="px-4 py-1.5 bg-slate-50 rounded-full text-xs font-bold text-slate-400 uppercase border border-slate-100">{{ $requisition->requisition_items->count() }}
                        Items</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        @foreach ($requisition->requisition_items as $index => $item)
                            <div
                                class="flex items-center justify-between p-5 bg-slate-50/50 rounded-3xl border border-slate-100 hover:border-orange-200 transition-colors group">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-400 font-bold border border-slate-100 shadow-sm group-hover:text-orange-400 transition-colors">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-slate-700">{{ $item->item->name ?? '-' }}</p>
                                        <p class="text-xs font-semibold text-slate-400 uppercase">Unit Price:
                                            ฿{{ number_format($item->item->per_unit ?? 0, 2) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8 text-right">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-300 uppercase">Qty</span>
                                        <span class="text-base font-bold text-slate-700">{{ $item->quantity }} <span
                                                class="text-[10px] text-slate-400 font-normal">ชิ้น</span></span>
                                    </div>
                                    <div class="flex flex-col w-24">
                                        <span class="text-xs font-bold text-slate-300 uppercase">Total</span>
                                        <span
                                            class="text-base font-bold text-orange-600">฿{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer Action -->
                <div
                    class="p-8 bg-slate-50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                    @if($requisition->status == \App\Models\serviceshams\Requisitions::STATUS_PENDING)
                        <div class="flex items-center gap-4 text-slate-400">
                            <i class="fa-solid fa-circle-info text-xl opacity-30"></i>
                            <p class="text-xs font-medium leading-relaxed">
                                คุณยังสามารถยกเลิกใบเบิกนี้ได้ก่อนที่เจ้าหน้าที่จะทำการเริ่มขั้นตอน <span
                                    class="text-orange-500 font-bold">"กำลังดำเนินการ"</span></p>
                        </div>
                        <button
                            class="w-full md:w-auto px-10 py-4 bg-white border-2 border-red-50 text-red-500 hover:bg-red-600 hover:text-white font-bold rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-3 btn-cancel-req"
                            data-href="{{ route('requisitions.cancel', $requisition->requisitions_id) }}">
                            <i class="fa-solid fa-xmark"></i>
                            ยกเลิกใบเบิกฉบับนี้
                        </button>
                    @else
                        <div
                            class="flex items-center gap-4 text-emerald-600 bg-emerald-50 px-6 py-4 rounded-2xl border border-emerald-100 w-full">
                            <i class="fa-solid fa-circle-check text-xl"></i>
                            <p class="text-xs font-bold leading-relaxed uppercase tracking-wider">ใบเบิกนี้ได้รับการยืนยันแล้ว
                                และไม่สามารถยกเลิกรายการได้ในขณะนี้</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelector('.btn-cancel-req').addEventListener('click', function (e) {
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
                            popup: 'rounded-[2.5rem] p-8',
                            confirmButton: 'rounded-xl px-8 py-3 font-bold text-sm',
                            cancelButton: 'rounded-xl px-8 py-3 font-bold text-sm'
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

            .animate-spin-pulse {
                animation: spin-pulse 4s infinite linear;
            }

            @keyframes spin-pulse {
                0% {
                    transform: rotate(0deg) scale(1);
                }

                50% {
                    transform: rotate(180deg) scale(1.1);
                }

                100% {
                    transform: rotate(360deg) scale(1);
                }
            }

            @keyframes flow-line {
                0% {
                    background-position: 0 -100px;
                }

                100% {
                    background-position: 0 100px;
                }
            }

            .animate-flow-line {
                background: linear-gradient(to bottom,
                        transparent 0%,
                        rgba(255, 255, 255, 0.6) 50%,
                        transparent 100%);
                background-size: 100% 100px;
                animation: flow-line 2s linear infinite;
            }
        </style>
        </div>
    @else
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-6">
                <i class="fa-solid fa-lock text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mb-2">เข้าถึงไม่ได้ / ACCESS DENIED</h1>
            <p class="text-slate-400 mb-8 max-w-md">คุณไม่มีสิทธิ์ในการเข้าชมใบเบิกพัสดุฉบับนี้ หรือใบเบิกนี้ไม่ได้เป็นของคุณ
            </p>
            <a href="{{ route('welcome') }}"
                class="px-8 py-3 bg-slate-900 text-white font-bold rounded-2xl shadow-lg transition-all active:scale-95">
                กลับหน้าหลัก
            </a>
        </div>
    @endif

@endsection