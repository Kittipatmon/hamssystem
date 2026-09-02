@extends('layouts.sidebar')

@section('title', $type === 'policy' ? 'จัดการนโยบาย' : 'จัดการขั้นตอนการดำเนินงาน')

@section('content')
<div class="min-h-screen bg-[#F8F8F9] dark:bg-[#161D31] px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-6 font-sans">
        
        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (VUEXY STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-[#283046] rounded-[6px] shadow-[0_4px_24px_0_rgba(34,41,47,0.05)] p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-[#7367F0]/10 rounded mb-2">
                    <span class="text-[10px] font-bold text-[#7367F0] uppercase tracking-widest">SYSTEM DATA CONTROL</span>
                </div>
                <h1 class="text-2xl font-semibold text-[#5E5873] dark:text-white mb-1">
                    {{ $type === 'policy' ? 'จัดการนโยบายระบบ (Policies)' : 'จัดการขั้นตอนการดำเนินงาน (Operations)' }}
                </h1>
                <p class="text-[#B9B9C3] text-sm">
                    จัดการบันทึกและปรับปรุงข้อมูล{{ $type === 'policy' ? 'นโยบายส่วนกลาง' : 'ขั้นตอนการทำงานมาตรฐาน' }} ที่แสดงในหน้าบริการระบบหลัก
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('backend.policy.create', ['type' => $type]) }}"
                    class="bg-[#7367F0] hover:bg-[#6357E0] shadow-[0_8px_25px_-8px_#7367F0] text-white px-4 py-2.5 rounded-[5px] text-sm font-semibold transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>เพิ่มข้อมูลใหม่</span>
                </a>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             DATA TABLE (VUEXY STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-[#283046] rounded-[6px] shadow-[0_4px_24px_0_rgba(34,41,47,0.05)] overflow-hidden p-4">
            
            <!-- Table Controls (Vuexy Style) -->
            <div class="pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#EBE9F1] dark:border-zinc-800">
                <div class="flex items-center gap-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">
                    <span>Show</span>
                    <select id="per-page-select" class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] px-3 py-1 outline-none bg-white dark:bg-[#283046] cursor-pointer hover:border-slate-400">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">
                    <span>Search</span>
                    <input type="text" id="searchInput" class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] px-3 py-1.5 outline-none bg-white dark:bg-[#283046] focus:border-[#7367F0] w-48 sm:w-64 hover:border-slate-400" placeholder="Search...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="policies-table" class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#F3F2F7] dark:bg-[#161D31] text-[#5E5873] dark:text-[#B4B7BD] text-xs font-semibold uppercase tracking-wider border-b border-[#EBE9F1] dark:border-zinc-800">
                            <th class="py-3 pl-8 pr-3 text-left w-12">
                                <input type="checkbox" id="selectAll" class="rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                            </th>
                            <th class="py-3 px-6 text-left w-24">ลำดับ</th>
                            <th class="py-3 px-6">หัวข้อ / รายละเอียดสำคัญ</th>
                            <th class="py-3 px-6 text-left w-44">ประเภทข้อมูล</th>
                            <th class="py-3 pl-4 pr-8 text-left w-36">เครื่องมือการจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EBE9F1] dark:divide-zinc-850">
                        @forelse($policies as $item)
                            <tr class="hover:bg-[#FAF9FF] dark:hover:bg-[#343D55] transition-colors">
                                <td class="py-3.5 pl-8 pr-3 text-left">
                                    <input type="checkbox" class="row-checkbox rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                                </td>
                                <td class="py-3.5 px-6 text-left">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-[3px] bg-[#7367F0]/10 text-[#7367F0] text-xs font-semibold">
                                        #{{ $item->order }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="text-sm font-semibold text-[#5E5873] dark:text-white mb-1 leading-snug">{{ $item->title }}</div>
                                    <div class="text-xs text-[#B9B9C3] line-clamp-2 leading-relaxed font-normal">{{ strip_tags($item->content) }}</div>
                                </td>
                                <td class="py-3.5 px-6 text-left">
                                    @if($item->type === 'policy')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#00CFE8]/10 text-[#00CFE8] text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#00CFE8] mr-1.5"></span> นโยบาย
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#28C76F]/10 text-[#28C76F] text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#28C76F] mr-1.5"></span> การดำเนินงาน
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 pl-4 pr-8">
                                    <div class="flex items-center justify-start gap-2">
                                        <a href="{{ route('backend.policy.edit', $item) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded bg-slate-50 hover:bg-[#FF9F43]/15 text-[#6E6B7B] hover:text-[#FF9F43] dark:bg-zinc-800 dark:hover:bg-[#FF9F43]/20 transition-colors"
                                            title="แก้ไขข้อมูล">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('backend.policy.destroy', $item) }}" method="POST" onsubmit="return confirm('ยืนยันการลบข้อมูลนี้ออกจากระบบ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded bg-slate-50 hover:bg-[#EA5455]/15 text-[#6E6B7B] hover:text-[#EA5455] dark:bg-zinc-800 dark:hover:bg-[#EA5455]/20 transition-colors"
                                                title="ลบข้อมูล">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-[#B9B9C3] bg-[#FAF9FF] dark:bg-[#161D31] italic font-semibold">
                                    <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                                    ไม่พบข้อมูลใดๆ ในระบบ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- jQuery & DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
            display: none !important; /* Hide default length and filter boxes */
        }
        .dataTables_wrapper .dataTables_info {
            color: #6E6B7B !important;
            font-size: 0.825rem;
            padding-top: 1.25rem !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 1.25rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #7367F0 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.375rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #7367F0/10 !important;
            color: #7367F0 !important;
            border: none !important;
        }
    </style>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#policies-table').DataTable({
                "dom": 'rtip',
                "order": [[ 1, "asc" ]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 4] }
                ],
                "language": {
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "Showing 0 to 0 of 0 entries",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "paginate": {
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });

            // Toggle all checkboxes
            $('#selectAll').on('click', function() {
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('.row-checkbox', rows).prop('checked', this.checked);
            });

            // Bind Custom Search Input
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Bind Custom Per Page Selector
            $('#per-page-select').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });
        });
    </script>
@endpush
@endsection
