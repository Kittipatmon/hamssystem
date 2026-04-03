@php
    $currentStep = 'รอกำเนินการ';
    $steps = [];
    $isHousingRequest = in_array(($item->task_type ?? ''), ['request', 'agreement', 'guest', 'leave']);
    
    if ($type == 'housingTasks' && $isHousingRequest) {
        $steps = [
            ['label' => 'ผู้บังคับบัญชา', 'status' => $item->commander_status],
            ['label' => 'ผจก. HAMS', 'status' => $item->managerhams_status],
            ['label' => 'คณะกรรมการ', 'status' => $item->Committee_status]
        ];
        foreach($steps as $s) {
            if ($s['status'] === 0 || is_null($s['status'])) {
                $currentStep = 'รอ' . $s['label'];
                break;
            }
        }
    } elseif ($type == 'vehicleBookings') {
        $currentStep = $item->status == 'รออนุมัติ' ? 'รอแผนก HAMS อนุมัติ' : $item->status;
    } elseif ($type == 'reservations') {
        $currentStep = $item->status == 'รออนุมัติ' ? 'รอเจ้าหน้าที่ยืนยัน' : $item->status;
    }

    // Prepare JSON data for modal
    $modalData = [
        'type' => $type,
        'id' => $item->id ?? $item->booking_id ?? $item->reservation_id ?? $item->requisitions_id ?? $item->agreement_id ?? $item->guest_id ?? $item->leave_id,
        'code' => '',
        'title' => '',
        'status' => $currentStep,
        'details' => []
    ];

    if ($type == 'requisitions') {
        $modalData['code'] = $item->requisitions_code ?? 'REQ-'.$item->requisitions_id;
        $modalData['title'] = 'คำขอเบิกพัสดุ';
        $modalData['details'] = [
            ['label' => 'รหัสพัสดุ', 'value' => $item->id_requisitions_items ?? '-'],
            ['label' => 'จำนวนที่ขอ', 'value' => ($item->item_quantity ?? 0) . ' รายการ'],
            ['label' => 'มูลค่ารวม', 'value' => '฿'.number_format($item->total_price ?? 0, 2)],
        ];
    } elseif ($type == 'reservations') {
        $modalData['code'] = $item->reservation_code;
        $modalData['title'] = $item->topic;
        $modalData['details'] = [
            ['label' => 'ห้องประชุม', 'value' => $item->room->room_name ?? 'ไม่ระบุ'],
            ['label' => 'วันที่จอง', 'value' => $item->start_time . ' - ' . $item->end_time],
            ['label' => 'จำนวนผู้เข้าร่วม', 'value' => ($item->attendees ?? 0) . ' ท่าน'],
        ];
    } elseif ($type == 'vehicleBookings') {
        $modalData['code'] = $item->booking_code;
        $modalData['title'] = $item->purpose;
        $modalData['details'] = [
            ['label' => 'จุดหมาย', 'value' => $item->destination . ' (' . $item->province . ')'],
            ['label' => 'ช่วงเวลา', 'value' => $item->start_time . ' ถึง ' . $item->end_time],
            ['label' => 'พนักงานขับรถ', 'value' => $item->driver_name ?? 'รอจัดสรร'],
        ];
    } elseif ($type == 'housingTasks') {
        $modalData['code'] = $item->task_type == 'repair' ? $item->repair_code : $item->requests_code ?? $item->agreement_code ?? $item->guest_code ?? $item->leave_code;
        $modalData['title'] = $item->title ?? $item->topic ?? 'รายการงานบ้านพัก';
        $modalData['details'] = [
            ['label' => 'ประเภทรายการ', 'value' => match($item->task_type) {
                'request' => 'คำขอเข้าพักอาศัย',
                'agreement' => 'ข้อตกลงการเข้าพัก',
                'guest' => 'นำญาติเข้าพัก',
                'leave' => 'ขอย้ายออก',
                'repair' => 'แจ้งซ่อมบำรุง',
                default => 'อื่นๆ'
            }],
            ['label' => 'สถานที่', 'value' => $item->room->room_number ?? $item->residence_address ?? $item->site ?? 'บ้านพักพนักงาน'],
        ];
    }
@endphp

<div class="p-6 bg-white dark:bg-zinc-800 rounded-3xl border border-slate-100 dark:border-zinc-700 shadow-sm transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-{{ $theme }}-200 group/card relative overflow-hidden cursor-pointer"
     data-task-json="{{ json_encode($modalData) }}"
     onclick="window.showTaskDetailFromElement(this)">
    <!-- Status Accent Line -->
    <div class="absolute top-0 left-0 w-1.5 h-full bg-{{ $theme }}-500 opacity-60 group-hover/card:opacity-100 transition-opacity"></div>
    
    <div class="flex justify-between items-start mb-5 relative uppercase">
        <div class="space-y-1.5">
            <span class="text-[10px] font-black text-{{ $theme }}-600 dark:text-{{ $theme }}-400 uppercase tracking-[0.2em] block leading-none">
                @if($type == 'requisitions') REQ-{{ $item->requisitions_code ?? $item->requisitions_id }}
                @elseif($type == 'reservations') RES-{{ $item->reservation_code }}
                @elseif($type == 'vehicleBookings') VEH-{{ $item->booking_code }}
                @elseif($type == 'housingTasks') 
                    @if(($item->task_type ?? '') == 'repair') RPR-{{ $item->repair_code }}
                    @elseif(($item->task_type ?? '') == 'request') HSG-REQ-{{ $item->requests_code }}
                    @elseif(($item->task_type ?? '') == 'agreement') HSG-AGR-{{ $item->agreement_code }}
                    @elseif(($item->task_type ?? '') == 'guest') HSG-GST-{{ $item->guest_code }}
                    @elseif(($item->task_type ?? '') == 'leave') HSG-LVE-{{ $item->leave_code }}
                    @endif
                @endif
            </span>
            <h3 class="text-md font-black text-slate-800 dark:text-white uppercase leading-tight line-clamp-1 truncate">
                @if($type == 'requisitions') คำขอเบิกพัสดุ
                @elseif($type == 'reservations') {{ $item->topic }}
                @elseif($type == 'vehicleBookings') {{ $item->purpose }}
                @elseif($type == 'housingTasks') 
                    @if(($item->task_type ?? '') == 'repair') {{ $item->title }}
                    @elseif(($item->task_type ?? '') == 'request') คำขอเข้าพักอาศัย ({{ $item->site }})
                    @elseif(($item->task_type ?? '') == 'agreement') ข้อตกลงการเข้าพักอาศัย
                    @elseif(($item->task_type ?? '') == 'guest') คำขอนำญาติเข้าพัก
                    @elseif(($item->task_type ?? '') == 'leave') แจ้งย้ายออกจากที่พัก
                    @endif
                @endif
            </h3>
        </div>
        <div class="flex flex-col items-end gap-1.5">
            <div class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border {{ $statusType == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : ($statusType == 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-600 border-red-100') }}">
                @if($type == 'requisitions') {{ $item->status_label ?? $item->status }}
                @elseif($type == 'housingTasks' && $isHousingRequest) 
                    {{ $item->Committee_status === 0 || is_null($item->Committee_status) ? 'รออนุมัติ' : 'รอดำเนินการ' }}
                @else {{ $item->status ?? 'เสร็จสิ้น' }}
                @endif
            </div>
            <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-100">{{ $currentStep }}</span>
        </div>
    </div>

    <!-- Structured Content Area -->
    <div class="space-y-4">
        @if($type == 'reservations')
            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-900/50 rounded-2xl border border-slate-100 dark:border-zinc-800">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                    <i class="fa-solid fa-door-open text-xs"></i>
                </div>
                <div>
                   <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">ห้องประชุม / สถานที่</span>
                   <span class="text-[11px] font-black text-slate-700 dark:text-zinc-200 uppercase">{{ $item->room->room_name ?? 'ไม่ได้ระบุ' }}</span>
                </div>
            </div>
        @elseif($type == 'vehicleBookings')
            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-900/50 rounded-2xl border border-slate-100 dark:border-zinc-800">
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600">
                    <i class="fa-solid fa-location-dot text-xs"></i>
                </div>
                <div>
                   <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">จุดหมายปลายทาง</span>
                   <span class="text-[11px] font-black text-slate-700 dark:text-zinc-200 uppercase">{{ $item->destination }} ({{ $item->province }})</span>
                </div>
            </div>
        @elseif($type == 'housingTasks')
            @php
                $hInfo = [
                    'request' => ['icon' => 'fa-file-circle-plus', 'color' => 'red', 'label' => 'ประเภทที่พักที่ขอ', 'val' => $item->current_house_type ?? 'หอพักพนักงาน'],
                    'agreement' => ['icon' => 'fa-file-signature', 'color' => 'blue', 'label' => 'หน่วยงาน/สังกัด', 'val' => $item->department ?? 'ไม่ระบุ'],
                    'guest' => ['icon' => 'fa-people-group', 'color' => 'purple', 'label' => 'จำนวนผู้พักอาศัยร่วม', 'val' => ($item->number_of_residents ?? 0) . ' ท่าน'],
                    'leave' => ['icon' => 'fa-door-open', 'color' => 'orange', 'label' => 'ที่อยู่บ้านพักเดิม', 'val' => $item->residence_address ?? 'ไม่ระบุ'],
                    'repair' => ['icon' => 'fa-screwdriver-wrench', 'color' => 'emerald', 'label' => 'จุดที่แจ้งซ่อม', 'val' => $item->room->room_number ?? 'บ้านพักพนักงาน'],
                ];
                $conf = $hInfo[$item->task_type ?? 'repair'] ?? $hInfo['repair'];
            @endphp
            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-900/50 rounded-2xl border border-slate-100 dark:border-zinc-800">
                <div class="w-8 h-8 rounded-lg bg-{{ $conf['color'] }}-100 dark:bg-{{ $conf['color'] }}-900/30 flex items-center justify-center text-{{ $conf['color'] }}-600">
                    <i class="fa-solid {{ $conf['icon'] }} text-xs"></i>
                </div>
                <div>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">{{ $conf['label'] }}</span>
                    <span class="text-[11px] font-black text-slate-700 dark:text-zinc-200 uppercase">{{ $conf['val'] }}</span>
                </div>
            </div>
        @elseif($type == 'requisitions')
            <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-900/50 rounded-2xl border border-slate-100 dark:border-zinc-800">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-receipt text-xs"></i>
                </div>
                <div>
                   <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block leading-none mb-1">มูลค่ารวม</span>
                   <span class="text-[11px] font-black text-slate-700 dark:text-zinc-200 uppercase">฿{{ number_format($item->total_price ?? 0, 2) }}</span>
                </div>
            </div>
        @endif

        @if($steps)
            <div class="flex items-center justify-between gap-2 px-1">
                @foreach($steps as $index => $step)
                    <div class="flex-1 flex flex-col items-center gap-1.5">
                        <div class="w-full h-1 relative bg-slate-100 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="absolute inset-0 bg-{{ $step['status'] == 1 ? 'emerald-500' : ($step['status'] == 2 ? 'red-500' : ($index == 0 || $steps[$index-1]['status'] == 1 ? 'amber-400' : 'slate-200')) }}"></div>
                        </div>
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">{{ $step['label'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center justify-between pt-1 px-1">
             <div class="flex items-center gap-3">
                 <div class="flex flex-col">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">วันที่ส่งคำขอ</span>
                    <span class="text-[10px] font-black text-slate-500 uppercase">
                        {{ $item->created_at->format('d M y / H:i น.') }}
                    </span>
                 </div>
             </div>
             @if($type == 'reservations' || $type == 'vehicleBookings')
                <div class="text-right">
                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5 whitespace-nowrap">ช่วงเวลาที่จอง</span>
                    <span class="text-[10px] font-black text-slate-900 dark:text-white uppercase px-2 py-1 bg-white dark:bg-zinc-800 border border-slate-100 dark:border-zinc-700 rounded-lg shadow-sm whitespace-nowrap">
                        {{ $item->start_time ?? '-' }} - {{ $item->end_time ?? '-' }}
                    </span>
                </div>
             @endif
        </div>
    </div>
</div>

