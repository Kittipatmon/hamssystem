<!-- Navbar (Tailwind + DaisyUI + Font Awesome) -->
<nav class="sticky top-0 z-50 w-full backdrop-blur-md bg-white/70 dark:bg-slate-900/60 border-b border-slate-200/70 dark:border-slate-700/60 shadow-[0_6px_24px_-12px_rgba(0,0,0,.25)]">
    <div class="max-w-8xl mx-auto px-3 md:px-6">
        <div class="h-16 flex items-center justify-between">

            <!-- Left: Brand -->
            <a href="#" class="flex items-center gap-3">
                <!-- Red circular token -->
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                     bg-gradient-to-br from-[#ff6767] to-[#b61717] text-white font-semibold
                     shadow-[0_6px_14px_rgba(182,23,23,.4)] ring-1 ring-white/40">
                    K
                </span>

                <!-- Brand text -->
                <span class="leading-tight">
                    <span class="text-[20px] font-extrabold tracking-tight text-[#c31919]">Kumwell</span>
                    <sup class="ml-1 text-[10px] tracking-wider text-slate-400 align-top">HAMS</sup>
                </span>
            </a>

            <!-- Right: Pills -->
            <div class="hidden md:flex items-center gap-1">


                <!-- หน้าแรก (active) -->
                <a href="#" class="pill pill-active">
                    <i class="fa-solid fa-house"></i>
                    <span>หน้าหลัก</span>
                </a>

                <a href="{{ route('items.itemsalllist') }}" class="pill">
                    <span>อุปกรณ์</span>
                </a>

                <!-- เกี่ยวกับเรา (dropdown) -->
                <div class="dropdown dropdown-center">
                    <label tabindex="0" class="pill cursor-pointer">
                        <i class="fa-solid fa-server"></i>
                        <span>ข้อมูลทั่วไป</span>
                        <i class="fa-solid fa-chevron-down text-[11px]"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu bg-white/95 backdrop-blur-sm rounded-2xl mt-2 p-2 w-52
                     shadow-[0_10px_30px_-12px_rgba(0,0,0,.25)] border border-slate-200 gap-1">
                        <li>
                            <a href="{{ route('items.index') }}" class="pill">ข้อมูลอุปกรณ์</a>
                        </li>
                        <li>
                            <a href="{{ route('items_type.index') }}" class="pill">ข้อมูลประเภทอุปกรณ์</a>
                        </li>
                        <li>
                            
                        </li>
                    </ul>
                </div>

                <a href="#" class="pill">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Checklist</span>
                </a>

                <div class="dropdown dropdown-center">
                    <label tabindex="0" class="pill cursor-pointer">
                        <i class="fa-solid fa-server"></i>
                        <span>Reports</span>
                        <i class="fa-solid fa-chevron-down text-[11px]"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu bg-white/95 backdrop-blur-sm rounded-2xl mt-2 p-2 w-52
                     shadow-[0_10px_30px_-12px_rgba(0,0,0,.25)] border border-slate-200 gap-1">
                        <li>
                            <a href="" class="pill">
                                <i class="fa-solid fa-chart-line"></i>
                                Dashboard</a>
                        </li>
                        <li>
                            <a href="" class="pill">
                                รายงานอุปกรณ์ทั้งหมด
                            </a>
                        </li>
                    </ul>
                </div>


                <!-- คู่มือการใช้ -->
                <a href="#" class="pill">
                    <i class="fa-solid fa-bookmark"></i>
                    <span>คู่มือการใช้</span>
                </a>

                @if(Auth::check())
                    @php
                        $cartCount = \App\Models\serviceshams\Cart_items::where('user_id', Auth::id())->count();
                    @endphp
                    <a href="{{ route('cartitem.index') }}" class="pill relative">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <!-- <span>Cart</span> -->
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 text-white text-[11px] font-semibold px-1 leading-none shadow">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                @endif





                <!-- Login -->
                 @guest
                <a href="/login" class="pill">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Login</span>
                </a>
                @endguest
                @if(Auth::check())
                <div class="drawer-end">
                    <input id="my-drawer-5" type="checkbox" class="drawer-toggle" />

                    <div class="drawer-content">
                        <label for="my-drawer-5" class="pill drawer-button">
                            <i class="fa-solid fa-user"></i>
                            <span>{{ Auth::user()->employee_code }}</span>
                            <i class="fa-solid fa-chevron-down text-[11px]"></i>
                        </label>
                    </div>

                    <div class="drawer-side" style="z-index: 1000;">
                        <label for="my-drawer-5" aria-label="close sidebar" class="drawer-overlay"></label>
                        
                        <div class="p-4 w-70 bg-base-200 text-base-content">
                            <div class="flex items-center space-x-3">
                                <div class="avatar">
                                    <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="{{ Auth::user()->avatar_url ?? 'https://via.placeholder.com/150' }}" alt="User Avatar" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ Auth::user()->fullname }}</div>
                                    <div class="text-sm opacity-60">{{ Auth::user()->role ?? 'Employee' }}</div>
                                </div>
                            </div>
                        </div>

                        <ul class="menu bg-base-200 min-h-full w-70 p-4 pt-0">
                            <li><span class="menu-title">บัญชีของฉัน</span></li>
                            <li><a href="#" class="text-[13px] pill"><i class="fa-regular fa-id-badge"></i> โปรไฟล์</a></li>
                            <li><a href="#" class="text-[13px] pill"><i class="fa-solid fa-bell"></i> การแจ้งเตือน</a></li>

                            <li><span class="menu-title">การตั้งค่า</span></li>
                            <li><a href="#" class="text-[13px] pill"><i class="fa-solid fa-gear"></i> การตั้งค่าบัญชี</a></li>
                            <!-- <li>
                                <label class="text-[13px] pill cursor-pointer">
                                    <i class="fa-solid fa-circle-half-stroke"></i>
                                    สลับโหมด
                                    <input type="checkbox" class="toggle toggle-primary" id="theme-toggle" />
                                </label>
                            </li> -->
                            <li><a href="/help" class="text-[13px] pill"><i class="fa-solid fa-circle-question"></i> ช่วยเหลือ</a></li>

                            <li class="mt-1 border-t border-slate-100"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="pill">
                                    @csrf
                                    <button type="submit" class="w-full text-left text-[13px] text-error">
                                        <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- <div class="dropdown dropdown-end">
                    <label tabindex="0" class="pill cursor-pointer">
                        <i class="fa-solid fa-user"></i>
                        <span>{{ Auth::user()->employee_code }}</span>
                        <i class="fa-solid fa-chevron-down text-[11px]"></i>
                    </label>
                    <ul tabindex="0"
                        class="dropdown-content menu bg-white/95 backdrop-blur-sm rounded-2xl mt-2 p-2 w-52
                               shadow-[0_10px_30px_-12px_rgba(0,0,0,.25)] border border-slate-200">
                        <li><a href="#" class="text-[13px]"><i class="fa-regular fa-id-badge"></i> โปรไฟล์</a></li>
                        <li><a href="#" class="text-[13px]"><i class="fa-solid fa-gear"></i> การตั้งค่า</a></li>
                        <li><a href="/help" class="text-[13px]"><i class="fa-solid fa-circle-question"></i> ช่วยเหลือ</a></li>
                        <li class="mt-1 border-t border-slate-100"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left text-[13px]">
                                    <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                                </button>
                            </form>
                        </li>
                    </ul>
                </div> -->
                @endif
            </div>

            <!-- Mobile menu button -->
            <button class="md:hidden btn btn-ghost btn-sm" onclick="document.getElementById('mnav').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-slate-700"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav" class="md:hidden hidden pb-3">
            <div class="flex flex-col gap-2">
                <a class="pill pill-active justify-center"><i class="fa-solid fa-house"></i> หน้าหลัก</a>
                <details class="collapse bg-transparent">
                    <summary class="pill justify-center"><i class="fa-regular fa-circle-question"></i> เกี่ยวกับเรา</summary>
                    <div class="mt-2 ms-2 flex flex-col gap-1">
                        <a class="text-[13px] px-3 py-1 rounded-lg hover:bg-red-50"><i class="fa-regular fa-building"></i> ข้อมูลบริษัท</a>
                        <a class="text-[13px] px-3 py-1 rounded-lg hover:bg-red-50"><i class="fa-solid fa-users"></i> ทีมงาน</a>
                        <a class="text-[13px] px-3 py-1 rounded-lg hover:bg-red-50"><i class="fa-regular fa-newspaper"></i> ข่าวสาร</a>
                    </div>
                </details>
                <a class="pill justify-center"><i class="fa-regular fa-book"></i> คู่มือการใช้</a>
                <a class="pill justify-center" href="/login"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            </div>
        </div>
    </div>
</nav>