@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto">
	<div class="breadcrumbs text-sm">
        <ul>
            <li><a href="{{ route('welcome') }}">Home</a></li>
            <li>
				<a href="{{ route('datamanage.news.newsalllist') }}">ข่าวสารทั้งหมด</a>
			</li>
			<li class="text-red-500">
				{{ $news->title }}
			</li>
        </ul>
    </div>
	<article class="bg-white p-6 rounded-xl shadow border border-gray-200">
		<header class="mb-4">
			<h1 class="text-2xl font-bold mb-2">{{ $news->title }}</h1>
			<div class="text-sm text-gray-500 flex items-center space-x-4">
				<span><i class="fa-regular fa-calendar-alt mr-1"></i>{{ optional($news->published_date)->format('d/m/Y') }}</span>
				@if($news->newto)<span class="px-2 py-1 bg-gray-100 rounded text-gray-700">{{ $news->newto }}</span>@endif
				<span class="px-2 py-1 rounded text-white {{ $news->is_active ? 'bg-green-600' : 'bg-gray-400' }}">{{ $news->is_active ? 'เผยแพร่' : 'ปิด' }}</span>
			</div>
		</header>

		
        <!-- images -->   
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
        @endphp

        <div class="carousel rounded-box w-full">
            @foreach(array_slice($imageUrls, 0, 3) as $url)
			<div class="carousel-item w-full">
                <img src="{{ $url }}" alt="รูปข่าว" class="w-full h-120 object-cover rounded">
			</div>
            @endforeach
            @if(count($imageUrls) > 3)
                <span class="w-16 h-16 flex items-center justify-center text-xs bg-gray-100 rounded">
                    +{{ count($imageUrls) - 3 }}
                </span>
            @endif
        </div>
        <!-- <div class="flex items-center space-x-2">
            @foreach(array_slice($imageUrls, 0, 3) as $url)
                <img src="{{ $url }}" alt="รูปข่าว" class="w-full h-full object-cover rounded">
            @endforeach
            @if(count($imageUrls) > 3)
                <span class="w-16 h-16 flex items-center justify-center text-xs bg-gray-100 rounded">
                    +{{ count($imageUrls) - 3 }}
                </span>
            @endif
        </div> -->
		<!-- end images -->
		<div class="prose max-w-none mt-6">
			{!! nl2br(e($news->content)) !!}
		</div>

		<!-- copy link -->
		<div class="mt-6">
			<label class="block text-sm font-medium mb-1">ลิงก์ข่าวสารนี้</label>
			<div class="flex">
				<input type="text" id="newsLink" value="{{ request()->fullUrl() }}" readonly class="border rounded-l-xl p-2 form-input w-full">
				<button onclick="copyNewsLink()" class="btn btn-primary rounded-r-xl">คัดลอกลิงก์</button>
			</div>
		</div>
		<script>
			function copyNewsLink() {
				const copyText = document.getElementById("newsLink");
				copyText.select();
				copyText.setSelectionRange(0, 99999); // สำหรับมือถือ
				document.execCommand("copy");
				alert("คัดลอกลิงก์ข่าวสารเรียบร้อย: " + copyText.value);
			}
		</script>


	</article>
</div>
@endsection