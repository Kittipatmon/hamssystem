@extends('layouts.navmeeting.app')

@section('title', 'จัดการข้อมูลห้องประชุม')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Actions and Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <!-- Live Search + Status Filter -->
            <div class="flex flex-1 w-full md:max-w-lg gap-2 items-center">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text" id="liveSearch" placeholder="ค้นหาชื่อห้อง..." 
                        class="input input-bordered w-full pl-10 pr-10 h-10 text-sm focus:border-teal-500 hover:border-teal-300 transition-colors rounded-lg">
                    <button id="clearSearch" style="display:none"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-red-500 transition-colors cursor-pointer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <!-- Search Status Filter -->
                <!-- <div class="relative" id="searchStatusDropdown">
                    <div id="searchStatusMenu" style="display:none"
                        class="absolute left-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                        <button type="button" data-filter="all" class="search-status-item w-full flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-slate-50 transition-colors text-teal-600 font-semibold">
                            <i class="fa-solid fa-layer-group w-4 text-center text-slate-400"></i> ทุกสถานะ
                        </button>
                        <button type="button" data-filter="1" class="search-status-item w-full flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-green-50 transition-colors text-slate-600">
                            <i class="fa-solid fa-circle-check w-4 text-center text-green-500"></i> ใช้งานได้
                        </button>
                        <button type="button" data-filter="0" class="search-status-item w-full flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-red-50 transition-colors text-slate-600">
                            <i class="fa-solid fa-ban w-4 text-center text-red-500"></i> ปิดใช้งาน
                        </button>
                    </div>
                </div> -->
            </div>

            <!-- Add Button -->
            <a href="{{ route('backend.bookingmeeting.rooms.create') }}" 
               class="btn bg-teal-600 hover:bg-teal-700 text-white border-0 shadow-md shadow-teal-200 shrink-0">
                <i class="fa-solid fa-plus mr-1 text-sm"></i> เพิ่มห้องประชุมใหม่
            </a>
        </div>

        <!-- Filter Tabs -->
        <div class="mt-4 flex flex-wrap gap-2 items-center">
            @php $currentTab = request('tab', 'all'); @endphp
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'all'])) }}" 
               class="px-4 py-1.5 text-sm rounded-lg border flex items-center gap-2 transition-all 
               {{ $currentTab == 'all' ? 'border-none bg-slate-100 text-slate-700 font-semibold shadow-inner' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                <i class="fa-solid fa-list-ul"></i> ทั้งหมด
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'active'])) }}" 
               class="px-4 py-1.5 text-sm rounded-lg border flex items-center gap-2 transition-all 
               {{ $currentTab == 'active' ? 'border-none bg-green-50 text-green-700 font-semibold shadow-inner' : 'border-slate-200 text-green-600 hover:bg-green-50' }}">
               <i class="fa-solid fa-circle-check"></i> ใช้งานได้
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'inactive'])) }}" 
               class="px-4 py-1.5 text-sm rounded-lg border flex items-center gap-2 transition-all 
               {{ $currentTab == 'inactive' ? 'border-none bg-red-50 text-red-700 font-semibold shadow-inner' : 'border-slate-200 text-red-600 hover:bg-red-50' }}">
               <i class="fa-solid fa-ban"></i> ปิดใช้งาน
            </a>
            <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['tab' => 'large'])) }}" 
               class="px-4 py-1.5 text-sm rounded-lg border flex items-center gap-2 transition-all 
               {{ $currentTab == 'large' ? 'border-none bg-cyan-50 text-cyan-700 font-semibold shadow-inner' : 'border-slate-200 text-cyan-600 hover:bg-cyan-50' }}">
               <i class="fa-solid fa-users"></i> ความจุมากกว่า 10
            </a>

            <!-- Styled Status Dropdown -->
            <!-- <div class="relative ml-auto" id="statusDropdown">
                <button id="statusToggle"
                    class="px-4 py-1.5 text-sm rounded-lg border flex items-center gap-2 transition-all cursor-pointer
                    {{ request('status') == '1' ? 'bg-green-50 text-green-700 border-green-200 font-semibold' : (request('status') == '0' ? 'bg-red-50 text-red-700 border-red-200 font-semibold' : 'border-slate-200 text-slate-600 hover:bg-slate-50') }}">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>
                        {{ request('status') == '1' ? 'ใช้งานได้' : (request('status') == '0' ? 'ปิดใช้งาน' : 'ทุกสถานะ') }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200" id="statusArrow"></i>
                </button>
                <div id="statusMenu" style="display:none"
                    class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                    <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->except('status'), ['tab' => request('tab', 'all')])) }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-slate-50 transition-colors {{ !request('status') || request('status') == 'all' ? 'text-teal-600 font-semibold bg-teal-50/50' : 'text-slate-600' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center text-slate-400"></i> ทุกสถานะ
                    </a>
                    <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['status' => '1'])) }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-green-50 transition-colors {{ request('status') == '1' ? 'text-green-600 font-semibold bg-green-50/50' : 'text-slate-600' }}">
                        <i class="fa-solid fa-circle-check w-4 text-center text-green-500"></i> ใช้งานได้
                    </a>
                    <a href="{{ route('backend.bookingmeeting.rooms.index', array_merge(request()->query(), ['status' => '0'])) }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm hover:bg-red-50 transition-colors {{ request('status') == '0' ? 'text-red-600 font-semibold bg-red-50/50' : 'text-slate-600' }}">
                        <i class="fa-solid fa-ban w-4 text-center text-red-500"></i> ปิดใช้งาน
                    </a>
                </div>
            </div> -->
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-sm">
                <thead class="bg-[#1E2B3C] text-white">
                    <tr>
                        <th class="w-16 text-center rounded-tl-xl font-medium tracking-wide">#</th>
                        <th class="font-medium tracking-wide">ชื่อห้อง</th>
                        <th class="text-center font-medium tracking-wide">ความจุ</th>
                        <th class="text-center font-medium tracking-wide">สถานที่</th>
                        <th class="text-center font-medium tracking-wide">ชั้น</th>
                        <th class="text-center font-medium tracking-wide">สถานะ</th>
                        <th class="text-center font-medium tracking-wide">รูปภาพ</th>
                        <th class="text-center rounded-tr-xl font-medium tracking-wide w-32">การจัดการ</th>
                    </tr>
                </thead>
                <tbody id="roomsTableBody">
                    @forelse($rooms as $index => $room)
                    <tr class="hover whitespace-nowrap lg:whitespace-normal room-row" data-name="{{ strtolower($room->room_name) }}" data-type="{{ strtolower($room->room_type ?? '') }}" data-status="{{ $room->status }}">
                        <td class="text-center font-medium text-slate-500">{{ method_exists($rooms, 'firstItem') ? $rooms->firstItem() + $index : $index + 1 }}</td>
                        <td>
                            <div class="font-bold text-slate-800 uppercase">{{ $room->room_name }}</div>
                            <div class="text-[11px] text-slate-500">{{ $room->room_type ?? 'ห้องประชุมประเภททั่วไป' }}</div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-1 mx-auto bg-cyan-50 text-cyan-600 px-2 py-0.5 rounded-full w-fit">
                                <i class="fa-solid fa-user-group text-[10px]"></i> <span class="font-semibold">{{ $room->capacity }} คน</span>
                            </div>
                        </td>
                        <td class="text-center text-slate-600">
                            <i class="fa-solid fa-location-dot text-red-500 mr-1"></i> {{ $room->location ?? 'สำนักงานใหญ่' }}
                        </td>
                        <td class="text-center">
                            @if($room->floor)
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs">ชั้น {{ $room->floor }}</span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($room->status == 1)
                                <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs flex items-center w-fit mx-auto gap-1 shadow-sm">
                                    <i class="fa-solid fa-circle-check"></i> ใช้งานได้
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs flex items-center w-fit mx-auto gap-1 shadow-sm">
                                    <i class="fa-solid fa-ban"></i> ปิดใช้งาน
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
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
                                <div class="w-12 h-8 rounded border border-slate-200 mx-auto overflow-hidden bg-slate-100 shadow-sm">
                                    <img src="{{ $imagePathUrl }}" alt="Room Image" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="text-slate-400 text-xs">
                                    <i class="fa-regular fa-image text-lg"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-center gap-2">
                                <a href="#" onclick="Swal.fire({ 
                                    title: '<span class=\'font-black\'>กำลังจัดทำ...</span>', 
                                    html: '<p class=\'text-slate-500\'>ระบบสถิติการใช้ห้องประชุม อยู่ระหว่างการพัฒนาครับ</p>', 
                                    icon: 'info', 
                                    confirmButtonColor: '#0891b2', 
                                    padding: '2rem', 
                                    borderRadius: '2.5rem',
                                    customClass: { popup: 'rounded-[2.5rem] border-0 shadow-2xl' }
                                })" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center border border-cyan-200 text-cyan-500 hover:bg-cyan-50 transition-colors" title="ดูข้อมูล">
                                    <i class="fa-regular fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('backend.bookingmeeting.rooms.edit', $room->room_id) }}" 
                                   class="w-8 h-8 rounded-lg flex items-center justify-center border border-amber-200 text-amber-500 hover:bg-amber-50 transition-colors" title="แก้ไข">
                                    <i class="fa-regular fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('backend.bookingmeeting.rooms.destroy', $room->room_id) }}" method="POST" class="m-0 p-0 delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                       class="w-8 h-8 rounded-lg flex items-center justify-center border border-red-200 text-red-500 hover:bg-red-50 transition-colors btn-delete" 
                                       data-name="{{ $room->room_name }}"
                                       title="ลบข้อมูล">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-10">
                            <div class="text-slate-400 flex flex-col items-center">
                                <i class="fa-solid fa-door-closed text-4xl mb-3 text-slate-300"></i>
                                <p class="text-base font-medium">ไม่พบข้อมูลห้องประชุม</p>
                                <p class="text-sm mt-1">ลองเปลี่ยนเงื่อนไขการค้นหา หรือเพิ่มห้องใหม่</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- No results from live search -->
        <div id="noSearchResults" style="display:none" class="p-8 text-center text-slate-400">
            <i class="fa-solid fa-magnifying-glass text-3xl mb-3 text-slate-300"></i>
            <p class="text-base font-medium">ไม่พบห้องที่ตรงกับคำค้นหา</p>
        </div>

        <!-- Pagination -->
        @if(method_exists($rooms, 'hasPages') && $rooms->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
            <div class="text-sm text-slate-500">
                Showing {{ $rooms->firstItem() }} to {{ $rooms->lastItem() }} of {{ $rooms->total() }} results
            </div>
            {{ $rooms->links() }}
        </div>
        @elseif(!method_exists($rooms, 'hasPages'))
        <div class="p-4 border-t border-slate-200 bg-slate-50 text-sm text-slate-500">
            <i class="fa-solid fa-list text-slate-400 mr-1"></i> แสดงทั้งหมด <span id="visibleCount">{{ $rooms->count() }}</span> รายการ
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // === Status Dropdown ===
    var toggle = document.getElementById('statusToggle');
    var menu = document.getElementById('statusMenu');
    var arrow = document.getElementById('statusArrow');
    var dropdown = document.getElementById('statusDropdown');

    if (toggle && menu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = menu.style.display !== 'none';
            menu.style.display = isOpen ? 'none' : 'block';
            if (arrow) arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
        });

        document.addEventListener('click', function(e) {
            if (dropdown && !dropdown.contains(e.target)) {
                menu.style.display = 'none';
                if (arrow) arrow.style.transform = '';
            }
        });
    }

    // === Live Search ===
    var searchInput = document.getElementById('liveSearch');
    var clearBtn = document.getElementById('clearSearch');
    var noResults = document.getElementById('noSearchResults');
    var countEl = document.getElementById('visibleCount');
    var currentSearchStatus = 'all';

    // Search Status Filter Dropdown
    var ssToggle = document.getElementById('searchStatusToggle');
    var ssMenu = document.getElementById('searchStatusMenu');
    var ssArrow = document.getElementById('searchStatusArrow');
    var ssLabel = document.getElementById('searchStatusLabel');
    var ssDropdown = document.getElementById('searchStatusDropdown');

    if (ssToggle && ssMenu) {
        ssToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = ssMenu.style.display !== 'none';
            ssMenu.style.display = isOpen ? 'none' : 'block';
            if (ssArrow) ssArrow.style.transform = isOpen ? '' : 'rotate(180deg)';
        });

        document.addEventListener('click', function(e) {
            if (ssDropdown && !ssDropdown.contains(e.target)) {
                ssMenu.style.display = 'none';
                if (ssArrow) ssArrow.style.transform = '';
            }
        });

        document.querySelectorAll('.search-status-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                currentSearchStatus = this.getAttribute('data-filter');
                // Update label & style
                var labels = { 'all': 'ทุกสถานะ', '1': 'ใช้งานได้', '0': 'ปิดใช้งาน' };
                var colors = { 'all': '', '1': 'text-green-600 font-semibold', '0': 'text-red-600 font-semibold' };
                if (ssLabel) ssLabel.textContent = labels[currentSearchStatus] || 'ทุกสถานะ';
                ssToggle.className = ssToggle.className.replace(/text-green-600|text-red-600|font-semibold|bg-green-50|bg-red-50/g, '').trim();
                if (currentSearchStatus === '1') ssToggle.classList.add('text-green-600', 'font-semibold', 'bg-green-50');
                else if (currentSearchStatus === '0') ssToggle.classList.add('text-red-600', 'font-semibold', 'bg-red-50');
                // Update active state in menu
                document.querySelectorAll('.search-status-item').forEach(function(b) {
                    b.classList.remove('font-semibold', 'text-teal-600', 'text-green-600', 'text-red-600', 'bg-teal-50/50', 'bg-green-50/50', 'bg-red-50/50');
                    b.classList.add('text-slate-600');
                });
                this.classList.remove('text-slate-600');
                if (currentSearchStatus === 'all') this.classList.add('font-semibold', 'text-teal-600');
                else if (currentSearchStatus === '1') this.classList.add('font-semibold', 'text-green-600');
                else if (currentSearchStatus === '0') this.classList.add('font-semibold', 'text-red-600');
                // Close menu & re-filter
                ssMenu.style.display = 'none';
                if (ssArrow) ssArrow.style.transform = '';
                filterTable();
            });
        });
    }

    function filterTable() {
        var query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var rows = document.querySelectorAll('.room-row');
        var count = 0;

        rows.forEach(function(row) {
            var name = row.getAttribute('data-name') || '';
            var type = row.getAttribute('data-type') || '';
            var status = row.getAttribute('data-status') || '';
            var nameMatch = query === '' || name.indexOf(query) !== -1 || type.indexOf(query) !== -1;
            var statusMatch = currentSearchStatus === 'all' || status === currentSearchStatus;
            var match = nameMatch && statusMatch;
            row.style.display = match ? '' : 'none';
            if (match) count++;
        });

        if (clearBtn) clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        if (noResults) noResults.style.display = (count === 0 && (query.length > 0 || currentSearchStatus !== 'all')) ? 'block' : 'none';
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
                title: '<span class="text-xl font-black text-slate-800">ยืนยันการลบข้อมูล?</span>',
                html: '<p class="text-slate-500 font-medium">คุณกำลังจะลบห้องประชุม <span class="text-red-600 font-bold">"' + roomName + '"</span><br>การดำเนินการนี้ไม่สามารถย้อนกลับได้</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#f1f5f9',
                confirmButtonText: 'ยืนยันการลบ',
                cancelButtonText: '<span class="text-slate-500">ยกเลิก</span>',
                padding: '2rem',
                borderRadius: '2.5rem',
                customClass: {
                    popup: 'rounded-[2.5rem] border-0 shadow-2xl',
                    confirmButton: 'rounded-xl px-10 py-3 font-bold',
                    cancelButton: 'rounded-xl px-10 py-3 font-bold'
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
