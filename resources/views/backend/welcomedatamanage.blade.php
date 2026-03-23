@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('content')
    <div class="px-6 py-4 max-w-7xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight mb-2">Welcome to the HAMS Service
                System</h2>
            <p class="text-gray-500 dark:text-gray-400 text-[14px]">ศูนย์รวมบริการและจัดการข้อมูลระบบต่างๆ ของพนักงาน</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
            <!-- Card 1: ระบบเบิกอุปกรณ์สำนักงาน -->
            <a href="{{ route('items.index') }}"
                class="bg-white dark:bg-[#1e2128] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col cursor-pointer">
                <div class="relative h-36 w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=800&auto=format&fit=crop"
                        alt="Office Supplies"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div
                        class="absolute top-3 right-3 bg-[#00C853] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                        พร้อมใช้งาน
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-red-500 text-[11px] font-semibold tracking-wider mb-1">บริการหลัก</span>
                    <h3
                        class="text-gray-900 dark:text-white text-[15px] font-bold mb-1.5 leading-tight group-hover:text-red-600 transition-colors">
                        ระบบเบิกอุปกรณ์สำนักงาน</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-[12px] leading-relaxed line-clamp-2 mt-auto">
                        จัดการเบิกอุปกรณ์ ติดตามสถานะและควบคุมสต็อก</p>
                </div>
            </a>

            <!-- Card 2: ระบบจองห้องประชุม -->
            <a href="{{ route('reservations.welcomemeeting') }}"
                class="bg-white dark:bg-[#1e2128] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col cursor-pointer">
                <div class="relative h-36 w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop"
                        alt="Meeting Room"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-red-500 text-[11px] font-semibold tracking-wider mb-1">บริการหลัก</span>
                    <h3
                        class="text-gray-900 dark:text-white text-[15px] font-bold mb-1.5 leading-tight group-hover:text-red-600 transition-colors">
                        ระบบจองห้องประชุม</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-[12px] leading-relaxed line-clamp-2 mt-auto">
                        จัดการจองห้องประชุม ติดตามสถานะและควบคุมการใช้งาน</p>
                </div>
            </a>

            <!-- Card 3: ระบบจองรถส่วนกลาง -->
            <a href="{{ route('bookingcar.welcome') }}"
                class="bg-white dark:bg-[#1e2128] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col cursor-pointer">
                <div class="relative h-36 w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?q=80&w=800&auto=format&fit=crop"
                        alt="Car Booking"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-red-500 text-[11px] font-semibold tracking-wider mb-1">บริการหลัก</span>
                    <h3
                        class="text-gray-900 dark:text-white text-[15px] font-bold mb-1.5 leading-tight group-hover:text-red-600 transition-colors">
                        ระบบจองรถส่วนกลาง</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-[12px] leading-relaxed line-clamp-2 mt-auto">
                        จัดการจองรถส่วนกลาง ติดตามสถานะและควบคุมการใช้งาน</p>
                </div>
            </a>

            <!-- Card 4: ระบบแจ้งซ่อม -->
            <a href="#"
                class="bg-white dark:bg-[#1e2128] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col opacity-90">
                <div class="relative h-36 w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1581141849291-1125c7b692b5?q=80&w=800&auto=format&fit=crop"
                        alt="Maintenance"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div
                        class="absolute top-3 right-3 bg-[#FF9800] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                        เร็วๆ นี้
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-red-500 text-[11px] font-semibold tracking-wider mb-1">บริการหลัก</span>
                    <h3 class="text-gray-900 dark:text-white text-[15px] font-bold mb-1.5 leading-tight">ระบบแจ้งซ่อม</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-[12px] leading-relaxed line-clamp-2 mt-auto">
                        จัดการแจ้งซ่อม ติดตามสถานะและควบคุมการดำเนินการ</p>
                </div>
            </a>

            <!-- Card 5: ระบบบ้านพักพนักงาน -->
            <a href="#"
                class="bg-white dark:bg-[#1e2128] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col opacity-90">
                <div class="relative h-36 w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1460317442991-0ec209397118?q=80&w=800&auto=format&fit=crop"
                        alt="Housing"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div
                        class="absolute top-3 right-3 bg-[#FF9800] text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                        เร็วๆ นี้
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-red-500 text-[11px] font-semibold tracking-wider mb-1">บริการหลัก</span>
                    <h3 class="text-gray-900 dark:text-white text-[15px] font-bold mb-1.5 leading-tight">ระบบบ้านพักพนักงาน
                    </h3>
                    <p class="text-gray-400 dark:text-gray-500 text-[12px] leading-relaxed line-clamp-2 mt-auto">
                        จัดการบ้านพักพนักงาน ติดตามสถานะและควบคุมการใช้งาน</p>
                </div>
            </a>
        </div>
    </div>
@endsection