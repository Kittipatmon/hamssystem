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
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        width: 260px;
        text-align: center;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        z-index: 10;
    }

    .node-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
        border-color: #e2e8f0;
    }

    .node-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        margin: 0 auto 12px;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        object-fit: cover;
    }

    .node-name {
        font-size: 15px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .node-dept {
        font-size: 11px;
        color: #94a3b8;
    }

    .btn-level-control {
        width: 32px;
        height: 32px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.2s ease;
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
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-3xl font-black text-slate-800 flex items-center gap-3">
                <i class="fa-solid fa-sitemap text-red-600"></i>
                โครงสร้างกรรมการบ้านพักพนักงาน
            </h1>
            <p class="text-slate-500 mt-2">แผนผังอำนาจการบริหารจัดการและตรวจสอบบ้านพักพนักงาน Kumwell</p>
        </div>
        @if($isHams)
            <div class="flex gap-3">
                <button onclick="document.getElementById('modal_add').showModal()" class="mt-4 md:mt-0 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-red-200 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> เพิ่มโครงสร้าง
                </button>
            </div>
        @endif
    </div>

    @php
        $groupedCommittees = $committees->groupBy('role')->sortBy(function($group) {
            return $group->min('order');
        });
    @endphp

    <div class="tree-wrapper">
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
                                    <div class="role-header flex items-center gap-2">
                                        <span class="px-4 py-1.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-[2px] rounded-full shadow-lg border border-slate-700">
                                            {{ $role }}
                                        </span>
                                        @if($isHams)
                                            <button onclick="openAddModal('{{ $role }}')" class="btn-level-control bg-emerald-500 text-white shadow-lg hover:rotate-90 transition-transform" title="เพิ่มในสายนี้">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                {{-- Node Card --}}
                                <div class="node-card border-t-4 {{ $loop->parent->first ? 'border-red-600' : 'border-amber-500' }}">
                                    <img src="{{ $m->user->photo_user ? asset($m->user->photo_user) : 'https://ui-avatars.com/api/?name='.urlencode($m->user->fullname).'&background=' . ($loop->parent->first ? 'dc2626' : 'f59e0b') . '&color=fff' }}" 
                                         class="node-avatar" alt="Avatar">
                                    <h3 class="node-name">{{ $m->user->fullname }}</h3>
                                    <p class="node-dept">{{ $m->user->department->department_name ?? '-' }} ({{ $m->user->emp_code }})</p>
                                    
                                    @if($isHams)
                                        <div class="mt-4 pt-4 border-t border-slate-50 flex justify-center gap-2">
                                            <button onclick='editNode(@json($m))' class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                            </button>
                                            <form action="{{ route('housing.committee.destroy', $m->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center">
                                                    <i class="fa-solid fa-trash text-[10px]"></i>
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
                <div class="py-20 text-center w-full">
                    <div class="w-24 h-24 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-sitemap text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">ยังไม่มีข้อมูลโครงสร้าง</h3>
                    <p class="text-slate-500 mt-2">เริ่มสร้างผังองค์กรโดยกดปุ่มด้านบน</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Modals --}}
<dialog id="modal_add" class="modal">
    <div class="modal-box bg-white rounded-3xl p-8 max-w-md">
        <h3 class="font-black text-2xl text-slate-800 mb-6">เพิ่มข้อมูลโครงสร้าง</h3>
        <form action="{{ route('housing.committee.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">เลือกพนักงาน</label>
                    <select name="user_id" class="select select-bordered w-full rounded-2xl select2-user" required>
                        <option value="">-- ค้นหารายชื่อ --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->emp_code }} - {{ $u->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">ตำแหน่ง / สายงาน</label>
                    <select id="role_select" name="role" class="select select-bordered w-full rounded-2xl" onchange="checkNewRole(this)" required>
                        @php
                            $roles = ['หัวหน้าบ้านพัก', 'ผู้ช่วยหัวหน้าบ้านพัก', 'กรรมการบ้านพัก', 'เลขาพนักงาน'];
                        @endphp
                        @foreach($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                        <option value="new">-- เพิ่มตำแหน่งใหม่ --</option>
                    </select>
                    <input type="text" id="role_input" placeholder="ระบุตำแหน่งใหม่" class="input input-bordered w-full mt-2 hidden rounded-2xl">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">ลำดับขั้น (1=สูงสุด)</label>
                    <input type="number" name="order" id="add_order" value="1" class="input input-bordered w-full rounded-2xl" required>
                </div>
            </div>
            <div class="modal-action flex gap-3 mt-8">
                <button type="button" onclick="modal_add.close()" class="flex-1 font-bold text-slate-400">ยกเลิก</button>
                <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-red-100">บันทึก</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm"><button>close</button></form>
</dialog>

<dialog id="modal_edit" class="modal">
    <div class="modal-box bg-white rounded-3xl p-8 max-w-md">
        <h3 class="font-black text-2xl text-slate-800 mb-6">แก้ไขข้อมูล</h3>
        <form id="edit_form" method="POST">
            @csrf @method('PUT')
            <div class="space-y-5">
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">พนักงาน</label>
                    <select name="user_id" id="edit_user_id" class="select select-bordered w-full rounded-2xl" required>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->emp_code }} - {{ $u->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">ตำแหน่ง</label>
                    <input type="text" name="role" id="edit_role" class="input input-bordered w-full rounded-2xl" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-500 mb-2 block uppercase tracking-wider">ลำดับขั้น</label>
                    <input type="number" name="order" id="edit_order" class="input input-bordered w-full rounded-2xl" required>
                </div>
            </div>
            <div class="modal-action flex gap-3 mt-8">
                <button type="button" onclick="modal_edit.close()" class="flex-1 font-bold text-slate-400">ยกเลิก</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-blue-100">บันทึกแก้ไข</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm"><button>close</button></form>
</dialog>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2-user').select2({
            dropdownParent: $('#modal_add'),
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

    function editNode(data) {
        let action = "{{ route('housing.committee.update', ':id') }}".replace(':id', data.id);
        $('#edit_form').attr('action', action);
        $('#edit_user_id').val(data.user_id);
        $('#edit_role').val(data.role);
        $('#edit_order').val(data.order);
        document.getElementById('modal_edit').showModal();
    }
</script>
@endpush

