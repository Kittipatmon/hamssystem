@extends('layouts.sidebar')

@section('title', 'ระบบตรวจสอบการแจ้งเตือน')

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-zinc-950 px-4 sm:px-6 lg:px-10 py-10">
    <div class="max-w-[1600px] mx-auto space-y-10 font-noto">
        
        <!-- Premium Header Banner -->
        <div class="relative overflow-hidden bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-200 dark:border-zinc-800 shadow-xl shadow-slate-200/50 dark:shadow-none p-10 group animate-zoom-in">
            <!-- Decorative background elements -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl group-hover:bg-amber-500/20 transition-colors duration-700"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-colors duration-700"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <span class="text-[10px] font-black text-amber-700 dark:text-amber-400 uppercase tracking-[0.2em]">LIVE SYSTEM ALERTS</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
                        แจ้งเตือน<span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-600 font-black">ระบบ</span>
                    </h1>
                    <p class="text-slate-500 dark:text-zinc-400 font-bold uppercase tracking-widest text-[11px] max-w-xl leading-relaxed">
                        ศูนย์ควบคุมการแจ้งเตือนพัสดุ ห้องประชุม และรายการจองรถส่วนกลางที่ <span class="bg-amber-100 dark:bg-amber-900/40 text-amber-900 dark:text-amber-300 px-2 rounded">รอดำเนินการ</span> ของคุณ
                    </p>
                </div>

                <div class="flex flex-col items-end gap-3">
                    <div class="px-8 py-5 bg-slate-900 dark:bg-zinc-800 rounded-3xl shadow-2xl shadow-slate-900/20 relative overflow-hidden group/tile">
                        <div class="absolute top-0 right-0 p-2 opacity-10 group-hover/tile:rotate-12 transition-transform">
                            <i class="fa-solid fa-bell text-5xl text-white"></i>
                        </div>
                        <span class="relative z-10 block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">PENDING TOTAL</span>
                        <div class="relative z-10 flex items-baseline gap-2">
                            <span class="text-5xl font-black text-white tracking-tighter">{{ $requisitionCount + $reservationCount + $vehicleBookingCount + $housingTasksCount }}</span>
                            <span class="text-slate-400 text-xs font-bold uppercase">รายการ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- High-Impact Status Tiles -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in-up">
            @php
                $statCards = [
                    ['title' => 'คำขอเบิกพัสดุ', 'count' => $requisitionCount, 'color' => 'blue', 'icon' => 'fa-shopping-cart'],
                    ['title' => 'ห้องประชุมรอนุมัติ', 'count' => $reservationCount, 'color' => 'emerald', 'icon' => 'fa-door-open'],
                    ['title' => 'จองรถรอนุมัติ', 'count' => $vehicleBookingCount, 'color' => 'amber', 'icon' => 'fa-car-side'],
                    ['title' => 'งานบ้านพักรอดำเนินการ', 'count' => $housingTasksCount, 'color' => 'purple', 'icon' => 'fa-home-user'],
                ];
            @endphp

            @foreach($statCards as $stat)
            <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] p-8 border border-slate-200 dark:border-zinc-800 shadow-sm hover:shadow-xl hover:border-{{ $stat['color'] }}-400 transition-all duration-300 group relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity duration-500">
                    <i class="fa-solid {{ $stat['icon'] }} text-8xl -rotate-12"></i>
                </div>
                
                <div class="flex flex-col h-full relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 dark:bg-{{ $stat['color'] }}-900/20 dark:text-{{ $stat['color'] }}-400 border border-{{ $stat['color'] }}-100 dark:border-{{ $stat['color'] }}-800">
                            <i class="fa-solid {{ $stat['icon'] }} text-lg"></i>
                        </div>
                        <div class="text-right">
                            <span class="block text-[32px] font-black text-slate-900 dark:text-white tracking-tighter leading-none">{{ $stat['count'] }}</span>
                        </div>
                    </div>
                    <div class="mt-auto">
                        <h3 class="text-[14px] font-black text-slate-800 dark:text-zinc-200 uppercase tracking-widest leading-none">{{ $stat['title'] }}</h3>
                        <div class="w-10 h-1 bg-{{ $stat['color'] }}-500 mt-3 rounded-full group-hover:w-full transition-all duration-500"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Task Monitoring Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            @php
                $catAlerts = [
                    ['id' => 'requisitions', 'title' => 'PENDING REQUISITIONS', 'th_title' => 'รายการรอเบิกพัสดุ', 'route' => 'requisitions.reqlistall', 'theme' => 'blue', 'icon' => 'fa-box', 'data' => $requisitions],
                    ['id' => 'reservations', 'title' => 'MEETING APPROVAL', 'th_title' => 'ห้องประชุมรออนุมัติ', 'route' => 'backend.bookingmeeting.reservations.index', 'theme' => 'emerald', 'icon' => 'fa-calendar-check', 'data' => $reservations],
                    ['id' => 'vehicleBookings', 'title' => 'VEHICLE APPROVAL', 'th_title' => 'การจองรถรออนุมัติ', 'route' => 'bookingcar.dashboard', 'theme' => 'amber', 'icon' => 'fa-car-burst', 'data' => $vehicleBookings],
                    ['id' => 'housingTasks', 'title' => 'HOUSING MONITORING', 'th_title' => 'รายการเกี่ยวกับงานบ้านพัก', 'route' => 'housing.management', 'theme' => 'purple', 'icon' => 'fa-home-user', 'data' => $housingTasks]
                ];
            @endphp

            @foreach($catAlerts as $cat)
            <div class="bg-white dark:bg-zinc-900 rounded-[2.5rem] border border-slate-200 dark:border-zinc-800 shadow-xl shadow-slate-200/40 dark:shadow-none overflow-hidden flex flex-col min-h-[550px] animate-fade-in-up">
                <div class="px-10 py-8 bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-100 dark:border-zinc-800">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center shadow-lg border border-slate-100 dark:border-zinc-800 text-{{ $cat['theme'] }}-600">
                                <i class="fa-solid {{ $cat['icon'] }} text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xs font-black text-{{ $cat['theme'] }}-600 dark:text-{{ $cat['theme'] }}-400 uppercase tracking-[0.3em] mb-1">{{ $cat['title'] }}</h2>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $cat['th_title'] }}</h3>
                            </div>
                        </div>
                        <div class="flex items-center bg-white dark:bg-zinc-900 px-4 py-2 rounded-xl border border-slate-200 dark:border-zinc-800">
                            <span class="text-xl font-black text-slate-900 dark:text-white mr-2">{{ $cat['data']->count() }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">รายการ</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-8 space-y-6 custom-scrollbar bg-white dark:bg-zinc-900/30">
                    @forelse($cat['data'] as $item)
                        @include('backend.welcomedatamanage_item_card', ['item' => $item, 'theme' => $cat['theme'], 'type' => $cat['id'], 'statusType' => 'pending'])
                    @empty
                        <div class="h-full flex flex-col items-center justify-center py-20 grayscale opacity-40">
                             <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center mb-6 border-2 border-dashed border-slate-200">
                                <i class="fa-solid {{ $cat['icon'] }} text-3xl text-slate-400"></i>
                             </div>
                             <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">ไม่มีรายการรอดำเนินการ</p>
                        </div>
                    @endforelse
                </div>
                
                @if($cat['data']->count() > 0)
                    <div class="px-10 py-6 bg-slate-50 dark:bg-zinc-800/50 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between">
                         <div class="flex -space-x-2">
                             @for($i=0; $i<min(3, $cat['data']->count()); $i++)
                                <div class="w-8 h-8 rounded-full bg-{{ $cat['theme'] }}-100 border-2 border-white dark:border-zinc-900 flex items-center justify-center text-[10px] font-black text-{{ $cat['theme'] }}-600">
                                    {{ substr($cat['data'][$i]->user->first_name ?? 'U', 0, 1) }}
                                </div>
                             @endfor
                             @if($cat['data']->count() > 3)
                                <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white dark:border-zinc-900 flex items-center justify-center text-[10px] font-black text-slate-600">
                                    +{{ $cat['data']->count() - 3 }}
                                </div>
                             @endif
                         </div>
                        <a href="{{ $cat['route'] !== '#' ? route($cat['route']) : '#' }}" class="group flex items-center gap-3 px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:shadow-xl hover:px-10 hover:bg-black active:scale-95">
                            จัดการรายการทั้งหมด
                            <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-2"></i>
                        </a>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div id="taskDetailModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="window.closeTaskDetail()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-zinc-800 animate-zoom-in">
            <div class="px-8 py-6 border-b border-slate-100 dark:border-zinc-800 flex items-center justify-between bg-slate-50/50 dark:bg-zinc-800/50">
                <div class="flex items-center gap-4">
                    <div id="modalIcon" class="w-12 h-12 rounded-2xl bg-white dark:bg-zinc-900 flex items-center justify-center shadow-lg border border-slate-100 dark:border-zinc-800 text-amber-600">
                        <i class="fa-solid fa-file-lines text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-1">Detailed View</h3>
                        <h2 id="modalTitle" class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">รายละเอียดรายการ</h2>
                    </div>
                </div>
                <button onclick="window.closeTaskDetail()" class="w-10 h-10 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-slate-400"></i>
                </button>
            </div>
            
            <div id="modalBody" class="p-10 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                <!-- Data will be injected here -->
                <div class="flex flex-col items-center justify-center py-20 animate-pulse">
                    <div class="w-12 h-12 rounded-full border-4 border-amber-500 border-t-transparent animate-spin mb-4"></div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">กำลังดึงข้อมูลระบบ...</p>
                </div>
            </div>

            <div class="px-8 py-6 bg-slate-50 dark:bg-zinc-800/50 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-end gap-4">
                <button onclick="window.closeTaskDetail()" class="px-8 py-3 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-700 text-slate-600 dark:text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">ยกเลิก</button>
                <a id="modalActionBtn" href="#" class="px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:shadow-xl transition-all">ไปหน้าจัดการ</a>
            </div>
        </div>
    </div>
</div>

<script>
    window.showTaskDetailFromElement = function(el) {
        const modal = document.getElementById('taskDetailModal');
        const body = document.getElementById('modalBody');
        const actionBtn = document.getElementById('modalActionBtn');
        const title = document.getElementById('modalTitle');
        const data = JSON.parse(el.getAttribute('data-task-json'));
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Set action button URL
        let detailUrl = '#';
        if (data.type === 'requisitions') detailUrl = "{{ route('requisitions.reqlistall') }}";
        else if (data.type === 'reservations') detailUrl = "{{ route('backend.bookingmeeting.reservations.index') }}";
        else if (data.type === 'vehicleBookings') detailUrl = "{{ route('bookingcar.dashboard') }}";
        else if (data.type === 'housingTasks') detailUrl = "{{ route('housing.management') }}";
        actionBtn.href = detailUrl;

        // Build Details HTML
        let detailsHtml = `
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">รหัสรายการ</span>
                        <span class="text-lg font-black text-slate-900 dark:text-white uppercase leading-none">${data.code}</span>
                    </div>
                    <div class="space-y-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">สถานะปัจจุบัน</span>
                        <span class="inline-flex px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase border border-amber-100">${data.status}</span>
                    </div>
                </div>
                
                <div class="p-8 bg-slate-50 dark:bg-zinc-800/80 rounded-[2rem] border border-slate-100 dark:border-zinc-700 space-y-6">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-200 dark:border-zinc-700 pb-3">ข้อมูลรายละเอียด</h4>
                    <div class="grid grid-cols-1 gap-4">
                        ${data.details.map(d => `
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-slate-500 uppercase">${d.label}</span>
                                <span class="text-[11px] font-black text-slate-800 dark:text-white uppercase">${d.value}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="flex items-start gap-4 p-5 bg-blue-50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/30">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                    <p class="text-[11px] font-bold text-blue-700/80 dark:text-blue-400/80 leading-relaxed uppercase">
                        นี่คือข้อมูลสรุปจากระบบ Monitoring หากต้องการดูเอกสารต้นฉบับหรือจัดการรายการ กรุณากดปุ่ม "ไปหน้าจัดการ"
                    </p>
                </div>
            </div>
        `;
        
        body.innerHTML = detailsHtml;
        title.innerText = data.title;
    };

    window.closeTaskDetail = function() {
        document.getElementById('taskDetailModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
</script>

<style>
    @font-face {
        font-family: 'Outfit';
        src: url('https://fonts.googleapis.com/css2?family=Outfit:wght@100;400;700;900&display=swap');
    }
    
    .max-w-1600px { max-width: 1600px; }

    @keyframes zoom-in { 
        from { opacity: 0; transform: scale(0.98) translateY(10px); } 
        to { opacity: 1; transform: scale(1) translateY(0); } 
    }
    
    @keyframes fade-in-up { 
        from { opacity: 0; transform: translateY(30px); } 
        to { opacity: 1; transform: translateY(0); } 
    }

    .animate-zoom-in { animation: zoom-in 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in-up { 
        opacity: 0;
        animation: fade-in-up 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
    }

    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #27272a; }
</style>
@endsection