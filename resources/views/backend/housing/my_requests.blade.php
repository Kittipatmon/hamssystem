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
                $tabItems = [
                    ['id' => 'tab-requests', 'label' => 'คำขอเข้าพัก', 'icon' => 'fa-file-circle-plus', 'color' => 'text-red-500'],
                    ['id' => 'tab-agreements', 'label' => 'ข้อตกลงเข้าพัก', 'icon' => 'fa-file-signature', 'color' => 'text-blue-500'],
                    ['id' => 'tab-guests', 'label' => 'นำญาติเข้าพัก', 'icon' => 'fa-people-arrows', 'color' => 'text-purple-500'],
                    ['id' => 'tab-leaves', 'label' => 'คำร้องย้ายออก', 'icon' => 'fa-right-from-bracket', 'color' => 'text-orange-500'],
                ];
            @endphp

            @foreach($tabItems as $index => $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" id="btn-{{ $tab['id'] }}"
                    class="tab-btn flex-1 min-w-[150px] flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-300 {{ $index === 0 ? 'bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg shadow-red-200' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700' }}">
                    <i class="fa-solid {{ $tab['icon'] }} {{ $index === 0 ? 'text-white' : ($tab['color'] ?? '') }}"></i>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Tab Contents --}}
        <div class="tab-panels">
            <div id="content-tab-requests"
                class="tab-panel bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $requests, 'type' => 'request'])
            </div>

            <div id="content-tab-agreements"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $agreements, 'type' => 'agreement'])
            </div>

            <div id="content-tab-guests"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $guests, 'type' => 'guest'])
            </div>

            <div id="content-tab-leaves"
                class="tab-panel hidden bg-white dark:bg-gray-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm overflow-x-auto">
                @include('backend.housing.partials.request_list', ['items' => $leaves, 'type' => 'leave'])
            </div>
        </div>

        <script>
            function switchTab(tabId) {
                // Hide all panels
                document.querySelectorAll('.tab-panel').forEach(panel => {
                    panel.classList.add('hidden');
                });
                // Show selected panel
                document.getElementById('content-' + tabId).classList.remove('hidden');

                // Reset all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'text-white', 'shadow-lg', 'shadow-red-200');
                    btn.classList.add('text-slate-500', 'hover:bg-slate-50', 'dark:hover:bg-slate-700');

                    // Reset icon color (finding the original color class from the Blade data would be complex, 
                    // so we'll just remove text-white and the color classes will take over if they were there)
                    const icon = btn.querySelector('i');
                    if (icon) icon.classList.remove('text-white');
                });

                // Active button state
                const activeBtn = document.getElementById('btn-' + tabId);
                activeBtn.classList.add('bg-gradient-to-r', 'from-red-600', 'to-red-700', 'text-white', 'shadow-lg', 'shadow-red-200');
                activeBtn.classList.remove('text-slate-500', 'hover:bg-slate-50', 'dark:hover:bg-slate-700');
                const activeIcon = activeBtn.querySelector('i');
                if (activeIcon) activeIcon.classList.add('text-white');
            }
        </script>
    </div>
@endsection