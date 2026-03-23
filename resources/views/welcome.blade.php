@extends('layouts.app')
@section('content')
    <style>
        .hero-banner {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
        }

        .hero-banner img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
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

        .section-line {
            height: 3px;
            width: 48px;
            background: linear-gradient(90deg, #dc2626, #ef4444);
            border-radius: 999px;
        }

        .scroll-hide {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .scroll-hide::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- ============ HERO BANNER ============ --}}
        <div class="hero-banner shadow-xl">
            <img src="{{ asset('images/welcome/whams.jpg') }}" alt="HAMS Welcome">
            <div class="hero-overlay">
                <div class="max-w-xl">
                    <div
                        class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-4 border border-white/25">
                        <i class="fa-solid fa-building-columns"></i> HAMS&nbsp;System
                    </div>
                    <h1 class="text-white text-3xl md:text-4xl font-bold leading-tight mb-2">
                        Human Asset Management<br class="hidden sm:block">& Service Building
                    </h1>
                    <p class="text-white/85 text-sm md:text-base leading-relaxed">
                        แผนกจัดการและบำรุงรักษาอาคาร — บริหารจัดการอุปกรณ์ ห้องประชุม รถส่วนกลาง ครบจบในที่เดียว
                    </p>
                </div>
            </div>
        </div>

        {{-- ============ SERVICES SECTION ============ --}}
        <div>
            <div class="mb-5">
                <h2 class="group text-xl font-bold text-slate-800 inline-block px-3 py-2 rounded transition-all duration-500 
                                               bg-gradient-to-r from-red-500 to-red-700 bg-[length:0%_100%] bg-no-repeat
                                               hover:bg-[length:100%_100%] hover:text-white">บริการทั้งหมด</h2>
                <div class="section-line mt-2"></div>
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

                {{-- Card 5 --}}
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')"
                    class="svc-card bg-white rounded-2xl shadow-sm overflow-hidden block cursor-pointer">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/welcome/residence.jpg') }}" alt="บ้านพัก"
                            class="w-full h-40 object-cover">
                        <span
                            class="absolute top-3 right-3 bg-orange-400 text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-sm">
                            เร็วๆ นี้
                        </span>
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] text-red-400 font-semibold uppercase tracking-widest mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">ระบบบ้านพักพนักงาน</h3>
                        <p class="text-[11px] text-slate-400 leading-relaxed">จัดการบ้านพักพนักงาน
                            ติดตามสถานะและควบคุมการใช้งาน</p>
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

        {{-- ============ NEWS SECTION ============ --}}
        <div>
            <div class="flex items-center justify-between mb-1">

                <h2 class="group text-xl font-bold text-slate-800 inline-block px-3 py-2 rounded transition-all duration-500 
                                               bg-gradient-to-r from-red-500 to-red-700 bg-[length:0%_100%] bg-no-repeat
                                               hover:bg-[length:100%_100%] hover:text-white">
                    ข่าวสาร/<span
                        class="text-red-600 group-hover:text-white transition-colors duration-300">ประชาสัมพันธ์</span>
                </h2>
                <a href="{{ route('datamanage.news.newsalllist') }}"
                    class="text-sm text-red-600 hover:text-red-700 font-semibold hover:underline flex items-center gap-1">
                    ดูทั้งหมด <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="section-line mb-5"></div>

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