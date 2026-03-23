<?php

namespace App\Http\Controllers\bookingmeeting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingmeeting\Rooms;

class BackendRoomsController extends Controller
{
    public function index(Request $request)
    {
        $query = Rooms::query();

        // Search by name
        if ($request->filled('search')) {
            $query->where('room_name', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by tab
        $hasTabFilter = false;
        if ($request->filled('tab')) {
            $hasTabFilter = true;
            if ($request->tab == 'active') {
                $query->where('status', 1);
            } elseif ($request->tab == 'inactive') {
                $query->where('status', 0);
            } elseif ($request->tab == 'large') {
                $query->where('capacity', '>', 10);
            }
        }

        // If a tab filter is active, show ALL results (no pagination)
        if ($hasTabFilter) {
            $rooms = $query->orderBy('room_id', 'desc')->get();
        } else {
            $rooms = $query->orderBy('room_id', 'desc')->paginate(10)->withQueryString();
        }

        return view('backend.bookingmeeting.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('backend.bookingmeeting.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|integer|in:0,1',
            'image_file.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['image_file', 'has_projector', 'has_video_conf']);
        $data['has_projector'] = $request->has('has_projector') ? 1 : 0;
        $data['has_video_conf'] = $request->has('has_video_conf') ? 1 : 0;

        // Handle File Uploads for Multiple Images
        $images = [];
        if ($request->hasFile('image_file')) {
            foreach ($request->file('image_file') as $file) {
                $filename = 'ROOM_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/room'), $filename);
                $images[] = $filename;
            }
        }
        $data['images'] = json_encode($images);

        Rooms::create($data);

        return redirect()->route('backend.bookingmeeting.rooms.index')->with('success', 'เพิ่มข้อมูลห้องประชุมสำเร็จ');
    }

    public function edit($id)
    {
        $room = Rooms::findOrFail($id);
        return view('backend.bookingmeeting.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Rooms::findOrFail($id);

        $request->validate([
            'room_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|integer|in:0,1',
            'image_file.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['image_file', 'has_projector', 'has_video_conf']);
        $data['has_projector'] = $request->has('has_projector') ? 1 : 0;
        $data['has_video_conf'] = $request->has('has_video_conf') ? 1 : 0;

        $images = is_string($room->images) ? json_decode($room->images, true) : (is_array($room->images) ? $room->images : []);

        if ($request->hasFile('image_file')) {
            foreach ($request->file('image_file') as $file) {
                $filename = 'ROOM_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/room'), $filename);
                $images[] = $filename;
            }
        }

        if (!empty($images)) {
            $data['images'] = json_encode(array_values(array_filter($images)));
        }

        $room->update($data);

        return redirect()->route('backend.bookingmeeting.rooms.index')->with('success', 'ปรับปรุงข้อมูลห้องประชุมสำเร็จ');
    }

    public function destroy($id)
    {
        $room = Rooms::findOrFail($id);
        // Note: Can add logic to delete images from storage if required
        $room->delete();

        return redirect()->route('backend.bookingmeeting.rooms.index')->with('success', 'ลบข้อมูลห้องประชุมสำเร็จ');
    }
}
