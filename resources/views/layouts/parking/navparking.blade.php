<!-- Navbar (Tailwind + Font Awesome) -->
<nav class="fixed top-0 left-0 right-0 z-[100] w-full bg-white/90 backdrop-blur-lg border-b border-red-100 shadow-sm transition-all duration-300">
    <div class="max-w-[90rem] mx-auto px-4 md:px-6">
        <div class="h-16 flex items-center justify-between">

            <!-- Left: Brand -->
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-[#b81515] to-[#901010] text-white font-bold text-xl shadow-lg shadow-red-200 group-hover:scale-105 transition-transform duration-300">
                    K
                    <div class="absolute inset-0 rounded-full border border-white/40"></div>
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-[20px] font-black tracking-tight text-[#b81515] leading-none">Kumwell</span>
                    <span class="text-[11px] font-semibold tracking-widest text-slate-500 uppercase leading-tight mt-0.5">Parking</span>
                </div>
            </a>

            <!-- Right: Navigation Links -->
            <div class="hidden md:flex items-center gap-1 2xl:gap-2">

                <!-- หน้าหลักพอร์ทัล -->
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent">
                    <i class="fa-solid fa-house text-slate-400 group-hover:text-[#b81515]"></i>
                    <span>หน้าหลัก</span>
                </a>

                @php
                    $user = Auth::user();
                    $isParkingAdmin = Auth::check() && $user->is_hams_admin;
                    
                    $pendingCount = 0;
                    $empPendingCount = 0;
                    $visPendingCount = 0;
                    if (Auth::check()) {
                        $managedDeptIds = \App\Models\Department::where('manager_id', $user->id)->pluck('id');
                        
                        if ($managedDeptIds->isNotEmpty() || $user->is_hams_admin || in_array($user->role, ['admin', 'editor'])) {
                            $employeeQuery = \App\Models\parking\EmployeeReservation::where('manager_approval', 'pending');
                            if (!$user->is_hams_admin && $user->role !== 'admin') {
                                $employeeQuery->whereIn('dept_id', $managedDeptIds);
                            }
                            $empPendingCount += $employeeQuery->count();
                        }
                        
                        if ($user->is_hams_admin) {
                            $visPendingCount += \App\Models\parking\VisitorReservation::where('manager_approval', 'pending')->count();
                            $empPendingCount += \App\Models\parking\EmployeeReservation::where('manager_approval', 'approved')
                                ->where('hams_status', 'pending')->count();
                        }
                        $pendingCount = $empPendingCount + $visPendingCount;
                    }
                @endphp
                
                @if($isParkingAdmin)

                <!-- แดชบอร์ดระบบจอดรถ -->
                <a href="{{ route('parking.dashboard') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.dashboard') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-chart-line {{ request()->routeIs('parking.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>แดชบอร์ด</span>
                </a>

                <!-- ข้อมูลรายการจอดรถ -->
                <div class="dropdown dropdown-bottom relative group">
                    <label tabindex="0" class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent cursor-pointer' }} relative">
                        <i class="fa-solid fa-folder-open {{ request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                        <span>ข้อมูลรายการจอดรถ</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-1 opacity-70"></i>
                        @if($pendingCount > 0)
                            @php
                                $isActive = request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*');
                            @endphp
                            <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isActive ? 'bg-white' : 'bg-red-400' }} opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-white border-[1.5px] border-white text-[10px] items-center justify-center font-bold shadow-sm">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                            </span>
                        @endif
                    </label>
                    <ul tabindex="0" class="dropdown-content menu p-2 shadow-xl bg-white rounded-box w-[300px] border border-red-50 z-[100] animate-fadeIn mt-1 absolute left-0">
                        <li>
                            <a href="{{ route('parking.employees.index') }}" class="flex items-center gap-3 text-[14px] font-medium text-slate-600 hover:text-[#b81515] hover:bg-red-50 rounded-lg {{ request()->routeIs('parking.employees.*') ? 'bg-red-50 text-[#b81515] font-semibold' : '' }}">
                                <i class="fa-solid fa-car-side w-4 text-center"></i> รถพนักงาน
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parking.employee_reservations.index') }}" class="flex justify-between items-center text-[14px] font-medium text-slate-600 hover:text-[#b81515] hover:bg-red-50 rounded-lg {{ request()->routeIs('parking.employee_reservations.*') ? 'bg-red-50 text-[#b81515] font-semibold' : '' }}">
                                <span class="flex items-center gap-3">
                                    <i class="fa-solid fa-building w-4 text-center"></i> คำร้องขอจอดรถพนักงาน(ในอาคาร)
                                </span>
                                @if($empPendingCount > 0)
                                    <span class="px-1.5 min-w-[20px] h-[20px] rounded-full bg-red-500 flex items-center justify-center text-[11px] font-bold text-white shadow-sm ml-2 flex-shrink-0 animate-pulse">{{ $empPendingCount > 99 ? '99+' : $empPendingCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parking.visitors.index') }}" class="flex justify-between items-center text-[14px] font-medium text-slate-600 hover:text-[#b81515] hover:bg-red-50 rounded-lg {{ request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals') ? 'bg-red-50 text-[#b81515] font-semibold' : '' }}">
                                <span class="flex items-center gap-3">
                                    <i class="fa-solid fa-users w-4 text-center"></i> จองที่จอดรถแขก
                                </span>
                                @if($visPendingCount > 0)
                                    <span class="px-1.5 min-w-[20px] h-[20px] rounded-full bg-red-500 flex items-center justify-center text-[11px] font-bold text-white shadow-sm ml-2 flex-shrink-0 animate-pulse">{{ $visPendingCount > 99 ? '99+' : $visPendingCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- แผนผังลานจอดรถ -->
                <a href="{{ route('parking.map') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.map') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-map-location-dot {{ request()->routeIs('parking.map') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>แผนผังลานจอดรถ</span>
                </a>

                {{-- <!-- แผนผังในอาคาร -->
                <a href="{{ route('parking.map.building') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.map.building') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-building {{ request()->routeIs('parking.map.building') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>แผนผังในอาคาร</span>
                </a> --}}
                @endif
                
                @if(!$isParkingAdmin)
                <!-- แผนผังลานจอดรถ (For Regular Users) -->
                <a href="{{ route('parking.map.full') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.map.full') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-map-location-dot {{ request()->routeIs('parking.map.full') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>แผนผังลานจอดรถ</span>
                </a>

                <!-- แผนผังในอาคาร (For Regular Users) -->
                <a href="{{ route('parking.map.building') }}"
                    class="flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.map.building') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-building {{ request()->routeIs('parking.map.building') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>แผนผังในอาคาร</span>
                </a>
                @endif

                @if(Auth::check())
                <!-- ติดตามอนุมัติคำขอ -->
                <a href="{{ route('parking.visitors.approvals') }}"
                    class="relative flex items-center gap-2 px-3 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('parking.visitors.approvals') ? 'text-white bg-[#b81515] shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-[#b81515] border border-transparent' }}">
                    <i class="fa-solid fa-list-check {{ request()->routeIs('parking.visitors.approvals') ? 'text-white' : 'text-slate-400 group-hover:text-[#b81515]' }}"></i>
                    <span>ติดตามอนุมัติคำขอ</span>
                    @if($pendingCount > 0)
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ request()->routeIs('parking.visitors.approvals') ? 'bg-white' : 'bg-red-400' }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-white border-[1.5px] border-white text-[10px] items-center justify-center font-bold shadow-sm">{{ $pendingCount > 99 ? '99+' : $pendingCount }}</span>
                        </span>
                    @endif
                </a>
                @endif

                <!-- Divider -->
                <div class="h-6 w-px bg-slate-200 mx-1"></div>

                @guest
                    <a href="/login"
                        class="flex items-center gap-2 px-5 py-2 text-[14px] font-bold text-[#b81515] border-2 border-red-100 rounded-full transition-all duration-300 hover:bg-red-50 hover:border-red-200">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>เข้าสู่ระบบ</span>
                    </a>
                @endguest

                @if(Auth::check())
                    <div class="dropdown dropdown-end">
                        <label tabindex="0"
                            class="flex items-center gap-2 pl-2 pr-4 py-1.5 text-[14px] font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-full transition-all duration-300 hover:bg-red-50 hover:border-red-200 hover:text-[#b81515] cursor-pointer shadow-sm">
                            @if(Auth::user()->photo_user)
                                <div class="w-8 h-8 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                    <img src="{{ asset(Auth::user()->photo_user) }}" alt="Profile" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#b81515] to-[#901010] text-white flex items-center justify-center text-xs shadow-inner">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                            <span class="max-w-[100px] truncate">{{ Auth::user()->emp_code ?? 'My Account' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 ml-1"></i>
                        </label>
                        <ul tabindex="0" class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-[280px] shadow-xl border border-red-50 gap-0 animate-fadeIn z-50">
                            <li class="px-3 py-3 border-b border-slate-100 mb-0 bg-slate-50/50 rounded-t-2xl">
                                <div class="flex items-center gap-3 cursor-default hover:bg-transparent px-1 p-0 focus:!bg-transparent active:!bg-transparent focus:!text-current active:!text-current">
                                    @if(Auth::user()->photo_user)
                                        <div class="w-12 h-12 rounded-full ring-2 ring-red-100 overflow-hidden">
                                            <img src="{{ asset(Auth::user()->photo_user) }}" alt="Profile" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-red-100 text-[#b81515] flex items-center justify-center text-lg shadow-inner ring-2 ring-white">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="flex flex-col flex-1 truncate">
                                        <span class="text-[15px] font-bold text-slate-800 truncate">{{ Auth::user()->fullname ?? 'My Account' }}</span>
                                        <span class="text-[12px] text-slate-500 truncate">{{ Auth::user()->position && strtolower(Auth::user()->position) !== 'admin' ? Auth::user()->position : '' }}</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a href="{{ route('profileUser') }}" class="flex items-center gap-3 px-3 py-2 text-[14px] font-medium text-slate-600 hover:text-[#b81515] hover:bg-red-50 rounded-xl transition-colors">
                                    <i class="fa-regular fa-id-badge text-red-400 w-4 text-center"></i> โปรไฟล์
                                </a>
                            </li>
                            <li class="mt-2 border-t border-slate-100 p-0"></li>
                            <li class="!p-0 m-0">
                                <form method="POST" action="{{ route('logout') }}" class="p-0 m-0 w-full block">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full gap-3 px-4 py-2.5 text-[14px] font-semibold text-[#b81515] hover:bg-red-50 rounded-none rounded-b-2xl transition-colors text-left !bg-transparent hover:!bg-red-50">
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

                        // Parking Visitor/Employee pending approvals
                        if (\Illuminate\Support\Facades\Schema::hasTable('visitor_reservations')) {
                            try {
                                $visRes = \App\Models\parking\VisitorReservation::where('manager_approval', 'pending')
                                    ->latest()->take(5)->get();
                                foreach ($visRes as $item) {
                                    $info = $item->car_registration ?: ($item->guest_name ?: 'ไม่ระบุข้อมูล');
                                    $realNotifs->push([
                                        'title' => 'คำขอจองที่จอดรถแขก (' . $info . ')',
                                        'desc' => 'รอการอนุมัติ / ตรวจสอบสิทธิ์ที่จอดรถ',
                                        'time' => $item->created_at ? $item->created_at->diffForHumans() : 'เมื่อเร็วๆ นี้',
                                        'url' => route('parking.visitors.approvals'),
                                        'icon' => 'fa-square-parking',
                                        'color' => 'bg-red-100 text-[#b81515]',
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
                    <label tabindex="0" data-count="{{ $notifCount }}" onclick="markHamsNotifSeen(this, '{{ $notifCount }}')" class="hams-notif-label relative flex items-center justify-center w-9 h-9 text-slate-600 hover:text-[#b81515] hover:bg-red-50 rounded-full transition-all duration-300 cursor-pointer shadow-sm border border-slate-100 hover:border-red-100" title="การแจ้งเตือน">
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
                                <span class="font-bold text-slate-800 text-[15px]">การแจ้งเตือนสิทธิ์จอดรถ</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $notifCount > 0 ? 'bg-red-100 text-[#b81515]' : 'bg-slate-100 text-slate-500' }}">
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
                                        <p class="text-xs font-bold text-slate-800 truncate group-hover:text-[#b81515]">{{ $notif['title'] }}</p>
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
                            <button type="button" onclick="document.getElementById('all_notif_modal_parking').showModal();" class="flex items-center justify-center w-full py-2.5 px-4 bg-[#b81515] hover:bg-[#901010] text-white font-bold text-xs rounded-xl shadow-md shadow-red-200 transition-all cursor-pointer">
                                ดูการแจ้งเตือนทั้งหมด
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal: All Notifications -->
                <dialog id="all_notif_modal_parking" class="modal modal-bottom sm:modal-middle z-[200]">
                    <div class="modal-box bg-white rounded-3xl p-0 max-w-2xl overflow-hidden shadow-2xl border border-slate-100 text-left">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#b81515] to-red-700 text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg backdrop-blur-sm shadow-inner">
                                    <i class="fa-solid fa-square-parking"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-lg leading-tight">การแจ้งเตือนสิทธิ์จอดรถ</h3>
                                    <p class="text-xs text-red-100 font-medium">รวมคำขอจองที่จอดรถแขกและพนักงานรออนุมัติ</p>
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
                                <a href="{{ $notif['url'] }}" class="flex items-start gap-4 py-4 px-3 rounded-2xl hover:bg-red-50/60 transition-all duration-200 group">
                                    <div class="w-11 h-11 rounded-2xl {{ $notif['color'] }} flex items-center justify-center flex-shrink-0 text-base font-bold shadow-sm group-hover:scale-105 transition-transform">
                                        <i class="fa-solid {{ $notif['icon'] }}"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-[#b81515] transition-colors truncate">{{ $notif['title'] }}</h4>
                                            <span class="text-[11px] font-semibold text-slate-400 flex-shrink-0">{{ $notif['time'] }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $notif['desc'] }}</p>
                                        <div class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-[#b81515] group-hover:translate-x-1 transition-transform">
                                            <span>เปิดหน้ารายการอนุมัติ</span>
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
                                    <p class="text-xs text-slate-400 mt-1">ทุกรายการคำขอจองที่จอดรถถูกอนุมัติเรียบร้อยแล้ว</p>
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
            <button class="md:hidden relative flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-[#b81515] transition-all active:scale-95 border border-slate-200 shadow-sm z-[110]"
                onclick="document.getElementById('mnav-parking').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav-parking" class="md:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn max-h-[80vh] overflow-y-auto">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 text-slate-600 hover:bg-slate-50">
                    <i class="fa-solid fa-house w-5 text-center text-slate-400"></i> หน้าหลักพอร์ทัล
                </a>
                
                @if($isParkingAdmin)
                <a href="{{ route('parking.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.dashboard') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('parking.dashboard') ? 'text-white' : 'text-slate-400' }}"></i> แดชบอร์ด
                </a>

                <!-- ข้อมูลรายการจอดรถ (Mobile) -->
                <details class="group" {{ request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*') ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 w-full text-left cursor-pointer list-none {{ request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-folder-open w-5 text-center {{ request()->routeIs('parking.employees.*') || (request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals')) || request()->routeIs('parking.employee_reservations.*') ? 'text-white' : 'text-slate-400' }}"></i> ข้อมูลรายการจอดรถ
                        </div>
                        <i class="fa-solid fa-chevron-down transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="flex flex-col gap-1.5 pl-4 pt-1.5">
                        <a href="{{ route('parking.employees.index') }}" class="flex items-center gap-3 px-4 py-3 text-[14px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.employees.*') ? 'bg-red-50 text-[#b81515] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-car-side w-5 text-center {{ request()->routeIs('parking.employees.*') ? 'text-[#b81515]' : 'text-slate-400' }}"></i> รถพนักงาน
                        </a>
                        <a href="{{ route('parking.employee_reservations.index') }}" class="flex items-center gap-3 px-4 py-3 text-[14px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.employee_reservations.*') ? 'bg-red-50 text-[#b81515] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-building w-5 text-center {{ request()->routeIs('parking.employee_reservations.*') ? 'text-[#b81515]' : 'text-slate-400' }}"></i> คำร้องขอจอดรถพนักงานในอาคาร(พนักงาน)
                        </a>
                        <a href="{{ route('parking.visitors.index') }}" class="flex items-center gap-3 px-4 py-3 text-[14px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals') ? 'bg-red-50 text-[#b81515] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-users w-5 text-center {{ request()->routeIs('parking.visitors.*') && !request()->routeIs('parking.visitors.approvals') ? 'text-[#b81515]' : 'text-slate-400' }}"></i> จองที่จอดรถแขก
                        </a>
                    </div>
                </details>

                <a href="{{ route('parking.map') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.map') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-map-location-dot w-5 text-center {{ request()->routeIs('parking.map') ? 'text-white' : 'text-slate-400' }}"></i> แผนผังลานจอดรถ
                </a>

                {{-- <a href="{{ route('parking.map.building') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.map.building') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-building w-5 text-center {{ request()->routeIs('parking.map.building') ? 'text-white' : 'text-slate-400' }}"></i> แผนผังในอาคาร
                </a> --}}
                @else
                <a href="{{ route('parking.map.full') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.map.full') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-map-location-dot w-5 text-center {{ request()->routeIs('parking.map.full') ? 'text-white' : 'text-slate-400' }}"></i> แผนผังลานจอดรถ
                </a>

                <a href="{{ route('parking.map.building') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.map.building') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-building w-5 text-center {{ request()->routeIs('parking.map.building') ? 'text-white' : 'text-slate-400' }}"></i> แผนผังในอาคาร
                </a>
                @endif

                @if(Auth::check())
                <a href="{{ route('parking.visitors.approvals') }}" class="relative flex items-center justify-between px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('parking.visitors.approvals') ? 'bg-[#b81515] text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-list-check w-5 text-center {{ request()->routeIs('parking.visitors.approvals') ? 'text-white' : 'text-slate-400' }}"></i> ติดตามอนุมัติคำขอ
                    </div>
                    @if($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </a>
                @endif

                <div class="h-px bg-slate-100 my-2 mx-2"></div>

                @guest
                    <a href="/login" class="flex items-center justify-center gap-2 px-4 py-4 text-[15px] font-bold text-white bg-[#b81515] hover:bg-[#901010] rounded-xl transition-colors shadow-md shadow-red-200 mt-2">
                        <i class="fa-solid fa-right-to-bracket"></i> เข้าสู่ระบบ
                    </a>
                @endguest
                
                @if(Auth::check())
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-3 text-[15px] font-bold text-[#b81515] bg-red-50 rounded-xl transition-colors mt-2">
                            <i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.15s ease-out forwards;
    }
    .dropdown-content.menu li.\!p-0 { padding: 0 !important; }
    .dropdown-content.menu li.\!p-0 > form { width: 100% !important; display: block !important; }
    .dropdown-content.menu li.\!p-0 > form > button { width: 100% !important; border-radius: 0 0 1rem 1rem !important; }
</style>
