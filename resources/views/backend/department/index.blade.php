@extends('layouts.sidebar')
@section('title', 'ข้อมูลแผนก (Department)')
@section('content')

<div class="min-h-screen bg-slate-100 dark:bg-zinc-950 px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-[1600px] mx-auto space-y-6 font-noto">

        {{-- ════════════════════════════════════════════════════════════════
             HEADER BANNER (CLINICAL COMMAND STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border-l-4 border-l-red-600 border border-slate-300 dark:border-zinc-800 p-6 sm:p-8 flex flex-col lg:flex-row lg:items-center justify-between gap-6 shadow-sm">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 rounded mb-3">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                    </span>
                    <span class="text-[10px] font-bold text-red-700 dark:text-red-400 uppercase tracking-widest">ORGANIZATION DIRECTORY</span>
                </div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                    รายชื่อแผนกองค์กร (Department Registry)
                </h1>
                <p class="text-slate-600 dark:text-zinc-400 text-sm">
                    สืบค้นและตรวจสอบรายชื่อแผนก ชื่อเต็ม สังกัดฝ่าย และสถานะการใช้งานของแผนกในระบบงานทั้งหมด
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full lg:w-auto font-bold">
                {{-- Search Box --}}
                <div class="relative flex-grow sm:w-80 group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 transition-colors group-focus-within:text-red-600">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </div>
                    <input type="text" id="searchInput" value="{{ request('search') }}" 
                        class="w-full pl-10 pr-10 py-3 text-sm rounded border border-slate-300 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800 focus:bg-white focus:ring-1 focus:ring-red-600 focus:border-red-600 outline-none text-slate-900 dark:text-white font-medium"
                        placeholder="พิมพ์ค้นหาแผนก หรือชื่อเต็ม...">
                    <div id="searchLoader" class="absolute inset-y-0 right-0 pr-3.5 flex items-center hidden">
                        <span class="loading loading-spinner loading-xs text-red-600"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════
             DATA TABLE (CLINICAL/LEDGER STYLE)
             ════════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-zinc-900 border border-slate-300 dark:border-zinc-800 shadow-sm overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-850 dark:bg-zinc-850 bg-slate-800 text-white border-b border-slate-300 dark:border-zinc-700">
                            <th class="py-3 px-4 text-xs font-bold uppercase tracking-wider w-24 text-center border-r border-slate-700 dark:border-zinc-700">ลำดับ</th>
                            <th class="py-3 px-6 text-xs font-bold uppercase tracking-wider border-r border-slate-700 dark:border-zinc-700">รหัสและรายละเอียดแผนก</th>
                            <th class="py-3 px-6 text-xs font-bold uppercase tracking-wider w-48 text-center">สถานะการใช้งาน</th>
                        </tr>
                    </thead>
                    <tbody id="departmentsBody" class="divide-y divide-slate-200 dark:divide-zinc-800">
                        @foreach ($departments as $department)
                            <tr class="odd:bg-white even:bg-slate-50/50 dark:odd:bg-zinc-900 dark:even:bg-zinc-900/40 hover:bg-red-50/10 dark:hover:bg-red-950/5 transition-colors">
                                <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800 font-bold text-slate-400 dark:text-zinc-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded border border-slate-300 dark:border-zinc-700 bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400">
                                            <i class="fa-solid fa-building text-xs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $department->name }}</span>
                                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">DEPT-{{ str_pad($department->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($department->department_status == 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 text-xs font-bold">
                                            <i class="fa-solid fa-check-circle mr-1.5 text-[10px]"></i> ใช้งานปกติ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 text-slate-800 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-300 dark:border-zinc-700 text-xs font-bold">
                                            ไม่ใช้งาน
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // --- AJAX Search Logic ---
        const searchInput = document.getElementById('searchInput');
        const searchLoader = document.getElementById('searchLoader');
        const departmentsBody = document.getElementById('departmentsBody');

        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value;
                
                searchLoader.classList.remove('hidden');
                
                searchTimeout = setTimeout(() => {
                    fetchResults(query);
                }, 500);
            });
        }

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
            departmentsBody.innerHTML = '';
            
            if (data.length === 0) {
                departmentsBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="py-12 text-center text-slate-400 bg-slate-50/50 dark:bg-zinc-900/40 italic font-bold">
                            <i class="fa-solid fa-folder-open text-2xl mb-2 block"></i>
                            ไม่พบข้อมูลแผนกตามเงื่อนไข
                        </td>
                    </tr>
                `;
                return;
            }

            data.forEach((d, index) => {
                const row = document.createElement('tr');
                row.className = 'odd:bg-white even:bg-slate-50/50 dark:odd:bg-zinc-900 dark:even:bg-zinc-900/40 hover:bg-red-50/10 dark:hover:bg-red-950/5 transition-colors';
                
                row.innerHTML = `
                    <td class="py-4 px-4 text-center border-r border-slate-200 dark:border-zinc-800 font-bold text-slate-400 dark:text-zinc-500">${index + 1}</td>
                    <td class="py-4 px-6 border-r border-slate-200 dark:border-zinc-800">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded border border-slate-300 dark:border-zinc-700 bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-500 dark:text-zinc-400">
                                <i class="fa-solid fa-building text-xs"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 dark:text-white text-sm">${d.name}</span>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-bold">DEPT-${(d.id || 0).toString().padStart(3, '0')}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        ${(d.department_status == 0 || d.department_status === undefined) ? `
                            <span class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900 text-xs font-bold">
                                <i class="fa-solid fa-check-circle mr-1.5 text-[10px]"></i> ใช้งานปกติ
                            </span>
                        ` : `
                            <span class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 text-slate-800 dark:bg-zinc-800 dark:text-zinc-400 border border-slate-300 dark:border-zinc-700 text-xs font-bold">
                                ไม่ใช้งาน
                            </span>
                        `}
                    </td>
                `;
                departmentsBody.appendChild(row);
            });
        }
    </script>
@endpush
@endsection
