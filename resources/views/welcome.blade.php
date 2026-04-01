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
        <div class="absolute bottom-18 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/50 animate-bounce z-20 cursor-pointer lg:center"
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
                        <div class="flex items-center gap-2 flex-shrink-0 bg-white/20 backdrop-blur-xl px-4 py-2 rounded-xl border border-white/30 shadow-xl animate-pulse">
                            <i class="fa-solid fa-bullhorn text-white text-[10px]"></i>
                            <span class="text-white text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap">Announcement</span>
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
                animation: marquee 30s linear infinite;
            }
            .animate-marquee:hover {
                animation-play-state: paused;
            }
            .pause {
                animation-play-state: paused;
            }
        </style>


    {{-- ============ SERVICES SECTION ============ --}}
    <div class="bg-slate-50/70 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div id="services" class="scroll-mt-20 mb-6 reveal">
                <div class="header-hover group">
                    <div class="flex items-center gap-3 mb-1">
                        <div
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-sm group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-grip text-white text-sm"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800">งานสนับสนุน</h2>
                    </div>
                </div>
                <p class="text-xs text-slate-400 ml-12">ระบบบริการสนับสนุนภายในองค์กร</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                {{-- Card 1 --}}
                <a href="{{ route('items.itemsalllist') }}"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block reveal reveal-delay-1">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/servicehams.jpg') }}" alt="เบิกอุปกรณ์"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            พร้อมใช้งาน
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบเบิกอุปกรณ์สำนักงาน</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการเบิกอุปกรณ์ ติดตามสถานะและควบคุมสต็อก
                        </p>
                    </div>
                </a>

                {{-- Card 2 --}}
                <a href="{{ route('reservations.welcomemeeting') }}"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block reveal reveal-delay-2">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/bookingmeet.jpg') }}" alt="จองห้องประชุม"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            พร้อมใช้งาน
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบจองห้องประชุม</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการจองห้องประชุม
                            ติดตามสถานะและควบคุมการใช้งาน</p>
                    </div>
                </a>

                {{-- Card 3 --}}
                <a href="{{ route('bookingcar.welcome') }}"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block reveal reveal-delay-3">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/bookingcar.jpg') }}" alt="จองรถส่วนกลาง"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            พร้อมใช้งาน
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบจองรถส่วนกลาง</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการจองรถส่วนกลาง
                            ติดตามสถานะและควบคุมการใช้งาน</p>
                    </div>
                </a>

                {{-- Card 4 --}}
                <a href="{{ route('housing.welcome') }}"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block reveal reveal-delay-4">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/residence.jpg') }}" alt="บ้านพัก"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-green-500 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            พร้อมใช้งาน
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบบ้านพักพนักงาน</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการบ้านพักพนักงาน
                            ติดตามสถานะและควบคุมการใช้งาน</p>
                    </div>
                </a>

                {{-- Card 5 --}}
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block cursor-pointer">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/repairrequest.jpg') }}" alt="แจ้งซ่อม"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-orange-400 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            เร็วๆ นี้
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบแจ้งซ่อม</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการแจ้งซ่อม
                            ติดตามสถานะและควบคุมการดำเนินการ</p>
                    </div>
                </a>

                {{-- Card 6: Admin --}}
                @php
                    $isHamsOrAdmin = Auth::check() && ((Auth::user()->department && Auth::user()->department->department_name === 'HAMS') || Auth::user()->employee_code === '11648');
                @endphp

                @if($isHamsOrAdmin)
                    <a href="{{ route('backend.welcomedatamanage') }}"
                        class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('images/welcome/data.png') }}" alt="จัดการข้อมูล"
                                class="w-full h-40 object-cover">
                        </div>
                        <div class="p-4">
                            <p class="text-[10px] text-indigo-400 font-semibold uppercase tracking-widest mb-1">บริการหลังบ้าน
                            </p>
                            <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบจัดการข้อมูลทั่วไป</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการข้อมูลทั่วไป เช่น ข้อมูลพนักงาน อาคาร
                                และอุปกรณ์</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ============ POLICY & OPERATIONS SECTION ============ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <div class="mb-6 reveal">
            <div class="header-hover group">
                <div class="flex items-center gap-3 mb-1">
                    <div
                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        <i class="fa-solid fa-book-open text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">นโยบายและการดำเนินงาน</h2>
                </div>
            </div>
            <p class="text-xs text-slate-400 ml-12">ข้อกำหนด แนวทางปฏิบัติ และขั้นตอนการทำงาน</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Policy Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 reveal reveal-left">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-bullhorn text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-blue-900 leading-tight">หมวดหมู่นโยบาย</h3>
                        <p class="text-[11px] text-slate-400">ข้อกำหนดและแนวทางปฏิบัติขององค์กร</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($policies as $policy)
                        <div
                            class="bg-blue-50/50 rounded-xl p-4 border border-blue-100/50 hover:border-blue-200 transition-all">
                            <div class="flex gap-3">
                                <div
                                    class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm text-blue-900 font-bold mb-1">{{ $policy->title }}</h4>
                                    <p class="text-[12px] text-blue-800/60 leading-relaxed">{{ $policy->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 py-8 italic text-sm">ยังไม่มีข้อมูลนโยบาย</p>
                    @endforelse
                </div>
            </div>

            <!-- Operations Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 reveal reveal-right">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-clipboard-list text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-emerald-900 leading-tight">หมวดหมู่การดำเนินงาน</h3>
                        <p class="text-[11px] text-slate-400">ขั้นตอนและแนวทางการปฏิบัติงาน</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($operations as $op)
                        <div
                            class="bg-emerald-50/50 rounded-xl p-4 border border-emerald-100/50 hover:border-emerald-200 transition-all">
                            <div class="flex gap-3">
                                <div
                                    class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm text-emerald-900 font-bold mb-1">{{ $op->title }}</h4>
                                    <p class="text-[12px] text-emerald-800/60 leading-relaxed">{{ $op->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 py-8 italic text-sm">ยังไม่มีข้อมูลการดำเนินงาน</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ============ NEWS SECTION ============ --}}
    <div class="bg-slate-50/70 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div id="news" class="scroll-mt-20 flex items-center justify-between mb-6 reveal">
                <div class="header-hover group">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-sm group-hover:rotate-12 transition-transform duration-300">
                            <i class="fa-solid fa-newspaper text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">ข่าวสาร/ประชาสัมพันธ์</h2>
                            <p class="text-xs text-slate-400">อัปเดตข่าวสารล่าสุด</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('datamanage.news.newsalllist') }}"
                    class="text-sm text-red-600 hover:text-red-700 font-semibold hover:underline flex items-center gap-1.5 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm hover:shadow transition-all">
                    ดูทั้งหมด <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

@if(isset($news) && $news->count())
    @php
        $mainNews = $news->first();
        $sideNews = $news->slice(1, 5);
        $bottomNews = $news->slice(6, 3);
        
        $processImages = function($item) {
            $paths = [];
            if (method_exists($item, 'imagePaths')) {
                $paths = (array) $item->imagePaths();
            } else {
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
            }
            $toUrl = function ($p) {
                $p = str_replace('\\', '/', (string) $p);
                if ($p === '') return null;
                $isAbsolute = preg_match('#^(https?:)?//#', $p) === 1;
                return $isAbsolute ? $p : asset(ltrim($p, '/'));
            };
            return array_values(array_filter(array_map($toUrl, $paths)));
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        {{-- FEATURED MAIN NEWS --}}
        @if($mainNews)
            @php
                $imgs = $processImages($mainNews);
                $fUrl = $imgs[0] ?? asset('images/welcome/news1.jpg');
                $mId = 'main-news-img-' . $mainNews->id;
            @endphp
            <div class="lg:col-span-8 group">
                <a href="{{ route('datamanage.news.detail', $mainNews) }}" class="block h-full bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col">
                    <div class="relative h-[480px] overflow-hidden">
                        <img id="{{ $mId }}" src="{{ $fUrl }}" data-images='@json($imgs)' alt="Featured News" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute top-6 left-6">
                            <span class="bg-red-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-xl uppercase tracking-widest">Featured Story</span>
                        </div>
                    </div>
                    <div class="p-10 -mt-20 relative z-10 bg-white mx-6 rounded-t-[2.5rem] flex-grow">
                        <div class="flex items-center gap-4 text-slate-400 text-xs font-bold mb-4 uppercase tracking-widest">
                            <time class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar-days text-red-500"></i>
                                {{ $mainNews->published_date ? $mainNews->published_date->format('d M Y') : '' }}
                            </time>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span>
                            <span>HAMS UPDATE</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-4 group-hover:text-red-600 transition-colors leading-tight">{{ $mainNews->title }}</h2>
                        <p class="text-slate-500 leading-relaxed text-lg line-clamp-3 mb-6">{{ strip_tags($mainNews->content) }}</p>
                        <span class="text-red-500 font-black text-sm uppercase tracking-widest flex items-center gap-2">
                            Read Full Article <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            </div>
            <script>
                (function() {
                    var el = document.getElementById(@json($mId));
                    if (!el) return;
                    var list = JSON.parse(el.dataset.images || '[]');
                    if (list.length <= 1) return;
                    var i = 0; setInterval(function() { i = (i + 1) % list.length; el.src = list[i]; }, 4000);
                })();
            </script>
        @endif

        {{-- SIDEBAR LIST --}}
        <div class="lg:col-span-4 space-y-4">
            @foreach($sideNews as $item)
                @php
                    $imgs = $processImages($item);
                    $fUrl = $imgs[0] ?? asset('images/welcome/news1.jpg');
                @endphp
                <a href="{{ route('datamanage.news.detail', $item) }}" class="group flex gap-4 p-4 bg-white rounded-2xl border border-slate-100 hover:shadow-xl hover:shadow-slate-200/50 transition-all">
                    <div class="w-24 h-24 rounded-xl overflow-hidden shrink-0">
                        <img src="{{ $fUrl }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="News">
                    </div>
                    <div class="flex flex-col justify-center overflow-hidden">
                        <time class="text-[10px] text-slate-400 font-bold mb-1">{{ $item->published_date ? $item->published_date->format('d M Y') : '' }}</time>
                        <h3 class="text-sm font-black text-slate-800 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors uppercase tracking-tight">{{ $item->title }}</h3>
                        <div class="mt-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="w-1 h-1 rounded-full bg-red-400"></div>
                            <div class="w-1 h-1 rounded-full bg-red-600"></div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- BOTTOM ROW --}}
    @if($bottomNews->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bottomNews as $item)
                @php
                    $imgs = $processImages($item);
                    $fUrl = $imgs[0] ?? asset('images/welcome/news1.jpg');
                    $imgId = 'bottom-news-img-' . $item->id;
                @endphp
                <a href="{{ route('datamanage.news.detail', $item) }}" class="group bg-white rounded-[2rem] overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500">
                    <div class="h-48 overflow-hidden relative">
                        <img id="{{ $imgId }}" src="{{ $fUrl }}" data-images='@json($imgs)' alt="News" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute bottom-4 left-4">
                             <time class="bg-black/50 backdrop-blur-md text-white text-[10px] px-3 py-1 rounded-full font-bold">{{ $item->published_date ? $item->published_date->format('d M Y') : '' }}</time>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-black text-slate-900 mb-3 line-clamp-2 leading-tight group-hover:text-red-600 transition-colors">{{ $item->title }}</h3>
                        <p class="text-slate-500 text-xs line-clamp-2 leading-relaxed">{{ strip_tags($item->content) }}</p>
                    </div>
                </a>
                <script>
                    (function() {
                        var el = document.getElementById(@json($imgId));
                        if (!el) return;
                        var list = JSON.parse(el.dataset.images || '[]');
                        if (list.length <= 1) return;
                        var i = 0; setInterval(function() { i = (i + 1) % list.length; el.src = list[i]; }, 3500);
                    })();
                </script>
            @endforeach
        </div>
    @endif
@else
    <div class="text-sm text-slate-400 bg-white rounded-2xl border border-slate-100 p-10 text-center">
        <i class="fa-solid fa-newspaper text-2xl text-slate-200 mb-3 block"></i>
        ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้
    </div>
@endif
        </div>
    </div>

    {{-- ============ ANNOUNCEMENT LIST SECTION ============ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 reveal">
        <div id="announcements-list" class="scroll-mt-20 mb-6 flex items-center justify-between">
            <div class="header-hover group">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-sm group-hover:rotate-12 transition-transform duration-300">
                        <i class="fa-solid fa-bullhorn text-white text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">ประกาศสำคัญ</h2>
                        <p class="text-xs text-slate-400">อัปเดตแจ้งเตือนล่าสุด</p>
                    </div>
                </div>
            </div>
            
            <!-- <div class="hidden sm:flex items-center gap-2 text-slate-300">
                <span class="text-[10px] font-black uppercase tracking-widest">Explore All</span>
                <i class="fa-solid fa-arrow-right-long animate-pulse"></i>
            </div> -->
        </div>

        @if(isset($announcements) && $announcements->count())
            <div id="announcementScrollContainer" class="flex space-x-6 overflow-x-auto pb-4 scroll-hide snap-x snap-mandatory scroll-smooth">
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
                    <button onclick='openAnnouncementModal(@json($annData))' class="text-left bg-white rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-red-500/10 hover:-translate-y-2 transition-all duration-500 group flex flex-col h-full active:scale-[0.98] min-w-[320px] w-[320px] snap-center flex-shrink-0 overflow-hidden">
                        {{-- Top: Image --}}
                        <div class="h-44 w-full overflow-hidden bg-slate-50 relative">
                            @if($ann->image_path)
                                <img src="{{ asset($ann->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Preview">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                    <i class="fa-solid fa-bullhorn text-4xl text-slate-200"></i>
                                </div>
                            @endif
                            @if($ann->is_urgent)
                                <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-red-600 flex items-center justify-center shadow-lg animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-white text-xs"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Body: Text Content --}}
                        <div class="p-8 flex flex-col flex-grow">
                            <div class="flex flex-col mb-4">
                                <span class="text-red-500 font-black text-[10px] tracking-widest uppercase mb-1">OFFICIAL NOTICE</span>
                                <time class="text-slate-400 text-[10px] font-bold">{{ $ann->published_date ? $ann->published_date->format('d M Y') : '' }}</time>
                            </div>
                            
                            <h3 class="text-[1.1rem] font-black text-slate-900 mb-4 line-clamp-2 leading-[1.3] group-hover:text-red-600 transition-colors uppercase tracking-tight">{{ $ann->title }}</h3>
                            <p class="text-slate-500 text-[13px] line-clamp-3 mb-8 flex-grow leading-[1.6]">{{ strip_tags($ann->content) }}</p>
                            
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                <span class="text-red-600 text-[11px] font-black uppercase tracking-widest group-hover:gap-3 flex items-center gap-2 transition-all">
                                    READ MORE <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                </span>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Slider Controls (Below Cards) --}}
            @if($announcements->count() > 3)
                <div class="flex items-center justify-center gap-10 mt-8">
                    <button onclick="scrollAnnouncements('left')" class="w-12 h-12 rounded-full border border-slate-100 bg-white text-slate-300 hover:text-red-600 hover:border-red-600 transition-all flex items-center justify-center shadow-xl shadow-slate-200/20 active:scale-90 group cursor-pointer">
                        <i class="fa-solid fa-chevron-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                    </button>
                    
                    <div class="flex gap-2 opacity-30">
                        <div class="w-1 h-1 rounded-full bg-slate-400"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                        <div class="w-1 h-1 rounded-full bg-slate-400"></div>
                    </div>

                    <button onclick="scrollAnnouncements('right')" class="w-12 h-12 rounded-full border border-slate-100 bg-white text-slate-300 hover:text-red-600 hover:border-red-600 transition-all flex items-center justify-center shadow-xl shadow-slate-200/20 active:scale-90 group cursor-pointer">
                        <i class="fa-solid fa-chevron-right text-sm group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            @endif
        @else
            <div class="bg-slate-50/50 rounded-[3.5rem] border-2 border-dashed border-slate-200 p-24 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-8 shadow-sm">
                    <i class="fa-solid fa-bullhorn text-4xl text-slate-200"></i>
                </div>
                <p class="text-slate-400 font-black text-xl uppercase tracking-widest leading-none">ยังไม่มีประกาศใหม่</p>
                <p class="text-slate-300 text-sm mt-3">ระบบยังไม่มีรายการประกาศที่แสดงให้เห็นในขณะนี้</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function scrollAnnouncements(direction) {
                const container = document.getElementById('announcementScrollContainer');
                if (!container) return;
                
                const scrollAmount = 344; // Card width (320) + gap (24)
                if (direction === 'left') {
                    container.scrollLeft -= scrollAmount;
                } else {
                    container.scrollLeft += scrollAmount;
                }
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