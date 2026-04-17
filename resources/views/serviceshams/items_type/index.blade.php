@extends('layouts.serviceitem.appservice')
@section('content')

    <div class="max-w-[90rem] mx-auto px-4 py-8 lg:py-18 space-y-8">

        <!-- Header Section with Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Main Title & Context -->
            <div class="lg:col-span-2 flex flex-col justify-center bg-white p-6 rounded-3xl shadow-sm border border-red-50">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                        <i class="fa-solid fa-tags text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800 tracking-tight">ประเภทอุปกรณ์</h1>
                        <p class="text-sm text-slate-500 font-medium">จัดการหมวดหมู่และประเภทของพัสดุอุปกรณ์ทั้งหมด</p>
                    </div>
                </div>
            </div>

            <!-- Stats 1: Total Types -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-list-ul text-lg"></i>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">หมวดหมู่ทั้งหมด</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($items_types->count()) }} <span
                            class="text-xs font-normal text-slate-400 ml-1">ประเภท</span></div>
                </div>
            </div>

            <!-- Stats 2: Active Types -->
            @php $activeCount = $items_types->where('status', 1)->count(); @endphp
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-lg"></i>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">เปิดใช้งานอยู่</div>
                    <div class="text-2xl font-black text-emerald-600">{{ number_format($activeCount) }} <span
                            class="text-xs font-normal text-slate-400 ml-1">ประเภท</span></div>
                </div>
            </div>

            <!-- Dashboard Shortcut -->
            <a href="{{ route('requisitions.dashboard') }}"
                class="group bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-3xl shadow-lg hover:shadow-slate-200 transition-all hover:-translate-y-1 flex items-center gap-4 border border-slate-700">
                <div
                    class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-pie text-lg"></i>
                </div>
                <div>
                    <div
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-red-400 transition-colors">
                        Analytics</div>
                    <div class="text-lg font-black text-white">ดูสถิติรวม</div>
                </div>
                <i
                    class="fa-solid fa-arrow-right text-slate-500 ml-auto group-hover:text-white group-hover:translate-x-1 transition-all"></i>
            </a>
        </div>

        <!-- Toolbar: Adding -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-8 bg-red-600 rounded-full"></span>
                <h2 class="text-lg font-extrabold text-slate-700">รายชื่อประเภทอุปกรณ์</h2>
            </div>
            <button id="btnOpenCreate"
                class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl shadow-lg shadow-red-100 transition-all active:scale-95 group">
                <i class="fa-solid fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                <span>เพิ่มประเภทใหม่</span>
            </button>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                <span class="text-emerald-800 font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Content Area: Responsive Dual-View -->
        <div class="space-y-6">

            <!-- 1. Desktop View: Premium Table -->
            <div class="hidden lg:block bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-4 overflow-x-auto">
                    <table id="typeTable" class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest rounded-l-2xl text-center">
                                    #</th>
                                <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">
                                    ชื่อประเภท</th>
                                <th class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest">
                                    คำอธิบาย</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    สถานะ</th>
                                <th
                                    class="px-6 py-4 text-[12px] font-black text-slate-400 uppercase tracking-widest text-center rounded-r-2xl">
                                    การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($items_types as $type)
                                <tr class="hover:bg-red-50/20 transition-all duration-200">
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-[13px] font-bold text-slate-400">{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-[15px] font-black text-slate-800">{{ $type->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-[13px] text-slate-500 truncate max-w-[300px]">
                                            {{ $type->description ?: '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('items_type.toggleStatus', $type->item_type_id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $type->status ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} text-[11px] font-black uppercase transition-all hover:scale-105 active:scale-95">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full {{ $type->status ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                                                {{ $type->status ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button
                                                class="w-9 h-9 flex items-center justify-center bg-slate-800 hover:bg-slate-900 text-white rounded-xl transition-all shadow-md shadow-slate-100"
                                                data-edit data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                                data-description="{{ $type->description ?: '' }}" title="แก้ไข">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </button>
                                            <button
                                                class="w-9 h-9 flex items-center justify-center bg-white border border-red-100 hover:bg-red-600 hover:text-white rounded-xl text-red-500 transition-all shadow-sm"
                                                data-delete data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                                title="ลบ">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Mobile View: Card List -->
            <div class="lg:hidden grid grid-cols-1 gap-4">
                @forelse($items_types as $type)
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-lg border border-slate-100 text-center min-w-[24px]">#{{ $loop->iteration }}</span>
                            <form action="{{ route('items_type.toggleStatus', $type->item_type_id) }}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 rounded-full {{ $type->status ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }} text-[10px] font-black uppercase">
                                    {{ $type->status ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </button>
                            </form>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight leading-tight">{{ $type->name }}</h3>
                            <p class="text-[12px] text-slate-500 italic mt-1">{{ $type->description ?: 'ไม่มีคำอธิบาย' }}</p>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button data-edit data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                data-description="{{ $type->description ?: '' }}"
                                class="flex-1 h-12 flex items-center justify-center bg-slate-800 text-white font-bold rounded-2xl shadow-lg shadow-slate-100">แก้ไข</button>
                            <button data-delete data-id="{{ $type->item_type_id }}" data-name="{{ $type->name }}"
                                class="w-12 h-12 flex items-center justify-center bg-white border-2 border-red-50 text-red-500 rounded-2xl shadow-sm"><i
                                    class="fa-solid fa-trash-can"></i></button>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[2rem] p-12 shadow-sm border border-slate-100 text-center">
                        <i class="fa-solid fa-folder-open text-4xl text-slate-200 mb-4"></i>
                        <p class="text-slate-400 font-bold">ไม่พบข้อมูลประเภทอุปกรณ์</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="modalCreate" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in">
            <div class="bg-red-600 p-8 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-plus-circle text-2xl"></i>
                    <h2 class="text-xl font-black italic tracking-tighter uppercase">เพิ่มประเภทอุปกรณ์</h2>
                </div>
            </div>
            <form action="{{ route('items_type.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">ชื่อเรียกประเภท
                        *</label>
                    <input type="text" name="name" required placeholder="เช่น อุปกรณ์สำนักงาน"
                        class="w-full h-14 px-6 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-200 transition-all font-bold text-slate-700" />
                </div>
                <div class="space-y-2">
                    <label
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">รายละเอียดเพิ่มเติม</label>
                    <textarea name="description" rows="3" placeholder="ระบุรายละเอียดสั้นๆ..."
                        class="w-full p-6 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-red-50 focus:border-red-200 transition-all font-medium text-slate-600 leading-relaxed"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" data-close
                        class="order-2 sm:order-1 flex-1 h-14 rounded-2xl text-slate-400 font-bold hover:bg-slate-50 transition-colors">ยกเลิก</button>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] h-14 bg-red-600 rounded-2xl text-white font-black shadow-lg shadow-red-100 hover:bg-red-700 transition-all active:scale-95">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="modalEdit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in">
            <div class="bg-slate-800 p-8 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-pen-to-square text-2xl"></i>
                    <h2 class="text-xl font-black italic tracking-tighter uppercase">แก้ไขประเภทอุปกรณ์</h2>
                </div>
            </div>
            <form id="formEdit" method="POST" class="p-8 space-y-6">
                @csrf @method('PUT')
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">ชื่อเรียกประเภท
                        *</label>
                    <input id="editName" type="text" name="name" required
                        class="w-full h-14 px-6 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100 focus:border-slate-300 transition-all font-bold text-slate-700" />
                </div>
                <div class="space-y-2">
                    <label
                        class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">รายละเอียดเพิ่มเติม</label>
                    <textarea id="editDescription" name="description" rows="3"
                        class="w-full p-6 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-slate-100 focus:border-slate-300 transition-all font-medium text-slate-600 leading-relaxed"></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="button" data-close
                        class="order-2 sm:order-1 flex-1 h-14 rounded-2xl text-slate-400 font-bold hover:bg-slate-50 transition-colors">ยกเลิก</button>
                    <button type="submit"
                        class="order-1 sm:order-2 flex-[2] h-14 bg-slate-800 rounded-2xl text-white font-black shadow-lg shadow-slate-100 hover:bg-slate-900 transition-all active:scale-95">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-backdrop></div>
        <div class="relative bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl overflow-hidden animate-zoom-in">
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-red-50 text-red-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-trash-can text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">ยืนยันการลบข้อมูล?</h3>
                <p class="text-sm text-slate-400 font-medium leading-relaxed px-4">คุณแน่ใจว่าต้องการลบประเภทอุปกรณ์ <span
                        id="deleteName" class="text-red-600 font-bold"></span> ออกจากระบบ?</p>
            </div>
            <form id="formDelete" method="POST" class="p-8 bg-slate-50 flex gap-3">
                @csrf @method('DELETE')
                <button type="button" data-close
                    class="flex-1 h-14 rounded-2xl bg-white text-slate-400 font-bold hover:bg-slate-100 border border-slate-100 transition-all">ยกเลิก</button>
                <button type="submit"
                    class="flex-1 h-14 bg-red-600 rounded-2xl text-white font-black shadow-lg shadow-red-100 hover:bg-red-700 transition-all active:scale-95">ลบเลย</button>
            </form>
        </div>
    </div>

    <style>
        @keyframes zoom-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-zoom-in {
            animation: zoom-in 0.3s ease-out forwards;
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards;
        }

        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

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

            // Edit buttons
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

            // ESC key to close
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeAll();
            });
        })();
    </script>
@endpush