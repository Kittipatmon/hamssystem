@extends('layouts.serviceitem.appservice')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap');

        :root {
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #f1f5f9;
            --accent: #6366f1;
            --accent-soft: #eef2ff;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --pending: #3b82f6;
            --radius-card: 2rem;
            --radius-lg: 1.5rem;
            --radius-md: 1rem;
            --radius-sm: 0.75rem;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        .db-wrap {
            font-family: 'Outfit', 'IBM Plex Sans Thai', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            padding: 3rem 2rem;
            max-width: 1600px;
            margin: 0 auto;
            position: relative;
        }

        .db-wrap::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
            z-index: 0;
            pointer-events: none;
        }

        /* ── Header ── */
        .db-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: var(--radius-card);
            padding: 2rem 2.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
            animation: fadeInDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .db-header__left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .db-logo {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--accent) 0%, #818cf8 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 8px 16px -4px rgba(99, 102, 241, 0.3);
        }

        .db-logo i {
            color: #fff;
            font-size: 1.25rem;
        }

        .db-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .db-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-top: 4px;
        }

        .db-header__right {
            display: flex;
            gap: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            letter-spacing: 0.02em;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            box-shadow: var(--shadow);
        }

        .btn-ghost:hover {
            background: var(--bg);
             border-color: var(--accent);
             color: var(--accent);
         }
 
         .btn-primary {
             background: var(--text-primary);
             color: #fff;
             box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
         }
 
         .btn-primary:hover {
             background: var(--accent);
             box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.2);
         }

        /* ── Filter Bar ── */
        .db-filters {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 2rem 2.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            animation: fadeInDown 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .filters-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 1rem;
        }

        .filters-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.875rem;
        }

        .field-wrap {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .field-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            padding-left: 2px;
        }

        .field-input {
            width: 100%;
            height: 2.5rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0 0.875rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-primary);
            font-family: inherit;
            outline: none;
            appearance: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            background: #fff;
        }

        .field-input::placeholder {
            color: var(--text-muted);
        }

        /* ── Summary Cards ── */
        .db-stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @media (max-width: 900px) {
            .db-stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 560px) {
            .db-stats {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .stat-card--accent {
            background: var(--accent);
            border-color: var(--accent);
        }

        .stat-card__bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 1rem 1rem 0 0;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .stat-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
        }

        .stat-card--accent .stat-label {
            color: rgba(255, 255, 255, 0.65);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            font-family: 'IBM Plex Mono', monospace;
        }

        .stat-card--accent .stat-value {
            color: #fff;
        }

        /* ── Charts ── */
        .db-charts-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
            animation: fadeInDown 0.9s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @media (max-width: 900px) {
            .db-charts-row {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: var(--shadow-md);
        }

        .chart-card__head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .chart-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .chart-subtitle {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Expense Chart full width ── */
        .db-expense {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-card);
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 3rem;
            animation: fadeInDown 1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .expense-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .realtime-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 2rem;
            padding: 0.4rem 1rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--success);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .realtime-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--success);
            animation: pulse 2s infinite;
        }

        /* ── Loading Overlay ── */
        #loadingOverlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.1);
            backdrop-filter: blur(12px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #loadingOverlay.hidden {
            display: none !important;
        }

        .loading-box {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 2rem;
            padding: 3rem 4rem;
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .spinner {
            width: 52px;
            height: 52px;
            border: 4px solid var(--border);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 1s cubic-bezier(0.5, 0.1, 0.4, 0.9) infinite;
        }

        /* ── Animations ── */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        /* Divider line utility */
        .vdivider {
            width: 1px;
            background: var(--border);
            align-self: stretch;
        }
    </style>

    <div class="db-wrap">

        <!-- ── Header ── -->
        <div class="db-header">
            <div class="db-header__left">
                <div class="db-logo"><i class="fa-solid fa-gauge-high"></i></div>
                <div>
                    <h1 class="db-title">รายงานสถิติ — Real-time Dashboard</h1>
                    <p class="db-subtitle">Analytics Engine &bull; วิเคราะห์พฤติกรรมการเบิกและสรุปงบประมาณ</p>
                </div>
            </div>
            <div class="db-header__right">
                <button id="btnResetFilters" type="button" class="btn btn-ghost">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
                <button id="btnExportCsv" type="button" class="btn btn-primary">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- ── Filters ── -->
        <div class="db-filters">
            <div class="filters-label"><i class="fa-solid fa-sliders" style="color:var(--text-muted);font-size:0.65rem"></i>
                Filters</div>
            <form id="filterForm" class="filters-grid">
                <div class="field-wrap">
                    <span class="field-label">จากวันที่</span>
                    <input type="date" name="date_from" class="field-input">
                </div>
                <div class="field-wrap">
                    <span class="field-label">ถึงวันที่</span>
                    <input type="date" name="date_to" class="field-input">
                </div>
                <div class="field-wrap">
                    <span class="field-label">สายงาน (Section)</span>
                    <select name="section" class="field-input">
                        <option value="">ทั้งหมด</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->section_id }}">{{ $s->section_code }} — {{ $s->section_fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <span class="field-label">ฝ่าย (Division)</span>
                    <select name="division" class="field-input" data-cascade="division">
                        <option value="">ทั้งหมด</option>
                        @foreach($divisions as $d)
                            <option value="{{ $d->division_id }}">{{ $d->division_name }} — {{ $d->division_fullname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <span class="field-label">แผนก (Dept)</span>
                    <select name="department" class="field-input" data-cascade="department">
                        <option value="">ทั้งหมด</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->department_id }}">{{ $d->department_name }} — {{ $d->department_fullname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <span class="field-label">ค้นหา (Search)</span>
                    <input type="text" id="searchInput" placeholder="Search items…" class="field-input">
                </div>
            </form>
        </div>

        <!-- ── Summary Stats ── -->
        <div id="summaryCards" class="db-stats">
            <!-- Pending -->
            <div class="stat-card">
                <div class="stat-card__bar" style="background:var(--pending)"></div>
                <div class="stat-icon" style="background:#eff6ff;color:var(--pending)">
                    <i class="fa-solid fa-hourglass-half" style="font-size:0.75rem"></i>
                </div>
                <div>
                    <div class="stat-label">รอดำเนินการ</div>
                    <div class="stat-value" data-summary="pending">{{ $pendingRequisitions }}</div>
                </div>
            </div>
            <!-- Finished -->
            <div class="stat-card">
                <div class="stat-card__bar" style="background:var(--success)"></div>
                <div class="stat-icon" style="background:#f0fdf4;color:var(--success)">
                    <i class="fa-solid fa-circle-check" style="font-size:0.75rem"></i>
                </div>
                <div>
                    <div class="stat-label">เสร็จสิ้น</div>
                    <div class="stat-value" data-summary="approved">{{ $approvedRequisitions }}</div>
                </div>
            </div>
            <!-- Cancelled -->
            <div class="stat-card">
                <div class="stat-card__bar" style="background:var(--danger)"></div>
                <div class="stat-icon" style="background:#fef2f2;color:var(--danger)">
                    <i class="fa-solid fa-ban" style="font-size:0.75rem"></i>
                </div>
                <div>
                    <div class="stat-label">ยกเลิก</div>
                    <div class="stat-value" data-summary="cancelled">{{ $cancelledRequisitions }}</div>
                </div>
            </div>
            <!-- Rejected -->
            <div class="stat-card">
                <div class="stat-card__bar" style="background:var(--warning)"></div>
                <div class="stat-icon" style="background:#fffbeb;color:var(--warning)">
                    <i class="fa-solid fa-circle-xmark" style="font-size:0.75rem"></i>
                </div>
                <div>
                    <div class="stat-label">ไม่อนุมัติ</div>
                    <div class="stat-value" data-summary="rejected">{{ $rejectedRequisitions ?? 0 }}</div>
                </div>
            </div>
            <!-- Total -->
            <div class="stat-card stat-card--accent">
                <div class="stat-icon" style="background:rgba(255,255,255,0.15);color:#fff">
                    <i class="fa-solid fa-layer-group" style="font-size:0.75rem"></i>
                </div>
                <div>
                    <div class="stat-label">คำขอทั้งหมด</div>
                    <div class="stat-value" data-summary="total">{{ $totalRequisitions }}</div>
                </div>
            </div>
        </div>

        <!-- ── Charts Row ── -->
        <div class="db-charts-row">
            <!-- Monthly Bar Chart -->
            <div class="chart-card">
                <div class="chart-card__head">
                    <div>
                        <p class="chart-title">ความคืบหน้ารายเดือน</p>
                        <p class="chart-subtitle">Status Breakdown per Month</p>
                    </div>
                    <div class="chart-controls">
                        <label class="toggle-label">
                            <input type="checkbox" id="chkBarLegend"> Legend
                        </label>
                        <label class="toggle-label">
                            <input type="checkbox" id="chkBarPercent"> Show %
                        </label>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyBarChart"></canvas>
                </div>
            </div>

            <!-- Top Items Donut -->
            <div class="chart-card">
                <div class="chart-card__head">
                    <div>
                        <p class="chart-title">พัสดุยอดนิยม (TOP 5)</p>
                        <p class="chart-subtitle">Top 5 Requisitioned Items</p>
                    </div>
                </div>
                <div class="chart-body" style="min-height:220px">
                    <canvas id="topItemsDonut"></canvas>
                </div>
                <ul id="topItemsList" class="top-items-list"></ul>
            </div>

            <!-- Monthly Totals Line -->
            <div class="chart-card">
                <div class="chart-card__head">
                    <div>
                        <p class="chart-title">แนวโน้มการเบิกสะสม</p>
                        <p class="chart-subtitle">Monthly Item Quantity Trend</p>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyTotalsLine"></canvas>
                </div>
            </div>
        </div>

        <!-- ── Full-Width Expense Chart ── -->
        <div class="db-expense">
            <div class="expense-head">
                <div>
                    <p class="chart-title">สรุปงบประมาณรายจ่ายรายเดือน</p>
                    <p class="chart-subtitle">Monthly Total Expenditure (Value in THB)</p>
                </div>
                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                    <label class="toggle-label">
                        <input type="checkbox" id="chkFilterRows" checked> Filter Results
                    </label>
                    <div class="vdivider"></div>
                    <div class="realtime-badge">
                        <div class="realtime-dot"></div>
                        Real-time Active
                    </div>
                </div>
            </div>
            <div style="min-height:140px">
                <canvas id="monthlyExpenseLine" height="55"></canvas>
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
            <div class="loading-box">
                <div class="spinner"></div>
                <div class="loading-text">
                    <p class="l1">กำลังประมวลผลข้อมูล</p>
                    <p class="l2">Syncing with server analytics…</p>
                </div>
            </div>
        </div>

    </div><!-- /db-wrap -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ── palette: single accent + status colours ──
        const PALETTE_STATUS = {
            pending: '#2563eb',
            endprogress: '#16a34a',
            cancelled: '#dc2626',
            rejected: '#d97706',
            approved: '#059669',
            returned: '#9ca3af',
            unknown: '#d1d5db'
        };
        const DONUT_COLORS = ['#4f46e5', '#818cf8', '#a5b4fc', '#c7d2fe', '#e0e7ff'];

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

        const divisionMap = @json($divisionMap ?? []);
        const departmentMap = @json($departmentMap ?? []);
        const allDivisions = Object.values(divisionMap).flat();
        const allDepartments = Object.values(departmentMap).flat();

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

        // Chart defaults – clean & minimal
        Chart.defaults.font.family = "'IBM Plex Sans Thai', sans-serif";
        Chart.defaults.color = '#6b6860';

        const gridColor = '#f0ede8';
        const tooltipDefaults = {
            backgroundColor: '#1a1917',
            padding: 10,
            cornerRadius: 8,
            titleFont: { weight: '700', size: 12 },
            bodyFont: { weight: '500', size: 11 }
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

            // 1. Bar
            const barData = buildBarDatasets(reqCounts, { percentMode });
            if (barChart) barChart.destroy();
            barChart = new Chart(document.getElementById('monthlyBarChart'), {
                type: 'bar',
                data: barData,
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: showLegend, position: 'bottom', labels: { font: { weight: '600', size: 11 }, usePointStyle: true, pointStyleWidth: 8 } },
                        tooltip: { ...tooltipDefaults, callbacks: { label: ctx => { const l = ctx.dataset.label || ''; const v = ctx.parsed.y; return percentMode ? `${l}: ${v.toFixed(1)}%` : `${l}: ${v}`; } } }
                    },
                    scales: {
                        x: { stacked: true, grid: { display: false }, ticks: { font: { weight: '600', size: 11 } } },
                        y: { stacked: true, beginAtZero: true, grid: { color: gridColor }, ticks: { font: { weight: '500', size: 10 }, callback: v => percentMode ? `${v}%` : v } }
                    }
                }
            });

            // 2. Donut
            const topItems = (data.top_items && data.top_items.length)
                ? data.top_items
                : Object.entries(computeTotals(stats)).map(([n, q]) => ({ name: n, quantity: q })).sort((a, b) => b.quantity - a.quantity).slice(0, 5);

            if (donutChart) donutChart.destroy();
            donutChart = new Chart(document.getElementById('topItemsDonut'), {
                type: 'doughnut',
                data: {
                    labels: topItems.map(i => i.name),
                    datasets: [{ data: topItems.map(i => i.quantity), backgroundColor: DONUT_COLORS, borderWidth: 0, hoverOffset: 8 }]
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

            // 3. Line (Qty Trend)
            const monthlyTotals = (data.monthly_totals && Object.keys(data.monthly_totals).length)
                ? data.monthly_totals
                : Object.fromEntries(Object.keys(stats).map(m => [m, Object.values(stats[m]).reduce((s, v) => s + v, 0)]));

            if (lineChart) lineChart.destroy();
            lineChart = new Chart(document.getElementById('monthlyTotalsLine'), {
                type: 'line',
                data: {
                    labels: Object.keys(monthlyTotals).sort().map(formatMonth),
                    datasets: [{
                        label: 'Total Qty',
                        data: Object.keys(monthlyTotals).sort().map(k => monthlyTotals[k]),
                        borderColor: '#4f46e5',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        backgroundColor: 'rgba(79,70,229,0.07)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { weight: '600', size: 11 } } },
                        y: { grid: { color: gridColor }, ticks: { font: { weight: '500', size: 10 } } }
                    }
                }
            });

            // 4. Expense Line
            const expenseTotals = (data.monthly_expense_totals && Object.keys(data.monthly_expense_totals).length) ? data.monthly_expense_totals : {};
            if (expenseChart) expenseChart.destroy();
            expenseChart = new Chart(document.getElementById('monthlyExpenseLine'), {
                type: 'line',
                data: {
                    labels: Object.keys(expenseTotals).sort().map(formatMonth),
                    datasets: [{
                        label: 'Expenditure',
                        data: Object.keys(expenseTotals).sort().map(k => expenseTotals[k]),
                        borderColor: '#16a34a',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 0,
                        backgroundColor: 'rgba(22,163,74,0.06)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { intersect: false },
                    plugins: { legend: { display: false }, tooltip: { ...tooltipDefaults, callbacks: { label: ctx => `฿ ${formatCurrency(ctx.parsed.y)}` } } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { weight: '600', size: 11 } } },
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { font: { weight: '500', size: 10 }, callback: v => `฿${(v / 1000).toFixed(0)}k` } }
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
        document.getElementById('btnResetFilters').addEventListener('click', () => {
            document.getElementById('filterForm').reset();
            handleSectionChange();
            fetchData();
        });
        document.getElementById('btnExportCsv').addEventListener('click', () => {
            const q = v => `"${String(v ?? '').replace(/"/g, '""')}"`;
            const lines = [];
            const df = document.querySelector('input[name="date_from"]').value || '';
            const dt = document.querySelector('input[name="date_to"]').value || '';
            const nowStr = new Date().toLocaleString('th-TH');
            lines.push([q('HAMS ANALYTICS - REPORT')].join(','));
            lines.push([q('Exported At'), q(nowStr)].join(','));
            lines.push([q('Date Range'), q(df || 'Genesis'), q('TO'), q(dt || 'Present')].join(','));
            lines.push('');
            const sum = currentData.summary || {};
            lines.push([q('SUMMARY STATS')].join(','));
            lines.push([q('Pending'), q('Approved'), q('Cancelled'), q('Rejected'), q('Total')].join(','));
            lines.push([sum.pending || 0, sum.approved || 0, sum.cancelled || 0, sum.rejected || 0, sum.total || 0].map(q).join(','));
            const csv = '\uFEFF' + lines.join('\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `HAMS_Dashboard_Export_${Date.now()}.csv`; a.click();
        });

        document.querySelectorAll('#filterForm input, #filterForm select').forEach(el => el.addEventListener('change', debounce(fetchData, 300)));
        document.getElementById('chkBarLegend').addEventListener('change', () => renderCharts(currentData));
        document.getElementById('chkBarPercent').addEventListener('change', () => renderCharts(currentData));

        const sectionSelect = document.querySelector('select[name="section"]');
        const divisionSelect = document.querySelector('select[name="division"]');
        const departmentSelect = document.querySelector('select[name="department"]');

        function rebuildOptions(selectEl, items) {
            const currentVal = selectEl.value;
            const opts = [`<option value="">ทั้งหมด</option>`].concat(items.map(i => `<option value="${i.id}">${i.name} (${i.fullname})</option>`));
            selectEl.innerHTML = opts.join('');
            if (items.some(i => String(i.id) === String(currentVal))) { selectEl.value = currentVal; } else { selectEl.value = ''; }
        }

        function handleSectionChange() {
            const sid = sectionSelect.value;
            const divisions = sid ? (divisionMap[sid] || []) : allDivisions;
            rebuildOptions(divisionSelect, divisions);
            rebuildOptions(departmentSelect, []);
        }
        function handleDivisionChange() {
            const did = divisionSelect.value;
            const departments = did ? (departmentMap[did] || []) : (sectionSelect.value ? [] : allDepartments);
            rebuildOptions(departmentSelect, departments);
        }

        sectionSelect.addEventListener('change', handleSectionChange);
        divisionSelect.addEventListener('change', handleDivisionChange);

        // Init
        renderCharts(currentData);
        renderSummary(currentData.summary);
    </script>
@endsection