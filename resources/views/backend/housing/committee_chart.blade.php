@extends('layouts.housing.apphousing')

@section('title', 'ผังกรรมการบ้านพักพนักงาน')

@push('styles')
    <style>
        /* Premium Branched Org Chart Styles */
        .tree-wrapper {
            width: 100%;
            overflow-x: auto;
            padding: 60px 0;
            background: radial-gradient(circle at 50% 50%, #fcfcfc 0%, #f7f9fc 100%);
            border-radius: 40px;
            border: 1px solid #f1f5f9;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }

        .tree {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 80px;
            min-width: fit-content;
            padding: 0 40px;
        }

        .level-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            position: relative;
            width: 100%;
        }

        /* Vertical line coming out of the bottom of parent content */
        .parent-line {
            position: absolute;
            bottom: -40px;
            left: 50%;
            width: 2px;
            height: 40px;
            background: #cbd5e1;
            transform: translateX(-50%);
        }

        /* Horizontal line for siblings */
        .sibling-connector {
            position: absolute;
            top: -40px;
            left: 0;
            right: 0;
            height: 2px;
            background: #cbd5e1;
            z-index: 0;
        }

        /* Top vertical line connecting content to sibling line */
        .child-line {
            position: absolute;
            top: -40px;
            left: 50%;
            width: 2px;
            height: 40px;
            background: #cbd5e1;
            transform: translateX(-50%);
        }

        .level-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .role-header {
            position: absolute;
            top: -55px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            white-space: nowrap;
        }

         .node-card {
            background: white;
            border-radius: 32px;
            padding: 32px 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.01);
            border: 1px solid rgba(241, 245, 249, 0.8);
            width: 280px;
            text-align: center;
            position: relative;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            z-index: 10;
        }

        .node-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.1);
            border-color: rgba(226, 232, 240, 0.5);
        }

        .node-avatar-container {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            z-index: 2;
        }

        .node-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            padding: 4px;
            background: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            object-fit: cover;
        }

        .node-avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 32px;
        }

        .node-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .node-dept {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .node-actions {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            gap: 12px;
            opacity: 1; /* Keep visible as in image */
        }

        .node-action-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 12px;
        }

        .btn-edit { background: #eff6ff; color: #2563eb; }
        .btn-edit:hover { background: #dbeafe; transform: scale(1.1); }
        .btn-delete { background: #fef2f2; color: #dc2626; }
        .btn-delete:hover { background: #fee2e2; transform: scale(1.1); }

        .role-badge {
            background: #0f172a;
            color: white;
            padding: 8px 20px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border: 1px solid #1e293b;
        }

        .level-add-btn {
            background: #10b981;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: -12px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 30;
        }

        .level-add-btn:hover {
            transform: scale(1.2) rotate(90deg);
            background: #059669;
        }

        /* Adjust sibling lines logic */
        .level-row .level-container:first-child .sibling-connector {
            left: 50%;
        }

        .level-row .level-container:last-child .sibling-connector {
            right: 50%;
        }

        .level-row .level-container:only-child .sibling-connector {
            display: none;
        }

        /* Mobile specific adjustments */
        @media (max-width: 768px) {
            .tree { gap: 100px; }
            .node-card { width: 240px; }
            .level-row { flex-direction: column; align-items: center; gap: 60px; }
            .sibling-connector { display: none !important; }
            .child-line { height: 40px; top: -40px; }
            .parent-line { height: 40px; bottom: -40px; }
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 mt-4">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center shadow-inner">
                    <i class="fa-solid fa-sitemap text-2xl text-red-600"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">
                        โครงสร้างกรรมการบ้านพักพนักงาน
                    </h1>
                    <p class="text-slate-500 font-medium">แผนผังอำนาจการบริหารจัดการและตรวจสอบบ้านพักพนักงาน Kumwell</p>
                </div>
            </div>
            @if($isHams)
                <div class="mt-6 md:mt-0">
                    <button onclick="document.getElementById('modal_add').showModal()"
                        class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-2xl text-sm font-bold shadow-2xl shadow-red-200 transition-all flex items-center justify-center gap-3 active:scale-95">
                        <i class="fa-solid fa-circle-plus text-lg"></i>
                        เพิ่มโครงสร้าง
                    </button>
                </div>
            @endif
        </div>

        @php
            $groupedCommittees = $committees->groupBy('role')->sortBy(function ($group) {
                return $group->min('order');
            });
        @endphp

        <div class="tree-wrapper shadow-2xl shadow-slate-200/50">
            <div class="tree">
                @if($groupedCommittees->count() > 0)
                    @foreach($groupedCommittees as $role => $members)
                        <div class="level-row">
                            @foreach($members as $m)
                                <div class="level-container">
                                    {{-- Lines --}}
                                    @if(!$loop->parent->first)
                                        <div class="sibling-connector"></div>
                                        <div class="child-line"></div>
                                    @endif

                                    @if(!$loop->parent->last)
                                        <div class="parent-line"></div>
                                    @endif

                                    {{-- Role Label (Header for the group) --}}
                                    @if($loop->first)
                                        <div class="role-header flex items-center">
                                            <span class="role-badge">
                                                {{ $role }}
                                            </span>
                                            @if($isHams)
                                                <button onclick="openAddModal('{{ $role }}')"
                                                    class="level-add-btn"
                                                    title="เพิ่มในตำแหน่งนี้">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Node Card --}}
                                    @php $user = $m->user; @endphp
                                    <div class="node-card">
                                        <div class="node-avatar-container">
                                            @if($user)
                                                @php
                                                    $avatarColor = $loop->parent->index % 2 == 0 ? 'amber' : 'red';
                                                    $bgClass = $avatarColor == 'red' ? 'bg-red-500' : 'bg-amber-500';
                                                @endphp
                                                <div class="absolute inset-0 {{ $bgClass }} opacity-10 rounded-full scale-110"></div>
                                                <img src="{{ $user->photo_user ? asset($user->photo_user) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fullname) . '&background=' . ($avatarColor == 'red' ? 'dc2626' : 'f59e0b') . '&color=fff' }}"
                                                    class="node-avatar relative" alt="Avatar">
                                            @else
                                                <div class="node-avatar-placeholder">
                                                    <i class="fa-solid fa-user-slash"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="space-y-1">
                                            <h3 class="node-name">
                                                {{ $user ? $user->fullname : 'ไม่พบข้อมูลพนักงาน' }}
                                            </h3>
                                            <p class="node-dept">
                                                {{ $user ? (optional($user->department)->name ?? '-') : 'ID: ' . $m->user_id }}
                                            </p>
                                        </div>

                                        @if($isHams)
                                            <div class="node-actions pt-6 border-t border-slate-50 mt-4">
                                                <button onclick='editNode(@json($m))'
                                                    class="node-action-btn btn-edit shadow-sm">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </button>
                                                <form action="{{ route('housing.committee.destroy', $m->id) }}" method="POST"
                                                    onsubmit="return confirm('ยืนยันการลบพนักงานท่านี้ออกจากตำแหน่งผัง?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="node-action-btn btn-delete shadow-sm">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div class="py-32 text-center w-full">
                        <div
                            class="w-32 h-32 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                            <i class="fa-solid fa-sitemap text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800">ยังไม่มีข้อมูลโครงสร้าง</h3>
                        <p class="text-slate-500 mt-3 font-medium">เริ่มสร้างผังองค์กรโดยกดปุ่ม "เพิ่มโครงสร้าง" ด้านบน</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Modals --}}
    <dialog id="modal_add" class="modal">
        <div class="modal-box bg-white/95 backdrop-blur-xl rounded-[40px] p-10 max-w-md shadow-2xl border border-white">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
                <h3 class="font-black text-2xl text-slate-800">เพิ่มข้อมูลโครงสร้าง</h3>
            </div>
            
            <form action="{{ route('housing.committee.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">เลือกพนักงาน</span>
                        </label>
                        <select name="user_id" class="select select-bordered w-full rounded-2xl select2-user h-12" required>
                            <option value="">-- ค้นหารายชื่อ --</option>
                            @foreach($users as $u)
                                @if($u->role === 'admin' || $u->emp_code === 'SYS-001') @continue @endif
                                <option value="{{ $u->id }}">{{ $u->fullname }} ({{ $u->emp_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">ตำแหน่ง / สายงาน</span>
                        </label>
                        <select id="role_select" name="role" class="select select-bordered w-full rounded-2xl h-12"
                            onchange="checkNewRole(this)" required>
                            @php
                                $roles = ['หัวหน้าบ้านพัก', 'ผู้ช่วยหัวหน้าบ้านพัก', 'กรรมการบ้านพัก', 'เลขาพนักงาน'];
                            @endphp
                            @foreach($roles as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                            <option value="new">-- เพิ่มตำแหน่งใหม่ --</option>
                        </select>
                        <input type="text" id="role_input" placeholder="ระบุตำแหน่งใหม่"
                            class="input input-bordered w-full mt-3 hidden rounded-2xl h-12">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">ลำดับขั้น (1=สูงสุด)</span>
                        </label>
                        <input type="number" name="order" id="add_order" value="1"
                            class="input input-bordered w-full rounded-2xl h-12" required>
                    </div>
                </div>
                
                <div class="modal-action grid grid-cols-2 gap-4 mt-10">
                    <button type="button" onclick="modal_add.close()"
                        class="btn btn-ghost rounded-2xl font-bold text-slate-400 normal-case h-14">ยกเลิก</button>
                    <button type="submit"
                        class="btn bg-red-600 hover:bg-red-700 border-none text-white rounded-2xl font-bold shadow-xl shadow-red-100 normal-case h-14">
                        บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/60 backdrop-blur-md"><button>close</button></form>
    </dialog>

    <dialog id="modal_edit" class="modal">
        <div class="modal-box bg-white/95 backdrop-blur-xl rounded-[40px] p-10 max-w-md shadow-2xl border border-white">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-user-pen text-xl"></i>
                </div>
                <h3 class="font-black text-2xl text-slate-800">แก้ไขข้อมูลผัง</h3>
            </div>
            
            <form id="edit_form" method="POST">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">พนักงาน</span>
                        </label>
                        <select name="user_id" id="edit_user_id" class="select select-bordered w-full rounded-2xl select2-user-edit h-12" required>
                            @foreach($users as $u)
                                @if($u->role === 'admin' || $u->emp_code === 'SYS-001') @continue @endif
                                <option value="{{ $u->id }}">{{ $u->fullname }} ({{ $u->emp_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">ตำแหน่ง / สายงาน</span>
                        </label>
                        <select id="edit_role_select" name="role" class="select select-bordered w-full rounded-2xl h-12"
                            onchange="checkNewRoleEdit(this)" required>
                            @php
                                $roles = ['หัวหน้าบ้านพัก', 'ผู้ช่วยหัวหน้าบ้านพัก', 'กรรมการบ้านพัก', 'เลขาพนักงาน'];
                            @endphp
                            @foreach($roles as $r)
                                <option value="{{ $r }}">{{ $r }}</option>
                            @endforeach
                            <option value="new">-- เพิ่มตำแหน่งใหม่ --</option>
                        </select>
                        <input type="text" id="edit_role_input" placeholder="ระบุตำแหน่งใหม่"
                            class="input input-bordered w-full mt-3 hidden rounded-2xl h-12">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold text-slate-500 uppercase tracking-widest text-[11px]">ลำดับขั้น</span>
                        </label>
                        <input type="number" name="order" id="edit_order" class="input input-bordered w-full rounded-2xl h-12" required>
                    </div>
                </div>
                
                <div class="modal-action grid grid-cols-2 gap-4 mt-10">
                    <button type="button" onclick="modal_edit.close()"
                        class="btn btn-ghost rounded-2xl font-bold text-slate-400 normal-case h-14">ยกเลิก</button>
                    <button type="submit"
                        class="btn bg-blue-600 hover:bg-blue-700 border-none text-white rounded-2xl font-bold shadow-xl shadow-blue-100 normal-case h-14">
                        อัปเดตข้อมูล
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop bg-slate-900/60 backdrop-blur-md"><button>close</button></form>
    </dialog>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2-user').select2({
                dropdownParent: $('#modal_add'),
                width: '100%'
            });
            $('.select2-user-edit').select2({
                dropdownParent: $('#modal_edit'),
                width: '100%'
            });
        });

        function openAddModal(role = null) {
            if (role) {
                $('#role_select').val(role);
                // Detect order based on existing levels
                const levelRows = document.querySelectorAll('.level-row');
                let order = 1;
                levelRows.forEach((row, idx) => {
                    if (row.querySelector('.role-header span')?.innerText === role) order = idx + 1;
                });
                $('#add_order').val(order);
            }
            document.getElementById('modal_add').showModal();
        }

        function checkNewRole(select) {
            const input = document.getElementById('role_input');
            if (select.value === 'new') {
                input.classList.remove('hidden');
                input.name = 'role';
                select.name = '';
                input.focus();
            } else {
                input.classList.add('hidden');
                input.name = '';
                select.name = 'role';
            }
        }

        function checkNewRoleEdit(select) {
            const input = document.getElementById('edit_role_input');
            if (select.value === 'new') {
                input.classList.remove('hidden');
                input.name = 'role';
                select.name = '';
                input.focus();
            } else {
                input.classList.add('hidden');
                input.name = '';
                select.name = 'role';
            }
        }

        function editNode(data) {
            let action = "{{ route('housing.committee.update', ':id') }}".replace(':id', data.id);
            $('#edit_form').attr('action', action);
            $('#edit_user_id').val(data.user_id).trigger('change');
            
            // Handle Role Selection
            const roles = ['หัวหน้าบ้านพัก', 'ผู้ช่วยหัวหน้าบ้านพัก', 'กรรมการบ้านพัก', 'เลขาพนักงาน'];
            const select = document.getElementById('edit_role_select');
            const input = document.getElementById('edit_role_input');
            
            if (roles.includes(data.role)) {
                $(select).val(data.role);
                input.classList.add('hidden');
                input.name = '';
                select.name = 'role';
            } else {
                $(select).val('new');
                input.classList.remove('hidden');
                input.value = data.role;
                input.name = 'role';
                select.name = '';
            }
            
            $('#edit_order').val(data.order);
            document.getElementById('modal_edit').showModal();
        }
    </script>
@endpush