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
            <div class="hidden md:flex items-center gap-2 lg:gap-3">

                <!-- หน้าแรก -->
                <a href="{{ route('welcome') }}" id="nav-home"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('welcome') && !request()->has('news') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-house {{ request()->routeIs('welcome') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>หน้าหลัก</span>
                </a>
                <a href="{{ request()->routeIs('welcome') ? '#services' : route('welcome') . '#services' }}"
                    id="nav-services"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 text-slate-600 hover:bg-red-50 hover:text-red-600">
                    <i class="fa-solid fa-concierge-bell text-slate-400 group-hover:text-red-500"></i>
                    <span>งานสนับสนุน</span>
                </a>
                <a href="{{ request()->routeIs('welcome') ? '#news' : route('welcome') . '#news' }}" id="nav-news"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('datamanage.news.newsalllist') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-newspaper {{ request()->routeIs('datamanage.news.newsalllist') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>ข่าวสาร/ประชาสัมพันธ์</span>
                </a>


                <!-- เกี่ยวกับเรา (dropdown) -->
                <div class="dropdown dropdown-hover dropdown-end">
                    <label tabindex="0"
                        class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 cursor-pointer {{ request()->is('about*') || request()->routeIs('datamanage.news.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                        <i
                            class="fa-regular fa-circle-question {{ request()->is('about*') || request()->routeIs('datamanage.news.*') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                        <span>เกี่ยวกับเรา</span>
                        <i class="fa-solid fa-chevron-down text-[10px] opacity-70 ml-1"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-56 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] z-50">
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                <i class="fa-regular fa-building text-red-400 w-4 text-center"></i> ข้อมูลบริษัท
                            </a>
                        </li>
                        <li>
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                <i class="fa-solid fa-users text-red-400 w-4 text-center"></i> ทีมงาน
                            </a>
                        </li>
                    </ul>
                </div>

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
                                @if(Auth::user()->photo_user)
                                    <img src="{{ asset(Auth::user()->photo_user) }}" alt="avatar"
                                        class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <span>{{ Auth::user()->employee_code }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] z-50">
                            <li class="px-4 py-3 border-b border-slate-100 mb-0 bg-slate-50/50 rounded-t-2xl">
                                <div
                                    class="flex items-center gap-3 cursor-default hover:bg-transparent px-1 p-0 focus:!bg-transparent active:!bg-transparent focus:!text-current active:!text-current">
                                    @if(Auth::user()->photo_user)
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
                                            class="text-[15px] font-bold text-slate-800 truncate">{{ Auth::user()->fullname ?? Auth::user()->employee_code }}</span>
                                        <span
                                            class="text-[12px] text-slate-500 truncate">{{ Auth::user()->position ?? 'Employee' }}</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('profileUser') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-regular fa-id-badge text-red-400 w-4 text-center"></i> โปรไฟล์
                                </a>
                            </li>
                            <li class="mt-2 border-t border-slate-100"></li>
                            <li class="p-0">
                                <form method="POST" action="{{ route('logout') }}" class="p-0 m-0 w-full">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full gap-6 px-16 py-2.5 text-[14px] font-semibold text-red-600 hover:bg-red-50 rounded-b-2xl transition-colors">
                                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> ออกจากระบบ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Mobile menu button -->
            <button
                class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-colors border border-slate-200"
                onclick="document.getElementById('mnav').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav" class="md:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house w-5 text-center {{ request()->routeIs('welcome') ? 'text-red-500' : 'text-slate-400' }}"></i>
                    หน้าหลัก
                </a>

                <details class="group [&_summary::-webkit-details-marker]:hidden" {{ request()->is('about*') || request()->routeIs('datamanage.news.*') ? 'open' : '' }}>
                    <summary
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl cursor-pointer transition-colors {{ request()->is('about*') || request()->routeIs('datamanage.news.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-regular fa-circle-question w-5 text-center {{ request()->is('about*') || request()->routeIs('datamanage.news.*') ? 'text-red-500' : 'text-slate-400' }}"></i>
                            เกี่ยวกับเรา
                        </div>
                        <i
                            class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180"></i>
                    </summary>
                    <div class="mt-1 mb-2 ml-4 pl-4 border-l-2 border-red-100 flex flex-col gap-1">
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fa-regular fa-building text-red-400 w-4 text-center"></i> ข้อมูลบริษัท
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                            <i class="fa-solid fa-users text-red-400 w-4 text-center"></i> ทีมงาน
                        </a>
                    </div>
                </details>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-colors {{ request()->is('manual*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-bookmark w-5 text-center {{ request()->is('manual*') ? 'text-red-500' : 'text-slate-400' }}"></i>
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
                            class="flex items-center justify-between px-4 py-3 text-[15px] font-bold text-slate-700 cursor-pointer transition-colors">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full overflow-hidden bg-red-600 text-white flex items-center justify-center text-sm shadow-inner">
                                    @if(Auth::user()->photo_user)
                                        <img src="{{ asset(Auth::user()->photo_user) }}" alt="avatar"
                                            class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-user"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="leading-tight">{{ Auth::user()->employee_code }}</span>
                                    <span
                                        class="text-[11px] text-slate-400 font-medium font-normal leading-tight">{{ Auth::user()->first_name ?? 'ผู้ใช้งานระบบ' }}</span>
                                </div>
                            </div>
                            <i
                                class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180 text-slate-400"></i>
                        </summary>
                        <div class="mt-1 mb-2 flex flex-col gap-1 px-2 pb-2">
                            <a href="{{ route('profileUser') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-regular fa-id-badge text-red-400 w-4 text-center"></i> โปรไฟล์
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full gap-3 px-4 py-2.5 text-[14px] font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors text-left hover:text-red-600">
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navHome = document.getElementById('nav-home');
        const navNews = document.getElementById('nav-news');
        const navServices = document.getElementById('nav-services');
        const newsSection = document.getElementById('news');
        const servicesSection = document.getElementById('services');

        const allNavs = [navHome, navNews, navServices].filter(Boolean);
        if (!navHome) return;

        const activeClasses = ['nav-active'];
        const inactiveClasses = ['text-slate-600', 'hover:bg-red-50', 'hover:text-red-600'];
        const bladeActiveClasses = ['bg-red-600', 'text-white', 'shadow-md', 'shadow-red-200'];

        function setActive(el, isActive) {
            if (!el) return;
            if (isActive) {
                el.classList.add(...activeClasses);
                el.classList.remove(...inactiveClasses);
                el.classList.remove(...bladeActiveClasses);
                const icon = el.querySelector('i');
                if (icon) icon.classList.remove('text-slate-400');
            } else {
                el.classList.remove(...activeClasses);
                el.classList.remove(...bladeActiveClasses);
                el.classList.add(...inactiveClasses);
                const icon = el.querySelector('i');
                if (icon) icon.classList.add('text-slate-400');
            }
        }

        function activateOnly(target) {
            allNavs.forEach(nav => setActive(nav, nav === target));
        }

        // Observe sections
        const observerOptions = {
            root: null,
            rootMargin: '-10% 0px -80% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.id === 'news' && navNews) {
                        activateOnly(navNews);
                    } else if (entry.target.id === 'services' && navServices) {
                        activateOnly(navServices);
                    }
                }
            });
        }, observerOptions);

        if (servicesSection) observer.observe(servicesSection);
        if (newsSection) observer.observe(newsSection);

        // Initial state
        if (window.location.hash === '#news') {
            activateOnly(navNews);
        } else if (window.location.hash === '#services') {
            activateOnly(navServices);
        } else {
            activateOnly(navHome);
        }

        // Click handlers for immediate feedback
        if (navNews) {
            navNews.addEventListener('click', () => activateOnly(navNews));
        }
        if (navServices) {
            navServices.addEventListener('click', () => activateOnly(navServices));
        }
        if (navHome) {
            navHome.addEventListener('click', () => {
                if (window.location.pathname === '/' || window.location.pathname === '/welcome') {
                    activateOnly(navHome);
                }
            });
        }

        // Top of page = Home active
        window.addEventListener('scroll', () => {
            if (window.scrollY < 200) {
                activateOnly(navHome);
            }
        });
    });
</script>