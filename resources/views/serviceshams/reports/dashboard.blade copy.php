@extends('layouts.serviceitem.appservice')
@section('content')
<div class="max-w-7xl mx-auto">
<div class="card bg-base-100 shadow-xl rounded-lg p-6 space-y-4 relative">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-lg font-bold">รายงานสถิติการเบิกอุปกรณ์ (Realtime)</h1>
        <div class="flex gap-2">
            <button id="btnResetFilters" type="button" class="btn btn-outline btn-sm">รีเซ็ตตัวกรอง</button>
            <button id="btnExportPdf" type="button" class="btn btn-error btn-sm text-white">Export PDF</button>
            <button id="btnExportCsv" type="button" class="btn btn-success btn-sm text-white">Export CSV</button>
        </div>
    </div>

    <form id="filterForm" class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <label class="font-medium text-xs">จากวันที่</label>
            <input type="date" name="date_from" class="input input-bordered input-sm w-full mt-1" />
        </div>
        <div>
            <label class="font-medium text-xs">ถึงวันที่</label>
            <input type="date" name="date_to" class="input input-bordered input-sm w-full mt-1" />
        </div>
        <div>
            <label class="font-medium text-xs">สายงาน</label>
            <select name="section" class="select select-bordered select-sm w-full mt-1">
                <option value="">ทั้งหมด</option>
                @foreach($sections as $s)
                    <option value="{{ $s->section_id }}">{{ $s->section_code }} ({{ $s->section_fullname }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-medium text-xs">ฝ่าย</label>
            <select name="division" class="select select-bordered select-sm w-full mt-1" data-cascade="division">
                <option value="">ทั้งหมด</option>
                @foreach($divisions as $d)
                    <option value="{{ $d->division_id }}">{{ $d->division_name }} ({{ $d->division_fullname }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-medium text-xs">แผนก</label>
            <select name="department" class="select select-bordered select-sm w-full mt-1" data-cascade="department">
                <option value="">ทั้งหมด</option>
                @foreach($departments as $d)
                    <option value="{{ $d->dept_id }}">{{ $d->department_name }} ({{ $d->department_fullname }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="font-medium text-xs">ค้นหาอุปกรณ์</label>
            <input type="text" id="searchInput" placeholder="พิมพ์เพื่อค้นหา..." class="input input-bordered input-sm w-full mt-1" />
        </div>
    </form>

    <div id="summaryCards" class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-2">
        <div class="stats shadow bg-blue-100"><div class="stat"><div class="stat-figure text-blue-500"><i class="fa-solid fa-clock fa-lg"></i></div><div class="stat-title">รอดำเนินการ</div><div class="stat-value" data-summary="pending">{{ $pendingRequisitions }}</div></div></div>
        <div class="stats shadow bg-green-100"><div class="stat"><div class="stat-figure text-green-500"><i class="fa-solid fa-check fa-lg"></i></div><div class="stat-title">เสร็จสิ้น</div><div class="stat-value" data-summary="approved">{{ $approvedRequisitions }}</div></div></div>
        <div class="stats shadow bg-red-100"><div class="stat"><div class="stat-figure text-red-500"><i class="fa-solid fa-xmark fa-lg"></i></div><div class="stat-title">ยกเลิก</div><div class="stat-value" data-summary="cancelled">{{ $cancelledRequisitions }}</div></div></div>
        <div class="stats shadow bg-orange-100"><div class="stat"><div class="stat-figure text-orange-500"><i class="fa-solid fa-ban fa-lg"></i></div><div class="stat-title">ไม่อนุมัติ</div><div class="stat-value" data-summary="rejected">{{ $rejectedRequisitions ?? 0 }}</div></div></div>
        <div class="stats shadow bg-purple-100"><div class="stat"><div class="stat-figure text-purple-500"><i class="fa-solid fa-list fa-lg"></i></div><div class="stat-title">ทั้งหมด</div><div class="stat-value" data-summary="total">{{ $totalRequisitions }}</div></div></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-base-100 p-4 rounded-box shadow">
            <div class="flex items-start justify-between mb-2 gap-2">
                <h2 class="text-sm font-semibold">กราฟสถิติจำนวนรายการเบิกในแต่ละเดือน</h2>
                <div class="flex flex-col gap-1 text-[10px]">
                    <label class="inline-flex items-center gap-1"><input type="checkbox" id="chkBarLegend" class="checkbox checkbox-xs"> <span>แสดงคำอธิบาย</span></label>
                    <label class="inline-flex items-center gap-1"><input type="checkbox" id="chkBarPercent" class="checkbox checkbox-xs"> <span>% ต่อเดือน</span></label>
                </div>
            </div>
            <canvas id="monthlyBarChart" height="250"></canvas>
        </div>
        <div class="bg-base-100 p-4 rounded-box shadow">
            <h2 class="text-sm font-semibold mb-2">Top 5 อุปกรณ์ที่ถูกเบิกมากที่สุด</h2>
            <canvas id="topItemsDonut" height="100"></canvas>
            <ul id="topItemsList" class="mt-4 text-xs space-y-1"></ul>
        </div>
        <div class="bg-base-100 p-4 rounded-box shadow">
            <h2 class="text-sm font-semibold mb-2">จำนวนอุปกรณ์ที่ถูกเบิกต่อเดือน</h2>
            <canvas id="monthlyTotalsLine" height="250"></canvas>
        </div>
    </div>
    <div class="bg-base-100 px-4 rounded-box shadow mb-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-semibold">กราฟสถิติรายจ่ายทั้งหมดในแต่ละเดือน</h2>
            <label class="label cursor-pointer gap-2 text-xs">
                <span>กรองตามค้นหา</span>
                <input type="checkbox" id="chkFilterRows" class="checkbox checkbox-xs" checked />
            </label>
        </div>
        <canvas id="monthlyExpenseLine" height="50"></canvas>
    </div>
    <div class="bg-base-100 p-4 rounded-box shadow mb-4" style="display:none;">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-semibold">รายละเอียดการเบิกต่อเดือน</h2>
            <label class="label cursor-pointer gap-2 text-xs">
                <span>กรองตามค้นหา</span>
                <input type="checkbox" id="chkFilterRows" class="checkbox checkbox-xs" checked />
            </label>
        </div>
        <div class="overflow-x-auto max-h-80">
            <table class="table table-sm" id="statTable">
                <thead class="sticky top-0 bg-base-200 text-xs">
                <tr>
                    <th class="w-32">เดือน</th>
                    <th>ชื่ออุปกรณ์</th>
                    <th class="text-right w-24">จำนวน</th>
                </tr>
                </thead>
                <tbody class="text-xs"></tbody>
            </table>
        </div>
    </div>

    <div id="loadingOverlay" class="hidden absolute inset-0 bg-base-100/60 backdrop-blur flex items-center justify-center rounded-lg">
        <div class="flex flex-col items-center gap-2">
            <span class="loading loading-spinner loading-lg"></span>
            <p class="text-xs">กำลังโหลดข้อมูล...</p>
        </div>
    </div>
</div>
</div>

<script>
    const daisyColors=['#2563eb','#f59e42','#22c55e','#a21caf','#e11d48','#fbbf24','#0ea5e9','#f472b6','#14b8a6','#64748b','#9333ea','#ea580c'];
    let barChart,donutChart,lineChart,expenseChart;
    
    // ข้อมูลเริ่มต้นที่ส่งมาจาก Controller
    let currentData={
        // item usage per month (still used for other charts)
        monthly_stats:@json($monthlyStats),
        // requisition counts per status per month (new bar chart source)
        monthly_requisition_counts:@json($monthlyRequisitionCounts ?? []),
        top_items:[],
        monthly_totals:{},
        monthly_expense_totals:@json($monthlyExpenseTotals ?? []),
        summary:{pending:{{ $pendingRequisitions }},approved:{{ $approvedRequisitions }},cancelled:{{ $cancelledRequisitions }},rejected:{{ $rejectedRequisitions ?? 0 }},total:{{ $totalRequisitions }}}
    };
    // Hierarchical maps for cascading filters
    const divisionMap=@json($divisionMap ?? []); // section_id -> [{id,name,fullname}]
    const departmentMap=@json($departmentMap ?? []); // division_id -> [{id,name,fullname}]
    const allDivisions = Object.values(divisionMap).flat();
    const allDepartments = Object.values(departmentMap).flat();
    
    // ----- Helper Functions -----
    function formatMonth(key){if(!key||key==='unknown')return '-';const [y,m]=key.split('-');return new Date(y,parseInt(m)-1,1).toLocaleDateString('th-TH',{year:'numeric',month:'short'});}
    function debounce(fn,delay){let t;return(...a)=>{clearTimeout(t);t=setTimeout(()=>fn(...a),delay);};}
    function toggleLoading(show){document.getElementById('loadingOverlay').classList.toggle('hidden',!show);}
    function computeTotals(stats){const totals={};Object.values(stats).forEach(items=>{Object.entries(items).forEach(([name,qty])=>{totals[name]=(totals[name]||0)+qty;});});return totals;}
    function formatCurrency(n){try{return new Intl.NumberFormat('th-TH',{style:'currency',currency:'THB',maximumFractionDigits:0}).format(n||0);}catch(_){return (n||0).toLocaleString('th-TH');}}
    
    // ----- Render Functions -----
    function renderSummary(summary){document.querySelectorAll('[data-summary]').forEach(el=>{const k=el.getAttribute('data-summary');if(summary[k]!==undefined)el.textContent=summary[k];});}
    
    function renderTables(data){
        const tbody=document.querySelector('#statTable tbody');
        tbody.innerHTML='';
        const stats=data.monthly_stats||{};
        const rows=[];
        Object.keys(stats).sort().forEach(month=>{Object.entries(stats[month]).sort((a,b)=>b[1]-a[1]).forEach(([item,qty])=>{rows.push(`<tr><td>${formatMonth(month)}</td><td>${item}</td><td class='text-right'>${qty}</td></tr>`);});});
        tbody.innerHTML=rows.join('')||`<tr><td colspan='3' class='text-center text-xs opacity-60'>ไม่มีข้อมูล</td></tr>`;
        applySearchFilter();
    }
    
    // Build datasets from monthly requisition counts grouped by status
    function buildRequisitionBarDatasets(counts,{percentMode=false}={}){
        const statusMeta={
            pending:{label:'รอดำเนินการ',color:'#2563eb'},
            endprogress:{label:'เสร็จสิ้น',color:'#22c55e'},
            cancelled:{label:'ยกเลิก',color:'#dc2626'},
            rejected:{label:'ไม่อนุมัติ',color:'#f97316'},
            approved:{label:'อนุมัติ',color:'#16a34a'}, // in case approved status used separately
            returned:{label:'ส่งคืน',color:'#6b7280'},
            unknown:{label:'ไม่ทราบสถานะ',color:'#94a3b8'}
        };
        const months=Object.keys(counts).sort();
        // Collect all statuses present
        const allStatuses=new Set();
        months.forEach(m=>{Object.keys(counts[m]||{}).forEach(s=>allStatuses.add(s));});
        const statuses=[...allStatuses];
        const monthTotals=months.map(m=>Object.values(counts[m]||{}).reduce((s,v)=>s+v,0));
        const datasets=statuses.map((status,idx)=>{
            const raw=months.map(m=>counts[m]?.[status]||0);
            const data=percentMode?raw.map((v,i)=>monthTotals[i]?((v/monthTotals[i])*100):0):raw;
            const meta=statusMeta[status]||{label:status,color:daisyColors[idx%daisyColors.length]};
            return {label:meta.label,data,backgroundColor:meta.color,stack:'status'};
        });
        return {labels:months.map(formatMonth),datasets};
    }
    
    function renderCharts(data){
    const stats=data.monthly_stats||{}; // item usage
    const reqCounts=data.monthly_requisition_counts||{}; // requisition counts by status
        
        // 1. Bar Chart (Stacked)
        const percentMode=document.getElementById('chkBarPercent').checked;
        const showLegend=document.getElementById('chkBarLegend').checked;
    const barData=buildRequisitionBarDatasets(reqCounts,{percentMode});
        if(barChart)barChart.destroy();
        barChart=new Chart(document.getElementById('monthlyBarChart'),{type:'bar',data:barData,options:{responsive:true,plugins:{legend:{display:showLegend,position:'top'},tooltip:{callbacks:{label:(ctx)=>{const label=ctx.dataset.label||'';const val=ctx.parsed.y;return percentMode?`${label}: ${val.toFixed(1)}%`:`${label}: ${val}`;}}}},scales:{x:{stacked:true},y:{stacked:true,beginAtZero:true,ticks:{callback:(v)=>percentMode?`${v}%`:v}}}}});
        
        // 2. Donut Chart (Top Items)
        const topItems=data.top_items&&data.top_items.length?data.top_items:Object.entries(computeTotals(stats)).map(([n,q])=>({name:n,quantity:q})).sort((a,b)=>b.quantity-a.quantity).slice(0,5);
        if(donutChart)donutChart.destroy();
        donutChart=new Chart(document.getElementById('topItemsDonut'),{type:'doughnut',data:{labels:topItems.map(i=>i.name),datasets:[{data:topItems.map(i=>i.quantity),backgroundColor:topItems.map((_,i)=>daisyColors[i%daisyColors.length])}]},options:{plugins:{legend:{position:'bottom'}}}});
        // document.getElementById('topItemsList').innerHTML=topItems.map(i=>`<li class='flex justify-between'><span>${i.name}</span><span class='font-semibold'>${i.quantity}</span></li>`).join('');
        
        // 3. Line Chart (Monthly Totals)
        const monthlyTotals=data.monthly_totals&&Object.keys(data.monthly_totals).length?data.monthly_totals:Object.fromEntries(Object.keys(stats).map(m=>[m,Object.values(stats[m]).reduce((s,v)=>s+v,0)]));
        if(lineChart)lineChart.destroy();
        lineChart=new Chart(document.getElementById('monthlyTotalsLine'),{type:'line',data:{labels:Object.keys(monthlyTotals).sort().map(formatMonth),datasets:[{label:'รวมต่อเดือน',data:Object.keys(monthlyTotals).sort().map(k=>monthlyTotals[k]),borderColor:'#2563eb',backgroundColor:'rgba(37,99,235,0.2)',tension:.3,fill:true}]},options:{responsive:true,plugins:{legend:{display:false}}}});

        // 4. Line Chart (Monthly Expense Totals)
        const expenseTotals=data.monthly_expense_totals&&Object.keys(data.monthly_expense_totals).length?data.monthly_expense_totals:{};
        if(expenseChart)expenseChart.destroy();
        expenseChart=new Chart(document.getElementById('monthlyExpenseLine'),{
            type:'line',
            data:{
                labels:Object.keys(expenseTotals).sort().map(formatMonth),
                datasets:[{
                    label:'รายจ่ายรวมต่อเดือน',
                    data:Object.keys(expenseTotals).sort().map(k=>expenseTotals[k]),
                    borderColor:'#16a34a',
                    backgroundColor:'rgba(22,163,74,0.2)',
                    tension:.3,
                    fill:true
                }]
            },
            options:{
                responsive:true,
                plugins:{
                    legend:{display:false},
                    tooltip:{
                        callbacks:{
                            label:(ctx)=>`${ctx.dataset.label}: ${formatCurrency(ctx.parsed.y)}`
                        }
                    }
                },
                scales:{
                    y:{
                        beginAtZero:true,
                        ticks:{
                            callback:(v)=>formatCurrency(v)
                        }
                    }
                }
            }
        });
    }
    
    // ----- Data Fetching -----
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
            renderTables(json);
            renderCharts(json);
        }catch(e){
            console.error(e);
        }finally{
            toggleLoading(false);
        }
    }
    
    // ----- Event Listeners -----
    function applySearchFilter(){
        const q=document.getElementById('searchInput').value.toLowerCase();
        const limit=document.getElementById('chkFilterRows').checked;
        document.querySelectorAll('#statTable tbody tr').forEach(tr=>{
            const match=tr.textContent.toLowerCase().includes(q);
            if(!q){tr.style.display='';return;}
            tr.style.display=limit&&!match?'none':'';
        });
    }
    
    document.getElementById('searchInput').addEventListener('input',debounce(applySearchFilter,200));
    document.getElementById('chkFilterRows').addEventListener('change',applySearchFilter);
    
    document.getElementById('btnResetFilters').addEventListener('click',()=>{
        document.getElementById('filterForm').reset();
        fetchData();
    });
    
    document.getElementById('btnExportCsv').addEventListener('click',()=>{
        const stats=currentData.monthly_stats||{};
        const rows=[['เดือน','ชื่ออุปกรณ์','จำนวน']];
        Object.keys(stats).sort().forEach(month=>{
            Object.entries(stats[month]).forEach(([name,qty])=>{
                rows.push([formatMonth(month),name,qty]);
            });
        });
        const csv=rows.map(r=>r.map(x=>`"${String(x).replace(/"/g,'""')}"`).join(',')).join('\n');
        const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});
        const a=document.createElement('a');
        a.href=URL.createObjectURL(blob);
        a.download='dashboard_requisitions.csv';
        a.click();
    });

    // PDF export removed
    
    document.querySelectorAll('#filterForm input,#filterForm select').forEach(el=>{
        el.addEventListener('change',debounce(fetchData,300));
    });
    document.getElementById('chkBarLegend').addEventListener('change',()=>renderCharts(currentData));
    document.getElementById('chkBarPercent').addEventListener('change',()=>renderCharts(currentData));

    // ----- Cascading Filter Logic -----
    const sectionSelect=document.querySelector('select[name="section"]');
    const divisionSelect=document.querySelector('select[name="division"]');
    const departmentSelect=document.querySelector('select[name="department"]');

    function rebuildOptions(selectEl, items){
        const currentVal=selectEl.value;
        const opts=[`<option value="">ทั้งหมด</option>`].concat(items.map(i=>`<option value="${i.id}">${i.name} (${i.fullname})</option>`));
        selectEl.innerHTML=opts.join('');
        // Try keep previous value if still exists
        if(items.some(i=>String(i.id)===String(currentVal))){selectEl.value=currentVal;} else {selectEl.value='';}
    }

    function handleSectionChange(){
        const sid=sectionSelect.value;
        const divisions = sid? (divisionMap[sid]||[]) : allDivisions;
        rebuildOptions(divisionSelect, divisions);
        // After section change, departments list resets (depends on division)
        rebuildOptions(departmentSelect, []);
    }

    function handleDivisionChange(){
        const did=divisionSelect.value;
        const departments = did? (departmentMap[did]||[]) : (sectionSelect.value? // if section chosen but no division selected show departments belonging to any division under section
            Object.keys(divisionMap).includes(sectionSelect.value)
                ? allDepartments.filter(d=>divisionMap[sectionSelect.value].some(v=>v.id==d.id?false:true)) // fallback empty
                : []
            : allDepartments);
        rebuildOptions(departmentSelect, departments);
    }

    sectionSelect.addEventListener('change',()=>{handleSectionChange();});
    divisionSelect.addEventListener('change',()=>{handleDivisionChange();});

    document.getElementById('btnResetFilters').addEventListener('click',()=>{
        setTimeout(()=>{ // after form.reset()
            rebuildOptions(divisionSelect, allDivisions);
            rebuildOptions(departmentSelect, allDepartments);
        },0);
    });
    
    // ----- Initial Render -----
    // โหลดข้อมูลครั้งแรกด้วยข้อมูลที่ส่งมาจาก PHP
    renderTables(currentData);
    renderCharts(currentData);
    renderSummary(currentData.summary);
</script>
@endsection
