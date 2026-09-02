<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HAMS System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Prompt', sans-serif;
        }

        /* Custom Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #7367F0;
        }

        /* Helper class to hide elements via JS */
        .hidden-force {
            display: none !important;
        }

        /* Smooth transition for sidebar width */
        #sidebar {
            transition: width 0.3s ease;
        }

        /* Kumwell Premium UI Components */
        .kumwell-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .kumwell-glass {
            background: rgba(30, 33, 41, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .kumwell-card {
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kumwell-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
        }

        .btn-kumwell-red {
            background-color: #7367F0;
            color: white;
            transition: all 0.2s ease;
        }

        .btn-kumwell-red:hover {
            background-color: #b71515;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(215, 25, 32, 0.3);
        }

        .kumwell-table-header {
            background: rgba(243, 244, 246, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .dark .kumwell-table-header {
            background: rgba(255, 255, 255, 0.03);
        }

        .kumwell-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* -------------------------------------------------------------
           CLINICAL GRID & HOSPITAL STRUCTURE DESIGN SYSTEM
           ------------------------------------------------------------- */
        .clinical-sidebar { background-color: #ffffff; border-right: 1px solid #e2e8f0; }
.dark .clinical-sidebar { background-color: #28243d; border-right: 1px solid #3b3559; }
        .clinical-header { background-color: #ffffff; border-bottom: 1px solid #e2e8f0; }
.dark .clinical-header { background-color: #28243d; border-bottom: 1px solid #3b3559; }
        
        /* Menu Grid Cards (Folder/Registry Style) */
        .menu-grid-item { border-radius: 0.5rem !important; background-color: transparent !important; color: #475569 !important; transition: all 0.2s ease-in-out; font-weight: 500; margin-bottom: 0.25rem; border: none !important; }
.dark .menu-grid-item { color: #cbd5e1 !important; border: none !important; background-color: transparent !important; }
        .menu-grid-item:hover { background-color: rgba(115, 103, 240, 0.08) !important; color: #7367F0 !important; border: none !important; }
.dark .menu-grid-item:hover { background-color: rgba(115, 103, 240, 0.16) !important; color: #7367F0 !important; border: none !important; }
        .menu-grid-item.active-menu { background-color: #7367F0 !important; box-shadow: 0 2px 6px 0 rgba(115, 103, 240, 0.3) !important; color: #ffffff !important; border: none !important; }
.dark .menu-grid-item.active-menu { background-color: #7367F0 !important; box-shadow: 0 2px 6px 0 rgba(115, 103, 240, 0.3) !important; color: #ffffff !important; border: none !important; }

        .clinical-section-header { font-size: 11px !important; font-weight: 500 !important; color: #94a3b8; text-transform: uppercase; padding: 12px 16px 4px 16px; display: block; border: none; background: transparent; }
.dark .clinical-section-header { color: #64748b; background: transparent; border: none; }

        /* Submenu Clinical Panel */
        .submenu-clinical-panel { background-color: transparent; padding: 4px 0 4px 12px; margin-top: 0; border: none; }
.dark .submenu-clinical-panel { background-color: transparent; border: none; }

        /* Hospital Grid Table Layout */
        .clinical-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px !important;
            background-color: #ffffff;
            border: 2px solid #cbd5e1;
        }
        .dark .clinical-table {
            background-color: #1e2129;
            border-color: #475569;
        }
        .clinical-table th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px 14px !important;
            border: 1px solid #cbd5e1 !important;
            text-align: left;
        }
        .dark .clinical-table th {
            background-color: #121418 !important;
            color: #f1f5f9 !important;
            border-color: #475569 !important;
        }
        .clinical-table td {
            padding: 10px 14px !important;
            border: 1px solid #cbd5e1 !important;
            color: #334155;
            vertical-align: middle;
        }
        .dark .clinical-table td {
            border-color: #475569 !important;
            color: #e2e8f0;
        }
        .clinical-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .dark .clinical-table tr:nth-child(even) {
            background-color: #242933;
        }
        .clinical-table tr:hover {
            background-color: #f1f5f9;
            transition: background-color 0.1s ease;
        }
        .dark .clinical-table tr:hover {
            background-color: #2a2e38;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        kumwell: {
                            red: '#7367F0',
                            dark: '#121418',
                            card: '#1E2129',
                            hover: '#2A2E38'
                        }
                    },
                    width: {
                        '68': '17rem',
                    }
                }
            }
        }
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: @json(session('success')),
                    timer: 2500,
                    showConfirmButton: false
                });
            });
        @endif
    </script>
</head>

<body class="bg-gray-50 dark:bg-kumwell-dark text-gray-800 dark:text-gray-200 antialiased">
<div class="flex h-screen overflow-hidden relative">

        <!-- Mobile Overlay Backdrop -->
        <div id="sidebar-backdrop"
            class="fixed inset-0 bg-black/50 z-20 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

        <aside id="sidebar"
            class="w-68 flex-shrink-0 flex flex-col h-full clinical-sidebar z-30 fixed md:relative -translate-x-full md:translate-x-0 transition-all duration-300 overflow-hidden">

            <!-- Header -->
            <div id="sidebar-header" class="h-20 flex items-center justify-between px-5 clinical-header">
                <div id="sidebar-logo"
                    class="flex items-center gap-2.5 overflow-hidden whitespace-nowrap transition-all duration-300 opacity-100">
                    <div
                        class="w-9 h-9 rounded bg-[#7367F0] flex items-center justify-center flex-shrink-0 shadow-sm border border-indigo-700">
                        <span class="font-black text-white text-lg tracking-wider">K</span>
                    </div>
                    <a href="{{ route('welcome') }}">
                        <div class="flex flex-col leading-none">
                            <span class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-wider">Kumwell</span>
                            <span class="text-[9px] text-[#7367F0] font-black uppercase tracking-widest mt-1">HA SYSTEM</span>
                        </div>
                    </a>
                </div>

                <button id="sidebar-toggle-btn"
                    class="p-1.5 rounded border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-all focus:outline-none">
                    <i id="icon-bars" class="fa-solid fa-bars text-sm hidden-force"></i>
                    <i id="icon-chevron" class="fa-solid fa-chevron-left text-xs"></i>
                </button>
            </div>

            @php
                $inventoryActive = request()->routeIs('items.*') || request()->routeIs('datamanage.news.*');
                $policyActive = request()->routeIs('backend.policy.*') || request()->routeIs('backend.announcement.*');
                $hamsActive = request()->routeIs('users.*') || request()->routeIs('usertypes.*') || request()->routeIs('sections.*') || request()->routeIs('divisions.*') || request()->routeIs('departments.*') || request()->routeIs('managers.*');
                $systemLogsActive = request()->routeIs('system-logs.*');
                $securityAlertsActive = request()->routeIs('security-alerts.*');
                $housingActive = request()->routeIs('housing.*');
                $isDashboard = request()->routeIs('backend.welcomedatamanage') || request()->routeIs('housing.welcome');
            @endphp

            <nav class="flex-1 px-3 py-4 space-y-3 overflow-y-auto overflow-x-hidden sidebar-scroll bg-slate-50/50 dark:bg-zinc-900/10">
                
                <div>
                    <span class="clinical-section-header sidebar-text">Main Registry Control</span>
                    <div class="space-y-1.5">
                        <!-- Dashboard -->
                        <a href="{{ route('backend.welcomedatamanage') }}"
                            class="group relative flex items-center px-3 py-2.5 menu-grid-item {{ $isDashboard ? 'active-menu' : '' }}">
                            <i id="dashboard-icon" class="fa-solid fa-chart-pie text-sm w-5 text-center mr-2.5 shrink-0"></i>
                            <span class="sidebar-text text-[13px]">Dashboard</span>
                        </a>

                        <!-- policy management -->
                        @if(in_array(Auth::user()->role, ['admin', 'editor']) || in_array(Auth::user()->dept_id, [14, 16]))
                            <div class="relative group">
                                <button onclick="toggleDropdown('dropdown-policy')"
                                    class="w-full flex items-center justify-between px-3 py-2.5 menu-grid-item {{ $policyActive ? 'active-menu' : '' }}"
                                    id="btn-policy">
                                    <div class="flex items-center min-w-0">
                                        <i id="icon-policy" class="fa-solid fa-users-gear text-sm w-5 text-center mr-2.5 shrink-0"></i>
                                        <span class="sidebar-text text-[13px] truncate">จัดการนโยบาย/ขั้นตอน</span>
                                    </div>
                                    <i id="arrow-policy" class="sidebar-text fa-solid fa-chevron-down text-[9px] transition-transform duration-300 {{ $policyActive ? 'rotate-180' : '' }}"></i>
                                </button>

                                <div id="dropdown-policy" class="{{ $policyActive ? '' : 'hidden' }} submenu-clinical-panel space-y-1">
                                    <a href="{{ route('backend.policy.index', ['type' => 'policy']) }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->get('type') === 'policy' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- นโยบาย</a>
                                    <a href="{{ route('backend.policy.index', ['type' => 'operation']) }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->get('type') === 'operation' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- หมวดหมู่การดำเนินงาน</a>
                                    <a href="{{ route('backend.announcement.index') }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->routeIs('backend.announcement.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- จัดการประกาศ / แจ้งให้ทราบ</a>
                                </div>
                            </div>
                        @endif

                        <!-- hams user management -->
                        @if(in_array(Auth::user()->role, ['admin', 'editor']) || in_array(Auth::user()->dept_id, [14, 16]))
                            <div class="relative group">
                                <button onclick="toggleDropdown('dropdown-hr')"
                                    class="w-full flex items-center justify-between px-3 py-2.5 menu-grid-item {{ $hamsActive ? 'active-menu' : '' }}"
                                    id="btn-hr">
                                    <div class="flex items-center min-w-0">
                                        <i id="icon-hr" class="fa-solid fa-user-group text-sm w-5 text-center mr-2.5 shrink-0"></i>
                                        <span class="sidebar-text text-[13px] truncate">HAMS User / สิทธิ์</span>
                                    </div>
                                    <i id="arrow-hr" class="sidebar-text fa-solid fa-chevron-down text-[9px] transition-transform duration-300 {{ $hamsActive ? 'rotate-180' : '' }}"></i>
                                </button>

                                <div id="dropdown-hr" class="{{ $hamsActive ? '' : 'hidden' }} submenu-clinical-panel space-y-1">
                                    <a href="{{ route('users.index') }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->routeIs('users.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- ข้อมูลพนักงาน</a>
                                    <a href="{{ route('departments.index') }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->routeIs('departments.*') && !request()->routeIs('managers.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- ข้อมูลแผนก</a>
                                    <a href="{{ route('managers.index') }}"
                                        class="block px-2.5 py-1.5 rounded text-[12px] text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/10 transition-colors {{ request()->routeIs('managers.*') ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/30 dark:bg-indigo-950/5 font-bold border-l-2 border-indigo-600' : '' }}">- ข้อมูลหัวหน้าแผนก</a>
                                </div>
                            </div>
                        @endif

                        <!-- System Logs -->
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('system-logs.index') }}"
                                class="group relative flex items-center px-3 py-2.5 menu-grid-item {{ $systemLogsActive ? 'active-menu' : '' }}">
                                <i id="logs-icon" class="fa-solid fa-clock-rotate-left text-sm w-5 text-center mr-2.5 shrink-0"></i>
                                <span class="sidebar-text text-[13px]">บันทึกระบบ (System Logs)</span>
                            </a>
                            
                            <!-- Security Alerts -->
                            <a href="{{ route('security-alerts.index') }}"
                                class="group relative flex items-center px-3 py-2.5 menu-grid-item {{ $securityAlertsActive ? 'active-menu' : '' }}">
                                <i id="security-icon" class="fa-solid fa-shield-halved text-sm w-5 text-center mr-2.5 shrink-0 group-hover:text-white transition-colors {{ $securityAlertsActive ? 'text-white' : '' }}"></i>
                                <span class="sidebar-text text-[13px]">การแจ้งเตือนการโจมตีระบบ</span>
                            </a>
                        @endif
                    </div>
                </div>

            </nav>

            <!-- Preferences & Profile footer block -->
            <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-zinc-900/50 space-y-2.5">
                <div class="sidebar-text flex items-center justify-between px-1">
                    <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Preferences</span>
                </div>

                <div class="sidebar-text flex items-center justify-between px-3 py-2 rounded border border-slate-200 dark:border-slate-800 bg-white dark:bg-zinc-900 shadow-sm transition-all hover:border-[#7367F0]/30 group">
                    <div class="flex items-center gap-2">
                        <div class="w-6.5 h-6.5 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-[#7367F0] transition-colors duration-300">
                            <i class="fa-solid fa-moon text-xs"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400">Dark Mode</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="dark-mode-toggle" class="sr-only peer">
                        <div class="w-19 h-6 bg-slate-200 dark:bg-slate-800 rounded-full peer-focus:ring-2 peer-focus:ring-[#7367F0]/20 peer-checked:bg-[#7367F0] transition-all duration-300 shadow-inner">
                            <div class="absolute inset-0 flex items-center justify-between px-2 pointer-events-none opacity-40">
                                <i class="fa-solid fa-sun text-[9px] text-yellow-600"></i>
                                <i class="fa-solid fa-moon text-[9px] text-blue-400"></i>
                            </div>
                        </div>
                        <div class="absolute top-0.5 left-0.5 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-6 shadow-sm"></div>
                    </label>
                </div>

                <!-- System Config Modal Trigger -->
                <button type="button" onclick="openSystemSettingsModal()" class="sidebar-text flex items-center justify-between px-3 py-2.5 w-full rounded border border-slate-200 dark:border-slate-800 bg-white dark:bg-zinc-900 shadow-sm transition-all hover:border-[#7367F0]/30 group text-left">
                    <div class="flex items-center gap-2">
                        <div class="w-6.5 h-6.5 rounded bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:text-[#7367F0] transition-colors duration-300">
                            <i class="fa-solid fa-sliders text-xs"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400">ตั้งค่าเปิด-ปิดระบบ</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 group-hover:translate-x-0.5 transition-transform"></i>
                </button>

                <!-- Admin Card style layout -->
                <div id="user-profile" class="flex items-center gap-3 p-3 rounded border border-slate-200 dark:border-slate-800 bg-white dark:bg-zinc-900 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-[#7367F0]"></div>
                    <div class="w-9 h-9 rounded bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 font-bold shrink-0">
                        <i class="fa-solid fa-id-card text-sm"></i>
                    </div>
                    <div class="sidebar-text flex-1 min-w-0">
                        <p class="text-[12px] font-black text-slate-800 dark:text-white truncate">
                            {{ Auth::user()->fullname }}
                        </p>
                        <p class="text-[10px] text-slate-400 font-bold tracking-wider mt-0.5">
                            ID: {{ Auth::user()->emp_code }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 bg-gray-50 dark:bg-kumwell-dark text-gray-900 dark:text-gray-100 overflow-y-auto relative">
            <div class="p-4">
                <div class="flex justify-between items-center mb-4 border-b border-gray-300/30 pb-3">
                    <div class="flex items-center gap-3">
                        <button id="mobile-sidebar-toggle"
                            class="md:hidden p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>
                    <div class="text-sm text-indigo-500">
                        <span id="current-date"></span>
                    </div>
                </div>
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // === 1. Sidebar Logic ===
        const sidebar = document.getElementById('sidebar');
        const sidebarHeader = document.getElementById('sidebar-header');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');
        const mobileToggleBtn = document.getElementById('mobile-sidebar-toggle');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const iconBars = document.getElementById('icon-bars');
        const iconChevron = document.getElementById('icon-chevron');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const sidebarLogo = document.getElementById('sidebar-logo');
        const tooltips = document.querySelectorAll('.tooltip');
        const dropdownSubmenus = document.querySelectorAll('[id^="dropdown-"]');
        const userProfile = document.getElementById('user-profile');

        let isMobile = window.innerWidth < 768;
        let isSidebarOpen = !isMobile;

        // Debounce resize to prevent flicker
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const newIsMobile = window.innerWidth < 768;
                if (isMobile !== newIsMobile) {
                    isMobile = newIsMobile;
                    isSidebarOpen = !isMobile;
                    updateSidebarState();
                }
            }, 100);
        });

        // Initialize state
        updateSidebarState();

        toggleBtn.addEventListener('click', () => {
            if (isMobile) {
                isSidebarOpen = false; // Always close on mobile when clicking inner toggle
            } else {
                isSidebarOpen = !isSidebarOpen;
            }
            updateSidebarState();
        });

        if (mobileToggleBtn) {
            mobileToggleBtn.addEventListener('click', () => {
                isSidebarOpen = true;
                updateSidebarState();
            });
        }

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', () => {
                isSidebarOpen = false;
                updateSidebarState();
            });
        }

        function updateSidebarState() {
            if (isMobile) {
                // Mobile layout logic
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-68');

                if (sidebarHeader) {
                    sidebarHeader.classList.remove('justify-center', 'px-0');
                    sidebarHeader.classList.add('justify-between', 'px-6');
                }

                iconBars.classList.add('hidden-force');
                iconChevron.classList.remove('hidden-force');
                sidebarLogo.classList.remove('opacity-0', 'w-0');
                sidebarLogo.classList.add('opacity-100');
                sidebarTexts.forEach(el => {
                    el.classList.remove('hidden');
                    el.classList.remove('hidden-force');
                });
                tooltips.forEach(t => t.classList.add('hidden'));
                userProfile.classList.remove('justify-center');

                if (isSidebarOpen) {
                    sidebar.classList.remove('-translate-x-full');
                    if (sidebarBackdrop) {
                        sidebarBackdrop.classList.remove('hidden');
                        setTimeout(() => sidebarBackdrop.classList.remove('opacity-0'), 10);
                    }
                } else {
                    sidebar.classList.add('-translate-x-full');
                    if (sidebarBackdrop) {
                        sidebarBackdrop.classList.add('opacity-0');
                        setTimeout(() => sidebarBackdrop.classList.add('hidden'), 300);
                    }
                }
            } else {
                // Desktop layout logic
                sidebar.classList.remove('-translate-x-full');
                if (sidebarBackdrop) {
                    sidebarBackdrop.classList.add('hidden');
                    sidebarBackdrop.classList.add('opacity-0');
                }

                if (isSidebarOpen) {
                    // Expand Sidebar
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-68');

                    if (sidebarHeader) {
                        sidebarHeader.classList.remove('justify-center', 'px-0');
                        sidebarHeader.classList.add('justify-between', 'px-6');
                    }

                    iconBars.classList.add('hidden-force');
                    iconChevron.classList.remove('hidden-force');

                    sidebarLogo.classList.remove('opacity-0', 'w-0', 'hidden-force');
                    sidebarLogo.classList.add('opacity-100');

                    sidebarTexts.forEach(el => {
                        el.classList.remove('hidden');
                        el.classList.remove('hidden-force');
                    });

                    tooltips.forEach(t => t.classList.add('hidden'));

                    userProfile.classList.remove('justify-center');

                } else {
                    sidebar.classList.remove('w-68');
                    sidebar.classList.add('w-20');

                    if (sidebarHeader) {
                        sidebarHeader.classList.remove('justify-between', 'px-6');
                        sidebarHeader.classList.add('justify-center', 'px-0');
                    }

                    iconBars.classList.remove('hidden-force');
                    iconChevron.classList.add('hidden-force');

                    sidebarLogo.classList.remove('opacity-100');
                    sidebarLogo.classList.add('opacity-0', 'w-0', 'hidden-force');

                    sidebarTexts.forEach(el => {
                        el.classList.add('hidden');
                        el.classList.add('hidden-force');
                    });

                    dropdownSubmenus.forEach(d => d.classList.add('hidden'));
                    document.querySelectorAll('.fa-chevron-down').forEach(i => i.classList.remove('rotate-180'));

                    tooltips.forEach(t => t.classList.remove('hidden'));

                    userProfile.classList.add('justify-center');
                }
            }
        }

        function toggleDropdown(dropdownId) {
            if (!isSidebarOpen) {
                isSidebarOpen = true;
                updateSidebarState();
                setTimeout(() => {
                    performToggle(dropdownId);
                }, 150);
            } else {
                performToggle(dropdownId);
            }
        }

        function performToggle(dropdownId) {
            const content = document.getElementById(dropdownId);
            const btn = content.previousElementSibling;
            const arrow = btn.querySelector('.fa-chevron-down');
            const icon = btn.querySelector('i:first-child');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                btn.classList.add('active-menu');
                if (icon) icon.classList.remove('opacity-60');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
                btn.classList.remove('active-menu');
                if (icon) icon.classList.add('opacity-60');
            }
        }

        const darkModeToggle = document.getElementById('dark-mode-toggle');

        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            darkModeToggle.checked = true;
        } else {
            document.documentElement.classList.remove('dark');
            darkModeToggle.checked = false;
        }

        darkModeToggle.addEventListener('change', function () {
            if (this.checked) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });

        function toggleServicesSectionSidebar(isChecked) {
            fetch('{{ route('backend.settings.toggle-services') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ show_services: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        alert(data.message);
                    }
                    
                    // If we are on the dashboard and there's a local toggle, sync its status
                    const localToggle = document.getElementById('toggle_services_btn');
                    if (localToggle) {
                        localToggle.checked = isChecked;
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling services section:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถบันทึกการตั้งค่าได้สำเร็จ'
                    });
                } else {
                    alert('ไม่สามารถบันทึกการตั้งค่าได้สำเร็จ');
                }
                document.getElementById('toggle_services_btn_sidebar').checked = !isChecked;
            });
        }

        const dateElement = document.getElementById('current-date');
        if (dateElement) {
            const now = new Date();
            dateElement.textContent = now.toDateString();
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: @json(session('success')),
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>

<!-- System Settings Modal -->
<div id="systemSettingsModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-[1050] flex items-center justify-center hidden p-4">
    @php
        $sysSettings = \Illuminate\Support\Facades\Storage::exists('settings.json') ? json_decode(\Illuminate\Support\Facades\Storage::get('settings.json'), true) : [];
        $showServices = $sysSettings['show_services'] ?? true;
        $disabledSys = $sysSettings['disabled_systems'] ?? [];
    @endphp
    <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl border border-slate-100 dark:border-zinc-800 max-w-md w-full overflow-hidden flex flex-col transform transition-all duration-300 scale-95 opacity-0 font-noto" id="systemSettingsModalContent">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center bg-gradient-to-r from-slate-50 to-white dark:from-zinc-900 dark:to-zinc-850">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-indigo-600"></i> ตั้งค่าเปิด-ปิดบริการหน้าแรก
                </h3>
                <p class="text-slate-500 text-xs mt-1">เลือกเปิด-ปิดการแสดงผลของระบบต่างๆ ในหน้าหลัก</p>
            </div>
            <button type="button" onclick="closeSystemSettingsModal()" class="btn btn-sm btn-circle btn-ghost text-slate-400 hover:text-slate-700 dark:hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Form Body -->
        <div class="pl-6 py-6 pr-4 space-y-4 max-h-[60vh] overflow-y-auto">

            <span class="text-[10px] font-black text-slate-400 dark:text-zinc-500 uppercase tracking-widest block mb-2">เลือกเปิด-ปิดใช้งานระบบย่อย</span>

            <!-- Checkbox List of systems -->
            <div class="space-y-3 mr-1.5">
                @php
                    $systemList = [
                        ['id' => 'office_supplies', 'title' => 'ระบบเบิกอุปกรณ์สำนักงาน', 'icon' => 'fa-box-open', 'desc' => 'ระบบเบิกพัสดุและสต็อกพัสดุ'],
                        ['id' => 'car_booking', 'title' => 'ระบบจองรถส่วนกลาง', 'icon' => 'fa-car-side', 'desc' => 'ระบบจองคิวรถส่วนกลางและคนขับ'],
                        ['id' => 'employee_housing', 'title' => 'ระบบบ้านพักพนักงาน', 'icon' => 'fa-building-user', 'desc' => 'ระบบสิทธิเข้าพักอาศัยพนักงาน'],
                        ['id' => 'parking_system', 'title' => 'ระบบลานจอดรถ', 'icon' => 'fa-square-parking', 'desc' => 'ระบบลานจอดรถพนักงานและแขก']
                    ];
                @endphp

                @foreach($systemList as $sys)
                <label class="flex items-center justify-between p-3.5 hover:bg-slate-50 dark:hover:bg-zinc-800/30 rounded-xl cursor-pointer border border-transparent hover:border-slate-100 dark:hover:border-zinc-800 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500">
                            <i class="fa-solid {{ $sys['icon'] }} text-sm"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $sys['title'] }}</span>
                            <p class="text-slate-400 text-[10px] mt-0.5">{{ $sys['desc'] }}</p>
                        </div>
                    </div>
                    <!-- Checked means enabled/showing (disabled = false) -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="settings_show_{{ $sys['id'] }}" class="sr-only peer" {{ !($disabledSys[$sys['id']] ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-2.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        <span class="text-[11px] font-bold ml-2 w-8 text-slate-400 dark:text-zinc-555 peer-checked:text-emerald-500 transition-colors select-none">
                            <span class="inline peer-checked:hidden">ปิด</span>
                            <span class="hidden peer-checked:inline">เปิด</span>
                        </span>
                    </label>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900/50 flex justify-end gap-2">
            <button type="button" onclick="closeSystemSettingsModal()" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 bg-transparent rounded-lg">ยกเลิก</button>
            <button type="button" onclick="saveSystemSettings()" class="px-5 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm">บันทึกการตั้งค่า</button>
        </div>
    </div>
</div>

<script>
    function openSystemSettingsModal() {
        const modal = document.getElementById('systemSettingsModal');
        const content = document.getElementById('systemSettingsModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeSystemSettingsModal() {
        const modal = document.getElementById('systemSettingsModal');
        const content = document.getElementById('systemSettingsModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function saveSystemSettings() {
        const showServices = document.getElementById('settings_show_services') ? document.getElementById('settings_show_services').checked : true;
        
        // Inverted: checked (show) -> disabled = false
        const officeSupplies = document.getElementById('settings_show_office_supplies') ? !document.getElementById('settings_show_office_supplies').checked : false;
        const carBooking = document.getElementById('settings_show_car_booking') ? !document.getElementById('settings_show_car_booking').checked : false;
        const employeeHousing = document.getElementById('settings_show_employee_housing') ? !document.getElementById('settings_show_employee_housing').checked : false;
        const parkingSystem = document.getElementById('settings_show_parking_system') ? !document.getElementById('settings_show_parking_system').checked : false;
        const centralData = document.getElementById('settings_show_central_data') ? !document.getElementById('settings_show_central_data').checked : false;

        fetch('{{ route('backend.settings.save-systems') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                show_services: showServices,
                office_supplies: officeSupplies,
                car_booking: carBooking,
                employee_housing: employeeHousing,
                parking_system: parkingSystem,
                central_data: centralData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeSystemSettingsModal();
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload page to apply new homepage settings if on welcome page
                        if (window.location.pathname === '/') {
                            window.location.reload();
                        }
                    });
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error saving settings:', error);
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: 'ไม่สามารถบันทึกการตั้งค่าได้สำเร็จ'
            });
        });
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@yield('scripts')
@stack('scripts')
</body>
</html>