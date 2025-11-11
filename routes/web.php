<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\News;

use App\Http\Controllers\NewsController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\serviceshams\RequisitionsController;
use App\Http\Controllers\serviceshams\ItemsController;

Route::get('/', function () {
    // Fetch active news ordered by newest published date
    $news = News::query()
        ->where('is_active', true)
        ->orderByDesc('published_date')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

    return view('welcome', compact('news'));
})->name('welcome');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/datamanage/welcome', [DataManagementController::class, 'welcomeDataManagement'])->name('datamanage.welcomedatamanage');

    Route::prefix('datamanage')->name('datamanage.')->group(function () {
        Route::get('news/newsall', [NewsController::class, 'newsall'])->name('news.newsalllist');
        Route::get('news/{news}/detail', [NewsController::class, 'detail'])->name('news.detail');

        Route::resource('news', NewsController::class)->except(['show']);
    });

    Route::get('/serviceshams/welcome', [RequisitionsController::class, 'welcomeService'])->name('serviceshams.welcomeservice');

    Route::resource('items', ItemsController::class);
    Route::get('items/create', [ItemsController::class, 'create'])->name('items.create');
    Route::post('items', [ItemsController::class, 'store'])->name('items.store');
    Route::get('items/{item}/edit', [ItemsController::class, 'edit'])->name('items.edit');
    Route::post('items/{id}/addstore', [ItemsController::class, 'updateStock'])->name('items.updateStock');
    Route::post('items/{id}/downstock', [ItemsController::class, 'downStock'])->name('items.downstock');
});

require __DIR__.'/auth.php';
