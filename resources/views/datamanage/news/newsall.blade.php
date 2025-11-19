@extends('layouts.app')
@section('content')
<div class="max-w-8xl mx-auto">
	<div class="mb-6 rounded-xl px-6 py-6 shadow-lg relative overflow-hidden">
		<div class="absolute inset-0 opacity-20 mix-blend-overlay bg-[url('https://www.transparenttextures.com/patterns/asfalt-dark.png')]"></div>
		<div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
			<div>
				<div class="breadcrumbs text-xs  mb-2">
					<ul class="flex space-x-2">
						<li><a class="hover:underline" href="{{ route('welcome') }}">Home</a></li>
						<li class="opacity-75">ข่าวสารทั้งหมด</li>
					</ul>
				</div>
				<h1 class="text-3xl font-extrabold tracking-tight drop-shadow-sm">ข่าวสารทั้งหมด</h1>
				<p class="mt-1 text-sm">แสดงข่าวสารที่เผยแพร่ทั้งหมดในระบบ</p>
			</div>
			<div class="w-full md:w-auto">
				<label class="sr-only" for="search">ค้นหาข่าวสาร</label>
				<div class="relative group">
					<input id="search" name="search" type="text" placeholder="ค้นหาข่าวสาร..." class="w-full md:w-72 rounded-lg bg-white/95 backdrop-blur border border-red-600/50 focus:border-red-800 focus:ring-2 focus:ring-red-500/60 px-4 py-2 text-sm shadow-inner transition placeholder:text-red-400/70" onchange="if(this.value) { window.location.href='{{ route('datamanage.news.newsalllist') }}?search=' + encodeURIComponent(this.value); } else { window.location.href='{{ route('datamanage.news.newsalllist') }}'; }" value="{{ request('search', '') }}">
					<span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-red-500 group-focus-within:text-red-600">
						<i class="fa-solid fa-magnifying-glass"></i>
					</span>
				</div>
			</div>
		</div>
	</div>

	@if(isset($news) && $news->count())
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
			@foreach($news as $item)
				@php
					$badgeClass = 'bg-gradient-to-r from-red-600 to-red-500 shadow-sm';

					// Normalize image paths (supports array, json, comma-separated or single string)
					$paths = [];
					$raw = $item->image_path ?? '';
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
					if (empty($paths)) { $paths = ['images/welcome/news1.jpg']; }

					// Build cover image url from first path
					$first = (string)($paths[0] ?? '');
					if ($first !== '' && preg_match('/^(https?:)?\/\//i', $first)) {
						$coverUrl = $first;
					} else {
						$slashed = str_replace('\\', '/', $first);
						$normalized = ltrim(preg_replace('#^/?(?:public/)?#i', '', $slashed), '/');
						$coverUrl = asset($normalized ?: 'images/welcome/news1.jpg');
					}

					// Route param safety (model uses primary key news_id)
					$routeParams = ['news' => $item->getAttribute('news_id')];
				@endphp

					<article class="group bg-white rounded-xl shadow-sm ring-1 ring-red-100/40 overflow-hidden transition-all duration-300 hover:shadow-red-200/70 hover:shadow-lg hover:-translate-y-1">
						<a href="{{ route('datamanage.news.detail', $routeParams) }}" class="block focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500/70">
							<div class="relative">
								<img src="{{ $coverUrl }}" alt="ภาพข่าว" class="w-full h-56 object-cover object-center transition duration-300 group-hover:scale-[1.02]">
								<span class="absolute top-3 left-3 {{ $badgeClass }} text-white text-[10px] tracking-wide font-semibold px-3 py-1 rounded-full backdrop-blur-sm bg-opacity-90">
									{{ $item->newto ?? 'ข่าว' }}
								</span>
								<div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent opacity-0 group-hover:opacity-50 transition"></div>
							</div>
							<div class="p-4 flex flex-col h-48">
								<div class="flex items-center justify-between text-[11px] text-gray-500 mb-1">
									<time datetime="{{ $item->published_date ? $item->published_date->toDateString() : '' }}" class="flex items-center">
										<i class="fa-regular fa-calendar-alt mr-1 text-red-500"></i>
										{{ optional($item->published_date)->format('d/m/Y') }}
									</time>
									@if($item->is_active)
										<span class="px-2 py-0.5 rounded-full bg-red-50 text-red-600 font-medium">เผยแพร่</span>
									@else
										<span class="px-2 py-0.5 rounded-full bg-gray-50 text-gray-600 font-medium">ปิด</span>
									@endif
								</div>
								<h3 class="text-sm font-semibold mb-2 line-clamp-2 text-gray-900 group-hover:text-red-600 transition">{{ $item->title }}</h3>
								<p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 140) }}</p>
								<div class="mt-auto pt-3 text-xs font-medium text-red-600 flex items-center gap-1 group-hover:gap-2 transition">
									<span>อ่านต่อ</span>
									<i class="fa-solid fa-arrow-right"></i>
								</div>
							</div>
						</a>
					</article>
			@endforeach
		</div>
	@else
		<div class="p-8 bg-white/80 backdrop-blur rounded-xl shadow text-gray-600 text-sm border border-red-100 flex flex-col items-center">
			<i class="fa-regular fa-newspaper text-4xl text-red-400 mb-3"></i>
			<p class="font-medium text-gray-700">ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้</p>
		</div>
	@endif
</div>
@endsection