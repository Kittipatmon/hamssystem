<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;
use App\Models\serviceshams\Cart_items;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\serviceshams\Items;
use App\Models\User;
use App\Models\serviceshams\Requisitions;

class CartItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // $cart_items = Cart_items::all();
    //     return view('cart_items.index', compact('cart_items'));
    // }

    public function showitems()
    {
        $userID = Auth::user()->id; // Get the authenticated user's ID
        $cart_items = Cart_items::where('user_id', $userID)
            ->get();
        // dd($cart_items);
        return view('serviceshams.cart_items.cartshow', compact('cart_items'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|numeric|exists:items,item_id',
            'quantity' => 'required|integer|min:1',
            // 'items_per_pack' => 'required|integer|min:0',
        ]);

        $userID = Auth::id();
        $itemID = (int) $request->input('item_id');
        $quantity = (int) $request->input('quantity');
        // $cart_quantity_pack = (int) $request->input('items_per_pack');

        $cartItem = Cart_items::where('user_id', $userID)
            ->where('cart_item_id', $itemID)
            ->first();

        $item = Items::where('item_id', $itemID)->first();
        if (!$item) {
            return redirect()->back()->with('error', 'ไม่พบสินค้าในระบบ');
        }
        // Guard: requested quantity must not exceed current stock
        if ($quantity > $item->quantity) {
            return redirect()->back()->with('error', 'จำนวนที่เลือกเกินจำนวนสต็อกที่มีอยู่');
        }

        if ($cartItem) {
            $newQty = $cartItem->cart_quantity + $quantity;
            if ($newQty > $item->quantity) {
                return redirect()->back()->with('error', 'จำนวนรวมหลังเพิ่มเกินจำนวนสต็อก');
            }
            $cartItem->cart_quantity = $newQty;
            $cartItem->save();
        } else {
            Cart_items::create([
                'cart_item_id' => $itemID,
                'cart_code' => $item->item_code,
                'cart_name' => $item->name,
                'cart_quantity' => $quantity,
                // 'cart_quantity_pack' => $cart_quantity_pack,
                'user_id' => $userID,
            ]);
        }

        // ตรวจสอบว่าเป็น AJAX หรือปกติ
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'เพิ่มสินค้าในตะกร้าสำเร็จ']);
        } else {
            return redirect()->back()->with('success', 'เพิ่มสินค้าในตะกร้าสำเร็จ');
        }
    }

    public function destroy($id)
    {
        $cartItem = Cart_items::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'ลบสินค้าในตะกร้าสำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $cartItem = Cart_items::findOrFail($id);
        $quantity = (int) $request->input('quantity');
        // $itemper_pack = (int) $request->input('items_per_pack');
        $item = Items::where('item_id', $cartItem->cart_item_id)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'ไม่พบสินค้าในระบบ');
        }
        if ($quantity > $item->quantity) {
            return redirect()->back()->with('error', 'จำนวนชิ้นที่เลือกเกินจำนวนที่มีในสต็อก');
        }
        // if ($itemper_pack > $item->items_per_pack) {
        //     return redirect()->back()->with('error', 'จำนวนแพ็คที่เลือกเกินจำนวนที่มีในสต็อก');
        // }
        $cartItem->cart_quantity = $quantity;
        // $cartItem->cart_quantity_pack = $itemper_pack;
        $cartItem->save();

        return redirect()->back()->with('success', 'อัปเดตจำนวนสินค้าในตะกร้าสำเร็จ');
    }

    public function checkout(Request $request)
    {
        $userID = Auth::id();
        $cart_items = Cart_items::where('user_id', $userID)->get();

        $requisition = new Requisitions();
        $requisition->requester_id = $userID;
        $requisition->request_date = now();
        $requisition->status = Requisitions::STATUS_PENDING;
        // $requisition->remarks = $request->input('remarks', ''); // ใช้ค่า default เป็นค่าว่าง

        $requisition->save();

        foreach ($cart_items as $cartItem) {
            $requisition->requisition_items()->create([
                'item_id' => $cartItem->cart_item_id,
                'quantity' => $cartItem->cart_quantity,
            ]);
        }


        return redirect()->route('cartitem.index')->with('success', 'ยืนยันการเบิกอุปกรณ์สำเร็จ');
    }

    /**
     * ฟังก์ชันสำหรับยืนยันการเบิกอุปกรณ์ (Confirm Requisition)
     * - ตรวจสอบว่าสินค้าในตะกร้ามีเพียงพอในสต็อก
     * - สร้างรายการเบิก (Requisition) และรายการเบิกรายการย่อย (Requisition_items)
     * - หักสต็อกสินค้า
     * - ลบรายการในตะกร้า
     */
    public function confirmRequisition(Request $request)
    {
        $userID = Auth::id();
        $cart_items = Cart_items::where('user_id', $userID)->get();

        if ($cart_items->isEmpty()) {
            return redirect()->back()->with('error', 'ไม่มีสินค้าในตะกร้า');
        }

        // ตรวจสอบสต็อกทั้งชิ้นและแพ็ค
        foreach ($cart_items as $cartItem) {
            $item = Items::where('item_id', $cartItem->cart_item_id)->first();
            if (!$item) {
                return redirect()->back()->with('error', 'ไม่พบสินค้าในระบบ');
            }
            if ($cartItem->cart_quantity > $item->quantity) {
                return redirect()->back()->with('error', 'สินค้า ' . $item->name . ' มีจำนวนชิ้นไม่เพียงพอในสต็อก');
            }
            // if ($cartItem->cart_quantity_pack > $item->items_per_pack) {
            //     return redirect()->back()->with('error', 'สินค้า ' . $item->name . ' มีจำนวนแพ็คไม่เพียงพอในสต็อก');
            // }
        }

        // คำนวณ total price
        $total_price = $cart_items->sum(function ($cartItem) {
            $item = Items::where('item_id', $cartItem->cart_item_id)->first();
            return $item ? ($item->per_unit * $cartItem->cart_quantity) : 0;
            // return $item ? ($item->per_unit * $cartItem->cart_quantity) + ($item->per_pack * $cartItem->cart_quantity_pack) : 0;
        });

        // ตรวจสอบงบประมาณ user
        $user = User::find($userID);
        if ($user && $total_price > $user->user_per) {
            // $user->user_per -= $total_price; // หักงบประมาณ
            // $user->save();
            return redirect()->back()->with('error', 'งบประมาณของคุณไม่เพียงพอสำหรับการเบิกครั้งนี้');
        }
        // หักงบประมาณของผู้ใช้
        if ($user) {
            $user->user_per -= $total_price; // หักงบประมาณ
            $user->save();
        } else {
            return redirect()->back()->with('error', 'ไม่พบผู้ใช้ในระบบ');
        }


        // สร้างรายการเบิก
        $requisition = new Requisitions();
        $requisition->requester_id = $userID;
        $requisition->request_date = now();
        $requisition->remarks = $request->input('remarks', ''); // ใช้ค่า default เป็นค่าว่าง
        $requisition->status = Requisitions::STATUS_PENDING;
        $requisition->total_price = $total_price;
        
        // สร้างรหัสเบิกแบบ YYMMลำดับ เช่น 24060001
        $year = date('y'); // ปี 2 หลัก
        $month = date('m'); // เดือน 2 หลัก
        $count = Requisitions::whereYear('request_date', date('Y'))
            ->whereMonth('request_date', date('m'))
            ->count() + 1;
        $sequence = str_pad($count, 4, '0', STR_PAD_LEFT);
        $requisition->requisitions_code = $year . $month . $sequence;
        $requisition->save();

        // หักสต็อกและสร้างรายการเบิกรายการย่อย
        foreach ($cart_items as $cartItem) {
            $item = Items::where('item_id', $cartItem->cart_item_id)->first();
            if ($item) {
                $item->quantity -= $cartItem->cart_quantity;
                // $item->items_per_pack -= $cartItem->cart_quantity_pack;
                $item->save();
            }
            $requisition->requisition_items()->create([
                'requisition_id' => $requisition->requisitions_id,
                'item_id' => $cartItem->cart_item_id,
                'quantity' => $cartItem->cart_quantity,
                // 'quantity_pack' => $cartItem->cart_quantity_pack,
            ]);
            $cartItem->delete();
        }

        return redirect()->route('cartitem.index')->with('success', 'ยืนยันการเบิกอุปกรณ์สำเร็จ');
    }
}
