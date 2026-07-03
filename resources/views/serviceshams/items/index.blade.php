@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="md:col-span-2 flex flex-col justify-center bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-boxes-stacked text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">จัดการคลังอุปกรณ์พัสดุ</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">บริหารจัดการพัสดุอุปกรณ์คงคลัง แก้ไข และปรับปรุงข้อมูลสต็อก</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Total Items -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">พัสดุอุปกรณ์ทั้งหมด</div>
                    <div class="text-lg font-black text-slate-800 mt-0.5">
                        {{ number_format($items->count()) }} <span class="text-xs font-normal text-slate-400">รายการ</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Low Stock Indicator -->
            @php $lowStockCount = $items->where('quantity', '<=', 5)->count(); @endphp
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 {{ $lowStockCount > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }} rounded flex items-center justify-center">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">พัสดุสต็อกต่ำ (≤ 5)</div>
                    <div class="text-lg font-black {{ $lowStockCount > 0 ? 'text-red-600' : 'text-slate-800' }} mt-0.5">
                        {{ number_format($lowStockCount) }} <span class="text-xs font-normal text-slate-400">รายการ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar: Adding and Sorting Info -->
        <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="flex items-center gap-2 mr-2">
                    <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                    <h2 class="font-bold text-slate-700">รายชื่อทะเบียนพัสดุ</h2>
                </div>

                <!-- Custom Filters -->
                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-48">
                        <select id="stockFilter"
                            class="w-full pl-3 pr-8 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all appearance-none cursor-pointer">
                            <option value="all">-- ทุกระดับสต็อก --</option>
                            <option value="low">ใกล้หมด (≤ 5 ชิ้น)</option>
                            <option value="out">ของหมด (0 ชิ้น)</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-60">
                        <input type="text" id="customSearch" placeholder="ค้นหาชื่อพัสดุ หรือรหัส..."
                            class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs font-bold focus:bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row w-full sm:w-auto items-center gap-2">
                <a href="{{ route('items.export') }}"
                    class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded shadow transition-all">
                    <i class="fa-solid fa-file-excel text-[10px]"></i>
                    <span>ส่งออก Excel</span>
                </a>
                <a href="{{ route('items.create') }}"
                    class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-all">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>เพิ่มอุปกรณ์พัสดุใหม่</span>
                </a>
            </div>
        </div>

        <!-- Master Registry Data Table (Hospital Grid Layout) -->
        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 overflow-x-auto">
                <table id="itemsTable" class="w-full text-left border-collapse border-slate-200 text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-16">รูปภาพ</th>
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-28">รหัสพัสดุ / ประเภท</th>
                            <th class="py-3 px-3 border-r border-slate-200 min-w-[250px]">ชื่อและคำอธิบายอุปกรณ์</th>
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-32">จำนวนคงคลัง</th>
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-28">ราคาต่อหน่วย</th>
                            <th class="py-3 px-3 text-center w-36">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($items as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Image Column -->
                                <td class="py-3 px-3 border-r border-slate-200 text-center">
                                    @if ($item->item_pic)
                                        <div class="w-10 h-10 rounded border border-slate-250 mx-auto overflow-hidden bg-slate-50 shadow-sm">
                                            <img src="{{ asset('images/items/' . $item->item_pic) }}"
                                                class="w-full h-full object-cover cursor-zoom-in"
                                                onclick="document.getElementById('img-{{ $item->item_id }}').showModal()">
                                        </div>
                                        <dialog id="img-{{ $item->item_id }}" class="modal text-left">
                                            <div class="modal-box p-0 max-w-xl rounded-lg overflow-hidden bg-white">
                                                <div class="bg-slate-50 p-4 flex justify-between items-center border-b border-slate-200">
                                                    <span class="font-bold text-slate-800 text-xs uppercase">{{ $item->name }}</span>
                                                    <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
                                                </div>
                                                <div class="p-2"><img src="{{ asset('images/items/' . $item->item_pic) }}" class="w-full h-auto rounded"></div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop bg-slate-900/80 backdrop-blur-sm"><button>close</button></form>
                                        </dialog>
                                    @else
                                        <div class="w-10 h-10 rounded border border-slate-200 border-dashed mx-auto flex items-center justify-center text-slate-300">
                                            <i class="fa-solid fa-box-open"></i>
                                        </div>
                                    @endif
                                </td>

                                <!-- Code Column -->
                                <td class="py-3 px-3 border-r border-slate-200 font-semibold text-center leading-normal">
                                    <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded border border-slate-250">{{ $item->item_code }}</span>
                                    <div class="text-[9px] uppercase font-bold text-red-600 mt-1.5">{{ $item->items_type ? $item->items_type->name : 'General' }}</div>
                                </td>

                                <!-- Name & Description Column -->
                                <td class="py-3 px-3 border-r border-slate-200 leading-normal">
                                    <div class="font-bold text-slate-800">{{ $item->name }}</div>
                                    <div class="text-[10px] text-slate-400 mt-1 font-semibold line-clamp-1" title="{{ $item->description }}">{{ $item->description ?: '-' }}</div>
                                    @if(mb_strlen($item->description) > 50)
                                        <button onclick="document.getElementById('desc-{{ $item->item_id }}').showModal()"
                                            class="text-[9px] font-bold text-red-600 hover:underline mt-1 uppercase">ดูคำอธิบายเต็ม</button>
                                        <dialog id="desc-{{ $item->item_id }}" class="modal text-left">
                                            <div class="modal-box rounded-lg p-5">
                                                <h3 class="font-bold text-xs text-slate-800 uppercase mb-3 border-b border-slate-250 pb-2">
                                                    {{ $item->name }}
                                                </h3>
                                                <p class="text-slate-600 leading-relaxed text-xs">{{ $item->description }}</p>
                                                <div class="modal-action">
                                                    <form method="dialog"><button class="btn btn-sm">ปิด</button></form>
                                                </div>
                                            </div>
                                            <form method="dialog" class="modal-backdrop bg-slate-900/40"><button>close</button></form>
                                        </dialog>
                                    @endif
                                </td>

                                <!-- Stock Column -->
                                <td class="py-3 px-3 border-r border-slate-200 text-center font-bold">
                                    <div class="flex flex-col items-center">
                                        <span class="text-sm {{ $item->quantity <= 5 ? 'text-red-600' : 'text-slate-800' }}">{{ number_format($item->quantity) }}</span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $item->quantity > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-[9px] font-bold mt-1">
                                            {{ $item->quantity > 0 ? 'มีพัสดุ' : 'พัสดุหมด' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Price Column -->
                                <td class="py-3 px-3 border-r border-slate-200 text-center font-bold text-slate-700">
                                    ฿{{ number_format($item->per_unit, 2) }}
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="flex bg-slate-100 rounded border border-slate-200 p-0.5">
                                            <button onclick="document.getElementById('add-{{ $item->item_id }}').showModal()"
                                                class="w-6 h-6 flex items-center justify-center bg-white hover:bg-emerald-600 hover:text-white rounded text-emerald-600 transition-colors shadow-sm"
                                                title="เพิ่มสต็อก"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                            <div class="w-px h-3 bg-slate-250 my-auto mx-0.5"></div>
                                            <button onclick="document.getElementById('down-{{ $item->item_id }}').showModal()"
                                                class="w-6 h-6 flex items-center justify-center bg-white hover:bg-orange-600 hover:text-white rounded text-orange-600 transition-colors shadow-sm"
                                                title="ลดสต็อก"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                        </div>

                                        <a href="{{ route('items.edit', $item->item_id) }}"
                                            class="w-6.5 h-6.5 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 flex items-center justify-center transition-colors"
                                            title="แก้ไขข้อมูลพัสดุ"><i class="fa-regular fa-pen-to-square text-[10px]"></i></a>
                                        
                                        <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" class="inline m-0 p-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('ยืนยันลบพัสดุรายการนี้ออกจากระบบ?')"
                                                class="w-6.5 h-6.5 rounded border border-slate-200 bg-slate-50 text-red-500 hover:bg-red-50 hover:border-red-200 flex items-center justify-center transition-colors"
                                                title="ลบ"><i class="fa-regular fa-trash-can text-[10px]"></i></button>
                                        </form>
                                    </div>

                                    <!-- Modals -->
                                    <dialog id="add-{{ $item->item_id }}" class="modal text-left">
                                        <div class="modal-box rounded-lg p-0 overflow-hidden max-w-md">
                                            <div class="bg-emerald-600 p-4 text-white font-bold text-xs uppercase flex items-center gap-2">
                                                <i class="fa-solid fa-circle-plus"></i> เพิ่มจำนวนสต็อก: {{ $item->name }}
                                            </div>
                                            <div class="p-5">@include('serviceshams.items.addstock', ['item' => $item])</div>
                                        </div>
                                        <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
                                    </dialog>
                                    
                                    <dialog id="down-{{ $item->item_id }}" class="modal text-left">
                                        <div class="modal-box rounded-lg p-0 overflow-hidden max-w-md">
                                            <div class="bg-orange-600 p-4 text-white font-bold text-xs uppercase flex items-center gap-2">
                                                <i class="fa-solid fa-circle-minus"></i> ปรับลดจำนวนสต็อก: {{ $item->name }}
                                            </div>
                                            <div class="p-5">@include('serviceshams.items.downstock', ['item' => $item])</div>
                                        </div>
                                        <form method="dialog" class="modal-backdrop bg-slate-900/60"><button>close</button></form>
                                    </dialog>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false,
                background: '#fff',
                customClass: { popup: 'rounded-xl shadow-2xl border border-slate-200', title: 'text-lg font-black text-slate-800' }
            });
        @endif
    </script>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem !important;
            font-size: 11px;
            font-weight: bold;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 4px;
            padding: 2px 8px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #c31919 !important;
            border-color: #c31919 !important;
            color: white !important;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #fef2f2 !important;
            border-color: transparent !important;
            color: #c31919 !important;
            border-radius: 4px;
        }

        table.dataTable thead th {
            border-bottom: 1px solid #cbd5e1 !important;
        }

        .dataTables_wrapper .dataTables_info {
            font-weight: 700;
            color: #64748b !important;
            font-size: 11px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const itemsTable = $('#itemsTable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 5] }],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/th.json' }
            });

            // Custom Filter Logic
            function applyFilters() {
                const stockVal = $('#stockFilter').val();
                const searchVal = $('#customSearch').val().toLowerCase();

                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    const quantity = parseInt(data[3].replace(/,/g, '')) || 0;

                    if (stockVal === 'low' && quantity > 5) return false;
                    if (stockVal === 'low' && quantity === 0) return false;
                    if (stockVal === 'out' && quantity > 0) return false;

                    return true;
                });

                itemsTable.search(searchVal).draw();
                $.fn.dataTable.ext.search.pop();
            }

            $('#stockFilter, #customSearch').on('change keyup input', applyFilters);
        });
    </script>
@endpush