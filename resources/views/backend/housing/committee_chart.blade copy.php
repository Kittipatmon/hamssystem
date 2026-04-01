@extends('layouts.housing.apphousing')

@section('title', 'ผังกรรมการบ้านพักพนักงาน')

@push('styles')
<style>
    /* Premium Org Chart Styles */
    .tree-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 20px;
    }

    /* Premium Stepped Org Chart Styles */
    .tree-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 60px 40px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .level-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        padding-left: 60px;
        margin-bottom: 60px;
        width: 100%;
    }

    /* Vertical line for the ladder */
    .level-group::before {
        content: '';
        position: absolute;
        left: 10px;
        top: -40px;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #e2e8f0, #cbd5e1);
        border-radius: 10px;
    }

    /* Horizontal step line */
    .level-group::after {
        content: '';
        position: absolute;
        left: 10px;
        top: 20px;
        width: 50px;
        height: 3px;
        background: #cbd5e1;
        border-radius: 10px;
    }

    .level-group:first-child::before {
        top: 20px;
    }

    .level-group:last-child::before {
        bottom: calc(100% - 20px);
    }

    .node-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        width: 280px;
        text-align: center;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10;
        overflow: hidden;
    }

    .node-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #e2e8f0;
    }

    .node-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin: 0 auto 12px;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        object-fit: cover;
    }

    .node-role-badge {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #1e293b;
        margin-bottom: 8px;
        padding: 4px 12px;
        background: #f8fafc;
        display: inline-block;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
    }

    .node-name {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .node-dept {
        font-size: 11px;
        color: #64748b;
    }

    .node-actions {
        margin-top: 15px;
        display: flex;
        justify-content: center;
        gap: 8px;
        transform: translateY(10px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .node-card:hover .node-actions {
        transform: translateY(0);
        opacity: 1;
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

    .btn-level-control:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto">

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
                <button onclick="openAddModal()" class="mt-4 md:mt-0 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-red-200 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> เพิ่มโครงสร้าง
                </button>
            </div>
        @endif
    </div>

    @php
        // Group committees by role and sort them by the minimum 'order' within the group
        $groupedCommittees = $committees->groupBy('role')->sortBy(function($group) {
            return $group->min('order');
        });
    @endphp

    <div class="tree-container">
        
        @if($groupedCommittees->count() > 0)
            <div class="flex flex-col w-full items-start">
                @foreach($groupedCommittees as $role => $members)
                    <div class="level-group" style="padding-left: calc(60px + {{ $loop->index * 40 }}px);">
                        {{-- Custom Step Connector for Stepped Layout --}}
                        <div class="absolute" style="left: calc(10px + {{ ($loop->index-1) * 40 }}px); top: -40px; bottom: 0; width: 3px; background: #cbd5e1; z-index: 0; {{ $loop->first ? 'display:none;' : '' }}"></div>
                        <div class="absolute" style="left: calc(10px + {{ ($loop->index-1) * 40 }}px); top: 20px; width: calc(50px + 40px); height: 3px; background: #cbd5e1; z-index: 0; border-radius: 0 0 0 10px; {{ $loop->first ? 'display:none;' : '' }}"></div>

                        {{-- Role Badge & Controls --}}
                        <div class="flex items-center gap-4 mb-6 z-10 relative">
                            <span class="px-5 py-2 bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wider rounded-xl shadow-lg border border-slate-700">
                                {{ $role }}
                            </span>
                            @if($isHams)
                                <div class="flex gap-1.5 p-1 bg-white rounded-xl shadow-sm border border-slate-100">
                                    <button onclick="openAddModal('{{ $role }}')" class="btn-level-control bg-emerald-500 text-white hover:bg-emerald-600" title="เพิ่มกรรมการในสายนี้">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    @if($members->count() > 0)
                                        <form action="{{ route('housing.committee.destroy', $members->last()->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบรายชื่อล่าสุดในสายงานนี้?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-level-control bg-rose-500 text-white hover:bg-rose-600" title="ลบรายชื่อล่าสุดในสายนี้">
                                                <i class="fa-solid fa-minus"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Members in this level --}}
                        <div class="flex flex-wrap gap-6 z-10 relative">
                            @foreach($members as $m)
                                <div class="node-card border-t-4 {{ $loop->parent->first ? 'border-red-600' : 'border-amber-500' }}">
                                    <div class="absolute top-2 right-2 px-2 py-0.5 rounded-full bg-slate-50 text-[9px] font-bold text-slate-400">#{{ $m->order }}</div>
                                    <img src="{{ $m->user->photo_user ? asset($m->user->photo_user) : 'https://ui-avatars.com/api/?name='.urlencode($m->user->fullname).'&background=' . ($loop->parent->first ? 'dc2626' : 'f59e0b') . '&color=fff' }}" 
                                         class="node-avatar" alt="Avatar">
                                    <h3 class="node-name text-sm">{{ $m->user->fullname }}</h3>
                                    <p class="node-dept text-[10px]">{{ $m->user->department->department_name ?? '-' }} ({{ $m->user->employee_code }})</p>
                                    
                                    @if($isHams)
                                        <div class="node-actions pt-4 mt-4 border-t border-slate-50">
                                            <button onclick="editNode({{ $m }})" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-all hover:rotate-12">
                                                <i class="fa-solid fa-pen text-[10px]"></i>
                                            </button>
                                            <form action="{{ route('housing.committee.destroy', $m->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-all hover:-rotate-12">
                                                    <i class="fa-solid fa-trash text-[10px]"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-20 text-center w-full">
                <div class="w-24 h-24 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-sitemap text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">ยังไม่มีข้อมูลโครงสร้าง</h3>
                <p class="text-slate-500 mt-2">กรุณากดปุ่ม "เพิ่มโครงสร้าง" เพื่อเริ่มต้นสร้างผังองค์กรแบบขั้นบันได</p>
            </div>
        @endif

    </div>

</div>

{{-- Add Modal --}}
<dialog id="modal_add" class="modal">
    <div class="modal-box bg-white rounded-3xl p-8 shadow-2xl border border-slate-100 max-w-md">
        <h3 class="font-black text-2xl text-slate-800 mb-6 flex items-center gap-3">
            <i class="fa-solid fa-user-plus text-red-600"></i>
            เพิ่มข้อมูลโครงสร้าง
        </h3>
        <form action="{{ route('housing.committee.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">เลือกรายชื่อพนักงาน</label>
                    <select name="user_id" class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500 select2-user" required>
                        <option value="">-- ค้นหารายชื่อ --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->employee_code }} - {{ $u->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">ตำแหน่งกรรมการ / สายงาน</label>
                    <div class="relative">
                        <select id="role_select" name="role" class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500" onchange="checkNewRole(this)" required>
                            <option value="หัวหน้าบ้านพัก">หัวหน้าบ้านพัก</option>
                            <option value="ผู้ช่วยหัวหน้าบ้านพัก">ผู้ช่วยหัวหน้าบ้านพัก</option>
                            <option value="กรรมการบ้านพัก">กรรมการบ้านพัก</option>
                            <option value="เลขาพนักงาน">เลขาพนักงาน</option>
                            <option value="new">-- เพิ่มตำแหน่งใหม่ --</option>
                        </select>
                        <input type="text" id="role_input" placeholder="ระบุตำแหน่งใหม่" class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm mt-2 hidden">
                    </div>
                </div>
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">ลำดับขั้น (1=สูงสุด, 2=รองลงมา...)</label>
                    <input type="number" name="order" id="add_order" value="1" class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500" required>
                </div>
            </div>
            <div class="modal-action mt-8 flex gap-3">
                <button type="button" onclick="modal_add.close()" class="flex-1 px-6 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">ยกเลิก</button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-red-100 transition-all">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>

{{-- Edit Modal --}}
<dialog id="modal_edit" class="modal">
    <div class="modal-box bg-white rounded-3xl p-8 shadow-2xl border border-slate-100 max-w-md">
        <h3 class="font-black text-2xl text-slate-800 mb-6 flex items-center gap-3">
            <i class="fa-solid fa-user-pen text-blue-600"></i>
            แก้ไขข้อมูล
        </h3>
        <form id="edit_form" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">พนักงาน</label>
                    <select name="user_id" id="edit_user_id" class="select select-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500" required>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->employee_code }} - {{ $u->fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">ตำแหน่ง</label>
                    <input type="text" name="role" id="edit_role" class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500" required>
                </div>
                <div>
                    <label class="text-[13px] font-bold text-slate-600 mb-2 block">ลำดับการแสดงผล</label>
                    <input type="number" name="order" id="edit_order" class="input input-bordered w-full rounded-2xl bg-slate-50 border-slate-200 text-sm focus:border-red-500" required>
                </div>
            </div>
            <div class="modal-action mt-8 flex gap-3">
                <button type="button" onclick="modal_edit.close()" class="flex-1 px-6 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-100 transition-colors">ยกเลิก</button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-blue-100 transition-all">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-slate-900/40 backdrop-blur-sm">
        <button>close</button>
    </form>
</dialog>

@endsection

@push('scripts')
<script>
    function openAddModal(role = null) {
        if (role) {
            $('#role_select').val(role);
            // Auto-detect order for this role or set next
            const levelIndex = Array.from(document.querySelectorAll('.level-group span')).findIndex(el => el.innerText.trim() === role);
            $('#add_order').val(levelIndex !== -1 ? levelIndex + 1 : 1);
        }
        document.getElementById('modal_add').showModal();
        $('.select2-user').select2({
            dropdownParent: $('#modal_add'),
            width: '100%'
        });
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
        let action = "{{ route('housing.committee.update', ':id') }}";
        action = action.replace(':id', data.id);
        $('#edit_form').attr('action', action);
        $('#edit_user_id').val(data.user_id);
        $('#edit_role').val(data.role);
        $('#edit_order').val(data.order);
        document.getElementById('modal_edit').showModal();
    }
</script>
@endpush
