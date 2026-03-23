@extends('layouts.serviceitem.appservice')

@section('content')
<div class="max-w-[1600px] mx-auto px-4 py-8 space-y-8 uppercase tracking-tight">

    <!-- Header & Quick Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 animate-zoom-in relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-5 pointer-events-none">
            <i class="fa-solid fa-chart-pie text-[15rem] -mr-20 -mt-20"></i>
        </div>
        <div class="flex items-center gap-5 relative">
            <div class="w-16 h-16 bg-slate-800 rounded-3xl flex items-center justify-center shadow-lg shadow-slate-200">
                <i class="fa-solid fa-gauge-high text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tighter italic leading-none">รายงานสถิติ (Real-time Dashboard)</h1>
                <p class="text-[13px] text-slate-400 font-bold mt-1.5 flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-slate-100 rounded text-slate-600 font-mono italic">ANALYTICS ENGINE</span>
                    <span>•</span>
                    <span class="italic">วิเคราะห์พฤติกรรมการเบิกและสรุปงบประมาณ</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3 relative">
            <button id="btnResetFilters" type="button" class="px-6 py-3 bg-white border-2 border-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-50 hover:border-slate-200 transition-all active:scale-95 text-xs uppercase italic">
                <i class="fa-solid fa-rotate-left mr-2"></i> Reset
            </button>
            <button id="btnExportCsv" type="button" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95 text-xs uppercase italic flex items-center gap-3">
                <i class="fa-solid fa-file-csv text-sm"></i> EXPORT CSV
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-slate-800 p-8 rounded-[2.5rem] shadow-xl border border-slate-700 animate-zoom-in">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">จากวันที่ (Date From)</label>
                <input type="date" name="date_from" class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-red-500 transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">ถึงวันที่ (Date To)</label>
                <input type="date" name="date_to" class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-red-500 transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">สายงาน (Section)</label>
                <select name="section" class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-slate-500 appearance-none">
                    <option value="">ทั้งหมด (All)</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->section_id }}">{{ $s->section_code }} ({{ $s->section_fullname }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">ฝ่าย (Division)</label>
                <select name="division" class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-slate-500 appearance-none" data-cascade="division">
                    <option value="">ทั้งหมด (All)</option>
                    @foreach($divisions as $d)
                        <option value="{{ $d->division_id }}">{{ $d->division_name }} ({{ $d->division_fullname }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">แผนก (Dept)</label>
                <select name="department" class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-slate-500 appearance-none" data-cascade="department">
                    <option value="">ทั้งหมด (All)</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->department_id }}">{{ $d->department_name }} ({{ $d->department_fullname }})</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest pl-2">ค้นหา (Search)</label>
                <input type="text" id="searchInput" placeholder="Search items..." class="w-full h-12 bg-slate-900 border-none rounded-xl px-4 text-white font-black text-[13px] focus:ring-2 focus:ring-slate-500 transition-all placeholder:text-slate-700 italic">
            </div>
        </form>
    </div>

    <!-- Summary Stats Cards -->
    <div id="summaryCards" class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-blue-50 shadow-sm flex flex-col gap-4">
            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center font-black italic">P</div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1 text-center">รอดำเนินการ</p>
                <p class="text-[28px] font-black text-slate-800 leading-none text-center italic font-mono" data-summary="pending">{{ $pendingRequisitions }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-emerald-50 shadow-sm flex flex-col gap-4">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center font-black italic">F</div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1 text-center">เสร็จสิ้น</p>
                <p class="text-[28px] font-black text-slate-800 leading-none text-center italic font-mono" data-summary="approved">{{ $approvedRequisitions }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-red-50 shadow-sm flex flex-col gap-4">
            <div class="w-10 h-10 bg-red-50 text-red-500 rounded-full flex items-center justify-center font-black italic">C</div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1 text-center">ยกเลิก</p>
                <p class="text-[28px] font-black text-slate-800 leading-none text-center italic font-mono" data-summary="cancelled">{{ $cancelledRequisitions }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-orange-50 shadow-sm flex flex-col gap-4">
            <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center font-black italic">R</div>
            <div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1 text-center">ไม่อนุมัติ</p>
                <p class="text-[28px] font-black text-slate-800 leading-none text-center italic font-mono" data-summary="rejected">{{ $rejectedRequisitions ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-slate-800 p-6 rounded-[2rem] shadow-xl shadow-slate-200 flex flex-col gap-4 text-white">
            <div class="w-10 h-10 bg-slate-700 text-slate-300 rounded-full flex items-center justify-center font-black italic">T</div>
            <div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1 text-center">คำขอทั้งหมด</p>
                <p class="text-[28px] font-black text-white leading-none text-center italic font-mono underline decoration-red-600 decoration-4 underline-offset-8" data-summary="total">{{ $totalRequisitions }}</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Monthly Bar Chart -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col">
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h2 class="text-sm font-black text-slate-800 italic uppercase">ความคืบหน้ารายเดือน</h2>
                    <p class="text-[10px] text-slate-300 font-bold uppercase mt-1 leading-none tracking-tight">Status Breakdown per Month</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" id="chkBarLegend" class="checkbox checkbox-xs rounded-md border-slate-200 group-hover:border-red-400">
                        <span class="text-[9px] font-black text-slate-400 uppercase italic">Show Legend</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" id="chkBarPercent" class="checkbox checkbox-xs rounded-md border-slate-200 group-hover:border-red-400">
                        <span class="text-[9px] font-black text-slate-400 uppercase italic">Show %</span>
                    </label>
                </div>
            </div>
            <div class="flex-1 min-h-[300px] flex items-center justify-center">
                <canvas id="monthlyBarChart"></canvas>
            </div>
        </div>

        <!-- Top Items Donut -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col">
            <div class="mb-8 items-start">
                 <h2 class="text-sm font-black text-slate-800 italic uppercase">พัสดุยอดนิยม (TOP 5)</h2>
                 <p class="text-[10px] text-slate-300 font-bold uppercase mt-1 leading-none tracking-tight">Top 5 Requisitioned Items</p>
            </div>
            <div class="flex-1 min-h-[250px] flex items-center justify-center">
                <canvas id="topItemsDonut"></canvas>
            </div>
            <ul id="topItemsList" class="mt-8 space-y-2 border-t border-slate-50 pt-6"></ul>
        </div>

        <!-- Monthly Totals Line -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col">
             <div class="mb-8">
                 <h2 class="text-sm font-black text-slate-800 italic uppercase">แนวโน้มการเบิกสะสม</h2>
                 <p class="text-[10px] text-slate-300 font-bold uppercase mt-1 leading-none tracking-tight">Monthly Item Quantity Trend</p>
            </div>
            <div class="flex-1 min-h-[300px] flex items-center justify-center">
                <canvas id="monthlyTotalsLine"></canvas>
            </div>
        </div>
    </div>

    <!-- Full Width Expense Chart -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-sm font-black text-slate-800 italic uppercase">สรุปงบประมาณรายจ่ายรายเดือน</h2>
                <p class="text-[10px] text-slate-300 font-bold uppercase mt-1 leading-none tracking-tight">Monthly Total Expenditure (Value in THB)</p>
            </div>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <span class="text-[9px] font-black text-slate-400 uppercase italic">Filter Results</span>
                    <input type="checkbox" id="chkFilterRows" class="checkbox checkbox-xs rounded-md border-slate-200" checked />
                </label>
                <div class="w-px h-6 bg-slate-100"></div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-emerald-600 uppercase italic">Real-time Data Active</span>
                </div>
            </div>
        </div>
        <div class="min-h-[150px]">
            <canvas id="monthlyExpenseLine" height="60"></canvas>
        </div>
    </div>

    <!-- Hidden Data Table (Legacy but maintained for sync) -->
    <div class="hidden">
        <table id="statTable"><tbody></tbody></table>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[200] flex items-center justify-center">
        <div class="bg-white p-10 rounded-[3rem] shadow-2xl flex flex-col items-center gap-6 animate-zoom-in">
            <div class="relative">
                <div class="w-20 h-20 border-4 border-slate-100 rounded-full"></div>
                <div class="w-20 h-20 border-t-4 border-red-600 rounded-full absolute top-0 left-0 animate-spin"></div>
            </div>
            <div class="text-center">
                <p class="text-[14px] font-black text-slate-800 italic uppercase">กำลังประมวลผลข้อมูล</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase italic mt-1">Syncing with server analytics...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const daisyColors=['#2563eb','#f59e42','#22c55e','#a21caf','#e11d48','#fbbf24','#0ea5e9','#f472b6','#14b8a6','#64748b','#9333ea','#ea580c'];
    let barChart,donutChart,lineChart,expenseChart;
    
    let currentData={
        monthly_stats:@json($monthlyStats),
        monthly_requisition_counts:@json($monthlyRequisitionCounts ?? []),
        top_items:[],
        monthly_totals:{},
        monthly_expense_totals:@json($monthlyExpenseTotals ?? []),
        summary:{pending:{{ $pendingRequisitions }},approved:{{ $approvedRequisitions }},cancelled:{{ $cancelledRequisitions }},rejected:{{ $rejectedRequisitions ?? 0 }},total:{{ $totalRequisitions }}}
    };

    const divisionMap=@json($divisionMap ?? []);
    const departmentMap=@json($departmentMap ?? []);
    const allDivisions = Object.values(divisionMap).flat();
    const allDepartments = Object.values(departmentMap).flat();
    
    function formatMonth(key){if(!key||key==='unknown')return '-';const [y,m]=key.split('-');return new Date(y,parseInt(m)-1,1).toLocaleDateString('th-TH',{year:'numeric',month:'short'});}
    function debounce(fn,delay){let t;return(...a)=>{clearTimeout(t);t=setTimeout(()=>fn(...a),delay);};}
    function toggleLoading(show){document.getElementById('loadingOverlay').classList.toggle('hidden',!show);}
    function computeTotals(stats){const totals={};Object.values(stats).forEach(items=>{Object.entries(items).forEach(([name,qty])=>{totals[name]=(totals[name]||0)+qty;});});return totals;}
    function formatCurrency(n){try{return new Intl.NumberFormat('th-TH',{style:'currency',currency:'THB',maximumFractionDigits:0}).format(n||0);}catch(_){return (n||0).toLocaleString('th-TH');}}
    
    function renderSummary(summary){document.querySelectorAll('[data-summary]').forEach(el=>{const k=el.getAttribute('data-summary');if(summary[k]!==undefined)el.textContent=summary[k];});}
    
    const statusMeta={
        pending:{label:'รอเบิก',color:'#3b82f6'},
        endprogress:{label:'เสร็จสิ้น',color:'#10b981'},
        cancelled:{label:'ยกเลิก',color:'#ef4444'},
        rejected:{label:'ไม่อนุมัติ',color:'#f97316'},
        approved:{label:'อนุมัติ',color:'#059669'},
        returned:{label:'คืนของ',color:'#64748b'},
        unknown:{label:'?',color:'#94a3b8'}
    };

    function buildRequisitionBarDatasets(counts,{percentMode=false}={}){
        const months=Object.keys(counts).sort();
        const allStatuses=new Set();
        months.forEach(m=>{Object.keys(counts[m]||{}).forEach(s=>allStatuses.add(s));});
        const statuses=[...allStatuses];
        const monthTotals=months.map(m=>Object.values(counts[m]||{}).reduce((s,v)=>s+v,0));
        const datasets=statuses.map((status,idx)=>{
            const raw=months.map(m=>counts[m]?.[status]||0);
            const data=percentMode?raw.map((v,i)=>monthTotals[i]?((v/monthTotals[i])*100):0):raw;
            const meta=statusMeta[status]||{label:status,color:daisyColors[idx%daisyColors.length]};
            return {
                label:meta.label,
                data,
                backgroundColor:meta.color,
                stack:'status',
                borderRadius:6
            };
        });
        return {labels:months.map(formatMonth),datasets};
    }
    
    function renderCharts(data){
        const stats=data.monthly_stats||{}; 
        const reqCounts=data.monthly_requisition_counts||{};
        
        // 1. Bar Chart
        const percentMode=document.getElementById('chkBarPercent').checked;
        const showLegend=document.getElementById('chkBarLegend').checked;
        const barData=buildRequisitionBarDatasets(reqCounts,{percentMode});
        if(barChart)barChart.destroy();
        barChart=new Chart(document.getElementById('monthlyBarChart'),{
            type:'bar',
            data:barData,
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{
                    legend:{display:showLegend,position:'bottom',labels:{font:{weight:'800',size:10,family:'inherit'}}},
                    tooltip:{backgroundColor:'#1e293b',padding:12,titleFont:{weight:'800',size:13},bodyFont:{weight:'600'},callbacks:{label:(ctx)=>{const label=ctx.dataset.label||'';const val=ctx.parsed.y;return percentMode?`${label}: ${val.toFixed(1)}%`:`${label}: ${val} REQS`;}}}
                },
                scales:{
                    x:{stacked:true,grid:{display:false},ticks:{font:{weight:'800',size:11}}},
                    y:{stacked:true,beginAtZero:true,grid:{color:'#f8fafc'},ticks:{font:{weight:'600',size:10},callback:(v)=>percentMode?`${v}%`:v}}
                }
            }
        });
        
        // 2. Donut Chart
        const topItems=data.top_items&&data.top_items.length?data.top_items:Object.entries(computeTotals(stats)).map(([n,q])=>({name:n,quantity:q})).sort((a,b)=>b.quantity-a.quantity).slice(0,5);
        if(donutChart)donutChart.destroy();
        donutChart=new Chart(document.getElementById('topItemsDonut'),{
            type:'doughnut',
            data:{
                labels:topItems.map(i=>i.name),
                datasets:[{
                    data:topItems.map(i=>i.quantity),
                    backgroundColor:topItems.map((_,i)=>daisyColors[i%daisyColors.length]),
                    borderWidth:0,
                    hoverOffset:10
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                cutout:'70%',
                plugins:{
                    legend:{display:false}
                }
            }
        });
        document.getElementById('topItemsList').innerHTML=topItems.map((i,idx)=>`
            <li class='flex justify-between items-center bg-slate-50/50 p-2 px-4 rounded-xl border border-slate-50'>
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 rounded-full" style="background:${daisyColors[idx%daisyColors.length]}"></div>
                    <span class="text-[11px] font-black text-slate-700 italic uppercase truncate max-w-[150px]">${i.name}</span>
                </div>
                <span class='font-black font-mono text-slate-800 italic'>${i.quantity}</span>
            </li>`).join('');
        
        // 3. Line Chart (Totals)
        const monthlyTotals=data.monthly_totals&&Object.keys(data.monthly_totals).length?data.monthly_totals:Object.fromEntries(Object.keys(stats).map(m=>[m,Object.values(stats[m]).reduce((s,v)=>s+v,0)]));
        if(lineChart)lineChart.destroy();
        lineChart=new Chart(document.getElementById('monthlyTotalsLine'),{
            type:'line',
            data:{
                labels:Object.keys(monthlyTotals).sort().map(formatMonth),
                datasets:[{
                    label:'TOTAL QTY',
                    data:Object.keys(monthlyTotals).sort().map(k=>monthlyTotals[k]),
                    borderColor:'#1e293b',
                    borderWidth:4,
                    pointBackgroundColor:'#1e293b',
                    pointBorderColor:'white',
                    pointBorderWidth:2,
                    pointRadius:6,
                    backgroundColor:'rgba(30,41,59,0.05)',
                    tension:.4,
                    fill:true
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{legend:{display:false}},
                scales:{
                    x:{grid:{display:false},ticks:{font:{weight:'800',size:11}}},
                    y:{grid:{color:'#f8fafc'},ticks:{font:{weight:'600',size:10}}}
                }
            }
        });

        // 4. Expense Chart
        const expenseTotals=data.monthly_expense_totals&&Object.keys(data.monthly_expense_totals).length?data.monthly_expense_totals:{};
        if(expenseChart)expenseChart.destroy();
        expenseChart=new Chart(document.getElementById('monthlyExpenseLine'),{
            type:'line',
            data:{
                labels:Object.keys(expenseTotals).sort().map(formatMonth),
                datasets:[{
                    label:'EXPENDITURE',
                    data:Object.keys(expenseTotals).sort().map(k=>expenseTotals[k]),
                    borderColor:'#10b981',
                    borderWidth:5,
                    pointBackgroundColor:'#10b981',
                    pointRadius:0,
                    backgroundColor:'rgba(16,185,129,0.05)',
                    tension:.3,
                    fill:true
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio:false,
                interaction:{intersect:false},
                plugins:{
                    legend:{display:false},
                    tooltip:{backgroundColor:'#1e293b',padding:12,callbacks:{label:(ctx)=>`฿ ${formatCurrency(ctx.parsed.y)}`}}
                },
                scales:{
                    x:{grid:{display:false},ticks:{font:{weight:'800',size:11}}},
                    y:{beginAtZero:true,grid:{color:'#f8fafc'},ticks:{font:{weight:'600',size:10},callback:(v)=>`฿${(v/1000)}k` }}
                }
            }
        });
    }
    
    async function fetchData(){
        const form=new FormData(document.getElementById('filterForm'));
        const params=new URLSearchParams();
        form.forEach((v,k)=>{if(v)params.append(k,v);});
        
        toggleLoading(true);
        try{
            const res=await fetch(`{{ route('requisitions.dashboard.data') }}?${params.toString()}`);
            const json=await res.json();
            currentData=json;
            renderSummary(json.summary);
            renderCharts(json);
        }catch(e){
            console.error(e);
        }finally{
            toggleLoading(false);
        }
    }
    
    document.getElementById('searchInput').addEventListener('input',debounce(fetchData,500));
    document.getElementById('btnResetFilters').addEventListener('click',()=>{
        document.getElementById('filterForm').reset();
        handleSectionChange();
        fetchData();
    });
    
    document.getElementById('btnExportCsv').addEventListener('click',()=>{
        const q=(v)=>`"${String(v??'').replace(/"/g,'""')}"`;
        const lines=[];
        const df=document.querySelector('input[name="date_from"]').value||'';
        const dt=document.querySelector('input[name="date_to"]').value||'';
        const nowStr=new Date().toLocaleString('th-TH');

        lines.push([q('HAMS ANALYTICS - REPORT')].join(','));
        lines.push([q(`Exported At`),q(nowStr)].join(','));
        lines.push([q('Date Range'),q(df||'Genesis'),q('TO'),q(dt||'Present')].join(','));
        lines.push('');

        const sum=currentData.summary||{};
        lines.push([q('SUMMARY STATS')].join(','));
        lines.push([q('Pending'),q('Approved'),q('Cancelled'),q('Rejected'),q('Total')].join(','));
        lines.push([sum.pending||0,sum.approved||0,sum.cancelled||0,sum.rejected||0,sum.total||0].map(q).join(','));
        lines.push('');

        const csv='\uFEFF'+lines.join('\n');
        const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});
        const a=document.createElement('a'); a.href=URL.createObjectURL(blob); a.download=`HAMS_Dashboard_Export_${Date.now()}.csv`; a.click();
    });

    document.querySelectorAll('#filterForm input,#filterForm select').forEach(el=>{
        el.addEventListener('change',debounce(fetchData,300));
    });
    document.getElementById('chkBarLegend').addEventListener('change',()=>renderCharts(currentData));
    document.getElementById('chkBarPercent').addEventListener('change',()=>renderCharts(currentData));

    const sectionSelect=document.querySelector('select[name="section"]');
    const divisionSelect=document.querySelector('select[name="division"]');
    const departmentSelect=document.querySelector('select[name="department"]');

    function rebuildOptions(selectEl, items){
        const currentVal=selectEl.value;
        const opts=[`<option value="">ทั้งหมด (All)</option>`].concat(items.map(i=>`<option value="${i.id}">${i.name} (${i.fullname})</option>`));
        selectEl.innerHTML=opts.join('');
        if(items.some(i=>String(i.id)===String(currentVal))){selectEl.value=currentVal;} else {selectEl.value='';}
    }

    function handleSectionChange(){
        const sid=sectionSelect.value;
        const divisions = sid? (divisionMap[sid]||[]) : allDivisions;
        rebuildOptions(divisionSelect, divisions);
        rebuildOptions(departmentSelect, []);
    }

    function handleDivisionChange(){
        const did=divisionSelect.value;
        const departments = did? (departmentMap[did]||[]) : (sectionSelect.value? [] : allDepartments);
        rebuildOptions(departmentSelect, departments);
    }

    sectionSelect.addEventListener('change',handleSectionChange);
    divisionSelect.addEventListener('change',handleDivisionChange);

    // Initial Render
    renderCharts(currentData);
    renderSummary(currentData.summary);

</script>

<style>
    @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-zoom-in { animation: zoom-in 0.4s ease-out forwards; }
</style>
@endsection