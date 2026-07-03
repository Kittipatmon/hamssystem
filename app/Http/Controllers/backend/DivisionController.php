<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Division;
use App\Models\Section;

class DivisionController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $query = Division::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('division_name', 'like', "%{$search}%")
                  ->orWhere('division_fullname', 'like', "%{$search}%");
        }

        try {
            $divisions = $query->with('section')->get();
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::warning("Table 'divisions' not found: " . $e->getMessage());
            $divisions = collect();
        }

        if ($request->ajax()) {
            return response()->json($divisions);
        }

        try {
            $sections = Section::all();
        } catch (\Illuminate\Database\QueryException $e) {
            $sections = collect();
        }
        return view('backend.division.index', compact('divisions', 'sections'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'section_id' => 'required',
            'division_name' => 'required|string|max:255',
            'division_fullname' => 'required|string|max:255',
        ]);

        Division::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('divisions.index')->with('success', 'สร้างฝ่ายใหม่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'section_id' => 'required',
            'division_name' => 'required|string|max:255',
            'division_fullname' => 'required|string|max:255',
            'division_status' => 'required|integer',
        ]);

        $division = Division::findOrFail($id);
        $division->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('divisions.index')->with('success', 'อัปเดตฝ่ายเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $division = Division::findOrFail($id);
        $division->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('divisions.index')->with('success', 'ลบฝ่ายเรียบร้อยแล้ว');
    }
}
