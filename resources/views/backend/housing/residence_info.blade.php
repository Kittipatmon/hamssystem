@extends('layouts.housing.apphousing')

@section('title', 'รายละเอียดห้องพัก')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
    
    <!-- Header Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('housing.welcome') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors font-bold text-sm">
            <i class="fa-solid fa-arrow-left"></i> กลับไปหน้าหลัก
        </a>
    </div>

    <!-- Hero Image & Title -->
    <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-slate-200 mb-12">
        <div class="relative h-[400px] w-full">
            @php
                $images = [
                    'images/housing/residence_bangyai.png',
                    'images/housing/residence_saiyai.png',
                ];
                $imgPath = $residence->cover_image ? $residence->cover_image : (str_contains($residence->name, 'ไทรใหญ่') ? $images[1] : $images[0]);
            @endphp
            <img src="{{ asset($imgPath) }}" alt="{{ $residence->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
                <span class="inline-block px-4 py-1.5 rounded-full bg-red-500 text-white text-xs font-black tracking-widest mb-4">AVAILABLE RESIDENCE</span>
                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight">อาคารบ้านพักสวัสดิการพนักงาน: <span class="text-red-400">{{ $residence->name }}</span></h1>
                <p class="text-slate-200 mt-4 max-w-2xl text-sm md:text-base leading-relaxed">
                    ค้นพบประสบการณ์การอยู่อาศัยที่สะดวกสบายและครบครัน พร้อมสภาพแวดล้อมที่ส่งเสริมคุณภาพชีวิตและการพักผ่อนอย่างเต็มที่หลังจากการทำงาน
                </p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Left Column (Description & Amenities) -->
                <div class="lg:col-span-2 space-y-10">
                    
                    <!-- Intro -->
                    <section>
                        <h3 class="text-xl font-black text-slate-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-quote-left text-red-500"></i> แนวคิดการออกแบบและการใช้ชีวิต
                        </h3>
                        <p class="text-slate-600 leading-loose text-sm md:text-base">
                            อาคารที่พักแห่งนี้ได้รับการออกแบบให้มีความร่มรื่น สะดวกสบายต่อการใช้ชีวิตประจำวัน ห้องพักมีการจัดสรรพื้นที่อย่างลงตัวเพื่อความโปร่งโล่ง รับแสงธรรมชาติและลมระบายอากาศได้ดี พร้อมเฟอร์นิเจอร์และสิ่งอำนวยความสะดวกพื้นฐานที่ช่วยให้คุณเข้าอยู่ได้ทันทีโดยไม่ต้องกังวล มีความเป็นส่วนตัวสูงและเงียบสงบ
                        </p>
                    </section>

                    <!-- Features Grid -->
                    <section>
                        <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
                            <i class="fa-solid fa-star text-amber-500"></i> สิ่งอำนวยความสะดวกในห้อง (Room Amenities)
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                ['icon' => 'fa-bed', 'title' => 'เตียงนอน', 'desc' => $features['bed'], 'color' => 'blue'],
                                ['icon' => 'fa-snowflake', 'title' => 'เครื่องปรับอากาศ', 'desc' => $features['ac'], 'color' => 'sky'],
                                ['icon' => 'fa-door-closed', 'title' => 'ตู้เสื้อผ้า', 'desc' => $features['closet'], 'color' => 'amber'],
                                ['icon' => 'fa-bath', 'title' => 'ห้องน้ำ', 'desc' => $features['bathroom'], 'color' => 'teal'],
                                ['icon' => 'fa-wind', 'title' => 'ระเบียงซักล้าง', 'desc' => $features['balcony'], 'color' => 'emerald'],
                                ['icon' => 'fa-shield-halved', 'title' => 'ความปลอดภัย', 'desc' => $features['security'], 'color' => 'rose'],
                            ] as $feat)
                            <div class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-[{{ $feat['color'] }}-300] hover:shadow-md transition-all">
                                <!-- using specific classes to avoid dynamic class purge issues in tailwind if any -->
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 
                                    @if($feat['color'] == 'blue') bg-blue-100 text-blue-600 
                                    @elseif($feat['color'] == 'sky') bg-sky-100 text-sky-600 
                                    @elseif($feat['color'] == 'amber') bg-amber-100 text-amber-600 
                                    @elseif($feat['color'] == 'teal') bg-teal-100 text-teal-600 
                                    @elseif($feat['color'] == 'emerald') bg-emerald-100 text-emerald-600 
                                    @elseif($feat['color'] == 'rose') bg-rose-100 text-rose-600 
                                    @endif
                                ">
                                    <i class="fa-solid {{ $feat['icon'] }} text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $feat['title'] }}</h4>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $feat['desc'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </div>

                <!-- Right Column (Sidebar / CTA) -->
                <div class="space-y-6">
                    
                    <!-- Building Stats -->
                    <div class="bg-slate-800 rounded-3xl p-6 text-white shadow-lg">
                        <h3 class="text-lg font-black mb-6 text-slate-200">ข้อมูลอาคารเบื้องต้น</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-4 border-b border-slate-700">
                                <span class="text-slate-400 flex items-center gap-2"><i class="fa-solid fa-layer-group w-5"></i> จำนวนชั้น</span>
                                <span class="font-bold">{{ $residence->total_floors }} ชั้น</span>
                            </div>
                            <div class="flex items-center justify-between pb-4 border-b border-slate-700">
                                <span class="text-slate-400 flex items-center gap-2"><i class="fa-solid fa-door-open w-5"></i> จำนวนห้องพักรวม</span>
                                <span class="font-bold">{{ $residence->total_rooms }} ห้อง</span>
                            </div>
                            @php
                                $availCount = $residence->rooms->where('residence_room_status', 0)->count();
                            @endphp
                            <div class="flex items-center justify-between pb-2">
                                <span class="text-slate-400 flex items-center gap-2"><i class="fa-solid fa-check-circle w-5"></i> สถานะปัจจุบัน</span>
                                @if($availCount > 0)
                                    <span class="inline-flex px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs font-bold">ว่าง {{ $availCount }} ห้อง</span>
                                @else
                                    <span class="inline-flex px-3 py-1 bg-rose-500/20 text-rose-400 rounded-full text-xs font-bold">ห้องเต็ม</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Call To Action -->
                    <div class="bg-red-50 rounded-3xl p-6 border border-red-100 shadow-sm">
                        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-2xl flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 mb-2">สนใจเข้าพักที่อาคารนี้?</h3>
                        @php
                            $user = auth()->user();
                            $isHams = $user && $user->is_hams_admin;
                        @endphp
                        @if($isHams)
                            <p class="text-sm text-slate-600 mb-6">คุณอยู่ในกลุ่มผู้ดูแลระบบ HAMS สามารถเข้าไปจัดการรายการห้องพักหรือดูภาพรวมสถานะการเข้าพักทั้งหมดได้เลย</p>
                            <a href="{{ route('housing.houselist', ['residence_id' => $residence->residence_id]) }}" class="block w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white text-center font-bold rounded-xl shadow-md transition-all active:scale-95">
                                ดูรายการห้องพักอาคารนี้ <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        @else
                            <p class="text-sm text-slate-600 mb-6">ดำเนินการกรอกแบบฟอร์มเพื่อส่งคำร้องขอเข้าพัก (QF-HAMS-02) ให้กับผู้บังคับบัญชาพิจารณาอนุมัติตามขั้นตอน</p>
                            <a href="{{ route('housing.request.create', ['site' => $residence->name]) }}" class="block w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white text-center font-bold rounded-xl shadow-md transition-all active:scale-95">
                                ยื่นคำร้องขอเข้าพักทันที <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Full Width Bottom Sections -->
            <div class="mt-12 space-y-12">
                <!-- Blueprint -->
                <section>
                    <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-blue-500"></i> แผนผังอาคาร (Blueprint)
                    </h3>
                    <div class="rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm">
                        @if($residence->blueprint_image)
                            <img src="{{ asset($residence->blueprint_image) }}" alt="Blueprint" class="w-full h-auto object-contain hover:scale-[1.02] transition-transform duration-500">
                        @else
                            <div class="w-full h-64 flex flex-col items-center justify-center text-slate-400">
                                <i class="fa-solid fa-map-location-dot text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-bold">ยังไม่มีรูปแผนผังอาคาร</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
