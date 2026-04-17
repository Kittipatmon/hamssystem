@extends('layouts.app')
@section('content')
    <style>
        .hero-banner {
            position: relative;
            overflow: hidden;
            background-color: #000;
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            z-index: 1;
        }

        .hero-slide.active {
            opacity: 1;
        }

        /* Ken Burns effect for non-stretched feel */
        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transform: scale(1.05);
            transition: transform 10s linear;
        }

        .hero-slide.active img {
            transform: scale(1);
        }

        /* Floating Preview Box */
        .preview-box {
            position: relative;
            width: 320px;
            height: 200px;
            border-radius: 1.5rem;
            background: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            border: 4px solid white;
        }

        @media (max-width: 1024px) {
            .preview-box {
                display: none;
            }
        }

        .preview-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .preview-slide.active {
            opacity: 1;
        }

        .preview-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 1.2rem;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(127, 29, 29, 0.85) 0%, rgba(220, 38, 38, 0.6) 50%, rgba(0, 0, 0, 0.2) 100%);
            display: flex;
            align-items: center;
            padding: 2.5rem;
        }

        .hero-overlay h1 {
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
        }

        .svc-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .svc-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(220, 38, 38, 0.18);
            border-color: #fecaca;
        }

        .svc-card img {
            transition: transform 0.5s ease;
        }

        .svc-card:hover img {
            transform: scale(1.06);
        }

        .news-item {
            transition: all 0.3s ease;
        }

        .news-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -6px rgba(0, 0, 0, 0.12);
        }

        .news-item:hover .news-thumb {
            transform: scale(1.05);
        }

        .news-thumb {
            transition: transform 0.5s ease;
        }

        .scroll-hide {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scroll-hide::-webkit-scrollbar {
            display: none;
        }

        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        /* Floating Animation for Preview Box */
        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .preview-box-floating {
            animation: floating 6s ease-in-out infinite;
        }

        /* Scroll Reveal Effect */
        .reveal {
            position: relative;
            opacity: 0;
            transform: translateY(30px);
            transition: all 1s cubic-bezier(0.23, 1, 0.320, 1);
            transition-delay: 0.1s;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-left {
            transform: translateX(-30px);
        }

        .reveal-right {
            transform: translateX(30px);
        }

        .reveal-delay-1 {
            transition-delay: 0.2s;
        }

        .reveal-delay-2 {
            transition-delay: 0.3s;
        }

        .reveal-delay-3 {
            transition-delay: 0.4s;
        }

        .reveal-delay-4 {
            transition-delay: 0.5s;
        }

        /* Section Header Hover Effect: Background Slide Right */
        .header-hover {
            position: relative;
            cursor: default;
            display: inline-flex;
            align-items: center;
            padding-right: 2rem;
        }

        .header-hover::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 80%;
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.05), transparent);
            border-left: 4px solid #dc2626;
            z-index: -1;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            border-radius: 0.5rem;
        }

        .header-hover:hover::before {
            width: 110%;
        }

        .header-hover h2 {
            transition: transform 0.3s ease;
        }

        .header-hover:hover h2 {
            transform: translateX(8px);
        }

        /* Custom Wizz Underline Transition */
        .wizz-transition {
            transition: width 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }
    </style>

    {{-- ============ HERO BANNER (Full Width Slider) ============ --}}
    <div class="-mx-6 -mt-6 mb-8">
        <div class="hero-banner shadow-2xl relative h-[calc(100vh-60px)] min-h-[400px]">

            {{-- Slider Images --}}
            <div id="hero-slider">
                <div class="hero-slide active">
                    <img src="https://fameline.com/wp-content/uploads/2023/09/PJR51_Mix-Products_Kumwell-Office_banner-scaled.jpg"
                        alt="HAMS Building">
                </div>
                <div class="hero-slide">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80"
                        alt="Modern Workspace">
                </div>
                <div class="hero-slide">
                    <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1920&q=80"
                        alt="Professional Services">
                </div>
            </div>

            {{-- Enhanced Overlay --}}
            <div
                class="absolute inset-0 bg-gradient-to-r from-red-900/95 via-red-800/80 to-black/40 mix-blend-multiply z-10">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/90 via-zinc-900/20 to-transparent z-10"></div>

            <div class="absolute inset-0 flex flex-col justify-center px-8 sm:px-12 lg:px-20 max-w-7xl mx-auto w-full z-20">
                <div class="max-w-2xl animate-fade-in-up">
                    {{-- Badge --}}
                    <div
                        class="inline-flex items-center gap-2.5 bg-white/10 backdrop-blur-md text-white/90 text-[11px] font-bold px-4 py-2 rounded-full mb-6 border border-white/20 shadow-lg tracking-widest uppercase">
                        <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                        HAMS System
                    </div>

                    {{-- Main Heading --}}
                    <h1
                        class="text-white text-4xl sm:text-5xl lg:text-6xl font-black leading-[1.15] mb-6 tracking-tight drop-shadow-2xl">
                        Human Asset <br class="hidden sm:block">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-200 to-white">Management</span>
                        <br class="hidden sm:block">
                        <span class="text-white/90 font-extrabold">& Service Building</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p
                        class="text-white/80 text-base sm:text-lg leading-relaxed max-w-xl font-medium drop-shadow-md border-l-4 border-red-500 pl-4 py-1">
                        แผนกจัดการและบำรุงรักษาอาคาร — บริหารจัดการอุปกรณ์ ห้องประชุม รถส่วนกลาง ครบจบในที่เดียว
                    </p>

                    {{-- Action Buttons (Optional/Placeholder for future) --}}
                    <div class="mt-10 flex items-center gap-4 hidden">
                        <button
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3.5 rounded-full font-bold shadow-lg shadow-red-900/20 transition-all hover:scale-105 active:scale-95">เริ่มต้นใช้งาน</button>
                    </div>
                </div>
            </div>

            {{-- Right Side: Floating Preview Box & Dots --}}
            <div class="hidden lg:flex absolute right-20 top-1/2 -translate-y-1/2 flex-col items-center gap-6 z-30 animate-fade-in-up"
                style="animation-delay: 0.2s">
                <div class="preview-box preview-box-floating">
                    <div id="preview-slider">
                        <div class="preview-slide active">
                            <img src="https://fameline.com/wp-content/uploads/2023/09/PJR51_Mix-Products_Kumwell-Office_banner-scaled.jpg"
                                alt="Preview 1">
                        </div>
                        <div class="preview-slide">
                            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80"
                                alt="Preview 2">
                        </div>
                        <div class="preview-slide">
                            <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=800&q=80"
                                alt="Preview 3">
                        </div>
                    </div>
                </div>

                {{-- Slider Controls (Dots) --}}
                <div class="flex gap-4" id="hero-dots">
                    <button data-index="0"
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                    <button data-index="1"
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-40 hover:opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                    <button data-index="2"
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-40 hover:opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/50 animate-bounce z-20 cursor-pointer lg:center"
            onclick="document.getElementById('services').scrollIntoView({behavior: 'smooth'})">
            <span class="text-[10px] tracking-widest uppercase font-bold">เลื่อนลง</span>
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </div>

        {{-- ============ INTEGRATED ANNOUNCEMENT SECTION ============ --}}
        @if(isset($announcements) && $announcements->count())
            <div class="absolute left-0 right-0 z-40 bg-zinc-950/20 backdrop-blur-md border-t border-white/10 group">
                <div class="bg-red-600/90 py-3.5 relative overflow-hidden">
                    <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center gap-6 relative z-10 font-noto">
                        <div class="flex items-center gap-2 flex-shrink-0 bg-white/20 backdrop-blur-xl px-3 sm:px-4 py-2 rounded-xl border border-white/30 shadow-xl animate-pulse">
                            <i class="fa-solid fa-bullhorn text-white text-[10px]"></i>
                            <span class="hidden sm:inline text-white text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap">Announcement</span>
                        </div>
                        
                        <div class="flex-1 overflow-hidden relative h-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="animate-marquee whitespace-nowrap flex gap-12 group-hover:pause">
                                    @foreach($announcements as $ann)
                                        @php
                                            $annData = [
                                                "title" => $ann->title,
                                                "content" => $ann->content,
                                                "date" => $ann->published_date ? $ann->published_date->format('d/m/Y') : '-',
                                                "image" => $ann->image_path,
                                                "is_urgent" => $ann->is_urgent
                                            ];
                                        @endphp
                                        <button onclick='openAnnouncementModal(@json($annData))' class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer group/item">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $ann->is_urgent ? 'bg-amber-400' : 'bg-white/60' }}"></div>
                                            <span class="text-white text-sm font-black tracking-tight flex items-center gap-2">
                                                @if($ann->is_urgent)
                                                    <span class="text-amber-400 uppercase text-[10px] tracking-widest">[เร่งด่วน]</span>
                                                @endif
                                                {{ $ann->title }}
                                                @if($ann->content)
                                                    <span class="text-white/70 font-medium ml-2">— {{ strip_tags($ann->content) }}</span>
                                                @endif
                                                <i class="fa-solid fa-circle-info text-[10px] opacity-0 group-hover/item:opacity-100 transition-opacity ml-1"></i>
                                            </span>
                                        </button>
                                    @endforeach
                                    <!-- Duplicate for seamless loop -->
                                    @foreach($announcements as $ann)
                                        @php
                                            $annData = [
                                                "title" => $ann->title,
                                                "content" => $ann->content,
                                                "date" => $ann->published_date ? $ann->published_date->format('d/m/Y') : '-',
                                                "image" => $ann->image_path,
                                                "is_urgent" => $ann->is_urgent
                                            ];
                                        @endphp
                                        <button onclick='openAnnouncementModal(@json($annData))' class="flex items-center gap-3 hover:opacity-80 transition-opacity cursor-pointer group/item">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $ann->is_urgent ? 'bg-amber-400' : 'bg-white/60' }}"></div>
                                            <span class="text-white text-sm font-black tracking-tight flex items-center gap-2">
                                                @if($ann->is_urgent)
                                                    <span class="text-amber-400 uppercase text-[10px] tracking-widest">[เร่งด่วน]</span>
                                                @endif
                                                {{ $ann->title }}
                                                @if($ann->content)
                                                    <span class="text-white/70 font-medium ml-2">— {{ strip_tags($ann->content) }}</span>
                                                @endif
                                                <i class="fa-solid fa-circle-info text-[10px] opacity-0 group-hover/item:opacity-100 transition-opacity ml-1"></i>
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:flex gap-2 opacity-50">
                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                            <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>

        <style>
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .animate-marquee {
                display: flex;
                animation: marquee 60s linear infinite;
            }
            .animate-marquee:hover {
                animation-play-state: paused;
            }
            .pause {
                animation-play-state: paused;
            }
        </style>


    {{-- ============ SERVICES SECTION ============ --}}
    <div class="relative py-24 bg-[#FAF7F2] overflow-hidden">
        {{-- Decorative Glow --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-6xl h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-50/50 rounded-full blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div id="services" class="scroll-mt-32 mb-16 reveal">
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center gap-3 bg-white px-4 py-2 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 mb-6 group hover:scale-105 transition-transform duration-500">
                        <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shadow-lg shadow-red-200 group-hover:rotate-12 transition-transform">
                            <i class="fa-solid fa-layer-group text-lg"></i>
                        </div>
                        <span class="text-slate-900 font-black text-sm tracking-tight">HAMS Ecosystem</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight mb-6">
                        งานสนับสนุน<span class="text-red-600">และบริการ</span>
                    </h2>
                    <p class="text-slate-500 text-lg font-medium max-w-2xl leading-relaxed">
                        ยกระดับประสิทธิภาพการทำงานด้วยระบบสนับสนุนอัจฉริยะ <br class="hidden sm:block"> ครอบคลุมทุกความต้องการภายในองค์กร
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 reveal">
                @php
                    $services = [
                        [
                            'route' => route('items.itemsalllist'),
                            'title' => 'ระบบเบิกอุปกรณ์สำนักงาน',
                            'subtitle' => 'Asset & Supply Management',
                            'description' => 'จัดการเบิกอุปกรณ์ ติดตามสถานะ และควบคุมสต็อกอัจฉริยะเพื่อความโปร่งใส',
                            'image' => asset('images/welcome/servicehams.jpg'),
                            'status' => 'พร้อมใช้งาน',
                            'status_color' => 'emerald',
                            'icon' => 'fa-box-open',
                            'delay' => 'reveal-delay-1'
                        ],
                        [
                            'route' => route('reservations.welcomemeeting'),
                            'title' => 'ระบบจองห้องประชุม',
                            'subtitle' => 'Meeting Room Booking',
                            'description' => 'จองห้องประชุมออนไลน์ เช็คตารางเวลา และจัดการเครื่องอำนวยความสะดวก',
                            'image' => asset('images/welcome/bookingmeet.jpg'),
                            'status' => 'พร้อมใช้งาน',
                            'status_color' => 'emerald',
                            'icon' => 'fa-calendar-check',
                            'delay' => 'reveal-delay-2'
                        ],
                        [
                            'route' => route('bookingcar.welcome'),
                            'title' => 'ระบบจองรถส่วนกลาง',
                            'subtitle' => 'Smart Transportation',
                            'description' => 'บริการจองรถยนต์ส่วนกลางสำหรับการปฏิบัติงาน ติดตามตำแหน่งและสถานะกนรใช้งาน',
                            'image' => asset('images/welcome/bookingcar.jpg'),
                            'status' => 'พร้อมใช้งาน',
                            'status_color' => 'emerald',
                            'icon' => 'fa-car-side',
                            'delay' => 'reveal-delay-3'
                        ],
                        [
                            'route' => route('housing.welcome'),
                            'title' => 'ระบบบ้านพักพนักงาน',
                            'subtitle' => 'Residence Management',
                            'description' => 'บริหารจัดการสิทธิที่พักพนักงาน ตรวจสอบห้องว่าง และดำเนินการส่งมอบที่พัก',
                            'image' => asset('images/welcome/residence.jpg'),
                            'status' => 'พร้อมใช้งาน',
                            'status_color' => 'emerald',
                            'icon' => 'fa-building-user',
                            'delay' => 'reveal-delay-1'
                        ],
                        [
                            'route' => '#',
                            'title' => 'ระบบแจ้งซ่อมบำรุง',
                            'subtitle' => 'Maintenance Request',
                            'description' => 'แจ้งซ่อมอุปกรณ์และอาคาร ติดตามคิวงาน และสรุปผลการดำเนินการ (เร็วๆ นี้)',
                            'image' => asset('images/welcome/repairrequest.jpg'),
                            'status' => 'เร็วๆ นี้',
                            'status_color' => 'orange',
                            'icon' => 'fa-tools',
                            'delay' => 'reveal-delay-2',
                            'upcoming' => true
                        ],
                    ];

                    // Add Admin card if applicable
                    if (Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]))) {
                        $services[] = [
                            'route' => route('backend.welcomedatamanage'),
                            'title' => 'ระบบจัดการข้อมูลหลังบ้าน',
                            'subtitle' => 'Central Data Control',
                            'description' => 'ศูนย์ควบคุมข้อมูล Master Data พนักงาน อาคาร และการตั้งค่าระบบทั้งหมด',
                            'image' => asset('images/welcome/data.png'),
                            'status' => 'ผู้ดูแลระบบ',
                            'status_color' => 'indigo',
                            'icon' => 'fa-database',
                            'delay' => 'reveal-delay-3'
                        ];
                    }
                @endphp

                @foreach($services as $svc)
                    <div class="{{ $svc['delay'] }}">
                        <a href="{{ $svc['route'] }}" 
                           @if(isset($svc['upcoming'])) onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'ระบบส่วนนี้จะเปิดให้บริการในอนาคตอันใกล้.', 'warning'); return false;" @endif
                           class="group relative block bg-white rounded-[2.5rem] p-4 shadow-2xl shadow-slate-200/40 border border-white hover:border-red-100 transition-all duration-500 hover:-translate-y-2 overflow-hidden h-full">
                            
                            {{-- Image Container --}}
                            <div class="relative h-60 w-full overflow-hidden rounded-[2rem] mb-6">
                                <img src="{{ $svc['image'] }}" alt="{{ $svc['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                                
                                {{-- Status Pill --}}
                                <div class="absolute top-6 right-6">
                                    <span class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg text-[10px] font-black uppercase tracking-widest text-{{ $svc['status_color'] }}-600 border border-white/50">
                                        {{ $svc['status'] }}
                                    </span>
                                </div>

                                {{-- Icon Overlay --}}
                                <div class="absolute bottom-6 left-6">
                                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-xl border border-white/30 flex items-center justify-center text-white shadow-xl">
                                        <i class="fa-solid {{ $svc['icon'] }} text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="px-3 pb-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.2em] @if($svc['status_color'] == 'indigo') text-indigo-500 @else text-red-600 @endif mb-2">{{ $svc['subtitle'] }}</p>
                                <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-red-600 transition-colors tracking-tight">{{ $svc['title'] }}</h3>
                                <p class="text-slate-500 text-sm leading-relaxed font-medium line-clamp-2">{{ $svc['description'] }}</p>
                                
                                <div class="mt-8 flex items-center justify-end">
                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-red-50 group-hover:text-red-600 border border-slate-100 transition-all duration-300">
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>    </div>

    {{-- ============ POLICY & OPERATIONS SECTION ============ --}}
    <div class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-[#FDFBF7] -z-10"></div>
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-white to-transparent opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col items-center mb-16 reveal">
                <div class="flex items-center gap-4 group">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white text-blue-600 shadow-xl shadow-blue-900/5 border border-slate-100 group-hover:rotate-6 transition-all duration-300 shrink-0">
                        <i class="fa-solid fa-book-open text-2xl"></i>
                    </div>
                    <div class="relative">
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight">
                            นโยบาย<span class="text-blue-600">และการ</span>ดำเนินงาน
                        </h2>
                        <div class="absolute -bottom-2 left-0 w-full h-1 bg-blue-600/10 rounded-full overflow-hidden">
                            <div class="w-0 group-hover:w-full h-full bg-blue-600 rounded-full wizz-transition"></div>
                        </div>
                    </div>
                </div>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg font-medium mt-6 text-center leading-relaxed">
                    ข้อกำหนด แนวทางปฏิบัติ และขั้นตอนการทำงานที่โปร่งใส <br class="hidden sm:block"> เพื่อมาตรฐานการบริการที่ยอดเยี่ยม
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Policy Section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-white/50 reveal reveal-left">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100/80">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-bullhorn text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">หมวดหมู่นโยบาย</h3>
                            <p class="text-xs text-slate-400 font-medium tracking-wide uppercase mt-1">Corporate Policies & Guidelines</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($policies as $policy)
                            <div class="group/card bg-white rounded-2xl p-5 border border-slate-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 relative overflow-hidden">
                                <div class="absolute left-0 top-0 w-1 h-full bg-blue-600 opacity-0 group-hover/card:opacity-100 transition-opacity"></div>
                                <div class="flex gap-5">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs shrink-0 group-hover/card:bg-blue-600 group-hover/card:text-white transition-colors mt-0.5">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-[15px] text-slate-800 font-bold mb-1.5 group-hover/card:text-blue-700 transition-colors">{{ $policy->title }}</h4>
                                        <p class="text-[13px] text-slate-500 leading-relaxed font-medium">{{ $policy->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                                    <i class="fa-solid fa-folder-open text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 italic text-sm">ยังไม่มีข้อมูลนโยบาย</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Operations Section -->
                <div class="bg-white/80 backdrop-blur-sm rounded-[2.5rem] p-8 shadow-2xl shadow-slate-200/50 border border-white/50 reveal reveal-right mt-8 lg:mt-0">
                    <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100/80">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                            <i class="fa-solid fa-clipboard-list text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800 leading-tight">หมวดหมู่การดำเนินงาน</h3>
                            <p class="text-xs text-slate-400 font-medium tracking-wide uppercase mt-1">Operational Procedures</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($operations as $op)
                            <div class="group/card bg-white rounded-2xl p-5 border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 relative overflow-hidden">
                                <div class="absolute left-0 top-0 w-1 h-full bg-emerald-600 opacity-0 group-hover/card:opacity-100 transition-opacity"></div>
                                <div class="flex gap-5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs shrink-0 group-hover/card:bg-emerald-600 group-hover/card:text-white transition-colors mt-0.5">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-[15px] text-slate-800 font-bold mb-1.5 group-hover/card:text-emerald-700 transition-colors">{{ $op->title }}</h4>
                                        <p class="text-[13px] text-slate-500 leading-relaxed font-medium">{{ $op->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                                    <i class="fa-solid fa-folder-open text-slate-300 text-2xl"></i>
                                </div>
                                <p class="text-slate-400 italic text-sm">ยังไม่มีข้อมูลการดำเนินงาน</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ NEWS SECTION ============ --}}
    <div class="relative py-24 bg-[#FAF7F2] overflow-hidden">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-[#F3EEE5] to-transparent opacity-60"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-red-50 rounded-full blur-3xl opacity-50"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div id="news" class="scroll-mt-32 mb-16 reveal">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-10 h-[2px] bg-red-600 rounded-full"></span>
                            <span class="text-red-600 font-bold text-xs uppercase tracking-[0.2em]">Latest Updates</span>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            ข่าวสารและ<span class="text-red-600">กิจกรรม</span>
                        </h2>
                        <p class="text-slate-500 text-lg font-medium mt-4 leading-relaxed">
                            ติดตามข้อมูลข่าวสาร ประชาสัมพันธ์ และกิจกรรมล่าสุดของหน่วยงาน HAMS <br class="hidden lg:block"> เพื่อไม่ให้พลาดทุกความเคลื่อนไหวสำคัญ
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('datamanage.news.newsalllist') }}"
                            class="inline-flex items-center gap-3 bg-white hover:bg-red-600 text-red-600 hover:text-white px-8 py-4 rounded-2xl font-bold shadow-xl shadow-slate-200/50 border border-red-50 hover:border-red-600 transition-all duration-300 group hover:-translate-y-1">
                            ดูข่าวสารทั้งหมด 
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>

            @if(isset($news) && $news->count())
                @php
                    $mainNews = $news->first();
                    $sideNews = $news->slice(1, 3);
                    
                    $processImages = function($item) {
                        $raw = $item->image_path ?? null;
                        if (is_string($raw)) {
                            $decoded = json_decode($raw, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $paths = $decoded;
                            } elseif (trim($raw) !== '') {
                                $paths = [$raw];
                            }
                        } elseif (is_array($raw)) {
                            $paths = $raw;
                        }
                        $p = $paths[0] ?? 'images/welcome/news1.jpg';
                        $p = str_replace('\\', '/', (string) $p);
                        return (preg_match('#^(https?:)?//#', $p) === 1) ? $p : asset(ltrim($p, '/'));
                    };
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 reveal">
                    {{-- MAIN FEATURE NEWS (LEFT) --}}
                    <div class="lg:col-span-7">
                        <div class="group cursor-pointer bg-white rounded-[2.5rem] p-4 shadow-2xl shadow-slate-200/40 border border-white transition-all duration-500 hover:-translate-y-2" 
                             onclick="window.location.href='{{ route('datamanage.news.newsalllist') }}'">
                            <div class="relative overflow-hidden rounded-[2rem] mb-8">
                                <img src="{{ $processImages($mainNews) }}" 
                                    alt="{{ $mainNews->title }}" 
                                    class="w-full aspect-[16/10] object-cover group-hover:scale-105 transition-transform duration-1000">
                                <div class="absolute top-6 left-6">
                                    <span class="bg-red-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">New Featured</span>
                                </div>
                            </div>
                            <div class="px-4 pb-4">
                                <div class="flex items-center gap-3 text-slate-400 text-xs font-bold mb-4 uppercase tracking-widest">
                                    <i class="fa-regular fa-calendar-check text-red-500"></i>
                                    {{ $mainNews->created_at->translatedFormat('d M Y') }}
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                                    <span>HAMS Administrator</span>
                                </div>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors">
                                    {{ $mainNews->title }}
                                </h3>
                                <p class="text-slate-500 text-base mb-8 line-clamp-3 leading-relaxed font-medium">
                                    {{ strip_tags($mainNews->content) }}
                                </p>
                                <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                    <span class="text-slate-900 group-hover:text-red-600 font-black text-sm tracking-tight transition-all flex items-center gap-2">
                                        อ่านเนื้อหาฉบับเต็ม <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR NEWS LIST (RIGHT) --}}
                    <div class="lg:col-span-5 flex flex-col gap-8">
                        @foreach($sideNews as $item)
                            <div class="group cursor-pointer flex gap-6 bg-white/60 hover:bg-white p-4 rounded-3xl border border-transparent hover:border-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500" 
                                 onclick="window.location.href='{{ route('datamanage.news.newsalllist') }}'">
                                <div class="w-32 sm:w-40 shrink-0 relative overflow-hidden rounded-2xl aspect-square shadow-md">
                                    <img src="{{ $processImages($item) }}" 
                                        alt="{{ $item->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                </div>
                                <div class="flex-1 flex flex-col justify-center py-1">
                                    <div class="flex items-center gap-2 text-slate-400 text-[11px] font-bold uppercase tracking-wider mb-2">
                                        <i class="fa-regular fa-calendar text-red-500/60"></i>
                                        {{ $item->created_at->translatedFormat('d M Y') }}
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-800 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors mb-4">
                                        {{ $item->title }}
                                    </h4>
                                    <span class="text-slate-400 group-hover:text-red-600 font-bold text-xs tracking-tight transition-colors flex items-center gap-2 mt-auto">
                                        เพิ่มเติม <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if($sideNews->isEmpty())
                            <div class="h-full flex items-center justify-center border-2 border-dashed border-slate-200 rounded-[2rem] p-10">
                                <p class="text-slate-400 font-medium italic text-center">คอยติดตามข่าวสารใหม่ๆ ได้ที่นี่เร็วๆ นี้</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-24 reveal">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-slate-200 border border-slate-50">
                        <i class="fa-solid fa-newspaper text-slate-200 text-4xl"></i>
                    </div>
                    <p class="text-slate-400 font-bold text-xl">ยังไม่มีข้อมูลข่าวสารในขณะนี้</p>
                    <p class="text-slate-300 font-medium mt-2">โปรดกลับมาเช็คใหม่อีกครั้งในภายหลัง</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ============ ANNOUNCEMENT LIST SECTION ============ --}}
    <div class="relative py-28 bg-[#00000] overflow-hidden">
        {{-- Abstract background patterns --}}
        <div class="absolute inset-0 opacity-[0.015] pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div id="announcements-list" class="scroll-mt-32 flex flex-col lg:flex-row lg:items-end justify-between mb-16 reveal gap-8">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-white shadow-xl shadow-red-900/5 flex items-center justify-center border border-red-50 group-hover:rotate-6 transition-transform">
                            <i class="fa-solid fa-bullhorn text-2xl text-red-600"></i>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                            ประกาศ<span class="text-red-600">สำคัญ</span>
                        </h2>
                    </div>
                    <p class="text-slate-500 text-lg font-medium leading-relaxed">
                        อัปเดตแจ้งเตือน ข้อมูลด่วน และประกาศล่าสุดจากผู้บริหารและแผนก HAMS <br class="hidden lg:block"> เพื่อการสื่อสารที่รวดเร็วและทั่วถึงภายในองค์กร
                    </p>
                </div>
                
                {{-- Custom Navigation Controls --}}
                <div class="flex items-center gap-4">
                    <div class="h-10 bg-white px-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <span id="annCounter" class="text-[13px] font-black tracking-widest text-slate-400">
                            <span class="text-red-600">01</span> <span class="mx-1 text-slate-200">/</span> {{ $announcements->count() < 10 ? '0' : '' }}{{ $announcements->count() }}
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button id="annPrevBtn" class="w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-100 hover:shadow-lg shadow-slate-200/50 flex items-center justify-center transition-all">
                            <i class="fa-solid fa-arrow-left-long"></i>
                        </button>
                        <button id="annNextBtn" class="w-12 h-12 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-100 hover:shadow-lg shadow-slate-200/50 flex items-center justify-center transition-all">
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if(isset($announcements) && $announcements->count())
                <div class="relative group/slider reveal">
                    {{-- Carousel Container --}}
                    <div id="announcementSlider" class="flex overflow-x-auto pb-12 scroll-hide snap-x snap-mandatory scroll-smooth gap-10 cursor-grab active:cursor-grabbing items-center min-h-[620px]">
                        @foreach($announcements as $index => $ann)
                            @php
                                $annData = [
                                    "title" => $ann->title,
                                    "content" => $ann->content,
                                    "date" => $ann->published_date ? $ann->published_date->format('d/m/Y') : '-',
                                    "image" => $ann->image_path,
                                    "is_urgent" => $ann->is_urgent
                                ];
                            @endphp
                            <div class="announcement-slide snap-center flex-shrink-0 transition-all duration-700 scale-90 opacity-40 py-10" data-original-index="{{ $index }}">
                                <button onclick='handleCardClick(this, @json($annData))' 
                                    class="text-left bg-white rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-white flex flex-col h-[540px] w-[360px] sm:w-[400px] overflow-hidden group/item cursor-pointer transition-all duration-500 hover:border-red-100 active:scale-95">
                                    
                                    {{-- Top: Image with Overlays --}}
                                    <div class="h-64 w-full overflow-hidden bg-slate-50 relative pointer-events-none">
                                        @if($ann->image_path)
                                            <img src="{{ asset($ann->image_path) }}" class="w-full h-full object-cover group-hover/item:scale-105 transition-transform duration-1000" alt="Preview">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#FDFBF7] to-[#F3EEE5]">
                                                <i class="fa-solid fa-bullhorn text-6xl text-red-100"></i>
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                            </div>
                                        @endif

                                        {{-- Official Badge --}}
                                        <div class="absolute top-6 left-6">
                                            <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-lg border border-white/50 flex items-center gap-2">
                                                <div class="w-1.5 h-1.5 rounded-full {{ $ann->is_urgent ? 'bg-red-500 animate-pulse' : 'bg-slate-300' }}"></div>
                                                <span class="text-[10px] font-black text-slate-800 uppercase tracking-widest">Official Notice</span>
                                            </div>
                                        </div>

                                        {{-- Urgent Tag --}}
                                        @if($ann->is_urgent)
                                            <div class="absolute top-6 right-6">
                                                <div class="bg-red-600 text-white px-3 py-1.5 rounded-xl shadow-lg font-black text-[9px] uppercase tracking-tighter">Urgent</div>
                                            </div>
                                        @endif

                                        {{-- Date over image --}}
                                        <div class="absolute bottom-6 left-8">
                                            <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mb-1">Published On</p>
                                            <p class="text-white text-sm font-black">{{ $ann->published_date ? $ann->published_date->format('d M Y') : '—' }}</p>
                                        </div>
                                    </div>

                                    {{-- Body: Text Content --}}
                                    <div class="p-10 flex flex-col flex-grow">
                                        <h3 class="text-2xl font-black text-slate-900 mb-5 line-clamp-2 leading-[1.25] group-hover/item:text-red-600 transition-colors tracking-tight">{{ $ann->title }}</h3>
                                        <p class="text-slate-500 text-[15px] line-clamp-3 mb-8 flex-grow leading-[1.6] font-medium">{{ strip_tags($ann->content) }}</p>
                                        
                                        <div class="flex items-center justify-between pt-8 border-t border-slate-50 mt-auto">
                                            <div class="flex -space-x-2">
                                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-400">H</div>
                                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200"></div>
                                            </div>
                                            <span class="text-slate-900 group-hover:text-red-600 font-black text-[13px] transition-all flex items-center gap-3">
                                                อ่านรายละเอียด <i class="fa-solid fa-arrow-right-long transition-transform group-hover:translate-x-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white/50 backdrop-blur-sm rounded-[3.5rem] border-2 border-dashed border-slate-200 p-24 text-center reveal">
                    <div class="w-24 h-24 bg-white rounded-3xl shadow-xl flex items-center justify-center mx-auto mb-8 transition-transform hover:rotate-12">
                        <i class="fa-solid fa-bullhorn text-4xl text-slate-200"></i>
                    </div>
                    <h3 class="text-slate-900 font-black text-2xl tracking-tight">ยังไม่มีประกาศล่าสุด</h3>
                    <p class="text-slate-500 text-base mt-3 max-w-md mx-auto leading-relaxed">คอยติดตามการอัปเดตข้อมูลและประกาศสำคัญจากผู้บริหารได้ที่นี่เร็วๆ นี้</p>
                </div>
            @endif
        </div>
    </div>
    @push('scripts')
        <script>
            /**
             * ANNOUNCEMENT CAROUSEL - GAPLESS INFINITE LOOP
             * Premium slider with seamless looping and middle-zoom effect
             */
            document.addEventListener('DOMContentLoaded', function() {
                const slider = document.getElementById('announcementSlider');
                const prevBtn = document.getElementById('annPrevBtn');
                const nextBtn = document.getElementById('annNextBtn');
                const counter = document.getElementById('annCounter');
                
                if (!slider) return;

                let slides = Array.from(slider.querySelectorAll('.announcement-slide'));
                const originalCount = slides.length;
                if (originalCount === 0) return;

                const cloneCount = Math.min(originalCount, 5); 
                
                for (let i = 0; i < cloneCount; i++) {
                    const clone = slides[i].cloneNode(true);
                    slider.appendChild(clone);
                }
                for (let i = 0; i < cloneCount; i++) {
                    const clone = slides[originalCount - 1 - i].cloneNode(true);
                    slider.prepend(clone);
                }

                const allSlides = Array.from(slider.querySelectorAll('.announcement-slide'));
                const slideWidth = allSlides[0].offsetWidth + 32; 
                
                slider.scrollLeft = slideWidth * cloneCount;

                let isJumping = false;
                let autoPlayTimer;

                const updateSlider = () => {
                    if (isJumping) return;

                    const sliderCenter = slider.scrollLeft + (slider.offsetWidth / 2);
                    let minDistance = Infinity;
                    let closestIndex = 0;

                    allSlides.forEach((slide, index) => {
                        const slideCenter = slide.offsetLeft + (slide.offsetWidth / 2);
                        const distance = Math.abs(sliderCenter - slideCenter);

                        if (distance < minDistance) {
                            minDistance = distance;
                            closestIndex = index;
                        }

                        if (distance < slide.offsetWidth / 1.1) {
                            slide.classList.remove('scale-90', 'opacity-40');
                            slide.classList.add('scale-100', 'opacity-100');
                        } else {
                            slide.classList.remove('scale-100', 'opacity-100');
                            slide.classList.add('scale-90', 'opacity-40');
                        }
                    });

                    const threshold = slideWidth * 0.5;
                    if (slider.scrollLeft <= threshold) {
                        isJumping = true;
                        slider.style.scrollBehavior = 'auto';
                        slider.scrollLeft += (originalCount * slideWidth);
                        slider.style.scrollBehavior = 'smooth';
                        isJumping = false;
                        return;
                    }
                    if (slider.scrollLeft >= (slider.scrollWidth - slider.offsetWidth - threshold)) {
                        isJumping = true;
                        slider.style.scrollBehavior = 'auto';
                        slider.scrollLeft -= (originalCount * slideWidth);
                        slider.style.scrollBehavior = 'smooth';
                        isJumping = false;
                        return;
                    }

                    if (counter) {
                        const originalIndex = parseInt(allSlides[closestIndex].dataset.originalIndex) || 0;
                        counter.innerHTML = `<span class="text-red-600">${originalIndex + 1}</span> / ${originalCount}`;
                    }
                };

                const startAutoPlay = () => {
                    clearInterval(autoPlayTimer);
                    autoPlayTimer = setInterval(() => {
                        slider.scrollBy({ left: slideWidth, behavior: 'smooth' });
                    }, 5000);
                };

                const stopAutoPlay = () => clearInterval(autoPlayTimer);

                slider.addEventListener('mouseenter', stopAutoPlay);
                slider.addEventListener('mouseleave', startAutoPlay);
                slider.addEventListener('scroll', updateSlider);
                window.addEventListener('resize', () => {
                    slider.scrollLeft = slideWidth * cloneCount;
                    updateSlider();
                });

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: -slideWidth, behavior: 'smooth' });
                        stopAutoPlay();
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: slideWidth, behavior: 'smooth' });
                        stopAutoPlay();
                    });
                }

                setTimeout(updateSlider, 400); 
                startAutoPlay();
            });

            /**
             * Handle Clicking on a card
             * If side card -> Center it
             * If middle card -> Open details
             */
            window.handleCardClick = function(btnElement, data) {
                const slider = document.getElementById('announcementSlider');
                const slide = btnElement.closest('.announcement-slide');
                if (!slider || !slide) return;

                const sliderCenter = slider.scrollLeft + (slider.offsetWidth / 2);
                const slideCenter = slide.offsetLeft + (slide.offsetWidth / 2);
                const distance = Math.abs(sliderCenter - slideCenter);

                // If already near center (focused), open modal
                if (distance < slide.offsetWidth / 2) {
                    openAnnouncementModal(data);
                } else {
                    // Smooth scroll to center this item
                    slider.scrollTo({
                        left: slide.offsetLeft - (slider.offsetWidth / 2) + (slide.offsetWidth / 2),
                        behavior: 'smooth'
                    });
                }
            };

            function openAnnouncementModal(data) {
                const modal = document.getElementById('announcementModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalContent = document.getElementById('modalContent');
                const modalDate = document.getElementById('modalDate');
                const modalImage = document.getElementById('modalImage');
                const modalImageContainer = document.getElementById('modalImageContainer');
                const modalUrgentBadge = document.getElementById('modalUrgentBadge');

                modalTitle.textContent = data.title;
                modalContent.innerHTML = data.content.split('\r\n').map(p => `<div>${p}</div>`).join('');
                modalDate.textContent = data.date;

                if (data.image) {
                    modalImage.src = "{{ asset('') }}" + data.image;
                    modalImageContainer.classList.remove('hidden');
                } else {
                    modalImageContainer.classList.add('hidden');
                }

                if (data.is_urgent) {
                    modalUrgentBadge.classList.remove('hidden');
                } else {
                    modalUrgentBadge.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAnnouncementModal() {
                const modal = document.getElementById('announcementModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        </script>
    @endpush

    {{-- ============ ANNOUNCEMENT MODAL ============ --}}
    <div id="announcementModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-zinc-950/70 backdrop-blur-sm transition-opacity" onclick="closeAnnouncementModal()"></div>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-[0_30px_60px_-12px_rgba(0,0,0,0.3)] transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-zinc-200">
                <div class="relative">
                    {{-- Close Button --}}
                    <button onclick="closeAnnouncementModal()" class="absolute top-5 right-5 z-20 w-10 h-10 flex items-center justify-center rounded-xl bg-white hover:bg-zinc-100 text-zinc-400 hover:text-zinc-900 shadow-sm border border-zinc-100 transition-all">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>

                    <div class="flex flex-col md:flex-row min-h-[400px]">
                        {{-- Left: Image --}}
                        <div id="modalImageContainer" class="w-full md:w-[48%] h-80 md:h-auto overflow-hidden bg-zinc-50 hidden border-r border-zinc-100">
                            <img id="modalImage" src="" class="w-full h-full object-cover" alt="Announcement">
                        </div>

                        {{-- Right: Text Content --}}
                        <div class="flex-1 p-10 sm:p-14 flex flex-col justify-center bg-white">
                            <div id="modalUrgentBadge" class="hidden mb-6">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[11px] font-black bg-red-600 text-white shadow-lg shadow-red-600/30 uppercase tracking-[0.2em]">
                                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                    URGENT
                                </span>
                            </div>

                            <div class="mb-8">
                                <h3 id="modalTitle" class="text-3xl sm:text-4xl font-black text-black leading-tight tracking-tight mb-4"></h3>
                                <div class="flex items-center gap-4 text-zinc-500 text-[10px] font-bold uppercase tracking-[0.2em]">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-regular fa-calendar-days text-red-600"></i>
                                        <span id="modalDate"></span>
                                    </div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-zinc-200"></div>
                                    <span class="text-zinc-600 font-black">HAMS OFFICIAL UPDATE</span>
                                </div>
                            </div>

                            <div id="modalContent" class="text-zinc-900 leading-[1.6] space-y-4 whitespace-pre-line font-medium text-lg sm:text-xl selection:bg-red-500 selection:text-white"></div>
                            
                            <div class="mt-12 pt-8 border-t border-zinc-100 flex items-center justify-between">
                                <button onclick="closeAnnouncementModal()" class="text-zinc-400 hover:text-black text-[10px] font-black uppercase tracking-[0.3em] transition-colors">
                                    CLOSE WINDOW
                                </button>
                                <div class="flex gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-zinc-100"></div>
                                    <div class="w-2 h-2 rounded-full bg-zinc-200"></div>
                                    <div class="w-2 h-2 rounded-full bg-red-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAnnouncementModal(data) {
                const modal = document.getElementById('announcementModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalContent = document.getElementById('modalContent');
                const modalDate = document.getElementById('modalDate');
                const modalImage = document.getElementById('modalImage');
                const modalImageContainer = document.getElementById('modalImageContainer');
                const modalUrgentBadge = document.getElementById('modalUrgentBadge');

                modalTitle.textContent = data.title;
                modalContent.innerHTML = data.content.split('\r\n').map(p => `<div>${p}</div>`).join('');
                modalDate.textContent = data.date;

                if (data.image) {
                    modalImage.src = "{{ asset('') }}" + data.image;
                    modalImageContainer.classList.remove('hidden');
                } else {
                    modalImageContainer.classList.add('hidden');
                }

                if (data.is_urgent) {
                    modalUrgentBadge.classList.remove('hidden');
                } else {
                    modalUrgentBadge.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeAnnouncementModal() {
                const modal = document.getElementById('announcementModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            document.addEventListener('DOMContentLoaded', function () {
                // Scroll Reveal Observer
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: "0px 0px -50px 0px"
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('active');
                            // Optional: unobserve after reveal
                            // observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

                // Hero Slider Logic
                const heroSlides = document.querySelectorAll('.hero-slide');
                const previewSlides = document.querySelectorAll('.preview-slide');
                const dots = document.querySelectorAll('#hero-dots button');

                if (heroSlides.length === 0 || previewSlides.length === 0 || dots.length === 0) return;

                let currentSlide = 0;
                let slideInterval;

                function goToSlide(index) {
                    // Remove active from previous
                    heroSlides[currentSlide].classList.remove('active');
                    previewSlides[currentSlide].classList.remove('active');
                    dots[currentSlide].classList.replace('opacity-100', 'opacity-40');

                    currentSlide = index;

                    // Add active to current
                    heroSlides[currentSlide].classList.add('active');
                    previewSlides[currentSlide].classList.add('active');
                    dots[currentSlide].classList.replace('opacity-40', 'opacity-100');
                }

                function nextSlide() {
                    let next = (currentSlide + 1) % heroSlides.length;
                    goToSlide(next);
                }

                function startTimer() {
                    clearInterval(slideInterval);
                    slideInterval = setInterval(nextSlide, 5000); // 5 seconds
                }

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        goToSlide(index);
                        startTimer();
                    });
                });

                startTimer();
            });
        </script>
    @endpush
@endsection
