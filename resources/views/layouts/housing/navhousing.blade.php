<!-- Navbar (Tailwind + DaisyUI + Font Awesome) -->
<nav
    class="fixed top-0 left-0 right-0 z-50 w-full bg-white/90 backdrop-blur-lg border-b border-red-100 shadow-sm transition-all duration-300">
    @php
        $isHamsOrAdmin = Auth::check() && (in_array(Auth::user()->role, ['admin', 'editor']) || in_array(Auth::user()->dept_id, [14, 16]));
    @endphp
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
            <div class="hidden lg:flex items-center gap-2 lg:gap-3">

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
                <a href="{{ route('housing.committee_chart') }}"
                    class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('housing.committee_chart') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-sitemap {{ request()->routeIs('housing.committee_chart') ? '' : 'text-slate-400 group-hover:text-red-500' }}"></i>
                    <span>ผังกรรมการ</span>
                </a>

                <!-- ติดตามสถานะ -->
                @php
                    $userId = Auth::id();
                    // Count items where the user is the APPLICANT and needs to act (e.g., status 4: Returned)
                    $uRequests = \App\Models\housing\ResidenceRequest::where('user_id', $userId)->where('send_status', 4)->count();
                    $uAgreements = \App\Models\housing\ResidenceAgreement::where('user_id', $userId)->where('send_status', 4)->count();
                    $uGuests = \App\Models\housing\ResidentGuestRequest::where('user_id', $userId)->where('send_status', 4)->count();
                    $uLeaves = \App\Models\housing\ResidenceLeave::where('user_id', $userId)->where('send_status', 4)->count();
                    
                    $userActionCount = 0;
                    if (Auth::check()) {
                        // 1. My own requests needing action (e.g. status 7 = room assigned, please create agreement)
                        $myActionCount = \App\Models\housing\ResidenceRequest::where('user_id', $userId)->where('send_status', 7)->count();

                        // 2. Others' requests waiting for my approval
                        $pendingForMeCount = 0;
                        // Requests
                        $pendingForMeCount += \App\Models\housing\ResidenceRequest::where(function ($q) use ($userId) {
                            $q->where(function ($sq) use ($userId) {
                                $sq->where('send_status', 0)->where('commander_id', $userId);
                            })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                        })->count();
                        // Agreements
                        $pendingForMeCount += \App\Models\housing\ResidenceAgreement::where(function ($q) use ($userId) {
                            $q->where(function ($sq) use ($userId) {
                                $sq->where('send_status', 0)->where('commander_id', $userId);
                            })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                        })->count();
                        // Guests
                        $pendingForMeCount += \App\Models\housing\ResidentGuestRequest::where(function ($q) use ($userId) {
                            $q->where(function ($sq) use ($userId) {
                                $sq->where('send_status', 0)->where('commander_id', $userId);
                            })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                        })->count();
                        // Leaves
                        $pendingForMeCount += \App\Models\housing\ResidenceLeave::where(function ($q) use ($userId) {
                            $q->where(function ($sq) use ($userId) {
                                $sq->where('send_status', 0)->where('managerhams_id', $userId);
                            })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                        })->count();

                        // 3. Repairs assigned to me (status 1)
                        $myRepairTasks = \App\Models\housing\ResidenceRepair::where('technician_id', $userId)->where('status', 1)->count();

                        $userActionCount = $myActionCount + $pendingForMeCount + $myRepairTasks + $uRequests + $uAgreements + $uGuests + $uLeaves;
                    }
                @endphp
                <a href="{{ route('housing.my_requests') }}"
                    class="relative flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 {{ request()->routeIs('housing.my_requests') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                    <i
                        class="fa-solid fa-clock-rotate-left {{ request()->routeIs('housing.my_requests') ? '' : 'text-slate-400' }}"></i>
                    <span>ติดตามสถานะ</span>
                    @if($userActionCount > 0)
                        <span
                            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 text-[10px] text-white ring-2 ring-white shadow-md animate-pulse">
                            {{ $userActionCount }}
                        </span>
                    @endif
                </a>

                <!-- แบบฟอร์ม (dropdown) -->
                <div class="dropdown dropdown-end">
                    <label tabindex="0"
                        class="flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 cursor-pointer {{ request()->routeIs('housing.request.*') || request()->routeIs('housing.agreement.*') || request()->routeIs('housing.guest.*') || request()->routeIs('housing.leave.*') || request()->routeIs('housing.repair.*') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                        <i
                            class="fa-solid fa-file-circle-plus {{ request()->routeIs('housing.request.*') || request()->routeIs('housing.agreement.*') || request()->routeIs('housing.guest.*') || request()->routeIs('housing.leave.*') || request()->routeIs('housing.repair.*') ? '' : 'text-slate-400' }}"></i>
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
                                คำร้องย้ายออก (QF-HAMS-04)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('housing.repair.create') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.repair.create') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                <i
                                    class="fa-solid fa-screwdriver-wrench w-4 text-center {{ request()->routeIs('housing.repair.create') ? 'text-red-600' : 'text-red-400' }}"></i>
                                แจ้งซ่อมบ้านพัก
                            </a>
                    </ul>
                </div>



                @if($isHamsOrAdmin)
                    @php
                        $userId = Auth::id();
                        $user = Auth::user();
                        // For HAMS Admins, show ALL pending items. For others (if any), show only assigned.
                        if ($user && (in_array($user->role, ['admin', 'editor']) || in_array($user->dept_id, [14, 16]) || $user->is_hams_editor)) {
                            $pRequests = \App\Models\housing\ResidenceRequest::whereIn('send_status', [0, 1, 2])->count();
                            $pAgreements = \App\Models\housing\ResidenceAgreement::whereIn('send_status', [0, 1, 2])->count();
                            $pGuests = \App\Models\housing\ResidentGuestRequest::whereIn('send_status', [0, 1, 2])->count();
                            $pLeaves = \App\Models\housing\ResidenceLeave::whereIn('send_status', [0, 1, 2])->count();
                        } else {
                            $pRequests = \App\Models\housing\ResidenceRequest::where(function ($q) use ($userId) {
                                $q->where(function ($sq) use ($userId) {
                                    $sq->where('send_status', 0)->where('commander_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                            })->count();
                            $pAgreements = \App\Models\housing\ResidenceAgreement::where(function ($q) use ($userId) {
                                $q->where(function ($sq) use ($userId) {
                                    $sq->where('send_status', 0)->where('commander_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                            })->count();
                            $pGuests = \App\Models\housing\ResidentGuestRequest::where(function ($q) use ($userId) {
                                $q->where(function ($sq) use ($userId) {
                                    $sq->where('send_status', 0)->where('commander_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 1)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                            })->count();
                            $pLeaves = \App\Models\housing\ResidenceLeave::where(function ($q) use ($userId) {
                                $q->where(function ($sq) use ($userId) {
                                    $sq->where('send_status', 0)->where('managerhams_id', $userId);
                                })
                                ->orWhere(function ($sq) use ($userId) {
                                    $sq->where('send_status', 2)->where('Committee_id', $userId);
                                });
                            })->count();
                        }
                        
                        // Pending Repairs for Management (Status 0: Waiting for assignment)
                        $pRepairs = \App\Models\housing\ResidenceRepair::where('status', 0)->count();
                        
                        $totalPendingApprovals = $pRequests + $pAgreements + $pGuests + $pLeaves;
                        $totalManageBadge = $totalPendingApprovals + $pRepairs;
                    @endphp

                    <!-- จัดการข้อมูล (dropdown) -->
                    <div class="dropdown dropdown-end">
                        <label tabindex="0"
                            class="relative flex items-center gap-2 px-4 py-2 text-[14px] font-semibold rounded-full transition-all duration-300 cursor-pointer {{ request()->routeIs('housing.management') || request()->routeIs('housing.houselist') || request()->routeIs('housing.committee_chart') || request()->routeIs('housing.report') ? 'bg-red-600 text-white shadow-md shadow-red-200' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                            <i
                                class="fa-solid fa-server {{ request()->routeIs('housing.management') || request()->routeIs('housing.houselist') || request()->routeIs('housing.committee_chart') || request()->routeIs('housing.report') ? '' : 'text-slate-400' }}"></i>
                            <span>จัดการ</span>
                            <i class="fa-solid fa-chevron-down text-[10px] opacity-70 ml-1"></i>
                            
                            @if($totalManageBadge > 0)
                                <span
                                    class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white ring-2 ring-white shadow-md animate-pulse">
                                    {{ $totalManageBadge }}
                                </span>
                            @endif
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-white rounded-2xl mt-0 translate-y-1 p-0 w-64 shadow-xl border border-red-50 gap-0 animate-fadeIn before:absolute before:-top-4 before:left-0 before:w-full before:h-4 before:content-[''] right-0 origin-top-right">
                            <li>
                                <a href="{{ route('housing.management') }}"
                                    class="flex items-center justify-between px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.management') && (!request()->filled('tab') || request()->get('tab') == 'requests') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <div class="flex items-center gap-3">
                                        <i
                                            class="fa-solid fa-table-list w-4 text-center {{ request()->routeIs('housing.management') && (!request()->filled('tab') || request()->get('tab') == 'requests') ? 'text-red-600' : 'text-red-400' }}"></i>
                                        จัดการข้อมูลทั้งหมด
                                    </div>
                                    @if($totalPendingApprovals > 0)
                                        <span class="px-2 py-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] text-center shadow-sm">
                                            {{ $totalPendingApprovals }}
                                        </span>
                                    @endif
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
                            <li>
                                <a href="{{ route('housing.committee_chart') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.committee_chart') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-sitemap w-4 text-center {{ request()->routeIs('housing.committee_chart') ? 'text-red-600' : 'text-red-400' }}"></i>
                                    ผังกรรมการบ้านพัก
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('housing.management', ['tab' => 'repairs']) }}"
                                    class="flex items-center justify-between px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->get('tab') == 'repairs' ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <div class="flex items-center gap-3">
                                        <i
                                            class="fa-solid fa-screwdriver-wrench w-4 text-center {{ request()->get('tab') == 'repairs' ? 'text-red-600' : 'text-red-400' }}"></i>
                                        จัดการงานซ่อม
                                    </div>
                                    @if($pRepairs > 0)
                                        <span class="px-2 py-0.5 bg-blue-500 text-white text-[10px] font-bold rounded-full min-w-[18px] text-center shadow-sm">
                                            {{ $pRepairs }}
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('housing.report') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-xl transition-colors {{ request()->routeIs('housing.report') ? 'bg-red-50 text-red-600' : 'text-slate-600 hover:text-red-600 hover:bg-red-50' }}">
                                    <i
                                        class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('housing.report') ? 'text-red-600' : 'text-red-400' }}"></i>
                                    สรุปรายงาน
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
                            <div
                                class="w-7 h-7 rounded-full overflow-hidden bg-gradient-to-tr from-red-500 to-red-600 text-white flex items-center justify-center text-xs shadow-inner">
                                @if(Auth::user()->photo_user)
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

                @php
                    $realNotifs = collect();

                    if (Auth::check()) {
                        $u = Auth::user();

                        // Housing requests pending
                        if (\Illuminate\Support\Facades\Schema::hasTable('residence_requests')) {
                            try {
                                $houseRes = \App\Models\housing\ResidenceRequest::whereIn('status', ['pending', '0', 0])
                                    ->latest()->take(5)->get();
                                foreach ($houseRes as $item) {
                                    $realNotifs->push([
                                        'title' => 'คำร้องขอสวัสดิการบ้านพัก',
                                        'desc' => 'มีคำร้องขอเข้าพักสวัสดิการรอดำเนินการ',
                                        'time' => $item->created_at ? $item->created_at->diffForHumans() : 'เมื่อเร็วๆ นี้',
                                        'url' => route('housing.welcomehousing'),
                                        'icon' => 'fa-house-user',
                                        'color' => 'bg-emerald-100 text-emerald-600',
                                    ]);
                                }
                            } catch (\Throwable $e) {}
                        }

                        if ($realNotifs->isEmpty() && \Illuminate\Support\Facades\Schema::hasTable('visitor_reservations')) {
                            try {
                                $visRes = \App\Models\parking\VisitorReservation::where('manager_approval', 'pending')
                                    ->latest()->take(3)->get();
                                foreach ($visRes as $item) {
                                    $info = $item->car_registration ?: ($item->guest_name ?: 'ไม่ระบุข้อมูล');
                                    $realNotifs->push([
                                        'title' => 'คำขอจองที่จอดรถแขก (' . $info . ')',
                                        'desc' => 'รอการอนุมัติ / ตรวจสอบสิทธิ์ที่จอดรถ',
                                        'time' => $item->created_at ? $item->created_at->diffForHumans() : 'เมื่อเร็วๆ นี้',
                                        'url' => route('parking.visitors.approvals'),
                                        'icon' => 'fa-square-parking',
                                        'color' => 'bg-red-100 text-red-600',
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
                                <span class="font-bold text-slate-800 text-[15px]">การแจ้งเตือนบ้านพัก</span>
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
                            <button type="button" onclick="document.getElementById('all_notif_modal_housing').showModal();" class="flex items-center justify-center w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md shadow-red-200 transition-all cursor-pointer">
                                ดูการแจ้งเตือนทั้งหมด
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal: All Notifications -->
                <dialog id="all_notif_modal_housing" class="modal modal-bottom sm:modal-middle z-[200]">
                    <div class="modal-box bg-white rounded-3xl p-0 max-w-2xl overflow-hidden shadow-2xl border border-slate-100 text-left">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg backdrop-blur-sm shadow-inner">
                                    <i class="fa-solid fa-house-user"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-lg leading-tight">การแจ้งเตือนสวัสดิการบ้านพัก</h3>
                                    <p class="text-xs text-red-100 font-medium">รวมคำขอเข้าพักและอนุมัติสวัสดิการบ้านพัก</p>
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
                                            <h4 class="text-xs font-bold text-slate-800 group-hover:text-red-600 transition-colors truncate">{{ $notif['title'] }}</h4>
                                            <span class="text-[11px] font-semibold text-slate-400 flex-shrink-0">{{ $notif['time'] }}</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $notif['desc'] }}</p>
                                        <div class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold text-red-600 group-hover:translate-x-1 transition-transform">
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
                                    <p class="text-xs text-slate-400 mt-1">ทุกรายการคำขอจองสวัสดิการถูกดำเนินการเรียบร้อยแล้ว</p>
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
                class="lg:hidden flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all active:scale-95 border border-slate-200 shadow-sm relative z-[110]"
                onclick="document.getElementById('mnav-housing').classList.toggle('hidden')">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile nav -->
        <div id="mnav-housing" class="lg:hidden hidden pb-4 pt-2 border-t border-slate-100 animate-fadeIn max-h-[75vh] overflow-y-auto custom-scrollbar">
            <div class="flex flex-col gap-1.5 px-2">
                <a href="{{ route('welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('welcome') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house w-5 text-center {{ request()->routeIs('welcome') ? 'text-white' : 'text-slate-400' }}"></i>
                    หน้าหลัก
                </a>

                <a href="{{ route('housing.welcome') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.welcome') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-house-chimney w-5 text-center {{ request()->routeIs('housing.welcome') ? 'text-white' : 'text-slate-400' }}"></i>
                    บ้านพัก
                </a>

                <a href="{{ route('housing.my_requests') }}"
                    class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.my_requests') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i
                        class="fa-solid fa-clock-rotate-left w-5 text-center {{ request()->routeIs('housing.my_requests') ? 'text-white' : 'text-slate-400' }}"></i>
                    ติดตามสถานะ
                </a>

                <details class="group [&_summary::-webkit-details-marker]:hidden" {{ request()->routeIs(['housing.request.*', 'housing.agreement.*', 'housing.guest.*', 'housing.leave.*', 'housing.repair.*']) ? 'open' : '' }}>
                    <summary
                        class="flex items-center justify-between px-4 py-3 text-[15px] font-medium transition-all duration-300 {{ request()->routeIs(['housing.request.*', 'housing.agreement.*', 'housing.guest.*', 'housing.leave.*', 'housing.repair.*']) ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }} rounded-xl cursor-pointer">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-file-circle-plus w-5 text-center {{ request()->routeIs(['housing.request.*', 'housing.agreement.*', 'housing.guest.*', 'housing.leave.*', 'housing.repair.*']) ? 'text-white' : 'text-slate-400' }}"></i> แบบฟอร์ม
                        </div>
                        <i
                            class="fa-solid fa-chevron-down text-xs transition-transform duration-300 group-open:-rotate-180 {{ request()->routeIs(['housing.request.*', 'housing.agreement.*', 'housing.guest.*', 'housing.leave.*', 'housing.repair.*']) ? 'text-white' : '' }}"></i>
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
                        class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.management') || request()->routeIs('housing.houselist') || request()->routeIs('housing.committee_chart') || request()->routeIs('housing.report') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i
                            class="fa-solid fa-server w-5 text-center {{ request()->routeIs('housing.management') || request()->routeIs('housing.houselist') || request()->routeIs('housing.committee_chart') || request()->routeIs('housing.report') ? 'text-white' : 'text-slate-400' }}"></i>
                        จัดการข้อมูล
                    </a>
                    <a href="{{ route('housing.committee_chart') }}"
                        class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium rounded-xl transition-all duration-300 {{ request()->routeIs('housing.committee_chart') ? 'bg-red-600 text-white font-bold shadow-md shadow-red-100' : 'text-slate-600 hover:bg-slate-50' }}">
                        <i
                            class="fa-solid fa-sitemap w-5 text-center {{ request()->routeIs('housing.committee_chart') ? 'text-white' : 'text-slate-400' }}"></i>
                        ผังกรรมการบ้านพัก
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
                            class="flex items-center justify-between px-4 py-3 text-[15px] font-bold cursor-pointer transition-colors {{ request()->routeIs('profileUser') ? 'bg-red-600 text-white shadow-md shadow-red-100' : 'text-slate-700' }} rounded-xl">
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
                                class="flex items-center gap-3 px-4 py-2.5 text-[14px] font-medium rounded-lg transition-colors {{ request()->routeIs('profileUser') ? 'bg-red-50 text-red-600 font-bold' : 'text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                                <i
                                    class="fa-regular fa-id-badge {{ request()->routeIs('profileUser') ? 'text-red-600' : 'text-red-400' }} w-4 text-center"></i> โปรไฟล์
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

    /* Force logout button to fill full dropdown width */
    .dropdown-content.menu li.\\!p-0 {
        padding: 0 !important;
    }
    .dropdown-content.menu li.\\!p-0 > form {
        width: 100% !important;
        display: block !important;
    }
    .dropdown-content.menu li.\\!p-0 > form > button {
        width: 100% !important;
        border-radius: 0 0 1rem 1rem !important;
    }
</style>
