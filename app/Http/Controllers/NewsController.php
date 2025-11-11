<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;

class NewsController extends Controller
{
    public function index()
    {
        // For data management, list all news (active and inactive) by publish date, then creation time
        $news = News::orderByDesc('published_date')
                    ->orderByDesc('created_at')
                    ->get();

        return view('datamanage.news.index', compact('news'));
    }

    public function create()
    {
        $news = new News([
            'is_active' => true,
            'published_date' => now()->toDateString(),
        ]);
        return view('datamanage.news.create', compact('news'));
    }

    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        // Handle multiple image uploads (images[]). Keep legacy 'image' for backward compatibility.
        $uploadedPaths = [];
        $destination = public_path('images/news');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file) { continue; }
                $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $uploadedPaths[] = '/images/news/' . $filename;
            }
        } elseif ($request->hasFile('image')) {
            // Legacy single upload
            $file = $request->file('image');
            $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $uploadedPaths[] = '/images/news/' . $filename;
        }

        $data['image_path'] = !empty($uploadedPaths) ? json_encode($uploadedPaths) : null;

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;
        $news = News::create($data);

        return redirect()->route('datamanage.news.index')->with('success', 'บันทึกข่าวสารเรียบร้อยแล้ว');
    }

    public function edit(News $news)
    {
        return view('datamanage.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $data = $request->validated();
        $existingImages = $this->normalizeImagePaths($news->image_path);

        $destination = public_path('images/news');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $uploadedPaths = [];

        if ($request->hasFile('images')) {
            // Remove old images
            foreach ($existingImages as $img) {
                $path = public_path(ltrim($img, '/'));
                if (is_file($path)) { @unlink($path); }
            }
            foreach ($request->file('images') as $file) {
                if (!$file) { continue; }
                $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $uploadedPaths[] = '/images/news/' . $filename;
            }
            $data['image_path'] = !empty($uploadedPaths) ? json_encode($uploadedPaths) : null;
        } elseif ($request->hasFile('image')) {
            // Remove old images
            foreach ($existingImages as $img) {
                $path = public_path(ltrim($img, '/'));
                if (is_file($path)) { @unlink($path); }
            }
            $file = $request->file('image');
            $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $data['image_path'] = json_encode(['/images/news/' . $filename]);
        } else {
            // No new uploads; keep existing images
            unset($data['image_path']);
        }

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;

        $news->update($data);

        return redirect()->route('datamanage.news.index')->with('success', 'แก้ไขข่าวสารเรียบร้อยแล้ว');
    }

    public function destroy(News $news)
    {
        // Delete all image files if exist (supports multiple or single)
        $images = $this->normalizeImagePaths($news->image_path);
        foreach ($images as $img) {
            $path = public_path(ltrim($img, '/'));
            if (is_file($path)) { @unlink($path); }
        }

        $news->delete();

        return redirect()->route('datamanage.news.index')->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }

    public function detail($id)
    {
        $news = News::findOrFail($id);
        return view('datamanage.news.detail', compact('news'));
    }

    /**
     * Normalize image_path field to array of strings.
     * Accepts JSON array, comma-separated string, or a single path string.
     */
    private function normalizeImagePaths($imagePath): array
    {
        if (empty($imagePath)) { return []; }

        // If already an array
        if (is_array($imagePath)) { return $imagePath; }

        // Try JSON decode
        $decoded = json_decode($imagePath, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        // Comma separated
        if (is_string($imagePath) && strpos($imagePath, ',') !== false) {
            return array_values(array_filter(array_map('trim', explode(',', $imagePath))));
        }

        // Single path string
        if (is_string($imagePath)) { return [trim($imagePath)]; }

        return [];
    }

    public function newsall(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = News::where('is_active', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('newto', 'like', "%{$search}%");
            });
        }

        $news = $query->orderByDesc('published_date')
                      ->orderByDesc('created_at')
                      ->get();

        return view('datamanage.news.newsall', compact('news'));
    }

    public function filterNews(Request $request)
    {
        $query = News::query()->where('is_active', true);

        if ($request->filled('start_date')) {
            $query->whereDate('published_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('published_date', '<=', $request->input('end_date'));
        }

        $news = $query->orderByDesc('published_date')
                      ->orderByDesc('created_at')
                      ->get();

        return view('datamanage.news.newsall', compact('news'));
    }
}
// C:\xampp\htdocs\hamssystem\resources\views\datamanage\news\detail.blade.php