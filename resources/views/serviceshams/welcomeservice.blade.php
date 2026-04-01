@extends('layouts.serviceitem.appservice')
@section('content')

@php
    $isHams = Auth::check() && (
        (Auth::user()->department && Auth::user()->department->department_name === 'HAMS') ||
        Auth::user()->employee_code === '11648'
    );
@endphp

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- ===== HAMS-Only Premium Dashboard Menu ===== --}}
    @if($isHams)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-zoom-in">

        {{-- Section 1: ตรวจสอบคำขอ (Red Theme) --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl @if($pendingApproveCount > 0) ring-4 ring-red-500/20 animate-card-pulse @else shadow-red-900/5 ring-1 ring-slate-100 @endif overflow-hidden group hover:-translate-y-1 transition-all duration-300 relative">
            
            @if($pendingApproveCount > 0)
                <div class="absolute top-4 right-4 flex items-center gap-1.5 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20 z-10 shadow-lg">
                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full animate-ping"></span>
                    <span class="text-[9px] font-extrabold text-white uppercase tracking-tighter">Action Required</span>
                </div>
            @endif

            <div class="bg-red-600 px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-md">
                        <i class="fa-solid fa-users-viewfinder text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-red-100 uppercase tracking-widest leading-none">Management</span>
                        <h3 class="text-base font-black text-white leading-none mt-1 uppercase">ตรวจสอบคำขอ</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-2">
                <a href="{{ route('requisitions.reqlistpending') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] @if($pendingApproveCount > 0) bg-red-50 border-red-100 @else bg-slate-50 border-transparent @endif hover:bg-red-50 hover:text-red-600 transition-all border group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clock-rotate-left @if($pendingApproveCount > 0) text-red-500 @else text-slate-300 @endif group-hover/item:text-red-400 text-xs"></i>
                        <span class="text-xs font-black @if($pendingApproveCount > 0) text-red-700 @else text-slate-600 @endif group-hover/item:text-red-700 uppercase">รายการรออนุมัติ</span>
                    </div>
                    <span class="px-3 py-1.5 @if($pendingApproveCount > 0) bg-red-600 text-white shadow-lg shadow-red-500/50 scale-110 animate-pulse @else bg-slate-200 text-slate-400 @endif text-[11px] font-black rounded-full transition-transform">{{ $pendingApproveCount }}</span>
                </a>
                <a href="{{ route('requisitions.reqlistpending') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-amber-50 hover:text-amber-600 transition-all border border-transparent hover:border-amber-100 group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-rotate text-slate-300 group-hover/item:text-amber-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover/item:text-amber-700 uppercase">รายการอัปเดตแล้ว</span>
                    </div>
                    <span class="px-3 py-1 @if($updatedCount > 0) bg-amber-500 text-white @else bg-slate-100 text-slate-300 @endif text-[10px] font-black rounded-full">{{ $updatedCount }}</span>
                </a>
                <a href="{{ route('requisitions.reqlistall') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-list-ul text-slate-300 group-hover/item:text-slate-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-500 group-hover/item:text-slate-800 uppercase">รายการทั้งหมด</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-400 group-hover/item:text-slate-700">{{ $allReqCount }}</span>
                </a>
            </div>
        </div>

        {{-- Section 2: จัดเตรียมอุปกรณ์ (Indigo/Blue Theme) --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl @if($checklistPendingCount > 0) ring-4 ring-indigo-500/20 @else shadow-indigo-900/5 ring-1 ring-slate-100 @endif overflow-hidden group hover:-translate-y-1 transition-all duration-300 text-left relative">
            
            @if($checklistPendingCount > 0)
                <div class="absolute top-4 right-4 flex items-center gap-1.5 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full border border-white/20 z-10 shadow-lg">
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-ping"></span>
                    <span class="text-[9px] font-extrabold text-white uppercase tracking-tighter">Logistics Alert</span>
                </div>
            @endif

            <div class="bg-slate-800 px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-white backdrop-blur-md">
                        <i class="fa-solid fa-boxes-packing text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Logistics</span>
                        <h3 class="text-base font-black text-white leading-none mt-1 uppercase">การเตรียมอุปกรณ์</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-2">
                <a href="{{ route('requisitions.reqchecklist') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] @if($checklistPendingCount > 0) bg-indigo-50 border-indigo-100 @else bg-slate-50 border-transparent @endif hover:bg-indigo-50 hover:text-indigo-600 transition-all border group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clipboard-list @if($checklistPendingCount > 0) text-indigo-500 @else text-slate-300 @endif group-hover/item:text-indigo-400 text-xs"></i>
                        <span class="text-xs font-black @if($checklistPendingCount > 0) text-indigo-700 @else text-slate-600 @endif group-hover/item:text-indigo-700 uppercase">รายการรอจัดเตรียม</span>
                    </div>
                    <span class="px-3 py-1.5 @if($checklistPendingCount > 0) bg-indigo-600 text-white shadow-lg shadow-indigo-500/50 scale-110 animate-bounce @else bg-slate-200 text-slate-400 @endif text-[11px] font-black rounded-full">{{ $checklistPendingCount }}</span>
                </a>
                <a href="{{ route('requisitions.reqlistall') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-truck-ramp-box text-slate-300 group-hover/item:text-slate-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-500 group-hover/item:text-slate-800 uppercase">จัดการพัสดุเรียบร้อย</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-400 group-hover/item:text-slate-700">{{ $packingDoneCount }}</span>
                </a>
            </div>
        </div>

        {{-- Section 3: รายงาน (Emerald/Slate Theme) --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100 overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="bg-emerald-600 px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-md">
                        <i class="fa-solid fa-chart-pie text-sm"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-black text-emerald-100 uppercase tracking-widest leading-none">Analysis</span>
                        <h3 class="text-base font-black text-white leading-none mt-1 uppercase">รายงานข้อมูล</h3>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-2">
                <a href="{{ route('requisitions.dashboard') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100 group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gauge-high text-slate-300 group-hover/item:text-emerald-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover/item:text-emerald-700 uppercase">Dashboard & สถิติ</span>
                    </div>
                </a>
                <a href="{{ route('requisitions.reportslistall') }}"
                    class="flex items-center justify-between p-4 rounded-[1.5rem] hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100 group/item">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-file-invoice text-slate-300 group-hover/item:text-slate-400 text-xs"></i>
                        <span class="text-xs font-bold text-slate-600 group-hover/item:text-slate-800 uppercase">รายงานการเบิก</span>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black rounded-full">{{ $reportsAllCount }}</span>
                </a>
            </div>
        </div>

    </div>
    @endif

    {{-- ===== Quick Actions (All users) ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('items.itemsalllist') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-red-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-100 transition-colors">
                <i class="fa-solid fa-boxes-stacked text-red-600 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-red-600 text-center uppercase tracking-tight">รายการอุปกรณ์</span>
        </a>
        <a href="{{ route('cartitem.index') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-orange-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                <i class="fa-solid fa-cart-shopping text-orange-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-orange-600 text-center uppercase tracking-tight">ตะกร้าของฉัน</span>
        </a>
        <a href="{{ route('requisitions.reqlistpending') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                <i class="fa-solid fa-file-circle-plus text-blue-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-600 text-center uppercase tracking-tight">คำขอของฉัน</span>
        </a>
        <a href="{{ route('requisitions.reqlistall') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-slate-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-rectangle-list text-slate-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 text-center uppercase tracking-tight">ประวัติทั้งหมด</span>
        </a>
    </div>

</div>

@push('styles')
<style>
    @keyframes card-pulse {
        0%, 100% { transform: scale(1); box-shadow: 0 20px 50px -10px rgba(0,0,0,0.05); }
        50% { transform: scale(1.015); box-shadow: 0 30px 80px -10px rgba(220, 38, 38, 0.2); }
    }
    .animate-card-pulse {
        animation: card-pulse 3s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }
    
    @keyframes zoom-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-zoom-in {
        animation: zoom-in 0.4s ease-out forwards;
    }
</style>
@endpush

@endsection