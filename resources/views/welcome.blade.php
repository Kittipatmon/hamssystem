@extends('layouts.app')
@section('content')
<div class="max-w-7xl mx-auto">
    <div>
        <img src="{{ asset('images/welcome/whams.jpg') }}" alt="Welcome Image" class="mt-4 rounded-lg shadow-lg" style="width: 1500px; height: 400px;">
        <!-- style="color: #7a1b1b;" -->
        <p class="text-2xl font-bold mt-4 text-center text-red-700">Human Asset Management & Service Building</p>
        <p class="text-sm text-center">แผนกจัดการและบำรุงรักษาอาคาร</p>
    </div>
    <div class="mt-6">
        <p><strong>บริการทั้งหมด</strong></p>
        <hr class="mb-2">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Card Start -->
            <div class="relative bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a href="{{ route('serviceshams.welcomeservice') }}">
                      <span class="absolute top-3 right-3 bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow animate-pulse-fast">พร้อมใช้งาน</span>
                    <img src="{{ asset('images/welcome/servicehams.jpg') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold mb-2">ระบบเบิกอุปกรณ์สำนักงาน</h3>
                        <p class="text-xs text-gray-700">จัดการการเบิกอุปกรณ์สำนักงาน ติดตามสถานะและควบคุมสต็อก</p>
                    </div>
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')">
                    <img src="{{ asset('images/welcome/bookingmeet.jpg') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold mb-2">ระบบจองห้องประชุม</h3>
                        <p class="text-xs text-gray-700">จัดการการจองห้องประชุม ติดตามสถานะและควบคุมการใช้งาน</p>
                    </div>
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')">
                    <img src="{{ asset('images/welcome/bookingcar.jpg') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold mb-2">ระบบจองรถส่วนกลาง</h3>
                        <p class="text-xs text-gray-700">จัดการการจองรถส่วนกลาง ติดตามสถานะและควบคุมการใช้งาน</p>
                    </div>
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')">
                    <img src="{{ asset('images/welcome/repairrequest.jpg') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold mb-2">ระบบแจ้งซ่อม</h3>
                        <p class="text-xs text-gray-700">จัดการการแจ้งซ่อม ติดตามสถานะและควบคุมการดำเนินการ</p>
                    </div>
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a onclick="Swal.fire('อยู่ระหว่างพัฒนา!', 'จะแจ้งให้ทราบในภายหลัง.', 'warning')">
                    <img src="{{ asset('images/welcome/residence.jpg') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลัก</p>
                        <h3 class="text-sm font-bold mb-2">ระบบบ้านพักพนักงาน</h3>
                        <p class="text-xs text-gray-700">
                            จัดการบ้านพักพนักงาน ติดตามสถานะและควบคุมการใช้งาน
                        </p>
                    </div>
                </a>
            </div>

            @if(Auth::check() && (in_array(Auth::user()->department_id, ['12', '14']) || Auth::user()->employee_code == '11648'))
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-700  hover:scale-[1.02]">
                <a href="{{ route('datamanage.welcomedatamanage') }}">
                    <img src="{{ asset('images/welcome/data.png') }}" alt="อุปกรณ์สำนักงาน" class="w-full h-65 object-cover">
                    <div class="p-4">
                        <p class="text-xs text-gray-500 mb-1">บริการหลังบ้าน</p>
                        <h3 class="text-sm font-bold mb-2">ระบบจัดการข้อมูลทั่วไป</h3>
                        <p class="text-xs text-gray-700">
                            จัดการข้อมูลทั่วไป เช่น ข้อมูลพนักงาน ข้อมูลอาคาร และข้อมูลอุปกรณ์
                        </p>
                    </div>
                </a>
            </div>
            @endif
            <!-- Card End -->
        </div>
    </div>

    <div class="mt-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">ข่าวสารล่าสุด</h2>
            <a href="{{ route('datamanage.news.newsalllist') }}" class="text-sm text-red-600 hover:underline">ดูทั้งหมด &rarr;</a>
        </div>
        <hr class="mb-4">
        <!-- Horizontal scrollable news cards -->
        @if(isset($news) && $news->count())
            <div class="flex space-x-6 overflow-x-auto pb-4 -mx-2 snap-x snap-mandatory">
                @foreach($news as $item)
                    @php
                        $badgeColors = [
                            'ประกาศ' => 'bg-green-600',
                            'กิจกรรม' => 'bg-yellow-600',
                            'ข่าว' => 'bg-blue-600',
                            'แจ้ง' => 'bg-indigo-600',
                        ];
                        $badgeClass = $badgeColors[$item->newto ?? ''] ?? 'bg-gray-600';

                        // Prefer first multiple image if exists
                        $primary = method_exists($item,'primaryImagePath') ? $item->primaryImagePath() : ($item->image_path ?? '');
                        $rawPath = $primary ?? '';
                        $isAbsolute = preg_match('/^(https?:)?\//', (string)$rawPath) === 1;
                        $imageUrl = $rawPath
                            ? ($isAbsolute ? $rawPath : asset(ltrim($rawPath,'/')))
                            : asset('images/welcome/news1.jpg');
                    @endphp
                    <article class="min-w-[300px] w-[300px] bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 snap-center">
                        <a href="{{ route('datamanage.news.detail', $item) }}" class="block">
                            <div class="relative">
                                <img src="{{ $imageUrl }}" alt="ภาพข่าว" class="w-full h-70 object-cover">
                                <span class="absolute top-3 left-3 {{ $badgeClass }} text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $item->newto ?? 'ข่าว' }}</span>
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-gray-500 mb-1">{{ $item->newto ?? 'ข่าว' }}</p>
                                <h3 class="text-sm font-bold mb-2">{{ $item->title }}</h3>
                                <p class="text-xs text-gray-700 mb-4">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 110) }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <time datetime="{{ $item->published_date ? $item->published_date->toDateString() : '' }}">
                                        <i class="fa-regular fa-calendar-alt mr-1"></i>
                                        {{ $item->published_date ? $item->published_date->format('d/m/Y') : '' }}
                                    </time>
                                    <span class="text-gray-700 font-medium">อ่านต่อ →</span>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @else
            <div class="text-sm text-gray-500">ยังไม่มีข่าวหรือกิจกรรมให้แสดงในขณะนี้</div>
        @endif
    </div>

</div>



@endsection