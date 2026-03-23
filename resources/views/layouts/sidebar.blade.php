<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HAMS System</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Prompt', sans-serif;
        }

        /* Custom Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #121418;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #D71920;
        }

        /* Helper class to hide elements via JS */
        .hidden-force {
            display: none !important;
        }

        /* Smooth transition for sidebar width */
        #sidebar {
            transition: width 0.3s ease;
        }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        kumwell: {
                            red: '#D71920',
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
            class="w-68 flex-shrink-0 flex flex-col h-full bg-[#0B1120] text-gray-300 border-r border-[#1E2B3C] shadow-xl z-30 fixed md:relative -translate-x-full md:translate-x-0 transition-all duration-300 overflow-hidden">

            <!-- Header -->
            <div id="sidebar-header" class="h-20 flex items-center justify-between px-6 border-b border-gray-800/50">

                <div id="sidebar-logo"
                    class="flex items-center gap-3 overflow-hidden whitespace-nowrap transition-all duration-300 opacity-100">
                    <div class="w-10 h-10 rounded-xl bg-[#D71920] flex items-center justify-center flex-shrink-0">
                        <span class="font-bold text-white text-xl">H</span>
                    </div>
                    <a href="{{ route('welcome') }}">
                        <div class="flex flex-col leading-tight mt-1">
                            <span class="text-[17px] font-bold tracking-wide text-white leading-none">Kumwell</span>
                            <span
                                class="text-[10px] text-[#D71920] font-bold uppercase tracking-[0.1em] leading-none mt-1">HA
                                SYSTEM</span>
                        </div>
                    </a>
                </div>

                <button id="sidebar-toggle-btn"
                    class="p-2 rounded-lg text-gray-400 hover:text-white transition-all focus:outline-none">
                    <i id="icon-bars" class="fa-solid fa-bars text-lg hidden-force"></i>
                    <i id="icon-chevron" class="fa-solid fa-chevron-left text-sm"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto overflow-x-hidden sidebar-scroll">

                <div class="sidebar-text px-2 mb-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Main
                    Menu</div>

                <!-- <a href="#" class="group relative flex items-center px-3 py-1 rounded-xl text-gray-400 hover:bg-gradient-to-r hover:from-kumwell-red hover:to-red-700 hover:text-white hover:shadow-lg hover:shadow-red-900/30 transition-all duration-200">
                    <i id="dashboard-icon" class="fa-solid fa-chart-pie text-sm w-6 text-center group-hover:text-white transition-colors mr-3"></i>
                    <span class="sidebar-text">Dashboard</span>
                    
                    <div class="tooltip absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        Dashboard
                    </div>
                </a> -->

                <a href="{{ route('backend.welcomedatamanage') }}"
                    class="group relative flex items-center px-3 py-2 rounded-xl text-gray-400 hover:bg-gradient-to-r hover:from-kumwell-red hover:to-red-700 hover:text-white hover:shadow-lg hover:shadow-red-900/30 transition-all duration-200 {{ request()->routeIs('backend.welcomedatamanage') ? 'bg-gradient-to-r from-kumwell-red to-red-700 text-white shadow-lg' : '' }}">
                    <i id="dashboard-icon"
                        class="fa-solid fa-chart-pie text-sm w-6 text-center group-hover:text-white transition-colors mr-3"></i>
                    <span class="sidebar-text">Dashboard</span>
                    
                    <div class="tooltip absolute left-14 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        Dashboard
                    </div>
                </a>

                <div class="relative group">
                    <button onclick="toggleDropdown('dropdown-datapublic')"
                        class="w-full flex items-center justify-between px-3 py-1 rounded-xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition-all duration-200"
                        id="btn-datapublic">
                        <div class="flex items-center">
                            <i id="icon-datapublic" class="fa-solid fa-database text-sm w-6 text-center mr-3"></i>
                            <span class="sidebar-text text-left">รายงานเบิกอุปกรณ์สำนักงาน</span>
                        </div>
                        <i id="arrow-datapublic"
                            class="sidebar-text fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>

                    <div id="dropdown-datapublic" class="hidden pl-10 pr-2 py-1 space-y-1 transition-all duration-300">
                        <a href="{{ route('items.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">
                            - รายการเบิกอุปกรณ์
                        </a>
                        <a href="{{ route('datamanage.news.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">
                            - ข้อมูลข่าวสาร
                        </a>
                    </div>

                    <div
                        class="tooltip absolute left-14 top-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        ข้อมูลทั่วไป
                    </div>
                </div>

                <div class="relative group">
                    <button onclick="toggleDropdown('dropdown-hr')"
                        class="w-full flex items-center justify-between px-3 py-1 rounded-xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition-all duration-200"
                        id="btn-hr">
                        <div class="flex items-center">
                            <i id="icon-hr" class="fa-solid fa-users-gear text-sm w-6 text-center mr-3"></i>
                            <span class="sidebar-text">HR Settings</span>
                        </div>
                        <i id="arrow-hr"
                            class="sidebar-text fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>

                    <div id="dropdown-hr" class="hidden pl-10 pr-2 py-1 space-y-1 transition-all duration-300">
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">-
                            ข้อมูลพนักงาน</a>
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">-
                            ข้อมูลประเภทพนักงาน</a>
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">-
                            ข้อมูลสายงาน</a>
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">-
                            ข้อมูลฝ่าย</a>
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">-
                            ข้อมูลแผนก</a>
                    </div>
                    <div
                        class="tooltip absolute left-14 top-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        HR Settings
                    </div>
                </div>

                <div class="relative group">
                    <button onclick="toggleDropdown('dropdown-suggestion')"
                        class="w-full flex items-center justify-between px-3 py-1 rounded-xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition-all duration-200"
                        id="btn-suggestion">
                        <div class="flex items-center">
                            <i id="icon-suggestion" class="fa-solid fa-database text-sm w-6 text-center mr-3"></i>
                            <span class="sidebar-text">รายการร้องเรียน</span>
                        </div>
                        <i id="arrow-suggestion"
                            class="sidebar-text fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>

                    <div id="dropdown-suggestion" class="hidden pl-10 pr-2 py-1 space-y-1 transition-all duration-300">
                        <a href=""
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">
                            - Dashboard
                        </a>
                        <a href=""
                            class="flex justify-between items-center px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors">
                            <span>- รับเรื่องร้องเรียน</span>


                        </a>
                    </div>

                    <div
                        class="tooltip absolute left-14 top-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        ข้อมูลทั่วไป
                    </div>
                </div>

                <div class="relative group">
                    <button onclick="toggleDropdown('dropdown-bookingmeeting')"
                        class="w-full flex items-center justify-between px-3 py-1 rounded-xl text-gray-400 hover:bg-gray-800/50 hover:text-white transition-all duration-200"
                        id="btn-bookingmeeting">
                        <div class="flex items-center">
                            <i id="icon-bookingmeeting" class="fa-solid fa-door-open text-sm w-6 text-center mr-3"></i>
                            <span
                                class="sidebar-text text-left break-words whitespace-normal leading-tight">ระบบจัดการห้องประชุม</span>
                        </div>
                        <i id="arrow-bookingmeeting"
                            class="sidebar-text fa-solid fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>

                    <div id="dropdown-bookingmeeting"
                        class="hidden pl-10 pr-2 py-1 space-y-1 transition-all duration-300">
                        <a href="{{ route('backend.bookingmeeting.rooms.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors {{ request()->routeIs('backend.bookingmeeting.rooms.*') ? 'text-kumwell-red bg-gray-800/50 font-medium' : '' }}">
                            - จัดการข้อมูลห้องประชุม
                        </a>
                        <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors {{ request()->routeIs('backend.bookingmeeting.reservations.*') ? 'text-kumwell-red bg-gray-800/50 font-medium' : '' }}">
                            - รายการจองห้องประชุม
                        </a>
                        <a href="{{ route('backend.bookingmeeting.report.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm text-gray-500 hover:text-kumwell-red hover:bg-gray-800/50 transition-colors {{ request()->routeIs('backend.bookingmeeting.report.*') ? 'text-kumwell-red bg-gray-800/50 font-medium' : '' }}">
                            - รายงานจัดห้องประชุม
                        </a>
                    </div>

                    <div
                        class="tooltip absolute left-14 top-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity pointer-events-none z-50 whitespace-nowrap ml-2 shadow-md border border-gray-700 hidden">
                        ระบบจัดการห้องประชุม
                    </div>
                </div>

            </nav>

            <div class="border-t border-gray-800 p-4 pb-6">

                <div class="sidebar-text flex items-center justify-between mb-4 px-2">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Preferences</span>
                </div>

                <div
                    class="sidebar-text flex items-center justify-between px-4 py-3 rounded-2xl bg-[#121620]/40 backdrop-blur-md border border-gray-800/60 mb-4 transition-all duration-300 hover:border-kumwell-red/30 group">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-gray-800/50 flex items-center justify-center text-gray-500 group-hover:text-kumwell-red transition-colors duration-300">
                            <i class="fa-solid fa-moon text-sm"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-300">Dark Mode</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="dark-mode-toggle" class="sr-only peer">
                        <div
                            class="w-14 h-7 bg-gray-700/50 rounded-full peer-focus:ring-2 peer-focus:ring-kumwell-red/20 peer-checked:bg-kumwell-red transition-all duration-300 shadow-inner">
                            <div
                                class="absolute inset-0 flex items-center justify-between px-2 pointer-events-none opacity-50">
                                <i class="fa-solid fa-sun text-[10px] text-yellow-500"></i>
                                <i class="fa-solid fa-moon text-[10px] text-blue-200"></i>
                            </div>
                        </div>
                        <div
                            class="absolute top-1 left-1 bg-white w-5 h-5 rounded-full transition-all duration-300 peer-checked:translate-x-3.5 shadow-lg flex items-center justify-center">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-200"></div>
                        </div>
                    </label>
                </div>

                <div id="user-profile" class="flex items-center gap-3 px-2">
                    <div
                        class="w-10 h-10 rounded-full bg-[#1E2536] border border-gray-700 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="sidebar-text flex-1 min-w-0">
                        <p class="text-[13px] font-medium text-white truncate">
                            {{ Auth::user()->fullname }}
                        </p>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">
                            {{ Auth::user()->employee_code }}
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
                    <div class="text-sm text-red-500">
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

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
                btn.classList.add('bg-white/5', 'text-white');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
                btn.classList.remove('bg-white/5', 'text-white');
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('scripts')
    @stack('scripts')

</body>

</html>