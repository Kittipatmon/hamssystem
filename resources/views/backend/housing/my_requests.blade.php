@extends('layouts.housing.apphousing')

@section('title', 'ติดตามสถานะคำขอ')

@section('content')
    <div class="max-w-6xl mx-auto pb-12">

        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-3">
                <i class="fa-solid fa-clock-rotate-left text-red-500 text-3xl"></i>
                ติดตามสถานะคำขอ
            </h2>
            <p class="text-slate-500 text-sm mt-1">รายการคำขอทั้งหมดของคุณที่กำลังดำเนินการหรือเสร็จสิ้นแล้ว</p>
        </div>

        {{-- Tabs for different categories --}}
        <div
            class="bg-white dark:bg-gray-400 rounded-2xl border border-slate-100 dark:border-slate-700 p-2 mb-8 shadow-sm flex flex-wrap gap-1">
            @php
                $userId = Auth::id();
                $myRepairTasksCount = \App\Models\housing\ResidenceRepair::where('technician_id', $userId)->where('status', 1)->count();
                $totalPending = collect($pendingApprovals)->sum(function($items){ return count($items); }) + $myRepairTasksCount;
                
                $tabItems = [];
                if($totalPending > 0) {
                    $tabItems[] = [
                        'id' => 'tab-pending', 
                        'label' => 'งานรอดำเนินการ', 
                        'icon' => 'fa-bell-exclamation', 
                        'color' => 'text-red-500',
                        'count' => $totalPending,
                        'is_pending_tab' => true
                    ];
                }
                
                $tabItems = array_merge($tabItems, [
                    [
                        'id' => 'tab-requests', 
                        'label' => 'คำขอเข้าพัก', 
                        'icon' => 'fa-file-circle-plus', 
                        'color' => 'text-red-500',
                        'count' => \App\Models\housing\ResidenceRequest::where('user_id', $userId)->whereIn('send_status', [4, 7])->count()
                    ],
                    [
                        'id' => 'tab-agreements', 
                        'label' => 'ข้อตกลงเข้าพัก', 
                        'icon' => 'fa-file-signature', 
                        'color' => 'text-blue-500',
                        'count' => \App\Models\housing\ResidenceAgreement::where('user_id', $userId)->where('send_status', 4)->count()
                    ],
                    [
                        'id' => 'tab-guests', 
                        'label' => 'นำญาติเข้าพัก', 
                        'icon' => 'fa-people-arrows', 
                        'color' => 'text-purple-500',
                        'count' => \App\Models\housing\ResidentGuestRequest::where('user_id', $userId)->where('send_status', 4)->count()
                    ],
                    [
                        'id' => 'tab-leaves', 
                        'label' => 'คำร้องย้ายออก', 
                        'icon' => 'fa-right-from-bracket', 
                        'color' => 'text-orange-500',
                        'count' => \App\Models\housing\ResidenceLeave::where('user_id', $userId)->where('send_status', 4)->count()
                    ],
                ]);
            @endphp

            @foreach($tabItems as $index => $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" id="btn-{{ $tab['id'] }}"
                    class="tab-btn flex-1 min-w-[150px] flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ $index === 0 ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg shadow-red-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    <i class="fa-solid {{ $tab['icon'] }} {{ $index === 0 ? 'text-white' : ($tab['color'] ?? '') }}"></i>
                    {{ $tab['label'] }}
                    @if($tab['count'] > 0)
                        <span class="ml-1.5 px-1.5 py-0.5 rounded-full bg-slate-100 text-[10px] {{ $index === 0 ? 'text-red-600' : 'text-slate-500' }}">{{ $tab['count'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Tab Contents --}}
        <div class="tab-panels">
            @if($totalPending > 0)
                <div id="content-tab-pending" 
                    class="tab-panel bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                    <div class="space-y-10">
                        @if(count($pendingApprovals['requests']))
                            <div>
                                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> คำขอเข้าพักที่รอคุณอนุมัติ
                                </h3>
                                @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['requests'], 'type' => 'request', 'is_pending' => true])
                            </div>
                        @endif
                        @if(count($pendingApprovals['agreements']))
                            <div>
                                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> ข้อตกลงที่รอคุณอนุมัติ
                                </h3>
                                @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['agreements'], 'type' => 'agreement', 'is_pending' => true])
                            </div>
                        @endif
                        @if(count($pendingApprovals['guests']))
                            <div>
                                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> คำขอนำญาติเข้าพักที่รอคุณอนุมัติ
                                </h3>
                                @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['guests'], 'type' => 'guest', 'is_pending' => true])
                            </div>
                        @endif
                        @if(count($pendingApprovals['leaves']))
                            <div>
                                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> คำร้องขอย้ายออกที่รอคุณอนุมัติ
                                </h3>
                                @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['leaves'], 'type' => 'leave', 'is_pending' => true])
                            </div>
                        @endif

                        @php
                            $myRepairTasks = \App\Models\housing\ResidenceRepair::where('technician_id', Auth::id())->where('status', 1)->get();
                        @endphp
                        @if($myRepairTasks->count())
                            <div>
                                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> งานแจ้งซ่อมที่มอบหมายให้คุณ
                                </h3>
                                @include('backend.housing.partials.repair_list_technician', ['items' => $myRepairTasks])
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div id="content-tab-requests"
                class="tab-panel {{ $totalPending > 0 ? 'hidden' : '' }} bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $requests, 'type' => 'request', 'is_pending' => false])
            </div>

            <div id="content-tab-agreements"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $agreements, 'type' => 'agreement', 'is_pending' => false])
            </div>

            <div id="content-tab-guests"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $guests, 'type' => 'guest', 'is_pending' => false])
            </div>

            <div id="content-tab-leaves"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $leaves, 'type' => 'leave', 'is_pending' => false])
            </div>
        </div>

        <script>
            function switchTab(tabId) {
                localStorage.setItem('housing_active_tab', tabId);
                // Hide all panels
                document.querySelectorAll('.tab-panel').forEach(panel => {
                    panel.classList.add('hidden');
                });
                // Show selected panel
                const contentPanel = document.getElementById('content-' + tabId);
                if(contentPanel) contentPanel.classList.remove('hidden');

                // Reset all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'text-white', 'shadow-lg', 'shadow-red-200');
                    btn.classList.add('text-slate-500', 'hover:bg-slate-50', 'dark:hover:bg-slate-700');
                    const icon = btn.querySelector('i');
                    if (icon) icon.classList.remove('text-white');
                });

                // Active button state
                const activeBtn = document.getElementById('btn-' + tabId);
                if(activeBtn) {
                    activeBtn.classList.add('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'text-white', 'shadow-lg', 'shadow-red-200');
                    activeBtn.classList.remove('text-slate-500', 'hover:bg-slate-50', 'dark:hover:bg-slate-700');
                    const activeIcon = activeBtn.querySelector('i');
                    if (activeIcon) activeIcon.classList.add('text-white');
                }
            }

            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                const tabParam = urlParams.get('tab');
                
                if (tabParam && document.getElementById('btn-tab-' + tabParam)) {
                    switchTab('tab-' + tabParam);
                } else {
                    const activeTab = localStorage.getItem('housing_active_tab');
                    if (activeTab && document.getElementById('btn-' + activeTab)) {
                        switchTab(activeTab);
                    }
                }
            };
        </script>
    </div>
@endsection