@extends('layouts.serviceitem.appservice')
@section('content')

<div class="max-w-[90rem] mx-auto px-4 py-8 space-y-8">

    <!-- Header Section with Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <!-- Main Title & Context -->
        <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                    <i class="fa-solid fa-boxes-stacked text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 leading-none">คลังอุปกรณ์</h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">จัดการและตรวจสอบพัสดุอุปกรณ์คงคลังในระบบทั้งหมด</p>
                </div>
            </div>
        </div>

        <!-- Stats 1: Total Items -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-layer-group text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase">อุปกรณ์ทั้งหมด</div>
                <div class="text-2xl font-bold text-slate-800">{{ number_format($items->count()) }} <span class="text-xs font-normal text-slate-400 ml-1">รายการ</span></div>
            </div>
        </div>

        <!-- Stats 2: Low Stock Indicator -->
        @php $lowStockCount = $items->where('quantity', '<=', 5)->count(); @endphp
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 {{ $lowStockCount > 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' }} rounded-full flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase">ของใกล้หมด</div>
                <div class="text-2xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-slate-800' }}">
                    {{ number_format($lowStockCount) }} <span class="text-xs font-normal text-slate-400 ml-1">รายการ</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Adding and Sorting Info -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex flex-col sm:flex-row items-center gap-6 w-full sm:w-auto">
            <div class="flex items-center gap-2">
                <span class="w-2 h-8 bg-red-600 rounded-full"></span>
                <h2 class="text-lg font-bold text-slate-700">รายชื่อพัสดุอุปกรณ์</h2>
            </div>
            
            <!-- Custom Filters -->
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div class="relative group w-full sm:w-64">
                    <i class="fa-solid fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <select id="stockFilter" class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all appearance-none cursor-pointer">
                        <option value="all">ทั้งหมด (ALL ITEMS)</option>
                        <option value="low">ใกล้หมด (LOW STOCK ≤ 5)</option>
                        <option value="out">ของหมด (OUT OF STOCK)</option>
                    </select>
                </div>
                
                <div class="relative group w-full sm:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs group-focus-within:text-red-500"></i>
                    <input type="text" id="customSearch" placeholder="ระบุชื่อพัสดุ หรือรหัส..." class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all shadow-sm">
                </div>
            </div>
        </div>

        <a href="{{ route('items.create') }}"
           class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-100 transition-all active:scale-95 group">
            <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
            <span>เพิ่มอุปกรณ์ใหม่</span>
        </a>
    </div>

    <!-- Content Area: Responsive Dual-View -->
    <div class="space-y-6">
        
        <!-- 1. Desktop View: Premium Table (Hidden on small screens) -->
        <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 overflow-x-auto">
                <table id="itemsTable" class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase rounded-l-2xl">รูปภาพ</th>
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase text-center">รหัสพัสดุ</th>
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase">ข้อมูลอุปกรณ์</th>
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase text-center">คลังคงเหลือ</th>
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase text-center">ราคาหน่วย</th>
                            <th class="px-6 py-4 text-[12px] font-bold text-slate-400 uppercase text-center rounded-r-2xl">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($items as $item)
                        <tr class="hover:bg-red-50/20 transition-all duration-200 group">
                            <!-- Image Column -->
                            <td class="px-6 py-4">
                                @if ($item->item_pic)
                                    <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-sm ring-2 ring-white group-hover:ring-red-100 transition-all">
                                        <img src="{{ asset('images/items/'.$item->item_pic) }}" 
                                             class="w-full h-full object-cover cursor-zoom-in"
                                             onclick="document.getElementById('img-{{ $item->item_id }}').showModal()">
                                    </div>
                                    <dialog id="img-{{ $item->item_id }}" class="modal">
                                        <div class="modal-box p-0 max-w-3xl rounded-3xl overflow-hidden bg-white">
                                            <div class="bg-slate-50 p-4 flex justify-between items-center border-b">
                                                <span class="font-bold text-slate-800">{{ $item->name }}</span>
                                                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
                                            </div>
                                            <div class="p-2"><img src="{{ asset('images/items/'.$item->item_pic) }}" class="w-full h-auto rounded-2xl"></div>
                                        </div>
                                        <form method="dialog" class="modal-backdrop bg-slate-900/80 backdrop-blur-sm"><button>close</button></form>
                                    </dialog>
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 border border-slate-100 border-dashed">
                                        <i class="fa-solid fa-box-open text-xl"></i>
                                    </div>
                                @endif
                            </td>

                            <!-- Code Column -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-[12px] font-mono font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">{{ $item->item_code }}</span>
                                    <span class="text-[10px] uppercase font-bold text-red-500">{{ $item->items_type ? $item->items_type->name : 'General' }}</span>
                                </div>
                            </td>

                            <!-- Name Column -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col max-w-[300px]">
                                    <span class="text-[15px] font-bold text-slate-800">{{ $item->name }}</span>
                                    <p class="text-[12px] text-slate-400 line-clamp-1 mt-0.5 leading-relaxed">{{ $item->description ?: '-' }}</p>
                                    @if(mb_strlen($item->description) > 50)
                                        <button onclick="document.getElementById('desc-{{ $item->item_id }}').showModal()" class="text-[10px] font-bold text-red-500 hover:underline text-left w-fit mt-1 uppercase tracking-tighter">Read More</button>
                                        <dialog id="desc-{{ $item->item_id }}" class="modal">
                                            <div class="modal-box rounded-3xl p-8">
                                                <h3 class="font-bold text-xl text-slate-800 mb-4 border-b pb-4">{{ $item->name }}</h3>
                                                <p class="text-slate-600 leading-relaxed">{{ $item->description }}</p>
                                                <div class="modal-action"><form method="dialog"><button class="btn btn-ghost rounded-xl">Close</button></form></div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop bg-slate-900/40"><button>close</button></form>
                                        </dialog>
                                    @endif
                                </div>
                            </td>

                            <!-- Stock Column -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-2xl font-bold {{ $item->quantity <= 5 ? 'text-red-500' : 'text-slate-800' }}">{{ number_format($item->quantity) }}</span>
                                        <span class="text-[11px] font-bold text-slate-300 uppercase">Qty</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full {{ $item->quantity > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} text-[10px] font-bold uppercase mt-1">
                                        <span class="w-1 h-1 rounded-full {{ $item->quantity > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                                        {{ $item->quantity > 0 ? 'IN STOCK' : 'OUT OF STOCK' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Price Column -->
                            <td class="px-6 py-4 text-center">
                                <span class="text-[15px] font-bold text-slate-600 tracking-tight">฿{{ number_format($item->per_unit, 2) }}</span>
                            </td>

                            <!-- Management Column -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Stock Buttons Group -->
                                    <div class="flex p-1 bg-slate-100 rounded-xl border border-slate-200 shadow-inner">
                                        <button onclick="document.getElementById('add-{{ $item->item_id }}').showModal()" class="w-8 h-8 flex items-center justify-center bg-white hover:bg-emerald-600 hover:text-white rounded-lg text-emerald-600 transition-all shadow-sm" title="เพิ่มสต็อก"><i class="fa-solid fa-plus text-xs"></i></button>
                                        <button onclick="document.getElementById('down-{{ $item->item_id }}').showModal()" class="w-8 h-8 flex items-center justify-center bg-white hover:bg-orange-600 hover:text-white rounded-lg text-orange-600 transition-all shadow-sm" title="ลดสต็อก"><i class="fa-solid fa-minus text-xs"></i></button>
                                    </div>

                                    <!-- Action Buttons Group -->
                                    <div class="flex gap-1.5 ml-2">
                                        <a href="{{ route('items.edit', $item->item_id) }}" class="w-9 h-9 flex items-center justify-center bg-slate-800 hover:bg-slate-900 text-white rounded-xl transition-all shadow-md shadow-slate-100" title="แก้ไข"><i class="fa-solid fa-pen-to-square text-xs"></i></a>
                                        <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('ยืนยันลบพัสดุ?')" class="w-9 h-9 flex items-center justify-center bg-white border border-red-100 hover:bg-red-600 hover:text-white rounded-xl text-red-500 transition-all shadow-sm" title="ลบ"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Desktop Modals -->
                                <dialog id="add-{{ $item->item_id }}" class="modal text-left">
                                    <div class="modal-box rounded-[2rem] p-0 overflow-hidden"><div class="bg-emerald-600 p-6 text-white font-bold text-xl flex items-center gap-3"><i class="fa-solid fa-circle-plus"></i> เพิ่มสต็อก: {{ $item->name }}</div><div class="p-8">@include('serviceshams.items.addstock', ['item' => $item])</div></div>
                                    <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
                                </dialog>
                                <dialog id="down-{{ $item->item_id }}" class="modal text-left">
                                    <div class="modal-box rounded-[2rem] p-0 overflow-hidden"><div class="bg-orange-600 p-6 text-white font-bold text-xl flex items-center gap-3"><i class="fa-solid fa-circle-minus"></i> ลดสต็อก: {{ $item->name }}</div><div class="p-8">@include('serviceshams.items.downstock', ['item' => $item])</div></div>
                                    <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
                                </dialog>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Mobile View: Card List (Hidden on desktop) -->
        <div class="lg:hidden grid grid-cols-1 gap-4" id="mobileList">
            @foreach($items as $item)
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 space-y-6 group item-card animate-fade-in" 
                 data-quantity="{{ $item->quantity }}" 
                 data-search="{{ strtolower($item->name . ' ' . $item->item_code) }}">
                <!-- Mobile Header -->
                <div class="flex items-start gap-4">
                    @if($item->item_pic)
                        <img src="{{ asset('images/items/'.$item->item_pic) }}" class="w-20 h-20 rounded-2xl object-cover shadow-md ring-2 ring-white">
                    @else
                        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-300 border border-dashed text-2xl"><i class="fa-solid fa-box-open"></i></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-0.5 rounded-lg border border-blue-100">{{ $item->item_code }}</span>
                            <span class="text-[10px] font-bold text-red-500 uppercase">{{ $item->items_type ? $item->items_type->name : 'General' }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 leading-tight mb-1">{{ $item->name }}</h3>
                        <p class="text-[11px] text-slate-400 line-clamp-2 leading-relaxed italic">{{ $item->description ?: 'ไม่มีคำอธิบาย' }}</p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-3 bg-red-50/20 p-4 rounded-2xl">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">สถานะสต็อก</span>
                        <span class="text-xl font-bold {{ $item->quantity <= 5 ? 'text-red-600' : 'text-emerald-600' }}">{{ number_format($item->quantity) }} <span class="text-[10px] font-normal text-slate-400">ชิ้น</span></span>
                    </div>
                    <div class="flex flex-col border-l border-red-100 pl-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">ราคา/หน่วย</span>
                        <span class="text-lg font-bold text-slate-700 font-mono">฿{{ number_format($item->per_unit, 0) }}</span>
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex items-center justify-between gap-4 pt-2">
                    <div class="flex gap-3">
                        <button onclick="document.getElementById('add-{{ $item->item_id }}-m').showModal()" class="w-12 h-12 flex items-center justify-center bg-emerald-500 text-white rounded-2xl shadow-lg shadow-emerald-100"><i class="fa-solid fa-plus text-lg"></i></button>
                        <button onclick="document.getElementById('down-{{ $item->item_id }}-m').showModal()" class="w-12 h-12 flex items-center justify-center bg-orange-500 text-white rounded-2xl shadow-lg shadow-orange-100"><i class="fa-solid fa-minus text-lg"></i></button>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('items.edit', $item->item_id) }}" class="px-6 h-12 flex items-center justify-center bg-slate-800 text-white font-bold rounded-2xl shadow-lg shadow-slate-100">แก้ไข</a>
                        <form action="{{ route('items.destroy', $item->item_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('ลบข้อมูล?')" class="w-12 h-12 flex items-center justify-center bg-white border-2 border-red-50 text-red-500 rounded-2xl shadow-sm"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Modals -->
            <dialog id="add-{{ $item->item_id }}-m" class="modal text-left">
                <div class="modal-box rounded-3xl p-0 overflow-hidden"><div class="bg-emerald-600 p-4 text-white font-bold">เพิ่มสต็อก: {{ $item->name }}</div><div class="p-6">@include('serviceshams.items.addstock', ['item' => $item])</div></div>
                <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
            </dialog>
            <dialog id="down-{{ $item->item_id }}-m" class="modal text-left">
                <div class="modal-box rounded-3xl p-0 overflow-hidden"><div class="bg-orange-600 p-4 text-white font-bold">ลดสต็อก: {{ $item->name }}</div><div class="p-6">@include('serviceshams.items.downstock', ['item' => $item])</div></div>
                <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
            </dialog>
            @endforeach
        </div>
    </div>
</div>

<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ',
        text: @json(session('success')),
        timer: 2500,
        showConfirmButton: false,
        background: '#fff',
        customClass: { popup: 'rounded-3xl shadow-2xl border border-red-50', title: 'text-2xl font-bold text-slate-800' }
    });
@endif
</script>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length { margin-bottom: 2rem !important; }
        .dataTables_wrapper .dataTables_length select { border-radius: 12px; padding: 4px 12px; border: 1px solid #f1f5f9; background-color: #f8fafc; font-weight: 600; }
        .dataTables_wrapper .dataTables_filter { display: none; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #dc2626 !important; border-color: #dc2626 !important; color: white !important; border-radius: 12px; font-weight: bold; }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #fee2e2 !important; border-color: transparent !important; color: #dc2626 !important; border-radius: 12px; }
        table.dataTable thead th { border-bottom: none !important; }
        .dataTables_wrapper .dataTables_info { font-weight: 700; color: #64748b !important; font-size: 13px; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemsTable = $('#itemsTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']],
                columnDefs: [ { orderable: false, targets: [0, 5] } ],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' }
            });

            // Custom Filter Logic
            function applyFilters() {
                const stockVal = $('#stockFilter').val();
                const searchVal = $('#customSearch').val().toLowerCase();

                // 1. Desktop Filtering
                // Custom Stock Filter for DataTables
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    const quantity = parseInt(data[3].replace(/,/g, '')) || 0; // Column 3 is Qty
                    
                    if (stockVal === 'low' && quantity > 5) return false;
                    if (stockVal === 'low' && quantity === 0) return false; // Usually Low Stock means > 0 but <= 5
                    if (stockVal === 'out' && quantity > 0) return false;
                    
                    return true;
                });
                
                itemsTable.search(searchVal).draw();
                $.fn.dataTable.ext.search.pop(); // Clear for next run

                // 2. Mobile Filtering
                $('.item-card').each(function() {
                    const qty = parseInt($(this).data('quantity')) || 0;
                    const searchData = $(this).data('search');
                    
                    let showByStock = true;
                    if (stockVal === 'low') showByStock = (qty > 0 && qty <= 5);
                    else if (stockVal === 'out') showByStock = (qty === 0);
                    
                    let showBySearch = searchData.includes(searchVal);
                    
                    if (showByStock && showBySearch) $(this).show();
                    else $(this).hide();
                });
            }

            $('#stockFilter, #customSearch').on('change keyup input', applyFilters);
        });
    </script>
@endpush