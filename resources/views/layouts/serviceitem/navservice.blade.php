<!-- Navbar (Tailwind + DaisyUI + Font Awesome) -->
<nav
    class="fixed top-0 left-0 right-0 z-[100] w-full bg-white/90 backdrop-blur-lg border-b border-red-100 shadow-sm transition-all duration-300">
    @php
        $isHamsOrAdmin = Auth::check() && (in_array(Auth::user()->role, ['admin', 'editor']) || in_array(Auth::user()->dept_id, [14, 16]));

        $toApproveCount = 0;
        $pendingPackingCount = 0;
        $checklistCount = 0;
        $cartCount = 0;
        $totalNavPendingCount = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $userId = $user->id;
            $userDept = $user->dept_id;
            $userRole = $user->role;

            // Re-calculate isHamsOrAdmin to be sure
            $isHamsOrAdmin = (in_array($userRole, ['admin', 'editor']) || in_array($userDept, [14, 16]));

            $cacheTtl = 120; // 2 minutes

            // 1. Items waiting for approval (FOR REPORTS BADGE)
            $queryToApprove = \App\Models\serviceshams\Requisitions::where('approve_status', 0)
                ->where('status', 'pending');
            if (!$isHamsOrAdmin) {
                $queryToApprove->where('approve_id', $userId);
            }
            $toApproveCount = $queryToApprove->count();

            // 2. Items ready to pack (Only for HAMS/Admin)
            if ($isHamsOrAdmin) {
                $pendingPackingCount = \App\Models\serviceshams\Requisitions::where('approve_status', 1)
                    ->where('packing_staff_status', 0)
                    ->where('status', 'pending')
                    ->count();
                $checklistCount = $pendingPackingCount;
            }

            // 3. My own pending requests (FOR PENDING LIST BADGE)
            $totalNavPendingCount = \App\Models\serviceshams\Requisitions::where('requester_id', $userId)
                ->where('status', 'pending')
                ->count();

            // 4. Cart count (Real-time to prevent mismatch)
            $cartCount = \App\Models\serviceshams\Cart_items::where('user_id', $userId)->count();
        }
    @endphp
    <div class="max-w-[90rem] mx-auto px-4 md:px-6">
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
            <div class="hidden lg:flex items-center gap-1 2xl:gap-2">

                <!-- หน้าแรก -->
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('welcome') ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600 border border-transparent' }}">
                    <i
                        class="fa-solid fa-house {{ request()->routeIs('welcome') ? 'text-white' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>หน้าหลัก</span>
                </a>

                @if($isHamsOrAdmin)

                    <a href="{{ route('serviceshams.welcomeservice') }}"
                        class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 relative {{ request()->routeIs('serviceshams.welcomeservice') ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600 border border-transparent' }}">
                        <i
                            class="fa-solid fa-square-poll-vertical {{ request()->routeIs('serviceshams.welcomeservice') ? 'text-white' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                        <span>ตรวจสอบ/เตรียมการ</span>
                        @if($pendingPackingCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm flex items-center justify-center min-w-[18px] h-[18px]">{{ $pendingPackingCount }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('items.itemsalllist') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('items.itemsalllist') ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600 border border-transparent' }}">
                    <i
                        class="fa-solid fa-boxes-stacked {{ request()->routeIs('items.itemsalllist') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>รายการอุปกรณ์</span>
                </a>

                @if(Auth::check())
                    <a href="{{ route('requisitions.reqlistpending') }}"
                        class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 relative {{ request()->routeIs('requisitions.reqlistpending') ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600 border border-transparent' }}">
                        <i
                            class="fa-solid fa-rotate {{ request()->routeIs('requisitions.reqlistpending') ? 'text-white fa-spin' : 'text-slate-400' }} text-xs"></i>
                        <span>รายการรอดำเนินการ</span>
                        @if($totalNavPendingCount > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm flex items-center justify-center min-w-[18px] h-[18px]">{{ $totalNavPendingCount }}</span>
                        @endif
                    </a>
                @endif

                @if($isHamsOrAdmin)
                    <!-- Checklist -->
                    <a href="{{ route('requisitions.reqchecklist') }}"
                        class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 relative {{ request()->routeIs('requisitions.reqchecklist') ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600 border border-transparent' }}">
                        <i class="fa-solid fa-clipboard-check {{ request()->routeIs('requisitions.reqchecklist') ? 'text-white' : 'text-slate-400' }}"></i>
                        <span>Checklist</span>
                        @if($checklistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm flex items-center justify-center min-w-[18px] h-[18px]">{{ $checklistCount }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::check() && $isHamsOrAdmin)
                    <div class="dropdown dropdown-end">
                        <label tabindex="0"
                            class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 cursor-pointer relative {{ request()->routeIs(['requisitions.dashboard', 'requisitions.reqlistall', 'items.index', 'items_type.index']) ? 'text-white bg-red-600 shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-chart-bar {{ request()->routeIs(['requisitions.dashboard', 'requisitions.reqlistall', 'items.index', 'items_type.index']) ? 'text-white' : 'text-slate-400' }}"></i>
                            <span>รายงาน</span>
                            @if($toApproveCount > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm flex items-center justify-center min-w-[18px] h-[18px]">{{ $toApproveCount }}</span>
                            @endif
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 ml-1"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-56 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-['']">
                            <li>
                                <a href="{{ route('requisitions.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-xl transition-all duration-300 {{ request()->routeIs('requisitions.dashboard') ? 'bg-red-50 text-red-600 font-bold' : 'font-medium text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-chart-line {{ request()->routeIs('requisitions.dashboard') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('requisitions.reqlistall') }}"
                                    class="flex items-center justify-between px-4 py-2.5 text-[14px] rounded-xl transition-all duration-300 {{ request()->routeIs('requisitions.reqlistall') ? 'bg-red-50 text-red-600 font-bold' : 'font-medium text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <div class="flex items-center gap-3">
                                        <i
                                            class="fa-solid fa-history {{ request()->routeIs('requisitions.reqlistall') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                        ประวัติการเบิกอุปกรณ์
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @if($toApproveCount > 0)
                                            <span class="bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                                title="รอคุณอนุมัติ">{{ $toApproveCount }}</span>
                                        @endif
                                        @if($pendingPackingCount > 0)
                                            <span
                                                class="bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                                                title="รอดำเนินการจัดอุปกรณ์">{{ $pendingPackingCount }}</span>
                                        @endif
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('items.index') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-xl transition-all duration-300 {{ request()->routeIs('items.index') ? 'bg-red-50 text-red-600 font-bold' : 'font-medium text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-box {{ request()->routeIs('items.index') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                    ข้อมูลอุปกรณ์
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('items_type.index') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-xl transition-all duration-300 {{ request()->routeIs('items_type.index') ? 'bg-red-50 text-red-600 font-bold' : 'font-medium text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-tags {{ request()->routeIs('items_type.index') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                    ข้อมูลประเภทอุปกรณ์
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
                    <div class="dropdown dropdown-end">
                        <label tabindex="0"
                            class="flex items-center gap-2 pl-2 pr-4 py-1.5 text-[14px] font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-full transition-all duration-300 hover:bg-red-50 hover:border-red-200 hover:text-red-700 cursor-pointer shadow-sm">
                            @if(Auth::user()->photo_user)
                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                    <img src="{{ asset(Auth::user()->photo_user) }}" alt="Profile"
                                        class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-red-500 to-red-600 text-white flex items-center justify-center text-xs shadow-inner">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                            <span class="max-w-[100px] truncate">{{ Auth::user()->emp_code ?? 'My Account' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-[280px] shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] z-50">
                            <li class="px-3 py-3 border-b border-slate-100 mb-0 bg-slate-50/50 rounded-t-2xl">
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
                                            class="text-[15px] font-bold text-slate-800 truncate">{{ Auth::user()->fullname ?? 'My Account' }}</span>
                                        <span
                                            class="text-[12px] text-slate-500 truncate">{{ Auth::user()->position && strtolower(Auth::user()->position) !== 'admin' ? Auth::user()->position : '' }}</span>
                                    </div>
                                </div>
                            </li>

                            <li class="menu-title px-3 py-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                รายการเบิกอุปกรณ์</li>
                            <li>
                                <a href="{{ route('requisitions.reqlistpending') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-rotate fa-spin text-orange-400 w-4 text-center"></i>
                                    รายการรอดำเนินการ
                                </a>
                            </li>
                            @if(Auth::user()->hr_status == \App\Models\User::HAMS_STATUS_ACTIVE)
                                <li>
                                    <a href="{{ route('requisitions.reqlistall') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                        <i class="fa-solid fa-list-check text-red-400 w-4 text-center"></i> รายงานทั้งหมด
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-solid fa-bookmark text-red-400 w-4 text-center"></i> คู่มือการใช้
                                </a>
                            </li>

                            <li
                                class="menu-title px-3 py-1 mt-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                บัญชีของฉัน</li>
                            <li>
                                <a href="{{ route('profileUser') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-regular fa-id-badge text-red-400 w-4 text-center"></i> โปรไฟล์
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-regular fa-bell text-red-400 w-4 text-center"></i> การแจ้งเตือน
                                </a>
                            </li>
                            <li class="mt-2 border-t border-slate-100 p-0"></li>
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

                @php
                    $realNotifs = collect();

                    if (Auth::check()) {
                        $u = Auth::user();

                        // Service Items pending requisitions
                        if (\Illuminate\Support\Facades\Schema::hasTable('requisitions')) {
                            try {
                                $reqRes = \App\Models\serviceshams\Requisitions::where('status', 'pending')
                                    ->latest()->take(5)->get();
                                foreach ($reqRes as $item) {
                                    $realNotifs->push([
                                        'title' => 'คำขอเบิกอุปกรณ์ (รหัสดำเนินการ #' . ($item->id ?? '') . ')',
                                        'desc' => 'มีรายการอุปกรณ์รอดำเนินการจัดเตรียม',
                                        'time' => $item->created_at ? $item->created_at->diffForHumans() : 'เมื่อเร็วๆ นี้',
                                        'url' => route('requisitions.reqlistpending'),
                                        'icon' => 'fa-boxes-stacked',
                                        'color' => 'bg-blue-100 text-blue-600',
                                    ]);
                                }
                            } catch (\Throwable $e) {}
                        }

                        $realNotifs = $realNotifs->take(5);
                    }
                    $notifCount = $realNotifs->count();
                @endphp

                <!-- Notification Dropdown -->
                <div class="dropdown dropdown-end">
                    <label tabindex="0" data-count="{{ $notifCount }}" onclick="markHamsNotifSeen(this, '{{ $notifCount }}')" class="hams-notif-label relative flex items-center justify-center w-9 h-9 text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-full transition-all duration-300 cursor-pointer shadow-sm border border-slate-100 hover:border-red-100" title="การแจ้งเตือน">
                        <i class="fa-regular fa-bell text-lg"></i>
                        @if($notifCount > 0)
                            <span class="notif-badge absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white shadow-sm ring-2 ring-white animate-pulse">
                                {{ $notifCount }}
                            </span>
                        @endif
                    </label>
                    <div tabindex="0" class="dropdown-content menu bg-white rounded-2xl mt-2 p-0 w-80 sm:w-96 shadow-2xl border border-slate-100 gap-0 animate-fadeIn z-[110] overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-50/80 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-[15px]">การแจ้งเตือนเบิกอุปกรณ์</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $notifCount > 0 ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-500' }}">
                                {{ $notifCount > 0 ? $notifCount . ' รายการใหม่' : 'ไม่มีใหม่' }}
                            </span>
                        </div>

                        <!-- Notification List Items -->
                        <div class="max-h-[320px] overflow-y-auto divide-y divide-slate-100 custom-scrollbar">
                            @forelse($realNotifs as $notif)
                                <a href="{{ $notif['url'] }}" class="flex items-start gap-3 p-3.5 hover:bg-red-50/50 transition-colors group">
                                    <div class="w-9 h-9 rounded-full {{ $notif['color'] }} flex items-center justify-center flex-shrink-0 text-sm font-bold shadow-sm">
                                        <i class="fa-solid {{ $notif['icon'] }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate group-hover:text-red-600">{{ $notif['title'] }}</p>
                                        <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $notif['desc'] }}</p>
                                        <span class="text-[10px] text-slate-400 mt-1 block">{{ $notif['time'] }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="py-8 text-center px-4">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2 text-lg">
                                        <i class="fa-solid fa-bell-slash"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-600">ไม่มีการแจ้งเตือนใหม่ในขณะนี้</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">ระบบจะแสดงการแจ้งเตือนเมื่อมีรายการรออนุมัติหรืออัปเดต</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer -->
                        <div class="p-3 bg-slate-50/50 border-t border-slate-100">
                            <button type="button" onclick="document.getElementById('all_notif_modal_service').showModal();" class="flex items-center justify-center w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md shadow-red-200 transition-all cursor-pointer">
                                ดูการแจ้งเตือนทั้งหมด
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal: All Notifications -->
                <dialog id="all_notif_modal_service" class="modal modal-bottom sm:modal-middle z-[200]">
                    <div class="modal-box bg-white rounded-3xl p-0 max-w-2xl overflow-hidden shadow-2xl border border-slate-100 text-left">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg backdrop-blur-sm shadow-inner">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-lg leading-tight">การแจ้งเตือนเบิกอุปกรณ์และพัสดุ</h3>
                                    <p class="text-xs text-blue-100 font-medium">รวมรายการขอเบิกอุปกรณ์และจัดเตรียมพัสดุ</p>
                                </div>
                            </div>
                            <form method="dialog">
                                <button class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-xmark text-base"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 max-h-[60vh] overflow-y-auto divide-y divide-slate-100 custom-scrollbar">
                            @forelse($realNotifs as $notif)
                                <a href="{{ $notif['url'] }}" class="flex items-start gap-4 py-4 px-3 rounded-2xl hover:bg-blue-50/60 transition-all duration-200 group">
                                    <div class="w-11 h-11 rounded-2xl {{ $notif['color'] }} flex items-center justify-center flex-shrink-0 text-base font-bold shadow-sm group-hover:scale-105 transition-transform">
                                        <i class="fa-solid {{ $notif['icon'] }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition-colors truncate">{{ $notif['title'] }}</h4>
                                            <span class="text-[11px] font-semibold text-slate-400 flex-shrink-0">{{ $notif['time'] }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $notif['desc'] }}</p>
                                        <div class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                                            <span>เปิดหน้ารายการเบิก</span>
                                            <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <i class="fa-solid fa-bell-slash"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-700">ไม่มีการแจ้งเตือนที่ค้างอยู่</h4>
                                    <p class="text-xs text-slate-400 mt-1">ทุกรายการคำขอเบิกอุปกรณ์ถูกดำเนินการจัดเตรียมเรียบร้อยแล้ว</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-xs text-slate-500 font-medium">รายการรอดำเนินการ {{ $notifCount }} รายการ</span>
                            <form method="dialog">
                                <button class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                                    ปิดหน้าต่าง
                                </button>
                            </form>
                        </div>
                    </div>
                    <form method="dialog" class="modal-backdrop bg-slate-900/50 backdrop-blur-xs">
                        <button>close</button>
                    </form>
                </dialog>
            </div>

            <!-- Mobile menu button -->
            <button
                class="lg:hidden relative flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all active:scale-95 border border-slate-200 shadow-sm z-[110]"
                onclick="document.getElementById('mnav').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-xl"></i>
                @if(Auth::check() && (isset($checklistCount) && $checklistCount > 0))
                    <span
                        class="absolute top-1 right-1 inline-flex items-center justify-center min-w-[12px] h-[12px] bg-red-600 rounded-full border border-white"></span>
                @endif
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav"
            class="lg:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house w-5 text-center {{ request()->routeIs('welcome') ? 'text-white' : 'text-slate-400' }}"></i>
                    หน้าหลัก
                </a>

                @if($isHamsOrAdmin)
                    <a href="{{ route('serviceshams.welcomeservice') }}"
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('serviceshams.welcomeservice') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-square-poll-vertical w-5 text-center {{ request()->routeIs('serviceshams.welcomeservice') ? 'text-white' : 'text-slate-400' }}"></i>
                            ตรวจสอบ/เตรียมการ
                        </div>
                        @if(isset($pendingPackingCount) && $pendingPackingCount > 0)
                            <span
                                class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $pendingPackingCount }}</span>
                        @endif
                    </a>
                @endif

                <a href="{{ route('items.itemsalllist') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('items.itemsalllist') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-boxes-stacked w-5 text-center {{ request()->routeIs('items.itemsalllist') ? 'text-white' : 'text-slate-400' }}"></i>
                    รายการอุปกรณ์
                </a>

                @if(Auth::check())
                    <a href="{{ route('requisitions.reqlistpending') }}"
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('requisitions.reqlistpending') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-rotate w-5 text-center {{ request()->routeIs('requisitions.reqlistpending') ? 'text-white fa-spin' : 'text-slate-300' }}"></i>
                            รายการรอดำเนินการ
                        </div>
                        @if(isset($totalNavPendingCount) && $totalNavPendingCount > 0)
                            <span
                                class="bg-orange-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $totalNavPendingCount }}</span>
                        @endif
                    </a>
                @endif
                @if($isHamsOrAdmin)
                    <!-- Checklist -->
                    <a href="{{ route('requisitions.reqchecklist') }}"
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('requisitions.reqchecklist') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-clipboard-check w-5 text-center {{ request()->routeIs('requisitions.reqchecklist') ? 'text-white' : 'text-slate-400' }}"></i>
                            Checklist
                        </div>
                        @if(isset($checklistCount) && $checklistCount > 0)
                            <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $checklistCount }}</span>
                        @endif
                    </a>
                @endif

                @if(Auth::check() && $isHamsOrAdmin)
                    <details class="group [&_summary::-webkit-details-marker]:hidden">
                        <summary
                            class="flex items-center justify-between px-4 py-3 text-[15px] rounded-xl cursor-pointer transition-all duration-300 {{ request()->routeIs(['requisitions.dashboard', 'requisitions.reqlistall', 'items.index', 'items_type.index']) ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'font-medium text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid fa-chart-bar w-5 text-center {{ request()->routeIs(['requisitions.dashboard', 'requisitions.reqlistall', 'items.index', 'items_type.index']) ? 'text-white' : 'text-slate-400' }}"></i>
                                รายงาน
                            </div>
                            <div class="flex items-center gap-1 mr-8">
                                @if($toApproveCount > 0)
                                    <span
                                        class="bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $toApproveCount }}</span>
                                @endif
                            </div>
                            <i
                                class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180"></i>
                        </summary>
                        <div class="mt-1 mb-2 ml-4 pl-4 border-l-2 border-red-100 flex flex-col gap-1">
                            <a href="{{ route('requisitions.dashboard') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-lg transition-all duration-300 {{ request()->routeIs('requisitions.dashboard') ? 'bg-red-50 text-red-700 font-bold border border-red-100' : 'font-medium text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                <i
                                    class="fa-solid fa-chart-line {{ request()->routeIs('requisitions.dashboard') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                Dashboard
                            </a>
                            <a href="{{ route('requisitions.reqlistall') }}"
                                class="flex items-center justify-between px-4 py-2.5 text-[14px] rounded-lg transition-all duration-300 {{ request()->routeIs('requisitions.reqlistall') ? 'bg-red-50 text-red-700 font-bold border border-red-100' : 'font-medium text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                <div class="flex items-center gap-3">
                                    <i
                                        class="fa-solid fa-history {{ request()->routeIs('requisitions.reqlistall') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                    ประวัติการเบิกอุปกรณ์
                                </div>
                                @if(isset($toApproveCount) && $toApproveCount > 0)
                                    <span
                                        class="bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $toApproveCount }}</span>
                                @endif
                            </a>
                            <a href="{{ route('items.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-lg transition-all duration-300 {{ request()->routeIs('items.index') ? 'bg-red-50 text-red-700 font-bold border border-red-100' : 'font-medium text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                <i
                                    class="fa-solid fa-box {{ request()->routeIs('items.index') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                ข้อมูลอุปกรณ์
                            </a>
                            <a href="{{ route('items_type.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] rounded-lg transition-all duration-300 {{ request()->routeIs('items_type.index') ? 'bg-red-50 text-red-700 font-bold border border-red-100' : 'font-medium text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                <i
                                    class="fa-solid fa-tags {{ request()->routeIs('items_type.index') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i>
                                ข้อมูลประเภทอุปกรณ์
                            </a>
                        </div>
                    </details>
                @endif



                <div class="h-px bg-slate-100 my-2 mx-2"></div>

                @guest
                    <a href="/login"
                        class="flex items-center justify-center gap-2 px-4 py-4 text-[15px] font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-md shadow-red-200 mt-2">
                        <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ
                    </a>
                @endguest

                @if(Auth::check())
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden bg-slate-50 rounded-xl border border-slate-100 mt-2">
                        <summary
                            class="flex items-center justify-between px-4 py-3 text-[15px] font-bold cursor-pointer transition-colors {{ request()->routeIs('profileUser') ? 'bg-red-600 text-white shadow-md shadow-red-100' : 'text-slate-700' }} rounded-xl">
                            <div class="flex items-center gap-3">
                                @if(Auth::user()->photo_user)
                                    <div class="w-10 h-10 rounded-full overflow-hidden shadow-sm">
                                        <img src="{{ asset(Auth::user()->photo_user) }}" alt="Profile"
                                            class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center text-sm shadow-inner">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                @endif
                                <div class="flex flex-col">
                                    <span class="leading-tight">{{ Auth::user()->fullname ?? 'My Account' }}</span>
                                    <span
                                        class="text-[11px] text-slate-400 font-normal leading-tight">{{ Auth::user()->position && strtolower(Auth::user()->position) !== 'admin' ? Auth::user()->position : '' }}</span>
                                </div>
                            </div>
                            <i
                                class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180 {{ request()->routeIs('profileUser') ? 'text-white' : 'text-slate-400' }}"></i>
                        </summary>
                        <div class="mt-2 mb-2 flex flex-col gap-1 px-2 pb-2">
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mt-2 mb-1">
                                รายการเบิกอุปกรณ์</div>
                            <a href="{{ route('requisitions.reqlistpending') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-rotate fa-spin text-orange-400 w-4 text-center"></i> รายการรอดำเนินการ
                            </a>
                            @if(Auth::user()->hr_status == \App\Models\User::HAMS_STATUS_ACTIVE)
                                <a href="{{ route('requisitions.reqlistall') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                    <i class="fa-solid fa-list-check text-red-400 w-4 text-center"></i> รายงานทั้งหมด
                                </a>
                            @endif
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-bookmark text-red-400 w-4 text-center"></i> คู่มือการใช้
                            </a>

                            <div class="h-px bg-slate-200 my-1 mx-2"></div>
                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mt-1 mb-1">
                                บัญชีของฉัน</div>

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
                            <a href="#"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium text-slate-600 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors">
                                <i class="fa-regular fa-bell text-red-400 w-4 text-center"></i> การแจ้งเตือน
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

@if(request()->routeIs('items.itemsalllist'))
<!-- Floating Cart Button on Right Edge -->
<a href="{{ route('cartitem.index') }}" 
   class="fixed right-0 top-24 z-[90] bg-white border border-slate-200 border-r-0 shadow-xl rounded-l-full pl-5 pr-4 py-2.5 flex items-center gap-2 text-slate-600 hover:text-red-600 hover:bg-red-50 transition-all duration-300 group">
    <div class="relative">
        <i class="fa-solid fa-cart-shopping text-xl group-hover:scale-110 transition-transform"></i>
        @if(isset($cartCount) && $cartCount > 0)
            <span class="absolute -top-1 -right-1.5 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600 border border-white"></span>
            </span>
        @endif
    </div>
    <span class="font-bold text-sm {{ (isset($cartCount) && $cartCount > 0) ? 'text-red-600' : '' }}">
        <span class="text-[12px]">ตะกร้า </span>({{ $cartCount ?? 0 }})
    </span>
</a>
@endif

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