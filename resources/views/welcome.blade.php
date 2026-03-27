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
    </style>

    {{-- ============ HERO BANNER (Full Width Slider) ============ --}}
    <div class="-mx-6 -mt-6 mb-12">
        <div class="hero-banner shadow-2xl relative h-[calc(100vh-60px)] min-h-[400px]">

            {{-- Slider Images --}}
            <div id="hero-slider">
                <div class="hero-slide active">
                    <img src="{{ asset('images/welcome/whams.jpg') }}" alt="HAMS Building">
                </div>
                <div class="hero-slide">
                    <img src="{{ asset('images/welcome/kmlhq.jpg') }}" alt="Kumwell HQ">
                </div>
                <div class="hero-slide">
                    <img src="{{ asset('images/welcome/servicehams.jpg') }}" alt="Service Equipment">
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
            <div class="hidden lg:flex flex-col items-center gap-6 animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="preview-box">
                    <div id="preview-slider">
                        <div class="preview-slide active">
                            <img src="{{ asset('images/welcome/whams.jpg') }}" alt="Preview 1">
                        </div>
                        <div class="preview-slide">
                            <img src="{{ asset('images/welcome/kmlhq.jpg') }}" alt="Preview 2">
                        </div>
                        <div class="preview-slide">
                            <img src="{{ asset('images/welcome/servicehams.jpg') }}" alt="Preview 3">
                        </div>
                    </div>
                </div>

                {{-- Slider Controls (Dots) --}}
                <div class="flex gap-4" id="hero-dots">
                    <button
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                    <button
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-40 hover:opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                    <button
                        class="w-2.5 h-2.5 rounded-full bg-white shadow-md opacity-40 hover:opacity-100 transition-all hover:scale-125 focus:outline-none"></button>
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/50 animate-bounce z-20 cursor-pointer lg:left-1/3"
            onclick="document.getElementById('services').scrollIntoView({behavior: 'smooth'})">
            <span class="text-[10px] tracking-widest uppercase font-bold">เลื่อนลง</span>
            <i class="fa-solid fa-chevron-down text-sm"></i>
        </div>
    </div>
    </div>

    {{-- ============ SERVICES SECTION ============ --}}
    <div class="bg-slate-50/70 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div id="services" class="scroll-mt-20 mb-6">
                <div class="flex items-center gap-3 mb-1">
                    <div
                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-grip text-white text-sm"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">งานสนับสนุน</h2>
                </div>
                <p class="text-xs text-slate-400 ml-12">ระบบบริการสนับสนุนภายในองค์กร</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
                {{-- Card 1 --}}
                <a href="{{ route('items.itemsalllist') }}"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block">
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
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/bookingmeet.jpg') }}" alt="จองห้องประชุม"
                            class="w-full h-40 object-cover">
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
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/bookingcar.jpg') }}" alt="จองรถส่วนกลาง"
                            class="w-full h-40 object-cover">
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
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block">
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
                @if(Auth::check() && (in_array(Auth::user()->department_id, ['12', '14']) || Auth::user()->employee_code == '11648'))
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
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-1">
                <div
                    class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-book-open text-white text-sm"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800">นโยบายและการดำเนินงาน</h2>
            </div>
            <p class="text-xs text-slate-400 ml-12">ข้อกำหนด แนวทางปฏิบัติ และขั้นตอนการทำงาน</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Policy Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
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
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
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
            <div id="news" class="scroll-mt-20 flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-lg bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-newspaper text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">ข่าวสาร/ประชาสัมพันธ์</h2>
                        <p class="text-xs text-slate-400">อัปเดตข่าวสารล่าสุด</p>
                    </div>
                </div>
                <a href="{{ route('datamanage.news.newsalllist') }}"
                    class="text-sm text-red-600 hover:text-red-700 font-semibold hover:underline flex items-center gap-1.5 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm hover:shadow transition-all">
                    ดูทั้งหมด <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if(isset($news) && $news->count())
                <div class="flex space-x-5 overflow-x-auto pb-4 scroll-hide snap-x snap-mandatory">
                    @foreach($news as $item)
                        @php
                            $badgeColors = [
                                'ประกาศ' => 'bg-green-600',
                                'กิจกรรม' => 'bg-yellow-600',
                                'ข่าว' => 'bg-blue-600',
                                'แจ้ง' => 'bg-indigo-600',
                            ];
                            $badgeClass = $badgeColors[$item->newto ?? ''] ?? 'bg-gray-600';

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
                                if ($p === '')
                                    return null;
                                $isAbsolute = preg_match('#^(https?:)?//#', $p) === 1;
                                return $isAbsolute ? $p : asset(ltrim($p, '/'));
                            };
                            $imageUrls = array_values(array_filter(array_map($toUrl, $paths)));
                            $firstUrl = $imageUrls[0] ?? asset('images/welcome/news1.jpg');
                            $imgId = 'news-img-' . ($item->id ?? $loop->index);
                            $dotsId = 'news-dots-' . ($item->id ?? $loop->index);
                        @endphp

                        <article
                            class="news-item min-w-[300px] w-[300px] bg-white rounded-2xl shadow-sm overflow-hidden snap-center flex-shrink-0 border border-slate-100/80">
                            <a href="{{ route('datamanage.news.detail', $item) }}" class="block">
                                <div class="relative overflow-hidden">
                                    <img id="{{ $imgId }}" src="{{ $firstUrl }}" data-images='@json($imageUrls)' alt="ภาพข่าว"
                                        class="news-thumb w-full h-44 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                    <span
                                        class="absolute top-3 left-3 {{ $badgeClass }} text-white text-[10px] font-bold px-3 py-1 rounded-full shadow">
                                        {{ $item->newto ?? 'ข่าว' }}
                                    </span>
                                    @if(count($imageUrls) > 1)
                                        <div class="absolute bottom-3 right-3 flex items-center gap-1" id="{{ $dotsId }}">
                                            @foreach($imageUrls as $key => $url)
                                                <button
                                                    class="w-2 h-2 rounded-full bg-white transition-opacity duration-300 {{ $key === 0 ? 'opacity-100' : 'opacity-40' }}"
                                                    data-index="{{ $key }}"></button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-[10px] text-slate-400 mb-1">{{ $item->newto ?? 'ข่าว' }}</p>
                                    <h3 class="text-sm font-bold text-slate-800 mb-2 leading-snug line-clamp-2">{{ $item->title }}
                                    </h3>
                                    <p class="text-[11px] text-slate-400 mb-3 leading-relaxed line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 110) }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px]">
                                        <time class="text-slate-400 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            {{ $item->published_date ? $item->published_date->format('d/m/Y') : '' }}
                                        </time>
                                        <span class="text-red-500 font-semibold">อ่านต่อ →</span>
                                    </div>
                                </div>
                            </a>
                        </article>

                        <script>
                            (function () {
                                var el = document.getElementById(@json($imgId));
                                if (!el) return;
                                var list = [];
                                try { list = JSON.parse(el.dataset.images || '[]'); } catch (e) { }
                                if (!Array.isArray(list) || list.length <= 1) return;
                                var i = 0;
                                setInterval(function () {
                                    i = (i + 1) % list.length;
                                    el.src = list[i];
                                }, 3000);
                            })();
                        </script>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-slate-400 bg-white rounded-2xl border border-slate-100 p-10 text-center">
                    <i class="fa-solid fa-newspaper text-2xl text-slate-200 mb-3 block"></i>
                    ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้
                </div>
            @endif
        </div>
    </div>
@endsection