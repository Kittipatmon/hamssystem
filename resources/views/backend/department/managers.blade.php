@extends('layouts.sidebar')
@section('title', 'ข้อมูลหัวหน้าแผนก (Department Managers)')
@section('content')

<div class="min-h-screen bg-[#F8F8F9] dark:bg-[#161D31] px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-6 font-sans">

        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (VUEXY STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-[#283046] rounded-[6px] shadow-[0_4px_24px_0_rgba(34,41,47,0.05)] p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-[#7367F0]/10 rounded mb-2">
                    <span class="text-[10px] font-bold text-[#7367F0] uppercase tracking-widest">MANAGERS DIRECTORY</span>
                </div>
                <h1 class="text-2xl font-semibold text-[#5E5873] dark:text-white mb-1">
                    รายชื่อหัวหน้าแผนก (Department Managers)
                </h1>
                <p class="text-[#B9B9C3] text-sm">
                    สืบค้นและตรวจสอบรายชื่อผู้บริหารและหัวหน้าแผนกต่างๆ ในองค์กร
                </p>
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
                    <select class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] px-3 py-1 outline-none bg-white dark:bg-[#283046] cursor-pointer hover:border-slate-400">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">
                    <span>Search</span>
                    <div class="relative group">
                        <input type="text" id="searchInput" value="{{ request('search') }}" 
                            class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] pl-3 pr-10 py-1.5 outline-none bg-white dark:bg-[#283046] focus:border-[#7367F0] w-48 sm:w-64 hover:border-slate-400" 
                            placeholder="Search...">
                        <div id="searchLoader" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                            <span class="loading loading-spinner loading-xs text-[#7367F0]"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="managers-table" class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-[#F3F2F7] dark:bg-[#161D31] text-[#5E5873] dark:text-[#B4B7BD] text-xs font-semibold uppercase tracking-wider border-b border-[#EBE9F1] dark:border-zinc-800">
                            <th class="py-3 pl-8 pr-3 text-left w-12">
                                <input type="checkbox" id="selectAll" class="rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                            </th>
                            <th class="py-3 px-6 w-24 text-left">ลำดับ</th>
                            <th class="py-3 px-6">แผนก (Department)</th>
                            <th class="py-3 pl-4 pr-8 text-left">หัวหน้าแผนก (Manager)</th>
                        </tr>
                    </thead>
                    <tbody id="departmentsBody" class="divide-y divide-[#EBE9F1] dark:divide-zinc-850">
                        @foreach ($departments as $department)
                            @php
                                $colors = [
                                    'bg-[#7367F0]/10 text-[#7367F0]', 
                                    'bg-[#00CFE8]/10 text-[#00CFE8]', 
                                    'bg-[#FF9F43]/10 text-[#FF9F43]', 
                                    'bg-[#EA5455]/10 text-[#EA5455]',
                                    'bg-[#28C76F]/10 text-[#28C76F]'
                                ];
                                $avatarColor = $colors[$department->id % count($colors)];
                            @endphp
                            <tr class="hover:bg-[#FAF9FF] dark:hover:bg-[#343D55] transition-colors">
                                <td class="py-3.5 pl-8 pr-3 text-left">
                                    <input type="checkbox" class="row-checkbox rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                                </td>
                                <td class="py-3.5 px-6 text-left text-sm font-semibold text-[#B9B9C3]">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm {{ $avatarColor }} shrink-0">
                                            <i class="fa-solid fa-building text-xs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-[#5E5873] dark:text-white text-sm">{{ $department->name }}</span>
                                            <span class="text-xs text-[#B9B9C3] mt-0.5 uppercase tracking-wider">DEPT-{{ str_pad($department->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 pl-4 pr-8">
                                    @if($department->manager)
                                        @php
                                            $nameParts = explode(' ', trim($department->manager->fullname));
                                            $initials = mb_substr($nameParts[0], 0, 1);
                                            if(isset($nameParts[1])) $initials .= mb_substr($nameParts[1], 0, 1);
                                            $mgrAvatarColor = $colors[$department->manager->id % count($colors)];
                                        @endphp
                                        <div class="flex items-center gap-3">
                                            @if($department->manager->photo_user)
                                                <img src="{{ asset($department->manager->photo_user) }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                                            @else
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs {{ $mgrAvatarColor }} shrink-0 uppercase">
                                                    {{ $initials }}
                                                </div>
                                            @endif
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-[#5E5873] dark:text-white text-sm">{{ $department->manager->fullname }}</span>
                                                <span class="text-xs text-[#B9B9C3] mt-0.5">{{ $department->manager->position ?? 'Manager' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-[#82868B]/10 text-[#82868B] text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#82868B] mr-1.5"></span> ยังไม่กำหนดหัวหน้าแผนก
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
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
            var table = $('#managers-table').DataTable({
                "dom": 'rtip',
                "order": [[ 1, "asc" ]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": [0] }
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
        });
    </script>
@endpush
@endsection
