@extends('layouts.parking.app')

@section('content')

<style>
  :root {
    --navy-950: #ffffff;
    --navy-900: #f4f7fa;
    --navy-800: #e7edf3;
    --line: #3f7aa8;
    --line-dim: #c4d2e0;
    --paper: #ffffff;
    --ink: #1c3550;
    --ink-dim: #5c7590;
    --avail: #3fa87c;
    --occupied: #e0703f;
    --reserved: #a8b6c4;
    --amber: #dd9a2b;
  }
  
  .wrap {
    max-width: 95%;
    margin: 0 auto;
    padding: 0 16px;
  }

  header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    border-bottom: 1px solid var(--line-dim);
    padding-bottom: 18px;
    margin-bottom: 20px;
  }
  
  h1 {
    font-size: 1.55rem;
    margin: 0 0 4px;
    font-weight: 700;
    letter-spacing: .2px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  
  header .sub {
    color: var(--ink-dim);
    font-size: .85rem;
  }
  
  .meta {
    font-size: .78rem;
    color: var(--ink-dim);
    text-align: right;
    line-height: 1.6;
  }
  
  .meta b {
    color: var(--ink);
  }

  .map-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 18px;
    align-items: center;
  }
  
  .map-stat {
    background: #ffffff;
    box-shadow: 0 1px 3px rgba(28,53,80,.08);
    border: 1px solid var(--line-dim);
    border-radius: 10px;
    padding: 10px 16px;
    min-width: 120px;
  }
  
  .map-stat .n {
    font-size: 1.4rem;
    font-weight: 700;
  }
  
  .map-stat .l {
    font-size: .72rem;
    color: var(--ink-dim);
  }
  
  .map-stat.avail .n { color: var(--avail); }
  .map-stat.occupied .n { color: var(--occupied); }
  .map-stat.reserved .n { color: var(--reserved); }

  .board-container-wrapper {
    position: relative;
    width: 100%;
    margin-bottom: 16px;
  }
  
  .map-zoom-bar {
    position: absolute;
    top: 12px;
    right: 12px;
    z-index: 40;
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    padding: 4px 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
  }

  .map-zoom-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 10px;
    background: #ffffff;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
  }

  .map-zoom-btn:hover {
    background: #f1f5f9;
    color: #b81515;
    border-color: #cbd5e1;
  }

  .board {
    background: #ffffff;
    box-shadow: 0 1px 4px rgba(28,53,80,.06);
    border: 1px solid var(--line-dim);
    border-radius: 14px;
    padding: 14px;
    overflow: auto;
    position: relative;
    resize: both;
    min-height: 300px;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-x pan-y;
    cursor: grab;
  }
  
  .board:active {
    cursor: grabbing;
  }
  
  #plan {
    display: block;
    width: 100%;
    height: auto;
    background: #f8fafc;
    transform-origin: 0 0;
    transition: transform 0.15s ease-out;
  }

  @media (max-width: 768px) {
    .board {
      padding: 8px;
      min-height: 260px;
    }
    #plan {
      min-width: 850px;
    }
  }

  .slot rect {
    stroke: var(--line);
    stroke-width: 1.3;
    cursor: pointer;
    transition: fill .15s ease, stroke .15s ease;
  }
  
  .slot text {
    fill: #ffffff;
    font-family: monospace;
    font-weight: 600;
    pointer-events: none;
    text-anchor: middle;
    dominant-baseline: central;
  }

  /* Custom map text and icon elements styles */
  .map-text-el {
    cursor: pointer;
    font-family: 'IBM Plex Sans Thai', sans-serif;
    font-weight: bold;
    user-select: none;
  }

  .map-icon-el {
    cursor: pointer;
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    text-anchor: middle;
    dominant-baseline: central;
    user-select: none;
  }

  /* Editable slot visual style */
  .edit-mode-active .slot rect,
  .edit-mode-active .map-text-el,
  .edit-mode-active .map-icon-el {
    stroke-width: 1.5;
    cursor: move !important;
  }

  .edit-mode-active .selected-edit rect {
    stroke: #ef4444 !important;
    stroke-width: 3 !important;
    fill: #fee2e2 !important;
  }

  .edit-mode-active .selected-edit text {
    fill: #ef4444 !important;
  }

  .edit-mode-active .selected-edit.map-text-el,
  .edit-mode-active .selected-edit.map-icon-el {
    fill: #ef4444 !important;
  }
  
  .unplaced-slot {
    display: none;
  }
  
  .edit-mode-active .unplaced-slot {
    display: inline;
  }
  
  .state-avail rect { fill: var(--avail); }
  .state-occupied rect { fill: var(--occupied); }
  .state-reserved rect { fill: var(--reserved); }

  .fixed-label {
    fill: var(--ink-dim);
    font-size: 11px;
  }
  
  .zone-label {
    fill: var(--ink-dim);
    font-size: 13px;
  }
  
  .road {
    fill: none;
    stroke: var(--line-dim);
    stroke-width: 1.2;
    stroke-dasharray: 2 6;
  }
  
  .curb {
    fill: none;
    stroke: var(--ink-dim);
    stroke-width: 1.6;
  }
  
  .gate {
    fill: var(--amber);
    opacity: .9;
  }
  
  .gate-text {
    fill: #fff;
    font-weight: 700;
    font-size: 13px;
    text-anchor: middle;
  }
  
  .pole circle { fill: var(--amber); }
  .guard-box {
    fill: #f1f5f9;
    stroke: var(--ink-dim);
    stroke-width: 1.4;
  }

  .legend {
    margin-top: 18px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 10px 24px;
    font-size: .82rem;
    color: var(--ink-dim);
    border-top: 1px solid var(--line-dim);
    padding-top: 16px;
  }
  
  .legend .row {
    display: flex;
    gap: 10px;
    align-items: flex-start;
  }
  
  .swatch {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    flex: none;
    margin-top: 2px;
  }
  
  .sw-avail { background: var(--avail); }
  .sw-occupied { background: var(--occupied); }
  .sw-reserved { background: var(--reserved); }
  .sw-pole { background: var(--amber); border-radius: 50%; }

  .action-bar {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
  }

  .editor-control-panel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    min-width: 320px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
  }
</style>

@if(request()->query('select_mode'))
<style>
  nav, footer, .wrap > div:first-child, header, .map-stats, .legend, .action-bar {
    display: none !important;
  }
  .wrap { max-width: 100% !important; padding: 0 !important; }
  .pt-24 { padding-top: 10px !important; padding-bottom: 10px !important; }
  body, .bg-slate-50, .board { background: #fff !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
</style>
@endif

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
  <div class="wrap">
    <div style="margin-bottom: 16px; margin-top: -50px;" class="flex justify-between items-center">
      <a href="javascript:history.back()" style="text-decoration:none; color:#1c3550; font-size:0.9rem; font-weight:500; display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:1px solid var(--line-dim); border-radius:8px; background:#fff; box-shadow:0 1px 3px rgba(28,53,80,.06); transition:all .15s ease;" onmouseover="this.style.borderColor='var(--line)'" onmouseout="this.style.borderColor='var(--line-dim)'">
        &larr; กลับหน้าก่อนหน้านี้
      </a>
      
      {{-- Admin layout controls --}}
      @php
          $isAdmin = Auth::check() && Auth::user()->is_hams_admin;
      @endphp
      @if($isAdmin)
      <div class="flex gap-2">
        <button id="btn-toggle-edit" onclick="toggleEditMode()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-sm shadow flex items-center gap-1.5 transition-colors">
          <i class="fa-solid fa-pen-to-square"></i> โหมดแก้ไข / จัดการตำแหน่ง
        </button>
      </div>
      @endif
    </div>

    <header>
      <div>
        <h1>
          <span>ลานจอดรถสำนักงานใหญ่ — ผังบริหารจัดการที่จอด</span>
        </h1>
        <div class="sub" style="margin-top: 4px;">Option 2-1 · เรียงลำดับช่องจอดตามแบบแปลนต้นฉบับ · คลิกที่ช่องเพื่อเปลี่ยนสถานะ</div>
      </div>
      <div class="meta">
        SCALE 1:300 (อ้างอิง)<br>
        รวม <b id="total-slots-count">{{ count($slots) }}</b> ช่องจอด · ความกว้างพื้นที่ 120 ม.<br>
        อัปเดตผัง <b>21-3-69</b> · หน้านี้สร้าง <b id="today"></b>
      </div>
    </header>

    {{-- Editor Toolbox (Hidden by default, shown in edit mode) - Restricted to admin --}}
    @if($isAdmin)
    <div id="editor-bar" class="action-bar hidden">
      <div class="flex flex-col gap-2 w-full md:w-auto">
        <span class="text-xs font-bold text-slate-700">เพิ่มวัตถุลงบนผัง:</span>
        <div class="flex gap-2">
          <button onclick="addNewSlotPrompt()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded flex items-center gap-1">
            <i class="fa-solid fa-square"></i> เพิ่มช่องจอด
          </button>
          <button onclick="addNewTextPrompt()" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded flex items-center gap-1">
            <i class="fa-solid fa-font"></i> เพิ่มข้อความ/รายละเอียด
          </button>
          <button onclick="addNewIconPrompt()" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded flex items-center gap-1">
            <i class="fa-solid fa-icons"></i> เพิ่มไอคอน/ป้าย
          </button>
        </div>
      </div>

      {{-- Position and Rotate inputs --}}
      <div class="editor-control-panel">
        <div class="flex justify-between items-center">
          <span class="text-xs font-bold text-slate-800">วัตถุที่เลือก: <span id="lbl-selected-slot-id" class="text-indigo-600">-</span></span>
          <button id="btn-delete-selected" onclick="deleteSelectedObject()" disabled class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded disabled:opacity-50">
            <i class="fa-solid fa-trash"></i> ลบวัตถุนี้
          </button>
        </div>
        
        <div class="flex gap-2 items-center">
          <label class="text-[10px] w-12 shrink-0">หมุน:</label>
          <input type="range" id="input-rotation" min="-180" max="180" value="0" oninput="updateSelectedProperty('rotation', this.value)" class="range range-primary range-xs flex-1" />
          <span id="lbl-rotation" class="text-xs font-mono w-8 text-right">0°</span>
        </div>

        <div id="slot-dimension-inputs" class="flex gap-2">
          <div class="flex-1">
            <label class="text-[10px] block">กว้าง (W):</label>
            <input type="number" id="input-width" oninput="updateSelectedProperty('width', this.value)" class="input input-bordered input-xs w-full" />
          </div>
          <div class="flex-1">
            <label class="text-[10px] block">ยาว (H):</label>
            <input type="number" id="input-height" oninput="updateSelectedProperty('height', this.value)" class="input input-bordered input-xs w-full" />
          </div>
        </div>

        <div id="element-text-inputs" class="flex flex-col gap-1 hidden">
          <div>
            <label class="text-[10px] block font-bold text-slate-600">ข้อความ / Class ไอคอน:</label>
            <input type="text" id="input-content" oninput="updateSelectedProperty('content', this.value)" class="input input-bordered input-xs w-full" />
          </div>
          <div class="flex gap-2">
            <div class="flex-1">
              <label class="text-[10px] block font-bold text-slate-600">ขนาดตัวอักษร:</label>
              <input type="number" id="input-scale" min="0.5" max="3.0" step="0.1" oninput="updateSelectedProperty('scale', this.value)" class="input input-bordered input-xs w-full" />
            </div>
            <div class="flex-1">
              <label class="text-[10px] block font-bold text-slate-600">สีข้อความ/ไอคอน:</label>
              <input type="color" id="input-color" oninput="updateSelectedProperty('color', this.value)" class="w-full h-6 border rounded" />
            </div>
          </div>
        </div>
      </div>

      <div class="ml-auto flex gap-2">
        <button onclick="saveLayoutChanges()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm shadow flex items-center gap-1.5 transition-colors">
          <i class="fa-solid fa-floppy-disk"></i> บันทึกตำแหน่งและองศา
        </button>
        <button onclick="cancelLayoutChanges()" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl text-sm transition-colors">
          ยกเลิก / ปิด
        </button>
      </div>
    </div>
    @endif

    <div class="map-stats">
      <div class="map-stat"><div class="n" id="stat-total">{{ count($slots) }}</div><div class="l">ช่องจอดทั้งหมด</div></div>
      <div class="map-stat avail"><div class="n" id="cAvail">0</div><div class="l">ว่าง</div></div>
      <div class="map-stat occupied"><div class="n" id="cOcc">0</div><div class="l">มีรถจอด</div></div>
      <div class="map-stat reserved"><div class="n" id="cRes">0</div><div class="l">ปิด/สงวนไว้</div></div>
      @if($isAdmin)
      <button class="reset-btn" onclick="resetAll()">รีเซ็ตสถานะทั้งหมด</button>
      @endif
    </div>

    <div class="board-container-wrapper">
      <div class="map-zoom-bar shadow-md">
        <button type="button" onclick="zoomMap(0.2)" class="map-zoom-btn" title="ขยาย (Zoom In)">
          <i class="fa-solid fa-plus text-xs"></i> <span class="hidden sm:inline">ขยาย</span>
        </button>
        <button type="button" onclick="zoomMap(-0.2)" class="map-zoom-btn" title="ย่อ (Zoom Out)">
          <i class="fa-solid fa-minus text-xs"></i> <span class="hidden sm:inline">ย่อ</span>
        </button>
        <button type="button" onclick="resetMapZoom()" class="map-zoom-btn" title="รีเซ็ต (Reset)">
          <i class="fa-solid fa-rotate-left text-xs"></i> <span id="map-zoom-val">100%</span>
        </button>
      </div>

      <div class="board" id="board-container">
        <svg id="plan" viewBox="0 0 2200 530" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="hatch" width="8" height="8" patternTransform="rotate(45)" patternUnits="userSpaceOnUse">
              <rect width="8" height="8" fill="#e7edf3"/>
              <line x1="0" y1="0" x2="0" y2="8" stroke="#3f7aa8" stroke-width="2"/>
            </pattern>
          </defs>

          <rect x="30" y="30" width="2140" height="470" fill="none" class="curb"/>

          <path class="road" d="M40,140 C300,70 800,30 1200,50 C1550,65 1900,45 2160,30"/>
          <text x="1600" y="46" class="fixed-label">FOOTPATH</text>
          <path class="road" d="M230,230 C360,190 480,235 585,285"/>
          <g>
            <rect class="guard-box" x="90" y="460" width="100" height="44"/>
            <text class="fixed-label" x="140" y="485" text-anchor="middle">GUARD</text>
          </g>

          <g id="slots"></g>
          <g id="custom-elements"></g>

          
        </svg>
      </div>
    </div>

    <div class="legend">
      <div class="row"><span class="swatch sw-avail"></span>ช่องจอดว่าง (คลิก 1 ครั้งเพื่อบันทึกรถเข้า)</div>
      <div class="row"><span class="swatch sw-occupied"></span>ช่องมีรถจอดอยู่ (คลิกซ้ำเพื่อสงวน/ปิดปรับปรุง)</div>
      <div class="row"><span class="swatch sw-reserved"></span>ช่องปิดปรับปรุง/สงวนไว้ (คลิกซ้ำเพื่อเปิดว่างอีกครั้ง)</div>
    </div>
  </div>
</div>

{{-- Popup Modal details --}}
<div id="employeeModal" class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
    <div class="h-2 w-full bg-[#b81515]"></div>
    <div class="p-6">
      <div class="flex justify-between items-start mb-4">
        <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
          <i class="fa-solid fa-square-parking text-[#b81515]"></i> ข้อมูลรถพนักงาน ช่อง <span id="modalSlotNumber"></span>
        </h3>
        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-6">
        <div id="modalUserPhoto" class="w-16 h-16 rounded-full overflow-hidden shadow-inner bg-slate-200 text-slate-400 flex items-center justify-center text-xl">
          <i class="fa-solid fa-user"></i>
        </div>
        <div>
          <div class="text-lg font-bold text-slate-800" id="modalUserName"></div>
          <div class="text-sm text-slate-500 font-medium" id="modalUserDept"></div>
        </div>
      </div>

      <div class="space-y-4">
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-slate-500 font-semibold text-sm">เลขทะเบียนรถ</span>
          <span class="px-3 py-1 bg-slate-100 text-slate-800 rounded font-bold border border-slate-200" id="modalCarRegistration"></span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-slate-100">
          <span class="text-slate-500 font-semibold text-sm">เวลาที่เข้าจอด</span>
          <span class="text-slate-800 font-bold" id="modalTimeIn"></span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-slate-500 font-semibold text-sm">สถานะ</span>
          <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">
            <i class="fa-solid fa-circle text-[8px] mr-1"></i> รถพนักงานจอดอยู่
          </span>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <button onclick="closeModal()" class="px-6 py-2 bg-slate-950 hover:bg-slate-800 text-white font-bold rounded-xl transition-all shadow-lg text-sm">
          ปิดหน้าต่าง
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('today').textContent = new Date().toLocaleDateString('th-TH');

const dbSlots = @json($slots);
const dbElements = @json($elements);
const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
const NS = "http://www.w3.org/2000/svg";
const slotsG = document.getElementById('slots');
const elementsG = document.getElementById('custom-elements');

let isEditMode = false;
let selectedSlotId = null;
let selectedElementId = null;

let dragElement = null;
let dragStartX = 0;
let dragStartY = 0;
let hasDragged = false;
let offset = { x: 0, y: 0 };

const slotData = [];
const elementData = [];

// Load map slots
Object.keys(dbSlots).forEach(key => {
    const s = dbSlots[key];
    createSlotElement(s.slot_number, s.pos_x, s.pos_y, s.width, s.height, s.rotation, s.status, s);
});

// Load map elements
dbElements.forEach(el => {
    createMapElementNode(el.id, el.type, el.content, el.pos_x, el.pos_y, el.rotation, el.scale, el.color);
});

updateStats();

function createSlotElement(id, x, y, w, h, rot, status, rawData) {
    const g = document.createElementNS(NS, 'g');
    let stateClass = 'state-avail';
    if (status === 'occupied') stateClass = 'state-occupied';
    else if (status === 'reserved') stateClass = 'state-reserved';
    
    let unplacedClass = (parseFloat(x) === 0 && parseFloat(y) === 0) ? ' unplaced-slot' : '';

    g.setAttribute('class', 'slot ' + stateClass + unplacedClass);
    g.setAttribute('data-id', id);
    
    const r = document.createElementNS(NS, 'rect');
    r.setAttribute('x', -w/2);
    r.setAttribute('y', -h/2);
    r.setAttribute('width', w);
    r.setAttribute('height', h);
    r.setAttribute('rx', 3);

    const t = document.createElementNS(NS, 'text');
    t.setAttribute('x', 0);
    t.setAttribute('y', 0);
    t.setAttribute('font-size', 14);
    t.textContent = id;

    g.appendChild(r);
    g.appendChild(t);
    
    g.setAttribute('transform', `translate(${x}, ${y}) rotate(${rot})`);

    const title = document.createElementNS(NS, 'title');
    if (rawData && rawData.employee_parkings && rawData.employee_parkings.length > 0) {
        const p = rawData.employee_parkings[0];
        title.textContent = `ช่องที่ ${id}: จอดโดย ${p.user ? p.user.fullname : 'ไม่ทราบชื่อ'}`;
    } else {
        title.textContent = `ช่องที่ ${id} (ว่าง)`;
    }
    g.appendChild(title);

    g.addEventListener('mousedown', onSlotMouseDown);
    g.addEventListener('click', (e) => onSlotClick(e, id, rawData));

    slotsG.appendChild(g);
    slotData.push({
        id: id,
        el: g,
        x: parseFloat(x),
        y: parseFloat(y),
        width: parseFloat(w),
        height: parseFloat(h),
        rotation: parseFloat(rot),
        status: status
    });
}

function createMapElementNode(id, type, content, x, y, rot, scale, color) {
    let node;
    if (type === 'text') {
        node = document.createElementNS(NS, 'text');
        node.setAttribute('class', 'map-text-el');
        node.setAttribute('font-size', 14 * scale);
        node.setAttribute('text-anchor', 'middle');
        node.setAttribute('dominant-baseline', 'central');
        node.textContent = content;
    } else {
        node = document.createElementNS(NS, 'text');
        node.setAttribute('class', 'map-icon-el');
        node.setAttribute('font-size', 18 * scale);
        // Convert FontAwesome code if needed or render simple icon text
        node.textContent = getFontAwesomeUnicode(content) || '\uf0eb'; // default bulb
    }

    node.setAttribute('data-el-id', id);
    node.setAttribute('fill', color || '#1c3550');
    node.setAttribute('transform', `translate(${x}, ${y}) rotate(${rot})`);

    node.addEventListener('mousedown', onElementMouseDown);

    elementsG.appendChild(node);
    elementData.push({
        id: id,
        type: type,
        el: node,
        x: parseFloat(x),
        y: parseFloat(y),
        rotation: parseFloat(rot),
        scale: parseFloat(scale),
        content: content,
        color: color || '#1c3550'
    });
}

// Convert FontAwesome text to unicode character for SVG rendering
function getFontAwesomeUnicode(cls) {
    const icons = {
        'fa-lightbulb': '\uf0eb',
        'fa-car': '\uf1b9',
        'fa-arrow-left': '\uf060',
        'fa-arrow-right': '\uf061',
        'fa-arrow-up': '\uf062',
        'fa-arrow-down': '\uf063',
        'fa-circle-exclamation': '\uf06a',
        'fa-square-parking': '\uf540',
        'fa-charging-station': '\uf5e7',
        'fa-tree': '\uf1bb',
        'fa-door-open': '\uf52b',
        'fa-restroom': '\uf7bd'
    };
    return icons[cls] || icons[cls.replace('fa-solid ', '').replace('fa-regular ', '')] || '';
}

// Select/Click handling
function onSlotClick(e, id, rawData) {
    if (isEditMode) return;

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('select_mode')) {
        const isOccupied = rawData && (rawData.status === 'occupied' || rawData.status === 'reserved' || (rawData.employee_parkings && rawData.employee_parkings.length > 0));
        if (isOccupied) {
            Swal.fire({ icon: 'error', title: 'ไม่สามารถเลือกได้', text: `ช่องจอด ${id} ไม่ว่าง` });
            return;
        }
        window.parent.postMessage({ type: 'slot_selected', slot: String(id) }, '*');
        return;
    }

    if (rawData && rawData.status === 'occupied' && rawData.employee_parkings && rawData.employee_parkings.length > 0) {
        if (isAdmin) showEmployeeInfo(rawData.employee_parkings[0], id);
        else Swal.fire({ icon: 'warning', title: 'สงวนสิทธิ์ข้อมูลสำหรับผู้ดูแลระบบ' });
    } else {
        if (isAdmin) cycle(id);
    }
}

// Enable Drag and Drop
function onSlotMouseDown(e) {
    if (!isEditMode) return;
    e.preventDefault();
    
    const id = this.getAttribute('data-id');
    dragElement = this;
    dragStartX = e.clientX;
    dragStartY = e.clientY;
    hasDragged = false;
    
    const rec = slotData.find(s => s.id == id);
    const svg = document.getElementById('plan');
    const rect = svg.getBoundingClientRect();
    
    // Calculate client mouse coordinates inside SVG viewBox
    const viewBox = svg.viewBox.baseVal;
    const scaleX = viewBox.width / rect.width;
    const scaleY = viewBox.height / rect.height;
    
    const mouseX = (e.clientX - rect.left) * scaleX;
    const mouseY = (e.clientY - rect.top) * scaleY;
    
    offset.x = mouseX - rec.x;
    offset.y = mouseY - rec.y;

    window.addEventListener('mousemove', onSlotMouseMove);
    window.addEventListener('mouseup', onSlotMouseUp);
}

function onSlotMouseMove(e) {
    if (!dragElement || !isEditMode) return;
    
    const dist = Math.hypot(e.clientX - dragStartX, e.clientY - dragStartY);
    if (dist < 4) return;
    
    hasDragged = true;
    const id = dragElement.getAttribute('data-id');
    const rec = slotData.find(s => s.id == id);
    
    const svg = document.getElementById('plan');
    const rect = svg.getBoundingClientRect();
    
    const viewBox = svg.viewBox.baseVal;
    const scaleX = viewBox.width / rect.width;
    const scaleY = viewBox.height / rect.height;
    
    const mouseX = (e.clientX - rect.left) * scaleX;
    const mouseY = (e.clientY - rect.top) * scaleY;
    
    rec.x = Math.round(mouseX - offset.x);
    rec.y = Math.round(mouseY - offset.y);
    
    updateSlotTransform(rec);
}

function onSlotMouseUp(e) {
    if (dragElement && !hasDragged) {
        const id = dragElement.getAttribute('data-id');
        selectSlotForEditing(id);
    }
    dragElement = null;
    window.removeEventListener('mousemove', onSlotMouseMove);
    window.removeEventListener('mouseup', onSlotMouseUp);
}

function updateSlotTransform(rec) {
    rec.el.setAttribute('transform', `translate(${rec.x}, ${rec.y}) rotate(${rec.rotation})`);
}

// Drag & Drop for Decorator Elements (Icons/Text)
function onElementMouseDown(e) {
    if (!isEditMode) return;
    
    const id = this.getAttribute('data-el-id');
    dragElement = this;
    dragStartX = e.clientX;
    dragStartY = e.clientY;
    hasDragged = false;
    
    const rec = elementData.find(el => el.id == id);
    const svg = document.getElementById('plan');
    const rect = svg.getBoundingClientRect();
    
    const viewBox = svg.viewBox.baseVal;
    const scaleX = viewBox.width / rect.width;
    const scaleY = viewBox.height / rect.height;
    
    const mouseX = (e.clientX - rect.left) * scaleX;
    const mouseY = (e.clientY - rect.top) * scaleY;
    
    offset.x = mouseX - rec.x;
    offset.y = mouseY - rec.y;

    window.addEventListener('mousemove', onElementMouseMove);
    window.addEventListener('mouseup', onElementMouseUp);
}

function onElementMouseMove(e) {
    if (!dragElement || !isEditMode) return;
    
    const dist = Math.hypot(e.clientX - dragStartX, e.clientY - dragStartY);
    if (dist < 4) return;
    
    hasDragged = true;
    const id = dragElement.getAttribute('data-el-id');
    const rec = elementData.find(el => el.id == id);
    
    const svg = document.getElementById('plan');
    const rect = svg.getBoundingClientRect();
    
    const viewBox = svg.viewBox.baseVal;
    const scaleX = viewBox.width / rect.width;
    const scaleY = viewBox.height / rect.height;
    
    const mouseX = (e.clientX - rect.left) * scaleX;
    const mouseY = (e.clientY - rect.top) * scaleY;
    
    rec.x = Math.round(mouseX - offset.x);
    rec.y = Math.round(mouseY - offset.y);
    
    rec.el.setAttribute('transform', `translate(${rec.x}, ${rec.y}) rotate(${rec.rotation})`);
}

function onElementMouseUp(e) {
    if (dragElement && !hasDragged) {
        const id = dragElement.getAttribute('data-el-id');
        selectElementForEditing(id);
    }
    dragElement = null;
    window.removeEventListener('mousemove', onElementMouseMove);
    window.removeEventListener('mouseup', onElementMouseUp);
}

// Select Slot in Editor
function selectSlotForEditing(id) {
    selectedSlotId = id;
    selectedElementId = null;
    
    document.querySelectorAll('.slot, .map-text-el, .map-icon-el').forEach(el => el.classList.remove('selected-edit'));
    
    const rec = slotData.find(s => s.id == id);
    if (rec) {
        rec.el.classList.add('selected-edit');
        document.getElementById('lbl-selected-slot-id').textContent = 'ช่องจอด: ' + id;
        document.getElementById('input-rotation').value = rec.rotation;
        document.getElementById('lbl-rotation').textContent = rec.rotation + '°';
        document.getElementById('input-width').value = rec.width;
        document.getElementById('input-height').value = rec.height;
        
        document.getElementById('slot-dimension-inputs').classList.remove('hidden');
        document.getElementById('element-text-inputs').classList.add('hidden');
        document.getElementById('btn-delete-selected').disabled = false;
    }
}

// Select Decorative Element in Editor
function selectElementForEditing(id) {
    selectedElementId = id;
    selectedSlotId = null;
    
    document.querySelectorAll('.slot, .map-text-el, .map-icon-el').forEach(el => el.classList.remove('selected-edit'));
    
    const rec = elementData.find(el => el.id == id);
    if (rec) {
        rec.el.classList.add('selected-edit');
        document.getElementById('lbl-selected-slot-id').textContent = 'วัตถุตกแต่ง #' + id;
        document.getElementById('input-rotation').value = rec.rotation;
        document.getElementById('lbl-rotation').textContent = rec.rotation + '°';
        
        document.getElementById('input-content').value = rec.content;
        document.getElementById('input-scale').value = rec.scale;
        document.getElementById('input-color').value = rec.color;

        document.getElementById('slot-dimension-inputs').classList.add('hidden');
        document.getElementById('element-text-inputs').classList.remove('hidden');
        document.getElementById('btn-delete-selected').disabled = false;
    }
}

// Rotation / Properties Update
function updateSelectedProperty(prop, value) {
    if (selectedSlotId) {
        const rec = slotData.find(s => s.id == selectedSlotId);
        if (!rec) return;

        if (prop === 'rotation') {
            rec.rotation = parseFloat(value);
            document.getElementById('lbl-rotation').textContent = value + '°';
        } else if (prop === 'width') {
            rec.width = parseFloat(value);
            rec.el.querySelector('rect').setAttribute('width', value);
            rec.el.querySelector('rect').setAttribute('x', -value/2);
        } else if (prop === 'height') {
            rec.height = parseFloat(value);
            rec.el.querySelector('rect').setAttribute('height', value);
            rec.el.querySelector('rect').setAttribute('y', -value/2);
        }
        updateSlotTransform(rec);
    } else if (selectedElementId) {
        const rec = elementData.find(el => el.id == selectedElementId);
        if (!rec) return;

        if (prop === 'rotation') {
            rec.rotation = parseFloat(value);
            document.getElementById('lbl-rotation').textContent = value + '°';
        } else if (prop === 'content') {
            rec.content = value;
            if (rec.type === 'text') {
                rec.el.textContent = value;
            } else {
                rec.el.textContent = getFontAwesomeUnicode(value) || '\uf0eb';
            }
        } else if (prop === 'scale') {
            rec.scale = parseFloat(value);
            const baseSize = rec.type === 'text' ? 14 : 18;
            rec.el.setAttribute('font-size', baseSize * rec.scale);
        } else if (prop === 'color') {
            rec.color = value;
            rec.el.setAttribute('fill', value);
        }
        rec.el.setAttribute('transform', `translate(${rec.x}, ${rec.y}) rotate(${rec.rotation})`);
    }
}

// Toggle layout editing mode
function toggleEditMode() {
    if (!isAdmin) return;
    isEditMode = !isEditMode;
    const board = document.querySelector('.board');
    const editorBar = document.getElementById('editor-bar');
    
    if (isEditMode) {
        board.classList.add('edit-mode-active');
        if (editorBar) editorBar.classList.remove('hidden');
        document.getElementById('btn-toggle-edit').innerHTML = '<i class="fa-solid fa-eye"></i> ดูแผนผังปกติ';
    } else {
        board.classList.remove('edit-mode-active');
        if (editorBar) editorBar.classList.add('hidden');
        document.querySelectorAll('.slot, .map-text-el, .map-icon-el').forEach(el => el.classList.remove('selected-edit'));
        document.getElementById('btn-toggle-edit').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> โหมดแก้ไข / จัดการตำแหน่ง';
    }
}

// Cancel editing and revert layout
function cancelLayoutChanges() {
    if (!isAdmin) return;
    isEditMode = false;
    const board = document.querySelector('.board');
    const editorBar = document.getElementById('editor-bar');
    board.classList.remove('edit-mode-active');
    if (editorBar) editorBar.classList.add('hidden');
    
    Swal.fire({
        title: 'ยกเลิกการแก้ไข',
        text: 'กำลังโหลดข้อมูลผังตำแหน่งเดิม...',
        timer: 1000,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    }).then(() => {
        window.location.reload();
    });
}

// Save Layout API call (Slots + Elements)
function saveLayoutChanges() {
    const slotsToSend = slotData.map(s => ({
        id: s.id,
        x: s.x,
        y: s.y,
        rotation: s.rotation,
        width: s.width,
        height: s.height
    }));

    const elementsToSend = elementData.map(el => ({
        id: el.id,
        x: el.x,
        y: el.y,
        rotation: el.rotation,
        scale: el.scale,
        content: el.content,
        color: el.color
    }));

    // Show Saving modal
    Swal.fire({
        title: 'กำลังบันทึกตำแหน่ง...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch("{{ route('parking.map.save_layout') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ slots: slotsToSend, elements: elementsToSend })
    })
    .then(res => res.json())
    .then(data => {
        Swal.close();
        if (data.success) {
            Swal.fire({ 
                icon: 'success', 
                title: 'บันทึกตำแหน่งสำเร็จ', 
                text: 'บันทึกโครงสร้างลานจอดรถเรียบร้อยแล้ว',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                isEditMode = false;
                toggleEditMode();
                window.location.reload();
            });
        }
    })
    .catch(err => {
        Swal.close();
        console.error("Save failed:", err);
        Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ไม่สามารถบันทึกข้อมูลผังได้', confirmButtonText: 'ตกลง' });
    });
}

// Delete Selected Slot or Decorative Element
function deleteSelectedObject() {
    if (selectedSlotId) {
        // Delete Slot
        Swal.fire({
            title: 'ลบช่องจอด?',
            text: `คุณต้องการลบช่องจอดหมายเลข ${selectedSlotId} ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบช่องจอด',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('parking/map/delete-slot') }}/${selectedSlotId}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const recIdx = slotData.findIndex(s => s.id == selectedSlotId);
                        if (recIdx !== -1) {
                            slotData[recIdx].el.remove();
                            slotData.splice(recIdx, 1);
                        }
                        
                        const countEl = document.getElementById('total-slots-count');
                        const statTotal = document.getElementById('stat-total');
                        const newVal = parseInt(countEl.textContent) - 1;
                        countEl.textContent = newVal;
                        statTotal.textContent = newVal;
                        
                        selectedSlotId = null;
                        document.getElementById('lbl-selected-slot-id').textContent = '-';
                        document.getElementById('btn-delete-selected').disabled = true;
                        updateStats();
                        Swal.fire({ title: 'ลบแล้ว', text: 'ลบช่องจอดเรียบร้อยแล้ว', icon: 'success', confirmButtonText: 'ตกลง' });
                    }
                });
            }
        });
    } else if (selectedElementId) {
        // Delete Element
        Swal.fire({
            title: 'ลบวัตถุตกแต่ง?',
            text: 'คุณต้องการลบวัตถุหรือรายละเอียดข้อความนี้ใช่หรือไม่?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ลบวัตถุ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('parking/map/delete-element') }}/${selectedElementId}`, {
                    method: "DELETE",
                    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}", "Accept": "application/json" }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const recIdx = elementData.findIndex(el => el.id == selectedElementId);
                        if (recIdx !== -1) {
                            elementData[recIdx].el.remove();
                            elementData.splice(recIdx, 1);
                        }
                        
                        selectedElementId = null;
                        document.getElementById('lbl-selected-slot-id').textContent = '-';
                        document.getElementById('btn-delete-selected').disabled = true;
                        document.getElementById('element-text-inputs').classList.add('hidden');
                        Swal.fire({ title: 'ลบแล้ว', text: 'ลบวัตถุตกแต่งเรียบร้อยแล้ว', icon: 'success', confirmButtonText: 'ตกลง' });
                    }
                });
            }
        });
    }
}

// Add New Slot Prompt
function addNewSlotPrompt() {
    Swal.fire({
        title: 'เพิ่มช่องจอดใหม่',
        input: 'text',
        inputPlaceholder: 'กรอกหมายเลขช่องจอด (เช่น 75)',
        showCancelButton: true,
        confirmButtonText: 'เพิ่มช่อง',
        cancelButtonText: 'ยกเลิก',
        preConfirm: (value) => {
            if (!value) Swal.showValidationMessage('กรุณากรอกหมายเลขช่องจอด');
            return value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const slotNum = result.value;
            fetch("{{ route('parking.map.add_slot') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    slot_number: slotNum,
                    pos_x: 150,
                    pos_y: 100,
                    rotation: 0,
                    width: 34,
                    height: 76
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    createSlotElement(slotNum, 200, 200, 34, 76, 0, 'available', data.slot);
                    selectSlotForEditing(slotNum);
                    
                    const countEl = document.getElementById('total-slots-count');
                    const statTotal = document.getElementById('stat-total');
                    const newVal = parseInt(countEl.textContent) + 1;
                    countEl.textContent = newVal;
                    statTotal.textContent = newVal;
                    updateStats();
                } else {
                    Swal.fire({ title: 'ผิดพลาด', text: data.message, icon: 'error', confirmButtonText: 'ตกลง' });
                }
            });
        }
    });
}

// Add Custom Text
function addNewTextPrompt() {
    Swal.fire({
        title: 'เพิ่มข้อความรายละเอียด',
        input: 'text',
        inputPlaceholder: 'พิมพ์ข้อความ (เช่น ทางเข้า, จุดจอดรถจักรยานยนต์)',
        showCancelButton: true,
        confirmButtonText: 'เพิ่มข้อความ',
        cancelButtonText: 'ยกเลิก',
        preConfirm: (value) => {
            if (!value) Swal.showValidationMessage('กรุณากรอกข้อความ');
            return value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('parking.map.add_element') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    type: 'text',
                    content: result.value,
                    pos_x: 150,
                    pos_y: 100,
                    color: '#1c3550'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const el = data.element;
                    createMapElementNode(el.id, 'text', el.content, el.pos_x, el.pos_y, el.rotation, el.scale, el.color);
                    selectElementForEditing(el.id);
                }
            });
        }
    });
}

// Add Custom Icon
function addNewIconPrompt() {
    Swal.fire({
        title: 'เลือกไอคอนตกแต่ง',
        input: 'select',
        inputOptions: {
            'fa-lightbulb': '💡 เสาไฟ/CCTV',
            'fa-car': '🚗 รถยนต์',
            'fa-arrow-left': '⬅ ลูกศรซ้าย',
            'fa-arrow-right': '➡ ลูกศรขวา',
            'fa-arrow-up': '⬆ ลูกศรขึ้น',
            'fa-arrow-down': '⬇ ลูกศรลง',
            'fa-circle-exclamation': '⚠ ป้ายเตือน',
            'fa-charging-station': '⚡ จุดชาร์จ EV',
            'fa-tree': '🌳 ต้นไม้',
            'fa-restroom': '🚻 ห้องน้ำ'
        },
        showCancelButton: true,
        confirmButtonText: 'เพิ่มไอคอน',
        cancelButtonText: 'ยกเลิก',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('parking.map.add_element') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    type: 'icon',
                    content: result.value,
                    pos_x: 150,
                    pos_y: 100,
                    color: '#dd9a2b'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const el = data.element;
                    createMapElementNode(el.id, 'icon', el.content, el.pos_x, el.pos_y, el.rotation, el.scale, el.color);
                    selectElementForEditing(el.id);
                }
            });
        }
    });
}

// Status Cycling for Admin
function cycle(id) {
    const rec = slotData.find(s => s.id === id);
    const el = rec.el;
    if (el.classList.contains('state-avail')) {
        el.classList.replace('state-avail', 'state-occupied');
    } else if (el.classList.contains('state-occupied')) {
        el.classList.replace('state-occupied', 'state-reserved');
    } else {
        el.classList.replace('state-reserved', 'state-avail');
    }
    updateStats();
}

function resetAll() {
    Swal.fire({
        title: 'ยืนยันการรีเซ็ต?',
        text: "คุณแน่ใจหรือไม่ที่จะรีเซ็ตสถานะช่องจอดทั้งหมดให้กลับเป็นว่าง?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'ใช่, รีเซ็ตเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            slotData.forEach(s => {
                s.el.classList.remove('state-occupied', 'state-reserved');
                s.el.classList.add('state-avail');
            });
            updateStats();
            Swal.fire({ title: 'รีเซ็ตสำเร็จ!', text: 'สถานะช่องจอดทั้งหมดถูกรีเซ็ตเรียบร้อยแล้ว', icon: 'success', confirmButtonText: 'ตกลง' });
        }
    });
}

function updateStats() {
    let a = 0, o = 0, r = 0;
    slotData.forEach(s => {
        if (s.el.classList.contains('state-avail')) a++;
        else if (s.el.classList.contains('state-occupied')) o++;
        else r++;
    });
    document.getElementById('cAvail').textContent = a;
    document.getElementById('cOcc').textContent = o;
    document.getElementById('cRes').textContent = r;
}

function showEmployeeInfo(parking, slotNumber) {
    const modal = document.getElementById('employeeModal');
    const content = document.getElementById('modalContent');
    
    document.getElementById('modalSlotNumber').textContent = slotNumber;
    document.getElementById('modalUserName').textContent = parking.user ? parking.user.fullname : 'ไม่ทราบชื่อ';
    document.getElementById('modalUserDept').textContent = parking.user && parking.user.dept_name ? parking.user.dept_name : '-';
    document.getElementById('modalCarRegistration').textContent = parking.car_registration;
    
    const timeIn = new Date(parking.time_in);
    document.getElementById('modalTimeIn').textContent = timeIn.toLocaleString('th-TH');

    const name = parking.user ? parking.user.fullname : 'ไม่ทราบชื่อ';
    let initials = '';
    const words = name.trim().split(' ');
    if (words.length >= 2) initials = words[0].charAt(0) + words[1].charAt(0);
    else initials = name.substring(0, 2);
    
    document.getElementById('modalUserPhoto').innerHTML = `<div class="w-full h-full flex items-center justify-center font-bold text-slate-700 text-sm bg-slate-100">${initials.toUpperCase()}</div>`;

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeModal() {
    const modal = document.getElementById('employeeModal');
    const content = document.getElementById('modalContent');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Zoom & Touch Drag Panning Handlers
let currentMapZoom = 1.0;
function zoomMap(delta) {
    currentMapZoom = Math.min(Math.max(0.6, currentMapZoom + delta), 3.0);
    applyMapZoom();
}

function resetMapZoom() {
    currentMapZoom = 1.0;
    applyMapZoom();
}

function applyMapZoom() {
    const plan = document.getElementById('plan');
    const zoomVal = document.getElementById('map-zoom-val');
    if (plan) {
        plan.style.transform = `scale(${currentMapZoom})`;
    }
    if (zoomVal) {
        zoomVal.textContent = `${Math.round(currentMapZoom * 100)}%`;
    }
}

// Mouse Drag & Touch Pan for Board
document.addEventListener('DOMContentLoaded', function() {
    const board = document.getElementById('board-container');
    if (!board) return;

    let isDown = false;
    let startX, startY, scrollLeft, scrollTop;

    board.addEventListener('mousedown', (e) => {
        if (e.target.closest('.slot') || e.target.closest('.map-text-el') || e.target.closest('.map-icon-el')) return;
        isDown = true;
        startX = e.pageX - board.offsetLeft;
        startY = e.pageY - board.offsetTop;
        scrollLeft = board.scrollLeft;
        scrollTop = board.scrollTop;
    });

    board.addEventListener('mouseleave', () => { isDown = false; });
    board.addEventListener('mouseup', () => { isDown = false; });
    board.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - board.offsetLeft;
        const y = e.pageY - board.offsetTop;
        const walkX = (x - startX) * 1.5;
        const walkY = (y - startY) * 1.5;
        board.scrollLeft = scrollLeft - walkX;
        board.scrollTop = scrollTop - walkY;
    });
});
</script>
@endsection
