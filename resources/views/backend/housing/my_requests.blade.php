@extends('layouts.housing.apphousing')

@section('title', 'ติดตามสถานะคำขอ')

@section('content')
     <style>
        /* Clinical Tab System */
        .management-tabs-container {
            display: flex;
            gap: 6px;
            padding: 6px;
            background: #f1f5f9;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            width: 100%;
            overflow-x: auto;
            margin-bottom: 24px;
        }

        .management-tab {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            transition: all 0.2s ease;
            position: relative;
            background: transparent;
            border: none;
            cursor: pointer;
            flex: 1;
            min-width: 150px;
        }

        .management-tab:hover {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.5);
        }


        .management-tab.active {
            color: #1e293b;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .management-tab.active i {
            color: #ef4444;
        }

        .badge-count {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            background: #dc2626;
            color: white;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            border: 1px solid white;
            margin-left: 4px;
        }

        /* Hospital Ledger Table Styling */
        .clinical-table {
            width: 100%;
            border-collapse: collapse;
        }
        .clinical-table th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 700;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 1px solid #cbd5e1 !important;
            padding: 12px 16px;
        }
        .clinical-table td {
            border: 1px solid #e2e8f0 !important;
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 13px;
            background-color: #ffffff;
        }
        .clinical-table tr:hover td {
            background-color: #f8fafc;
        }
     </style>

    <div class="max-w-6xl mx-auto pb-12 py-8">

        <!-- Premium Header (Clinical Theme) -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-200 pb-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center shadow-sm border border-red-100 shrink-0">
                    <i class="fa-solid fa-clock-rotate-left text-red-600 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-800">ติดตามสถานะคำขอ (MY REQUEST STATUS)</h2>
                    <p class="text-slate-500 mt-1 flex items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        รายการคำขอเข้าพัก ข้อตกลง และการย้ายออกทั้งหมดของคุณ
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('housing.welcome') }}" 
                    class="btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 rounded-xl px-5 text-xs sm:text-sm h-11 flex items-center gap-2 transition-all border shadow-sm">
                    <i class="fa-solid fa-house-chimney text-slate-400"></i> กลับหน้าหลัก
                </a>
            </div>
        </div>

        {{-- Tabs for different categories --}}
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

        <div class="management-tabs-container">
            @foreach($tabItems as $index => $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" id="btn-{{ $tab['id'] }}"
                    class="management-tab {{ $index === 0 ? 'active' : '' }}">
                    <i class="fa-solid {{ $tab['icon'] }} text-sm"></i>
                    <span class="whitespace-nowrap">{{ $tab['label'] }}</span>
                    @if($tab['count'] > 0)
                        <span class="badge-count">{{ $tab['count'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Tab Contents --}}
        <div class="tab-panels bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            @if($totalPending > 0)
                <div id="content-tab-pending" class="tab-panel space-y-8">
                    @if(count($pendingApprovals['requests']))
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> คำขอเข้าพักที่รอคุณอนุมัติ
                            </h3>
                            @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['requests'], 'type' => 'request', 'is_pending' => true])
                        </div>
                    @endif
                    @if(count($pendingApprovals['agreements']))
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> ข้อตกลงที่รอคุณอนุมัติ
                            </h3>
                            @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['agreements'], 'type' => 'agreement', 'is_pending' => true])
                        </div>
                    @endif
                    @if(count($pendingApprovals['guests']))
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span> คำขอนำญาติเข้าพักที่รอคุณอนุมัติ
                            </h3>
                            @include('backend.housing.partials.request_list', ['items' => $pendingApprovals['guests'], 'type' => 'guest', 'is_pending' => true])
                        </div>
                    @endif
                    @if(count($pendingApprovals['leaves']))
                        <div>
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-widest flex items-center gap-2">
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
                            <h3 class="text-xs font-bold text-slate-500 mb-3 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> งานแจ้งซ่อมที่มอบหมายให้คุณ
                            </h3>
                            @include('backend.housing.partials.repair_list_technician', ['items' => $myRepairTasks])
                        </div>
                    @endif
                </div>
            @endif

            <div id="content-tab-requests" class="tab-panel {{ $totalPending > 0 ? 'hidden' : '' }}">
                @include('backend.housing.partials.request_list', ['items' => $requests, 'type' => 'request', 'is_pending' => false])
            </div>

            <div id="content-tab-agreements" class="tab-panel hidden">
                @include('backend.housing.partials.request_list', ['items' => $agreements, 'type' => 'agreement', 'is_pending' => false])
            </div>

            <div id="content-tab-guests" class="tab-panel hidden">
                @include('backend.housing.partials.request_list', ['items' => $guests, 'type' => 'guest', 'is_pending' => false])
            </div>

            <div id="content-tab-leaves" class="tab-panel hidden">
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
                document.querySelectorAll('.management-tab').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Active button state
                const activeBtn = document.getElementById('btn-' + tabId);
                if(activeBtn) {
                    activeBtn.classList.add('active');
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