@extends('layouts.sidebar')
@section('title', 'ข้อมูลฝ่าย (Division)')
@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 pb-6 border-b border-gray-200 dark:border-white/10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-kumwell-red/10 flex items-center justify-center text-kumwell-red shadow-inner">
                <i class="fa-solid fa-layer-group text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    ฝ่าย <span class="text-kumwell-red">(Division)</span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    จัดการข้อมูลฝ่ายและเชื่อมโยงกับสายงานองค์กร
                </p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative flex-grow sm:w-80 group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none transition-colors group-focus-within:text-kumwell-red">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                </div>
                <input type="text" id="searchInput" value="{{ request('search') }}" 
                    class="input input-bordered w-full pl-10 h-11 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-4 focus:ring-kumwell-red/10 focus:border-kumwell-red transition-all duration-300 rounded-xl text-sm"
                    placeholder="ค้นหาชื่อฝ่าย หรือชื่อเต็ม...">
                <div id="searchLoader" class="absolute inset-y-0 right-0 pr-3.5 flex items-center hidden">
                    <span class="loading loading-spinner loading-xs text-kumwell-red"></span>
                </div>
            </div>
            <button type="button" onclick="openCreateModal()" 
                class="btn bg-kumwell-red hover:bg-red-700 text-white border-none shadow-lg shadow-red-500/20 h-11 px-6 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 active:scale-95 group">
                <i class="fa-solid fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-bold">สร้างฝ่ายใหม่</span>
            </button>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="kumwell-card bg-white dark:bg-kumwell-card border border-gray-200 dark:border-white/5 overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
            <table class="table table-md w-full">
                <thead class="kumwell-table-header dark:text-gray-300">
                    <tr class="border-b border-gray-200 dark:border-white/5">
                        <th class="py-4 pl-6 text-left">ลำดับ</th>
                        <th class="text-left">สายงาน (Section)</th>
                        <th class="text-left">ชื่อย่อ (Name)</th>
                        <th class="text-left">ชื่อเต็ม (Fullname)</th>
                        <th class="text-center">สถานะ</th>
                        <th class="w-28 text-center pr-6">จัดการ</th>
                    </tr>
                </thead>
                    <tbody id="divisionsBody" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($divisions as $division)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5">
                            <td class="pl-6 font-medium text-gray-400">{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-sitemap text-xs opacity-40"></i>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $division->section->section_code ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="bg-kumwell-red/10 text-kumwell-red px-2 py-0.5 rounded font-bold text-xs">
                                    {{ $division->division_name }}
                                </span>
                            </td>
                            <td>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $division->division_fullname }}</div>
                            </td>
                            <td class="text-center">
                                @if ($division->division_status === 0)
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
                                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all"
                                        onclick='openEditModal(@json($division))' title="แก้ไข">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all"
                                        onclick="deleteDivision({{ $division->division_id }})" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
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

    <div id="divisionModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full bg-black/60 backdrop-blur-sm transition-all duration-300">
        <div class="relative mx-auto p-4 w-full max-w-lg">
            <div class="relative kumwell-card bg-white dark:bg-kumwell-card shadow-2xl overflow-hidden border border-white/10">
                <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2" id="modalTitle">
                        สร้างฝ่ายใหม่
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-100 dark:hover:bg-white/10 rounded-full text-sm w-10 h-10 inline-flex justify-center items-center transition-colors"
                        onclick="closeModal()">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-8">
                    <form id="divisionForm" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="POST">
                        <input type="hidden" name="id" id="divisionId">

                        <div class="space-y-5">
                            <div>
                                <label for="section_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">สายงาน (Section) <span class="text-kumwell-red">*</span></label>
                                <select name="section_id" id="section_id"
                                    class="select select-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"
                                    required>
                                    <option value="" disabled selected>-- เลือกรหัสฝ่าย --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->section_id }}">{{ $section->section_code }} -
                                            {{ $section->section_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="division_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ชื่อย่อ <span class="text-kumwell-red">*</span></label>
                                    <input type="text" name="division_name" id="division_name"
                                        class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"
                                        placeholder="เช่น HR, IT" required>
                                </div>
                                <div>
                                    <label for="division_status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">สถานะ <span class="text-kumwell-red">*</span></label>
                                    <select name="division_status" id="division_status"
                                        class="select select-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"
                                        required>
                                        <option value="0">ใช้งาน</option>
                                        <option value="1">ไม่ใช้งาน</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="division_fullname" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">ชื่อเต็ม</label>
                                <input type="text" name="division_fullname" id="division_fullname"
                                    class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red"
                                    placeholder="ใส่ชื่อเต็มของฝ่าย">
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-10">
                            <button type="button" class="btn btn-ghost px-6" onclick="closeModal()">ยกเลิก</button>
                            <button type="submit" class="btn btn-kumwell-red px-8 shadow-lg shadow-kumwell-red/20" id="submitButton">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-100 p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-red-500"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">ยืนยันการลบ?</h3>
            <p class="text-base text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?<br>
                การกระทำนี้ไม่สามารถย้อนกลับได้
            </p>
            <div class="flex justify-center items-center gap-4">
                <button type="button" class="btn btn-ghost px-8" onclick="closeDeleteModal()">ยกเลิก</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-error text-white px-10 shadow-lg shadow-red-200">ลบ</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // ตรวจสอบว่า Flowbite Modal โหลดหรือยัง ถ้ายังให้ใช้ class hidden manual
        let modal;
        try {
            const modalElement = document.getElementById('divisionModal');
            modal = new Modal(modalElement);
        } catch (e) {
            console.warn('Flowbite modal not initialized, using fallback toggle logic.');
        }

        const form = document.getElementById('divisionForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        const divisionId = document.getElementById('divisionId');
        const submitButton = document.getElementById('submitButton');

        // แยกฟังก์ชันเปิด Modal Create
        function openCreateModal() {
            modalTitle.innerText = 'สร้างฝ่ายใหม่';
            form.action = '{{ route("divisions.store") }}';
            methodField.value = 'POST';
            divisionId.value = '';
            form.reset();

            // รีเซ็ต Select ให้เป็นค่า default
            document.getElementById('section_id').value = "";
            document.getElementById('division_status').value = "0";

            submitButton.innerText = 'บันทึก';
            showModal();
        }

        // แยกฟังก์ชันเปิด Modal Edit
        function openEditModal(division) {
            modalTitle.innerText = 'แก้ไขฝ่าย';
            // ตรวจสอบ URL ให้ถูกต้อง
            form.action = '{{ url("divisions") }}/' + division.division_id;
            methodField.value = 'PUT';
            divisionId.value = division.division_id;

            // Assign Values
            // จุดที่แก้ไข: ต้อง Set value ให้ Select Dropdown ด้วย
            document.getElementById('section_id').value = division.section_id;
            document.getElementById('division_name').value = division.division_name;
            document.getElementById('division_fullname').value = division.division_fullname;
            document.getElementById('division_status').value = division.division_status;

            submitButton.innerText = 'บันทึกข้อมูล';
            showModal();
        }

        function showModal() {
            if (modal) {
                modal.show();
            } else {
                const modalEl = document.getElementById('divisionModal');
                modalEl.classList.remove('hidden');
                modalEl.classList.add('flex');
                modalEl.removeAttribute('aria-hidden');
            }
        }

        function closeModal() {
            if (modal) {
                modal.hide();
            } else {
                const modalEl = document.getElementById('divisionModal');
                modalEl.classList.add('hidden');
                modalEl.classList.remove('flex');
                modalEl.setAttribute('aria-hidden', 'true');
            }
        }

        function openDeleteModal() {
            const modalEl = document.getElementById('deleteModal');
            modalEl.classList.remove('hidden');
            modalEl.classList.add('flex');
            modalEl.removeAttribute('aria-hidden');
        }

        function closeDeleteModal() {
            const modalEl = document.getElementById('deleteModal');
            modalEl.classList.add('hidden');
            modalEl.classList.remove('flex');
            modalEl.setAttribute('aria-hidden', 'true');
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const action = form.action;
            const formData = new FormData(form);
            // ดึงค่า method จาก hidden field ใส่เข้าไปใน formData (ถึงแม้ Laravel จะอ่าน _method ก็ตาม)
            const method = methodField.value;

            fetch(action, {
                method: 'POST', // Browser form submit ใช้ POST เสมอ แล้ว Laravel จะดู _method เอง
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json' // บอก server ว่าขอ response เป็น json
                },
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        closeModal();
                        // อาจจะใช้ SweetAlert ตรงนี้แทนการ reload ทันทีก็ได้
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // แสดง Error message อย่างง่าย
                    let msg = "เกิดข้อผิดพลาด!";
                    if (error.errors) {
                        msg = Object.values(error.errors).flat().join('\n');
                    } else if (error.message) {
                        msg = error.message;
                    }
                    alert(msg);
                });
        });

        function deleteDivision(id) {
            openDeleteModal();

            const confirmBtn = document.getElementById('confirmDeleteBtn');
            // Clone and replace to remove old listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            newConfirmBtn.addEventListener('click', function () {
                const formData = new FormData();
                formData.append('_method', 'DELETE');

                fetch('{{ url("divisions") }}/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('ไม่สามารถลบได้');
                            closeDeleteModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        closeDeleteModal();
                    });
            });
        }

        // Close on backdrop click for delete modal
        document.getElementById('deleteModal').addEventListener('click', (e) => {
            if (e.target.id === 'deleteModal') {
                closeDeleteModal();
            }
        });

        // --- AJAX Search Logic ---
        const searchInput = document.getElementById('searchInput');
        const searchLoader = document.getElementById('searchLoader');
        const divisionsBody = document.getElementById('divisionsBody');

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;
            
            searchLoader.classList.remove('hidden');
            
            searchTimeout = setTimeout(() => {
                fetchResults(query);
            }, 500);
        });

        function fetchResults(query) {
            const url = new URL(window.location.href);
            url.searchParams.set('search', query);

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                renderTable(data);
                searchLoader.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error fetching results:', error);
                searchLoader.classList.add('hidden');
            });
        }

        function renderTable(data) {
            divisionsBody.innerHTML = '';
            
            if (data.length === 0) {
                divisionsBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-16">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="fa-solid fa-folder-open text-4xl opacity-20"></i>
                                <p class="text-sm">ไม่พบข้อมูลฝ่าย</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach((d, index) => {
                const statusBadge = d.division_status === 0 
                    ? `<div class="kumwell-badge bg-success/10 text-success border border-success/20">
                            <i class="fa-solid fa-check-circle text-[10px]"></i> ใช้งาน
                       </div>`
                    : `<div class="kumwell-badge bg-error/10 text-error border border-error/20">
                            <i class="fa-solid fa-times-circle text-[10px]"></i> ไม่ใช้งาน
                       </div>`;

                const sectionCode = d.section ? d.section.section_code : '-';

                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5';
                row.innerHTML = `
                    <td class="pl-6 font-medium text-gray-400">${index + 1}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-sitemap text-xs opacity-40"></i>
                            <span class="font-medium text-gray-700 dark:text-gray-300">${sectionCode}</span>
                        </div>
                    </td>
                    <td>
                        <span class="bg-kumwell-red/10 text-kumwell-red px-2 py-0.5 rounded font-bold text-xs uppercase tracking-wider">
                            ${d.division_name}
                        </span>
                    </td>
                    <td>
                        <div class="font-medium text-gray-900 dark:text-white">${d.division_fullname}</div>
                    </td>
                    <td class="text-center">${statusBadge}</td>
                    <td class="pr-6">
                        <div class="flex justify-center gap-2">
                            <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all"
                                onclick='openEditModalFromJS(${JSON.stringify(d)})' title="แก้ไข">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all"
                                onclick="deleteDivision(${d.division_id})" title="ลบ">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                `;
                divisionsBody.appendChild(row);
            });
        }

        // Helper for dynamic edit buttons
        window.openEditModalFromJS = function(division) {
            openEditModal(division);
        };
    </script>
@endpush