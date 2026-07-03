@extends('layouts.sidebar')

@section('content')
<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-6 font-noto">

        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (CLINICAL COMMAND STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border-l-4 border-l-red-600 border border-slate-300 dark:border-zinc-800 p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded mb-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                    </span>
                    <span class="text-[10px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest">EMPLOYEE DATABASE CENTER</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    ระบบทะเบียนข้อมูลพนักงาน (Employee Registry)
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    สืบค้น ตรวจสอบข้อมูลสังกัด แผนก สิทธิ์การรายงาน และสถานะการเปิดบัญชีเข้าใช้งานส่วนกลาง
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-slate-50 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-5 py-3 rounded min-w-[160px]">
                    <div class="flex-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">TOTAL EMPLOYEES</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold text-slate-900 dark:text-white leading-none">
                                {{ $users->total() }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">คน</span>
                        </div>
                    </div>
                </div>

                <button type="button" id="toggle-filter"
                    class="bg-white hover:bg-slate-50 dark:bg-zinc-900 dark:hover:bg-zinc-850 text-slate-700 dark:text-zinc-300 px-5 py-3 rounded border border-slate-300 dark:border-zinc-700 text-sm font-bold shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-filter text-amber-500"></i>
                    <span>ตัวกรองการสืบค้น</span>
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded text-red-800 dark:text-red-400 text-sm font-semibold">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ════════════════════════════════════════════════════════════════
             FILTER PANEL (CLINICAL STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <form id="filter-form" method="GET" action="{{ route('users.index') }}"
            class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 p-6 hidden space-y-4 shadow-sm transition-all duration-300">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">รหัสพนักงาน</label>
                    <input type="text" name="emp_code" placeholder="ค้นหาตามรหัส..." value="{{ request('emp_code') }}"
                        class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">ชื่อ-นามสกุล</label>
                    <input type="text" name="fullname" placeholder="ค้นหาชื่อ..." value="{{ request('fullname') }}"
                        class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">ตำแหน่ง</label>
                    <input type="text" name="position" placeholder="ค้นหาตำแหน่ง..." value="{{ request('position') }}"
                        class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">ประเภทพนักงาน</label>
                    <select name="employee_type" class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                        <option value="">ทั้งหมด</option>
                        <option value="รายเดือน" {{ request('employee_type') === 'รายเดือน' ? 'selected' : '' }}>รายเดือน</option>
                        <option value="รายวัน" {{ request('employee_type') === 'รายวัน' ? 'selected' : '' }}>รายวัน</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">สถานะ Active</label>
                    @php $statusOptions = \App\Models\User::getStatusOptions(); @endphp
                    <select name="status" class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                        <option value="">ทั้งหมด</option>
                        @foreach($statusOptions as $value => $option)
                            @php $label = is_array($option) ? ($option['label'] ?? '') : $option; @endphp
                            <option value="{{ $value }}" {{ (string) request('status') === (string) $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">แผนก</label>
                    <select name="department" class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                        <option value="">แผนกทั้งหมด</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ (string) request('department') === (string) $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">ระดับสิทธิ์</label>
                    @php
                        $roleOptions = \App\Models\User::getRoleOptions();
                        $selectedRole = request('role');
                    @endphp
                    <select name="role" class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                        <option value="">ระดับสิทธิ์ทั้งหมด</option>
                        @foreach($roleOptions as $value => $meta)
                            <option value="{{ $value }}" {{ (string) $selectedRole === (string) $value ? 'selected' : '' }}>
                                {{ $meta['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-500 dark:text-zinc-400 uppercase">สถานะ HAMS</label>
                    @php
                        $hamsStatusOptions = \App\Models\User::getHamsStatusOptions();
                        $selectedHamsStatus = request('hr_status');
                    @endphp
                    <select name="hr_status" class="w-full px-3 py-2 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white outline-none text-slate-900 dark:text-white font-medium">
                        <option value="">สถานะ HAMS ทั้งหมด</option>
                        @foreach($hamsStatusOptions as $value => $option)
                            @php $label = is_array($option) ? ($option['label'] ?? '') : $option; @endphp
                            <option value="{{ $value }}" {{ (string) $selectedHamsStatus === (string) $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end justify-end">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 border border-slate-300 dark:border-zinc-700 rounded text-xs font-bold text-slate-600 dark:text-zinc-400 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                        <i class="fa-solid fa-rotate-left mr-1"></i> ล้างค่าทั้งหมด
                    </a>
                </div>
            </div>
        </form>

        {{-- ════════════════════════════════════════════════════════════════
             DATA TABLE (CLINICAL/LEDGER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden relative"
            id="table-wrap" data-show-pattern="{{ url('users') }}/:id">

            <div id="loader" class="hidden absolute inset-0 bg-white/60 dark:bg-zinc-900/60 backdrop-blur-sm flex items-center justify-center z-20">
                <div class="flex flex-col items-center gap-3">
                    <span class="loading loading-spinner loading-lg text-red-600"></span>
                    <span class="text-red-600 font-bold text-xs animate-pulse">กำลังสืบค้นข้อมูลพนักงาน...</span>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-300 dark:border-zinc-700 px-6 py-3 flex items-center justify-between text-xs font-bold text-slate-500 dark:text-zinc-400">
                <div>
                    แสดงผลรายการลำดับที่ {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} (ทั้งหมด {{ $users->total() }} รายการ)
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-850 dark:bg-zinc-850 bg-slate-800 text-white border-b border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-32 text-center border-r border-slate-700 dark:border-zinc-700">รหัสพนักงาน</th>
                            <th class="py-3 px-6 text-xs font-bold uppercase tracking-wider border-r border-slate-700 dark:border-zinc-700">ชื่อ-นามสกุล / สังกัด</th>
                            <th class="py-3 px-6 text-xs font-bold uppercase tracking-wider border-r border-slate-700 dark:border-zinc-700">หน่วยงาน (แผนก/ฝ่าย)</th>
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-24 text-center border-r border-slate-700 dark:border-zinc-700">ระดับ</th>
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-36 text-center border-r border-slate-700 dark:border-zinc-700">สถานะ HAMS</th>
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-40 text-center border-r border-slate-700 dark:border-zinc-700">สิทธิ์รายงาน</th>
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-32 text-center border-r border-slate-700 dark:border-zinc-700">สถานะ Active</th>
                            <th class="py-3 px-6 text-xs font-bold uppercase tracking-wider w-24 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="users-body" class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @forelse($users as $user)
                            <tr class="odd:bg-white even:bg-slate-50/50 dark:odd:bg-zinc-900 dark:even:bg-zinc-900/40 hover:bg-red-50/10 dark:hover:bg-red-950/5 transition-colors">
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="font-mono text-xs font-bold bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 px-2.5 py-1 rounded text-red-600">
                                        {{ $user->emp_code }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $user->fullname }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $user->prefix }}</div>
                                </td>
                                <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-xs font-medium text-slate-700 dark:text-zinc-300">
                                            <i class="fa-solid fa-building-user mr-1 opacity-50"></i> {{ $user->department->name ?? '-' }}
                                        </span>
                                        @if(($user->division->division_name ?? '-') !== '-' || ($user->section->section_code ?? '-') !== '-')
                                            <span class="text-[10px] text-slate-400">
                                                {{ $user->division->division_name ?? '-' }} / {{ $user->section->section_code ?? '-' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-{{ $user->level_user_color }}/10 text-{{ $user->level_user_color }} border border-{{ $user->level_user_color }}/20 text-[10px] font-bold uppercase">
                                        {{ $user->level_user_label }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-{{ $user->hams_status_color }}/10 text-{{ $user->hams_status_color }} border border-{{ $user->hams_status_color }}/20 text-[10px] font-bold uppercase">
                                        {!! $user->hams_status_icon !!} <span class="ml-1">{{ $user->hams_status_label }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    @php $isEditor = $user->is_hams_editor; @endphp
                                    <button type="button" onclick="toggleHamsEditor({{ $user->id }}, this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded border transition-all duration-150 text-xs font-bold {{ $isEditor ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900' : 'bg-slate-50 text-slate-600 border-slate-300 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700' }}">
                                        <i class="fa-solid {{ $isEditor ? 'fa-check-circle' : 'fa-circle-plus opacity-50' }} text-[10px]"></i>
                                        <span>HAMS Editor</span>
                                    </button>
                                    @if ($isEditor && $user->hamsPermissionLatestLog?->grantedBy)
                                        <div class="text-[9px] text-emerald-600 mt-1 font-bold opacity-80 grantor-info">
                                            <i class="fa-solid fa-user-check mr-1"></i>โดย: {{ $user->hamsPermissionLatestLog->grantedBy->fullname }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-{{ $user->status_color }}/10 text-{{ $user->status_color }} border border-{{ $user->status_color }}/20 text-[10px] font-bold uppercase">
                                        {!! $user->status_icon !!} <span class="ml-1">{{ $user->status_label }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="w-8 h-8 flex items-center justify-center rounded border border-slate-300 dark:border-zinc-700 bg-white hover:bg-blue-50 hover:text-blue-700 hover:border-blue-300 dark:bg-zinc-800 dark:hover:bg-blue-950/30 dark:hover:border-blue-800 text-slate-600 dark:text-zinc-400 transition-colors"
                                        title="ดูข้อมูลรายละเอียด">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900/40 italic font-bold">
                                    <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                                    ไม่พบข้อมูลใดๆ ในระบบ
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- Filter Toggle ---
        const btn = document.getElementById('toggle-filter');
        const panel = document.getElementById('filter-form');
        if (btn && panel) {
            btn.addEventListener('click', function () {
                panel.classList.toggle('hidden');
            });
        }

        // Submit form automatically on select change
        const form = document.getElementById('filter-form');
        if (form) {
            form.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', () => {
                    form.submit();
                });
            });
        }

        window.toggleHamsEditor = async function (userId, btn) {
            if (typeof Swal !== 'undefined') {
                const result = await Swal.fire({
                    title: 'ยืนยันการเปลี่ยนแปลงสิทธิ์?',
                    text: 'คุณต้องการปรับปรุงสิทธิ์ HAMS Editor สำหรับพนักงานท่านนี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, เปลี่ยนแปลง',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                });
                if (!result.isConfirmed) return;
            } else {
                if (!confirm('ยืนยันการเปลี่ยนแปลงสิทธิ์ HAMS Editor?')) return;
            }

            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="loading loading-spinner loading-xs"></span>';

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch(`/users/${userId}/toggle-hams-editor`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();

                if (data.success) {
                    if (data.is_hams_editor) {
                        btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded border transition-all duration-150 text-xs font-bold bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900';
                        btn.innerHTML = '<i class="fa-solid fa-check-circle text-[10px]"></i><span>HAMS Editor</span>';

                        let grantorDiv = btn.parentElement.querySelector('.grantor-info');
                        if (!grantorDiv) {
                            grantorDiv = document.createElement('div');
                            grantorDiv.className = 'text-[9px] text-emerald-600 mt-1 font-bold opacity-80 grantor-info';
                            btn.parentElement.appendChild(grantorDiv);
                        }
                        grantorDiv.innerHTML = `<i class="fa-solid fa-user-check mr-1"></i>โดย: ${data.grantor_name}`;
                    } else {
                        btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded border transition-all duration-150 text-xs font-bold bg-slate-50 text-slate-600 border-slate-300 dark:bg-zinc-800 dark:text-zinc-400 dark:border-zinc-700';
                        btn.innerHTML = '<i class="fa-solid fa-circle-plus opacity-50 text-[10px]"></i><span>HAMS Editor</span>';

                        let grantorDiv = btn.parentElement.querySelector('.grantor-info');
                        if (grantorDiv) grantorDiv.remove();
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            } catch (err) {
                console.error(err);
                btn.innerHTML = originalContent;
                alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            } finally {
                btn.disabled = false;
            }
        }
    });
</script>
@endsection