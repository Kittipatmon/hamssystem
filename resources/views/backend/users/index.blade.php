@extends('layouts.sidebar')
@section('content')

<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 pb-6 border-b border-gray-200 dark:border-white/10">
        <div>
            <h1 class="text-3xl font-bold text-kumwell-red flex items-center gap-3">
                <i class="fa-solid fa-users-gear text-2xl opacity-80"></i>
                จัดการข้อมูลพนักงาน
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">จัดการข้อมูลพื้นฐาน, ระดับพนักงาน และสถานะการทำงาน</p>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <button type="button" id="toggle-filter" class="btn btn-ghost border-gray-300 dark:border-white/10 hover:bg-gray-100 dark:hover:bg-white/5 flex-grow md:flex-none">
                <i class="fa-solid fa-filter mr-2 text-warning"></i> ตัวกรอง
            </button>
            <a href="{{ route('users.create') }}" class="btn btn-kumwell-red flex-grow md:flex-none shadow-lg">
                <i class="fa-solid fa-user-plus mr-2"></i>
                เพิ่มพนักงานใหม่
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-error mb-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Filter Form --}}
    <form id="filter-form" method="GET" action="{{ route('users.index') }}"
        class="kumwell-card kumwell-glass p-6 hidden transition-all duration-500 ease-in-out border-kumwell-red/20 shadow-xl shadow-kumwell-red/5">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end mb-4">
            <div class="form-control">
                <span class="label-text mb-1 text-xs text-gray-500 dark:text-gray-400">รหัสพนักงาน</span>
                <input type="text" name="employee_code" placeholder="ค้นหารหัส..."
                    value="{{ request('employee_code') }}"
                    class="input input-bordered input-sm w-full dark:bg-gray-700" />
            </div>

            <div class="form-control">
                <span class="label-text mb-1 text-xs text-gray-500 dark:text-gray-400">ชื่อ-นามสกุล</span>
                <input type="text" name="fullname" placeholder="ค้นหาชื่อ..." value="{{ request('fullname') }}"
                    class="input input-bordered input-sm w-full dark:bg-gray-700" />
            </div>

            <div class="form-control">
                <span class="label-text mb-1 text-xs text-gray-500 dark:text-gray-400">ตำแหน่ง</span>
                <input type="text" name="position" placeholder="ค้นหาตำแหน่ง..." value="{{ request('position') }}"
                    class="input input-bordered input-sm w-full dark:bg-gray-700" />
            </div>
            <div class="form-control">
                <span class="label-text mb-1 text-xs text-gray-500 dark:text-gray-400">ประเภทพนักงาน</span>
                <select name="employee_type" class="select select-bordered select-sm w-full dark:bg-gray-700">
                    <option value="">ทั้งหมด</option>
                    <option value="รายเดือน"
                        {{ request('employee_type') === 'รายเดือน' ? 'selected' : '' }}>รายเดือน</option>
                    <option value="รายวัน"
                        {{ request('employee_type') === 'รายวัน' ? 'selected' : '' }}>รายวัน</option>
                </select>
            </div>
            <div>
                <span class="label-text mb-1 text-xs text-gray-500 dark:text-gray-400">สถานะ Active</span>
                @php $statusOptions = \App\Models\User::getStatusOptions(); @endphp
                <select name="status" class="select select-bordered select-sm w-full dark:bg-gray-700">
                    <option value="">ทั้งหมด</option>
                    @foreach($statusOptions as $value => $option)
                    @php
                        $label = is_array($option) ? ($option['label'] ?? '') : $option;
                    @endphp
                    <option value="{{ $value }}"
                        {{ (string)request('status') === (string)$value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <select name="department" class="select select-bordered select-sm w-full dark:bg-gray-700">
                <option value="">แผนก (ทั้งหมด)</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->department_id }}"
                    {{ (string)request('department') === (string)$dept->department_id ? 'selected' : '' }}>
                    {{ $dept->department_name }}
                </option>
                @endforeach
            </select>

            <select name="division" class="select select-bordered select-sm w-full dark:bg-gray-700">
                <option value="">ฝ่าย (ทั้งหมด)</option>
                @foreach($divisions as $div)
                <option value="{{ $div->division_id }}"
                    {{ (string)request('division') === (string)$div->division_id ? 'selected' : '' }}>
                    {{ $div->division_name }}
                </option>
                @endforeach
            </select>

            <select name="section" class="select select-bordered select-sm w-full dark:bg-gray-700">
                <option value="">สายงาน (ทั้งหมด)</option>
                @foreach($sections as $sect)
                <option value="{{ $sect->section_id }}"
                    {{ (string)request('section') === (string)$sect->section_id ? 'selected' : '' }}>
                    {{ $sect->section_code }}
                </option>
                @endforeach
            </select>

            @php
            $levelOptions = \App\Models\User::getLevelUserOptions();
            $selectedLevel = request('level_user');
            @endphp
            <select name="level_user" class="select select-bordered select-sm w-full dark:bg-gray-700">
                <option value="">ระดับพนักงาน (ทั้งหมด)</option>
                @foreach($levelOptions as $value => $meta)
                <option value="{{ $value }}" {{ (string)$selectedLevel === (string)$value ? 'selected' : '' }}>
                    {{ $meta['label'] }}
                </option>
                @endforeach
            </select>

            @php
            $hamsStatusOptions = \App\Models\User::getHamsStatusOptions();
            $selectedHamsStatus = request('hr_status');
            @endphp

            <select name="hr_status" class="select select-bordered select-sm w-full dark:bg-gray-700">
                <option value="">สถานะ HAMS (ทั้งหมด)</option>
                @foreach($hamsStatusOptions as $value => $option)
                @php
                // รองรับทั้งกรณีเป็น string ตรง ๆ หรือเป็น array ที่มี key 'label'
                $label = is_array($option) ? ($option['label'] ?? '') : $option;
                @endphp
                <option value="{{ $value }}" {{ (string)$selectedHamsStatus === (string)$value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>


        </div>

        <div class="flex justify-end mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-ghost btn-sm text-gray-500">
                <i class="fa-solid fa-rotate-left mr-1"></i> ล้างค่า
            </a>
        </div>
    </form>

    {{-- Table Container --}}
    <div class="kumwell-card bg-white dark:bg-kumwell-card border border-gray-200 dark:border-white/5 overflow-hidden shadow-2xl shadow-gray-200/50 dark:shadow-none relative"
        id="table-wrap" data-show-pattern="{{ url('users') }}/:id" data-edit-pattern="{{ url('users') }}/:id/edit"
        data-destroy-pattern="{{ url('users') }}/:id">

        <div id="loader"
            class="hidden absolute inset-0 bg-white/60 dark:bg-kumwell-dark/60 backdrop-blur-md flex items-center justify-center z-20">
            <div class="flex flex-col items-center gap-3">
                <span class="loading loading-spinner loading-lg text-kumwell-red"></span>
                <span class="text-kumwell-red font-medium text-sm animate-pulse">กำลังโหลดข้อมูล...</span>
            </div>
        </div>
        <div>
            <div class="p-4 text-sm text-gray-500">
                แสดงผล {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} จาก
                ทั้งหมด {{ $users->total() }} รายการ
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-md w-full">
                <thead class="kumwell-table-header dark:text-gray-300">
                    <tr class="border-b border-gray-200 dark:border-white/5">
                        <th class="py-4 pl-6">รหัสพนักงาน</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>หน่วยงาน (แผนก/ฝ่าย/สายงาน)</th>
                        <th>ตำแหน่ง / ประเภท</th>
                        <th class="text-center">ระดับ</th>
                        <th class="text-center">สถานะ HAMS</th>
                        <th class="text-center">สถานะ Active</th>
                        <th class="w-32 text-center pr-6">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="users-body"
                    class="text-gray-700 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5">
                        <td class="pl-6">
                            <span class="font-mono text-sm bg-gray-100 dark:bg-white/10 px-2 py-1 rounded text-kumwell-red font-bold">
                                {{ $user->employee_code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-bold text-gray-900 dark:text-white">{{ $user->fullname }}</div>
                            <div class="text-xs text-gray-400 mt-0.5 italic">{{ $user->prefix }}</div>
                        </td>
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fa-solid fa-building-user mr-1 opacity-50"></i> {{ $user->department->department_name ?? '-' }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    <i class="fa-solid fa-layer-group mr-1 opacity-50"></i> {{ $user->division->division_name ?? '-' }} / {{ $user->section->section_code ?? '-' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->position }}</div>
                            <div class="badge badge-ghost badge-xs opacity-70 mt-1 uppercase tracking-wider text-[10px]">{{ $user->employee_type ?? '-' }}</div>
                        </td>
                        <td class="text-center">
                            <div class="kumwell-badge bg-{{ $user->level_user_color }}/10 text-{{ $user->level_user_color }} border border-{{ $user->level_user_color }}/20">
                                <i class="fa-solid fa-award text-[10px]"></i>
                                {{ $user->level_user_label }}
                            </div>
                        </td>
                        <td class="text-center">
                            @php $hamsMeta = \App\Models\User::getHamsStatusOptions()[$user->hr_status] ?? null; @endphp
                            <div class="kumwell-badge bg-{{ $user->hams_status_color }}/10 text-{{ $user->hams_status_color }} border border-{{ $user->hams_status_color }}/20">
                                {!! $user->hams_status_icon !!}
                                {{ $user->hams_status_label }}
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="kumwell-badge bg-{{ $user->status_color }}/10 text-{{ $user->status_color }} border border-{{ $user->status_color }}/20">
                                {!! $user->status_icon !!}
                                {{ $user->status_label }}
                            </div>
                        </td>
                        <td class="pr-6">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-circle btn-ghost btn-xs hover:bg-info/20 hover:text-info transition-all" title="ดูข้อมูล">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all" title="แก้ไข">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    class="inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-gray-500 py-10">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-inbox text-4xl mb-2 text-gray-300"></i>
                                <span>ไม่พบข้อมูลตามเงื่อนไข</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center gap-4" id="pagination">
        {{ $users->links() }}
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Delete confirmation (works for server + dynamically rendered rows) ---
    document.addEventListener('submit', (e) => {
        const formEl = e.target?.closest?.('form.form-delete');
        if (!formEl) return;

        // prevent infinite loop when we call form.submit() after confirmation
        if (formEl.dataset.confirmed === '1') {
            delete formEl.dataset.confirmed;
            return;
        }

        e.preventDefault();

        const proceed = () => {
            formEl.dataset.confirmed = '1';
            formEl.submit();
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'เมื่อลบแล้วจะไม่สามารถกู้คืนได้',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) proceed();
            });
        } else {
            if (confirm('ยืนยันการลบ?')) proceed();
        }
    });

    const addUserForm = document.getElementById('add_user_form');
    const addUserModal = document.getElementById('add_user_modal');
    const modalErrors = document.getElementById('modal_errors');
    const confirmAddUserBtn = document.getElementById('confirm-add-user');

    if (confirmAddUserBtn && addUserForm) {
        confirmAddUserBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (addUserModal && typeof addUserModal.close === 'function') {
                addUserModal.close();
            }

            // 2. เรียก SweetAlert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'ยืนยันการบันทึกข้อมูล?',
                    text: 'คุณต้องการบันทึกข้อมูลพนักงานใหม่ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#10b981', // Tailwind green-500 hex
                    cancelButtonColor: '#6b7280', // Tailwind gray-500 hex
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit จริง
                        addUserForm.requestSubmit();
                    } else {
                        // ถ้ากดยกเลิก ให้เปิด Modal กลับมา
                        if (addUserModal && typeof addUserModal.showModal === 'function') {
                            addUserModal.showModal();
                        }
                    }
                });
            } else {
                // Fallback ถ้าไม่มี SweetAlert
                if (confirm('คุณต้องการบันทึกข้อมูลพนักงานใหม่ใช่หรือไม่?')) {
                    addUserForm.requestSubmit();
                } else {
                    if (addUserModal) addUserModal.showModal();
                }
            }
        });
    }

    if (addUserForm) {
        addUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            // ถ้า User submit เอง (กด enter) ให้ปิด modal ก่อนเล็กน้อยหรือโชว์ Loading
            // แต่ใน flow นี้เราใช้ปุ่มแยก ดังนั้นโค้ดนี้จะทำงานตอน requestSubmit()

            if (modalErrors) {
                modalErrors.classList.add('hidden');
                modalErrors.innerHTML = '';
            }

            // Re-open modal ถ้าเป็นการ submit แบบปกติเพื่อให้เห็น loading หรือ error (กรณีไม่ได้ผ่าน swal logic)
            // แต่เนื่องจาก requestSubmit() มาจาก logic ข้างบน เราอาจจะต้องเปิด Modal มารับ Error
            // หากเป็นการ submit success โค้ดด้านล่างจะจัดการเอง

            const formData = new FormData(this);
            const action = this.getAttribute('action');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (response.status === 422) {
                    // Validation Errors: ต้องเปิด Modal กลับมาแสดง Error
                    if (addUserModal && !addUserModal.open) addUserModal.showModal();

                    const errorData = await response.json();
                    let errorHtml = '<ul class="list-disc list-inside">';
                    for (const key in errorData.errors) {
                        errorData.errors[key].forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                    }
                    errorHtml += '</ul>';
                    if (modalErrors) {
                        modalErrors.innerHTML = errorHtml;
                        modalErrors.classList.remove('hidden');
                    }

                    // Scroll to top of modal
                    const modalBox = addUserModal?.querySelector?.('.modal-box');
                    if (modalBox) modalBox.scrollTop = 0;

                } else if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                } else {
                    // Success
                    this.reset();
                    // Modal ปิดอยู่แล้วจาก step ก่อนหน้า หรือถ้าเปิดอยู่ก็ปิดเลย
                    if (addUserModal && addUserModal.open) addUserModal.close();

                    await fetchUsers(); // Refresh Table

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: 'เพิ่มพนักงานเรียบร้อยแล้ว',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        alert('เพิ่มพนักงานเรียบร้อยแล้ว');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                if (addUserModal && !addUserModal.open) addUserModal.showModal();
                if (modalErrors) {
                    modalErrors.innerHTML = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล โปรดลองใหม่อีกครั้ง';
                    modalErrors.classList.remove('hidden');
                }
            }
        });
    }

    // ... (ส่วน Logic อื่นๆ เช่น Filter, Pagination คงเดิม) ...
    // --- Filter Toggle ---
    const btn = document.getElementById('toggle-filter');
    const panel = document.getElementById('filter-form');
    if (btn && panel) {
        btn.addEventListener('click', function() {
            panel.classList.toggle('hidden');
        });
    }

    // --- Realtime Search/Pagination Logic (Code เดิมของคุณใส่ตรงนี้ต่อได้เลย) ---
    // (copy logic fetchUsers, renderRows, renderPagination เดิมมาใส่)
    const form = document.getElementById('filter-form');
    const tbody = document.getElementById('users-body');
    const pagWrap = document.getElementById('pagination');
    const loader = document.getElementById('loader');
    const tableWrap = document.getElementById('table-wrap');

    if (!form || !tbody || !pagWrap || !loader || !tableWrap) {
        return;
    }

    const showPattern = tableWrap.dataset.showPattern || '/users/:id';
    const editPattern = tableWrap.dataset.editPattern || '/users/:id/edit';
    const destroyPattern = tableWrap.dataset.destroyPattern || '/users/:id';

    const debouncedFetch = debounce(fetchUsers, 300);

    form.querySelectorAll('input').forEach(el => {
        el.addEventListener('input', debouncedFetch);
        el.addEventListener('change', debouncedFetch);
    });
    form.querySelectorAll('select').forEach(el => {
        el.addEventListener('change', debouncedFetch);
    });

    pagWrap.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;
        e.preventDefault();
        const url = new URL(a.href);
        fetchUsers(url.searchParams);
    });

    // ... (ฟังก์ชัน fetchUsers, renderRows, helpers เดิม) ...
    async function fetchUsers(overrideSearchParams = null) {
        loader.classList.remove('hidden');
        let params;
        if (overrideSearchParams instanceof URLSearchParams) {
            params = overrideSearchParams;
        } else {
            params = new URLSearchParams();
            new FormData(form).forEach((v, k) => {
                if (v) params.set(k, v);
            });
            const cur = new URLSearchParams(window.location.search);
            if (cur.get('per_page')) params.set('per_page', cur.get('per_page'));
        }
        if (!params.get('per_page')) params.set('per_page', 50);
        if (!params.get('include')) params.set('include', 'department,division,section');

        try {
            const res = await fetch(`/api/users?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            renderRows(data.data || []);
            renderPagination(data);
            const sync = new URLSearchParams(params);
            sync.set('page', data.current_page || 1);
            history.replaceState({}, '', `${window.location.pathname}?${sync.toString()}`);
        } catch (err) {
            console.error(err);
        } finally {
            loader.classList.add('hidden');
        }
    }

    function renderRows(items) {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        if (!Array.isArray(items) || items.length === 0) {
            tbody.innerHTML =
                `<tr><td colspan="11" class="text-center text-gray-500 py-10">ไม่พบข้อมูลตามเงื่อนไข</td></tr>`;
            return;
        }
        tbody.innerHTML = items.map(u => {
            const showUrl = showPattern.replace(':id', u.id);
            const editUrl = editPattern.replace(':id', u.id);
            const destroyUrl = destroyPattern.replace(':id', u.id);

            const hasHrStatus = u.hr_status !== null && u.hr_status !== undefined && u.hr_status !== '';
            const hasStatus = u.status !== null && u.status !== undefined && u.status !== '';
            // ... (Logic การสร้าง HTML Row เหมือนเดิม แต่ปรับ class เล็กน้อยให้เข้ากับ Tailwind) ...
            return `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                <td class="font-medium">${safe(u.employee_code)}</td>
                <td><div class="font-bold">${safe(u.fullname)}</div></td>
                <td>${safe(u.department?.department_name ?? u.department?.name)}</td>
                <td>${safe(u.division?.division_name ?? u.division?.name)}</td>
                <td>${safe(u.section?.section_code ?? u.section?.code)}</td>
                <td>${safe(u.position)}</td>
                <td class="whitespace-nowrap">${safe(u.employee_type)}</td>
                <td>${levelBadge(u)}</td>
                <td>${hasHrStatus ? `<span class="badge badge-${safe(u.hams_status_color ?? 'neutral')} badge-sm text-xs">${safe(u.hams_status_label ?? u.hr_status)}</span>` : '-'}</td>
                <td>${hasStatus ? `<span class="badge badge-${safe(u.status_color ?? 'neutral')} badge-sm text-xs text-white">${safe(u.status_label ?? u.status)}</span>` : '-'}</td>
                <td>
                    <div class="flex justify-center gap-1">
                        <a href="${showUrl}" class="btn btn-square btn-xs btn-info text-white"><i class="fa-solid fa-eye"></i></a>
                        <a href="${editUrl}" class="btn btn-square btn-xs btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="${destroyUrl}" method="POST" class="inline form-delete">
                            <input type="hidden" name="_token" value="${csrf}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-square btn-xs btn-error text-white" title="ลบ"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    // Helper functions (debounce, safe, etc.) ... keep as is
    function debounce(fn, wait = 300) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    function formatDate(iso) {
        if (!iso) return '-';
        const d = new Date(iso);
        return isNaN(d) ? '-' : d.toLocaleDateString('th-TH', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function safe(v) {
        return (v ?? '-').toString().replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');
    }

    function levelBadge(u) {
        return `<span class="badge badge-${safe(u.level_user_color ?? 'neutral')} badge-outline badge-sm font-medium">${safe(u.level_user_label ?? u.level_user)}</span>`;
    }

    // ... renderPagination logic (keep mostly same, just check classes) ...
    function renderPagination(pag) {
        // ... (Logic เดิม) ...
        // เพียงแค่ตรวจสอบว่า class ที่ gen ออกมาเป็น Tailwind/DaisyUI ที่ถูกต้อง
        const cur = pag.current_page || 1;
        const last = pag.last_page || 1;
        const prev = pag.prev_page_url;
        const next = pag.next_page_url;

        let html =
            `<div class="join shadow-sm">
            <a class="join-item btn btn-sm ${!prev ? 'btn-disabled opacity-50' : ''}" ${prev ? `href="${prev}"` : ''}>«</a>`;

        const pages = calcWindow(cur, last);
        pages.forEach(p => {
            if (p === '...') html += `<button class="join-item btn btn-sm btn-disabled">...</button>`;
            else {
                const url = new URL(pag.path + '?page=' + p, window.location.origin);
                new URLSearchParams(window.location.search).forEach((v, k) => {
                    if (k !== 'page') url.searchParams.set(k, v);
                });
                html +=
                    `<a class="join-item btn btn-sm ${p === cur ? 'btn-active btn-primary text-white' : ''}" href="${url}">${p}</a>`;
            }
        });
        html +=
            `<a class="join-item btn btn-sm ${!next ? 'btn-disabled opacity-50' : ''}" ${next ? `href="${next}"` : ''}>»</a></div>`;

        // Per page dropdown
        const currentPer = new URLSearchParams(window.location.search).get('per_page') || 50;
        html += `
        <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
            <span>แสดง</span>
            <select id="per-page" class="select select-bordered select-xs h-8">
                <option value="10" ${currentPer == 10 ? 'selected' : ''}>10</option>
                <option value="20" ${currentPer == 20 ? 'selected' : ''}>20</option>
                <option value="50" ${currentPer == 50 ? 'selected' : ''}>50</option>
                <option value="100" ${currentPer == 100 ? 'selected' : ''}>100</option>
            </select>
            <span>รายการ</span>
        </div>`;

        pagWrap.innerHTML = html;
        const perSel = document.getElementById('per-page');
        if (perSel) perSel.addEventListener('change', () => {
            const sp = new URLSearchParams(window.location.search);
            sp.set('per_page', perSel.value);
            sp.delete('page');
            fetchUsers(sp);
        });
    }

    function calcWindow(cur, last) {
        const arr = [];
        if (last <= 7) {
            for (let i = 1; i <= last; i++) arr.push(i);
            return arr;
        }
        arr.push(1);
        if (cur > 3) arr.push('...');
        const start = Math.max(2, cur - 1);
        const end = Math.min(last - 1, cur + 1);
        for (let i = start; i <= end; i++) arr.push(i);
        if (cur < last - 2) arr.push('...');
        arr.push(last);
        return arr;
    }
});
</script>
@endsection