@extends('layouts.serviceitem.appservice')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap');

        :root {
            --bg: #fafbfd;
            --border: #cbd5e1;
            --accent: #dc2626;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --success: #059669;
            --danger: #ef4444;
            --warning: #d97706;
            --pending: #2563eb;
        }

        body {
            background-color: var(--bg);
        }

        .db-wrap {
            font-family: 'Outfit', 'IBM Plex Sans Thai', sans-serif;
            min-height: 100vh;
            max-width: 90rem;
            margin: 0 auto;
            position: relative;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: 1px solid #cbd5e1;
            transition: all 0.15s ease-in-out;
            letter-spacing: 0.02em;
        }

        .btn-ghost {
            background: white;
            color: #475569;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        .field-input {
            width: 100%;
            height: 36px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 0 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            font-family: inherit;
            outline: none;
            transition: all 0.15s ease;
        }

        .field-input:focus {
            border-color: var(--accent);
            background: white;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1.1;
            font-family: 'IBM Plex Mono', monospace;
            margin-top: 2px;
        }

        /* ── Loading Overlay ── */
        #loadingOverlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.2);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #loadingOverlay.hidden {
            display: none !important;
        }

        .loading-box {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 24px 32px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            width: 90%;
            max-width: 320px;
        }

        .spinner {
            width: 36px;
            height: 36px;
            border: 3px solid #cbd5e1;
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* ── Top Items List ── */
        .top-items-list {
            margin: 1rem 0 0 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .top-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed #e2e8f0;
        }

        .top-item:last-child {
            border-bottom: none;
        }

        .top-item__dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .top-item__name {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .top-item__qty {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            font-weight: 800;
            color: var(--text-primary);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>

    <div class="db-wrap max-w-[90rem] mx-auto px-4 py-6 space-y-6">

        <!-- Header Section -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-10">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-red-600 rounded flex items-center justify-center shadow text-white">
                    <i class="fa-solid fa-square-poll-vertical text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-800 uppercase tracking-wide">สรุปรายงานสถิติการเบิกพัสดุ</h1>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">วิเคราะห์สถิติจำนวนชิ้นงานและการเบิกงบประมาณเชิงลึกขององค์กร</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button id="btnResetFilters" type="button" class="btn btn-ghost">
                    <i class="fa-solid fa-arrows-rotate"></i> รีเซ็ตตัวกรอง
                </button>
                <button id="btnExportPdf" type="button" class="btn btn-primary">
                    <i class="fa-solid fa-file-pdf"></i> ส่งออก PDF
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm space-y-4">
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-filter"></i> ตัวคัดกรองข้อมูลสรุปวิเคราะห์
            </div>
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-semibold">
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">เลือกปีปฏิทิน (Year)</span>
                    <select name="year" class="field-input">
                        <option value="">ทั้งหมดทุกปี</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>ปี {{ $y + 543 }} ({{ $y }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">ฝ่าย / แผนกผู้เบิก (Dept)</span>
                    <select name="department" class="field-input" data-cascade="department">
                        <option value="">ทั้งหมดทุกแผนก</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->dept_id }}">{{ $d->department_name }} ({{ $d->department_fullname }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <span class="text-[10px] font-bold text-slate-400 uppercase">ค้นหา (Search query)</span>
                    <input type="text" id="searchInput" name="search" placeholder="ระบุชื่อผู้เบิกพัสดุ หรือ คีย์เวิร์ด..." class="field-input">
                </div>
            </form>
        </div>

        <!-- Summary Stats (5-column Grid to match reqlistall layout) -->
        <div id="summaryCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Pending -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded border border-blue-200 flex items-center justify-center">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">รอดำเนินการ</div>
                    <div class="stat-value" data-summary="pending">{{ $pendingRequisitions }}</div>
                </div>
            </div>
            <!-- Finished -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded border border-emerald-200 flex items-center justify-center">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">เสร็จสิ้นแล้ว</div>
                    <div class="stat-value text-emerald-600" data-summary="approved">{{ $approvedRequisitions }}</div>
                </div>
            </div>
            <!-- Cancelled -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded border border-red-200 flex items-center justify-center">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ยกเลิกรายการ</div>
                    <div class="stat-value text-red-600" data-summary="cancelled">{{ $cancelledRequisitions }}</div>
                </div>
            </div>
            <!-- Rejected -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex items-center gap-3.5 text-xs font-semibold">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded border border-amber-200 flex items-center justify-center">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">ไม่อนุมัติ</div>
                    <div class="stat-value text-amber-600" data-summary="rejected">{{ $rejectedRequisitions ?? 0 }}</div>
                </div>
            </div>
            <!-- Total -->
            <div class="bg-slate-900 p-5 rounded border border-slate-900 shadow-sm flex items-center gap-3.5 text-xs font-semibold text-white">
                <div class="w-10 h-10 bg-white/10 text-white rounded border border-white/20 flex items-center justify-center">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider" style="color: rgba(255, 255, 255, 0.6) !important;">รวมคำขอเบิก</div>
                    <div class="stat-value" style="color: #ffffff !important;" data-summary="total">{{ $totalRequisitions }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Bar Chart -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col min-h-[400px]">
                <div class="flex items-start justify-between border-b border-slate-100 pb-3 mb-4">
                    <div>
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">สถิติความคืบหน้ารายเดือน</p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">Monthly Status breakdown</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:10px;" class="font-bold text-slate-500">
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" id="chkBarLegend"> Legend
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" id="chkBarPercent"> % Mode
                        </label>
                    </div>
                </div>
                <div class="relative flex-1 min-h-0">
                    <canvas id="monthlyBarChart"></canvas>
                </div>
            </div>

            <!-- Top Items Donut -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col min-h-[400px]">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">พัสดุยอดนิยม (TOP 5)</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Top 5 requested items</p>
                </div>
                <div class="relative flex-1 min-h-[180px] max-h-[200px]">
                    <canvas id="topItemsDonut"></canvas>
                </div>
                <ul id="topItemsList" class="top-items-list mt-4 flex-1"></ul>
            </div>

            <!-- Monthly Totals Line -->
            <div class="bg-white p-5 rounded border border-slate-200 shadow-sm flex flex-col min-h-[400px]">
                <div class="border-b border-slate-100 pb-3 mb-4">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">อัตราแนวโน้มการเบิกสะสม</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Monthly quantity trend</p>
                </div>
                <div class="relative flex-1 min-h-0">
                    <canvas id="monthlyTotalsLine"></canvas>
                </div>
            </div>
        </div>

        <!-- Full-Width Expense Chart -->
        <div class="bg-white p-5 rounded border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-start justify-between border-b border-slate-100 pb-3">
                <div>
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">สรุปงบประมาณค่าใช้จ่ายสะสมรายเดือน</p>
                    <p class="text-[9px] text-slate-400 font-bold uppercase">Monthly Expenditure value (THB)</p>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;font-size:10px;" class="font-bold text-slate-500">
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="checkbox" id="chkFilterRows" checked> Filter Results
                    </label>
                    <div class="w-px h-4 bg-slate-200"></div>
                    <div class="realtime-badge bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded text-emerald-600 font-bold text-[9px] uppercase tracking-wider flex items-center gap-1">
                        <div class="realtime-dot"></div> Live Sync
                    </div>
                </div>
            </div>
            <div class="relative h-64">
                <canvas id="monthlyExpenseLine"></canvas>
            </div>
        </div>

        <!-- Hidden Table -->
        <div style="display:none">
            <table id="statTable">
                <tbody></tbody>
            </table>
        </div>

        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="hidden">
            <div class="loading-box text-center">
                <div class="spinner mx-auto"></div>
                <div class="loading-text mt-4">
                    <p class="font-bold text-slate-800 text-xs">กำลังประมวลผลข้อมูล</p>
                    <p class="text-[10px] text-slate-400 mt-1">Syncing server analytics data...</p>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const PALETTE_STATUS = {
            pending: '#2563eb',
            endprogress: '#16a34a',
            cancelled: '#dc2626',
            rejected: '#d97706',
            approved: '#059669',
            returned: '#9ca3af',
            unknown: '#d1d5db'
        };
        const DONUT_COLORS = ['#dc2626', '#3b82f6', '#10b981', '#f59e0b', '#6366f1'];

        let barChart, donutChart, lineChart, expenseChart;
        let currentData = {
            monthly_stats: @json($monthlyStats),
            monthly_requisition_counts: @json($monthlyRequisitionCounts ?? []),
            top_items: [],
            monthly_totals: {},
            monthly_expense_totals: @json($monthlyExpenseTotals ?? []),
            summary: {
                pending:   {{ $pendingRequisitions }},
                approved:  {{ $approvedRequisitions }},
                cancelled: {{ $cancelledRequisitions }},
                rejected:  {{ $rejectedRequisitions ?? 0 }},
                total:     {{ $totalRequisitions }}
            }
        };

        function formatMonth(key) {
            if (!key || key === 'unknown') return '-';
            const [y, m] = key.split('-');
            return new Date(y, parseInt(m) - 1, 1).toLocaleDateString('th-TH', { year: 'numeric', month: 'short' });
        }
        function debounce(fn, delay) { let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), delay); }; }
        function toggleLoading(show) { document.getElementById('loadingOverlay').classList.toggle('hidden', !show); }
        function computeTotals(stats) {
            const totals = {};
            Object.values(stats).forEach(items => Object.entries(items).forEach(([n, q]) => { totals[n] = (totals[n] || 0) + q; }));
            return totals;
        }
        function formatCurrency(n) {
            try { return new Intl.NumberFormat('th-TH', { style: 'currency', currency: 'THB', maximumFractionDigits: 0 }).format(n || 0); }
            catch (_) { return (n || 0).toLocaleString('th-TH'); }
        }
        function renderSummary(summary) {
            document.querySelectorAll('[data-summary]').forEach(el => {
                const k = el.getAttribute('data-summary');
                if (summary[k] !== undefined) el.textContent = summary[k];
            });
        }

        // Chart defaults
        Chart.defaults.font.family = "'IBM Plex Sans Thai', sans-serif";
        Chart.defaults.color = '#64748b';

        const gridColor = '#f1f5f9';
        const tooltipDefaults = {
            backgroundColor: '#0f172a',
            padding: 8,
            cornerRadius: 4,
            titleFont: { weight: '700', size: 11 },
            bodyFont: { weight: '500', size: 10 }
        };

        function buildBarDatasets(counts, { percentMode = false } = {}) {
            const months = Object.keys(counts).sort();
            const allStatuses = new Set();
            months.forEach(m => Object.keys(counts[m] || {}).forEach(s => allStatuses.add(s)));
            const statuses = [...allStatuses];
            const monthTotals = months.map(m => Object.values(counts[m] || {}).reduce((s, v) => s + v, 0));
            const datasets = statuses.map((status, idx) => {
                const raw = months.map(m => counts[m]?.[status] || 0);
                const data = percentMode ? raw.map((v, i) => monthTotals[i] ? ((v / monthTotals[i]) * 100) : 0) : raw;
                const color = PALETTE_STATUS[status] || '#9ca3af';
                return { label: status, data, backgroundColor: color, stack: 'status', borderRadius: 4, borderSkipped: false };
            });
            return { labels: months.map(formatMonth), datasets };
        }

        function renderCharts(data) {
            const stats = data.monthly_stats || {};
            const reqCounts = data.monthly_requisition_counts || {};
            const percentMode = document.getElementById('chkBarPercent').checked;
            const showLegend = document.getElementById('chkBarLegend').checked;

            const isMobile = window.innerWidth < 640;
            const barData = buildBarDatasets(reqCounts, { percentMode });
            if (barChart) barChart.destroy();
            barChart = new Chart(document.getElementById('monthlyBarChart'), {
                type: 'bar',
                data: barData,
                options: {
                    responsive: true, maintainAspectRatio: false,
                    barPercentage: isMobile ? 0.8 : 0.6,
                    categoryPercentage: isMobile ? 0.9 : 0.8,
                    plugins: {
                        legend: { 
                            display: showLegend, 
                            position: 'bottom', 
                            labels: { 
                                font: { weight: '600', size: 9 }, 
                                usePointStyle: true, 
                                pointStyleWidth: 8,
                                padding: 10
                            } 
                        },
                        tooltip: { ...tooltipDefaults, callbacks: { label: ctx => { const l = ctx.dataset.label || ''; const v = ctx.parsed.y; return percentMode ? `${l}: ${v.toFixed(1)}%` : `${l}: ${v}`; } } }
                    },
                    scales: {
                        x: { stacked: true, grid: { display: false }, ticks: { font: { weight: '600', size: 9 } } },
                        y: { stacked: true, beginAtZero: true, grid: { color: gridColor }, ticks: { font: { weight: '500', size: 9 }, callback: v => percentMode ? `${v}%` : v } }
                    }
                }
            });

            // Donut
            const topItems = (data.top_items && data.top_items.length)
                ? data.top_items
                : Object.entries(computeTotals(stats)).map(([n, q]) => ({ name: n, quantity: q })).sort((a, b) => b.quantity - a.quantity).slice(0, 5);

            if (donutChart) donutChart.destroy();
            donutChart = new Chart(document.getElementById('topItemsDonut'), {
                type: 'doughnut',
                data: {
                    labels: topItems.map(i => i.name),
                    datasets: [{ data: topItems.map(i => i.quantity), backgroundColor: DONUT_COLORS, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false }, tooltip: tooltipDefaults } }
            });

            document.getElementById('topItemsList').innerHTML = topItems.map((i, idx) => `
                        <li class="top-item">
                            <div style="display:flex;align-items:center;gap:0.5rem">
                                <div class="top-item__dot" style="background:${DONUT_COLORS[idx % DONUT_COLORS.length]}"></div>
                                <span class="top-item__name">${i.name}</span>
                            </div>
                            <span class="top-item__qty">${i.quantity}</span>
                        </li>`).join('');

            // Line
            const monthlyTotals = (data.monthly_totals && Object.keys(data.monthly_totals).length)
                ? data.monthly_totals
                : Object.fromEntries(Object.keys(stats).map(m => [m, Object.values(stats[m]).reduce((s, v) => s + v, 0)]));

            if (lineChart) lineChart.destroy();
            lineChart = new Chart(document.getElementById('monthlyTotalsLine'), {
                type: 'line',
                data: {
                    labels: Object.keys(monthlyTotals).sort().map(formatMonth),
                    datasets: [{
                        label: 'ยอดการเบิกสะสม',
                        data: Object.keys(monthlyTotals).sort().map(k => monthlyTotals[k]),
                        borderColor: '#dc2626',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#dc2626',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: isMobile ? 3 : 4,
                        backgroundColor: 'rgba(220,38,38,0.03)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { weight: '600', size: 9 } } },
                        y: { grid: { color: gridColor }, ticks: { font: { weight: '500', size: 9 } } }
                    }
                }
            });

            // Expense Line
            const expenseTotals = (data.monthly_expense_totals && Object.keys(data.monthly_expense_totals).length) ? data.monthly_expense_totals : {};
            if (expenseChart) expenseChart.destroy();
            expenseChart = new Chart(document.getElementById('monthlyExpenseLine'), {
                type: 'line',
                data: {
                    labels: Object.keys(expenseTotals).sort().map(formatMonth),
                    datasets: [{
                        label: 'งบประมาณการเบิก',
                        data: Object.keys(expenseTotals).sort().map(k => expenseTotals[k]),
                        borderColor: '#059669',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#059669',
                        pointRadius: isMobile ? 0 : 3,
                        backgroundColor: 'rgba(5,150,105,0.03)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { intersect: false },
                    plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults, callbacks: { label: ctx => `฿ ${formatCurrency(ctx.parsed.y)}` } } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { weight: '600', size: 9 } } },
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { font: { weight: '500', size: 9 }, callback: v => `฿${(v / 1000).toFixed(0)}k` } }
                    }
                }
            });
        }

        async function fetchData() {
            const form = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams();
            form.forEach((v, k) => { if (v) params.append(k, v); });
            toggleLoading(true);
            try {
                const res = await fetch(`{{ route('requisitions.dashboard.data') }}?${params.toString()}`);
                const json = await res.json();
                currentData = json;
                renderSummary(json.summary);
                renderCharts(json);
            } catch (e) { console.error(e); }
            finally { toggleLoading(false); }
        }

        document.getElementById('searchInput').addEventListener('input', debounce(fetchData, 500));
        
        function handleReset() {
            document.getElementById('filterForm').reset();
            fetchData();
        }
        
        document.getElementById('btnResetFilters').addEventListener('click', () => {
            handleReset();
        });
        document.getElementById('btnExportPdf').addEventListener('click', () => {
            const form = document.getElementById('filterForm');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.location.href = `{{ route('requisitions.reportslistall.export.pdf') }}?${params}`;
        });

        document.querySelectorAll('#filterForm input, #filterForm select').forEach(el => el.addEventListener('change', debounce(fetchData, 300)));
        document.getElementById('chkBarLegend').addEventListener('change', () => renderCharts(currentData));
        document.getElementById('chkBarPercent').addEventListener('change', () => renderCharts(currentData));

        // Init
        renderCharts(currentData);
        renderSummary(currentData.summary);
    </script>
@endsection
