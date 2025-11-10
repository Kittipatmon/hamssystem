<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\News;

Route::get('/', function () {
    // Fetch active news ordered by newest published date
    $news = News::query()
        ->where('is_active', true)
        ->orderByDesc('published_date')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

    return view('welcome', compact('news'));
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
