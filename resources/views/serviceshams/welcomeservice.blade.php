@extends('layouts.serviceitem.appservice')
@section('content')

@php
    $isHams = Auth::check() && (
        (Auth::user()->department && Auth::user()->department->department_name === 'HAMS') ||
        Auth::user()->employee_code === '11648'
    );
@endphp

<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

    {{-- ===== HAMS-Only Stats Panel ===== --}}
    @if($isHams)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 rounded-2xl overflow-hidden shadow-lg border border-red-900/30">

        {{-- Col 1: ตรวจสอบคำขอ --}}
        <div class="bg-red-800 text-white">
            <div class="px-5 py-3 bg-red-900/50 flex items-center gap-2 border-b border-red-700/50">
                <i class="fa-solid fa-users text-sm"></i>
                <span class="font-bold text-sm tracking-wide">ตรวจสอบคำขอ</span>
                @if($pendingApproveCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $pendingApproveCount }}</span>
                @endif
            </div>
            <div class="p-4 space-y-1">
                <a href="{{ route('requisitions.reqlistpending') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายการรออนุมัติ</span>
                    @if($pendingApproveCount > 0)
                        <span class="bg-red-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $pendingApproveCount }}</span>
                    @else
                        <span class="text-red-300 text-[11px] font-bold">{{ $pendingApproveCount }}</span>
                    @endif
                </a>
                <a href="{{ route('requisitions.reqlistpending') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายการอัปเดตแล้ว</span>
                    <span class="@if($updatedCount > 0) bg-amber-400 text-red-900 @else text-red-300 @endif text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $updatedCount }}</span>
                </a>
                <a href="{{ route('requisitions.reqlistall') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายการทั้งหมด</span>
                    <span class="text-red-200 text-[11px] font-bold">{{ $allReqCount }}</span>
                </a>
            </div>
        </div>

        {{-- Col 2: เช็คและจัดเตรียม --}}
        <div class="bg-red-800 text-white border-l border-r border-red-700/60">
            <div class="px-5 py-3 bg-red-900/50 flex items-center gap-2 border-b border-red-700/50">
                <i class="fa-solid fa-circle-check text-sm"></i>
                <span class="font-bold text-sm tracking-wide">เช็คและจัดเตรียมอุปกรณ์</span>
                @if($checklistPendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $checklistPendingCount }}</span>
                @else
                    <span class="ml-auto bg-white/20 text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $checklistPendingCount }}</span>
                @endif
            </div>
            <div class="p-4 space-y-1">
                <a href="{{ route('requisitions.reqchecklist') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายการรอจัดเตรียม</span>
                    @if($checklistPendingCount > 0)
                        <span class="bg-red-500 text-white text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $checklistPendingCount }}</span>
                    @else
                        <span class="text-red-300 text-[11px] font-bold">{{ $checklistPendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('requisitions.reqlistall') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายการทั้งหมด</span>
                    <span class="text-red-200 text-[11px] font-bold">{{ $packingDoneCount }}</span>
                </a>
            </div>
        </div>

        {{-- Col 3: รายงานข้อมูล --}}
        <div class="bg-red-800 text-white">
            <div class="px-5 py-3 bg-red-900/50 flex items-center gap-2 border-b border-red-700/50">
                <i class="fa-solid fa-users text-sm"></i>
                <span class="font-bold text-sm tracking-wide">รายงานข้อมูล</span>
            </div>
            <div class="p-4 space-y-1">
                <a href="{{ route('requisitions.dashboard') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายงานสถิติ</span>
                </a>
                <a href="{{ route('requisitions.reportslistall') }}"
                    class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-white/10 transition-colors group">
                    <span class="text-sm text-red-100 group-hover:text-white">รายงานข้อมูล</span>
                    <span class="@if($reportsAllCount > 0) bg-red-500 text-white @else text-red-300 @endif text-[11px] font-bold px-2 py-0.5 rounded-full">{{ $reportsAllCount }}</span>
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
            <span class="text-sm font-semibold text-slate-700 group-hover:text-red-600 text-center">รายการอุปกรณ์</span>
        </a>
        <a href="{{ route('cartitem.index') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-orange-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center group-hover:bg-orange-100 transition-colors">
                <i class="fa-solid fa-cart-shopping text-orange-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-orange-600 text-center">ตะกร้าของฉัน</span>
        </a>
        <a href="{{ route('requisitions.reqlistpending') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-blue-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                <i class="fa-solid fa-file-circle-plus text-blue-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-600 text-center">คำขอของฉัน</span>
        </a>
        <a href="{{ route('requisitions.reqlistall') }}"
            class="flex flex-col items-center gap-3 p-5 bg-white rounded-2xl shadow border border-slate-100 hover:border-slate-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center group-hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-rectangle-list text-slate-500 text-xl"></i>
            </div>
            <span class="text-sm font-semibold text-slate-700 text-center">ประวัติทั้งหมด</span>
        </a>
    </div>

</div>

@endsection