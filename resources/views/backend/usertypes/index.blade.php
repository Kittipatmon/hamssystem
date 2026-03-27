@extends('layouts.sidebar')
@section('title', 'จัดการประเภทพนักงาน')
@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 pb-6 border-b border-gray-200 dark:border-white/10">
        <div>
            <h1 class="text-3xl font-bold text-kumwell-red flex items-center gap-3">
                <i class="fa-solid fa-user-tag text-2xl opacity-80"></i>
                จัดการระดับพนักงาน (User Levels)
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">กำหนดและจัดการการตั้งค่าระดับพนักงานในระบบ</p>
        </div>
        <button type="button" id="openCreateModal" class="btn btn-kumwell-red shadow-lg">
            <i class="fa-solid fa-plus-circle mr-2 text-lg"></i> เพิ่มระดับพนักงาน
        </button>
    </div>

    {{-- Main Content Card --}}
    <div class="kumwell-card bg-white dark:bg-kumwell-card border border-gray-200 dark:border-white/5 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
            <table class="table table-md w-full">
                <thead class="kumwell-table-header dark:text-gray-300">
                    <tr class="border-b border-gray-200 dark:border-white/5">
                        <th class="py-4 pl-6 text-left">ระดับพนักงาน (User Type)</th>
                        <th class="text-left">คำอธิบาย (Description)</th>
                        <th class="text-center">สถานะ</th>
                        <th class="w-28 text-center pr-6">จัดการ</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($userTypes as $index => $userType)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5">
                            <td class="pl-6">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-award text-xs text-kumwell-red opacity-40"></i>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $userType->type_name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $userType->description }}">
                                    {{ $userType->description ?? '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                @if($userType->status == '0')
                                    <div class="kumwell-badge bg-success/10 text-success border border-success/20">
                                        <i class="fa-solid fa-check-circle text-[10px]"></i> ใช้งาน
                                    </div>
                                @else
                                    <div class="kumwell-badge bg-error/10 text-error border border-error/20">
                                        <i class="fa-solid fa-times-circle text-[10px]"></i> ไม่ใช้งาน
                                    </div>
                                @endif
                            </td>
                            <td class="pr-6">
                                <div class="flex justify-center gap-2">
                                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all editBtn"
                                        data-id="{{ $userType->id }}" data-type_name="{{ $userType->type_name }}"
                                        data-description="{{ $userType->description }}" data-status="{{ $userType->status }}"
                                        title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all deleteBtn"
                                        data-id="{{ $userType->id }}" data-name="{{ $userType->type_name }}" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <i class="fa-solid fa-folder-open text-4xl opacity-20"></i>
                                    <p class="text-sm">ไม่พบข้อมูลระดับพนักงาน</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div id="createModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="relative mx-auto p-4 w-full max-w-lg">
            <div class="relative kumwell-card bg-white dark:bg-kumwell-card shadow-2xl overflow-hidden border border-white/10">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle text-success"></i> เพิ่มระดับพนักงาน
                    </h2>

                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 dark:hover:bg-white/10 rounded-full text-sm w-10 h-10 inline-flex justify-center items-center transition-colors" data-close-create>
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-8">
                <form method="POST" action="{{ route('usertypes.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            ระดับพนักงาน <span class="text-kumwell-red">*</span>
                        </label>
                        <input name="type_name" type="text" class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"
                            placeholder="เช่น Admin, User, Manager" required />
                        @error('type_name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">คำอธิบาย</label>
                        <textarea name="description" rows="3"
                            class="textarea textarea-bordered w-full bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red" placeholder="รายละเอียดเพิ่มเติม"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100 dark:border-white/5 mt-4">
                        <button type="button" class="btn btn-ghost px-6" data-close-create>ยกเลิก</button>
                        <button type="submit" class="btn btn-kumwell-red px-8 shadow-lg shadow-kumwell-red/20">บันทึกข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div id="editModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="relative mx-auto p-4 w-full max-w-lg">
            <div class="relative kumwell-card bg-white dark:bg-kumwell-card shadow-2xl overflow-hidden border border-white/10">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-edit text-warning"></i> แก้ไขระดับพนักงาน
                    </h2>

                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 dark:hover:bg-white/10 rounded-full text-sm w-10 h-10 inline-flex justify-center items-center transition-colors" data-close-edit>
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-8">
                <form method="POST" id="editForm" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ระดับพนักงาน <span class="text-kumwell-red">*</span></label>
                            <input name="type_name" id="edit_type_name" type="text"
                                class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red" required />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">สถานะ <span class="text-kumwell-red">*</span></label>
                            <select name="status" id="edit_status" class="select select-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red">
                                <option value="0">ใช้งาน</option>
                                <option value="1">ไม่ใช้งาน</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">คำอธิบาย</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="textarea textarea-bordered w-full bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100 dark:border-white/5 mt-4">
                        <button type="button" class="btn btn-ghost px-6" data-close-edit>ยกเลิก</button>
                        <button type="submit" class="btn btn-warning text-white px-8 shadow-lg shadow-warning/20">อัปเดตข้อมูล</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-100">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ยืนยันการลบ?</h3>
                <p class="text-sm text-gray-500 dark:text-gray-300 mb-6">
                    คุณต้องการลบรายการ <span id="deleteName" class="font-bold text-gray-800 dark:text-white"></span>
                    ใช่หรือไม่?<br>
                    การกระทำนี้ไม่สามารถย้อนกลับได้
                </p>
                <form method="POST" id="deleteForm" class="flex justify-center space-x-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-ghost" data-close-delete>ยกเลิก</button>
                    <button type="submit" class="btn btn-error text-white px-6">ยืนยันลบ</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Utility functions to Open/Close Modals
            function openModal(modal) {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Animation effect (optional)
                setTimeout(() => {
                    modal.firstElementChild.classList.remove('scale-95', 'opacity-0');
                    modal.firstElementChild.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeModal(modal) {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Elements
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            const deleteModal = document.getElementById('deleteModal');

            // --- Create Modal Logic ---
            document.getElementById('openCreateModal')?.addEventListener('click', () => {
                // Optional: Reset form when opening create modal
                const form = createModal.querySelector('form');
                if (form) form.reset();
                openModal(createModal);
            });

            // --- Edit Modal Logic ---
            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    // Use Optional Chaining (?.) for safety
                    // document.getElementById('edit_code').value = btn.dataset.code || '';
                    document.getElementById('edit_type_name').value = btn.dataset.type_name || '';
                    // document.getElementById('edit_name_en').value = btn.dataset.name_en || '';
                    document.getElementById('edit_description').value = btn.dataset.description || '';
                    document.getElementById('edit_status').value = btn.dataset.status || '0';
                    // const categorySelect = document.getElementById('edit_category_id');
                    // if (categorySelect) {
                    //     categorySelect.value = btn.dataset.category_id || '';
                    // }

                    const editForm = document.getElementById('editForm');
                    // Ensure the route URL is correct
                    editForm.action = `{{ url('usertypes') }}/${id}`;

                    openModal(editModal);
                });
            });

            // --- Delete Modal Logic ---
            document.querySelectorAll('.deleteBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const name = btn.dataset.name;

                    document.getElementById('deleteName').textContent = name;
                    const deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = `{{ url('usertypes') }}/${id}`;

                    openModal(deleteModal);
                });
            });

            // --- Global Close Handlers ---
            // Close buttons (X and Cancel)
            document.querySelectorAll('[data-close-create]').forEach(btn => btn.addEventListener('click', () => closeModal(
                createModal)));
            document.querySelectorAll('[data-close-edit]').forEach(btn => btn.addEventListener('click', () => closeModal(
                editModal)));
            document.querySelectorAll('[data-close-delete]').forEach(btn => btn.addEventListener('click', () => closeModal(
                deleteModal)));

            // Close on Escape key
            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    [createModal, editModal, deleteModal].forEach(m => closeModal(m));
                }
            });

            // Close when clicking outside (Backdrop)
            [createModal, editModal, deleteModal].forEach(modal => {
                modal?.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
            });
        </script>
    @endpush
@endsection