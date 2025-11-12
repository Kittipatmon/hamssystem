<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;

use App\Models\serviceshams\Items_type;
use Illuminate\Http\Request;

class ItemsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items_types = Items_type::all();
        return view('serviceshams.items_type.index', compact('items_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('serviceshams.items_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'status' => 'required|integer',
        ]);

        Items_type::create($request->all());
        return redirect()->route('items_type.index')->with('success', 'เพิ่มประเภทรายการเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // 'status' => 'required|integer',
        ]);

        $items_type = Items_type::find($id);
        if ($items_type) {
            $items_type->update($request->all());
            return redirect()->route('items_type.index')->with('success', 'Item type updated successfully.');
        } else {
            return redirect()->route('items_type.index')->with('error', 'Item type not found.');
        }
        
    }

    public function updateStatus(Request $request, $id)
    {
        // 1. Find the item type
        $items_type = Items_type::find($id);
        if (!$items_type) {
            return redirect()->route('items_type.index')->with('error', 'Item type not found.');
        }

        $itemsInUse = \App\Models\serviceshams\Items::where('type_id', $id)->exists();
        if ($itemsInUse) {
            return redirect()->route('items_type.index')->with('error', 'ไม่สามารถเปลี่ยนสถานะได้ เนื่องจากมีการใช้งานอยู่ในรายการอุปกรณ์');
        }

        $items_type->status = $items_type->status ? 0 : 1;
        $items_type->save();

        return redirect()->route('items_type.index')->with('success', 'เปลี่ยนสถานะประเภทรายการเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $items_type = Items_type::find($id);
        if ($items_type) {
            $items_type->delete();
            return redirect()->route('items_type.index')->with('success', 'Item type deleted successfully.');
        } else {
            return redirect()->route('items_type.index')->with('error', 'Item type not found.');
        }
    }
}
