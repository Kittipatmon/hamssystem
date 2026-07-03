@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="md:col-span-2 flex flex-col justify-center bg-white p-5 rounded border border-slate-200 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                        <i class="fa-solid fa-tags text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">ประเภทอุปกรณ์พัสดุ</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">จัดการ กำหนด และแก้ไขหมวดหมู่จัดกลุ่มของพัสดุอุปกรณ์</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Total Types -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">หมวดหมู่ทั้งหมด</div>
                    <div class="text-lg font-black text-slate-800 mt-0.5">
                        {{ number_format($items_types->count()) }} <span class="text-xs font-normal text-slate-400">ประเภท</span>
                    </div>
                </div>
            </div>

            <!-- Stats 2: Active Types -->
            @php $activeCount = $items_types->where('status', 1)->count(); @endphp
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded border border-emerald-100 flex items-center justify-center">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">เปิดใช้งานในระบบ</div>
                    <div class="text-lg font-black text-emerald-700 mt-0.5">
                        {{ number_format($activeCount) }} <span class="text-xs font-normal text-slate-400">ประเภท</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="bg-white p-4 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4 text-xs font-semibold">
            <div class="flex items-center gap-2">
                <span class="w-1.5 h-6 bg-red-600 rounded-full"></span>
                <h2 class="font-bold text-slate-700">ทะเบียนหมวดหมู่พัสดุ</h2>
            </div>
            <button id="btnOpenCreate"
                class="w-full sm:w-auto flex items-center justify-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-all cursor-pointer">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>เพิ่มประเภทพัสดุใหม่</span>
            </button>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 p-3 rounded flex items-center gap-2 text-xs">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span class="text-emerald-800 font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Master Registry Data Table (Hospital Grid Layout) -->
        <div class="bg-white rounded border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-4 overflow-x-auto">
                <table id="typeTable" class="w-full text-left border-collapse border-slate-200 text-xs">
                    <thead>
                        <tr class="bg-slate-100/70 text-slate-700 font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-16">#</th>
                            <th class="py-3 px-3 border-r border-slate-200 min-w-[200px]">ชื่อหมวดหมู่ประเภท</th>
                            <th class="py-3 px-3 border-r border-slate-200 min-w-[300px]">คำชี้แจง / รายละเอียดเพิ่มเติม</th>
                            <th class="py-3 px-3 border-r border-slate-200 text-center w-32">สถานะใช้งาน</th>
                            <th class="py-3 px-3 text-center w-28">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($items_types as $type)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3 border-r border-slate-200 text-center font-semibold text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 font-bold text-slate-800">
                                    {{ $type->name }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 text-slate-500 font-semibold leading-normal">
                                    {{ $type->description ?: '-' }}
                                </td>
                                <td class="py-3 px-3 border-r border-slate-200 text-center font-bold">
                                    <form action="{{ route('items_type.toggleStatus', $type->item_type_id) }}" method="POST"
                                        class="inline m-0 p-0">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $type->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }} text-[10px] font-bold uppercase transition-all cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $type->status ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                                            {{ $type->status ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-3 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button
                                            class="w-7 h-7 rounded border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 flex items-center justify-center transition-colors cursor-pointer"
                                            data-edit data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                            data-description="{{ $type->description ?: '' }}" title="แก้ไขข้อมูลประเภท">
                                            <i class="fa-regular fa-pen-to-square text-[10px]"></i>
                                        </button>
                                        <button
                                            class="w-7 h-7 rounded border border-slate-200 bg-slate-50 text-red-500 hover:bg-rose-50 hover:border-rose-250 flex items-center justify-center transition-colors cursor-pointer"
                                            data-delete data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                            title="ลบข้อมูลประเภท">
                                            <i class="fa-regular fa-trash-can text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="modalCreate" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-md rounded border border-slate-200 shadow-2xl overflow-hidden text-xs font-semibold">
            <div class="bg-gradient-to-r from-[#e53935] to-[#c62828] p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle text-lg"></i>
                    <h2 class="text-sm font-black uppercase tracking-wider">เพิ่มประเภทพัสดุอุปกรณ์ใหม่</h2>
                </div>
            </div>
            <form action="{{ route('items_type.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">ชื่อประเภทอุปกรณ์ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="เช่น อุปกรณ์สำนักงาน, พัสดุสิ้นเปลือง"
                        class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">รายละเอียดเพิ่มเติม</label>
                    <textarea name="description" rows="3" placeholder="ระบุรายละเอียดสังเขป..."
                        class="w-full p-3 rounded border border-slate-300 bg-white focus:border-red-600 focus:ring-1 focus:ring-red-600 outline-none transition-all font-semibold text-slate-800 leading-normal"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" data-close
                        class="px-4 py-2 rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-colors">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-md rounded border border-slate-200 shadow-2xl overflow-hidden text-xs font-semibold">
            <div class="bg-slate-800 p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                    <h2 class="text-sm font-black uppercase tracking-wider">แก้ไขข้อมูลประเภทพัสดุ</h2>
                </div>
            </div>
            <form id="formEdit" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">ชื่อประเภทอุปกรณ์ <span class="text-red-500">*</span></label>
                    <input id="editName" type="text" name="name" required
                        class="w-full h-9 px-3 rounded border border-slate-300 bg-white focus:border-slate-400 focus:ring-1 focus:ring-slate-400 outline-none transition-all font-semibold text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700">รายละเอียดเพิ่มเติม</label>
                    <textarea id="editDescription" name="description" rows="3"
                        class="w-full p-3 rounded border border-slate-300 bg-white focus:border-slate-400 focus:ring-1 focus:ring-slate-400 outline-none transition-all font-semibold text-slate-800 leading-normal"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" data-close
                        class="px-4 py-2 rounded border border-slate-300 text-slate-600 hover:bg-slate-50 transition-colors">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded shadow transition-colors">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-sm rounded border border-slate-200 shadow-2xl overflow-hidden text-xs font-semibold">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-50 text-red-600 rounded flex items-center justify-center mx-auto mb-4 border border-red-100">
                    <i class="fa-solid fa-trash-can text-2xl"></i>
                </div>
                <h3 class="text-sm font-black text-slate-850">ยืนยันลบหมวดหมู่นี้ออกจากระบบ?</h3>
                <p class="text-[11px] text-slate-450 mt-1 font-medium leading-relaxed">คุณกำลังจะลบประเภท <span id="deleteName" class="text-red-600 font-bold"></span> การกระทำนี้ไม่สามารถยกเลิกได้</p>
            </div>
            <form id="formDelete" method="POST" class="p-4 bg-slate-50 flex gap-2 border-t border-slate-100">
                @csrf @method('DELETE')
                <button type="button" data-close
                    class="flex-1 h-9 rounded bg-white text-slate-500 font-bold hover:bg-slate-100 border border-slate-200 transition-colors">ยกเลิก</button>
                <button type="submit"
                    class="flex-1 h-9 bg-red-600 hover:bg-red-700 text-white font-bold rounded shadow transition-colors">ลบข้อมูล</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        (function () {
            const openCreateBtn = document.getElementById('btnOpenCreate');
            const modalCreate = document.getElementById('modalCreate');
            const modalEdit = document.getElementById('modalEdit');
            const modalDelete = document.getElementById('modalDelete');
            const formEdit = document.getElementById('formEdit');
            const formDelete = document.getElementById('formDelete');
            const editName = document.getElementById('editName');
            const editDescription = document.getElementById('editDescription');
            const deleteName = document.getElementById('deleteName');

            function show(modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
            function hide(modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; }
            function closeAll() { [modalCreate, modalEdit, modalDelete].forEach(m => hide(m)); }

            openCreateBtn && openCreateBtn.addEventListener('click', () => show(modalCreate));

            document.querySelectorAll('[data-close], [data-close-backdrop]').forEach(btn => {
                btn.addEventListener('click', closeAll);
            });

            document.addEventListener('click', e => {
                const btn = e.target.closest('[data-edit]');
                if (btn) {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name');
                    const desc = btn.getAttribute('data-description');
                    editName.value = name || '';
                    editDescription.value = desc || '';
                    formEdit.action = `{{ url('items_type') }}/${id}`;
                    show(modalEdit);
                }

                const delBtn = e.target.closest('[data-delete]');
                if (delBtn) {
                    const id = delBtn.getAttribute('data-id');
                    const name = delBtn.getAttribute('data-name');
                    deleteName.textContent = name || '';
                    formDelete.action = `{{ url('items_type') }}/${id}`;
                    show(modalDelete);
                }
            });

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeAll();
            });
        })();
    </script>
@endpush