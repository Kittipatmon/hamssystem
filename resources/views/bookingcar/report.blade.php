@extends('layouts.bookingcar.appcar')

@section('content')

    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-8 animate-fadeIn">
        <h2 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-800 flex items-center gap-3 mb-6">
            <i class="fa-solid fa-chart-pie text-red-500"></i>
            รายงานสรุปการใช้รถส่วนกลาง
        </h2>

        <!-- Dashboard Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-[12px] font-semibold text-slate-400 uppercase tracking-widest">การจองทั้งหมด</div>
                    <div class="text-2xl font-bold text-slate-700 mt-0.5">{{ $totalBookings }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <div class="text-[12px] font-semibold text-slate-400 uppercase tracking-widest">อนุมัติแล้ว</div>
                    <div class="text-2xl font-bold text-slate-700 mt-0.5">{{ $approvedBookings }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div class="text-[12px] font-semibold text-slate-400 uppercase tracking-widest">รอพิจารณา</div>
                    <div class="text-2xl font-bold text-slate-700 mt-0.5">{{ $pendingBookings }}</div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <div class="text-[12px] font-semibold text-slate-400 uppercase tracking-widest">ไม่อนุมัติ / ยกเลิก
                    </div>
                    <div class="text-2xl font-bold text-slate-700 mt-0.5">{{ $rejectedBookings }}</div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-slate-50 rounded-2xl shadow-sm border border-slate-200 p-6 mb-8 bg-gradient-to-br from-slate-50 to-white/50">
            <h3 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2 uppercase tracking-wide">
                <i class="fa-solid fa-filter text-red-500"></i> ตัวกรองข้อมูล
            </h3>
            <form action="{{ route('bookingcar.report') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                    <!-- Transaction Date -->
                    <div class="form-control w-full">
                        <label class="label py-1">
                            <span class="label-text font-bold text-slate-700 text-[12px] uppercase">วันที่ทำรายการ</span>
                        </label>
                        <input type="date" name="transaction_date" value="{{ request('transaction_date') }}" 
                               class="input input-bordered w-full bg-white border-slate-200 focus:border-red-500 focus:ring-red-500 text-sm h-11 shadow-sm" />
                    </div>

                    <!-- Unified Search -->
                    <div class="form-control w-full">
                        <label class="label py-1">
                            <span class="label-text font-bold text-slate-700 text-[12px] uppercase">ค้นหา (ผู้จอง / จุดหมายปลายทาง)</span>
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ชื่อผู้จอง หรือ สถานที่ปลายทาง..."
                               class="input input-bordered w-full bg-white border-slate-200 focus:border-red-500 focus:ring-red-500 text-sm h-11 shadow-sm" />
                    </div>

                    <!-- Status -->
                    <div class="form-control w-full">
                        <label class="label py-1">
                            <span class="label-text font-bold text-slate-700 text-[12px] uppercase">สถานะ</span>
                        </label>
                        <select name="status" class="select select-bordered w-full bg-white border-slate-200 focus:border-red-500 focus:ring-red-500 text-sm h-11 shadow-sm">
                            <option value="">ทั้งหมด</option>
                            <option value="รออนุมัติ" {{ request('status') === 'รออนุมัติ' ? 'selected' : '' }}>รออนุมัติ</option>
                            <option value="อนุมัติแล้ว" {{ request('status') === 'อนุมัติแล้ว' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                            <option value="ไม่อนุมัติ" {{ request('status') === 'ไม่อนุมัติ' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                            <option value="ยกเลิก" {{ request('status') === 'ยกเลิก' ? 'selected' : '' }}>ยกเลิก</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-3 flex justify-end gap-2 mt-2">
                        <a href="{{ route('bookingcar.report') }}" class="btn btn-ghost hover:bg-slate-100 text-slate-500 h-10 px-6 font-semibold border border-slate-200">
                            ล้างค่า
                        </a>
                        <button type="submit" class="btn bg-red-500 hover:bg-red-600 border-none text-white h-10 px-8 font-semibold shadow-md shadow-red-100">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i> ค้นหา
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-700 text-lg mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> ความเคลื่อนไหวล่าสุด ({{ $recentBookings->total() }} รายการ)
                </span>
            </h3>

            <div class="overflow-x-auto">
                <table class="table w-full text-[13px]">
                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100">
                        <tr>
                            <th class="py-3 pl-4">วันที่ทำรายการ</th>
                            <th class="py-3">ผู้จอง</th>
                            <th class="py-3">จุดหมายปลายทาง</th>
                            <th class="py-3 text-center">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600">
                        @foreach ($recentBookings as $item)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                <td class="pl-4 py-3 text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="py-3 font-medium">{{ $item->user->first_name ?? 'N/A' }}
                                    {{ $item->user->last_name ?? '' }}</td>
                                <td class="py-3">{{ $item->destination }}</td>
                                <td class="py-3 text-center">
                                    <span
                                        class="text-[11px] font-semibold px-2 py-1 rounded-md {{ $item->status === 'อนุมัติแล้ว' ? 'bg-green-100 text-green-700' : ($item->status === 'รออนุมัติ' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center pagination-premium">
                {{ $recentBookings->links() }}
            </div>
        </div>
    </div>
@endsection