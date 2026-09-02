@extends('layouts.serviceitem.appservice')
@section('content')

    @php
        $isHams = Auth::check() && (
            in_array(Auth::user()->role, ['admin', 'editor']) ||
            in_array(Auth::user()->dept_id, [14, 16])
        );
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">

        {{-- ===== HAMS-Only Premium Dashboard Menu ===== --}}
        @if($isHams)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-zoom-in">

                {{-- Section 1: ตรวจสอบคำขอ (Red Theme) --}}
                <div class="bg-white rounded-lg border @if($pendingApproveCount > 0) border-red-500 shadow-[0_0_12px_rgba(220,38,38,0.15)] @else border-slate-200 @endif overflow-hidden relative">

                    @if($pendingApproveCount > 0)
                        <div class="absolute top-3 right-3 flex items-center gap-1 px-2.5 py-0.5 bg-red-600 text-white rounded text-[9px] font-black uppercase tracking-wider animate-pulse">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            <span>รอดำเนินการ</span>
                        </div>
                    @endif

                    <div class="bg-gradient-to-r from-[#e53935] to-[#c62828] px-5 py-4 flex items-center gap-3 text-white">
                        <div class="w-9 h-9 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-users-viewfinder text-sm"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-red-100 uppercase tracking-widest block leading-none">Management</span>
                            <h3 class="text-sm font-black mt-1 leading-none uppercase">ตรวจสอบคำขอเบิกพัสดุ</h3>
                        </div>
                    </div>
                    
                    <div class="p-4 space-y-2 text-xs">
                        <a href="{{ route('requisitions.reqlistpending') }}"
                            class="flex items-center justify-between p-3 rounded @if($pendingApproveCount > 0) bg-red-50/50 border border-red-200 text-[#c31919] font-bold @else bg-slate-50 border border-slate-100 text-slate-700 @endif hover:bg-red-50/80 transition-all group">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-xs @if($pendingApproveCount > 0) text-[#c31919] @else text-slate-400 @endif"></i>
                                <span>รายการรออนุมัติ</span>
                            </div>
                            <span class="px-2.5 py-0.5 @if($pendingApproveCount > 0) bg-red-600 text-white font-bold @else bg-slate-200 text-slate-500 @endif rounded text-[10px]">{{ $pendingApproveCount }}</span>
                        </a>
                        
                        <a href="{{ route('requisitions.reqlistpending') }}"
                            class="flex items-center justify-between p-3 rounded bg-slate-50 border border-slate-100 text-slate-700 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200 transition-all font-semibold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-rotate text-xs text-slate-400"></i>
                                <span>รายการอัปเดตแล้ว</span>
                            </div>
                            <span class="px-2 py-0.5 @if($updatedCount > 0) bg-amber-500 text-white font-bold @else bg-slate-200 text-slate-500 @endif rounded text-[10px]">{{ $updatedCount }}</span>
                        </a>

                        <a href="{{ route('requisitions.reqlistall') }}"
                            class="flex items-center justify-between p-3 rounded bg-slate-50 border border-slate-100 text-slate-700 hover:bg-slate-100 transition-all font-semibold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-list-ul text-xs text-slate-400"></i>
                                <span>รายการทั้งหมดในระบบ</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">{{ $allReqCount }}</span>
                        </a>
                    </div>
                </div>

                {{-- Section 2: จัดเตรียมอุปกรณ์ (Logistics Theme) --}}
                <div class="bg-white rounded-lg border @if($checklistPendingCount > 0) border-indigo-500 shadow-[0_0_12px_rgba(79,70,229,0.12)] @else border-slate-200 @endif overflow-hidden relative">

                    @if($checklistPendingCount > 0)
                        <div class="absolute top-3 right-3 flex items-center gap-1 px-2.5 py-0.5 bg-indigo-600 text-white rounded text-[9px] font-black uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                            <span>รอแพ็คของ</span>
                        </div>
                    @endif

                    <div class="bg-slate-800 px-5 py-4 flex items-center gap-3 text-white">
                        <div class="w-9 h-9 bg-white/10 rounded flex items-center justify-center">
                            <i class="fa-solid fa-boxes-packing text-sm"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block leading-none">Logistics</span>
                            <h3 class="text-sm font-black mt-1 leading-none uppercase">การเตรียมจัดส่งอุปกรณ์</h3>
                        </div>
                    </div>

                    <div class="p-4 space-y-2 text-xs">
                        <a href="{{ route('requisitions.reqchecklist') }}"
                            class="flex items-center justify-between p-3 rounded @if($checklistPendingCount > 0) bg-indigo-50/50 border border-indigo-200 text-indigo-700 font-bold @else bg-slate-50 border border-slate-100 text-slate-700 @endif hover:bg-indigo-50/80 transition-all">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-clipboard-list text-xs @if($checklistPendingCount > 0) text-indigo-600 @else text-slate-400 @endif"></i>
                                <span>รายการรอจัดเตรียมพัสดุ</span>
                            </div>
                            <span class="px-2.5 py-0.5 @if($checklistPendingCount > 0) bg-indigo-600 text-white font-bold @else bg-slate-200 text-slate-500 @endif rounded text-[10px]">{{ $checklistPendingCount }}</span>
                        </a>

                        <a href="{{ route('requisitions.reqlistall') }}"
                            class="flex items-center justify-between p-3 rounded bg-slate-50 border border-slate-100 text-slate-700 hover:bg-slate-100 transition-all font-semibold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-truck-ramp-box text-xs text-slate-400"></i>
                                <span>จัดเตรียมพัสดุเรียบร้อย</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500">{{ $packingDoneCount }}</span>
                        </a>
                    </div>
                </div>

                {{-- Section 3: รายงาน (Emerald Theme) --}}
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="bg-emerald-600 px-5 py-4 flex items-center gap-3 text-white">
                        <div class="w-9 h-9 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-chart-pie text-sm"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-100 uppercase tracking-widest block leading-none">Analysis</span>
                            <h3 class="text-sm font-black mt-1 leading-none uppercase">สถิติและรายงานผล</h3>
                        </div>
                    </div>
                    
                    <div class="p-4 space-y-2 text-xs">
                        <a href="{{ route('requisitions.dashboard') }}"
                            class="flex items-center justify-between p-3 rounded bg-slate-50 border border-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-all font-bold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-gauge-high text-xs text-slate-400"></i>
                                <span>Dashboard ภาพรวม & สถิติ</span>
                            </div>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400"></i>
                        </a>

                        <a href="{{ route('requisitions.reportslistall') }}"
                            class="flex items-center justify-between p-3 rounded bg-slate-50 border border-slate-100 text-slate-700 hover:bg-slate-100 transition-all font-bold">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-invoice text-xs text-slate-400"></i>
                                <span>รายงานประวัติการเบิกพัสดุ</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-2 py-0.5 rounded">{{ $reportsAllCount }}</span>
                        </a>
                    </div>
                </div>

            </div>
        @endif

        {{-- ===== Quick Actions (All users) ===== --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('items.itemsalllist') }}"
                class="flex flex-col items-center gap-2.5 p-4 bg-white rounded border border-slate-200 hover:border-red-400 hover:shadow-sm transition-all text-xs font-bold text-slate-700 hover:text-[#c31919] group">
                <div class="w-10 h-10 bg-red-50 rounded flex items-center justify-center text-red-600 text-lg group-hover:bg-[#c31919] group-hover:text-white transition-colors">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <span>รายการอุปกรณ์ทั้งหมด</span>
            </a>
            
            <a href="{{ route('cartitem.index') }}"
                class="flex flex-col items-center gap-2.5 p-4 bg-white rounded border border-slate-200 hover:border-orange-400 hover:shadow-sm transition-all text-xs font-bold text-slate-700 hover:text-orange-600 group">
                <div class="w-10 h-10 bg-orange-50 rounded flex items-center justify-center text-orange-500 text-lg group-hover:bg-orange-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <span>ตะกร้าพัสดุของฉัน</span>
            </a>

            <a href="{{ route('requisitions.reqlistpending') }}"
                class="flex flex-col items-center gap-2.5 p-4 bg-white rounded border border-slate-200 hover:border-blue-400 hover:shadow-sm transition-all text-xs font-bold text-slate-700 hover:text-blue-600 group">
                <div class="w-10 h-10 bg-blue-50 rounded flex items-center justify-center text-blue-500 text-lg group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-file-circle-plus"></i>
                </div>
                <span>คำขอเบิกพัสดุของฉัน</span>
            </a>

            <a href="{{ route('requisitions.reqlistall') }}"
                class="flex flex-col items-center gap-2.5 p-4 bg-white rounded border border-slate-200 hover:border-slate-400 hover:shadow-sm transition-all text-xs font-bold text-slate-700 hover:text-slate-950 group">
                <div class="w-10 h-10 bg-slate-50 rounded flex items-center justify-center text-slate-500 text-lg group-hover:bg-slate-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-rectangle-list"></i>
                </div>
                <span>ประวัติใบเบิกพัสดุทั้งหมด</span>
            </a>
        </div>

    </div>

    @push('styles')
        <style>
            @keyframes zoom-in {
                from {
                    opacity: 0;
                    transform: scale(0.98);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-zoom-in {
                animation: zoom-in 0.3s ease-out forwards;
            }
        </style>
    @endpush

@endsection