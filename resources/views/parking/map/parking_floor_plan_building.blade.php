@extends('layouts.parking.app')

@section('content')

<style>
  :root {
    --ink: #1c3550;
    --ink-dim: #5c7590;
    --line: #3b4a58;
    --blue: #2f8fd4;
    --blue-dark: #1c6ea8;
    --yellow: #f4d03f;
    --gray: #c9ced3;
    --gray-dark: #9aa2aa;
    --green: #6fbf6a;
    --lightblue: #bfe3f7;
    --red: #e03b3b;
    --paper: #ffffff;
    --bg: #eef2f5;
  }
  
  .wrap {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 20px;
  }

  header {
    margin-bottom: 18px;
  }
  
  h1 {
    font-size: 1.5rem;
    margin: 0 0 4px;
    font-weight: 700;
  }
  
  header .sub {
    color: var(--ink-dim);
    font-size: .85rem;
    font-family: 'IBM Plex Mono', monospace;
  }

  .board {
    position: relative;
    width: 100%;
    min-width: 1100px;
    aspect-ratio: 1280/660;
    background: var(--paper);
    overflow: hidden;
  }
  
  .pct {
    position: absolute;
  }

  .bay {
    background: var(--paper);
    border: 1px solid var(--gray-dark);
  }
  
  .triple-bay {
    display: flex;
    align-items: flex-end;
    gap: 2%;
    height: 100%;
    padding: 20% 4% 5% 4%;
  }
  
  .car-slot {
    flex: 1;
    height: 100%;
    border: 2px solid var(--green);
    border-radius: 5px;
    background: rgba(111,191,106,.10);
    cursor: pointer;
    transition: background .15s ease, border-color .15s ease;
  }

  /* Bay-12 quad grid: 2x2 */
  .quad-grid .car-slot {
    flex: none;
    height: 100%;
    width: 100%;
    min-height: 0;
  }
  
  .car-slot:hover {
    filter: brightness(1.05);
  }
  
  .car-slot.occupied {
    border-color: var(--red);
    background: rgba(224,59,59,.16);
  }
  
  .map-stats {
    display: flex !important;
    flex-direction: row !important;
    gap: 12px !important;
    flex-wrap: wrap !important;
    margin-bottom: 16px;
    align-items: center;
  }
  
  .map-stat {
    background: #fff;
    border: 1px solid #d6dde3;
    border-radius: 10px;
    padding: 8px 14px;
    min-width: 120px;
    flex: 0 1 auto;
  }
  
  .map-stat .n {
    font-size: 1.25rem;
    font-weight: 700;
    font-family: 'IBM Plex Mono', monospace;
  }
  
  .map-stat .l {
    font-size: .7rem;
    color: var(--ink-dim);
  }
  
  .map-stat.avail .n {
    color: var(--green);
  }
  
  .map-stat.occ .n {
    color: var(--red);
  }
  
  .bay-inner-dash {
    position: absolute;
    top: 14%;
    bottom: 10%;
    left: 50%;
    border-left: 2px dashed #b9c2ca;
  }
  
  .badge {
    position: absolute;
    top: 6px;
    left: 6px;
    background: var(--blue);
    color: #fff;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 700;
    font-size: 13px;
    width: 22px;
    height: 22px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0,0,0,.25);
    z-index: 5;
  }
  
  .room {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: .8rem;
    font-weight: 600;
    color: var(--ink);
    border: 1px solid var(--ink);
    padding: 4px;
  }
  
  .room.gray {
    background: var(--gray);
  }
  
  .room.yellow {
    background: var(--yellow);
  }
  
  .room.green {
    background: var(--green);
    color: #fff;
  }
  
  .room.blue {
    background: var(--blue);
    color: #fff;
  }
  
  .room.lightblue {
    background: var(--lightblue);
  }
  
  .room.exec-label {
    align-items: flex-end;
    padding-bottom: 6px;
    font-weight: 600;
    color: var(--ink);
    background: var(--lightblue);
  }
  
  .vtext {
    writing-mode: vertical-rl;
    text-orientation: mixed;
  }
  
  .title-tag {
    background: var(--blue);
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    padding: 6px 16px;
    border-radius: 0 0 0 8px;
  }
  
  .stripe-red {
    background: var(--red);
  }
  
  .stripe-green {
    background: #4caf50;
  }
  
  .stair-hatch {
    background-image: repeating-linear-gradient(0deg, #d7dbdf 0 4px, #eceff1 4px 8px);
    border: 1px solid var(--ink);
  }
  
  .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--red);
  }
  
  .car {
    position: absolute;
  }
  
  .car svg {
    width: 100%;
    height: 100%;
    display: block;
  }

  .legend {
    margin-top: 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 14px 26px;
    font-size: .8rem;
    color: var(--ink-dim);
  }
  
  .legend .row {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .sw {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    border: 1px solid var(--ink-dim);
  }
  
  footer {
    margin-top: 20px;
    font-size: .75rem;
    color: var(--ink-dim);
    font-family: 'IBM Plex Mono', monospace;
  }
</style>

@if(request()->query('select_mode'))
<style>
  nav, footer, .wrap > div:first-child, header, .map-stats, .legend {
    display: none !important;
  }
  .wrap {
    max-width: 100% !important;
    padding: 0 !important;
  }
  body, .bg-slate-50, .board {
    background: #ffffff !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
  }
  .pt-24 {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
  }
</style>
@endif

<style>
  .map-scroll-container::-webkit-scrollbar {
    height: 8px;
    display: block !important;
  }
  .map-scroll-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
  }
  .map-scroll-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }
  .map-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
  .map-scroll-container {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
  }
</style>

<div class="pt-24 pb-12 bg-slate-50 min-h-screen">
  <div class="wrap">
    <div style="margin-bottom: 16px; margin-top: -50px">
      <a href="{{ route('parking.dashboard') }}" style="text-decoration:none; color:#1c3550; font-size:0.9rem; font-weight:500; display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border:1px solid #d6dde3; border-radius:8px; background:#fff; box-shadow:0 1px 3px rgba(28,53,80,.06); transition:all .15s ease;" onmouseover="this.style.borderColor='#2f8fd4'" onmouseout="this.style.borderColor='#d6dde3'">
        &larr; กลับหน้าหลัก
      </a>
    </div>

    <header>
      <h1>แผนผังพื้นที่จอดรถ</h1>
      <div class="sub">Facility parking layout · เรียงหมายเลขช่องจอด 1–19 ตามผังต้นฉบับ · คลิกช่องจอดที่ว่างเพื่อระบุผู้จอด</div>
    </header>

    <div class="map-stats">
      <div class="map-stat"><div class="n" id="cTotal">0</div><div class="l">ช่องย่อยทั้งหมด</div></div>
      <div class="map-stat avail"><div class="n" id="cAvail">0</div><div class="l">ว่าง</div></div>
      <div class="map-stat occ"><div class="n" id="cOcc">0</div><div class="l">ไม่ว่าง</div></div>
    </div>

    <!-- Render dynamic slots helper -->
    @php
        $renderSlot = function($bay, $index) use ($slots) {
            $slotId = "B{$bay}_{$index}";
            $slot = $slots->get($slotId);
            $hasActiveEmpRes = $slot && $slot->employeeReservations && $slot->employeeReservations->count() > 0;
            $hasActiveVisRes = $slot && $slot->visitorReservations && $slot->visitorReservations->count() > 0;
            $hasActiveEmp = $slot && $slot->employeeParkings && $slot->employeeParkings->count() > 0;
            
            $isOccupied = $slot && ($slot->status === 'occupied' || $slot->status === 'reserved' || $hasActiveEmp || $hasActiveVisRes || $hasActiveEmpRes);
            $occupiedClass = $isOccupied ? 'occupied' : '';
            
            $empName = '';
            $empCode = '';
            $carReg = '';
            $deptName = '';
            $timeIn = '';
            
            if ($isOccupied) {
                if ($hasActiveEmp) {
                    $p = $slot->employeeParkings->first();
                    $empName = $p->user ? $p->user->fullname : 'ไม่ทราบชื่อ';
                    $empCode = $p->user ? $p->user->emp_code : '-';
                    $carReg = $p->car_registration;
                    $deptName = $p->user ? $p->user->dept_name : '-';
                    $timeIn = $p->time_in ? $p->time_in->format('d/m/Y H:i') : '-';
                } elseif ($hasActiveVisRes) {
                    $v = $slot->visitorReservations->first();
                    $empName = $v->guest_name . " (ผู้ติดต่อ)";
                    $empCode = $v->company ?? 'บุคคลภายนอก';
                    $carReg = $v->car_registration;
                    $deptName = "📞 " . $v->phone;
                    $timeIn = \Carbon\Carbon::parse($v->checkin_datetime)->format('d/m/Y H:i');
                } elseif ($hasActiveEmpRes) {
                    $er = $slot->employeeReservations->first();
                    $empName = $er->user ? $er->user->fullname : 'ไม่ทราบชื่อ';
                    $empCode = $er->user ? $er->user->emp_code : '-';
                    $carReg = $er->car_registration;
                    $deptName = $er->department ? $er->department->name : '-';
                    $timeIn = \Carbon\Carbon::parse($er->checkin_datetime)->format('d/m/Y H:i');
                    
                    if ($er->manager_approval !== 'approved' || $er->hams_status !== 'acknowledged') {
                        $empName .= " (รออนุมัติ)";
                    } else {
                        $empName .= " (จอดแล้ว)";
                    }
                }
            }
            
            $tooltip = $isOccupied ? "ช่องจอด {$bay} คันที่ {$index}: จอดโดย {$empName} ({$empCode})" : "ช่องจอด {$bay} คันที่ {$index} (ว่าง / คลิกเพื่อระบุผู้จอด)";
            
            return "
            <div class='car-slot $occupiedClass' 
                 data-slot-id='$slotId' 
                 data-bay='$bay' 
                 data-index='$index' 
                 data-occupied='".($isOccupied ? 'true' : 'false')."'
                 data-emp-name='$empName'
                 data-emp-code='$empCode'
                 data-car-reg='$carReg'
                 data-dept-name='$deptName'
                 data-time-in='$timeIn'
                 title='$tooltip'></div>
            ";
        };
    @endphp

    @if(request()->query('select_mode'))
    <div class="block md:hidden bg-amber-500 text-white text-center py-2 px-4 text-xs font-bold sticky top-0 z-[100] flex items-center justify-center gap-2 shadow-sm rounded-t-lg">
        <i class="fa-solid fa-arrows-left-right animate-pulse"></i>
        <span>ปัดนิ้ว ซ้าย-ขวา เพื่อดูแผนผังลานจอดรถทั้งหมด</span>
    </div>
    @endif

    <div class="map-scroll-container" style="position: relative; width: 100%; overflow-x: auto; border: 2px solid var(--ink); border-radius: 6px; box-shadow: 0 2px 10px rgba(28,53,80,.08); margin-bottom: 20px; touch-action: pan-x pan-y; -webkit-overflow-scrolling: touch;">
      <div class="map-zoom-bar" style="position: absolute; top: 12px; right: 12px; z-index: 40; display: flex; align-items: center; gap: 4px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); border: 1px solid #cbd5e1; border-radius: 12px; padding: 4px 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
        <button type="button" onclick="zoomBuildingMap(0.15)" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; background:#fff; color:#1e293b; border:1px solid #e2e8f0; border-radius:8px; font-weight:700; font-size:0.75rem; cursor:pointer;" title="ขยาย (Zoom In)">
          <i class="fa-solid fa-plus text-xs"></i> <span class="hidden sm:inline">ขยาย</span>
        </button>
        <button type="button" onclick="zoomBuildingMap(-0.15)" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; background:#fff; color:#1e293b; border:1px solid #e2e8f0; border-radius:8px; font-weight:700; font-size:0.75rem; cursor:pointer;" title="ย่อ (Zoom Out)">
          <i class="fa-solid fa-minus text-xs"></i> <span class="hidden sm:inline">ย่อ</span>
        </button>
        <button type="button" onclick="resetBuildingMapZoom()" style="display:inline-flex; align-items:center; gap:4px; padding:6px 10px; background:#fff; color:#1e293b; border:1px solid #e2e8f0; border-radius:8px; font-weight:700; font-size:0.75rem; cursor:pointer;" title="รีเซ็ต (Reset)">
          <i class="fa-solid fa-rotate-left text-xs"></i> <span id="bld-zoom-val">100%</span>
        </button>
      </div>

      <div class="board" id="board" style="transform-origin: 0 0; transition: transform 0.15s ease-out;">
      <!-- title tag, top right -->
      <div class="pct" style="right:0;top:0; z-index: 10;"><div class="title-tag">แผงผังพื้นที่จอดรถ</div></div>

      <!-- ===== TOP ROW: bays 1-7 ===== -->
      <div class="pct bay" style="left:4%; top:6%; width:9.6%; height:23%;"><div class="badge">1</div>
        <div class="triple-bay" data-bay="1">
          {!! $renderSlot(1, 1) !!}{!! $renderSlot(1, 2) !!}{!! $renderSlot(1, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:13.6%; top:6%; width:9.6%; height:23%;"><div class="badge">2</div>
        <div class="triple-bay" data-bay="2">
          {!! $renderSlot(2, 1) !!}{!! $renderSlot(2, 2) !!}{!! $renderSlot(2, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:23.2%; top:6%; width:9.6%; height:23%;"><div class="badge">3</div>
        <div class="triple-bay" data-bay="3">
          {!! $renderSlot(3, 1) !!}{!! $renderSlot(3, 2) !!}{!! $renderSlot(3, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:32.8%; top:6%; width:9.6%; height:23%;"><div class="badge">4</div>
        <div class="triple-bay" data-bay="4">
          {!! $renderSlot(4, 1) !!}{!! $renderSlot(4, 2) !!}{!! $renderSlot(4, 3) !!}
        </div>
      </div>

      <div class="pct room lightblue exec-label" style="left:42.4%; top:6%; width:19.2%; height:23%; padding:0;">
        <div class="badge">5</div>
        <div class="badge" style="left:auto; right:6px;">6</div>
        <div class="pct" style="left:50%; top:0; width:0; height:100%; border-left:2px dashed rgba(28,53,80,.35); pointer-events:none;"></div>
        
        <!-- Bay 5 Slots -->
        <div class="pct triple-bay" data-bay="5" style="left:0; top:0; width:50%; height:100%; background:transparent; padding:20% 8% 18% 8%;">
          {!! $renderSlot(5, 1) !!}{!! $renderSlot(5, 2) !!}{!! $renderSlot(5, 3) !!}
        </div>
        
        <!-- Bay 6 Slots -->
        <div class="pct triple-bay" data-bay="6" style="left:50%; top:0; width:50%; height:100%; background:transparent; padding:20% 8% 18% 8%;">
          {!! $renderSlot(6, 1) !!}{!! $renderSlot(6, 2) !!}{!! $renderSlot(6, 3) !!}
        </div>

        <span class="pct" style="bottom:6px; left:0; width:100%; text-align:center; pointer-events:none;">ช่องจอดสำหรับผู้บริหาร</span>
      </div>

      <!-- Bay 7 - top row far right -->
      <div class="pct bay" style="left:61.6%; top:6%; width:9.6%; height:23%;"><div class="badge">7</div>
        <div class="triple-bay" data-bay="7">
          {!! $renderSlot(7, 1) !!}{!! $renderSlot(7, 2) !!}{!! $renderSlot(7, 3) !!}
        </div>
      </div>

      <!-- top-right service block: fire escape stairs -->
      <div class="pct room yellow" style="left:75%; top:6%; width:10%; height:9%;">บันไดหนีไฟ</div>
      <div class="pct stripe-green" style="left:75%; top:15%; width:10%; height:3%;"></div>
      <div class="pct stripe-red" style="left:75%; top:18%; width:3%; height:2%;"></div>
      <div class="pct stair-hatch" style="left:87%; top:6%; width:4%; height:20%;"></div>
      <div class="pct vtext" style="left:87.2%; top:7%; width:3.6%; height:18%; font-size:11px; display:flex; align-items:center; justify-content:center; color:var(--ink-dim);">บันไดทางขึ้น</div>

      <!-- dashed aisle dividers -->
      <div class="pct" style="left:4%; top:32%; width:65%; border-top:2px dashed #b9c2ca;"></div>
      <div class="pct" style="left:4%; top:38%; width:35%; border-top:2px dashed #b9c2ca;"></div>

      <!-- ===== MIDDLE ROW: bays 8,9,10 + lift + electrical room ===== -->
      <div class="pct" style="left:4%; top:41%; width:9.6%; height:23%;">
        <!-- Yellow Room -->
        <div class="pct room yellow" style="left:0; top:0; width:35%; height:100%; display:flex; align-items:center; justify-content:center; padding: 0;">
          <div class="badge" style="left: 6px; top: 6px;">8</div>
          <span class="vtext" style="font-size:.78rem; font-weight: 600; color: var(--ink);">บันไดหนีไฟ</span>
        </div>
        <!-- Red Stripe -->
        <div class="pct stripe-red" style="left:38%; top:15%; width:6%; height:70%; border-radius: 2px;"></div>
        <!-- Dashed Line -->
        <div class="pct" style="left:46%; top:0; width:0; height:100%; border-left: 2px dashed #b9c2ca;"></div>
        <!-- 2 Parking Slots -->
        <div class="pct" style="left:48%; top:0; width:52%; height:100%; display:flex; align-items:flex-end; gap:4%; padding:20% 4% 5% 4%;">
          {!! $renderSlot(8, 1) !!}{!! $renderSlot(8, 2) !!}
        </div>
      </div>
      <div class="pct bay" style="left:13.6%; top:41%; width:9.6%; height:23%;"><div class="badge">9</div>
        <div class="triple-bay" data-bay="9">
          {!! $renderSlot(9, 1) !!}{!! $renderSlot(9, 2) !!}{!! $renderSlot(9, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:23.2%; top:41%; width:9.6%; height:23%;"><div class="badge">10</div>
        <div class="triple-bay" data-bay="10">
          {!! $renderSlot(10, 1) !!}{!! $renderSlot(10, 2) !!}{!! $renderSlot(10, 3) !!}
        </div>
      </div>

      <div class="pct room gray" style="left:32.8%; top:41%; width:4.7%; height:23%;">ลิฟต์</div>

      <div class="pct room yellow" style="left:37.5%; top:41%; width:12.5%; height:14%;">
        <div class="pct stripe-red" style="left:0; top:0; width:100%; height:8%;"></div>
        ห้องไฟฟ้า
      </div>
      <div class="pct bay" style="left:37.5%; top:55%; width:12.5%; height:13%; background:var(--paper);">
        <div class="badge">14</div>
        <div class="triple-bay" data-bay="14">
          {!! $renderSlot(14, 1) !!}{!! $renderSlot(14, 2) !!}{!! $renderSlot(14, 3) !!}
        </div>
        <div style="position:absolute; bottom:4px; left:50%; transform:translateX(-50%); font-size:.65rem; color:#fff; background:var(--ink); padding:0 4px; border-radius:2px; pointer-events:none;">LG</div>
      </div>

      <!-- Bay 11 & 13, Restroom, and Green Area layout -->
      <div class="pct bay" style="left:65.6%; top:41%; width:9.6%; height:13%;"><div class="badge">11</div>
        <div class="triple-bay" data-bay="11">
          {!! $renderSlot(11, 1) !!}{!! $renderSlot(11, 2) !!}{!! $renderSlot(11, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:65.6%; top:55%; width:9.6%; height:13%;"><div class="badge">13</div>
        <div class="triple-bay" data-bay="13">
          {!! $renderSlot(13, 1) !!}{!! $renderSlot(13, 2) !!}{!! $renderSlot(13, 3) !!}
        </div>
      </div>

      <!-- Column 2 (Middle): Restroom (top) and Green Area (bottom) -->
      <div class="pct room gray" style="left:75.2%; top:41%; width:9.6%; height:13%; font-size: 0.8rem;">ห้องน้ำ</div>
      <div class="pct" style="left:75.2%; top:54%; width:9.6%; height:14%; background:var(--green); border-radius:0 0 0 100%;"></div>

      <!-- Bay 12 - 4 slots (2x2 grid) with red border -->
      <div class="pct" style="left:71.5%; top:27%; width:9%; height:14%; border:1px solid #b4b4b4ff; border-radius:6px; background:var(--paper);">
        <div class="badge">12</div>
        <div class="quad-grid" style="position:absolute; inset:8px 5px 5px 5px; display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr; gap:5px;">
          {!! $renderSlot(12, 1) !!}
          {!! $renderSlot(12, 2) !!}
          {!! $renderSlot(12, 3) !!}
          {!! $renderSlot(12, 4) !!}
        </div>
      </div>
      <div class="pct room yellow vtext" style="left:80.5%; top:27%; width:3.2%; height:14%; font-size:.7rem; display:flex; align-items:center; justify-content:center;">
        Fire Pump
      </div>

      <!-- Column 3 (Right): Ramp / triangular corner -->
      <div class="pct" style="left:84.8%; top:6%; width:11.2%; height:62%; background:var(--gray); clip-path:polygon(0 0, 100% 0, 0 100%);"></div>

      <!-- ===== LG DEPARTMENT big block ===== -->
      <div class="pct room gray" style="left:4%; top:64%; width:29.2%; height:29%; font-size:1.1rem; font-weight:700;">แผนก LG</div>

      <!-- ===== BOTTOM ROW: bays 15-19 ===== -->
      <div class="pct bay" style="left:37.5%; top:80%; width:9.6%; height:13%;">
        <div class="badge">15</div>
        <div class="triple-bay" data-bay="15">
          {!! $renderSlot(15, 1) !!}{!! $renderSlot(15, 2) !!}{!! $renderSlot(15, 3) !!}
        </div>
        <div style="position:absolute; bottom:2px; left:50%; transform:translateX(-50%); font-size:.6rem; color:#fff; background:var(--ink); padding:0 4px; border-radius:2px; pointer-events:none;">LG ×3</div>
      </div>
      <div class="pct bay" style="left:47.1%; top:80%; width:9.6%; height:13%;">
        <div class="badge">16</div>
        <div class="triple-bay" data-bay="16">
          {!! $renderSlot(16, 1) !!}{!! $renderSlot(16, 2) !!}{!! $renderSlot(16, 3) !!}
        </div>
        <div style="position:absolute; bottom:2px; right:6px; font-size:.6rem; color:#fff; background:var(--ink); padding:0 4px; border-radius:2px; pointer-events:none;">LG</div>
      </div>
      <div class="pct bay" style="left:56.7%; top:80%; width:9.6%; height:13%;"><div class="badge">17</div>
        <div class="triple-bay" data-bay="17">
          {!! $renderSlot(17, 1) !!}{!! $renderSlot(17, 2) !!}{!! $renderSlot(17, 3) !!}
        </div>
      </div>
      <div class="pct bay" style="left:66.3%; top:80%; width:9.6%; height:13%;"><div class="badge">18</div>
        <div class="triple-bay" data-bay="18">
          {!! $renderSlot(18, 1) !!}{!! $renderSlot(18, 2) !!}{!! $renderSlot(18, 3) !!}
        </div>
      </div>

      <div class="pct room blue" style="left:75.9%; top:80%; width:14%; height:13%; border-radius:0 0 0 0;">
        <div class="badge">19</div>
        ช่องจอดผู้มาติดต่อ
      </div>
      <div class="pct" style="left:89.9%; top:80%; width:5.5%; height:13%; background:var(--green); border-radius:0 0 60px 0;"></div>
      <div class="pct" style="left:91.5%; top:82%; display:flex; gap:3px;">
        <span class="dot"></span><span class="dot"></span><span class="dot"></span>
      </div>
      <div class="pct room" style="left:92%; top:88%; width:6%; height:5%; background:#fff; font-size:.7rem;">รปภ.</div>

      <!-- outer boundary -->
      <div class="pct" style="left:0; top:0; right:0; bottom:0; border:2px solid var(--ink); pointer-events:none;"></div>
      </div>
    </div>

    <div class="legend">
      <div class="row"><span class="sw" style="background:rgba(111,191,106,.15); border-color:var(--green);"></span>ช่องจอดว่าง (คลิกเพื่อเลือกลงทะเบียนหรือระบุผู้จอด)</div>
      <div class="row"><span class="sw" style="background:rgba(224,59,59,.2); border-color:var(--red);"></span>ช่องมีรถจอด/ไม่ว่าง (คลิกเพื่อดูรายละเอียดพนักงานผู้จอด)</div>
      <div class="row"><span class="sw" style="background:var(--lightblue);"></span>ช่องจอดผู้บริหาร</div>
      <div class="row"><span class="sw" style="background:var(--blue);"></span>ช่องจอดผู้มาติดต่อ</div>
      <div class="row"><span class="sw" style="background:var(--yellow);"></span>ห้องบันไดหนีไฟ / ไฟฟ้า / Fire Pump</div>
      <div class="row"><span class="sw" style="background:var(--gray);"></span>ห้องน้ำ / ลิฟต์ / แผนก LG</div>
      <div class="row"><span class="sw" style="background:var(--green);"></span>พื้นที่สีเขียว / จุดรักษาความปลอดภัย</div>
    </div>

    <footer>ผังนี้จัดเรียงหมายเลขช่องจอด 1–19 และตำแหน่งห้อง/สิ่งอำนวยความสะดวกตามแบบต้นฉบับที่แนบมา</footer>
  </div>
</div>

<!-- Modal for interactive actions -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300" id="bookingModal">
  <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95" id="bookingModalContent">
    <div class="h-2 w-full bg-[#b81515]"></div>
    <div class="p-6">
      <div class="flex justify-between items-start mb-6">
        <h3 class="text-2xl font-black text-slate-800 flex items-center gap-2">
          <i class="fa-solid fa-square-parking text-[#b81515]"></i> จัดการช่องจอด <span id="modalBayInfo"></span>
        </h3>
        <button onclick="closeBookingModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
          <i class="fa-solid fa-xmark text-lg"></i>
        </button>
      </div>

      <!-- Info Area (If Occupied) -->
      <div id="occupiedInfo" class="hidden">
        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-6">
          <div id="modalUserPhoto" class="w-16 h-16 rounded-full overflow-hidden shadow-inner bg-slate-200 text-slate-400 flex items-center justify-center text-xl border border-slate-200">
            <i class="fa-solid fa-user"></i>
          </div>
          <div>
            <div class="text-lg font-bold text-slate-800" id="modalUserName"></div>
            <div class="text-sm text-slate-500 font-semibold" id="modalUserDept"></div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-slate-500 font-bold text-sm">เลขทะเบียนรถ</span>
            <span class="px-3 py-1 bg-slate-100 text-slate-800 rounded font-bold border border-slate-200" id="modalCarRegistration"></span>
          </div>
          <div class="flex justify-between items-center py-2 border-b border-slate-100">
            <span class="text-slate-500 font-bold text-sm">เวลาที่เข้าจอด</span>
            <span class="text-slate-800 font-bold" id="modalTimeIn"></span>
          </div>
        </div>
        
        <div class="mt-8 flex justify-end gap-3">
          <button onclick="closeBookingModal()" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all text-sm">
            ปิด
          </button>
        </div>
      </div>

      <!-- Options Area (If Empty) -->
      <div id="emptyOptions" class="hidden">
        <p class="text-slate-500 font-semibold mb-6 text-sm">ช่องจอดนี้ยังว่างอยู่ คุณต้องการลงทะเบียนจอดรถในช่องจอดนี้อย่างไร?</p>
        <div class="flex flex-col gap-3">
          <a href="#" id="btnEmpRegister" class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl text-center shadow-lg transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-tie"></i> จองที่จอดรถพนักงานชั่วคราว
          </a>
          <a href="#" id="btnVisitorRegister" class="w-full py-3 px-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-2xl border border-red-200 transition-all flex flex-col items-center justify-center gap-1">
            <div class="font-bold flex items-center justify-center gap-2">
              <i class="fa-solid fa-user-clock"></i> จองที่จอดรถให้แขก
            </div>
            <div class="text-[11px] font-medium text-red-500 text-center leading-tight">
              (เข้าไปจะให้กรอกข้อมูล และกดยืนยัน และจะไปยังหน้าติดตามอนุมัติคำขอ) เพื่อรอ hams อนุมัติ
            </div>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function updateStats(){
  const slots = document.querySelectorAll('.car-slot');
  let occ = 0;
  slots.forEach(s => { if(s.classList.contains('occupied')) occ++; });
  document.getElementById('cTotal').textContent = slots.length;
  document.getElementById('cOcc').textContent = occ;
  document.getElementById('cAvail').textContent = slots.length - occ;
}

const bookingModal = document.getElementById('bookingModal');
const bookingModalContent = document.getElementById('bookingModalContent');

document.querySelectorAll('.car-slot').forEach(slot=>{
  slot.addEventListener('click', () => {
    const isOccupied = slot.getAttribute('data-occupied') === 'true';
    const slotId = slot.getAttribute('data-slot-id');
    const bay = slot.getAttribute('data-bay');
    const index = slot.getAttribute('data-index');
    const isParkingAdmin = @json($isParkingAdmin ?? false);

    // Check if select_mode is active
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('select_mode')) {
      if (isOccupied) {
        Swal.fire({
          icon: 'error',
          title: 'ไม่สามารถเลือกได้',
          text: `ช่องจอด ${slotId} ไม่ว่างหรือถูกสงวนไว้แล้ว`,
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#ef4444'
        });
        return;
      }
      // Post message to parent window
      window.parent.postMessage({ type: 'slot_selected', slot: slotId }, '*');
      return;
    }

    if (isOccupied && !isParkingAdmin) {
        Swal.fire({
          icon: 'warning',
          title: 'ไม่สามารถดูข้อมูลได้',
          text: 'สงวนสิทธิ์การดูข้อมูลผู้จอดสำหรับผู้ดูแลระบบเท่านั้น',
          confirmButtonText: 'ตกลง',
          confirmButtonColor: '#ef4444'
        });
        return;
    }

    document.getElementById('modalBayInfo').textContent = `${bay} คันที่ ${index}`;

    if (isOccupied) {
      const name = slot.getAttribute('data-emp-name') || 'ไม่ทราบชื่อ';
      document.getElementById('modalUserName').textContent = name;
      document.getElementById('modalUserDept').textContent = slot.getAttribute('data-dept-name') || '-';
      document.getElementById('modalCarRegistration').textContent = slot.getAttribute('data-car-reg') || '-';
      document.getElementById('modalTimeIn').textContent = slot.getAttribute('data-time-in') || '-';

      let initials = '';
      const words = name.trim().split(' ');
      if (words.length >= 2) {
        initials = words[0].charAt(0) + words[1].charAt(0);
      } else {
        initials = name.substring(0, 2);
      }
      initials = initials.toUpperCase();
      document.getElementById('modalUserPhoto').innerHTML = `<div class="w-full h-full flex items-center justify-center font-bold text-slate-700 text-sm bg-slate-100">${initials}</div>`;

      document.getElementById('occupiedInfo').classList.remove('hidden');
      document.getElementById('emptyOptions').classList.add('hidden');
    } else {
      // Set link routes
      document.getElementById('btnEmpRegister').href = `{{ route('parking.employee_reservations.create') }}?slot=${slotId}`;
      document.getElementById('btnVisitorRegister').href = `{{ route('parking.visitors.create') }}?slot=${slotId}`;

      document.getElementById('occupiedInfo').classList.add('hidden');
      document.getElementById('emptyOptions').classList.remove('hidden');
    }

    // Open Modal
    bookingModal.classList.remove('opacity-0', 'pointer-events-none');
    bookingModalContent.classList.remove('scale-95');
  });
});

function closeBookingModal(){
  bookingModal.classList.add('opacity-0', 'pointer-events-none');
  bookingModalContent.classList.add('scale-95');
}

// Close on outside click
bookingModal.addEventListener('click', (e) => {
  if (e.target === bookingModal) {
    closeBookingModal();
  }
});

updateStats();

// Zoom Building Map JS Handlers
let currentBldZoom = 1.0;
function zoomBuildingMap(delta) {
    currentBldZoom = Math.min(Math.max(0.6, currentBldZoom + delta), 2.5);
    applyBldZoom();
}
function resetBuildingMapZoom() {
    currentBldZoom = 1.0;
    applyBldZoom();
}
function applyBldZoom() {
    const board = document.getElementById('board');
    const zoomVal = document.getElementById('bld-zoom-val');
    if (board) {
        board.style.transform = `scale(${currentBldZoom})`;
    }
    if (zoomVal) {
        zoomVal.textContent = `${Math.round(currentBldZoom * 100)}%`;
    }
}
</script>
@endsection