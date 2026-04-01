@extends('layouts.sidebar')
@section('title', 'ข้อมูลสายงาน (Section)')
@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 pb-6 border-b border-gray-200 dark:border-white/10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-kumwell-red/10 flex items-center justify-center text-kumwell-red shadow-inner">
                <i class="fa-solid fa-code-branch text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    สายงาน <span class="text-kumwell-red">(Section)</span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    จัดการและกำหนดโครงสร้างสายงานระดับสูงขององค์กร
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
                    placeholder="ค้นารหัส หรือชื่อสายงาน...">
                <div id="searchLoader" class="absolute inset-y-0 right-0 pr-3.5 flex items-center hidden">
                    <span class="loading loading-spinner loading-xs text-kumwell-red"></span>
                </div>
            </div>
            <button type="button" onclick="openModal()" 
                class="btn bg-kumwell-red hover:bg-red-700 text-white border-none shadow-lg shadow-red-500/20 h-11 px-6 rounded-xl flex items-center justify-center gap-2 transition-all duration-300 active:scale-95 group">
                <i class="fa-solid fa-plus-circle text-lg group-hover:rotate-90 transition-transform duration-500"></i>
                <span class="font-bold">สร้างสายงานใหม่</span>
            </button>
        </div>
    </div>

    {{-- Alert Section --}}
    @if (session('success'))
    <div class="alert alert-success shadow-lg border-none bg-success/20 text-success py-3">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Main Content Card --}}
    <div class="kumwell-card bg-white dark:bg-kumwell-card border border-gray-200 dark:border-white/5 overflow-hidden shadow-2xl relative">
        <div class="overflow-x-auto">
            <table class="table table-md w-full">
                <thead class="kumwell-table-header dark:text-gray-300">
                    <tr class="border-b border-gray-200 dark:border-white/5">
                        <th class="py-4 pl-6 text-left">ลำดับ</th>
                        <th class="text-left">ชื่อย่อ (Code)</th>
                        <th class="text-left">ชื่อเต็ม (Name)</th>
                        <th class="text-center">สถานะ</th>
                        <th class="w-28 text-center pr-6">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="sectionsBody">
                    @foreach ($sections as $section)
                    <tr id="section-{{ $section->section_id }}" class="hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5">
                        <td class="pl-6 font-medium text-gray-400">{{ $loop->iteration }}</td>
                        <td>
                            <span class="bg-kumwell-red/10 text-kumwell-red px-2 py-0.5 rounded font-bold text-xs uppercase tracking-wider">
                                {{ $section->section_code }}
                            </span>
                        </td>
                        <td>
                            <div class="font-medium text-gray-900 dark:text-white">{{ $section->section_name }}</div>
                        </td>
                        <td class="text-center">
                            @if ($section->section_status === 0)
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
                                <button onclick="openModal({{ json_encode($section) }})" class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all" title="แก้ไข">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form id="delete-form-{{ $section->section_id }}"
                                    action="{{ route('sections.destroy', $section->section_id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all"
                                        onclick="showDeleteModal('{{ $section->section_id }}')" title="ลบ">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Save Modal -->
<div id="save-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
    <div class="relative mx-auto p-4 w-full max-w-lg">
        <div class="relative kumwell-card bg-white dark:bg-kumwell-card shadow-2xl overflow-hidden border border-white/10">
            <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i id="modal-icon" class="fa-solid fa-plus-circle text-success"></i>
                    <span id="modal-title">สร้างสายงานใหม่</span>
                </h2>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 dark:hover:bg-white/10 rounded-full text-sm w-10 h-10 inline-flex justify-center items-center transition-colors" onclick="closeModal()">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-8">
        <form id="save-form" class="space-y-6">
            @csrf
            <input type="hidden" id="section_id" name="section_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2" for="section_code">ชื่อย่อ <span class="text-kumwell-red">*</span></label>
                    <input type="text" id="section_code" name="section_code" class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red" placeholder="เช่น SEC-01" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2" for="section_status">สถานะ <span class="text-kumwell-red">*</span></label>
                    <select id="section_status" name="section_status" class="select select-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red" required>
                        <option value="0">ใช้งาน</option>
                        <option value="1">ไม่ใช้งาน</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2" for="section_name">ชื่อเต็ม <span class="text-kumwell-red">*</span></label>
                <input type="text" id="section_name" name="section_name" class="input input-bordered w-full h-12 bg-white dark:bg-white/5 border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-kumwell-red/20 focus:border-kumwell-red" placeholder="ระบุชื่อเต็มสายงาน" required>
            </div>

            <div class="flex justify-end space-x-3 pt-6 mt-4">
                <button type="button" class="btn btn-ghost px-6" onclick="closeModal()">ยกเลิก</button>
                <button type="submit" class="btn btn-kumwell-red px-8 shadow-lg shadow-kumwell-red/20">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-100">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-3xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ยืนยันการลบ?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-300 mb-6">
                คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้? <br>
                การกระทำนี้ไม่สามารถย้อนกลับได้
            </p>
            <div class="flex justify-center space-x-3">
                <button type="button" class="btn btn-ghost" onclick="hideDeleteModal()">ยกเลิก</button>
                <button id="confirm-delete-btn" class="btn btn-error text-white px-6">ลบ</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentSectionId = null;

function openModal(section = null) {
    const modal = document.getElementById('save-modal');
    const form = document.getElementById('save-form');
    const modalTitle = document.getElementById('modal-title');
    const modalIcon = document.getElementById('modal-icon');

    form.reset();
    document.getElementById('section_id').value = '';
    currentSectionId = null;

    if (section) {
        modalTitle.textContent = 'แก้ไขสายงาน';
        modalIcon.className = 'fa-solid fa-edit mr-2 text-yellow-500';
        document.getElementById('section_id').value = section.section_id;
        document.getElementById('section_code').value = section.section_code;
        document.getElementById('section_name').value = section.section_name;
        document.getElementById('section_status').value = section.section_status;
        currentSectionId = section.section_id;
    } else {
        modalTitle.textContent = 'สร้างสายงานใหม่';
        modalIcon.className = 'fa-solid fa-plus-circle mr-2 text-green-500';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('save-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('save-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const sectionId = document.getElementById('section_id').value;
    let url = '{{ route("sections.store") }}';
    let method = 'POST';

    const data = {};
    formData.forEach((value, key) => data[key] = value);

    if (sectionId) {
        url = `/sections/${sectionId}`;
        data['_method'] = 'PUT';
    }

    const response = await fetch(url, {
        method: 'POST', // Always POST, with _method for PUT
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    });

    if (response.ok) {
        location.reload(); // Easiest way to see changes
    } else {
        const errors = await response.json();
        // Handle errors, e.g., display them to the user
        console.error(errors);
        alert('Error saving section.');
    }
});

function showDeleteModal(id) {
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    const form = document.getElementById('delete-form-' + id);
    const confirmBtn = document.getElementById('confirm-delete-btn');
    confirmBtn.onclick = function() {
        form.submit();
    }
}

function hideDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal on escape key press
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeModal();
        hideDeleteModal();
    }
});

// --- AJAX Search Logic ---
const searchInput = document.getElementById('searchInput');
const searchLoader = document.getElementById('searchLoader');
const sectionsBody = document.getElementById('sectionsBody');

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
    sectionsBody.innerHTML = '';
    
    if (data.length === 0) {
        sectionsBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-16">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <i class="fa-solid fa-folder-open text-4xl opacity-20"></i>
                        <p class="text-sm">ไม่พบข้อมูลสายงาน</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    data.forEach((s, index) => {
        const statusBadge = s.section_status === 0 
            ? `<div class="kumwell-badge bg-success/10 text-success border border-success/20">
                    <i class="fa-solid fa-check-circle text-[10px]"></i> ใช้งาน
               </div>`
            : `<div class="kumwell-badge bg-error/10 text-error border border-error/20">
                    <i class="fa-solid fa-times-circle text-[10px]"></i> ไม่ใช้งาน
               </div>`;

        const row = document.createElement('tr');
        row.id = `section-${s.section_id}`;
        row.className = 'hover:bg-gray-50 dark:hover:bg-white/5 transition-all duration-200 border-b border-gray-100 dark:border-white/5';
        row.innerHTML = `
            <td class="pl-6 font-medium text-gray-400">${index + 1}</td>
            <td>
                <span class="bg-kumwell-red/10 text-kumwell-red px-2 py-0.5 rounded font-bold text-xs uppercase tracking-wider">
                    ${s.section_code}
                </span>
            </td>
            <td>
                <div class="font-medium text-gray-900 dark:text-white">${s.section_name}</div>
            </td>
            <td class="text-center">${statusBadge}</td>
            <td class="pr-6">
                <div class="flex justify-center gap-2">
                    <button onclick='openModal(${JSON.stringify(s)})' class="btn btn-circle btn-ghost btn-xs hover:bg-warning/20 hover:text-warning transition-all" title="แก้ไข">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-circle btn-ghost btn-xs hover:bg-error/20 hover:text-error transition-all"
                        onclick="showDeleteModal('${s.section_id}')" title="ลบ">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            </td>
        `;
        sectionsBody.appendChild(row);
    });
}
</script>
@endpush