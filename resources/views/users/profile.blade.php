@extends('layouts.app')

@section('content')
    <div class="min-h-screen font-sans bg-gray-50 dark:bg-slate-950"
        style="background: linear-gradient(160deg, #fff1f1 0%, #fff 55%, #f5f5f5 100%);">

        <!-- ===== HERO BANNER (soft pink-red gradient, compact) ===== -->
        <div class="relative overflow-hidden"
            style="height: 200px; background: linear-gradient(135deg, #e53e3e 0%, #c53030 40%, #9b2c2c 100%);">
            <!-- Soft light overlay -->
            <div class="absolute inset-0"
                style="background: radial-gradient(ellipse at 70% 50%, rgba(255,80,80,0.35) 0%, transparent 65%);"></div>

            <!-- Floating blobs -->
            <div class="absolute rounded-full animate-blob-a"
                style="width:280px;height:280px;top:-40px;right:-60px;background:rgba(255,255,255,0.07);filter:blur(50px);">
            </div>
            <div class="absolute rounded-full animate-blob-b"
                style="width:200px;height:200px;bottom:-80px;left:-30px;background:rgba(0,0,0,0.12);filter:blur(60px);">
            </div>
            <div class="absolute rounded-full animate-blob-c"
                style="width:160px;height:160px;top:20px;left:30%;background:rgba(255,255,255,0.05);filter:blur(40px);">
            </div>

            <!-- Floating border shapes -->
            <div class="absolute border-2 rounded-3xl animate-shape-a"
                style="width:90px;height:90px;top:18%;left:5%;border-color:rgba(255,255,255,0.18);transform:rotate(18deg);">
            </div>
            <div class="absolute border rounded-[2rem] animate-shape-b"
                style="width:120px;height:120px;top:10%;right:12%;border-color:rgba(255,255,255,0.1);transform:rotate(-12deg);">
            </div>
            <div class="absolute border-2 rounded-xl animate-shape-c"
                style="width:50px;height:50px;bottom:20%;left:20%;border-color:rgba(255,255,255,0.2);transform:rotate(35deg);">
            </div>

            <!-- Shimmer sweep -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute h-full animate-shimmer"
                    style="width:50%;top:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,0.06),transparent);transform:skewX(-20deg);">
                </div>
            </div>

            <!-- Canvas stardust -->
            <canvas id="hero-canvas" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index:5;"></canvas>

            <!-- Breadcrumb -->
            <div class="absolute top-6 left-8 z-10 hidden md:flex items-center gap-3"
                style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.3em;color:rgba(255,255,255,0.5);">
                <span>Kumwell HAMS</span>
                <span style="width:5px;height:5px;background:rgba(255,255,255,0.3);border-radius:50%;"></span>
                <span style="color:rgba(255,255,255,0.9);">Employee Profile</span>
            </div>
        </div>

        <!-- ===== MAIN WRAPPER (pulled up over the hero) ===== -->
        <div class="max-w-5xl mx-auto px-4 sm:px-6" style="margin-top:-80px;position:relative;z-index:20;">

            <!-- ===== PROFILE HEADER CARD ===== -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 mb-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                    <!-- Avatar -->
                    <div class="relative shrink-0 group">
                        <div
                            class="w-36 h-36 rounded-[1.5rem] overflow-hidden border-4 border-white shadow-lg bg-gray-100 dark:bg-slate-800 ring-4 ring-gray-50 dark:ring-slate-900">
                            @php $avatar = $user->photo_user; @endphp
                            @if($avatar)
                                <img id="profile-image" src="{{ asset($avatar) }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @else
                                <div id="profile-placeholder"
                                    class="w-full h-full flex items-center justify-center text-gray-300 dark:text-slate-600">
                                    <i class="fa-solid fa-user text-5xl"></i>
                                </div>
                            @endif
                        </div>
                        @php $inputId = 'avatar-' . ($user->id ?? 'me'); @endphp
                        <label for="{{ $inputId }}"
                            class="absolute -bottom-2 -right-2 w-10 h-10 bg-white dark:bg-slate-700 rounded-xl shadow-md border border-gray-200 dark:border-slate-600 flex items-center justify-center cursor-pointer hover:bg-gray-50 transition z-10">
                            <i class="fa-solid fa-camera text-gray-500 dark:text-gray-300 text-sm"></i>
                            <input id="{{ $inputId }}" type="file" accept="image/*" class="hidden"
                                onchange="uploadAvatar(this)">
                        </label>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left space-y-3 pt-1">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl text-[10px] font-bold uppercase tracking-widest border"
                            style="background:#fff1f1;border-color:#fecaca;color:#b91c1c;">
                            <span
                                class="bg-red-500 text-white px-2 py-0.5 rounded-lg">{{ strtoupper($user->usertype->description ?? 'Executive') }}</span>
                        </div>

                        <!-- Name -->
                        <h1
                            class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight leading-tight">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </h1>

                        <!-- Meta row -->
                        <div
                            class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-sm font-semibold text-gray-500 dark:text-slate-400">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-id-card" style="color:#b91c1c;opacity:.8;"></i>
                                <span>{{ $user->employee_code }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-briefcase" style="color:#b91c1c;opacity:.7;"></i>
                                <span>{{ $user->position ?: 'วิศวกรอิเล็กทรอนิกส์' }}</span>
                            </div>
                            <div class="flex items-center gap-2" style="color:#16a34a;">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="text-[11px] font-black uppercase tracking-widest">Active</span>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Button -->
                    <div class="shrink-0 pt-1">
                        <button
                            class="px-8 py-3 rounded-full font-bold text-xs uppercase tracking-widest text-white shadow-lg hover:scale-105 active:scale-95 transition-all"
                            style="background:#1e293b;letter-spacing:.18em;">
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- ===== BOTTOM GRID ===== -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- LEFT COLUMN -->
                <div class="lg:col-span-4 space-y-6">

                    <!-- Work Statistics -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl p-7 shadow-sm border border-gray-100 dark:border-slate-800">
                        <div
                            class="flex items-center gap-3 mb-7 text-gray-900 dark:text-white font-bold uppercase text-xs tracking-widest">
                            <i class="fa-solid fa-clock-rotate-left" style="color:#dc2626;"></i>
                            <span>Work Statistics</span>
                        </div>

                        @php
                            $start = \Carbon\Carbon::parse($user->startwork_date ?? now());
                            $diff = $start->diff(\Carbon\Carbon::now());
                            $months = ($diff->y * 12) + $diff->m;
                        @endphp

                        <!-- Large Year Number -->
                        <div class="text-center py-4 mb-4">
                            <div class="font-bold tabular-nums leading-none"
                                style="font-size:5rem;color:#dc2626;text-shadow:0 2px 12px rgba(220,38,38,0.18);">
                                {{ $diff->y }}
                            </div>
                            <div class="mt-1 text-[10px] font-bold uppercase tracking-widest"
                                style="color:#9ca3af;letter-spacing:.25em;">Years in Service</div>
                        </div>

                        <div class="space-y-4">
                            <div
                                class="flex items-center gap-4 p-3 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700">
                                <div
                                    class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500 shrink-0">
                                    <i class="fa-solid fa-calendar-day text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Started
                                        Date</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $start->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-4 p-3 rounded-2xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700">
                                <div
                                    class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 shrink-0">
                                    <i class="fa-solid fa-hourglass-half text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-0.5">Current
                                        Status</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $months }} Months
                                        employed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Department (solid red) -->
                    <div class="rounded-3xl p-7 text-white shadow-xl relative overflow-hidden"
                        style="background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);">
                        <!-- Ghost building icon -->
                        <div class="absolute -right-4 -bottom-4 opacity-10 pointer-events-none"
                            style="font-size:9rem;line-height:1;">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div class="relative z-10 space-y-4">
                            <p class="text-[10px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.6);">
                                Primary Department</p>
                            <h4 class="text-3xl font-bold tracking-tight leading-tight">
                                {{ optional($user->department)->department_name ?? 'ICT' }}
                            </h4>
                            <div class="flex items-center gap-2 rounded-2xl px-4 py-3 border"
                                style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                                <span
                                    class="text-xs font-bold uppercase tracking-wide">{{ $user->workplace ?: 'สนง.ใหญ่' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Tab Bar -->
                    <div class="border-b border-gray-200 dark:border-slate-800 flex gap-8 px-1">
                        <button class="pb-4 text-[11px] font-bold uppercase tracking-widest relative"
                            style="color:#dc2626;border-bottom:2.5px solid #dc2626;">
                            Information
                        </button>
                        <button
                            class="pb-4 text-[11px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition border-b-2 border-transparent">
                            Career Path
                        </button>
                    </div>

                    <!-- Info Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <!-- Personal Information -->
                        <div
                            class="bg-white dark:bg-slate-900 rounded-3xl p-7 shadow-sm border border-gray-100 dark:border-slate-800">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-1 h-6 rounded-full" style="background:#dc2626;"></div>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white">Personal Information</h4>
                            </div>
                            <div class="space-y-7">
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Employee
                                        Code</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->employee_code }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Gender
                                    </p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->sex ?? 'ชาย' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Employee
                                        Type</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $user->employee_type ?? 'รายเดือน' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Organizational Details -->
                        <div
                            class="bg-white dark:bg-slate-900 rounded-3xl p-7 shadow-sm border border-gray-100 dark:border-slate-800">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-1 h-6 rounded-full" style="background:#dc2626;"></div>
                                <h4 class="text-base font-bold text-gray-900 dark:text-white">Organizational Details</h4>
                            </div>
                            <div class="space-y-7">
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Section
                                        Code</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ optional($user->section)->section_code ?? 'CAO' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">Division
                                    </p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ optional($user->division)->division_name ?? 'ICT' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mb-1.5">
                                        Department</p>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ optional($user->department)->department_name ?? 'ICT' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Corporate Classification -->
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl p-7 shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-inner"
                            style="background:#fff1f1;border:1px solid #fecaca;">
                            <i class="fa-solid fa-shield-halved text-2xl" style="color:#dc2626;"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-900 dark:text-white mb-1.5">Corporate Classification</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                                Current HR Level: <span class="font-bold"
                                    style="color:#dc2626;">{{ $user->usertype->description ?? 'Standard' }}</span>.
                                This information is managed by the Human Resources department specialized for Kumwell
                                Corporation.
                            </p>
                        </div>
                    </div>
                </div>

            </div><!-- end grid -->
        </div><!-- end wrapper -->

    </div><!-- end page -->

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            /* Orb blobs */
            @keyframes blob-a {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                50% {
                    transform: translate(-20px, 15px) scale(1.1);
                }
            }

            @keyframes blob-b {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                50% {
                    transform: translate(15px, -15px) scale(1.08);
                }
            }

            @keyframes blob-c {

                0%,
                100% {
                    transform: translate(0, 0) scale(1);
                }

                50% {
                    transform: translate(10px, 20px) scale(1.15);
                }
            }

            .animate-blob-a {
                animation: blob-a 10s ease-in-out infinite;
            }

            .animate-blob-b {
                animation: blob-b 8s ease-in-out infinite 1s;
            }

            .animate-blob-c {
                animation: blob-c 12s ease-in-out infinite 2s;
            }

            /* Floating shapes */
            @keyframes shape-a {

                0%,
                100% {
                    transform: rotate(18deg) translateY(0);
                }

                50% {
                    transform: rotate(24deg) translateY(-15px);
                }
            }

            @keyframes shape-b {

                0%,
                100% {
                    transform: rotate(-12deg) translateY(0);
                }

                50% {
                    transform: rotate(-18deg) translateY(12px);
                }
            }

            @keyframes shape-c {

                0%,
                100% {
                    transform: rotate(35deg) translateY(0);
                }

                50% {
                    transform: rotate(42deg) translateY(-10px);
                }
            }

            .animate-shape-a {
                animation: shape-a 8s ease-in-out infinite;
            }

            .animate-shape-b {
                animation: shape-b 10s ease-in-out infinite 1.5s;
            }

            .animate-shape-c {
                animation: shape-c 7s ease-in-out infinite 3s;
            }

            /* Shimmer */
            @keyframes shimmer {
                0% {
                    left: -120%;
                }

                100% {
                    left: 180%;
                }
            }

            .animate-shimmer {
                animation: shimmer 7s ease-in-out infinite 1s;
            }
        </style>

        <script>
            /* ── Canvas star-dust particles ── */
            (function () {
                const c = document.getElementById('hero-canvas');
                if (!c) return;
                const ctx = c.getContext('2d');
                const resize = () => { c.width = c.offsetWidth; c.height = c.offsetHeight; };
                resize();
                window.addEventListener('resize', resize);

                const rnd = (a, b) => Math.random() * (b - a) + a;
                const dots = Array.from({ length: 70 }, () => ({
                    x: rnd(0, c.width),
                    y: rnd(0, c.height),
                    r: rnd(.4, 2.2),
                    a: rnd(.3, .9),
                    vx: rnd(-.1, .1),
                    vy: rnd(-.4, -.08),
                    life: Math.random(),
                    d: rnd(.003, .008)
                }));
                (function loop() {
                    ctx.clearRect(0, 0, c.width, c.height);
                    dots.forEach(p => {
                        p.x += p.vx; p.y += p.vy; p.life -= p.d;
                        if (p.life <= 0) { p.x = rnd(0, c.width); p.y = rnd(0, c.height); p.life = 1; }
                        ctx.beginPath();
                        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(255,255,255,${p.a * p.life})`;
                        ctx.fill();
                    });
                    requestAnimationFrame(loop);
                })();
            })();

            /* ── Avatar Upload ── */
            function uploadAvatar(input) {
                if (!input.files || !input.files[0]) return;
                const file = input.files[0];
                if (!file.type.match('image.*')) {
                    Swal.fire({ icon: 'error', title: 'Invalid File', text: 'Please select an image.', confirmButtonColor: '#dc2626' });
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({ icon: 'error', title: 'Too Large', text: 'Max 2MB.', confirmButtonColor: '#dc2626' });
                    return;
                }
                const fd = new FormData();
                fd.append('avatar', file);
                Swal.fire({ title: 'Uploading…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                fetch('{{ route("users.update_avatar") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: fd
                })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            const img = document.getElementById('profile-image');
                            const ph = document.getElementById('profile-placeholder');
                            if (img) { img.src = d.avatar_url; }
                            else if (ph) {
                                const ni = document.createElement('img');
                                ni.id = 'profile-image'; ni.src = d.avatar_url; ni.alt = 'Avatar';
                                ni.className = 'w-full h-full object-cover';
                                ph.parentNode.replaceChild(ni, ph);
                            }
                            Swal.fire({ icon: 'success', title: 'Updated!', timer: 1800, showConfirmButton: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Failed', text: d.message || 'Error', confirmButtonColor: '#dc2626' });
                        }
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Connection failed.', confirmButtonColor: '#dc2626' }));
            }
        </script>
    @endpush
@endsection