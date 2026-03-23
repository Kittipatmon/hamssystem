@extends('layouts.app')
@section('content')
    <style>
        .hero-section {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 50%, #7f1d1d 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .service-card {
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .service-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #dc2626, #ef4444);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .service-card:hover::after {
            transform: scaleX(1);
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(220, 38, 38, 0.15);
            border-color: #fecaca;
        }

        .service-icon-wrap {
            background: linear-gradient(135deg, #fef2f2, #fff);
            transition: all 0.4s ease;
        }

        .service-card:hover .service-icon-wrap {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .service-card:hover .service-icon-wrap i {
            color: white !important;
        }

        .news-card {
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.12);
        }

        .news-card:hover .news-img {
            transform: scale(1.05);
        }

        .news-img {
            transition: transform 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .animate-delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .animate-delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .animate-delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .animate-delay-5 {
            animation-delay: 0.5s;
            opacity: 0;
        }

        .animate-delay-6 {
            animation-delay: 0.6s;
            opacity: 0;
        }

        .scroll-container {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scroll-container::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- ============ HERO SECTION ============ --}}
        <div class="hero-section rounded-2xl shadow-xl p-8 md:p-12 text-white relative z-10 animate-fadeInUp">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex-1 text-center md:text-left">
                    <div
                        class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4 border border-white/20">
                        <i class="fa-solid fa-building"></i> HAMS System
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight tracking-tight mb-3">
                        Human Asset Management<br>& Service Building
                    </h1>
                    <p class="text-white/80 text-sm md:text-base leading-relaxed max-w-lg">
                        ระบบจัดการและบำรุงรักษาอาคาร — จัดการอุปกรณ์ ห้องประชุม รถส่วนกลาง และบริการต่างๆ ในที่เดียว
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-start">
                        <div
                            class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg text-xs border border-white/10">
                            <i class="fa-solid fa-shield-check text-emerald-300"></i> ปลอดภัย
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg text-xs border border-white/10">
                            <i class="fa-solid fa-bolt text-yellow-300"></i> รวดเร็ว
                        </div>
                        <div
                            class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg text-xs border border-white/10">
                            <i class="fa-solid fa-clock text-sky-300"></i> 24/7
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 hidden md:block">
                    <img src="{{ asset('images/welcome/whams.jpg') }}" alt="Welcome"
                        class="w-72 h-48 object-cover rounded-xl shadow-lg border-2 border-white/20">
                </div>
            </div>
        </div>

        {{-- ============ SERVICES SECTION ============ --}}
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1 h-7 bg-gradient-to-b from-red-500 to-red-700 rounded-full"></div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">บริการทั้งหมด</h2>
                <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                {{-- Card 1: เบิกอุปกรณ์ --}}
                <a href="{{ route('items.itemsalllist') }}"
                    class="service-card rounded-2xl p-5 block animate-fadeInUp animate-delay-1">
                    <div class="flex items-start gap-4">
                        <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-box-open text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="inline-block bg-green-50 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">พร้อมใช้งาน</span>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบเบิกอุปกรณ์สำนักงาน</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการเบิกอุปกรณ์ ติดตามสถานะ
                                และควบคุมสต็อก</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                        <span
                            class="text-[11px] font-semibold text-red-500 flex items-center gap-1 group-hover:gap-2 transition-all">
                            เข้าใช้งาน <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>

                {{-- Card 2: จองห้องประชุม --}}
                <a href="{{ route('reservations.welcomemeeting') }}"
                    class="service-card rounded-2xl p-5 block animate-fadeInUp animate-delay-2">
                    <div class="flex items-start gap-4">
                        <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-door-open text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="inline-block bg-green-50 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">พร้อมใช้งาน</span>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบจองห้องประชุม</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการจองห้องประชุม ติดตามสถานะการใช้งาน
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                        <span class="text-[11px] font-semibold text-red-500 flex items-center gap-1">
                            เข้าใช้งาน <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>

                {{-- Card 3: จองรถ --}}
                <a href="{{ route('bookingcar.welcome') }}"
                    class="service-card rounded-2xl p-5 block animate-fadeInUp animate-delay-3">
                    <div class="flex items-start gap-4">
                        <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-car-side text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="inline-block bg-green-50 text-green-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">พร้อมใช้งาน</span>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบจองรถส่วนกลาง</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการจองรถส่วนกลาง ติดตามสถานะการใช้งาน
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                        <span class="text-[11px] font-semibold text-red-500 flex items-center gap-1">
                            เข้าใช้งาน <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>

                {{-- Card 4: แจ้งซ่อม --}}
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')"
                    class="service-card rounded-2xl p-5 block cursor-pointer animate-fadeInUp animate-delay-4">
                    <div class="flex items-start gap-4">
                        <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-wrench text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="inline-block bg-orange-50 text-orange-500 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">กำลังพัฒนา</span>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบแจ้งซ่อม</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการแจ้งซ่อม ติดตามสถานะการดำเนินการ</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                        <span class="text-[11px] font-semibold text-orange-400 flex items-center gap-1">
                            เร็วๆ นี้ <i class="fa-solid fa-clock text-[10px]"></i>
                        </span>
                    </div>
                </a>

                {{-- Card 5: บ้านพัก --}}
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')"
                    class="service-card rounded-2xl p-5 block cursor-pointer animate-fadeInUp animate-delay-5">
                    <div class="flex items-start gap-4">
                        <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-house-chimney text-red-500 text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span
                                class="inline-block bg-orange-50 text-orange-500 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">กำลังพัฒนา</span>
                            <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบบ้านพักพนักงาน</h3>
                            <p class="text-[11px] text-slate-400 leading-relaxed">จัดการบ้านพักพนักงาน ติดตามสถานะการใช้งาน
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                        <span class="text-[11px] font-semibold text-orange-400 flex items-center gap-1">
                            เร็วๆ นี้ <i class="fa-solid fa-clock text-[10px]"></i>
                        </span>
                    </div>
                </a>

                {{-- Card 6: จัดการข้อมูล (Admin only) --}}
                @if(Auth::check() && (in_array(Auth::user()->department_id, ['12', '14']) || Auth::user()->employee_code == '11648'))
                    <a href="{{ route('datamanage.welcomedatamanage') }}"
                        class="service-card rounded-2xl p-5 block animate-fadeInUp animate-delay-6">
                        <div class="flex items-start gap-4">
                            <div class="service-icon-wrap w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                style="background: linear-gradient(135deg, #eef2ff, #fff);">
                                <i class="fa-solid fa-database text-indigo-500 text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span
                                    class="inline-block bg-indigo-50 text-indigo-600 text-[10px] font-bold px-2 py-0.5 rounded-full mb-1.5">หลังบ้าน</span>
                                <h3 class="text-sm font-bold text-slate-800 mb-1 leading-snug">ระบบจัดการข้อมูลทั่วไป</h3>
                                <p class="text-[11px] text-slate-400 leading-relaxed">จัดการข้อมูลพนักงาน อาคาร และอุปกรณ์</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-50 flex items-center justify-end">
                            <span class="text-[11px] font-semibold text-indigo-500 flex items-center gap-1">
                                เข้าใช้งาน <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        {{-- ============ NEWS SECTION ============ --}}
        <div class="animate-fadeInUp animate-delay-3">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-1 h-7 bg-gradient-to-b from-red-500 to-red-700 rounded-full"></div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">ข่าวสารล่าสุด</h2>
                </div>
                <a href="{{ route('datamanage.news.newsalllist') }}"
                    class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-700 font-semibold hover:bg-red-50 px-4 py-2 rounded-xl transition-colors">
                    ดูทั้งหมด <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if(isset($news) && $news->count())
                <div class="flex space-x-5 overflow-x-auto pb-4 scroll-container snap-x snap-mandatory">
                    @foreach($news as $item)
                        @php
                            $badgeColors = [
                                'ประกาศ' => 'bg-emerald-500',
                                'กิจกรรม' => 'bg-amber-500',
                                'ข่าว' => 'bg-blue-500',
                                'แจ้ง' => 'bg-indigo-500',
                            ];
                            $badgeClass = $badgeColors[$item->newto ?? ''] ?? 'bg-slate-500';

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
                        @endphp

                        <article
                            class="news-card min-w-[300px] w-[300px] bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden snap-center flex-shrink-0">
                            <a href="{{ route('datamanage.news.detail', $item) }}" class="block">
                                <div class="relative overflow-hidden">
                                    <img id="{{ $imgId }}" src="{{ $firstUrl }}" data-images='@json($imageUrls)' alt="ภาพข่าว"
                                        class="news-img w-full h-44 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                                    <span
                                        class="absolute top-3 left-3 {{ $badgeClass }} text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                                        {{ $item->newto ?? 'ข่าว' }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-sm font-bold text-slate-800 mb-2 leading-snug line-clamp-2">{{ $item->title }}
                                    </h3>
                                    <p class="text-[11px] text-slate-400 mb-3 leading-relaxed line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 100) }}
                                    </p>
                                    <div class="flex items-center justify-between text-[11px]">
                                        <time class="text-slate-400 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar-days"></i>
                                            {{ $item->published_date ? $item->published_date->format('d/m/Y') : '' }}
                                        </time>
                                        <span class="text-red-500 font-semibold flex items-center gap-1">
                                            อ่านต่อ <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                        </span>
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
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-newspaper text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm text-slate-400">ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้</p>
                </div>
            @endif
        </div>

    </div>
@endsection