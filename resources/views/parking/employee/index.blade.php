@extends('layouts.parking.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<style>
    /* ============ DataTable Custom Overrides ============ */
    #employeesTable_wrapper .dataTables_length label,
    #employeesTable_wrapper .dataTables_filter label {
        font-size: 0.82rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    #employeesTable_wrapper .dataTables_length select,
    #employeesTable_wrapper .dataTables_filter input {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.625rem;
        padding: 0.35rem 0.75rem;
        font-size: 0.82rem;
        color: #334155;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        background-color: #f8fafc;
        font-family: 'Prompt', sans-serif;
    }

    #employeesTable_wrapper .dataTables_filter input:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,.12);
    }

    #employeesTable_wrapper .dataTables_info {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
        padding-top: 0.75rem;
    }

    #employeesTable_wrapper .dataTables_paginate {
        padding-top: 0.6rem;
    }

    #employeesTable_wrapper .dataTables_paginate .paginate_button {
        border-radius: 0.5rem !important;
        border: none !important;
        padding: 0.3rem 0.75rem !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        color: #475569 !important;
        transition: background .15s, color .15s;
        font-family: 'Prompt', sans-serif;
    }

    #employeesTable_wrapper .dataTables_paginate .paginate_button:hover {
        background: #fee2e2 !important;
        color: #b91c1c !important;
    }

    #employeesTable_wrapper .dataTables_paginate .paginate_button.current,
    #employeesTable_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #ef4444 !important;
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(239,68,68,.35);
    }

    #employeesTable_wrapper .dataTables_paginate .paginate_button.disabled,
    #employeesTable_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #cbd5e1 !important;
        background: transparent !important;
    }

    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    table.dataTable.no-footer {
        border-bottom: none !important;
    }

    table.dataTable thead th, table.dataTable tfoot th {
        font-weight: 700 !important;
    }
</style>
@endpush

@section('content')
<div class="pt-32 pb-24 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-car-side text-[#b81515]"></i> รถพนักงาน
                </h2>
                <p class="text-slate-500 mt-1 font-medium">ดูข้อมูลการจอดรถและช่องจอดของพนักงานที่ลงทะเบียนไว้</p>
            </div>
            <div>
                <a href="{{ route('parking.employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-lg shadow-slate-200 text-sm">
                    <i class="fa-solid fa-plus"></i> ลงทะเบียนจอดรถพนักงาน
                </a>
            </div>
        </div>

        @php
            $pkCollection = collect($parkings);
            $total = $pkCollection->count();
            $indoor = $pkCollection->filter(function($p) { return $p->slot && str_starts_with($p->slot->slot_number, 'B'); })->count();
            $outdoor = $pkCollection->filter(function($p) { return $p->slot && !str_starts_with($p->slot->slot_number, 'B'); })->count();
            $unassigned = $pkCollection->where('slot_id', null)->count();
        @endphp

        <!-- Dashboard Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">รถพนักงานทั้งหมด</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $total }}</h3>
                    <p class="text-[10px] font-bold text-blue-600">ลงทะเบียนในระบบ</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">ลานจอดในอาคาร (Indoor)</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $indoor }}</h3>
                    <p class="text-[10px] font-bold text-indigo-600">ช่องจอดพิเศษ</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-building"></i>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">ลานจอดรถสำนักงานใหญ่ (Outdoor)</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $outdoor }}</h3>
                    <p class="text-[10px] font-bold text-emerald-600">ช่องจอดทั่วไป</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <i class="fa-solid fa-square-parking"></i>
                </div>
            </div>
            
            <!-- Card 4 -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-500 mb-1">รอจัดสรรช่องจอด</p>
                    <h3 class="text-3xl font-black text-slate-800 leading-none mb-2">{{ $unassigned }}</h3>
                    <p class="text-[10px] font-bold text-amber-600">ยังไม่มีที่จอดประจำ</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <i class="fa-solid fa-circle-question"></i>
                </div>
            </div>
        </div>

        <!-- Search Box & Row Count -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto flex-1">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" id="tableSearch" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white transition-all font-medium" placeholder="ค้นหาชื่อพนักงาน, ทะเบียน หรือช่องจอด...">
                </div>
                <div class="relative w-full sm:max-w-xs">
                    <select id="locationFilter" class="w-full pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-red-500 focus:bg-white transition-all font-medium text-slate-600 appearance-none">
                        <option value="">พื้นที่จอดทั้งหมด (All)</option>
                        <option value="สำนักงานใหญ่">สำนักงานใหญ่ (Outdoor)</option>
                        <option value="ในอาคาร">ในอาคาร (Indoor)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            <div class="text-sm text-slate-500 font-semibold shrink-0">
                แสดงผล <span id="rowCount" class="font-black text-red-600">0</span> รายการ
            </div>
        </div>

        <!-- Data Table (Clean, Premium Enterprise Layout) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table id="employeesTable" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">
                            <th class="px-6 py-4">พนักงาน</th>
                            <th class="px-6 py-4 text-center">รหัสพนักงาน</th>
                            <th class="px-6 py-4 text-center">ทะเบียนรถ</th>
                            <th class="px-6 py-4 text-center">ช่องจอด</th>
                            <th class="px-6 py-4 text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium" id="parkingTableBody">
                        @foreach ($parkings as $parking)
                            @php
                                $name = $parking->user ? $parking->user->fullname : 'ไม่ทราบชื่อ';
                                $searchString = $name . ' ' . 
                                               ($parking->user ? $parking->user->emp_code : '') . ' ' .
                                               ($parking->user ? $parking->user->dept_name : '') . ' ' . 
                                               $parking->car_registration . ' ' . 
                                               ($parking->slot ? $parking->slot->slot_number : '');
                                               
                                // Generate initials
                                $initials = '';
                                $words = explode(' ', trim($name));
                                if (count($words) >= 2) {
                                    $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
                                } else {
                                    $initials = mb_substr($name, 0, 2);
                                }
                                $initials = mb_strtoupper($initials);

                                // Select background color based on name string
                                $bgColors = [
                                    'bg-red-50 text-red-700 border-red-100', 
                                    'bg-blue-50 text-blue-700 border-blue-100', 
                                    'bg-emerald-50 text-emerald-700 border-emerald-100', 
                                    'bg-amber-50 text-amber-700 border-amber-100', 
                                    'bg-indigo-50 text-indigo-700 border-indigo-100', 
                                    'bg-purple-50 text-purple-700 border-purple-100'
                                ];
                                $colorIndex = abs(crc32($name)) % count($bgColors);
                                $selectedColor = $bgColors[$colorIndex];
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors" data-search="{{ $searchString }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-bold {{ $selectedColor }} shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $name }}</div>
                                            <div class="text-xs text-slate-500 font-semibold mt-0.5">{{ $parking->user ? $parking->user->dept_name : '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center font-mono font-bold text-slate-700">
                                    {{ $parking->user ? $parking->user->emp_code : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-block px-3 py-1 bg-slate-50 text-slate-800 rounded-lg border border-slate-200 font-bold tracking-wide text-xs">
                                        {{ $parking->car_registration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($parking->slot)
                                        @php
                                            $slotNum = $parking->slot->slot_number;
                                            if (str_starts_with($slotNum, 'B')) {
                                                $parts = explode('_', substr($slotNum, 1));
                                                $displaySlot = "ในอาคาร ช่อง " . ($parts[0] ?? '') . " คันที่ " . ($parts[1] ?? '');
                                                $badgeStyle = "bg-indigo-50 text-indigo-700 border-indigo-100";
                                            } else {
                                                $displaySlot = "สำนักงานใหญ่ ช่อง " . $slotNum;
                                                $badgeStyle = "bg-emerald-50 text-emerald-700 border-emerald-100";
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 border rounded-full font-bold text-xs {{ $badgeStyle }}">
                                            <i class="fa-solid fa-square-parking"></i> {{ $displaySlot }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 font-normal">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right flex justify-end gap-2">
                                    <a href="{{ route('parking.employees.edit', $parking->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-slate-600 bg-slate-50 hover:bg-slate-100 hover:text-slate-800 rounded-lg border border-slate-200/80 transition-colors">
                                        <i class="fa-solid fa-pen-to-square"></i> แก้ไข
                                    </a>
                                    <form action="{{ route('parking.employees.destroy', $parking->id) }}" method="POST" class="m-0 p-0" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition-colors">
                                            <i class="fa-solid fa-trash-can"></i> ลบ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- jQuery + DataTables JS CDN -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        const table = $('#employeesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, 'ทั้งหมด']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/th.json',
            },
            dom: '<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 pt-4 mb-2"l>rt<"flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 pb-4 pt-2"ip>',
            columnDefs: [
                { orderable: false, targets: 4 } // Disable sorting on Action column
            ],
            drawCallback: function(settings) {
                // Update the custom row count badge
                const api = this.api();
                const totalRows = api.rows({ search: 'applied' }).count();
                $('#rowCount').text(totalRows);
            }
        });

        // Link custom search input to DataTables search function
        $('#tableSearch').on('keyup input', function() {
            table.search(this.value).draw();
        });

        // Link location filter dropdown
        $('#locationFilter').on('change', function() {
            table.column(3).search(this.value).draw();
        });

        // Handle SweetAlert2 delete confirmation
        $(document).on('click', '.btn-delete', function(e) {
            const form = $(this).closest('form');
            Swal.fire({
                title: 'ยืนยันการลบข้อมูล?',
                text: "คุณแน่ใจหรือไม่ที่จะลบรายการลงทะเบียนจอดรถพนักงานนี้?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#475569',
                confirmButtonText: 'ใช่, ลบเลย!',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    popup: 'rounded-3xl border-0 shadow-2xl',
                    title: 'font-prompt'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
