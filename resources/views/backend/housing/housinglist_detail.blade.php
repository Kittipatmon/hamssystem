@extends('layouts.housing.apphousing')

@section('title', 'รายละเอียดห้องพัก')

@section('content')
<div class="max-w-4xl mx-auto pb-12">

    {{-- Header with Back Button --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('housing.houselist') }}" class="group flex items-center gap-2 text-slate-500 hover:text-red-600 transition-colors font-semibold">
            <div class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-red-50 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </div>
            <span>กลับไปหน้ารายการ</span>
        </a>
        <div class="text-right">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">ห้อง {{ $room->room_number }}</h2>
            <p class="text-xs text-slate-400 font-medium">พื้นที่: {{ $room->residence->name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left: Room Info Card --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-48 bg-slate-100 dark:bg-gray-700 relative overflow-hidden flex items-center justify-center">
                    @if($room->image)
                        <img src="{{ asset($room->image) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-bed text-5xl text-slate-300"></i>
                    @endif
                    
                    <div class="absolute top-4 right-4">
                        @php
                            $status = $room->residence_room_status;
                            $statusColors = [
                                0 => 'bg-emerald-500',
                                1 => 'bg-red-500',
                                2 => 'bg-amber-500'
                            ];
                            $statusLabels = [
                                0 => 'ว่าง',
                                1 => 'ไม่ว่าง',
                                2 => 'ซ่อม/ปรับปรุง'
                            ];
                        @endphp
                        <span class="{{ $statusColors[$status] }} text-white text-[10px] font-bold px-3 py-1 rounded-full shadow-lg">
                            {{ $statusLabels[$status] }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700">
                        <span class="text-xs text-slate-400 font-bold uppercase">ชั้น</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $room->floor }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-700">
                        <span class="text-xs text-slate-400 font-bold uppercase">ความจุ</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $room->capacity ?? '-' }} คน</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs text-slate-400 font-bold uppercase">ราคา</span>
                        <span class="font-bold text-red-600">{{ number_format($room->price ?? 0) }} ฿</span>
                    </div>
                </div>
            </div>

            @if($room->note)
            <div class="bg-blue-50 dark:bg-blue-900/20 p-5 rounded-2xl border border-blue-100 dark:border-blue-800">
                <h4 class="text-blue-600 dark:text-blue-400 text-xs font-bold mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info"></i> หมายเหตุเพิ่มเติม
                </h4>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $room->note }}</p>
            </div>
            @endif
        </div>

        {{-- Right: Main Content (Occupant or Assignment) --}}
        <div class="lg:col-span-2 space-y-6">
            
            @if($room->residence_room_status == 1 && $currentStay)
                {{-- Occupant Info --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-red-50/50 dark:bg-red-900/10 border-b border-red-50 dark:border-red-900/20">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-red-500"></i>
                            ข้อมูลผู้เข้าพักปัจจุบัน
                        </h3>
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-6 mb-8 p-4 bg-slate-50 dark:bg-gray-700/50 rounded-2xl border border-slate-100 dark:border-gray-600">
                            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-2xl shadow-inner">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">ผู้เข้าพัก</p>
                                <p class="text-lg font-black text-slate-800 dark:text-white">โปรดตรวจสอบข้อมูลในระบบ HAMS</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div class="p-4 rounded-2xl border border-gray-50 dark:border-gray-700">
                                <p class="text-slate-400 text-[10px] uppercase font-bold mb-1">วันที่เข้าพัก</p>
                                <p class="font-bold text-gray-700 dark:text-gray-200">
                                    <i class="fa-solid fa-calendar-day mr-2 text-red-400"></i>
                                    {{ $currentStay->residence_stay_date ? \Carbon\Carbon::parse($currentStay->residence_stay_date)->format('d/m/Y') : ($currentStay->check_in ? \Carbon\Carbon::parse($currentStay->check_in)->format('d/m/Y') : '-') }}
                                </p>
                            </div>
                            <div class="p-4 rounded-2xl border border-gray-50 dark:border-gray-700">
                                <p class="text-slate-400 text-[10px] uppercase font-bold mb-1">เบอร์ติดต่อ</p>
                                <p class="font-bold text-gray-700 dark:text-gray-200">
                                    <i class="fa-solid fa-phone mr-2 text-red-400"></i>
                                    {{ $currentStay->tel_phone ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($room->residence_room_status == 0)
                {{-- Assignment Form --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-emerald-50/50 dark:bg-emerald-900/10 border-b border-emerald-50 dark:border-emerald-900/20">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-key text-emerald-500"></i>
                            มอบหมายห้องพัก
                        </h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-2xl text-emerald-700 dark:text-emerald-400 text-sm italic">
                            เลือกผู้ส่งคำขอเข้าพักที่ผ่านการพิจารณาเบื้องต้นแล้ว เพื่อมอบหมายห้องนี้และปรับสถานะเป็น "ดำเนินการเสร็จสิ้น"
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1 tracking-wider">เลือกผู้ส่งคำขอเข้าพัก</label>
                                <select id="requestSelect" class="select2 w-full px-4 py-3 rounded-xl border-gray-200 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:ring-red-500 transition-all">
                                    <option value="">-- กรุณาเลือกรายชื่อ --</option>
                                    @foreach($eligibleRequesters as $req)
                                        <option value="{{ $req->id }}">{{ $req->requests_code }} - {{ $req->first_name }} {{ $req->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button onclick="submitRoomAssignment({{ $room->residence_room_id }})" 
                                    class="w-full bg-gradient-to-r from-red-600 to-orange-600 text-white font-bold py-4 rounded-2xl shadow-xl shadow-red-200 dark:shadow-none hover:shadow-2xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3 text-lg">
                                <i class="fa-solid fa-check-circle text-xl"></i> 
                                ยืนยันการมอบหมายและปิดงาน
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- Maintenance View --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm p-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-amber-50 mx-auto flex items-center justify-center text-amber-500 mb-4 shadow-inner">
                        <i class="fa-solid fa-wrench text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">ห้องพักอยู่ระหว่างการซ่อมแซม</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">ยังไม่เปิดให้มีการมอบหมายห้องพักในขณะนี้ หากดำเนินการเสร็จสิ้นแล้ว โปรดอัปเดตสถานะห้องกลับเป็น "ว่าง"</p>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
function submitRoomAssignment(roomId) {
    const requestId = document.getElementById('requestSelect').value;

    if (!requestId) {
        Swal.fire({
            icon: 'warning',
            title: 'คำแนะนำ',
            text: 'กรุณาเลือกผู้ส่งคำขอจากรายการก่อนครับ',
            confirmButtonColor: '#dc2626'
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการมอบหมาย?',
        text: "ระบบจะทำการมอบหมายห้องและแจ้งเตือนไปยังผู้ใช้งานทันที",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("housing.assign_room") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    room_id: roomId,
                    request_id: requestId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'มอบหมายห้องพักเรียบร้อยแล้ว',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('housing.houselist') }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: data.message || 'เกิดข้อผิดพลาดในการดำเนินการ'
                    });
                }
            });
        }
    });
}

$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        dropdownCssClass: 'text-sm'
    });
});
</script>
@endsection
