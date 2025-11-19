<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\serviceshams\Requisitions;
use App\Models\serviceshams\Requisition_items;
use App\Models\serviceshams\Items;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChecklistController extends Controller
{

     public function updateCheckItem(Request $request, $id)
    {
        $requisitionItem = Requisition_items::find($id);
        if (!$requisitionItem) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => 'Requisition item not found.'], 404);
            }
            return back()->withErrors(['error' => 'Requisition item not found.']);
        }

        // Update check_item field
        $requisitionItem->check_item = $request->input('check_item');
        $requisitionItem->save();

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['success' => true, 'check_item' => $requisitionItem->check_item]);
        }

        return redirect()
            ->route('requisitions.detailchecklist', ['id' => $requisitionItem->requisition_id])
            ->with('success', 'Requisition item updated successfully.');
    }

    public function submitReq(Request $request, $id)
    {
        $requisition = Requisitions::findOrFail($id);

        // Guard: allow submit only if all items are checked
        $totalItems = Requisition_items::where('requisition_id', $id)->count();
        $checkedItems = Requisition_items::where('requisition_id', $id)->where('check_item', 1)->count();
        if ($totalItems > 0 && $checkedItems < $totalItems) {
            return redirect()->back()->with('error', 'กรุณาตรวจสอบรายการอุปกรณ์ให้ครบทุกชิ้นก่อนส่ง');
        }

        $requisition->packing_staff_id = Auth::user()->id;
        $requisition->packing_staff_status = Requisitions::PACKING_STATUS_APPROVED;
        $requisition->packing_staff_comment = $request->input('packing_staff_comment');
        $requisition->packing_staff_date = now();
        $requisition->status = Requisitions::STATUS_END_PROGRESS;
        $requisition->save();

        // $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        // foreach ($requisition_items as $item) {
        //     $itemModel = Items::find($item->item_id);
        //     if ($itemModel) {
        //         $itemModel->decrement('quantity', $item->quantity); // ลดจำนวนสินค้าในคลัง
        //         $itemModel->decrement('quantity_pack', $item->quantity_pack); // ลดจำนวนสินค้าในคลัง
        //     }
        // }


        // return redirect()->back()->with('success', '
        //     จัดเตรียมอุปกรณ์เรียบร้อย พร้อมส่งให้ผู้ขอเบิกแล้ว
        // .');
        return redirect()->route('requisitions.reqchecklist')->with('success', 'จัดเตรียมอุปกรณ์เรียบร้อย พร้อมส่งให้ผู้ขอเบิกแล้ว');
    }

    public function cancelReq(Request $request, $id)
    {
        $requisition = Requisitions::findOrFail($id);
        $requisition->packing_staff_id = Auth::id();
        $requisition->packing_staff_status = Requisitions::PACKING_STATUS_CANCELLED;
        $requisition->packing_staff_comment = $request->input('packing_staff_comment');
        $requisition->packing_staff_date = now();
        $requisition->status = Requisitions::STATUS_CANCELLED;
        $requisition->save();

        $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        foreach ($requisition_items as $item) {
            $itemModel = Items::find($item->item_id);
            if ($itemModel) {
                $itemModel->increment('quantity', $item->quantity); // เพิ่มจำนวนสินค้าในคลัง
                // $itemModel->increment('items_per_pack', $item->quantity_pack); // เพิ่มจำนวนสินค้าในคลัง
            }
        }

        return redirect()->back()->with('success', 'Requisition cancelled successfully.');
    }

    public function successReq(Request $request)
    {
        return redirect()->route('requisitions.reqchecklist')
            ->with('success', 'ดำเนินการสำเร็จ');
    }
}
