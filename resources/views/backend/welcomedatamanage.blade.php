@extends('layouts.sidebar')

@section('title', 'แจ้งเตือนระบบ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 uppercase tracking-tight">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-zinc-950 p-8 rounded-[2.5rem] border border-zinc-100 dark:border-zinc-800 shadow-sm animate-zoom-in">
        <div>
            <h1 class="text-3xl font-black text-zinc-900 dark:text-white flex items-center gap-4">
                แจ้งเตือนระบบ
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2 font-bold flex items-center gap-2 uppercase tracking-widest">
                <span>รายการที่รอดำเนินการและยังไม่ถูกอนุมัติของ</span>
                <span class="px-3 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-lg text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/50 decoration-amber-500 underline underline-offset-4 decoration-2">
                    {{ auth()->user()->userName ?? auth()->user()->name }}
                </span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="p-3 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-100 dark:border-zinc-800 shadow-inner">
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">รวมรายการค้างคา:</span>
                <span class="ml-2 text-xl font-black text-amber-600">{{ $requisitionCount + $reservationCount + $vehicleBookingCount + $repairCount }}</span>
            </div>
        </div>
    </div>

    <!-- Outstanding Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up">
        @php
            $statCards = [
                ['title' => 'เบิกพัสดุค้างคา', 'count' => $requisitionCount, 'color' => 'blue', 'icon' => 'fa-cart-shopping'],
                ['title' => 'จองห้องประชุมรอนุมัติ', 'count' => $reservationCount, 'color' => 'emerald', 'icon' => 'fa-door-open'],
                ['title' => 'จองรถส่วนกลางรอนุมัติ', 'count' => $vehicleBookingCount, 'color' => 'amber', 'icon' => 'fa-car'],
                ['title' => 'แจ้งซ่อมที่ยังไม่เสร็จ', 'count' => $repairCount, 'color' => 'purple', 'icon' => 'fa-wrench'],
            ];
        @endphp

        @foreach($statCards as $stat)
        <div class="bg-white dark:bg-zinc-950 rounded-[2rem] p-6 border-b-4 border-{{ $stat['color'] }}-500 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden group border border-zinc-100 dark:border-zinc-800">
            {{-- Removed redundant icon --}}
            <div class="flex justify-between items-center mb-4 relative z-10">
                <div class="hidden"></div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1">TOTAL PENDING</span>
                    <span class="text-3xl font-black text-zinc-900 dark:text-white font-mono tracking-tighter">{{ $stat['count'] }}</span>
                </div>
            </div>
            <p class="text-[12px] font-black text-zinc-700 dark:text-zinc-300 uppercase tracking-wide leading-none">{{ $stat['title'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Alert Tracking Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up delay-200">
        @php
            $catAlerts = [
                ['id' => 'requisitions', 'title' => 'รายการรอพัสดุ', 'route' => 'requisitions.reqlistall', 'theme' => 'blue', 'icon' => 'fa-box-archive', 'data' => $requisitions],
                ['id' => 'reservations', 'title' => 'ห้องประชุมรออนุมัติ', 'route' => 'backend.bookingmeeting.reservations.index', 'theme' => 'emerald', 'icon' => 'fa-calendar-clock', 'data' => $reservations],
                ['id' => 'vehicleBookings', 'title' => 'การจองรถรออนุมัติ', 'route' => 'bookingcar.welcome', 'theme' => 'amber', 'icon' => 'fa-car-clock', 'data' => $vehicleBookings],
                ['id' => 'repairs', 'title' => 'งานซ่อมค้างดำเนินการ', 'route' => '#', 'theme' => 'purple', 'icon' => 'fa-screwdriver-wrench', 'data' => $repairs]
            ];
        @endphp

        @foreach($catAlerts as $cat)
            <div class="bg-white dark:bg-zinc-950 rounded-[2.5rem] border border-zinc-100 dark:border-zinc-800 shadow-sm overflow-hidden flex flex-col min-h-[400px]">
                <div class="px-8 py-6 border-b border-zinc-50 dark:border-zinc-900 bg-zinc-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        {{-- Icon removed for clarity --}}
                        <div>
                            <h2 class="text-sm font-black text-zinc-800 dark:text-white uppercase tracking-widest leading-none">{{ $cat['title'] }}</h2>
                            @if($cat['data']->count() > 0)
                                <span class="text-[9px] font-bold text-amber-600 dark:text-amber-500 uppercase tracking-widest">ต้องการการตรวจสอบเพื่อเร่งอนุมัติ</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto max-h-[500px] p-6 space-y-4 custom-scrollbar">
                    @forelse($cat['data'] as $item)
                        @include('backend.welcomedatamanage_item_card', ['item' => $item, 'theme' => $cat['theme'], 'type' => $cat['id'], 'statusType' => 'pending'])
                    @empty
                        @include('backend.welcomedatamanage_empty_state', ['icon' => $cat['icon'], 'text' => 'ไม่มี'.$cat['title']])
                    @endforelse
                </div>
                
                @if($cat['data']->count() > 0)
                    <div class="p-4 bg-zinc-50/30 dark:bg-zinc-900/30 border-t border-zinc-50 dark:border-zinc-800 text-center">
                        <p class="text-[9px] font-bold text-zinc-400 uppercase tracking-[0.2em] leading-none mb-3">พบ {{ $cat['data']->count() }} รายการที่กำลังรอดำเนินการ</p>
                        <a href="{{ $cat['route'] !== '#' ? route($cat['route']) : '#' }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-{{ $cat['theme'] }}-600 hover:bg-{{ $cat['theme'] }}-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-{{ $cat['theme'] }}-500/10 active:scale-95">
                            จัดการรายการ
                            <i class="fa-solid fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    @keyframes fade-in-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes bounce-subtle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-1px); } }
    .animate-zoom-in { animation: zoom-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in-up { animation: fade-in-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-bounce-subtle { animation: bounce-subtle 2s ease-in-out infinite; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; }
</style>
@endsection