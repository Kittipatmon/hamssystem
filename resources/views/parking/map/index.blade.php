@extends('layouts.parking.app')

@section('content')
<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-slate-800 tracking-tight flex items-center justify-center gap-3">
                <i class="fa-solid fa-map-location-dot text-[#b81515]"></i> เลือกแผนผังลานจอดรถ
            </h2>
            <p class="text-slate-500 mt-2 text-lg font-medium">เข้าชมระบบผังที่จอดรถแบบโต้ตอบ (Interactive Layouts) ของสำนักงาน</p>
        </div>

        <!-- Grid of 2 Maps -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Map 1: Outdoor HQ Map -->
            <a href="{{ route('parking.map.full') }}" class="group relative bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden hover:shadow-2xl hover:border-blue-400 transition-all duration-300 flex flex-col p-8">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-bl-full flex items-center justify-center transition-all group-hover:bg-blue-500/20">
                    <i class="fa-solid fa-circle-arrow-right text-2xl text-blue-600 transition-transform group-hover:translate-x-1"></i>
                </div>
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-square-parking"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 group-hover:text-blue-600 transition-colors mb-3">
                    ลานจอดรถสำนักงานใหญ่ (Outdoor)
                </h3>
                <p class="text-slate-500 font-medium leading-relaxed mb-6">
                    แผนผังระบบบริหารจัดการลานจอดรถกลางแจ้งของสำนักงานใหญ่ รวมทั้งหมด 74 ช่องจอด สามารถตรวจสอบ ตรวจเช็คสถานะการจอด และดูโปรไฟล์พนักงานที่เข้าจอดแบบ Real-time
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between text-blue-600 font-bold text-sm">
                    <span>เปิดดูแผนผังภายนอก</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>

            @php
                $isAdmin = Auth::check() && Auth::user()->is_hams_admin;
            @endphp
            @if($isAdmin)
            <!-- Map 2: Indoor Building Map -->
            <a href="{{ route('parking.map.building') }}" class="group relative bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden hover:shadow-2xl hover:border-emerald-400 transition-all duration-300 flex flex-col p-8">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/10 rounded-bl-full flex items-center justify-center transition-all group-hover:bg-emerald-500/20">
                    <i class="fa-solid fa-circle-arrow-right text-2xl text-emerald-600 transition-transform group-hover:translate-x-1"></i>
                </div>
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-inner group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 group-hover:text-emerald-600 transition-colors mb-3">
                    แผนผังพื้นที่ในอาคาร (Indoor)
                </h3>
                <p class="text-slate-500 font-medium leading-relaxed mb-6">
                    แผนผังโครงสร้างพื้นที่จอดรถภายในตัวอาคารสำนักงานและโซนพื้นที่ใช้สอยต่าง ๆ แบ่งเป็น 19 ช่องใหญ่ มีความจุช่องละ 3 คันย่อย คลุมทั้งที่จอดรถผู้บริหารและผู้ติดต่อ
                </p>
                <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between text-emerald-600 font-bold text-sm">
                    <span>เปิดดูแผนผังภายใน</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </div>
            </a>
            @endif

        </div>

    </div>
</div>
@endsection
