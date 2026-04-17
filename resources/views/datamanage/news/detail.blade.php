@extends('layouts.datamanagement.app')

@section('content')
<!-- <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-gray-900 to-white -z-10"></div> -->

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="breadcrumbs text-sm text-gray-600 my-6">
        <ul class="inline-flex items-center">
            <li>
                <a href="{{ route('welcome') }}" class="hover:text-white transition-colors">Home</a>
            </li>
            <li>
                <a href="{{ route('datamanage.news.newsalllist') }}" class="hover:text-white transition-colors">ข่าวสารทั้งหมด</a>
            </li>
            <li class="text-red-500">
                {{ $news->title }}
            </li>
        </ul>
</div>

    <article class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 relative">
        
        @php
            $paths = [];
            if (is_object($news) && method_exists($news, 'imagePaths')) {
                $paths = (array) $news->imagePaths();
            } else {
                $raw = is_object($news) ? ($news->image_path ?? ($news->primaryImagePath() ?? '')) : '';
                if (is_array($raw)) {
                    $paths = $raw;
                } elseif (is_string($raw) && $raw !== '') {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $paths = $decoded;
                    } else {
                        $paths = array_filter(array_map('trim', preg_split('/\s*,\s*/', $raw)));
                    }
                }
            }
            // Fallback image
            if (empty($paths)) {
                $paths = ['images/welcome/news1.jpg'];
            }

            $imageUrls = [];
            foreach ($paths as $p) {
                $p = (string) $p;
                if ($p === '') continue;
                if (preg_match('/^(https?:)?\/\//i', $p)) {
                    $imageUrls[] = $p;
                } else {
                    $slashed = str_replace('\\', '/', $p);
                    $normalized = preg_replace('#^/?(?:public/)?#i', '', $slashed);
                    $normalized = ltrim($normalized, '/');
                    $imageUrls[] = asset($normalized);
                }
            }
            if (empty($imageUrls)) {
                $imageUrls[] = asset('images/welcome/news1.jpg');
            }
            $carouselId = 'carousel_' . uniqid();
        @endphp

        <div class="relative group bg-black">
            <div id="{{ $carouselId }}" class="carousel w-full h-[400px] md:h-[500px] overflow-x-auto scroll-smooth flex snap-x snap-mandatory scrollbar-hide">
                @foreach($imageUrls as $url)
                <div class="carousel-item w-full flex-shrink-0 snap-center relative">
                    <img src="{{ $url }}" alt="รูปข่าว" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>
                </div>
                @endforeach
            </div>
            
            <div class="absolute bottom-4 right-4 flex space-x-1">
                @foreach($imageUrls as $key => $url)
                    <div class="w-2 h-2 rounded-full bg-white/50 active-dot-{{$carouselId}}" data-index="{{$key}}"></div>
                @endforeach
            </div>
        </div>

        <div class="p-8 md:p-12 relative">
            <div class="flex flex-wrap items-center gap-3 mb-6 -mt-16 relative z-10">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-xs shadow-lg flex items-center">
                    <i class="fa-regular fa-calendar-alt mr-2 text-red-400"></i>
                    {{ optional($news->published_date)->format('d M Y') }}
                </div>

                <div class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-3 py-1 rounded-full text-xs shadow-lg flex items-center">
                    <i class="fa-regular fa-eye mr-2 text-blue-400"></i>
                    {{ number_format($news->views_count ?? 0) }}
                </div>
                
                @if($news->newto)
                    <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg shadow-red-600/30">
                        {{ $news->newto }}
                    </span>
                @endif

                <!-- <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider border {{ $news->is_active ? 'border-green-500 text-green-500 bg-green-50' : 'border-gray-400 text-gray-400 bg-gray-50' }}">
                    {{ $news->is_active ? 'Published' : 'Draft' }}
                </span> -->
            </div>

            <header class="mb-4 border-l-4 border-red-600 pl-6">
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 leading-tight">
                    {{ $news->title }}
                </h1>
            </header>

            <div class="prose prose-lg prose-red max-w-none text-sm text-gray-600 leading-relaxed">
                {!! nl2br(e($news->content)) !!}
            </div>

            <hr class="my-10 border-gray-100">

            <div class="bg-gray-50 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-4 border border-gray-100">
                <div class="flex items-center gap-3 text-gray-700">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-red-500">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm">แชร์ข่าวสารนี้</p>
                        <p class="text-xs text-gray-500">คัดลอกลิงก์เพื่อส่งต่อให้เพื่อนร่วมงาน</p>
                    </div>
                </div>

                <div class="flex w-full md:w-auto max-w-md relative">
                    <input type="text" id="newsLink" value="{{ request()->fullUrl() }}" readonly 
                        class="w-full md:w-80 bg-white border border-gray-300 text-gray-500 text-sm rounded-l-xl focus:ring-red-500 focus:border-red-500 block p-3 pl-4"
                    >
                    <button onclick="copyNewsLink()" 
                        class="bg-gray-900 hover:bg-red-600 text-white font-medium rounded-r-xl text-sm px-6 py-3 transition-all duration-300 flex items-center shadow-lg shadow-gray-900/20 hover:shadow-red-600/40">
                        <i class="fa-regular fa-copy mr-2"></i> คัดลอก
                    </button>
                </div>
            </div>

        </div>
    </article>
</div>

<style>
    /* Hide Scrollbar for clean carousel */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    // Carousel Auto-play Logic
    (function() {
        const el = document.getElementById('{{ $carouselId }}');
        if (!el) return;
        const items = el.querySelectorAll('.carousel-item');
        if (items.length <= 1) return;

        let i = 0;
        const intervalMs = 5000; // ช้าลงนิดนึงเพื่อให้ดู elegant
        let timer = null;

        const go = (idx) => {
            i = (idx + items.length) % items.length;
            // ใช้ behavior smooth เพื่อความลื่นไหล
            items[i].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
        };

        const start = () => {
            stop();
            timer = setInterval(() => go(i + 1), intervalMs);
        };

        const stop = () => {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        };

        el.addEventListener('mouseenter', stop);
        el.addEventListener('mouseleave', start);
        el.addEventListener('touchstart', stop); // มือถือหยุดเมื่อแตะ
        
        setTimeout(start, 300);
    })();

    // Copy Link Logic (Modern with Toast/Alert fallback)
    function copyNewsLink() {
        const copyText = document.getElementById("newsLink");
        
        // ใช้ Clipboard API ถ้าทำได้ (Modern Way)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(copyText.value).then(() => {
                 showToast('คัดลอกลิงก์เรียบร้อยแล้ว!');
            });
        } else {
            // Fallback for older browsers
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            showToast('คัดลอกลิงก์เรียบร้อยแล้ว!');
        }
    }

    // Simple Toast Function (ถ้าไม่มี library toast)
    function showToast(message) {
        // สร้าง element ชั่วคราว
        const div = document.createElement('div');
        div.className = 'fixed bottom-5 right-5 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-xl z-50 animate-bounce transition-opacity duration-300 flex items-center';
        div.innerHTML = `<i class="fa-solid fa-check-circle text-green-400 mr-2"></i> ${message}`;
        document.body.appendChild(div);
        setTimeout(() => {
            div.style.opacity = '0';
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }
</script>
@endsection