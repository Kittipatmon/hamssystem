@extends('layouts.bookingcar.appcar')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-list-ul text-red-500"></i>
                    รายการจองรถส่วนกลาง
                </h2>
                <p class="text-slate-500 mt-1 text-sm">จัดการคำร้องขอใช้รถส่วนกลางทั้งหมด (Dashboard)</p>
            </div>
            <a href="{{ route('bookingcar.welcome') }}" 
               class="btn btn-sm bg-gradient-to-r from-red-500 to-red-700 text-white hover:from-red-600 hover:to-red-800 border-0 shadow-md rounded-full px-6">
                <i class="fa-solid fa-plus mr-1"></i> จองรถใหม่
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-6 bg-green-50 text-green-800 border-green-200">
                <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Data Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table w-full text-[13px]">
                    <!-- head -->
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-4 pl-6 text-center">ลำดับ</th>
                            <th class="py-4">เลขที่ใบจอง</th>
                            <th class="py-4">ผู้จอง / วันที่เดินทาง</th>
                            <th class="py-4">จุดหมายปลายทาง</th>
                            <th class="py-4">รถที่จอง</th>
                            <th class="py-4 text-center">สถานะการจอง</th>
                            <th class="py-4 text-center">คืนรถ</th>
                            <th class="py-4 pr-6 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        @forelse ($bookings as $index => $item)
                            <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-50">
                                <td class="pl-6 text-center w-12">{{ $bookings->firstItem() + $index }}</td>
                                <td class="font-mono text-xs text-slate-500">{{ $item->booking_code }}</td>
                                <td>
                                    <div class="font-semibold text-slate-800">{{ $item->user->first_name ?? 'N/A' }} {{ $item->user->last_name ?? '' }}</div>
                                    <div class="text-[11px] text-slate-400 mt-1">
                                        <i class="fa-regular fa-calendar-days mr-1"></i>
                                        {{ \Carbon\Carbon::parse($item->booking_date)->format('d/m/Y') }} 
                                        ({{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }})
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-700 max-w-[150px] truncate" title="{{ $item->destination }}">
                                        {{ $item->destination }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1">อ.{{ $item->district }} จ.{{ $item->province }}</div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 font-medium text-xs">
                                        <i class="fa-solid fa-car"></i> {{ $item->vehicle->name ?? 'ไม่ระบุรถ' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusClass = match($item->status) {
                                            'อนุมัติแล้ว' => 'bg-green-100 text-green-700 border-green-200',
                                            'รออนุมัติ' => 'bg-orange-100 text-orange-700 border-orange-200',
                                            'ไม่อนุมัติ', 'ยกเลิก' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                        $iconClass = match($item->status) {
                                            'อนุมัติแล้ว' => 'fa-check',
                                            'รออนุมัติ' => 'fa-clock',
                                            'ไม่อนุมัติ', 'ยกเลิก' => 'fa-xmark',
                                            default => 'fa-circle-question'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border text-[11px] font-semibold {{ $statusClass }}">
                                        <i class="fa-solid {{ $iconClass }}"></i> {{ $item->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item->status === 'อนุมัติแล้ว')
                                        @php
                                            $retClass = match($item->return_status) {
                                                'ยังไม่ส่งคืน' => 'text-orange-500 bg-orange-50',
                                                'ส่งคืนแล้ว' => 'text-blue-600 bg-blue-50',
                                                'มีปัญหา' => 'text-red-600 bg-red-50',
                                                default => 'text-slate-500'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[11px] font-medium {{ $retClass }}">
                                            {{ $item->return_status }}
                                        </span>
                                    @else
                                        <span class="text-[11px] text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="pr-6 text-center">
                                    <a href="{{ route('bookingcar.edit', $item->booking_id) }}" 
                                       class="btn btn-xs btn-circle btn-ghost text-blue-500 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                       title="จัดการ/แก้ไข">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center">
                                            <i class="fa-solid fa-folder-open text-2xl text-slate-300"></i>
                                        </div>
                                        <p>ยังไม่มีรายการจองรถในระบบ</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex justify-center">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
