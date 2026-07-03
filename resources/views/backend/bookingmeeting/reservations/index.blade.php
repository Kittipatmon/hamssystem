@extends('layouts.navmeeting.app')

@section('title', 'รายการจองห้องประชุม')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header / Banner -->
        <div class="bg-gradient-to-r from-[#e53935] to-[#c62828] rounded-xl shadow-md p-5 text-white flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center text-2xl shadow-inner">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-wide uppercase">ทะเบียนควบคุมการจองห้องประชุม</h2>
                    <p class="text-red-100 text-xs mt-0.5 font-medium">ตรวจสอบ ติดตาม และจัดการสิทธิ์อนุมัติการจองห้องประชุมของพนักงาน</p>
                </div>
            </div>
            
            <div class="mt-4 md:mt-0 relative z-10">
                <span class="text-[11px] font-bold bg-white/20 text-white border border-white/20 px-3 py-1 rounded-full">
                    ระบบจัดการห้องประชุมส่วนกลาง
                </span>
            </div>

            <!-- Decorative SVG -->
            <div class="absolute top-0 right-0 opacity-10 pointer-events-none transform translate-x-1/4 -translate-y-1/4">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19,4H18V2H16V4H8V2H6V4H5C3.89,4 3.01,4.9 3.01,6L3,20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V6A2,2 0 0,0 19,4M19,20H5V10H19V20M19,8H5V6H19V8Z" />
                </svg>
            </div>
        </div>

        <!-- Filters Form -->
        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
            <form action="{{ route('backend.bookingmeeting.reservations.index') }}" method="GET"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">ค้นหาข้อมูล</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-search text-slate-400 text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="หัวข้อประชุม, ผู้จอง, ชื่อห้อง..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 pl-9 pr-3 text-xs focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 mb-1.5">กรองตามสถานะ</label>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-md h-9 px-3 text-xs focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>-- ทุกสถานะ --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รออนุมัติ</option>
                        <option value="acknowledge" {{ request('status') == 'acknowledge' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                    </select>
                </div>

                <div class="flex gap-2 sm:col-span-2 md:col-span-2">
                    <button type="submit"
                        class="bg-[#c31919] hover:bg-red-800 text-white font-bold h-9 text-xs rounded-md px-6 shadow-sm transition-all flex items-center justify-center gap-1.5 flex-1 md:flex-initial">
                        <i class="fa-solid fa-filter"></i> ค้นหา
                    </button>
                    <a href="{{ route('backend.bookingmeeting.reservations.index') }}"
                        class="bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-md h-9 px-4 flex items-center justify-center text-xs font-bold transition-all">
                        ล้างเงื่อนไข
                    </a>
                </div>
            </form>
        </div>

        <!-- Master Registry Data Table (Hospital Grid Layout) -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between no-print">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-database text-red-700 text-sm"></i>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                        บัญชีรายชื่อควบคุมบันทึกการจองห้องประชุมส่วนกลาง
                    </h2>
                </div>
                @if($reservations->total() > 0)
                    <span class="text-[11px] font-bold bg-slate-200/80 text-slate-600 px-2.5 py-0.5 rounded-full">
                        พบข้อมูล {{ $reservations->total() }} รายการ
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border-slate-200 text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center w-12">#</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 w-28">รหัสใบจอง</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 min-w-[220px]">หัวข้อและวัตถุประสงค์การประชุม</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 w-44">ห้องประชุม / วันเวลาใช้งาน</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 min-w-[150px]">ผู้ขอจอง / ผู้เรียนเสนอ</th>
                            <th class="py-3.5 px-3 border-r border-slate-200 text-center w-32">ผลอนุมัติ</th>
                            <th class="py-3.5 px-3 text-center w-24 no-print">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($reservations as $index => $res)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Row number -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                                    {{ $reservations->firstItem() + $index }}
                                </td>

                                <!-- Reference Code -->
                                <td class="py-3.5 px-3 border-r border-slate-200 font-mono text-[10px] font-bold text-slate-700">
                                    {{ $res->reservation_code ?? 'N/A' }}
                                </td>

                                <!-- Topic & Objective -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800 leading-tight">
                                        {{ $res->topic }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-semibold">
                                        <i class="fa-solid fa-users text-red-500/50"></i>
                                        จำนวนผู้เข้าประชุม: {{ $res->participant_count }} คน
                                    </div>
                                    @if($res->objective)
                                        <div class="text-[10px] text-slate-500 mt-1.5 flex items-start gap-1 font-medium bg-slate-50 p-1.5 rounded border border-slate-100">
                                            <i class="fa-regular fa-comment-dots text-slate-400 mt-0.5 shrink-0"></i>
                                            <span class="italic">"{{ $res->objective }}"</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Room & Datetime -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-tight">
                                    <div class="font-bold text-red-700 flex items-center gap-1 mb-2">
                                        <i class="fa-solid fa-door-open text-xs text-red-700/60"></i>
                                        {{ $res->room->room_name ?? 'N/A' }}
                                    </div>
                                    <div class="space-y-1 pl-1 border-l border-slate-200">
                                        <div class="text-[10px] font-bold text-slate-700 flex items-center gap-1">
                                            <i class="fa-regular fa-calendar text-[10px]"></i>
                                            {{ \Carbon\Carbon::parse($res->reservation_date)->locale('th')->addYears(543)->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-[10px] font-bold text-[#c31919] flex items-center gap-1">
                                            <i class="fa-regular fa-clock text-[10px]"></i>
                                            {{ substr($res->start_time, 0, 5) }} - {{ substr($res->end_time, 0, 5) }} น.
                                        </div>
                                    </div>
                                </td>

                                <!-- Requester Info -->
                                <td class="py-3.5 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800">
                                        {{ $res->user->first_name ?? 'N/A' }} {{ $res->user->last_name ?? '' }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-semibold mt-0.5">
                                        รหัส: {{ $res->user->emp_code ?? '-' }}
                                    </div>
                                    @if($res->requester_name)
                                        <div class="text-[9px] text-red-600 font-bold mt-1.5 italic bg-red-50 px-1.5 py-0.5 rounded border border-red-100/50 w-fit">
                                            เรียนเสนอ: {{ $res->requester_name }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status & Actions -->
                                <td class="py-3.5 px-3 border-r border-slate-200 text-center font-bold">
                                    <div class="flex flex-col items-center gap-2">
                                        @if($res->status == 'pending')
                                            <div class="flex flex-col items-center gap-1.5">
                                                <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded text-[10px] flex items-center gap-1 shadow-sm font-semibold">
                                                    <i class="fa-regular fa-clock"></i> รออนุมัติ
                                                </span>
                                                <div class="flex items-center bg-white border border-slate-200 rounded p-0.5 shadow-sm">
                                                    <form action="{{ route('backend.bookingmeeting.reservations.update_status', $res->reservation_id) }}"
                                                        method="POST" class="m-0 p-0 confirm-submit"
                                                        data-msg="อนุมัติการจองห้องประชุมรายการนี้?">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="acknowledge">
                                                        <button type="submit"
                                                            class="w-6 h-6 rounded text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all duration-300"
                                                            title="อนุมัติการจอง">
                                                            <i class="fa-solid fa-check text-[10px]"></i>
                                                        </button>
                                                    </form>
                                                    <div class="w-px h-3 bg-slate-200 mx-0.5"></div>
                                                    <form action="{{ route('backend.bookingmeeting.reservations.update_status', $res->reservation_id) }}"
                                                        method="POST" class="m-0 p-0 confirm-submit"
                                                        data-msg="ไม่อนุมัติการจองห้องประชุมรายการนี้?"
                                                        data-type="warning">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit"
                                                            class="w-6 h-6 rounded text-orange-600 hover:bg-orange-600 hover:text-white flex items-center justify-center transition-all duration-300"
                                                            title="ไม่อนุมัติ">
                                                            <i class="fa-solid fa-xmark text-[10px]"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @elseif($res->status == 'acknowledge' || $res->status == 'approved' || $res->status == 'อนุมัติ' || $res->status == 'เสร็จสิ้น')
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded text-[10px] flex items-center gap-1 shadow-sm font-semibold">
                                                <i class="fa-solid fa-check"></i> อนุมัติแล้ว
                                            </span>
                                            @if($res->approvedBy)
                                                <div class="text-[9px] text-emerald-600 mt-1 font-bold opacity-80">
                                                    โดย: {{ $res->approvedBy->fullname }}
                                                </div>
                                            @endif
                                        @elseif($res->status == 'rejected' || $res->status == 'ไม่อนุมัติ')
                                            <span class="bg-red-50 text-red-700 border border-red-200 px-2 py-0.5 rounded text-[10px] flex items-center gap-1 shadow-sm font-semibold">
                                                <i class="fa-solid fa-xmark"></i> ไม่อนุมัติ
                                            </span>
                                            @if($res->approvedBy)
                                                <div class="text-[9px] text-red-600 mt-1 font-bold opacity-80">
                                                    โดย: {{ $res->approvedBy->fullname }}
                                                </div>
                                            @endif
                                        @elseif($res->status == 'cancelled' || $res->status == 'ยกเลิก')
                                            <span class="bg-slate-50 text-slate-500 border border-slate-200 px-2 py-0.5 rounded text-[10px] flex items-center gap-1 shadow-sm font-semibold">
                                                <i class="fa-solid fa-ban"></i> ยกเลิก
                                            </span>
                                        @else
                                            <span class="bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded text-[10px] shadow-sm font-semibold capitalize">{{ $res->status }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Manage buttons -->
                                <td class="py-3.5 px-3 text-center no-print">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('backend.bookingmeeting.reservations.edit', $res->reservation_id) }}"
                                            class="w-7 h-7 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 flex items-center justify-center transition-colors"
                                            title="แก้ไขการจอง">
                                            <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
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
                let confirmBtnColor = '#c31919';
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
                    title: '<span class="font-prompt text-lg font-black">ยืนยันการทำรายการ</span>',
                    html: `<p class="text-slate-600 text-xs font-semibold">${msg}</p>`,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'ยืนยันตกลง',
                    cancelButtonText: 'ยกเลิก',
                    padding: '1.5rem',
                    customClass: {
                        popup: 'rounded-2xl border-0 shadow-2xl',
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
