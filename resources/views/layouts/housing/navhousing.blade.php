<!-- Navbar (Tailwind + DaisyUI + Font Awesome) -->
<nav
    class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-lg border-b border-red-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="h-16 flex items-center justify-between">

            <!-- Left: Brand -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div
                    class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white font-bold text-xl shadow-lg shadow-red-200 group-hover:scale-105 transition-transform duration-300">
                    K
                    <div class="absolute inset-0 rounded-full border border-white/40"></div>
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-[20px] font-black tracking-tight text-red-600 leading-none">Kumwell</span>
                    <span
                        class="text-[11px] font-semibold tracking-widest text-slate-500 uppercase leading-tight mt-0.5">HAMS</span>
                </div>
            </a>

            <!-- Right: Navigation Links -->
            <div class="hidden md:flex items-center gap-2 lg:gap-3">

                <!-- หน้าแรก -->
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-house {{ request()->routeIs('welcome') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>หน้าหลัก</span>
                </a>

                <!-- บ้านพัก -->
                <a href="{{ route('housing.welcome') }}"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('housing.welcome') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-house-chimney {{ request()->routeIs('housing.welcome') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>บ้านพัก</span>
                </a>

                <!-- ติดตามสถานะ -->
                <a href="{{ route('housing.my_requests') }}"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('housing.my_requests') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-clock-rotate-left {{ request()->routeIs('housing.my_requests') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>ติดตามสถานะ</span>
                </a>

                <!-- แบบฟอร์ม (dropdown) -->
                <div class="dropdown dropdown-hover dropdown-end">
                    <label tabindex="0"
                        class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 cursor-pointer {{ request()->routeIs('housing.request.*') || request()->routeIs('housing.agreement.*') || request()->routeIs('housing.guest.*') || request()->routeIs('housing.leave.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                        <i
                            class="fa-solid fa-file-circle-plus {{ request()->routeIs('housing.request.*') || request()->routeIs('housing.agreement.*') || request()->routeIs('housing.guest.*') || request()->routeIs('housing.leave.*') ? '' : 'text-slate-400' }}"></i>
                        <span>แบบฟอร์ม</span>
                        <i class="fa-solid fa-chevron-down text-[10px] opacity-70 ml-1"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] right-0 origin-top-right">
                        <li>
                            <a href="{{ route('housing.request.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.request.*') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                <i
                                    class="fa-solid fa-file-circle-plus w-4 text-center {{ request()->routeIs('housing.request.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                                คำขอเข้าพัก (QF-HAMS-02)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('housing.agreement.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.agreement.*') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                <i
                                    class="fa-solid fa-file-signature w-4 text-center {{ request()->routeIs('housing.agreement.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                                ข้อตกลงเข้าพัก (QF-HAMS-03)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('housing.guest.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.guest.*') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                <i
                                    class="fa-solid fa-people-arrows w-4 text-center {{ request()->routeIs('housing.guest.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                                นำญาติเข้าพัก (QF-HAMS-05)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('housing.leave.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.leave.*') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                <i
                                    class="fa-solid fa-right-from-bracket w-4 text-center {{ request()->routeIs('housing.leave.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                                คำร้องย้ายออก
                            </a>
                        </li>
                    </ul>
                </div>

                @php
                    $isHamsOrAdmin = Auth::check() && ((Auth::user()->department && Auth::user()->department->department_name === 'HAMS') || Auth::user()->employee_code === '11648');
                @endphp

                @if($isHamsOrAdmin)
                    <!-- จัดการข้อมูล (dropdown) -->
                    <div class="dropdown dropdown-hover dropdown-end">
                        <label tabindex="0"
                            class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold text-slate-600 rounded-full transition-all duration-300 hover:bg-red-50 hover:text-red-600 cursor-pointer {{ request()->routeIs('housing.management') ? 'bg-red-600 text-white shadow-md shadow-red-200' : '' }}">
                            <i
                                class="fa-solid fa-server {{ request()->routeIs('housing.management') ? '' : 'text-slate-400' }}"></i>
                            <span>จัดการ</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 ml-1"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] right-0 origin-top-right">
                            <li>
                                <a href="{{ route('housing.management') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.management') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-table-list w-4 text-center {{ request()->routeIs('housing.management') ? 'text-red-600' : 'text-red-400' }}"></i>
                                    จัดการข้อมูลทั้งหมด
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('housing.houselist') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.houselist') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-building w-4 text-center {{ request()->routeIs('housing.houselist') ? 'text-red-600' : 'text-red-400' }}"></i>
                                    รายการบ้านพัก
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

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
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-['']">
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
                            <li class="mt-1 border-t border-slate-100"></li>
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
                onclick="document.getElementById('mnav-housing').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav-housing" class="md:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house w-5 text-center {{ request()->routeIs('welcome') ? 'text-red-500' : 'text-slate-400' }}"></i>
                    หน้าหลัก
                </a>

                <a href="{{ route('housing.welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.welcome') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house-chimney w-5 text-center {{ request()->routeIs('housing.welcome') ? 'text-red-500' : 'text-slate-400' }}"></i>
                    บ้านพัก
                </a>

                <a href="{{ route('housing.my_requests') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.my_requests') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-clock-rotate-left w-5 text-center {{ request()->routeIs('housing.my_requests') ? 'text-red-500' : 'text-slate-400' }}"></i>
                    ติดตามสถานะ
                </a>

                <details class="group [&_summary::-webkit-details-marker]:hidden">
                    <summary
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium text-slate-600 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-circle-plus w-5 text-center text-slate-400"></i> แบบฟอร์ม
                        </div>
                        <i
                            class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180"></i>
                    </summary>
                    <div class="mt-1 mb-2 ml-4 pl-4 border-l-2 border-red-100 flex flex-col gap-1">
                        <a href="{{ route('housing.request.create') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-lg transition-colors {{ request()->routeIs('housing.request.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-file-circle-plus w-4 text-center {{ request()->routeIs('housing.request.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                            คำขอเข้าพัก
                        </a>
                        <a href="{{ route('housing.agreement.create') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-lg transition-colors {{ request()->routeIs('housing.agreement.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-file-signature w-4 text-center {{ request()->routeIs('housing.agreement.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                            ข้อตกลงเข้าพัก
                        </a>
                        <a href="{{ route('housing.guest.create') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-lg transition-colors {{ request()->routeIs('housing.guest.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-people-arrows w-4 text-center {{ request()->routeIs('housing.guest.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                            นำญาติเข้าพัก
                        </a>
                        <a href="{{ route('housing.leave.create') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-lg transition-colors {{ request()->routeIs('housing.leave.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-right-from-bracket w-4 text-center {{ request()->routeIs('housing.leave.*') ? 'text-red-600' : 'text-red-400' }}"></i>
                            คำร้องย้ายออก
                        </a>
                    </div>
                </details>

                @if($isHamsOrAdmin)
                    <a href="{{ route('housing.management') }}"
                        class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.management') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i
                            class="fa-solid fa-table-list w-5 text-center {{ request()->routeIs('housing.management') ? 'text-red-500' : 'text-slate-400' }}"></i>
                        จัดการข้อมูล
                    </a>
                @endif

                <div class="h-px bg-slate-100 my-2 mx-2"></div>

                @guest
                    <a href="/login"
                        class="flex items-center justify-center gap-2 px-4 py-3 text-[15px] font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-md shadow-red-200 mt-2">
                        <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ
                    </a>
                @endguest

                @if(Auth::check())
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden bg-slate-50 rounded-xl border border-slate-100 mt-2">
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
</style>