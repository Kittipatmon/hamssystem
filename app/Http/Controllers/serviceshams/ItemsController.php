<?php

namespace App\Http\Controllers\serviceshams;
use App\Http\Controllers\Controller;

use App\Models\serviceshams\Items;
use App\Http\Requests\StoreItemsRequest;
use App\Http\Requests\UpdateItemsRequest;
use App\Models\serviceshams\Items_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Items::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%");
            });
        }

        $type_search = $request->input('type_search');
        if ($type_search) {
            $query->whereHas('items_type', function ($q) use ($type_search) {
                $q->where('name', 'like', "%{$type_search}%");
            });
        }

        $items = $query->get();
        $items_types = Items_type::where('status', 1)->get();
        return view('serviceshams.items.index', compact('items', 'items_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items_types = Items_type::where('status', 1)->get();
        return view('serviceshams.items.create' , compact('items_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            // 'items_per_pack' => 'nullable|integer',
            'type_id' => 'required|exists:items_type,item_type_id',
            'item_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'item_code' => 'required|string|max:255',
            'per_unit' => 'required|numeric',
            // 'per_pack' => 'nullable|numeric',
        ]);

        $filename = null;
        if ($request->hasFile('item_pic')) {
            $file = $request->file('item_pic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/items'), $filename);
        }

        $item = new Items();
        $item->name = $validated['name'];
        $item->description = $validated['description'] ?? null;
        $item->quantity = $validated['quantity'];
        // $item->items_per_pack = $validated['items_per_pack'] ?? null;
        $item->type_id = $validated['type_id'];
        $item->item_pic = $filename;
        $item->item_code = $validated['item_code'];
        $item->per_unit = $validated['per_unit'];
        // $item->per_pack = $validated['per_pack'] ?? null;
        $item->created_at = now();
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:0',
            // 'items_per_pack' => 'nullable|integer',
        ]);

        $item = Items::findOrFail($id);

        if (!is_null($request->quantity)) {
            $item->quantity += $request->quantity;
        }

        // if (!is_null($request->items_per_pack)) {
        //     $item->items_per_pack += $request->items_per_pack;
        // }

        $item->save();

        return redirect()->route('items.index')->with('success', 'เพิ่มสต็อกอุปกรณ์เรียบร้อย.');
    }

    public function downStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:0',
            // 'items_per_pack' => 'nullable|integer',
        ]);

        $item = Items::findOrFail($id);

        if (!is_null($request->quantity)) {
            $item->quantity -= $request->quantity;
        }

        // if (!is_null($request->items_per_pack)) {
        //     $item->items_per_pack -= $request->items_per_pack;
        // }

        $item->save();

        return redirect()->route('items.index')->with('success', 'ลดสต็อกอุปกรณ์เรียบร้อย.');
    }
    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Items $item)
    {
        $items_types = Items_type::where('status', 1)->get();
        return view('serviceshams.items.edit', compact('item', 'items_types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            // 'items_per_pack' => 'nullable|integer',
            'type_id' => 'required|exists:items_type,item_type_id',
            'item_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'item_code' => 'required|string|max:255',
            'per_unit' => 'required|numeric',
            // 'per_pack' => 'nullable|numeric',
        ]);

        $item = Items::findOrFail($id);
        $filename = $item->item_pic;

        if ($request->hasFile('item_pic')) {

            if ($filename && file_exists(public_path('images/items/' . $filename))) {
                @unlink(public_path('images/items/' . $filename));
            }
            $file = $request->file('item_pic');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/items'), $filename);
            $item->item_pic = $filename;
        }

        $item->name = $validated['name'];
        $item->description = $validated['description'] ?? null;
        $item->quantity = $validated['quantity'];
        // $item->items_per_pack = $validated['items_per_pack'] ?? null;
        $item->type_id = $validated['type_id'];

        if (!$request->hasFile('item_pic')) {
            $item->item_pic = $filename;
        }
        $item->item_code = $validated['item_code'];
        $item->per_unit = $validated['per_unit'];
        // $item->per_pack = $validated['per_pack'] ?? null;
        $item->updated_at = now();
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $items = Items::find($id);
        $items->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function searchItem(Request $request)
    {
        $query = trim((string) $request->input('query', ''));

        $itemsQuery = Items::query();

        if ($query !== '') {
            $itemsQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('item_code', 'LIKE', "%{$query}%");
            });
        }

        // Return only fields needed by the UI
        $items = $itemsQuery
            ->orderBy('name')
            ->get(['item_id', 'name', 'item_pic', 'item_code', 'quantity', 'per_unit']);

        return response()->json([
            'data' => $items,
        ]);
    }

    public function itemsAll()
    {
        $items = Items::all();
        return view('serviceshams.items.itemsall', compact('items'));
    }

    // public function show()
    // {
    //     $item = Items::all();
    //     return view('items.show', compact('item'));
    // }
}
