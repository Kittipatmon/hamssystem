@extends('layouts.sidebar')

@section('content')
<div class="min-h-screen bg-[#F8F9FA] px-4 sm:px-6 lg:px-8 py-8 font-sans">
    
    @if ($errors->any())
        <div class="max-w-[1600px] mx-auto mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm font-semibold">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Page Header -->
    <div class="max-w-[1600px] mx-auto mb-6">
        <h2 class="text-lg font-bold text-slate-800">จัดการผู้ใช้งาน / กำหนดสิทธิ์ (User & Role Management)</h2>
    </div>

    <!-- Search Filter Container -->
    <div class="max-w-[1600px] mx-auto bg-white rounded-lg shadow-sm border border-slate-200 p-5 mb-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Search Filter</h3>
        <form id="filter-form" method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="relative">
                <select name="role" class="w-full appearance-none px-4 py-2 text-sm rounded-md border border-slate-300 focus:border-indigo-500 outline-none bg-white text-slate-600 cursor-pointer transition-colors hover:border-slate-400" onchange="fetchTableData()">
                    <option value="">Select Role</option>
                    <option value="Viewer" {{ request('role') == 'Viewer' ? 'selected' : '' }}>Viewer</option>
                    <option value="Editor" {{ request('role') == 'Editor' ? 'selected' : '' }}>Editor</option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>
            
            <div class="relative">
                <select name="status" class="w-full appearance-none px-4 py-2 text-sm rounded-md border border-slate-300 focus:border-indigo-500 outline-none bg-white text-slate-600 cursor-pointer transition-colors hover:border-slate-400" onchange="fetchTableData()">
                    <option value="">Select Status</option>
                    <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online (การใช้งานอยู่)</option>
                    <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (ทำงานอยู่)</option>
                    <option value="resign" {{ request('status') == 'resign' ? 'selected' : '' }}>Resigned (ลาออก)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="max-w-[1600px] mx-auto bg-white dark:bg-[#283046] rounded-[6px] shadow-[0_4px_24px_0_rgba(34,41,47,0.05)] overflow-hidden p-4">
        
        <!-- Table Controls (Vuexy Style) -->
        <div class="pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#EBE9F1] dark:border-zinc-800">
            <div class="flex items-center gap-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">
                <span>Show</span>
                <select id="per-page-select" onchange="fetchTableData()"
                    class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] px-3 py-1 outline-none bg-white dark:bg-[#283046] cursor-pointer hover:border-slate-400">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                <span>entries</span>
            </div>
            <div class="flex items-center gap-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">
                <span>Search</span>
                <input type="text" name="search" form="filter-form" value="{{ request('search') }}" 
                    class="border border-[#D8D6DE] dark:border-zinc-700 rounded-[5px] px-3 py-1.5 outline-none bg-white dark:bg-[#283046] focus:border-[#7367F0] w-48 sm:w-64 hover:border-slate-400" 
                    placeholder="Search..."
                    onkeyup="debounceSearch(this.value)">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto min-h-[400px]" id="table-wrapper">
            <table id="users-table" class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#F3F2F7] dark:bg-[#161D31] text-[#5E5873] dark:text-[#B4B7BD] text-xs font-semibold uppercase tracking-wider border-b border-[#EBE9F1] dark:border-zinc-800">
                        <th class="py-3 pl-8 pr-3 text-left w-12">
                            <input type="checkbox" id="selectAll" class="rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                        </th>
                        <th class="py-3 px-6 text-left cursor-pointer hover:bg-[#EBE9F1]/50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="flex items-center gap-1">NAME <i class="fa-solid fa-sort text-[10px] opacity-40"></i></div>
                        </th>
                        <th class="py-3 px-6 text-left cursor-pointer hover:bg-[#EBE9F1]/50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="flex items-center gap-1">DEPARTMENT <i class="fa-solid fa-sort text-[10px] opacity-40"></i></div>
                        </th>
                        <th class="py-3 px-6 text-left cursor-pointer hover:bg-[#EBE9F1]/50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="flex items-center gap-1">EMAIL <i class="fa-solid fa-sort text-[10px] opacity-40"></i></div>
                        </th>
                        <th class="py-3 px-6 text-left cursor-pointer hover:bg-[#EBE9F1]/50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="flex items-center gap-1">ROLE <i class="fa-solid fa-sort text-[10px] opacity-40"></i></div>
                        </th>
                        <th class="py-3 px-6 text-left cursor-pointer hover:bg-[#EBE9F1]/50 dark:hover:bg-zinc-800 transition-colors">
                            <div class="flex items-center gap-1">STATUS <i class="fa-solid fa-sort text-[10px] opacity-40"></i></div>
                        </th>
                        <th class="py-3 pl-4 pr-8 text-left w-24">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBE9F1] dark:divide-zinc-850">
                    @forelse($users as $user)
                        @php
                            // Generate Avatar Initials
                            $nameParts = explode(' ', trim($user->fullname));
                            $initials = mb_substr($nameParts[0], 0, 1);
                            if(isset($nameParts[1])) $initials .= mb_substr($nameParts[1], 0, 1);
                            
                            // Mock colors matching the image style
                            $colors = [
                                'bg-[#7367F0]/10 text-[#7367F0]', 
                                'bg-[#00CFE8]/10 text-[#00CFE8]', 
                                'bg-[#FF9F43]/10 text-[#FF9F43]', 
                                'bg-[#EA5455]/10 text-[#EA5455]',
                                'bg-[#28C76F]/10 text-[#28C76F]'
                            ];
                            $avatarColor = $colors[$user->id % count($colors)];

                            // Handle real roles
                            $realRole = $user->role ?? 'viewer';
                            if ($realRole === 'admin') {
                                $roleLabel = 'Admin';
                                $roleBadge = 'bg-[#7367F0]/10 text-[#7367F0]';
                            } elseif ($realRole === 'editor') {
                                $roleLabel = 'Editor';
                                $roleBadge = 'bg-[#FF9F43]/10 text-[#FF9F43]';
                            } else {
                                $roleLabel = 'Viewer';
                                $roleBadge = 'bg-[#82868B]/10 text-[#82868B]';
                            }
                            
                            // Mock Email if not available
                            $email = $user->email ?? (strtolower(str_replace(' ', '.', $nameParts[0])) . '@example.com');
                        @endphp
                        <tr class="hover:bg-[#FAF9FF] dark:hover:bg-[#343D55] transition-colors">
                            <td class="py-3.5 pl-8 pr-3 text-left">
                                <input type="checkbox" class="row-checkbox rounded border-[#D8D6DE] dark:border-zinc-700 text-[#7367F0] focus:ring-[#7367F0]">
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    @if($user->photo_user)
                                        <img src="{{ asset($user->photo_user) }}" class="w-9 h-9 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs {{ $avatarColor }} shrink-0 uppercase">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-[#5E5873] dark:text-white">{{ $user->fullname }}</div>
                                        <div class="text-xs text-[#B9B9C3] mt-0.5">{{ '@' . strtolower($user->emp_code) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="text-sm font-medium text-[#6E6B7B] dark:text-[#B4B7BD]">{{ $user->department->name ?? '-' }}</div>
                            </td>
                            <td class="py-3.5 px-6">
                                <div class="text-sm text-[#6E6B7B] dark:text-[#B4B7BD]">{{ $email }}</div>
                            </td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-[4px] text-xs font-semibold {{ $roleBadge }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                @if($user->isOnline())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#28C76F]/10 text-[#28C76F]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#28C76F] mr-1.5 animate-pulse"></span>
                                        Online
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#EA5455]/10 text-[#EA5455]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#EA5455] mr-1.5"></span>
                                        Offline
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 pl-4 pr-8 text-left relative" x-data="{ open: false }">
                                <div class="flex items-center justify-start gap-1">
                                    <a href="{{ route('users.show', $user->id) }}" 
                                        class="w-7 h-7 flex items-center justify-center rounded bg-slate-50 hover:bg-[#7367F0]/15 text-[#6E6B7B] hover:text-[#7367F0] dark:bg-zinc-800 dark:hover:bg-[#7367F0]/20 transition-colors"
                                        title="View Details">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>
                                    
                                    <button @click="open = !open" @click.away="open = false" 
                                        class="w-7 h-7 flex items-center justify-center rounded bg-slate-50 hover:bg-slate-100 text-[#6E6B7B] dark:bg-zinc-800 dark:hover:bg-zinc-700 transition-colors" 
                                        title="Options">
                                        <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                    </button>
                                </div>
                                
                                <div x-show="open" style="display: none;" 
                                    class="absolute right-6 top-10 w-48 bg-white dark:bg-[#283046] rounded-md shadow-lg border border-[#EBE9F1] dark:border-zinc-800 z-10 py-1 text-left">
                                    @if(Auth::user()->role === 'admin' && Auth::id() !== $user->id)
                                        <button onclick="openRoleModal({{ $user->id }}, '{{ $realRole }}')" 
                                            class="w-full text-left px-4 py-2 text-sm text-[#6E6B7B] dark:text-[#B4B7BD] hover:bg-slate-50 dark:hover:bg-[#343D55] flex items-center">
                                            <i class="fa-solid fa-user-shield w-4 mr-2 text-[#7367F0]"></i> กำหนดสิทธิ์
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-[#B9B9C3]">
                                <p class="text-sm font-semibold">No records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    
    <!-- AlpineJS for Dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery & DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
            display: none !important; /* Hide default length and filter boxes, we use our custom inputs */
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
            var table = $('#users-table').DataTable({
                "dom": 'rtip',
                "order": [[ 1, "asc" ]],
                "pageLength": 10,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 6] }
                ],
                "language": {
                    "zeroRecords": "ไม่พบข้อมูลพนักงาน",
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

            // Bind Custom Search Box
            $('input[name="search"]').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Bind Custom Per Page Select
            $('#per-page-select').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });

            // Bind Custom Filter dropdowns (Role & Status)
            $('select[name="role"]').on('change', function() {
                var val = $(this).val();
                table.column(4).search(val ? '^' + val + '$' : '', true, false).draw();
            });

            $('select[name="status"]').on('change', function() {
                var val = $(this).val();
                // Filter online/offline status column
                if (val === 'online') {
                    table.column(5).search('Online').draw();
                } else if (val === 'offline') {
                    table.column(5).search('Offline').draw();
                } else {
                    table.column(5).search('').draw();
                }
            });
        });

        function openRoleModal(userId, currentRole) {
            const roleOptions = {
                'admin': 'Admin (ผู้ดูแลระบบ)',
                'editor': 'Editor (ผู้แก้ไข)',
                'viewer': 'Viewer (ผู้เข้าชม)'
            };
            
            let optionsHtml = '';
            for (const [val, label] of Object.entries(roleOptions)) {
                const selected = val === currentRole ? 'selected' : '';
                optionsHtml += `<option value="${val}" ${selected}>${label}</option>`;
            }

            Swal.fire({
                title: 'กำหนดสิทธิ์การใช้งาน',
                html: `
                    <div class="text-sm text-slate-600 mb-4">เลือกระดับสิทธิ์ที่ต้องการกำหนดให้พนักงานรายนี้ และระบุเหตุผลการเปลี่ยนแปลง</div>
                    <div class="text-left mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">ระดับสิทธิ์</label>
                        <select id="swal-role" class="w-full border border-slate-300 rounded-md px-3 py-2 outline-none focus:border-indigo-500">
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="text-left">
                        <label class="block text-sm font-medium text-slate-700 mb-1">เหตุผลการเปลี่ยนสิทธิ์ <span class="text-red-500">*</span></label>
                        <input id="swal-reason" class="w-full border border-slate-300 rounded-md px-3 py-2 outline-none focus:border-indigo-500" placeholder="ระบุเหตุผล..." autocomplete="off">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก',
                preConfirm: () => {
                    const role = document.getElementById('swal-role').value;
                    const reason = document.getElementById('swal-reason').value;
                    if (!reason.trim()) {
                        Swal.showValidationMessage('กรุณาระบุเหตุผลการเปลี่ยนสิทธิ์');
                        return false;
                    }
                    return { role: role, reason: reason };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    changeUserRole(userId, result.value.role, result.value.reason);
                }
            });
        }

        function changeUserRole(userId, newRole, reason = '') {
            Swal.fire({
                title: 'ยืนยันการเปลี่ยนสิทธิ์?',
                text: `คุณต้องการเปลี่ยนสิทธิ์พนักงานเป็น ${newRole.charAt(0).toUpperCase() + newRole.slice(1)} ใช่หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send API Request
                    fetch(`/users/${userId}/role`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            role: newRole,
                            reason: reason
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: data.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                             }).then(() => {
                                window.location.reload();
                             });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
                    });
                }
            });
        }
    </script>
@endpush