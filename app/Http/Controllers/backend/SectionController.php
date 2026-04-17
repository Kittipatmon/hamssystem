<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('section_code', 'like', "%{$search}%")
                  ->orWhere('section_name', 'like', "%{$search}%");
        }

        try {
            $sections = $query->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $sections = collect();
        }

        if ($request->ajax()) {
            return response()->json($sections);
        }

        return view('backend.section.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'section_code' => 'required|string|max:255',
            'section_name' => 'required|string|max:255',
        ]);

        Section::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('sections.index')->with('success', 'สร้างสายงานใหม่เรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section_code' => 'required|string|max:255',
            'section_name' => 'required|string|max:255',
            'section_status' => 'required|integer',
        ]);

        $section = Section::findOrFail($id);
        $section->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('sections.index')->with('success', 'อัปเดตสายงานเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect()->route('sections.index')->with('success', 'ลบสายงานเรียบร้อยแล้ว');
    }
}
