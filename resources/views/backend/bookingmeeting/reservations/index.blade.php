@extends('layouts.navmeeting.app')

@section('title', 'รายการจองห้องประชุม')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header / Banner -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white flex flex-col items-center justify-center relative overflow-hidden">
            <div class="flex items-center gap-3 relative z-10 mb-2">
                <i class="fa-solid fa-list-check text-4xl"></i>
                <h2 class="text-3xl font-bold tracking-wide">รายการจองห้องประชุม</h2>
            </div>
            <p class="text-blue-100 relative z-10 text-sm">ตรวจสอบและจัดการข้อมูลการจองห้องประชุม</p>

            <!-- Decorative SVG -->
            <div class="absolute top-0 left-0 opacity-10 pointer-events-none transform -translate-x-1/4 -translate-y-1/4">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M19,4H18V2H16V4H8V2H6V4H5C3.89,4 3.01,4.9 3.01,6L3,20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V6A2,2 0 0,0 19,4M19,20H5V10H19V20M19,8H5V6H19V8Z" />
                </svg>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <form action="{{ route('backend.bookingmeeting.reservations.index') }}" method="GET"
                class="flex flex-col md:flex-row flex-wrap gap-4 items-end">

                <div class="w-full md:w-auto flex-1">
                    <label class="block text-xs font-medium text-slate-500 mb-1">ค้นหา</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-slate-400 text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="หัวข้อประชุม, ชื่อผู้ขอ, ชื่อห้อง..."
                            class="input input-bordered w-full pl-10 h-10 text-sm focus:border-blue-500">
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <label class="block text-xs font-medium text-slate-500 mb-1">สถานะ</label>
                    <select name="status" class="select select-bordered w-full md:w-40 h-10 min-h-10 text-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>ทุกสถานะ</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รออนุมัติ</option>
                        <option value="acknowledge" {{ request('status') == 'acknowledge' ? 'selected' : '' }}>อนุมัติแล้ว
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                </div>

                <button type="submit"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white border-0 h-10 min-h-10 px-6 w-full md:w-auto shadow-md shadow-blue-200">
                    <i class="fa-solid fa-filter"></i> กรองข้อมูล
                </button>
                <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                    class="btn bg-slate-100 hover:bg-slate-200 text-slate-600 border-0 h-10 min-h-10 px-4 w-full md:w-auto">
                    ล้างค่า
                </a>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="w-16 text-center font-medium">#</th>
                            <th class="font-medium">รายละเอียดการจอง</th>
                            <th class="font-medium">ห้องประชุม / เวลา</th>
                            <th class="text-center font-medium">ผู้ขอจอง</th>
                            <th class="text-center font-medium">สถานะ</th>
                            <th class="text-center font-medium w-28">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $index => $res)
                            <tr class="hover whitespace-nowrap lg:whitespace-normal">
                                <td class="text-center font-medium text-slate-500">{{ $reservations->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="font-bold text-slate-800 text-base mb-0.5 truncate max-w-xs"
                                        title="{{ $res->topic }}">{{ $res->topic }}</div>
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span><i class="fa-solid fa-users text-blue-400 mr-1"></i> {{ $res->participant_count }}
                                            คน</span>
                                        @if($res->reservation_code)
                                            <span class="text-slate-400 px-1.5 py-0.5 bg-slate-100 rounded">Ref:
                                                {{ $res->reservation_code }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-semibold text-indigo-700"><i class="fa-solid fa-door-open mr-1"></i>
                                        {{ $res->room->room_name ?? 'N/A' }}</div>
                                    <div class="text-[12px] text-slate-600 flex items-center gap-1 mt-0.5">
                                        <i class="fa-regular fa-calendar text-slate-400"></i>
                                        {{ \Carbon\Carbon::parse($res->reservation_date)->locale('th')->addYears(543)->translatedFormat('d M Y') }}
                                        <i class="fa-regular fa-clock text-slate-400 ml-1"></i>
                                        {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} น.
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-slate-700 font-medium">{{ $res->requester_name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $res->user->emp_code ?? '' }}</div>
                                </td>
                                <td class="text-right">
                                    <div class="flex flex-col items-end gap-2 pr-2">
                                        @if($res->status == 'pending')
                                            <div class="flex items-center gap-1.5">
                                                <span
                                                    class="bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs flex items-center gap-1 shadow-sm">
                                                    <i class="fa-regular fa-clock"></i> รออนุมัติ
                                                </span>
                                                <div class="flex items-center bg-white border border-slate-200 rounded-lg p-0.5 shadow-sm">
                                                    <form
                                                        action="{{ route('backend.bookingmeeting.reservations.update_status', $res->reservation_id) }}"
                                                        method="POST" class="m-0 p-0 confirm-submit"
                                                        data-msg="อนุมัติการจองห้องประชุมรายการนี้?">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="acknowledge">
                                                        <button type="submit"
                                                            class="w-7 h-7 rounded-md text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300"
                                                            title="อนุมัติการจอง">
                                                            <i class="fa-solid fa-check text-xs"></i>
                                                        </button>
                                                    </form>
                                                    <div class="w-px h-3 bg-slate-200 mx-0.5"></div>
                                                    <form
                                                        action="{{ route('backend.bookingmeeting.reservations.update_status', $res->reservation_id) }}"
                                                        method="POST" class="m-0 p-0 confirm-submit"
                                                        data-msg="ไม่อนุมัติการจองห้องประชุมรายการนี้?"
                                                        data-type="warning">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit"
                                                            class="w-7 h-7 rounded-md text-orange-600 hover:bg-orange-600 hover:text-white flex items-center justify-center transition-all duration-300"
                                                            title="ไม่อนุมัติ">
                                                            <i class="fa-solid fa-xmark text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif($res->status == 'acknowledge' || $res->status == 'approved' || $res->status == 'อนุมัติ' || $res->status == 'เสร็จสิ้น')
                                            <span
                                                class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-check"></i> อนุมัติแล้ว
                                            </span>
                                            @if($res->approvedBy)
                                                <div class="text-[10px] text-emerald-600 mt-1 font-bold opacity-80">
                                                    <i class="fa-solid fa-user-check mr-1"></i>โดย: {{ $res->approvedBy->fullname }}
                                                </div>
                                            @endif
                                        @elseif($res->status == 'rejected' || $res->status == 'ไม่อนุมัติ')
                                            <span
                                                class="bg-red-100 text-red-700 border border-red-200 px-3 py-1 rounded-full text-xs flex items-center gap-1 shadow-sm">
                                                <i class="fa-solid fa-xmark"></i> ไม่อนุมัติ
                                            </span>
                                            @if($res->approvedBy)
                                                <div class="text-[10px] text-red-600 mt-1 font-bold opacity-80">
                                                    <i class="fa-solid fa-user-check mr-1"></i>โดย: {{ $res->approvedBy->fullname }}
                                                </div>
                                            @endif
                                        @elseif($res->status == 'cancelled' || $res->status == 'ยกเลิก')
                                            <span
                                                class="bg-orange-100 text-orange-700 border border-orange-200 px-3 py-1 rounded-full text-xs flex items-center gap-1 shadow-sm transition-all duration-300">
                                                <i class="fa-solid fa-ban"></i> ยกเลิก
                                            </span>
                                        @else
                                            <span
                                                class="bg-slate-100 text-slate-600 border border-slate-200 px-3 py-1 rounded-full text-xs shadow-sm capitalize">{{ $res->status }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5 pr-2">
                                        <a href="{{ route('backend.bookingmeeting.reservations.show', $res->reservation_id) }}"
                                            class="w-8 h-8 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 flex items-center justify-center transition-colors"
                                            title="ดูรายละเอียด">
                                            <i class="fa-regular fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('backend.bookingmeeting.reservations.edit', $res->reservation_id) }}"
                                            class="w-8 h-8 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 flex items-center justify-center transition-colors"
                                            title="แก้ไขการจอง">
                                            <i class="fa-regular fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form
                                            action="{{ route('backend.bookingmeeting.reservations.destroy', $res->reservation_id) }}"
                                            method="POST" class="m-0 p-0 confirm-submit"
                                            data-msg="ยืนยันลบรายการจองหมายเลข {{ $res->reservation_id }} นี้ทิ้งอย่างถาวร?"
                                            data-type="danger">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200 flex items-center justify-center transition-colors"
                                                title="ลบข้อมูล">
                                                <i class="fa-regular fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="text-slate-400 flex flex-col items-center">
                                        <i class="fa-regular fa-calendar-xmark text-4xl mb-3 text-slate-300"></i>
                                        <p class="text-base font-medium">ไม่พบรายการจองห้องประชุม</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reservations->hasPages())
                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmForms = document.querySelectorAll('.confirm-submit');
        confirmForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = this.getAttribute('data-msg') || 'ยืนยันการทำรายการ?';
                const type = this.getAttribute('data-type') || 'question';
                let icon = 'question';
                let confirmBtnColor = '#3b82f6';
                if (type === 'warning') {
                    icon = 'warning';
                    confirmBtnColor = '#f59e0b';
                } else if (type === 'danger') {
                    icon = 'error';
                    confirmBtnColor = '#ef4444';
                } else if (msg.includes('อนุมัติ')) {
                    icon = 'success';
                    confirmBtnColor = '#10b981';
                }
                Swal.fire({
                    title: '<span class="font-prompt text-xl font-bold">ยืนยันการทำรายการ</span>',
                    html: `<p class="text-slate-600 font-medium">${msg}</p>`,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'ยืนยันตกลง',
                    cancelButtonText: 'ยกเลิก',
                    padding: '2rem',
                    customClass: {
                        popup: 'rounded-3xl border-0 shadow-2xl',
                        title: 'font-prompt'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
