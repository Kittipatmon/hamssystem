<?php

namespace App\Http\Controllers\Parking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\parking\ParkingSlot;
use App\Models\parking\ParkingZone;
use App\Models\parking\ParkingMapElement;

class ParkingMapController extends Controller
{
    private function initializeDefaultCoordinates()
    {
        // ── Zone 1: ลานจอดรถสำนักงานใหญ่ (ภายนอก) ──
        $zone1 = ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        if (!$zone1) {
            $zone1 = ParkingZone::create([
                'building' => 'ลานจอดรถหลัก',
                'floor' => 'กลางแจ้ง',
                'zone' => 'A',
                'total_slots' => 74
            ]);
        }

        $defaultPositionsZone1 = [];

        // Group A: upper diagonal row 49 -> 29
        for ($i = 0; $i < 21; $i++) {
            $id = 49 - $i;
            $defaultPositionsZone1[$id] = ['x' => 940 + $i * 43, 'y' => 175, 'w' => 34, 'h' => 76, 'r' => -20];
        }

        // Group B+C: lower diagonal chain 28 -> 1
        for ($i = 0; $i < 28; $i++) {
            $id = 28 - $i;
            $defaultPositionsZone1[$id] = ['x' => 620 + $i * 43, 'y' => 275, 'w' => 34, 'h' => 76, 'r' => -20];
        }

        // Group D: top-left diagonal 73..70
        $tLeft = [73, 72, 71, 70];
        foreach ($tLeft as $i => $id) {
            $defaultPositionsZone1[$id] = ['x' => 330 + $i * 48, 'y' => 100, 'w' => 34, 'h' => 74, 'r' => 35];
        }

        // Group E: top-right perpendicular row 57..52
        $topRow = [
            57 => ['x' => 1250, 'y' => 76],
            56 => ['x' => 1372, 'y' => 74],
            55 => ['x' => 1478, 'y' => 60],
            54 => ['x' => 1584, 'y' => 58],
            53 => ['x' => 1682, 'y' => 60],
            52 => ['x' => 1786, 'y' => 60]
        ];
        foreach ($topRow as $id => $pos) {
            $defaultPositionsZone1[$id] = ['x' => $pos['x'], 'y' => $pos['y'], 'w' => 74, 'h' => 38, 'r' => 0];
        }

        // Vertical stack 58, 59, 60, 61
        $vStack = [58 => 90, 59 => 180, 60 => 270, 61 => 360];
        foreach ($vStack as $id => $y) {
            $defaultPositionsZone1[$id] = ['x' => 1980, 'y' => $y, 'w' => 34, 'h' => 76, 'r' => 0];
        }

        // Group G: pair 51, 50
        $defaultPositionsZone1[51] = ['x' => 745, 'y' => 210, 'w' => 68, 'h' => 38, 'r' => 0];
        $defaultPositionsZone1[50] = ['x' => 828, 'y' => 210, 'w' => 68, 'h' => 38, 'r' => 0];

        // Group H: bottom perpendicular row 69..62
        $botRow = [69, 68, 67, 66, 65, 64, 63, 62];
        foreach ($botRow as $i => $id) {
            $defaultPositionsZone1[$id] = ['x' => 1070 + $i * 94, 'y' => 420, 'w' => 74, 'h' => 38, 'r' => 0];
        }

        // Save Zone 1 slots ONLY if table is empty
        $existingCount = ParkingSlot::where('zone_id', $zone1->id)->count();
        if ($existingCount === 0) {
            foreach ($defaultPositionsZone1 as $slotNum => $pos) {
                ParkingSlot::create([
                    'zone_id' => $zone1->id,
                    'slot_number' => (string)$slotNum,
                    'status' => 'available',
                    'pos_x' => $pos['x'],
                    'pos_y' => $pos['y'],
                    'rotation' => $pos['r'],
                    'width' => $pos['w'],
                    'height' => $pos['h'],
                ]);
            }
        }


        // ── Zone 2: แผนผังพื้นที่จอดรถในอาคาร (Bays) ──
        $zone2 = ParkingZone::where('building', 'ในอาคาร')->first();
        if (!$zone2) {
            $zone2 = ParkingZone::create([
                'building' => 'ในอาคาร',
                'floor' => 'ชั้น 1',
                'zone' => 'B',
                'total_slots' => 54
            ]);
        }

        // List slots B1_1 to B19_3
        $bays = [
            1 => 3, 2 => 3, 3 => 3, 4 => 3, 5 => 3, 6 => 3, 7 => 3,
            8 => 2, 9 => 3, 10 => 3, 11 => 3, 12 => 4, 13 => 3, 14 => 3,
            15 => 3, 16 => 3, 17 => 3, 18 => 3, 19 => 1
        ];

        foreach ($bays as $bayNum => $slotCount) {
            for ($i = 1; $i <= $slotCount; $i++) {
                $slotId = "B{$bayNum}_{$i}";
                $slot = ParkingSlot::where('zone_id', $zone2->id)->where('slot_number', $slotId)->first();
                if (!$slot) {
                    ParkingSlot::create([
                        'zone_id' => $zone2->id,
                        'slot_number' => $slotId,
                        'status' => 'available',
                        'pos_x' => 0,
                        'pos_y' => 0,
                        'rotation' => 0,
                        'width' => 30,
                        'height' => 60,
                    ]);
                }
            }
        }

        // Initialize default map elements (like poles and labels) if empty
        if (ParkingMapElement::where('zone_id', $zone1->id)->count() === 0) {
            $initialPoles = [
                [1338, 72], [1548, 66], [1994, 74], [300, 255], [560, 225], [900, 158], [1120, 128]
            ];
            foreach ($initialPoles as $p) {
                ParkingMapElement::create([
                    'zone_id' => $zone1->id,
                    'type' => 'icon',
                    'content' => 'fa-lightbulb', // Representing lighting/CCTV poles
                    'pos_x' => $p[0],
                    'pos_y' => $p[1],
                    'rotation' => 0,
                    'scale' => 1.0,
                    'color' => '#dd9a2b'
                ]);
            }
        }
    }

    public function index()
    {
        $zone = ParkingZone::first();
        if ($zone) {
            $slots = ParkingSlot::where('zone_id', $zone->id)
                                ->whereIn('slot_number', ['1', '2', '3', '4', '5', '6'])
                                ->get()
                                ->keyBy('slot_number');
        } else {
            $slots = collect();
        }

        return view('parking.map.index', compact('zone', 'slots'));
    }

    public function mapFull()
    {
        $this->initializeDefaultCoordinates();
        
        $zone1 = ParkingZone::where('building', 'ลานจอดรถหลัก')->first();

        // Load Zone 1 slots
        $slots = ParkingSlot::where('zone_id', $zone1->id ?? 1)
        ->with([
            'employeeParkings' => function($q) {
                $q->where('status', 'parking')->with('user');
            },
            'visitorReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in']);
            },
            'employeeReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in'])->with(['user', 'department']);
            }
        ])->get()->keyBy('slot_number');

        // Load Custom layout elements (Text/Icons)
        $elements = ParkingMapElement::where('zone_id', $zone1->id ?? 1)->get();

        return view('parking.map.car_park_management_plan', compact('slots', 'elements'));
    }

    public function mapBuilding()
    {
        $this->initializeDefaultCoordinates();

        $zone2 = ParkingZone::where('building', 'ในอาคาร')->first();

        // Load Zone 2 slots
        $slots = ParkingSlot::where('zone_id', $zone2->id ?? 2)
        ->with([
            'employeeParkings' => function($q) {
                $q->where('status', 'parking')->with('user');
            },
            'visitorReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in']);
            },
            'employeeReservations' => function($q) {
                $q->whereIn('status', ['reserved', 'checked_in'])->with(['user', 'department']);
            }
        ])->get()->keyBy('slot_number');

        return view('parking.map.parking_floor_plan_building', compact('slots'));
    }

    public function saveLayout(Request $request)
    {
        $request->validate([
            'slots' => 'nullable|array',
            'slots.*.id' => 'required',
            'slots.*.x' => 'required|numeric',
            'slots.*.y' => 'required|numeric',
            'slots.*.rotation' => 'required|numeric',
            'slots.*.width' => 'required|numeric',
            'slots.*.height' => 'required|numeric',
            'elements' => 'nullable|array',
            'elements.*.id' => 'required',
            'elements.*.x' => 'required|numeric',
            'elements.*.y' => 'required|numeric',
            'elements.*.rotation' => 'required|numeric',
        ]);

        $zone1 = ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        $zone1Id = $zone1 ? $zone1->id : 1;

        if ($request->has('slots') && is_array($request->slots)) {
            foreach ($request->slots as $sData) {
                // Query by slot_number and zone_id to specify main zone
                $slot = ParkingSlot::where('zone_id', $zone1Id)
                                   ->where('slot_number', (string)$sData['id'])
                                   ->first();
                if ($slot) {
                    $slot->update([
                        'pos_x' => $sData['x'],
                        'pos_y' => $sData['y'],
                        'rotation' => $sData['rotation'],
                        'width' => $sData['width'],
                        'height' => $sData['height'],
                    ]);
                } else {
                    // Fallback to query by slot_number only if zone matching didn't catch it
                    $fallbackSlot = ParkingSlot::where('slot_number', (string)$sData['id'])->first();
                    if ($fallbackSlot) {
                        $fallbackSlot->update([
                            'pos_x' => $sData['x'],
                            'pos_y' => $sData['y'],
                            'rotation' => $sData['rotation'],
                            'width' => $sData['width'],
                            'height' => $sData['height'],
                        ]);
                    }
                }
            }
        }

        // Save Elements Layout if provided
        if ($request->has('elements') && is_array($request->elements)) {
            foreach ($request->elements as $elData) {
                $element = ParkingMapElement::find($elData['id']);
                if ($element) {
                    $element->update([
                        'pos_x' => $elData['x'],
                        'pos_y' => $elData['y'],
                        'rotation' => $elData['rotation'],
                        'scale' => $elData['scale'] ?? 1.0,
                        'content' => $elData['content'] ?? $element->content,
                        'color' => $elData['color'] ?? $element->color,
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'บันทึกตำแหน่งผังและออบเจกต์เรียบร้อยแล้ว']);
    }

    /**
     * API: Add a new parking slot
     */
    public function addSlot(Request $request)
    {
        $request->validate([
            'slot_number' => 'required|string',
            'pos_x' => 'required|numeric',
            'pos_y' => 'required|numeric',
            'rotation' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'zone_name' => 'nullable|string'
        ]);

        $zoneName = $request->zone_name ?: 'ลานจอดรถหลัก';
        $zone = ParkingZone::where('building', $zoneName)->first();
        if (!$zone) {
            $zone = ParkingZone::create([
                'building' => $zoneName,
                'floor' => 'ทั่วไป',
                'total_slots' => 1
            ]);
        }

        // Check if duplicate in this zone
        $exists = ParkingSlot::where('zone_id', $zone->id)->where('slot_number', $request->slot_number)->exists();
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'มีเลขช่องจอดนี้อยู่แล้วในโซนนี้'], 422);
        }

        $slot = ParkingSlot::create([
            'zone_id' => $zone->id,
            'slot_number' => $request->slot_number,
            'status' => 'available',
            'pos_x' => $request->pos_x,
            'pos_y' => $request->pos_y,
            'rotation' => $request->rotation,
            'width' => $request->width,
            'height' => $request->height,
        ]);

        return response()->json(['success' => true, 'slot' => $slot]);
    }

    /**
     * API: Delete a parking slot
     */
    public function deleteSlot(Request $request, $slot_number)
    {
        $slot = ParkingSlot::where('slot_number', $slot_number)->first();
        if ($slot) {
            $slot->delete();
            return response()->json(['success' => true, 'message' => 'ลบช่องจอดเรียบร้อยแล้ว']);
        }
        return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลช่องจอด'], 44);
    }

    /**
     * API: Add custom decorative element (Text or Icon)
     */
    public function addElement(Request $request)
    {
        $request->validate([
            'type' => 'required|in:text,icon',
            'content' => 'required|string',
            'pos_x' => 'required|numeric',
            'pos_y' => 'required|numeric',
            'color' => 'nullable|string',
        ]);

        $zone = ParkingZone::where('building', 'ลานจอดรถหลัก')->first();
        if (!$zone) {
            $zone = ParkingZone::create(['building' => 'ลานจอดรถหลัก', 'total_slots' => 74]);
        }

        $element = ParkingMapElement::create([
            'zone_id' => $zone->id,
            'type' => $request->type,
            'content' => $request->content,
            'pos_x' => $request->pos_x,
            'pos_y' => $request->pos_y,
            'rotation' => 0,
            'scale' => 1.0,
            'color' => $request->color ?: '#1c3550',
        ]);

        return response()->json(['success' => true, 'element' => $element]);
    }

    /**
     * API: Delete custom layout element
     */
    public function deleteElement($id)
    {
        $element = ParkingMapElement::find($id);
        if ($element) {
            $element->delete();
            return response()->json(['success' => true, 'message' => 'ลบออบเจกต์เรียบร้อยแล้ว']);
        }
        return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูลออบเจกต์']);
    }
}
