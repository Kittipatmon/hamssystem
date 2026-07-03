@extends('layouts.navmeeting.app')

@section('title', 'จัดการข้อมูลห้องประชุม')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Actions and Filters -->
    <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <!-- Live Search + Status Filter -->
            <div class="flex flex-1 w-full md:max-w-lg gap-2 items-center">
                <div class="relative flex-1 text-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input type="text" id="liveSearch" placeholder="ค้นหาชื่อห้องประชุม..." 
                        class="w-full bg-slate-50 border border-slate-200 rounded h-9 pl-9 pr-8 text-xs focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 outline-none transition-all">
                    <button id="clearSearch" style="display:none"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>

            <!-- Add Button -->
            <a href="{{ route('backend.bookingmeeting.rooms.create') }}" 
               class="bg-teal-600 hover:bg-teal-700 text-white font-bold h-9 rounded px-4 flex items-center justify-center text-xs shadow-sm transition-all gap-1.5 shrink-0">
                <i class="fa-solid fa-plus text-[10px]"></i> เพิ่มห้องประชุมใหม่
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="mt-4 flex flex-wrap gap-2 items-center text-xs font-semibold">
            @php $currentTab = request('tab', 'all'); @endphp
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'all'])) }}" 
               class="px-3 py-1.5 rounded border flex items-center gap-1.5 transition-all 
               {{ $currentTab == 'all' ? 'border-transparent bg-slate-100 text-slate-700 font-bold shadow-inner' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                <i class="fa-solid fa-list-ul"></i> ทั้งหมด
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'active'])) }}" 
               class="px-3 py-1.5 rounded border flex items-center gap-1.5 transition-all 
               {{ $currentTab == 'active' ? 'border-transparent bg-green-50 text-green-700 font-bold shadow-inner' : 'border-slate-200 text-green-600 hover:bg-green-50' }}">
               <i class="fa-solid fa-circle-check"></i> เปิดใช้งานได้
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'inactive'])) }}" 
               class="px-3 py-1.5 rounded border flex items-center gap-1.5 transition-all 
               {{ $currentTab == 'inactive' ? 'border-transparent bg-red-50 text-red-700 font-bold shadow-inner' : 'border-slate-200 text-red-600 hover:bg-red-50' }}">
               <i class="fa-solid fa-ban"></i> ปิดใช้งาน
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'large'])) }}" 
               class="px-3 py-1.5 rounded border flex items-center gap-1.5 transition-all 
               {{ $currentTab == 'large' ? 'border-transparent bg-cyan-50 text-cyan-700 font-bold shadow-inner' : 'border-slate-200 text-cyan-600 hover:bg-cyan-50' }}">
               <i class="fa-solid fa-users"></i> ความจุมากกว่า 10 คน
            </a>
        </div>
    </div>

    <!-- Data Table (Clinical Grid Layout) -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="bg-slate-50 px-5 py-4 border-b border-slate-200 flex items-center justify-between no-print">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-database text-teal-600 text-sm"></i>
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">
                    บัญชีฐานข้อมูลและสเปคของห้องประชุมส่วนกลาง
                </h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border-slate-200 text-xs">
                <thead>
                    <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                        <th class="py-3.5 px-3 border-r border-slate-200 text-center w-12">#</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 min-w-[150px]">ข้อมูลห้องประชุม</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 text-center w-24">ความจุ (คน)</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 min-w-[150px]">สถานที่ตั้ง</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 text-center w-24">ชั้นที่ตั้ง</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 text-center w-32">สถานะสิทธิ์จอง</th>
                        <th class="py-3.5 px-3 border-r border-slate-200 text-center w-24">ภาพห้อง</th>
                        <th class="py-3.5 px-3 text-center w-32 no-print">จัดการข้อมูล</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody" class="divide-y divide-slate-200">
                    @forelse($rooms as $index => $room)
                    <tr class="hover:bg-slate-50/70 transition-colors room-row" data-name="{{ strtolower($room->room_name) }}" data-type="{{ strtolower($room->room_type ?? '') }}" data-status="{{ $room->status }}">
                        <!-- Row number -->
                        <td class="py-3.5 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                            {{ method_exists($rooms, 'firstItem') ? $rooms->firstItem() + $index : $index + 1 }}
                        </td>

                        <!-- Room Name & Type -->
                        <td class="py-3.5 px-3 border-r border-slate-200 font-bold text-slate-800">
                            <div class="uppercase leading-tight">{{ $room->room_name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $room->room_type ?? 'ห้องประชุมประเภททั่วไป' }}</div>
                        </td>

                        <!-- Capacity -->
                        <td class="py-3.5 px-3 border-r border-slate-200 text-center font-semibold">
                            <div class="inline-block bg-cyan-50 text-cyan-700 border border-cyan-200 px-2 py-0.5 rounded text-[10px]">
                                {{ $room->capacity }} คน
                            </div>
                        </td>

                        <!-- Location -->
                        <td class="py-3.5 px-3 border-r border-slate-200 font-semibold text-slate-600">
                            <i class="fa-solid fa-location-dot text-red-500/70 mr-1"></i> {{ $room->location ?? 'สำนักงานใหญ่' }}
                        </td>

                        <!-- Floor -->
                        <td class="py-3.5 px-3 border-r border-slate-200 text-center font-bold text-slate-600">
                            @if($room->floor)
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px]">ชั้น {{ $room->floor }}</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-3 border-r border-slate-200 text-center font-bold">
                            @if($room->status == 1)
                                <span class="bg-green-50 text-green-700 border border-green-200 px-2.5 py-0.5 rounded text-[10px] flex items-center w-fit mx-auto gap-1 shadow-sm font-semibold">
                                    <i class="fa-solid fa-circle-check"></i> พร้อมใช้งาน
                                </span>
                            @else
                                <span class="bg-red-50 text-red-700 border border-red-200 px-2.5 py-0.5 rounded text-[10px] flex items-center w-fit mx-auto gap-1 shadow-sm font-semibold">
                                    <i class="fa-solid fa-ban"></i> ปิดใช้งาน
                                </span>
                            @endif
                        </td>

                        <!-- Image -->
                        <td class="py-3.5 px-3 border-r border-slate-200 text-center">
                            @php
                                $images = is_string($room->images) ? json_decode($room->images, true) : $room->images;
                                $firstImage = !empty($images) && is_array($images) ? $images[0] : null;
                                $imagePathUrl = null;
                                if ($firstImage) {
                                    if (file_exists(public_path('images/room/' . $firstImage))) {
                                        $imagePathUrl = asset('images/room/' . $firstImage);
                                    } elseif (file_exists(public_path('images/' . $firstImage))) {
                                        $imagePathUrl = asset('images/' . $firstImage);
                                    } elseif (file_exists(public_path($firstImage))) {
                                        $imagePathUrl = asset($firstImage);
                                    }
                                }
                            @endphp
                            
                            @if($imagePathUrl)
                                <div class="w-10 h-7 rounded border border-slate-200 mx-auto overflow-hidden bg-slate-100 shadow-sm">
                                    <img src="{{ $imagePathUrl }}" alt="Room Image" class="w-full h-full object-cover">
                                </div>
                            @else
                                <span class="text-slate-300 font-bold">-</span>
                            @endif
                        </td>

                        <!-- Action buttons -->
                        <td class="py-3.5 px-3 text-center no-print">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('backend.bookingmeeting.rooms.edit', $room->room_id) }}" 
                                   class="w-7 h-7 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 flex items-center justify-center transition-colors" title="แก้ไขข้อมูลห้อง">
                                    <i class="fa-regular fa-pen-to-square text-[11px]"></i>
                                </a>
                                <form action="{{ route('backend.bookingmeeting.rooms.destroy', $room->room_id) }}" method="POST" class="m-0 p-0 delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                       class="w-7 h-7 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 flex items-center justify-center transition-colors btn-delete cursor-pointer" 
                                       data-name="{{ $room->room_name }}"
                                       title="ลบข้อมูลห้อง">
                                        <i class="fa-regular fa-trash-can text-[11px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400 font-semibold">
                            <i class="fa-solid fa-door-closed text-3xl mb-2 text-slate-300 block"></i>
                            ไม่พบข้อมูลห้องประชุมในระบบ
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- No results from live search -->
        <div id="noSearchResults" style="display:none" class="p-8 text-center text-slate-400 font-semibold text-xs border-t border-slate-200">
            <i class="fa-solid fa-magnifying-glass text-2xl mb-2 text-slate-300 block"></i>
            ไม่พบห้องประชุมที่ตรงกับคำค้นหา
        </div>

        <!-- Pagination / Total count -->
        @if(method_exists($rooms, 'hasPages') && $rooms->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between font-semibold text-slate-500 text-[11px]">
            <div>
                แสดง {{ $rooms->firstItem() }} ถึง {{ $rooms->lastItem() }} จากทั้งหมด {{ $rooms->total() }} รายการ
            </div>
            {{ $rooms->links() }}
        </div>
        @elseif(!method_exists($rooms, 'hasPages'))
        <div class="p-3 border-t border-slate-200 bg-slate-50 text-slate-500 text-[11px] font-bold">
            <i class="fa-solid fa-list text-slate-400 mr-1"></i> แสดงทั้งหมด <span id="visibleCount">{{ $rooms->count() }}</span> รายการห้องประชุม
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Live Search ===
    var searchInput = document.getElementById('liveSearch');
    var clearBtn = document.getElementById('clearSearch');
    var noResults = document.getElementById('noSearchResults');
    var countEl = document.getElementById('visibleCount');

    function filterTable() {
        var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var rows = document.querySelectorAll('.room-row');
        var count = 0;

        rows.forEach(function(row) {
            var name = row.getAttribute('data-name') || '';
            var type = row.getAttribute('data-type') || '';
            var match = query === '' || name.indexOf(query) !== -1 || type.indexOf(query) !== -1;
            row.style.display = match ? '' : 'none';
            if (match) count++;
        });

        if (clearBtn) clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        if (noResults) noResults.style.display = (count === 0 && query.length > 0) ? 'block' : 'none';
        if (countEl) countEl.textContent = count;
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTable);

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                searchInput.value = '';
                filterTable();
                searchInput.focus();
            });
        }
    }

    // === SweetAlert2 Delete Confirmation ===
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var form = this.closest('.delete-form');
            var roomName = this.getAttribute('data-name');

            Swal.fire({
                title: '<span class="text-lg font-black text-slate-800">ยืนยันการลบข้อมูลห้องประชุม?</span>',
                html: '<p class="text-slate-500 text-xs font-semibold">คุณกำลังจะลบห้องประชุม <span class="text-red-600 font-bold">"' + roomName + '"</span> ออกจากระบบอย่างถาวร</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'ยืนยันลบข้อมูล',
                cancelButtonText: 'ยกเลิก',
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-2xl border-0 shadow-2xl',
                    confirmButton: 'rounded px-6 py-2.5 text-xs font-bold',
                    cancelButton: 'rounded px-6 py-2.5 text-xs font-bold text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
