@extends('layouts.sidebar')
@section('title', 'รายละเอียดพนักงาน : ' . $user->emp_code)

@section('content')
<style>
    /* Clinical Registry Theme Styles */
    .clinical-card {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .dark .clinical-card {
        background: #1e2129;
        border-color: #475569;
    }
    .clinical-card-header {
        background: #f1f5f9;
        border-bottom: 2px solid #cbd5e1;
        padding: 12px 16px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.05em;
    }
    .dark .clinical-card-header {
        background: #121418;
        border-bottom-color: #475569;
        color: #f1f5f9;
    }
    .clinical-card-header.accent-red {
        border-left: 5px solid #D71920;
    }
    .clinical-card-header.accent-blue {
        border-left: 5px solid #3b82f6;
    }
    .clinical-card-header.accent-green {
        border-left: 5px solid #10b981;
    }
    
    /* Grid details table style */
    .clinical-grid-table {
        width: 100%;
        border-collapse: collapse;
    }
    .clinical-grid-table td {
        border: 1px solid #e2e8f0;
        padding: 12px 16px;
        vertical-align: middle;
    }
    .dark .clinical-grid-table td {
        border-color: #334155;
    }
    .clinical-grid-table td.label-cell {
        background-color: #f8fafc;
        font-weight: 600;
        color: #475569;
        width: 25%;
        font-size: 0.85rem;
    }
    .dark .clinical-grid-table td.label-cell {
        background-color: #181b22;
        color: #94a3b8;
    }
    .clinical-grid-table td.value-cell {
        background-color: #ffffff;
        color: #0f172a;
        font-size: 0.95rem;
    }
    .dark .clinical-grid-table td.value-cell {
        background-color: #1e2129;
        color: #e2e8f0;
    }

    /* Badge styles */
    .clinical-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 700;
        border: 1px solid transparent;
    }

    /* Stamp/Seal effect for Registry status */
    .registry-stamp {
        border: 3px double currentColor;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        text-transform: uppercase;
        padding: 8px 16px;
        border-radius: 4px;
        display: inline-block;
        transform: rotate(-3deg);
        letter-spacing: 0.1em;
        font-size: 1.1rem;
    }
</style>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    {{-- Header / Navigation Bar --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-4 border-b-2 border-slate-300 dark:border-slate-700">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded bg-[#D71920] flex items-center justify-center text-white">
                    <i class="fa-solid fa-hospital-user text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">
                        EMPLOYEE CLINICAL REGISTRY
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                        HAMS.REGISTRY.ID // {{ $user->emp_code }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="btn bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:border-slate-600 btn-sm gap-2 rounded">
                <i class="fa-solid fa-arrow-left-long"></i> ย้อนกลับหน้าหลัก
            </a>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-kumwell-red btn-sm gap-2 rounded px-4">
                <i class="fa-solid fa-user-pen"></i> แก้ไขข้อมูลพนักงาน
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Column: Card View / Stamp & Statuses --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Patient ID badge style --}}
            <div class="clinical-card">
                <div class="clinical-card-header accent-red">
                    <i class="fa-solid fa-id-card"></i> บัตรประจำตัวพนักงาน
                </div>
                <div class="p-6 flex flex-col items-center text-center bg-slate-50/50 dark:bg-slate-900/10">
                    
                    {{-- Profile image representation --}}
                    <div class="w-32 h-32 rounded-lg border-4 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 flex flex-col items-center justify-center shadow-inner relative overflow-hidden mb-4">
                        <div class="absolute top-0 left-0 w-full h-1 bg-[#D71920]"></div>
                        <i class="fa-solid fa-user-tie text-5xl text-slate-400 dark:text-slate-500"></i>
                        <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 mt-2 tracking-widest">PHOTO AREA</span>
                    </div>

                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">
                        {{ $user->prefix }} {{ $user->first_name }} {{ $user->last_name }}
                    </h2>
                    <span class="text-sm font-mono font-semibold px-2.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 mt-1">
                        CODE: {{ $user->emp_code }}
                    </span>

                    <div class="w-full border-t-2 border-dashed border-slate-300 dark:border-slate-700 my-4"></div>

                    <div class="w-full space-y-3">
                        <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
                            <span>ประเภท</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $user->employee_type ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
                            <span>ตำแหน่งงาน</span>
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $user->position ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-slate-500 dark:text-slate-400">
                            <span>สังกัดสถานที่</span>
                            <span class="font-bold text-[#D71920]">{{ $user->workplace ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- System Status Registry --}}
            <div class="clinical-card">
                <div class="clinical-card-header accent-blue">
                    <i class="fa-solid fa-shield-halved"></i> SYSTEM AUTHORIZATION
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-xs font-mono font-bold text-slate-400 dark:text-slate-500 mb-2 uppercase">REGISTRY STATUS (สถานะ)</span>
                        @php
                            $statusOptions = \App\Models\User::getStatusOptions();
                            $statusMeta = $statusOptions[$user->status] ?? ['label' => '-', 'color' => 'gray'];
                            $stampClass = match($user->status) {
                                \App\Models\User::STATUS_ACTIVE => 'text-emerald-600 border-emerald-600 dark:text-emerald-400 dark:border-emerald-400',
                                \App\Models\User::STATUS_RESIGN => 'text-rose-600 border-rose-600 dark:text-rose-400 dark:border-rose-400',
                                default => 'text-slate-500 border-slate-500 dark:text-slate-400 dark:border-slate-400'
                            };
                        @endphp
                        <div class="text-center py-2">
                            <div class="registry-stamp {{ $stampClass }}">
                                {{ $statusMeta['label'] }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/20 border border-slate-200 dark:border-slate-700 rounded text-center">
                            <span class="block text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase">ACCESS LEVEL</span>
                            <span class="text-lg font-bold text-slate-800 dark:text-white">LVL {{ $user->level_user }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/20 border border-slate-200 dark:border-slate-700 rounded text-center">
                            <span class="block text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase">HAMS STATUS</span>
                            @php
                                $hamsStatusOptions = \App\Models\User::getHamsStatusOptions();
                                $hamsLabel = $hamsStatusOptions[$user->hr_status]['label'] ?? '-';
                            @endphp
                            <span class="text-xs font-bold text-slate-800 dark:text-white block mt-1 truncate" title="{{ $hamsLabel }}">
                                {{ $hamsLabel }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resignation Details --}}
            @if($user->status == \App\Models\User::STATUS_RESIGN)
            <div class="clinical-card border-rose-500 dark:border-rose-700">
                <div class="clinical-card-header bg-rose-50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-300 border-b-rose-500 border-l-4 border-l-rose-600">
                    <i class="fa-solid fa-circle-exclamation"></i> บันทึกการพ้นสภาพพนักงาน
                </div>
                <div class="p-4 space-y-3 bg-rose-50/20 dark:bg-rose-950/5">
                    <div class="flex justify-between items-center pb-2 border-b border-rose-200 dark:border-rose-900">
                        <span class="text-xs text-rose-700 dark:text-rose-400 font-semibold">วันที่พ้นสภาพ</span>
                        <span class="text-sm font-mono font-bold text-rose-800 dark:text-rose-200">
                            {{ isset($user->endwork_date) ? $user->endwork_date->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-rose-700 dark:text-rose-400 font-semibold block mb-1">เหตุผลและหมายเหตุ</span>
                        <div class="p-3 bg-white dark:bg-slate-800 border border-rose-200 dark:border-rose-900 rounded text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-mono">
                            {{ $user->endwork_comment ?: 'ไม่ระบุเหตุผล' }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Detailed Grid Tables --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Personal Registry Table --}}
            <div class="clinical-card">
                <div class="clinical-card-header accent-red">
                    <i class="fa-solid fa-file-invoice"></i> SECTION I: REGISTRATION DETAILS (ข้อมูลส่วนตัวหลัก)
                </div>
                <table class="clinical-grid-table">
                    <tbody>
                        <tr>
                            <td class="label-cell"><i class="fa-solid fa-user-tag mr-1 text-slate-400"></i> คำนำหน้า</td>
                            <td class="value-cell font-semibold">{{ $user->prefix ?: '-' }}</td>
                            <td class="label-cell"><i class="fa-solid fa-venus-mars mr-1 text-slate-400"></i> เพศ</td>
                            <td class="value-cell font-semibold">{{ $user->sex ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell"><i class="fa-solid fa-signature mr-1 text-slate-400"></i> ชื่อจริง</td>
                            <td class="value-cell font-bold text-slate-800 dark:text-white">{{ $user->first_name ?: '-' }}</td>
                            <td class="label-cell"><i class="fa-solid fa-signature mr-1 text-slate-400"></i> นามสกุล</td>
                            <td class="value-cell font-bold text-slate-800 dark:text-white">{{ $user->last_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell"><i class="fa-regular fa-calendar-check mr-1 text-slate-400"></i> วันที่เริ่มงาน</td>
                            <td class="value-cell font-mono">{{ isset($user->startwork_date) ? $user->startwork_date->format('d/m/Y') : '-' }}</td>
                            <td class="label-cell"><i class="fa-solid fa-business-time mr-1 text-slate-400"></i> อายุงานรวม</td>
                            <td class="value-cell font-semibold text-slate-700 dark:text-slate-300">
                                @if($user->startwork_date)
                                    {{ \Carbon\Carbon::parse($user->startwork_date)->diffForHumans(null, true) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Professional & Work Registry Table --}}
            <div class="clinical-card">
                <div class="clinical-card-header accent-green">
                    <i class="fa-solid fa-building-user"></i> SECTION II: PROFESSIONAL & POSITION ASSIGNMENT (ข้อมูลงาน)
                </div>
                <table class="clinical-grid-table">
                    <tbody>
                        <tr>
                            <td class="label-cell" style="width: 20%;"><i class="fa-solid fa-sitemap mr-1 text-slate-400"></i> ตำแหน่งปัจจุบัน</td>
                            <td class="value-cell font-bold text-[#D71920]" colspan="3">
                                {{ $user->position ?: '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-cell" style="width: 20%;"><i class="fa-solid fa-briefcase mr-1 text-slate-400"></i> ฝ่าย (Division)</td>
                            <td class="value-cell font-semibold" style="width: 30%;">{{ $user->division->division_name ?? '-' }}</td>
                            <td class="label-cell" style="width: 20%;"><i class="fa-solid fa-network-wired mr-1 text-slate-400"></i> แผนก (Department)</td>
                            <td class="value-cell font-semibold" style="width: 30%;">{{ $user->department->department_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell"><i class="fa-solid fa-diagram-project mr-1 text-slate-400"></i> สายงาน (Section)</td>
                            <td class="value-cell font-semibold">{{ $user->section->section_name ?? '-' }}</td>
                            <td class="label-cell"><i class="fa-solid fa-id-badge mr-1 text-slate-400"></i> ประเภทพนักงาน</td>
                            <td class="value-cell font-semibold">{{ $user->employee_type ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-cell"><i class="fa-solid fa-map-location-dot mr-1 text-slate-400"></i> สถานที่ทำงาน</td>
                            <td class="value-cell font-semibold" colspan="3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#D71920]"></span>
                                    <span>{{ $user->workplace ?: '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Audit/Metadata Log Info --}}
            <div class="p-4 bg-slate-100 dark:bg-slate-900 border-2 border-slate-300 dark:border-slate-700 rounded-lg flex flex-col md:flex-row justify-between items-center text-[11px] font-mono text-slate-500 dark:text-slate-400 gap-4">
                <span>SYSTEM REFERENCE: HAMS-USR-{{ $user->id }}</span>
                <span>LAST MODIFIED: {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</span>
            </div>

        </div>

    </div>
</div>
@endsection
