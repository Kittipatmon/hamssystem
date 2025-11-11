@extends('layouts.app')
@section('content')
<div class="max-w-8xl mx-auto">
    <div class="breadcrumbs text-sm">
        <ul>
            <li><a href="{{ route('welcome') }}">Home</a></li>
            <li>ข่าวสารทั้งหมด</li>
        </ul>
    </div>
	<div class="mb-6">
        <div class="flex justify-between space-x-3 mb-1">
            <h1 class="text-2xl font-bold">ข่าวสารทั้งหมด</h1>
            <input name="search" type="text" placeholder="ค้นหาข่าวสาร..." class="border border-gray-300 rounded px-3 py-1 w-64"
                onchange="if(this.value) { window.location.href='{{ route('datamanage.news.newsalllist') }}?search=' + encodeURIComponent(this.value); } else { window.location.href='{{ route('datamanage.news.newsalllist') }}'; }"
                value="{{ request('search', '') }}">
        </div>
		<p class="text-sm text-gray-600">แสดงข่าวสารที่เผยแพร่ทั้งหมด</p>
	</div>

	@if(isset($news) && $news->count())
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
			@foreach($news as $item)
				@php
					$badgeColors = [
						'ประกาศ' => 'bg-green-600',
						'กิจกรรม' => 'bg-yellow-600',
						'ข่าว' => 'bg-blue-600',
						'แจ้ง' => 'bg-indigo-600',
					];
					$badgeClass = $badgeColors[$item->newto ?? ''] ?? 'bg-gray-600';

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

				<article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
					<a href="{{ route('datamanage.news.detail', $routeParams) }}" class="block">
						<div class="relative">
							<img src="{{ $coverUrl }}" alt="ภาพข่าว" class="w-full h-60 object-cover">
							<span class="absolute top-3 left-3 {{ $badgeClass }} text-white text-xs font-semibold px-3 py-1 rounded-full">
								{{ $item->newto ?? 'ข่าว' }}
							</span>
						</div>
						<div class="p-4">
							<div class="flex items-center justify-between text-xs text-gray-500 mb-1">
								<time datetime="{{ $item->published_date ? $item->published_date->toDateString() : '' }}">
									<i class="fa-regular fa-calendar-alt mr-1"></i>
									{{ optional($item->published_date)->format('d/m/Y') }}
								</time>
								@if($item->is_active)
									<span class="px-2 py-0.5 rounded bg-green-100 text-green-700">เผยแพร่</span>
								@else
									<span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600">ปิด</span>
								@endif
							</div>
							<h3 class="text-base font-bold mb-2 line-clamp-2">{{ $item->title }}</h3>
							<p class="text-sm text-gray-700 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 140) }}</p>
							<div class="mt-3 text-sm text-red-600">อ่านต่อ →</div>
						</div>
					</a>
				</article>
			@endforeach
		</div>
	@else
		<div class="p-6 bg-white rounded-lg shadow text-gray-600 text-sm">ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้</div>
	@endif
</div>
@endsection