@extends('layouts.parking.app')

@section('content')

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-[#b81515]"></i> ติดตามอนุมัติคำขอจองที่จอดรถ
                </h2>
                <p class="text-slate-500 mt-1 font-medium">ติดตามสถานะการจองพื้นที่และอนุมัติการจองลานจอดรถ</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-lg mb-6 rounded-xl border border-emerald-200 flex items-center gap-3 bg-emerald-50">
                <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                <span class="font-bold text-slate-800">{{ session('success') }}</span>
            </div>
        @endif

        <div class="space-y-8">
            @if(isset($pendingVisitorApprovals) && $pendingVisitorApprovals->isNotEmpty())
                <!-- Visitor Approvals Section (For HAMS) -->
                <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 mb-8">
                    <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                        คำขอจองลานจอดรถของแขกผู้มาติดต่อ
                        <span class="text-xs font-medium text-slate-500 font-normal">(รอ HAMS อนุมัติ)</span>
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">ผู้ขอจอง</th>
                                    <th class="px-6 py-4">รายละเอียดแขก</th>
                                    <th class="px-6 py-4">ทะเบียนรถ/โทร</th>
                                    <th class="px-6 py-4">เวลาเข้า</th>
                                    <th class="px-6 py-4">ช่องจอด</th>
                                    <th class="px-6 py-4 text-right">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($pendingVisitorApprovals as $res)
                                    @php
                                        $visitorData = [
                                            'guest_name' => $res->guest_name,
                                            'company' => $res->company,
                                            'car_registration' => $res->car_registration,
                                            'phone' => $res->phone,
                                            'checkin' => \App\Helpers\ThaiDate::format($res->checkin_datetime),
                                            'duration' => $res->duration_hours ? $res->duration_hours . ' ชั่วโมง' : '-',
                                            'slot' => $res->slot ? $res->slot->slot_number . ' (' . ($res->slot->zone?->zone ?: 'ไม่ระบุโซน') . ')' : 'ไม่ระบุ',
                                            'contact_user' => $res->contactUser?->fullname,
                                            'contact_dept' => $res->contactUser?->department?->dept_name_th,
                                            'contact_details' => $res->contact_details,
                                            'manager_status' => $res->manager_approval,
                                            'manager_name' => $res->manager?->fullname ?: ($res->contactUser?->department?->manager?->fullname ?: 'หัวหน้าแผนก'),
                                            'manager_at' => $res->manager_approved_at ? \App\Helpers\ThaiDate::format($res->manager_approved_at) : '-',
                                            'hams_status' => $res->hams_status,
                                            'hams_name' => $res->hamsAckBy?->fullname ?: 'HAMS',
                                            'hams_at' => $res->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($res->hams_acknowledged_at) : '-'
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold">
                                            {{ $res->contactUser?->fullname }}
                                            <span class="block text-[10px] text-blue-500 mt-0.5">
                                                แขกผู้มาติดต่อ
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">{{ $res->guest_name }}</div>
                                            <div class="text-xs text-slate-400">{{ $res->company ?: '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2.5 py-1 bg-slate-50 text-slate-800 rounded-lg border border-slate-200 font-bold tracking-wide text-xs">
                                                {{ $res->car_registration }}
                                            </span>
                                            <div class="text-xs text-slate-400 mt-1">{{ $res->phone ?: '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold">{{ \App\Helpers\ThaiDate::format($res->checkin_datetime) }}</td>
                                        <td class="px-6 py-4">
                                            @if($res->slot)
                                                <div class="font-bold text-[#b81515]">{{ $res->slot->slot_number }}</div>
                                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $res->slot->zone?->zone ?: 'ไม่ระบุโซน' }}</div>
                                            @else
                                                <div class="font-bold text-slate-400">ไม่ระบุ</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="showVisitorDetails(this)" data-visitor="{{ json_encode($visitorData) }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                    <i class="fa-solid fa-circle-info"></i> รายละเอียด
                                                </button>
                                                <form action="{{ route('parking.visitors.approve', ['id' => $res->id, 'type' => 'visitor']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                        <i class="fa-solid fa-check"></i> อนุมัติ
                                                    </button>
                                                </form>
                                                <form action="{{ route('parking.visitors.reject', ['id' => $res->id, 'type' => 'visitor']) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                        <i class="fa-solid fa-xmark"></i> ปฏิเสธ
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(isset($pendingManagerReservations) && $pendingManagerReservations->isNotEmpty())
                <!-- Manager Approvals Section (For Employees) -->
                <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-6 mb-8">
                    <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-emerald-500 rounded-full"></span>
                        คำขอจองลานจอดรถพนักงาน
                        @if(!auth()->user()->is_hams_admin && auth()->user()->role !== 'admin')
                            <span class="text-xs font-medium text-slate-500 font-normal">(รอหัวหน้าแผนกอนุมัติ)</span>
                        @else
                            <span class="text-xs font-medium text-slate-500 font-normal">(รอหัวหน้าแผนกอนุมัติ)</span>
                        @endif
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">ผู้ขอจอง</th>
                                    <th class="px-6 py-4">รายละเอียดพนักงาน</th>
                                    <th class="px-6 py-4">ทะเบียนรถ/โทร</th>
                                    <th class="px-6 py-4">เวลาเข้า</th>
                                    <th class="px-6 py-4">ช่องจอด</th>
                                    <th class="px-6 py-4 text-right">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($pendingManagerReservations as $res)
                                    @php
                                        $isManager = $managedDeptIds->contains($res->dept_id);
                                        $visitorData = [
                                            'guest_name' => $res->user?->fullname,
                                            'company' => $res->department?->dept_name_th ?: 'Kumwell',
                                            'car_registration' => $res->car_registration,
                                            'phone' => '-',
                                            'checkin' => \App\Helpers\ThaiDate::format($res->checkin_datetime),
                                            'duration' => '-',
                                            'slot' => $res->slot ? $res->slot->slot_number . ' (' . ($res->slot->zone?->zone ?: 'ไม่ระบุโซน') . ')' : 'ไม่ระบุ',
                                            'contact_user' => $res->user?->fullname,
                                            'contact_dept' => $res->department?->dept_name_th,
                                            'contact_details' => 'พนักงานขอจองเอง',
                                            'manager_status' => $res->manager_approval,
                                            'manager_name' => $res->manager?->fullname ?: ($res->contactUser?->department?->manager?->fullname ?: 'หัวหน้าแผนก'),
                                            'manager_at' => $res->manager_approved_at ? \App\Helpers\ThaiDate::format($res->manager_approved_at) : '-',
                                            'hams_status' => $res->hams_status,
                                            'hams_name' => $res->hamsAckBy?->fullname ?: 'HAMS',
                                            'hams_at' => $res->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($res->hams_acknowledged_at) : '-'
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold">
                                            {{ $res->user?->fullname }}
                                            <span class="block text-[10px] text-emerald-500 mt-0.5">
                                                พนักงานขอจองเอง
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">{{ $res->user?->fullname }}</div>
                                            <div class="text-xs text-slate-400">{{ $res->department?->dept_name_th ?: 'Kumwell' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2.5 py-1 bg-slate-50 text-slate-800 rounded-lg border border-slate-200 font-bold tracking-wide text-xs">
                                                {{ $res->car_registration }}
                                            </span>
                                            <div class="text-xs text-slate-400 mt-1">-</div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold">{{ \App\Helpers\ThaiDate::format($res->checkin_datetime) }}</td>
                                        <td class="px-6 py-4">
                                            @if($res->slot)
                                                <div class="font-bold text-[#b81515]">{{ $res->slot->slot_number }}</div>
                                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $res->slot->zone?->zone ?: 'ไม่ระบุโซน' }}</div>
                                            @else
                                                <div class="font-bold text-slate-400">ไม่ระบุ</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <button type="button" onclick="showVisitorDetails(this)" data-visitor="{{ json_encode($visitorData) }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                    <i class="fa-solid fa-circle-info"></i> รายละเอียด
                                                </button>
                                                @if($isManager)
                                                    <form action="{{ route('parking.visitors.approve', ['id' => $res->id, 'type' => 'employee']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                            <i class="fa-solid fa-check"></i> อนุมัติ
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('parking.visitors.reject', ['id' => $res->id, 'type' => 'employee']) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                            <i class="fa-solid fa-xmark"></i> ปฏิเสธ
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="text-xs font-bold text-amber-600 flex items-center">
                                                        <i class="fa-solid fa-clock mr-1"></i> รออนุมัติ
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($pendingHamsReservations->isNotEmpty())
                <!-- HAMS Acknowledgements Section -->
                <div class="bg-white rounded-2xl border border-indigo-100 shadow-sm p-6">
                    <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-indigo-500 rounded-full"></span>
                        คำขอจองลานจอดรถรอ Hams รับทราบ
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">ผู้ขอจอง</th>
                                    <th class="px-6 py-4">รายละเอียดแขก/พนักงาน</th>
                                    <th class="px-6 py-4">ทะเบียนรถ/โทร</th>
                                    <th class="px-6 py-4">ช่องจอด</th>
                                    <th class="px-6 py-4">ผู้อนุมัติ (หัวหน้า)</th>
                                    <th class="px-6 py-4 text-right">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($pendingHamsReservations as $res)
                                    @php
                                        $visitorData = [
                                            'guest_name' => $res->res_type == 'visitor' ? $res->guest_name : $res->user?->fullname,
                                            'company' => $res->res_type == 'visitor' ? ($res->company ?: '-') : ($res->department?->dept_name_th ?: 'Kumwell'),
                                            'car_registration' => $res->car_registration,
                                            'phone' => $res->res_type == 'visitor' ? ($res->phone ?: '-') : '-',
                                            'checkin' => \App\Helpers\ThaiDate::format($res->checkin_datetime),
                                            'duration' => ($res->res_type == 'visitor' && $res->duration_hours) ? $res->duration_hours . ' ชั่วโมง' : '-',
                                            'slot' => $res->slot ? $res->slot->slot_number . ' (' . ($res->slot->zone?->zone ?: 'ไม่ระบุโซน') . ')' : 'ไม่ระบุ',
                                            'contact_user' => $res->res_type == 'visitor' ? $res->contactUser?->fullname : $res->user?->fullname,
                                            'contact_dept' => $res->res_type == 'visitor' ? ($res->contactUser?->department?->dept_name_th ?: '-') : ($res->department?->dept_name_th ?: 'Kumwell'),
                                            'contact_details' => $res->res_type == 'visitor' ? ($res->contact_details ?: '-') : 'พนักงานขอจองเอง',
                                            'manager_status' => $res->manager_approval,
                                            'manager_name' => $res->manager?->fullname ?: ($res->contactUser?->department?->manager?->fullname ?: 'หัวหน้าแผนก'),
                                            'manager_at' => $res->manager_approved_at ? \App\Helpers\ThaiDate::format($res->manager_approved_at) : '-',
                                            'hams_status' => $res->hams_status,
                                            'hams_name' => $res->hamsAckBy?->fullname ?: 'HAMS',
                                            'hams_at' => $res->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($res->hams_acknowledged_at) : '-'
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-bold">
                                            {{ $res->res_type == 'visitor' ? $res->contactUser?->fullname : $res->user?->fullname }}
                                            <span class="block text-[10px] {{ $res->res_type == 'visitor' ? 'text-blue-500' : 'text-emerald-500' }} mt-0.5">
                                                {{ $res->res_type == 'visitor' ? 'แขกผู้มาติดต่อ' : 'พนักงานขอจองเอง' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">{{ $res->res_type == 'visitor' ? $res->guest_name : $res->user?->fullname }}</div>
                                            <div class="text-xs text-slate-400">{{ $res->res_type == 'visitor' ? ($res->company ?: '-') : ($res->department?->dept_name_th ?: 'Kumwell') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2.5 py-1 bg-slate-50 text-slate-800 rounded-lg border border-slate-200 font-bold tracking-wide text-xs">
                                                {{ $res->car_registration }}
                                            </span>
                                            <div class="text-xs text-slate-400 mt-1">{{ $res->res_type == 'visitor' ? $res->phone : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($res->slot)
                                                <div class="font-bold text-[#b81515]">{{ $res->slot->slot_number }}</div>
                                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $res->slot->zone?->zone ?: 'ไม่ระบุโซน' }}</div>
                                            @else
                                                <div class="font-bold text-slate-400">ไม่ระบุ</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-emerald-600">{{ $res->manager?->fullname }}</div>
                                            @if($res->manager_approved_at)
                                                <div class="text-[10px] text-slate-400 mt-1"><i class="fa-regular fa-calendar-check mr-1"></i>{{ \App\Helpers\ThaiDate::format($res->manager_approved_at) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button type="button" onclick="showVisitorDetails(this)" data-visitor="{{ json_encode($visitorData) }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                    <i class="fa-solid fa-circle-info"></i>
                                                </button>
                                                <form action="{{ route('parking.visitors.acknowledge', ['id' => $res->id, 'type' => $res->res_type]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-md">
                                                        <i class="fa-solid fa-check-double"></i> รับทราบ
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- My Bookings Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h4 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-[#b81515] rounded-full"></span>
                    ประวัติการขอจองลานจอดรถของฉัน
                </h4>
                @if($myReservations->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto text-slate-300 mb-4">
                            <i class="fa-solid fa-car text-2xl"></i>
                        </div>
                        <p class="text-slate-400 text-sm font-bold">ไม่พบประวัติการขอจองลานจอดรถ</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                                    <th class="px-6 py-4">ชื่อแขก</th>
                                    <th class="px-6 py-4">ทะเบียนรถ</th>
                                    <th class="px-6 py-4">ช่องจอด</th>
                                    <th class="px-6 py-4">เวลาเข้า</th>
                                    <th class="px-6 py-4">หัวหน้าแผนก</th>
                                    <th class="px-6 py-4">สถานะ HAMS</th>
                                    <th class="px-6 py-4">สถานะการจอง</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                @foreach($myReservations as $res)
                                    @php
                                        $visitorData = [
                                            'guest_name' => $res->res_type == 'visitor' ? $res->guest_name : $res->user?->fullname,
                                            'company' => $res->res_type == 'visitor' ? ($res->company ?: '-') : ($res->department?->dept_name_th ?: 'Kumwell'),
                                            'car_registration' => $res->car_registration,
                                            'phone' => $res->res_type == 'visitor' ? ($res->phone ?: '-') : '-',
                                            'checkin' => \App\Helpers\ThaiDate::format($res->checkin_datetime),
                                            'duration' => ($res->res_type == 'visitor' && $res->duration_hours) ? $res->duration_hours . ' ชั่วโมง' : '-',
                                            'slot' => $res->slot ? $res->slot->slot_number . ' (' . ($res->slot->zone?->zone ?: 'ไม่ระบุโซน') . ')' : 'ไม่ระบุ',
                                            'contact_user' => $res->res_type == 'visitor' ? $res->contactUser?->fullname : $res->user?->fullname,
                                            'contact_dept' => $res->res_type == 'visitor' ? ($res->contactUser?->department?->dept_name_th ?: '-') : ($res->department?->dept_name_th ?: 'Kumwell'),
                                            'contact_details' => $res->res_type == 'visitor' ? ($res->contact_details ?: '-') : 'พนักงานขอจองเอง',
                                            'manager_status' => $res->manager_approval,
                                            'manager_name' => $res->manager?->fullname ?: ($res->contactUser?->department?->manager?->fullname ?: 'หัวหน้าแผนก'),
                                            'manager_at' => $res->manager_approved_at ? \App\Helpers\ThaiDate::format($res->manager_approved_at) : '-',
                                            'hams_status' => $res->hams_status,
                                            'hams_name' => $res->hamsAckBy?->fullname ?: 'HAMS',
                                            'hams_at' => $res->hams_acknowledged_at ? \App\Helpers\ThaiDate::format($res->hams_acknowledged_at) : '-'
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">{{ $res->res_type == 'visitor' ? $res->guest_name : $res->user?->fullname }}</div>
                                            <div class="text-xs text-slate-400">
                                                {{ $res->res_type == 'visitor' ? ($res->company ?: '-') : 'ขอจองเอง (พนักงาน)' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-xs">{{ $res->car_registration }}</td>
                                        <td class="px-6 py-4">
                                            @if($res->slot)
                                                <div class="font-bold text-[#b81515]">{{ $res->slot->slot_number }}</div>
                                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $res->slot->zone?->zone ?: 'ไม่ระบุโซน' }}</div>
                                            @else
                                                <div class="font-bold text-slate-400">จัดสรรอัตโนมัติ</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-xs font-semibold">{{ \App\Helpers\ThaiDate::format($res->checkin_datetime) }}</td>
                                        <td class="px-6 py-4">
                                            @if($res->manager_approval === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                    <i class="fa-solid fa-clock text-[10px]"></i> รออนุมัติ
                                                </span>
                                            @elseif($res->manager_approval === 'approved')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i> อนุมัติแล้ว
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                                    <i class="fa-solid fa-circle-xmark text-[10px]"></i> ปฏิเสธ
                                                </span>
                                            @endif
                                            @if($res->res_type == 'employee' && $res->manager)
                                                <div class="text-[10px] text-slate-400 mt-0.5">โดย {{ $res->manager->fullname }}</div>
                                            @endif
                                            @if($res->manager_approved_at)
                                                <div class="text-[9px] text-slate-400 mt-0.5">เมื่อ {{ \App\Helpers\ThaiDate::format($res->manager_approved_at) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($res->hams_status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                                                    <i class="fa-solid fa-clock text-[10px]"></i> รอดำเนินการ
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                                    <i class="fa-solid fa-check-double text-[10px]"></i> HAMS รับทราบ
                                                </span>
                                                @if($res->hamsAckBy)
                                                    <div class="text-[10px] text-slate-400 mt-0.5">โดย {{ $res->hamsAckBy->fullname }}</div>
                                                @endif
                                                @if($res->hams_acknowledged_at)
                                                    <div class="text-[9px] text-slate-400 mt-0.5">เมื่อ {{ \App\Helpers\ThaiDate::format($res->hams_acknowledged_at) }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button type="button" onclick="showVisitorDetails(this)" data-visitor="{{ json_encode($visitorData) }}" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg transition-all text-xs flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-circle-info"></i> รายละเอียด
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Visitor Details Modal -->
<div id="visitor-details-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm transition-opacity duration-300 opacity-0" aria-hidden="true">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="visitor-details-modal-content">
        <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50">
            <h3 class="font-black text-lg text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-address-card text-blue-500"></i>
                รายละเอียดแขกผู้มาติดต่อ
            </h3>
            <button type="button" onclick="closeVisitorDetails()" class="text-slate-400 hover:text-rose-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-rose-50">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ชื่อแขก</div>
                    <div id="modal-guest-name" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">บริษัท</div>
                    <div id="modal-company" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ทะเบียนรถ</div>
                    <div id="modal-car-reg" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เบอร์โทรศัพท์</div>
                    <div id="modal-phone" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">เวลาเข้า</div>
                    <div id="modal-checkin" class="text-sm font-bold text-slate-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-amber-50 rounded-xl border border-amber-100">
                    <div class="text-[10px] font-bold text-amber-500 uppercase tracking-wider mb-1">ระยะเวลาจอด (โดยประมาณ)</div>
                    <div id="modal-duration" class="text-sm font-bold text-amber-700"></div>
                </div>
                <div class="col-span-2 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">ช่องจอด</div>
                    <div id="modal-slot" class="text-sm font-bold text-[#b81515]"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">ติดต่อพนักงาน / ผู้ขอจอง</div>
                    <div id="modal-contact-user" class="text-sm font-bold text-blue-800"></div>
                </div>
                <div class="col-span-2 sm:col-span-1 p-3 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">แผนกที่ติดต่อ</div>
                    <div id="modal-contact-dept" class="text-sm font-bold text-blue-800"></div>
                </div>
                <div class="col-span-2 p-3 bg-purple-50 rounded-xl border border-purple-100">
                    <div class="text-[10px] font-bold text-purple-400 uppercase tracking-wider mb-1">รายละเอียดการติดต่อ (เรื่องที่มาติดต่อ)</div>
                    <div id="modal-contact-details" class="text-sm font-bold text-purple-800 break-words whitespace-pre-wrap"></div>
                </div>
                <div class="col-span-2 mt-2 pt-4 border-t border-slate-200">
                    <div class="text-xs font-bold text-slate-800 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-timeline text-slate-400"></i> สเตปการอนุมัติ
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-emerald-100 text-emerald-600" id="modal-manager-icon">
                                <i class="fa-solid fa-check text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-slate-800">อนุมัติโดยหัวหน้าแผนก</div>
                                <div class="text-[10px] text-slate-500" id="modal-manager-text"></div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-indigo-100 text-indigo-600" id="modal-hams-icon">
                                <i class="fa-solid fa-check-double text-[10px]"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-bold text-slate-800">การรับทราบจาก HAMS</div>
                                <div class="text-[10px] text-slate-500" id="modal-hams-text"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-end">
            <button type="button" onclick="closeVisitorDetails()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition-all text-sm shadow-sm">
                ปิด
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showVisitorDetails(btn) {
        const data = JSON.parse(btn.getAttribute('data-visitor'));
        
        document.getElementById('modal-guest-name').innerText = data.guest_name || '-';
        document.getElementById('modal-company').innerText = data.company || '-';
        document.getElementById('modal-car-reg').innerText = data.car_registration || '-';
        document.getElementById('modal-phone').innerText = data.phone || '-';
        document.getElementById('modal-checkin').innerText = data.checkin || '-';
        document.getElementById('modal-duration').innerText = data.duration || '-';
        document.getElementById('modal-slot').innerText = data.slot || '-';
        document.getElementById('modal-contact-user').innerText = data.contact_user || '-';
        document.getElementById('modal-contact-dept').innerText = data.contact_dept || '-';
        document.getElementById('modal-contact-details').innerText = data.contact_details || '-';
        
        // Approval steps
        const mgrIcon = document.getElementById('modal-manager-icon');
        const mgrText = document.getElementById('modal-manager-text');
        if (data.manager_status === 'approved') {
            mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-emerald-100 text-emerald-600";
            mgrIcon.innerHTML = '<i class="fa-solid fa-check text-[10px]"></i>';
            mgrText.innerHTML = `อนุมัติแล้ว โดย <b>${data.manager_name}</b> <br> เมื่อ ${data.manager_at}`;
        } else if (data.manager_status === 'rejected') {
            mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-rose-100 text-rose-600";
            mgrIcon.innerHTML = '<i class="fa-solid fa-xmark text-[10px]"></i>';
            mgrText.innerHTML = `ปฏิเสธ โดย <b>${data.manager_name}</b> <br> เมื่อ ${data.manager_at}`;
        } else {
            mgrIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-amber-100 text-amber-600";
            mgrIcon.innerHTML = '<i class="fa-solid fa-clock text-[10px]"></i>';
            mgrText.innerHTML = `รอการอนุมัติ (โดย ${data.manager_name})`;
        }

        const hamsIcon = document.getElementById('modal-hams-icon');
        const hamsText = document.getElementById('modal-hams-text');
        if (data.hams_status === 'acknowledged') {
            hamsIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-indigo-100 text-indigo-600";
            hamsIcon.innerHTML = '<i class="fa-solid fa-check-double text-[10px]"></i>';
            hamsText.innerHTML = `รับทราบแล้ว โดย <b>${data.hams_name}</b> <br> เมื่อ ${data.hams_at}`;
        } else {
            hamsIcon.className = "w-6 h-6 rounded-full flex items-center justify-center mt-0.5 bg-slate-100 text-slate-400";
            hamsIcon.innerHTML = '<i class="fa-solid fa-clock text-[10px]"></i>';
            hamsText.innerHTML = `รอดำเนินการ`;
        }
        
        const modal = document.getElementById('visitor-details-modal');
        const modalContent = document.getElementById('visitor-details-modal-content');
        
        modal.classList.remove('hidden');
        // Slight delay to allow display block to apply before transition
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    function closeVisitorDetails() {
        const modal = document.getElementById('visitor-details-modal');
        const modalContent = document.getElementById('visitor-details-modal-content');
        
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        
        // Wait for transition to finish before hiding
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush
