@extends('layouts.housing.apphousing')

@section('title', 'ระบบจัดการบ้านพักพนักงาน')

@section('content')
    <div class="min-h-[80vh]">

        {{-- Hero Section --}}
        <div
            class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-red-50/30 to-orange-50/20 rounded-3xl mb-8">
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M30 5 L55 20 L55 50 L30 65 L5 50 L5 20 Z&quot; fill=&quot;none&quot; stroke=&quot;%23dc2626&quot; stroke-width=&quot;0.5&quot;/%3E%3C/svg%3E'); background-size: 60px 60px;">
            </div>
            <div class="relative px-6 py-10 md:py-14 text-center">
                <div
                    class="inline-flex items-center gap-2 px-4 py-1.5 bg-red-100/60 text-red-600 rounded-full text-xs font-bold uppercase tracking-wider mb-4">
                    <i class="fa-solid fa-house-chimney"></i> Kumwell HAMS
                </div>
                <div class="flex flex-col items-center gap-4">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-800 mb-0">
                        ข้อมูลบ้านพักพนักงาน
                    </h1>
                    @if(Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16])))
                        <a href="{{ route('housing.management') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-white dark:bg-gray-800 border-2 border-red-500 text-red-600 rounded-full text-sm font-black hover:bg-red-500 hover:text-white transition-all shadow-md group/dash">
                            <i class="fa-solid fa-chart-line group-hover/dash:animate-pulse"></i>
                            MANAGEMENT DASHBOARD
                        </a>
                    @endif
                </div>
                <p class="text-gray-500 text-sm md:text-base max-w-lg mx-auto mt-4">
                    เลือกบ้านพักที่ต้องการเพื่อดำเนินการขอเข้าพักอาศัย
                </p>
            </div>
        </div>

        {{-- Next Step Notification Alert --}}
        @if($userActiveRequest)
            @php
                $status = $userActiveRequest->send_status;
                $config = match ($status) {
                    0, 1, 2 => [
                        'icon' => 'fa-hourglass-half',
                        'color' => 'amber',
                        'title' => 'กำลังอยู่ระหว่างการพิจารณา',
                        'desc' => 'คำขอหมายเลข ' . $userActiveRequest->requests_code . ' กำลังถูกตรวจสอบตามขั้นตอน ขั้นตอนถัดไป: รอการอนุมัติจากผู้เกี่ยวข้อง',
                        'btn' => 'ติดตามสถานะ',
                        'route' => route('housing.my_requests')
                    ],
                    3 => [
                        'icon' => 'fa-circle-check',
                        'color' => 'blue',
                        'title' => 'ผ่านการอนุมัติแล้ว!',
                        'desc' => 'คำขอของคุณได้รับการอนุมัติขั้นสุดท้ายแล้ว ขั้นตอนถัดไป: รอเจ้าหน้าที่ดำเนินการมอบหมายห้องพักให้คุณ',
                        'btn' => 'ดูรายละเอียด',
                        'route' => route('housing.my_requests')
                    ],
                    4 => [
                        'icon' => 'fa-circle-exclamation',
                        'color' => 'orange',
                        'title' => 'คำขอถูกส่งกลับแก้ไข',
                        'desc' => 'กรุณาตรวจสอบและแก้ไขข้อมูลในคำขอหมายเลข ' . $userActiveRequest->requests_code . ' ของคุณเพื่อให้การพิจารณาดำเนินการต่อได้',
                        'btn' => 'ไปแก้ไขข้อมูล',
                        'route' => route('housing.my_requests')
                    ],
                    7 => ($pendingAgreement) ? [
                        'icon' => 'fa-hourglass-half',
                        'color' => 'blue',
                        'title' => 'รอกรรมการตรวจสอบสัญญา',
                        'desc' => 'คุณส่งแบบฟอร์มข้อตกลงฯ เรียบร้อยแล้ว ขั้นตอนถัดไป: รอเจ้าหน้าที่ตรวจสอบและอนุมัติสัญญาเข้าพักของคุณ',
                        'btn' => 'ติดตามผล',
                        'route' => route('housing.my_requests') . '?tab=agreements'
                    ] : [
                        'icon' => 'fa-file-signature',
                        'color' => 'red',
                        'title' => 'ขั้นตอนสุดท้าย: ลงนามข้อตกลง',
                        'desc' => 'คุณได้รับมอบหมายห้องพักแล้ว! กรุณากรอกแบบฟอร์มข้อตกลงการเข้าพักอาศัย (QF-HAMS-03) เพื่อยืนยันการเข้าพัก',
                        'btn' => 'กรอกแบบฟอร์ม',
                        'route' => route('housing.agreement.create')
                    ],
                    default => null
                };
            @endphp

            @if($config)
                <div class="max-w-5xl mx-auto mb-8 px-4 sm:px-0">
                    <div
                        class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl border-l-4 border-{{ $config['color'] }}-500 shadow-md p-5 group transition-all hover:shadow-lg">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-{{ $config['color'] }}-500/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110">
                        </div>
                        <div class="flex flex-col sm:flex-row items-center gap-5 relative z-10">
                            <div
                                class="w-12 h-12 rounded-xl bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 flex items-center justify-center text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400 shadow-inner">
                                <i class="fa-solid {{ $config['icon'] }} text-xl"></i>
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100 tracking-tight">{{ $config['title'] }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                                    {!! $config['desc'] !!}
                                </p>
                            </div>
                            <a href="{{ $config['route'] }}"
                                class="bg-{{ $config['color'] }}-600 hover:bg-{{ $config['color'] }}-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2 whitespace-nowrap">
                                {{ $config['btn'] }} <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Residence Cards --}}

        <div class="max-w-5xl mx-auto mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($residences as $index => $res)
                    @php
                        $images = [
                            'images/housing/residence_bangyai.png',
                            'images/housing/residence_saiyai.png',
                        ];
                        $imgPath = $images[$index] ?? $images[0];
                        $availCount = $res->rooms->where('residence_room_status', 0)->count();
                        $totalCount = $res->rooms->count();
                    @endphp
                    <a href="{{ route('housing.request.create') }}"
                        class="group block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-red-100/50 hover:border-red-200 transition-all duration-500 hover:-translate-y-1">

                        {{-- Image --}}
                        <div class="relative h-56 overflow-hidden">
                            <img src="{{ asset($imgPath) }}" alt="{{ $res->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                            {{-- Status badge --}}
                            <div class="absolute top-3 right-3 flex gap-2">
                                @if($availCount > 0)
                                    <span
                                        class="bg-emerald-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">
                                        <i class="fa-solid fa-check-circle mr-0.5"></i> {{ $availCount }} ห้องว่าง
                                    </span>
                                @else
                                    <span
                                        class="bg-red-500/90 backdrop-blur-sm text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg">
                                        <i class="fa-solid fa-xmark-circle mr-0.5"></i> เต็ม
                                    </span>
                                @endif
                            </div>

                            {{-- Click indicator --}}
                            <div
                                class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span
                                    class="bg-white/90 backdrop-blur-sm text-red-600 text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                                    เลือกบ้านพัก <i class="fa-solid fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Card Content --}}
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-red-600 transition-colors mb-2">
                                บ้านพัก{{ $res->name }}
                            </h3>
                            <div class="flex items-center gap-3">
                                <span
                                    class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    <i class="fa-solid fa-layer-group"></i> {{ $res->total_floors }} ชั้น
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-600 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                    <i class="fa-solid fa-door-open"></i> {{ $totalCount }} ห้อง
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="max-w-6xl mx-auto mt-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <a href="{{ route('housing.request.create') }}"
                    class="group bg-white rounded-2xl border border-gray-100 py-8 px-5 min-h-[160px] flex flex-col items-center justify-center text-center hover:border-red-200 hover:shadow-xl hover:shadow-red-50/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all mb-4">
                        <i class="fa-solid fa-file-circle-plus text-white text-xl"></i>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-700 group-hover:text-red-600 transition-colors">คำขอเข้าพัก</span>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">QF-HAMS-02</p>
                </a>

                <a href="{{ route('housing.agreement.create') }}"
                    class="group bg-white rounded-2xl border border-gray-100 py-8 px-5 min-h-[160px] flex flex-col items-center justify-center text-center hover:border-blue-200 hover:shadow-xl hover:shadow-blue-50/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all mb-4">
                        <i class="fa-solid fa-file-signature text-white text-xl"></i>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">ข้อตกลงเข้าพัก</span>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">QF-HAMS-03</p>
                </a>

                <a href="{{ route('housing.guest.create') }}"
                    class="group bg-white rounded-2xl border border-gray-100 py-8 px-5 min-h-[160px] flex flex-col items-center justify-center text-center hover:border-purple-200 hover:shadow-xl hover:shadow-purple-50/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all mb-4">
                        <i class="fa-solid fa-people-arrows text-white text-xl"></i>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-700 group-hover:text-purple-600 transition-colors">นำญาติเข้าพัก</span>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">QF-HAMS-05</p>
                </a>

                <a href="{{ route('housing.leave.create') }}"
                    class="group bg-white rounded-2xl border border-gray-100 py-8 px-5 min-h-[160px] flex flex-col items-center justify-center text-center hover:border-orange-200 hover:shadow-xl hover:shadow-orange-50/50 transition-all duration-300">
                    <div
                        class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all mb-4">
                        <i class="fa-solid fa-right-from-bracket text-white text-xl"></i>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-700 group-hover:text-orange-600 transition-colors">คำร้องย้ายออก</span>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Move-out</p>
                </a>
            </div>
        </div>


        {{-- Booking Details Section --}}
        <!-- <div class="max-w-5xl mx-auto mt-8">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">รายละเอียดการจอง</h2>
                    <div class="w-16 h-0.5 bg-red-500 mx-auto mt-2 rounded-full"></div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    {{-- KPI Row --}}
                    <div class="grid grid-cols-2 md:grid-cols-5 border-b border-gray-100">
                        <div class="p-5 text-center border-r border-gray-100">
                            <p class="text-2xl font-bold text-blue-600">{{ $totalRooms }}</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">ห้องพักทั้งหมด</p>
                        </div>
                        <div class="p-5 text-center border-r border-gray-100">
                            <p class="text-2xl font-bold text-emerald-600">{{ $availableRooms }}</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">ห้องว่าง</p>
                        </div>
                        <div class="p-5 text-center border-r border-gray-100">
                            <p class="text-2xl font-bold text-red-600">{{ $occupiedRooms }}</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">ห้องไม่ว่าง</p>
                        </div>
                        <div class="p-5 text-center border-r border-gray-100">
                            <p class="text-2xl font-bold text-amber-600">{{ $pendingRequests }}</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">รอดำเนินการ</p>
                        </div>
                        <div class="p-5 text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ $activeResidents }}</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-1">ผู้พักอาศัย</p>
                        </div>
                    </div>


                    {{-- Recent Requests Table --}}
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-gray-400"></i> คำร้องล่าสุด
                        </h3>
                        <a href="{{ route('housing.management') }}"
                            class="text-xs text-red-500 hover:text-red-700 font-semibold transition-colors">
                            ดูทั้งหมด <i class="fa-solid fa-arrow-right ml-0.5"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-5 py-3 text-left font-medium">เลขที่</th>
                                    <th class="px-5 py-3 text-left font-medium">ประเภท</th>
                                    <th class="px-5 py-3 text-left font-medium">ผู้ยื่นคำร้อง</th>
                                    <th class="px-5 py-3 text-left font-medium">วันที่</th>
                                    <th class="px-5 py-3 text-left font-medium">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @php
                                    $allRecent = collect();
                                    foreach ($recentRequests as $r) {
                                        $allRecent->push((object) [
                                            'code' => $r->requests_code,
                                            'type' => 'คำขอเข้าพัก',
                                            'type_slug' => 'request',
                                            'type_color' => 'bg-red-50 text-red-600',
                                            'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                            'date' => $r->created_at,
                                            'status' => $r->send_status,
                                        ]);
                                    }
                                    foreach ($recentAgreements as $r) {
                                        $allRecent->push((object) [
                                            'code' => $r->agreement_code,
                                            'type' => 'ข้อตกลง',
                                            'type_slug' => 'agreement',
                                            'type_color' => 'bg-blue-50 text-blue-600',
                                            'name' => $r->full_name ?? '',
                                            'date' => $r->created_at,
                                            'status' => $r->send_status,
                                        ]);
                                    }
                                    foreach ($recentGuests as $r) {
                                        $allRecent->push((object) [
                                            'code' => $r->resident_guest_code,
                                            'type' => 'นำญาติเข้าพัก',
                                            'type_slug' => 'guest',
                                            'type_color' => 'bg-purple-50 text-purple-600',
                                            'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                            'date' => $r->created_at,
                                            'status' => $r->send_status,
                                        ]);
                                    }
                                    foreach ($recentLeaves as $r) {
                                        $allRecent->push((object) [
                                            'code' => $r->residence_leaves_code,
                                            'type' => 'ขอย้ายออก',
                                            'type_slug' => 'leave',
                                            'type_color' => 'bg-orange-50 text-orange-600',
                                            'name' => ($r->first_name ?? '') . ' ' . ($r->last_name ?? ''),
                                            'date' => $r->created_at,
                                            'status' => $r->send_status,
                                        ]);
                                    }
                                    $allRecent = $allRecent->sortByDesc('date')->take(10);
                                @endphp

                                @forelse($allRecent as $item)
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-gray-700">{{ $item->code }}</td>
                                        <td class="px-5 py-3.5">
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $item->type_color }}">{{ $item->type }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-600 text-[13px]">{{ $item->name }}</td>
                                        <td class="px-5 py-3.5 text-gray-400 text-xs">
                                            {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusColor($item->status) }}">
                                                {{ \App\Http\Controllers\housing\EmployeeHousingController::getStatusLabel($item->status, $item->type_slug) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                                            <i class="fa-regular fa-folder-open text-3xl mb-2 block"></i>
                                            <p class="text-sm">ยังไม่มีคำร้อง</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> -->

    </div>
@endsection