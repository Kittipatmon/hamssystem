@extends('layouts.sidebar')

@section('content')
<!-- Header Area -->
<div class="flex justify-between items-center mb-6 bg-white dark:bg-zinc-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-zinc-800">
    <div class="flex-1 text-center sm:text-left sm:pl-4">
        <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-[#5942e9]"></i> การแจ้งเตือนการโจมตีระบบ (Security Alerts)
        </h2>
    </div>
</div>

<!-- Stats Grid - 4 cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <!-- Stat 1: Failed logins today -->
    <div class="sa-stat-card group" style="animation-delay: 0ms">
        <div class="sa-stat-icon bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400">
            <i class="fa-solid fa-user-xmark text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">ล็อกอินล้มเหลววันนี้</h3>
            <p class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter leading-none">{{ $totalFailsToday }}</p>
        </div>
    </div>

    <!-- Stat 2: High risk today -->
    <div class="sa-stat-card group" style="animation-delay: 60ms">
        <div class="sa-stat-icon bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">ความเสี่ยงสูง (High Risk) วันนี้</h3>
            <p class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter leading-none">{{ $highRiskToday }}</p>
        </div>
    </div>

    <!-- Stat 3: Banned IPs -->
    <div class="sa-stat-card group" style="animation-delay: 120ms">
        <div class="sa-stat-icon bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
            <i class="fa-solid fa-ban text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">IP ที่ถูกบล็อก</h3>
            <p class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter leading-none">{{ $bannedIpCount }}</p>
        </div>
    </div>

    <!-- Stat 4: Unique IPs this month -->
    <div class="sa-stat-card group" style="animation-delay: 180ms">
        <div class="sa-stat-icon bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400">
            <i class="fa-solid fa-globe text-xl"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">Unique IPs เดือนนี้</h3>
            <div class="flex items-end gap-2">
                <p class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter leading-none">{{ $uniqueIpsThisMonth }}</p>
                @if($trendPercentage != 0)
                <span class="inline-flex items-center gap-0.5 text-[11px] font-bold px-1.5 py-0.5 rounded-full mb-0.5
                    {{ $trendPercentage > 0 ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400' }}">
                    <i class="fa-solid {{ $trendPercentage > 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-[9px]"></i>
                    {{ abs($trendPercentage) }}%
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All-time Summary Strip -->
<div class="sa-alltime-strip mb-6" style="animation-delay: 240ms">
    <div class="flex items-center gap-2 text-[13px]">
        <i class="fa-solid fa-database text-indigo-500 dark:text-indigo-400"></i>
        <span class="font-semibold text-slate-700 dark:text-slate-300">สถิติรวมทั้งหมด:</span>
        <span class="font-black text-slate-900 dark:text-white">{{ number_format($totalAllTime) }}</span>
        <span class="text-slate-500 dark:text-slate-400">ครั้ง</span>
        <span class="text-slate-300 dark:text-slate-600 mx-1">|</span>
        <span class="text-slate-500 dark:text-slate-400">ตั้งแต่</span>
        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $firstAlertDate }}</span>
        <span class="text-slate-300 dark:text-slate-600 mx-1">|</span>
        <span class="text-slate-500 dark:text-slate-400">เดือนนี้</span>
        <span class="font-black {{ $thisMonthCount > $lastMonthCount ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">{{ $thisMonthCount }}</span>
        <span class="text-slate-400 dark:text-slate-500 text-xs">vs เดือนก่อน</span>
        <span class="font-semibold text-slate-600 dark:text-slate-400">{{ $lastMonthCount }}</span>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <!-- Chart 1: Monthly Login Failures (spans 2 cols) -->
    <div class="xl:col-span-2 sa-chart-card" style="animation-delay: 300ms">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-black text-slate-800 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-chart-column text-indigo-600 dark:text-indigo-400 text-sm"></i>
                </span>
                Login Failures ต่อเดือน
            </h3>
            <span class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-zinc-800 px-2.5 py-1 rounded-full">12 เดือนย้อนหลัง</span>
            <span class="sa-click-hint"><i class="fa-solid fa-hand-pointer text-indigo-400"></i> คลิกแท่งเพื่อดูรายละเอียด</span>
        </div>
        <div class="h-[280px]">
            <canvas id="monthlyFailuresChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Top 10 IPs -->
    <div class="sa-chart-card" style="animation-delay: 400ms">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-black text-slate-800 dark:text-white flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-crosshairs text-rose-600 dark:text-rose-400 text-sm"></i>
                </span>
                Top 10 IPs
            </h3>
        </div>
        <div class="h-[280px]">
            <canvas id="topIpsChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart 3: Risk Breakdown (full width) -->
<div class="sa-chart-card mb-6" style="animation-delay: 460ms">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-base font-black text-slate-800 dark:text-white flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                <i class="fa-solid fa-layer-group text-amber-600 dark:text-amber-400 text-sm"></i>
            </span>
            High Risk vs Low Risk ต่อเดือน
        </h3>
        <div class="flex items-center gap-4 text-xs font-semibold">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#ef4444] inline-block"></span> <span class="text-slate-500 dark:text-slate-400">High Risk</span></span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#f59e0b] inline-block"></span> <span class="text-slate-500 dark:text-slate-400">Low Risk</span></span>
        </div>
    </div>
    <div class="h-[240px]">
        <canvas id="riskBreakdownChart"></canvas>
    </div>
</div>

<!-- Filter Area -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 mb-6 shadow-sm">
    <form method="GET" action="{{ route('security-alerts.index') }}" id="filterForm">
        <div class="flex flex-col lg:flex-row items-end gap-4">
            <div class="w-full lg:w-1/4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">ค้นหา (IP, Email, Username)</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:bg-zinc-800 dark:border-zinc-700 dark:text-white transition-colors" placeholder="พิมพ์คำค้นหา...">
            </div>
            <div class="w-full lg:w-1/6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">ระดับความเสี่ยง</label>
                <select name="risk_level" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:bg-zinc-800 dark:border-zinc-700 dark:text-white transition-colors">
                    <option value="">ทั้งหมด</option>
                    <option value="high" {{ request('risk_level') == 'high' ? 'selected' : '' }}>ความเสี่ยงสูง (เดาสุ่ม)</option>
                </select>
            </div>
            <div class="w-full lg:w-1/6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    <i class="fa-regular fa-calendar text-indigo-500 mr-1"></i>เดือน
                </label>
                <select name="filter_month" id="filterMonth" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:bg-zinc-800 dark:border-zinc-700 dark:text-white transition-colors">
                    <option value="">ทุกเดือน</option>
                    @php
                        $thaiMonths = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
                    @endphp
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $filterMonth == $m ? 'selected' : '' }}>{{ $thaiMonths[$m] }}</option>
                    @endfor
                </select>
            </div>
            <div class="w-full lg:w-1/6">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    <i class="fa-regular fa-calendar-check text-indigo-500 mr-1"></i>ปี (พ.ศ.)
                </label>
                <select name="filter_year" id="filterYear" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:bg-zinc-800 dark:border-zinc-700 dark:text-white transition-colors">
                    <option value="">ทุกปี</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ $filterYear == $yr ? 'selected' : '' }}>{{ $yr + 543 }} ({{ $yr }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="bg-[#5942e9] hover:bg-indigo-700 text-white px-5 py-2 rounded-md text-sm font-medium shadow-sm transition-colors">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> ค้นหา
                </button>
                @if(request()->hasAny(['search', 'risk_level', 'filter_month', 'filter_year']))
                    <a href="{{ route('security-alerts.index') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300 text-sm underline whitespace-nowrap">ล้างค่า</a>
                @endif
            </div>
        </div>
    </form>

    {{-- Active filter badge --}}
    @if($filterMonth || $filterYear)
    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center gap-2 flex-wrap">
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">กำลังกรอง:</span>
        @if($filterMonth && $filterYear)
            <span class="sa-filter-badge">
                <i class="fa-solid fa-filter text-[9px]"></i>
                {{ $thaiMonths[(int)$filterMonth] }} {{ $filterYear + 543 }}
                <a href="{{ route('security-alerts.index', array_merge(request()->except(['filter_month', 'filter_year']))) }}" class="ml-1 hover:text-red-600 dark:hover:text-red-400">&times;</a>
            </span>
        @elseif($filterYear)
            <span class="sa-filter-badge">
                <i class="fa-solid fa-filter text-[9px]"></i>
                ปี {{ $filterYear + 543 }}
                <a href="{{ route('security-alerts.index', array_merge(request()->except(['filter_year']))) }}" class="ml-1 hover:text-red-600 dark:hover:text-red-400">&times;</a>
            </span>
        @endif
        <span class="text-xs text-slate-400 dark:text-slate-500">
            ({{ $groupedLogs->count() }} รายการ)
        </span>
    </div>
    @endif
</div>

<!-- Table Area -->
<div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm">
    <div class="overflow-x-auto">
        <table id="security-table" class="w-full text-left text-sm whitespace-nowrap stripe hover">
            <thead class="text-slate-500 dark:text-slate-400 font-medium border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-4 py-3 font-semibold">วันที่/เวลาล่าสุด</th>
                    <th class="px-4 py-3 font-semibold">IP Address ล่าสุด</th>
                    <th class="px-4 py-3 font-semibold">Email / Username</th>
                    <th class="px-4 py-3 font-semibold text-center">จำนวนครั้งทั้งหมด</th>
                    <th class="px-4 py-3 font-semibold">ระดับความเสี่ยงรวม</th>
                    <th class="px-4 py-3 font-semibold">รายละเอียดล่าสุด</th>
                    <th class="px-4 py-3 font-semibold text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                @foreach($groupedLogs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-4">{{ $log->latest_date }}</td>
                    <td class="px-4 py-4 font-mono text-slate-600 dark:text-slate-400">
                        {{ $log->latest_ip }}
                    </td>
                    <td class="px-4 py-4 font-semibold text-indigo-600 dark:text-indigo-400">{{ $log->email }}</td>
                    <td class="px-4 py-4 text-center">
                        <span class="inline-block bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 px-3 py-1 rounded-full text-xs font-bold border border-indigo-200">
                            {{ $log->total_attempts }} ครั้ง
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        @if($log->risk)
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border bg-[#fce8e6] text-[#c5221f] border-[#fad2cf]">
                                ความเสี่ยงสูง (High Risk)
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border bg-[#fef7e0] text-[#e37400] border-[#fce8b2]">
                                ปกติ (Low)
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-normal break-words max-w-xs text-slate-600 dark:text-slate-400 text-xs">
                        {{ $log->description }}
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <button onclick="showDetailsModal('{{ $log->email }}', {{ json_encode($log->history) }})" class="text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 px-2.5 py-1.5 rounded-md text-xs font-medium transition-colors border border-indigo-100 dark:border-indigo-850 flex items-center gap-1 shadow-sm">
                                <i class="fa-regular fa-eye text-xs"></i> ดูรายละเอียด
                            </button>
                            
                            @if($log->latest_ip && $log->latest_ip !== 'Unknown')
                                @php
                                    $isBanned = in_array($log->latest_ip, $bannedIps ?? []);
                                    $safeIpId = str_replace(['.', ':'], '-', $log->latest_ip);
                                @endphp
                                <div class="flex items-center gap-2 justify-center" title="บล็อก IP ล่าสุด">
                                    <label class="relative inline-flex items-center cursor-pointer select-none m-0">
                                        <input type="checkbox" class="sr-only peer ban-checkbox-{{ $safeIpId }}" onchange="toggleBanIp('{{ $log->latest_ip }}', this.checked, '{{ $safeIpId }}')" {{ $isBanned ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-2.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-[#007bff]"></div>
                                    </label>
                                    <span class="text-xs font-medium text-slate-600 dark:text-slate-400 ban-label-{{ $safeIpId }} w-14 text-start">{{ $isBanned ? 'บล็อกแล้ว' : 'แบน IP ล่าสุด' }}</span>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/50 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-zinc-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl border border-slate-200 dark:border-zinc-800">
            <div class="bg-white dark:bg-zinc-900 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start w-full">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa-solid fa-triangle-exclamation text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 class="text-base font-semibold leading-6 text-slate-900 dark:text-white" id="modal-title">
                            ประวัติความพยายามเข้าสู่ระบบของ: <span id="modal-user-email" class="text-indigo-600 dark:text-indigo-400 font-bold"></span>
                        </h3>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">รายการความผิดพลาดในการยืนยันตัวตนแยกตามวันที่และไอพีแอดเดรส</p>
                        
                        <!-- History Table -->
                        <div class="mt-5 border border-slate-200 dark:border-zinc-800 rounded-lg overflow-hidden">
                            <div class="max-h-[400px] overflow-y-auto">
                                <table class="w-full text-left text-xs whitespace-nowrap">
                                    <thead class="bg-slate-50 dark:bg-zinc-950 text-slate-500 dark:text-slate-400 font-medium border-b border-slate-200 dark:border-zinc-800">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">วันที่/เวลา</th>
                                            <th class="px-4 py-3 font-semibold">IP Address</th>
                                            <th class="px-4 py-3 font-semibold text-center">จำนวนครั้ง</th>
                                            <th class="px-4 py-3 font-semibold">ระดับความเสี่ยง</th>
                                            <th class="px-4 py-3 font-semibold">รายละเอียด</th>
                                            <th class="px-4 py-3 font-semibold text-center">จัดการ IP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal-history-tbody" class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                                        <!-- Dynamic rows will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-zinc-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-zinc-800">
                <button type="button" onclick="closeDetailsModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-zinc-850 px-4 py-2 text-sm font-semibold text-slate-900 dark:text-slate-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 sm:mt-0 sm:w-auto">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* ===== Security Alerts Custom Styles ===== */

    /* Stat Cards */
    .sa-stat-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        transition: all .25s cubic-bezier(.4,0,.2,1);
        animation: sa-fadeUp .5s ease both;
        position: relative;
        overflow: hidden;
    }
    .sa-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #5942e9 0%, #7c6cf0 50%, #a78bfa 100%);
        opacity: 0;
        transition: opacity .25s ease;
    }
    .sa-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(89,66,233,.1);
        border-color: #c7c0f5;
    }
    .sa-stat-card:hover::before {
        opacity: 1;
    }

    .dark .sa-stat-card {
        background: #18181b;
        border-color: #27272a;
    }
    .dark .sa-stat-card:hover {
        box-shadow: 0 8px 24px rgba(89,66,233,.2);
        border-color: #5942e9;
    }

    .sa-stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform .3s ease;
    }
    .sa-stat-card:hover .sa-stat-icon {
        transform: scale(1.08);
    }

    /* All-time Strip */
    .sa-alltime-strip {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        border: 1px solid #e0e7ff;
        border-radius: 10px;
        padding: 12px 20px;
        animation: sa-fadeUp .5s ease both;
    }
    .dark .sa-alltime-strip {
        background: linear-gradient(135deg, #18181b 0%, #1e1b4b 100%);
        border-color: #312e81;
    }

    /* Chart Cards */
    .sa-chart-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
        animation: sa-fadeUp .6s ease both;
        transition: box-shadow .25s ease;
    }
    .sa-chart-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.06);
    }
    .dark .sa-chart-card {
        background: #18181b;
        border-color: #27272a;
    }
    .dark .sa-chart-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,.3);
    }

    /* Animations */
    @keyframes sa-fadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Filter Badge */
    .sa-filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border: 1px solid #c7d2fe;
        color: #4338ca;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        transition: all .2s ease;
    }
    .sa-filter-badge:hover {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    }
    .dark .sa-filter-badge {
        background: linear-gradient(135deg, #312e81, #1e1b4b);
        border-color: #4338ca;
        color: #a5b4fc;
    }

    /* Clickable chart hint */
    .sa-chart-clickable {
        cursor: pointer;
    }
    .sa-click-hint {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 500;
        color: #94a3b8;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        transition: all .2s ease;
    }
    .dark .sa-click-hint {
        background: #27272a;
        color: #64748b;
    }

    /* DataTables overrides */
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    // ===== DataTable =====
    $(document).ready(function() {
        $('#security-table').DataTable({
            "order": [[ 0, "desc" ]],
            "language": {
                "lengthMenu": "แสดง _MENU_ รายการต่อหน้า",
                "zeroRecords": "ไม่พบข้อมูล",
                "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                "infoEmpty": "ไม่มีข้อมูล",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "search": "ค้นหาในตาราง:",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            }
        });
    });

    // ===== Charts =====
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // Shared defaults
        Chart.defaults.font.family = "'Inter', 'IBM Plex Sans Thai', system-ui, sans-serif";
        Chart.defaults.color = textColor;

        // Data from backend
        const monthLabels = @json($monthLabels);
        const monthTotals = @json($monthTotals);
        const monthHighRisk = @json($monthHighRisk);
        const monthLowRisk = @json($monthLowRisk);
        const topIps = @json($topIps);

        // Month keys for chart click-to-filter (YYYY-MM format)
        const monthKeys = [];
        for (let i = 11; i >= 0; i--) {
            const d = new Date();
            d.setMonth(d.getMonth() - i);
            monthKeys.push({ year: d.getFullYear(), month: d.getMonth() + 1 });
        }

        // Helper: navigate to filtered view by month/year
        function navigateToMonth(index) {
            if (index < 0 || index >= monthKeys.length) return;
            const mk = monthKeys[index];
            const url = new URL(window.location.href);
            url.searchParams.set('filter_year', mk.year);
            url.searchParams.set('filter_month', mk.month);
            url.searchParams.delete('search');
            url.searchParams.delete('risk_level');
            window.location.href = url.toString();
        }

        // ---- Chart 1: Monthly Login Failures ----
        const ctx1 = document.getElementById('monthlyFailuresChart').getContext('2d');
        const gradient1 = ctx1.createLinearGradient(0, 0, 0, 280);
        gradient1.addColorStop(0, 'rgba(89, 66, 233, 0.85)');
        gradient1.addColorStop(1, 'rgba(89, 66, 233, 0.25)');

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'จำนวนครั้ง',
                    data: monthTotals,
                    backgroundColor: gradient1,
                    borderColor: '#5942e9',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: '#5942e9',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        navigateToMonth(elements[0].index);
                    }
                },
                onHover: (evt, elements) => {
                    evt.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#27272a' : '#1e293b',
                        titleFont: { weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => `${ctx.parsed.y} ครั้ง`,
                            afterLabel: () => '🔍 คลิกเพื่อดูรายละเอียด'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: {
                            font: { size: 10 },
                            precision: 0
                        }
                    }
                }
            }
        });

        // ---- Chart 2: Top 10 IPs (Horizontal Bar) ----
        const ipLabels = topIps.map(i => i.ip);
        const ipTotals = topIps.map(i => i.total);

        const ctx2 = document.getElementById('topIpsChart').getContext('2d');
        const gradient2 = ctx2.createLinearGradient(0, 0, 400, 0);
        gradient2.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
        gradient2.addColorStop(1, 'rgba(239, 68, 68, 0.85)');

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ipLabels,
                datasets: [{
                    label: 'จำนวนครั้ง',
                    data: ipTotals,
                    backgroundColor: gradient2,
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 4,
                    borderSkipped: false,
                    hoverBackgroundColor: '#ef4444',
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1400,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#27272a' : '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (ctx) => `${ctx.parsed.x} ครั้ง`
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { precision: 0, font: { size: 10 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10, family: "'JetBrains Mono', monospace", weight: '500' },
                            callback: function(value) {
                                const label = this.getLabelForValue(value);
                                return label.length > 15 ? label.substring(0, 15) + '…' : label;
                            }
                        }
                    }
                }
            }
        });

        // ---- Chart 3: Risk Breakdown (Stacked Bar) ----
        const ctx3 = document.getElementById('riskBreakdownChart').getContext('2d');

        new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [
                    {
                        label: 'High Risk',
                        data: monthHighRisk,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: '#ef4444',
                        borderWidth: 1,
                        borderRadius: { topLeft: 6, topRight: 6 },
                        borderSkipped: false,
                        hoverBackgroundColor: '#ef4444',
                    },
                    {
                        label: 'Low Risk',
                        data: monthLowRisk,
                        backgroundColor: 'rgba(245, 158, 11, 0.6)',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: { bottomLeft: 6, bottomRight: 6 },
                        borderSkipped: false,
                        hoverBackgroundColor: '#f59e0b',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (evt, elements) => {
                    if (elements.length > 0) {
                        navigateToMonth(elements[0].index);
                    }
                },
                onHover: (evt, elements) => {
                    evt.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                },
                animation: {
                    duration: 1600,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#27272a' : '#1e293b',
                        titleFont: { weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y} ครั้ง`,
                            afterBody: () => '\n🔍 คลิกเพื่อดูรายละเอียด'
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' } }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { precision: 0, font: { size: 10 } }
                    }
                }
            }
        });
    });

    // ===== IP Location Check =====
    function checkIpLocation(ip) {
        if(typeof Swal === 'undefined') {
            alert('ไม่สามารถโหลดหน้าต่างแจ้งเตือนได้ โปรดรีเฟรชหน้าเว็บ');
            return;
        }

        Swal.fire({
            title: 'กำลังตรวจสอบพิกัด...',
            text: 'กรุณารอสักครู่',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`http://ip-api.com/json/${ip}`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const mapsLink = `https://www.google.com/maps?q=${data.lat},${data.lon}`;
                    Swal.fire({
                        title: 'ข้อมูลพิกัด (IP Location)',
                        html: `
                            <div class="text-left text-sm mt-4 space-y-3 bg-slate-50 dark:bg-zinc-800 p-4 rounded-lg border border-slate-200 dark:border-zinc-700">
                                <p><strong class="text-slate-700 dark:text-slate-300">IP:</strong> <span class="text-slate-600 dark:text-slate-400">${data.query}</span></p>
                                <p><strong class="text-slate-700 dark:text-slate-300">ประเทศ:</strong> <span class="text-slate-600 dark:text-slate-400">${data.country} (${data.countryCode})</span></p>
                                <p><strong class="text-slate-700 dark:text-slate-300">เมือง/ภูมิภาค:</strong> <span class="text-slate-600 dark:text-slate-400">${data.city}, ${data.regionName}</span></p>
                                <p><strong class="text-slate-700 dark:text-slate-300">ISP:</strong> <span class="text-slate-600 dark:text-slate-400">${data.isp}</span></p>
                                <p><strong class="text-slate-700 dark:text-slate-300">พิกัด:</strong> <span class="text-slate-600 dark:text-slate-400">${data.lat}, ${data.lon}</span></p>
                                <div class="mt-5 text-center">
                                    <a href="${mapsLink}" target="_blank" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-md transition-colors font-medium shadow-sm">
                                        <i class="fa-solid fa-map-location-dot"></i> ดูบน Google Maps
                                    </a>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'ปิด',
                        confirmButtonColor: '#64748b'
                    });
                } else {
                    Swal.fire('ข้อผิดพลาด', 'ไม่สามารถค้นหาข้อมูลพิกัดสำหรับ IP นี้ได้ (อาจเป็น Private IP หรือ Localhost)', 'warning');
                }
            })
            .catch(err => {
                Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ API ตรวจสอบพิกัด', 'error');
            });
    }

    // ===== Toggle Ban IP =====
    function toggleBanIp(ip, isBanned, safeIpId) {
        fetch('{{ route("security-alerts.toggle-ban") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ip: ip, is_banned: isBanned })
        })
        .then(async res => {
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                if (data && data.message) {
                    throw new Error(data.message);
                }
                if (res.status === 419) {
                    throw new Error('เซสชันหมดอายุ กรุณารีเฟรชหน้าเว็บแล้วลองใหม่');
                }
                throw new Error('เซิร์ฟเวอร์เกิดข้อผิดพลาด (Status ' + res.status + ')');
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                // Update all labels for this IP if it appears multiple times
                document.querySelectorAll('.ban-label-' + safeIpId).forEach(el => {
                    el.textContent = isBanned ? 'บล็อกแล้ว' : 'แบน IP';
                });
                
                // Update all checkboxes for this IP
                document.querySelectorAll('.ban-checkbox-' + safeIpId).forEach(el => {
                    if (el !== event.target) {
                        el.checked = isBanned;
                    }
                });

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            } else {
                Swal.fire('ข้อผิดพลาด', data.message || 'เกิดข้อผิดพลาด', 'error');
                // Revert checkbox
                document.querySelectorAll('.ban-checkbox-' + safeIpId).forEach(el => {
                    el.checked = !isBanned;
                });
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('ข้อผิดพลาด', err.message || 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            // Revert checkbox
            document.querySelectorAll('.ban-checkbox-' + safeIpId).forEach(el => {
                el.checked = !isBanned;
            });
        });
    }

    // ===== Details Modal =====
    function showDetailsModal(email, history) {
        document.getElementById('modal-user-email').innerText = email;
        const tbody = document.getElementById('modal-history-tbody');
        tbody.innerHTML = '';
        
        const bannedIps = @json($bannedIps ?? []);

        history.forEach(item => {
            const isBanned = bannedIps.includes(item.ip);
            const safeIpId = item.ip.replace(/\./g, '-').replace(/:/g, '-');
            const checkboxId = `modal-ban-${safeIpId}-${Math.floor(Math.random() * 1000000)}`;

            const riskBadge = item.risk === 'High' 
                ? `<span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border bg-[#fce8e6] text-[#c5221f] border-[#fad2cf]">ความเสี่ยงสูง (High Risk)</span>`
                : `<span class="px-2.5 py-0.5 rounded text-[11px] font-semibold border bg-[#fef7e0] text-[#e37400] border-[#fce8b2]">ปกติ (Low)</span>`;

            const banToggleHtml = item.ip !== 'Unknown' 
                ? `
                <div class="flex items-center gap-2 justify-center" title="บล็อก IP นี้">
                    <label class="relative inline-flex items-center cursor-pointer select-none m-0">
                        <input type="checkbox" id="${checkboxId}" class="sr-only peer ban-checkbox-${safeIpId}" onchange="toggleBanIp('${item.ip}', this.checked, '${safeIpId}')" ${isBanned ? 'checked' : ''}>
                        <div class="w-11 h-6 bg-slate-200 dark:bg-zinc-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-2.5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-[#007bff]"></div>
                    </label>
                    <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 ban-label-${safeIpId} w-14 text-start">${isBanned ? 'บล็อกแล้ว' : 'แบน IP'}</span>
                </div>
                `
                : '-';

            const row = `
                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3">${item.date}</td>
                    <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">${item.ip}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block bg-slate-100 dark:bg-zinc-850 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-full font-bold border border-slate-200">
                            ${item.attempts}
                        </span>
                    </td>
                    <td class="px-4 py-3">${riskBadge}</td>
                    <td class="px-4 py-3 whitespace-normal break-words max-w-xs text-slate-600 dark:text-slate-400">${item.description}</td>
                    <td class="px-4 py-3 text-center">${banToggleHtml}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });

        const modal = document.getElementById('detailsModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush
