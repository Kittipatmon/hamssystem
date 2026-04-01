<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderByDesc('published_date')->get();
        return view('backend.announcement.index', compact('announcements'));
    }

    public function create()
    {
        return view('backend.announcement.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_urgent' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_urgent'] = $request->has('is_urgent');

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/announcements'), $imageName);
            $data['image_path'] = 'images/announcements/' . $imageName;
        }

        Announcement::create($data);

        return redirect()->route('backend.announcement.index')->with('success', 'สร้างประกาศเรียบร้อยแล้ว');
    }

    public function edit(Announcement $announcement)
    {
        return view('backend.announcement.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_urgent' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_urgent'] = $request->has('is_urgent');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image_path && file_exists(public_path($announcement->image_path))) {
                @unlink(public_path($announcement->image_path));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
            $request->image->move(public_path('images/announcements'), $imageName);
            $data['image_path'] = 'images/announcements/' . $imageName;
        }

        $announcement->update($data);

        return redirect()->route('backend.announcement.index')->with('success', 'แก้ไขประกาศเรียบร้อยแล้ว');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('backend.announcement.index')->with('success', 'ลบประกาศเรียบร้อยแล้ว');
    }
}
