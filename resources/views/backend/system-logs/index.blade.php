@extends('layouts.sidebar')

@section('content')
<!-- Header Area -->
<div class="flex justify-between items-center mb-6 bg-white dark:bg-zinc-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800">
    <div class="flex-1 text-center sm:text-left sm:pl-4">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white">System Logs</h2>
    </div>
    <div>
        <button onclick="openArchivesModal()" class="bg-[#5942e9] hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
            <i class="fa-solid fa-file-zipper"></i> Archived Logs
        </button>
    </div>
</div>

<!-- Filter Area -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 mb-6 shadow-sm">
    <form method="GET" action="{{ route('system-logs.index') }}">
        <div class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-1/3 md:w-1/4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">ประเภท Log</label>
                <select name="log_name" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:bg-zinc-800 dark:border-zinc-700 dark:text-white transition-colors">
                    <option value="">ทั้งหมด</option>
                    @foreach($logTypes as $type)
                        <option value="{{ $type }}" {{ request('log_name') == $type ? 'selected' : '' }}>{{ Str::title($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-[#5942e9] hover:bg-indigo-700 text-white px-5 py-2 rounded-md text-sm font-medium shadow-sm transition-colors">
                    กรองข้อมูล
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Table Area -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm">
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table id="logs-table" class="w-full text-left text-sm whitespace-nowrap stripe hover">
            <thead class="text-slate-500 dark:text-slate-400 font-medium border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-3 font-semibold">#</th>
                    <th class="px-4 py-3 font-semibold">ผู้ใช้งาน</th>
                    <th class="px-4 py-3 font-semibold">ประเภท</th>
                    <th class="px-4 py-3 font-semibold">โมดูล</th>
                    <th class="px-4 py-3 font-semibold w-1/3 min-w-[300px]">รายละเอียด</th>
                    <th class="px-4 py-3 font-semibold">IP ADDRESS</th>
                    <th class="px-4 py-3 font-semibold">วันที่/เวลา</th>
                    <th class="px-4 py-3 font-semibold text-center">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                @foreach ($logs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-4">{{ $log->id }}</td>
                    <td class="px-4 py-4">
                        @if($log->causer)
                            {{ $log->causer->fullname ?? $log->causer->firstname ?? 'Unknown' }}
                        @else
                            System
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @php
                            $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                            $logName = strtolower($log->log_name);
                            if($logName == 'login' || str_contains($logName, 'login')) {
                                $badgeClass = 'bg-[#e6f4ea] text-[#1e8e3e] border-[#ceead6]';
                                $displayText = 'Login';
                            }
                            elseif($logName == 'error' || ($log->properties->has('risk') && $log->properties['risk'] == true)) {
                                $badgeClass = 'bg-[#fce8e6] text-[#c5221f] border-[#fad2cf]';
                                $displayText = 'Error';
                            }
                            elseif(in_array($log->event, ['created', 'updated', 'deleted']) || $logName == 'default') {
                                $badgeClass = 'bg-[#fef7e0] text-[#e37400] border-[#fce8b2]';
                                $displayText = 'Update';
                            } else {
                                $displayText = Str::title($logName);
                            }
                        @endphp
                        <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border {{ $badgeClass }}">
                            {{ $displayText }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-slate-500">
                        {{ $log->subject_type ? strtolower(class_basename($log->subject_type)) : ($logName == 'error' ? 'system_exception' : 'auth') }}
                    </td>
                    <td class="px-4 py-4 whitespace-normal break-words max-w-lg text-slate-600 dark:text-slate-400">
                        @if($log->log_name == 'error' && isset($log->properties['trace']))
                            {{ $log->description }}: {{ $log->properties['exception'] ?? '' }}
                            <div class="mt-2 text-xs opacity-80">{{ substr($log->properties['trace'], 0, 300) }}...</div>
                        @else
                            {{ $log->description }}
                        @endif
                    </td>
                    <td class="px-4 py-4 text-slate-500">
                        {{ $log->properties['ip'] ?? '127.0.0.1' }}
                    </td>
                    <td class="px-4 py-4 text-slate-500">
                        {{ $log->created_at->format('d/m/Y, H:i') }}
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button data-log="{{ json_encode([
                                'id' => $log->id, 
                                'description' => $log->description, 
                                'properties' => $log->properties, 
                                'date' => $log->created_at->format('d/m/Y, H:i:s')
                            ]) }}"
                            onclick="showLogDetails(this)"
                            class="text-[#5942e9] hover:bg-indigo-50 p-1.5 rounded-md transition-colors border border-indigo-100 dark:border-indigo-900/30">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    </div>
</div>

<!-- Modal -->
<div id="logModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border border-slate-200 dark:border-zinc-800">
            <div class="bg-white dark:bg-zinc-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa-solid fa-circle-info text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">Log Details #<span id="modal-log-id"></span></h3>
                        <div class="mt-1">
                            <p class="text-sm text-slate-500" id="modal-log-date"></p>
                        </div>
                        <div class="mt-4 text-sm text-slate-700 dark:text-slate-300">
                            <div class="font-medium mb-2 pb-2 border-b border-slate-100 dark:border-zinc-800" id="modal-log-desc"></div>
                            
                            <div class="mt-3">
                                <h4 class="font-medium text-xs text-slate-500 uppercase tracking-wider mb-2">Properties / Data</h4>
                                <div class="bg-slate-50 dark:bg-zinc-950 p-3 rounded-lg border border-slate-200 dark:border-zinc-800 overflow-x-auto max-h-96 overflow-y-auto">
                                    <div id="modal-log-props"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-zinc-800">
                <button type="button" onclick="closeLogModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-slate-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 sm:mt-0 sm:w-auto">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Archives Modal -->
<div id="archivesModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl border border-slate-200 dark:border-zinc-800">
            <div class="bg-white dark:bg-zinc-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa-solid fa-file-zipper text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">Archived Logs (ZIP)</h3>
                        <div class="mt-1">
                            <p class="text-sm text-slate-500">ไฟล์ประวัติการทำงานของปีก่อนๆ (เก็บรักษาสูงสุด 3 ปี)</p>
                        </div>
                        <div class="mt-4">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300 dark:divide-zinc-700">
                                    <thead class="bg-gray-50 dark:bg-zinc-800">
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-white sm:pl-6">File Name</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white">Size</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-right">
                                                <span class="sr-only">Download</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="archivesList" class="divide-y divide-gray-200 dark:divide-zinc-700 bg-white dark:bg-zinc-900">
                                        <!-- Fetched dynamically -->
                                        <tr><td colspan="3" class="text-center py-4 text-sm text-gray-500">Loading...</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-zinc-800">
                <button type="button" onclick="closeArchivesModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-zinc-800 px-3 py-2 text-sm font-semibold text-slate-900 dark:text-slate-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 sm:mt-0 sm:w-auto">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_length select, .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #5942e9;
        color: white !important;
        border: none;
        border-radius: 0.375rem;
    }
</style>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#logs-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                "infoEmpty": "ไม่มีข้อมูล",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "search": "ค้นหา:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    });

    function parseUserAgent(ua) {
        if (!ua) return 'ไม่ทราบอุปกรณ์ / เบราว์เซอร์';
        
        let os = 'ไม่ทราบระบบปฏิบัติการ (Unknown OS)';
        let browser = 'ไม่ทราบเบราว์เซอร์ (Unknown Browser)';
        
        // Detect OS
        if (ua.indexOf('Windows NT 10.0') !== -1) os = 'Windows 10 / 11';
        else if (ua.indexOf('Windows NT 6.3') !== -1) os = 'Windows 8.1';
        else if (ua.indexOf('Windows NT 6.2') !== -1) os = 'Windows 8';
        else if (ua.indexOf('Windows NT 6.1') !== -1) os = 'Windows 7';
        else if (ua.indexOf('Macintosh') !== -1) os = 'macOS';
        else if (ua.indexOf('iPhone') !== -1) os = 'iPhone';
        else if (ua.indexOf('iPad') !== -1) os = 'iPad';
        else if (ua.indexOf('Android') !== -1) {
            let match = ua.match(/Android\s+([0-9\.]+)/);
            os = match ? 'Android ' + match[1] : 'Android';
        } else if (ua.indexOf('Linux') !== -1) os = 'Linux';

        // Detect Browser
        if (ua.indexOf('Edg/') !== -1) {
            let match = ua.match(/Edg\/([0-9\.]+)/);
            browser = match ? 'Microsoft Edge ' + match[1] : 'Microsoft Edge';
        } else if (ua.indexOf('Chrome/') !== -1) {
            let match = ua.match(/Chrome\/([0-9\.]+)/);
            browser = match ? 'Google Chrome ' + match[1] : 'Google Chrome';
        } else if (ua.indexOf('Safari/') !== -1 && ua.indexOf('Version/') !== -1) {
            let match = ua.match(/Version\/([0-9\.]+)/);
            browser = match ? 'Safari ' + match[1] : 'Safari';
        } else if (ua.indexOf('Firefox/') !== -1) {
            let match = ua.match(/Firefox\/([0-9\.]+)/);
            browser = match ? 'Firefox ' + match[1] : 'Firefox';
        } else if (ua.indexOf('MSIE') !== -1 || ua.indexOf('Trident/') !== -1) {
            browser = 'Internet Explorer';
        }

        return `${os} (${browser})`;
    }

    function showLogDetails(btn) {
        try {
            const data = JSON.parse(btn.getAttribute('data-log'));
            document.getElementById('modal-log-id').textContent = data.id;
            document.getElementById('modal-log-date').textContent = data.date;
            document.getElementById('modal-log-desc').textContent = data.description;
            
            let propsHtml = '';
            if (data.properties && Object.keys(data.properties).length > 0) {
                if (data.properties.trace) {
                    // If it's an error with trace, just show the trace nicely
                    propsHtml = `<pre class="text-xs font-mono text-slate-700 dark:text-slate-300 whitespace-pre-wrap">Exception: ${data.properties.exception || ""}\n\n${data.properties.trace}</pre>`;
                } else {
                    // Format JSON as a nice list
                    propsHtml = '<ul class="space-y-3">';
                    for (const [key, value] of Object.entries(data.properties)) {
                        let displayKey = key;
                        // Map common keys to Thai or more readable text
                        const keyMap = {
                            'ip': 'IP Address',
                            'user_agent': 'เบราว์เซอร์ / อุปกรณ์ (User Agent)',
                            'email': 'อีเมลที่ใช้',
                            'username': 'ชื่อผู้ใช้',
                            'risk': 'ระดับความเสี่ยง',
                            'attempts': 'จำนวนครั้ง',
                            'old': 'ข้อมูลเดิมก่อนแก้ไข',
                            'attributes': 'ข้อมูลใหม่ (หลังทำรายการ)',
                        };
                        if (keyMap[key]) displayKey = keyMap[key];
                        
                        let displayValue = value;
                        if (key === 'user_agent') {
                            displayValue = `<span title="${value}" style="cursor: help; border-bottom: 1px dotted #94a3b8;">${parseUserAgent(value)}</span>`;
                        } else if (typeof value === 'object' && value !== null) {
                            displayValue = `<pre class="mt-1 bg-white dark:bg-zinc-900 p-2 rounded border border-slate-200 dark:border-zinc-700 text-[11px] font-mono whitespace-pre-wrap text-slate-600 dark:text-slate-300">${JSON.stringify(value, null, 2)}</pre>`;
                        } else if (typeof value === 'boolean') {
                            displayValue = value ? '<span class="text-red-500 font-bold bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded text-xs border border-red-200 dark:border-red-800">ใช่ (Yes)</span>' : '<span class="text-emerald-500 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded text-xs border border-emerald-200 dark:border-emerald-800">ไม่ใช่ (No)</span>';
                        } else if (value === null) {
                            displayValue = '<span class="text-slate-400 italic">ไม่มีข้อมูล (Null)</span>';
                        }
                        
                        propsHtml += `
                            <li class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 border-b border-slate-200 dark:border-zinc-800/60 pb-3 last:border-0 last:pb-0">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 w-full sm:w-1/3 shrink-0 pt-0.5">${displayKey}</span>
                                <div class="text-sm text-slate-800 dark:text-slate-200 w-full sm:w-2/3 break-words">${displayValue}</div>
                            </li>
                        `;
                    }
                    propsHtml += '</ul>';
                }
            } else {
                propsHtml = '<p class="text-sm text-slate-500 text-center italic py-4">ไม่มีข้อมูลเพิ่มเติม (No additional properties)</p>';
            }
            
            document.getElementById('modal-log-props').innerHTML = propsHtml;
            
            // Show Modal
            const modal = document.getElementById('logModal');
            modal.classList.remove('hidden');
            // small timeout to allow display block to apply before transition
            setTimeout(() => {
                modal.classList.add('opacity-100');
            }, 10);
            
        } catch(e) {
            console.error("Error parsing log data", e);
        }
    }

    function closeLogModal() {
        const modal = document.getElementById('logModal');
        modal.classList.add('hidden');
    }

    function openArchivesModal() {
        const modal = document.getElementById('archivesModal');
        modal.classList.remove('hidden');
        
        fetch('{{ route("system-logs.archives") }}')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('archivesList');
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-4 text-sm text-gray-500">ไม่มีไฟล์ Archive ในขณะนี้</td></tr>';
                    return;
                }
                
                let html = '';
                data.forEach(file => {
                    const downloadUrl = '{{ route("system-logs.download-archive", ":filename") }}'.replace(':filename', file.name);
                    html += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6"><i class="fa-solid fa-file-zipper text-gray-400 mr-2"></i> ${file.name}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${file.size} MB</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="${downloadUrl}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Download</a>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            });
    }

    function closeArchivesModal() {
        const modal = document.getElementById('archivesModal');
        modal.classList.add('hidden');
    }
</script>
@endpush
