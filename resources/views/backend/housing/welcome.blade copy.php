@extends('layouts.housing.apphousing')

@section('title', 'ระบบจัดการบ้านพักพนักงาน')

@section('content')
    <div class="space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 text-sm shadow-sm">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-house-chimney text-white text-lg"></i>
                    </div>
                    ระบบจัดการบ้านพักพนักงาน
                </h2>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm ml-[52px]">Dashboard ภาพรวมบ้านพักและคำร้องต่างๆ</p>
            </div>
            <a href="{{ route('housing.management') }}"
                class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-table-list"></i> จัดการข้อมูลทั้งหมด
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total Rooms --}}
            <div
                class="kumwell-card bg-white dark:bg-gray-800 p-5 border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">
                        ห้องพักทั้งหมด</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalRooms }} <span
                            class="text-sm font-normal text-gray-400">ห้อง</span></h3>
                </div>
            </div>

            {{-- Available --}}
            <div
                class="kumwell-card bg-white dark:bg-gray-800 p-5 border border-emerald-100 dark:border-emerald-800 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-emerald-600 text-xs font-semibold uppercase tracking-wider mb-1">ห้องว่าง</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $availableRooms }} <span
                            class="text-sm font-normal text-gray-400">ห้อง</span></h3>
                </div>
            </div>

            {{-- Occupied --}}
            <div
                class="kumwell-card bg-white dark:bg-gray-800 p-5 border border-red-100 dark:border-red-800 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-user-lock"></i>
                </div>
                <div>
                    <p class="text-red-600 text-xs font-semibold uppercase tracking-wider mb-1">ห้องไม่ว่าง</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $occupiedRooms }} <span
                            class="text-sm font-normal text-gray-400">ห้อง</span></h3>
                </div>
            </div>

            {{-- Pending Requests --}}
            <div
                class="kumwell-card bg-white dark:bg-gray-800 p-5 border border-amber-100 dark:border-amber-800 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center text-xl shrink-0 {{ $pendingRequests > 0 ? 'animate-pulse' : '' }}">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p class="text-amber-600 text-xs font-semibold uppercase tracking-wider mb-1">รอดำเนินการ</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $pendingRequests }} <span
                            class="text-sm font-normal text-gray-400">รายการ</span></h3>
                </div>
            </div>

            {{-- Active Residents --}}
            <div
                class="kumwell-card bg-white dark:bg-gray-800 p-5 border border-purple-100 dark:border-purple-800 flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-people-roof"></i>
                </div>
                <div>
                    <p class="text-purple-600 text-xs font-semibold uppercase tracking-wider mb-1">ผู้พักอาศัย</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $activeResidents }} <span
                            class="text-sm font-normal text-gray-400">คน</span></h3>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('housing.request.create') }}"
                class="kumwell-card bg-white dark:bg-gray-800 p-4 border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-3 text-center hover:border-red-300 dark:hover:border-red-700 hover:scale-[1.02] group transition-all">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-lg group-hover:shadow-red-200 dark:group-hover:shadow-red-900/50 transition-shadow">
                    <i class="fa-solid fa-file-circle-plus text-white text-xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">คำขอเข้าพัก</span>
                <span class="text-[10px] text-gray-400">QF-HAMS-02</span>
            </a>
            <a href="{{ route('housing.agreement.create') }}"
                class="kumwell-card bg-white dark:bg-gray-800 p-4 border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-3 text-center hover:border-blue-300 dark:hover:border-blue-700 hover:scale-[1.02] group transition-all">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg group-hover:shadow-blue-200 dark:group-hover:shadow-blue-900/50 transition-shadow">
                    <i class="fa-solid fa-file-signature text-white text-xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">ข้อตกลงเข้าพัก</span>
                <span class="text-[10px] text-gray-400">QF-HAMS-03</span>
            </a>
            <a href="{{ route('housing.guest.create') }}"
                class="kumwell-card bg-white dark:bg-gray-800 p-4 border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-3 text-center hover:border-purple-300 dark:hover:border-purple-700 hover:scale-[1.02] group transition-all">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg group-hover:shadow-purple-200 dark:group-hover:shadow-purple-900/50 transition-shadow">
                    <i class="fa-solid fa-people-arrows text-white text-xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">นำญาติเข้าพัก</span>
                <span class="text-[10px] text-gray-400">QF-HAMS-05</span>
            </a>
            <a href="{{ route('housing.leave.create') }}"
                class="kumwell-card bg-white dark:bg-gray-800 p-4 border border-gray-100 dark:border-gray-700 flex flex-col items-center gap-3 text-center hover:border-orange-300 dark:hover:border-orange-700 hover:scale-[1.02] group transition-all">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-lg group-hover:shadow-orange-200 dark:group-hover:shadow-orange-900/50 transition-shadow">
                    <i class="fa-solid fa-right-from-bracket text-white text-xl"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">คำร้องย้ายออก</span>
                <span class="text-[10px] text-gray-400">Move-out</span>
            </a>
        </div>

        {{-- Residence Overview --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($residences as $res)
                <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-5 py-4 flex items-center justify-between">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <i class="fa-solid fa-building text-red-400"></i> บ้านพัก{{ $res->name }}
                        </h3>
                        <div class="flex gap-2 text-xs">
                            <span class="bg-white/10 text-white px-2 py-1 rounded-full">{{ $res->total_floors }} ชั้น</span>
                            <span class="bg-white/10 text-white px-2 py-1 rounded-full">{{ $res->rooms->count() }} ห้อง</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-3 gap-3 text-center text-sm">
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-3">
                                <p class="text-2xl font-bold text-emerald-600">
                                    {{ $res->rooms->where('residence_room_status', 0)->count() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ว่าง</p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3">
                                <p class="text-2xl font-bold text-red-600">
                                    {{ $res->rooms->where('residence_room_status', 1)->count() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ไม่ว่าง</p>
                            </div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3">
                                <p class="text-2xl font-bold text-amber-600">
                                    {{ $res->rooms->where('residence_room_status', 2)->count() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ซ่อมบำรุง</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Recent Activity --}}
        <div class="kumwell-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800">
                <h3 class="font-bold text-gray-800 dark:text-white text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> คำร้องล่าสุด
                </h3>
                <a href="{{ route('housing.management') }}"
                    class="text-xs text-red-500 hover:text-red-700 font-medium">ดูทั้งหมด &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium">เลขที่</th>
                            <th class="px-4 py-3 text-left font-medium">ประเภท</th>
                            <th class="px-4 py-3 text-left font-medium">ผู้ยื่นคำร้อง</th>
                            <th class="px-4 py-3 text-left font-medium">วันที่</th>
                            <th class="px-4 py-3 text-left font-medium">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                            $allRecent = collect();
                            foreach ($recentRequests as $r) {
                                $allRecent->push((object) [
                                    'code' => $r->requests_code,
                                    'type' => 'คำขอเข้าพัก',
                                    'type_color' => 'bg-red-100 text-red-700',
                                    'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                    'date' => $r->created_at,
                                    'status' => $r->send_status,
                                ]);
                            }
                            foreach ($recentAgreements as $r) {
                                $allRecent->push((object) [
                                    'code' => $r->agreement_code,
                                    'type' => 'ข้อตกลง',
                                    'type_color' => 'bg-blue-100 text-blue-700',
                                    'name' => $r->full_name ?? '',
                                    'date' => $r->created_at,
                                    'status' => $r->send_status,
                                ]);
                            }
                            foreach ($recentGuests as $r) {
                                $allRecent->push((object) [
                                    'code' => $r->resident_guest_code,
                                    'type' => 'นำญาติเข้าพัก',
                                    'type_color' => 'bg-purple-100 text-purple-700',
                                    'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                    'date' => $r->created_at,
                                    'status' => $r->send_status,
                                ]);
                            }
                            foreach ($recentLeaves as $r) {
                                $allRecent->push((object) [
                                    'code' => $r->residence_leaves_code,
                                    'type' => 'ขอย้ายออก',
                                    'type_color' => 'bg-orange-100 text-orange-700',
                                    'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                    'date' => $r->created_at,
                                    'status' => $r->send_status,
                                ]);
                            }
                            $allRecent = $allRecent->sortByDesc('date')->take(10);
                        @endphp

                        @forelse($allRecent as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-4 py-3 font-mono text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item->code }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item->type_color }}">{{ $item->type }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-xs">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->status) }}">
                                        {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    <i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>
                                    ยังไม่มีคำร้อง
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection