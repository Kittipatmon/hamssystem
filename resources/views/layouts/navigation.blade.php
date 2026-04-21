<!-- Navbar (Tailwind + DaisyUI + Font Awesome) -->
<nav
    class="fixed top-0 left-0 right-0 z-50 w-full bg-white/90 backdrop-blur-lg border-b border-red-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="h-16 flex items-center justify-between">

            <!-- Left: Brand -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <!-- Red circular token -->
                <div
                    class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white font-bold text-xl shadow-lg shadow-red-200 group-hover:scale-105 transition-transform duration-300">
                    K
                    <div class="absolute inset-0 rounded-full border border-white/40"></div>
                </div>

                <!-- Brand text -->
                <div class="flex flex-col justify-center">
                    <span class="text-[20px] font-black tracking-tight text-red-600 leading-none">Kumwell</span>
                    <span
                        class="text-[11px] font-semibold tracking-widest text-slate-500 uppercase leading-tight mt-0.5">HAMS</span>
                </div>
            </a>

            <!-- Right: Navigation Links -->
            <div class="hidden xl:flex items-center gap-2 lg:gap-3">

                <!-- หน้าแรก -->
                <a href="{{ route('welcome') }}" id="nav-home"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('welcome') && !request()->has('news') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-house {{ request()->routeIs('welcome') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>หน้าหลัก</span>
                </a>
                <a href="{{ request()->routeIs('welcome') ? '#services' : route('welcome') . '#services' }}"
                    id="nav-services"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('serviceshams.*') || request()->routeIs('items.*') || request()->routeIs('requisitions.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-concierge-bell {{ request()->routeIs('serviceshams.*') || request()->routeIs('items.*') || request()->routeIs('requisitions.*') ? 'text-white' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>งานสนับสนุน</span>
                </a>
                <a href="{{ request()->routeIs('welcome') ? '#news' : route('welcome') . '#news' }}" id="nav-news"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('datamanage.news.newsalllist') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-newspaper {{ request()->routeIs('datamanage.news.newsalllist') ? 'text-white' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>ข่าวสาร/ประชาสัมพันธ์</span>
                </a>


                <!-- ประกาศ -->
                <a href="{{ request()->routeIs('welcome') ? '#announcements-list' : route('welcome') . '#announcements-list' }}"
                    id="nav-announcements"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('announcements.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-bullhorn {{ request()->routeIs('announcements.*') ? 'text-white' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>ประกาศ</span>
                </a>

                <!-- คู่มือการใช้ -->
                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->is('manual*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-bookmark {{ request()->is('manual*') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>คู่มือการใช้</span>
                </a>

                <!-- Login / Profile Divider -->
                <div class="h-6 w-px bg-slate-200 mx-1"></div>

                <!-- Login or Profile -->
                @guest
                    <a href="/login"
                        class="flex items-center gap-2 px-5 py-2 text-[14px] font-bold text-red-600 border-2 border-red-100 rounded-full transition-all duration-300 hover:bg-red-50 hover:border-red-200">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>เข้าสู่ระบบ</span>
                    </a>
                @endguest

                @if(Auth::check())
                    <div class="dropdown dropdown-end dropdown-hover">
                        <label tabindex="0"
                            class="flex items-center gap-2 pl-2 pr-4 py-1.5 text-[14px] font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-full transition-all duration-300 hover:bg-red-50 hover:border-red-200 hover:text-red-700 cursor-pointer shadow-sm">
                            <div
                                class="w-7 h-7 rounded-full overflow-hidden bg-gradient-to-tr from-red-500 to-red-600 text-white flex items-center justify-center text-xs shadow-inner">
                                @if(Auth::check() && Auth::user()->photo_user)
                                    <img src="{{ asset(Auth::user()->photo_user) }}" alt="avatar"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <span>{{ Auth::user()->emp_code }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] z-50">
                            <li class="px-4 py-3 border-b border-slate-100 mb-0 bg-slate-50/50 rounded-t-2xl">
                                <div
                                    class="flex items-center gap-3 cursor-default hover:bg-transparent px-1 p-0 focus:!bg-transparent active:!bg-transparent focus:!text-current active:!text-current">
                                    @if(Auth::check() && Auth::user()->photo_user)
                                        <div class="w-12 h-12 rounded-full ring-2 ring-red-100 overflow-hidden">
                                            <img src="{{ asset(Auth::user()->photo_user) }}" alt="Profile"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg shadow-inner ring-2 ring-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="flex flex-col flex-1 truncate">
                                        <span
                                            class="text-[15px] font-bold text-slate-800 truncate">{{ Auth::user()->fullname ?? Auth::user()->emp_code }}</span>
                                        <span
                                            class="text-[12px] text-slate-500 truncate">{{ Auth::user()->position ?? 'Employee' }}</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('profileUser') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('profileUser') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-regular fa-id-badge {{ request()->routeIs('profileUser') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i> โปรไฟล์
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-gear text-red-400 w-4 text-center"></i> การตั้งค่า
                                </a>
                            </li>
                            <li>
                                <a href="/help"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-circle-question text-red-400 w-4 text-center"></i> ช่วยเหลือ
                                </a>
                            </li>
                            <li class="mt-1 border-t border-slate-100 p-0"></li>
                            <li class="!p-0 m-0">
                                <form method="POST" action="{{ route('logout') }}" class="p-0 m-0 w-full block">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full gap-3 px-4 py-2.5 text-[14px] font-semibold text-red-600 hover:bg-red-50 rounded-none rounded-b-2xl transition-colors text-left !bg-transparent hover:!bg-red-50">
                                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> ออกจากระบบ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Mobile menu button -->
            <button
                class="xl:hidden flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all active:scale-95 border border-slate-200 shadow-sm relative z-[110]"
                onclick="document.getElementById('mnav').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav"
            class="xl:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn max-h-[75vh] overflow-y-auto custom-scrollbar relative z-[100]">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}" id="mnav-home"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house w-5 text-center {{ request()->routeIs('welcome') ? 'text-white' : 'text-slate-400' }}"></i>
                    หน้าหลัก
                </a>

                <a href="{{ request()->routeIs('welcome') ? '#services' : route('welcome') . '#services' }}" id="mnav-services"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('serviceshams.*') || request()->routeIs('items.*') || request()->routeIs('requisitions.*') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-concierge-bell w-5 text-center {{ request()->routeIs('serviceshams.*') || request()->routeIs('items.*') || request()->routeIs('requisitions.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    งานสนับสนุน
                </a>

                <a href="{{ request()->routeIs('welcome') ? '#news' : route('welcome') . '#news' }}" id="mnav-news"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('datamanage.news.newsalllist') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-newspaper w-5 text-center {{ request()->routeIs('datamanage.news.newsalllist') ? 'text-white' : 'text-slate-400' }}"></i>
                    ข่าวสาร/ประชาสัมพันธ์
                </a>

                <a href="{{ request()->routeIs('welcome') ? '#announcements-list' : route('welcome') . '#announcements-list' }}" id="mnav-announcements"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('announcements.*') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-bullhorn w-5 text-center {{ request()->routeIs('announcements.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    ประกาศ
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->is('manual*') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-bookmark w-5 text-center {{ request()->is('manual*') ? 'text-white' : 'text-slate-400' }}"></i>
                    คู่มือการใช้
                </a>

                <div class="h-px bg-slate-100 my-2 mx-2"></div>

                @guest
                    <a href="/login"
                        class="flex items-center justify-center gap-2 px-4 py-3 text-[15px] font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-md shadow-red-200 mt-2">
                        <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ
                    </a>
                @endguest

                @if(Auth::check())
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden bg-slate-50 rounded-xl border border-slate-100 mt-2 z-50">
                        <summary
                            class="flex items-center justify-between px-4 py-3 text-[15px] font-bold cursor-pointer transition-colors {{ request()->routeIs('profileUser') ? 'bg-red-600 text-white shadow-md shadow-red-100' : 'text-slate-700' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full overflow-hidden bg-red-600 text-white flex items-center justify-center text-sm shadow-inner">
                                    @if(Auth::check() && Auth::user()->photo_user)
                                        <img src="{{ asset(Auth::user()->photo_user) }}" alt="avatar"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="leading-tight">{{ Auth::user()->emp_code }}</span>
                                    <span
                                        class="text-[11px] text-slate-400 font-medium font-normal leading-tight">{{ Auth::user()->first_name ?? 'ผู้ใช้งานระบบ' }}</span>
                                </div>
                            </div>
                            <i
                                class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180 {{ request()->routeIs('profileUser') ? 'text-white' : 'text-slate-400' }}"></i>
                        </summary>
                        <div class="mt-1 mb-2 flex flex-col gap-1 px-2 pb-2">
                            <a href="{{ route('profileUser') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-regular fa-id-badge text-red-400 w-4 text-center"></i> โปรไฟล์
                            </a>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-gear text-red-400 w-4 text-center"></i> การตั้งค่า
                            </a>
                            <a href="/help"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-circle-question text-red-400 w-4 text-center"></i> ช่วยเหลือ
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="p-0 m-0">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full gap-3 px-4 py-2.5 text-[14px] font-bold text-red-600 rounded-lg hover:bg-red-50 transition-colors text-left">
                                    <i class="fa-solid fa-right-from-bracket text-red-500 w-4 text-center"></i> ออกจากระบบ
                                </button>
                            </form>
                        </div>
                    </details>
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.15s ease-out forwards;
    }

    /* Active Nav Styles */
    .nav-active {
        background-color: #dc2626 !important;
        /* bg-red-600 */
        color: white !important;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1), 0 10px 15px -3px rgba(220, 38, 38, 0.2) !important;
    }

    .nav-active i {
        color: white !important;
    }

    /* Force logout button to fill full dropdown width */
    .dropdown-content.menu li.\\!p-0 {
        padding: 0 !important;
    }

    .dropdown-content.menu li.\\!p-0>form {
        width: 100% !important;
        display: block !important;
    }

    .dropdown-content.menu li.\\!p-0>form>button {
        width: 100% !important;
        border-radius: 0 0 1rem 1rem !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navHome = document.getElementById('nav-home');
        const navNews = document.getElementById('nav-news');
        const navServices = document.getElementById('nav-services');
        const navAnnouncements = document.getElementById('nav-announcements');

        const mnavHome = document.getElementById('mnav-home');
        const mnavNews = document.getElementById('mnav-news');
        const mnavServices = document.getElementById('mnav-services');
        const mnavAnnouncements = document.getElementById('mnav-announcements');

        const newsSection = document.getElementById('news');
        const servicesSection = document.getElementById('services');
        const announcementsSection = document.getElementById('announcements-list');

        const allNavs = [
            navHome, navNews, navServices, navAnnouncements,
            mnavHome, mnavNews, mnavServices, mnavAnnouncements
        ].filter(Boolean);

        if (allNavs.length === 0) return;

        const inactiveClasses = ['text-slate-600', 'hover:bg-red-50', 'hover:text-red-600', 'hover:bg-slate-50'];
        const allRedClasses = ['bg-red-600', 'text-white', 'shadow-md', 'shadow-red-200', 'shadow-red-100', 'font-bold', 'nav-active'];
        const activeClasses = ['bg-red-600', 'text-white', 'shadow-md', 'shadow-red-200', 'font-bold', 'nav-active'];

        // Flag to prevent scroll listener from fighting with manual clicks
        let isManualAction = false;
        let manualActionTimeout;

        function setActive(el, isActive) {
            if (!el) return;
            if (isActive) {
                el.classList.add(...activeClasses);
                el.classList.remove(...inactiveClasses);
                const icon = el.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-slate-400');
                    icon.classList.add('text-white');
                }
            } else {
                el.classList.remove(...allRedClasses);
                el.classList.add(...inactiveClasses);
                const icon = el.querySelector('i');
                if (icon) {
                    icon.classList.add('text-slate-400');
                    icon.classList.remove('text-white');
                }
            }
        }

        function activateOnly(targetType) {
            // targetType is 'home', 'news', 'services', or 'announcements'
            allNavs.forEach(nav => {
                const isMatch = (targetType === 'home' && (nav === navHome || nav === mnavHome)) ||
                                (targetType === 'news' && (nav === navNews || nav === mnavNews)) ||
                                (targetType === 'services' && (nav === navServices || nav === mnavServices)) ||
                                (targetType === 'announcements' && (nav === navAnnouncements || nav === mnavAnnouncements));
                setActive(nav, isMatch);
            });
        }

        // Observe sections
        const observerOptions = {
            root: null,
            rootMargin: '-40% 0px -40% 0px',
            threshold: [0, 0.1]
        };

        const observer = new IntersectionObserver((entries) => {
            if (isManualAction) return; // Ignore observer while manual scroll is active
            
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.id === 'news') {
                        activateOnly('news');
                    } else if (entry.target.id === 'services') {
                        activateOnly('services');
                    } else if (entry.target.id === 'announcements-list') {
                        activateOnly('announcements');
                    }
                }
            });
        }, observerOptions);

        if (servicesSection) observer.observe(servicesSection);
        if (newsSection) observer.observe(newsSection);
        if (announcementsSection) observer.observe(announcementsSection);

        function updateFromHash() {
            const hash = window.location.hash;
            if (hash === '#news') {
                activateOnly('news');
            } else if (hash === '#services') {
                activateOnly('services');
            } else if (hash === '#announcements-list') {
                activateOnly('announcements');
            } else if (window.scrollY < 200) {
                activateOnly('home');
            }
        }

        // Initial state
        updateFromHash();

        // Listen for hash changes (clicking links)
        window.addEventListener('hashchange', updateFromHash);

        function handleManualClick(target) {
            isManualAction = true;
            clearTimeout(manualActionTimeout);
            activateOnly(target);
            // Allow scroll listener again after scroll animation likely finished
            manualActionTimeout = setTimeout(() => {
                isManualAction = false;
            }, 1000);
        }

        // Click handlers for immediate feedback
        [navNews, mnavNews].forEach(el => el?.addEventListener('click', () => handleManualClick('news')));
        [navServices, mnavServices].forEach(el => el?.addEventListener('click', () => handleManualClick('services')));
        [navAnnouncements, mnavAnnouncements].forEach(el => el?.addEventListener('click', () => handleManualClick('announcements')));
        [navHome, mnavHome].forEach(el => el?.addEventListener('click', () => {
            if (window.location.pathname === '/' || window.location.pathname === '/welcome') {
                handleManualClick('home');
            }
        }));

        // Top of page = Home active
        window.addEventListener('scroll', () => {
            if (isManualAction) return;
            if (window.scrollY < 100) {
                activateOnly('home');
            }
        });

        // Close mobile nav on link click with a slight delay to allow navigation/scrolling
        [mnavHome, mnavNews, mnavServices, mnavAnnouncements].forEach(el => {
            el?.addEventListener('click', () => {
                setTimeout(() => {
                    const mnav = document.getElementById('mnav');
                    if (mnav) mnav.classList.add('hidden');
                }, 300);
            });
        });
    });
</script>