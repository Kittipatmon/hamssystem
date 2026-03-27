<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\News;

use App\Http\Controllers\NewsController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\serviceshams\RequisitionsController;
use App\Http\Controllers\serviceshams\ItemsController;
use App\Http\Controllers\serviceshams\CartItemsController;
use App\Http\Controllers\serviceshams\ItemsTypeController;
use App\Http\Controllers\serviceshams\ChecklistController;

use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\MicrosoftAuthController;
use App\Http\Controllers\bookingmeeting\RoomsController;
use App\Http\Controllers\bookingmeeting\ReservationsController;
use App\Http\Controllers\backend\BackendVehicleController;
use App\Http\Controllers\bookingcar\BookingCarController;
use App\Http\Controllers\housing\EmployeeHousingController;

Route::get('/', function () {
    // Fetch active news ordered by newest published date
    $news = News::query()
        ->where('is_active', true)
        ->orderByDesc('published_date')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

    $policies = \App\Models\Policy::where('type', 'policy')->orderBy('order')->get();
    $operations = \App\Models\Policy::where('type', 'operation')->orderBy('order')->get();

    return view('welcome', compact('news', 'policies', 'operations'));
})->name('welcome');


// Microsoft (Outlook) OAuth routes (public)
Route::get('/auth/microsoft/redirect', [MicrosoftAuthController::class, 'redirect'])->name('auth.microsoft.redirect');
Route::get('/auth/microsoft/callback', [MicrosoftAuthController::class, 'callback'])->name('auth.microsoft.callback');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::prefix('datamanage')->name('datamanage.')->group(function () {
    Route::get('news/newsall', [NewsController::class, 'newsall'])->name('news.newsalllist');
    Route::get('news/{news}/detail', [NewsController::class, 'detail'])->name('news.detail');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/backend/welcome', [DataManagementController::class, 'welcomeDataManagement'])->name('backend.welcomedatamanage');
    Route::resource('backend/policy', \App\Http\Controllers\Backend\PolicyController::class, ['as' => 'backend']);


    Route::prefix('datamanage')->name('datamanage.')->group(function () {


        Route::resource('news', NewsController::class)->except(['show']);
        // Send Outlook notification for a news item
        Route::post('news/{news}/notify-outlook', [NewsController::class, 'sendOutlook'])
            ->name('news.notifyOutlook');
        // After Microsoft login, trigger sending via GET (used for post-login continuation)
        Route::get('news/{news}/notify-outlook-after-login', [NewsController::class, 'sendOutlookAfterLogin'])
            ->name('news.notifyOutlook.afterLogin');
    });

    Route::get('/serviceshams/welcome', [RequisitionsController::class, 'welcomeService'])->name('serviceshams.welcomeservice');

    // Route::resource('items', ItemsController::class);
    Route::get('items', [ItemsController::class, 'index'])->name('items.index');
    Route::get('items/create', [ItemsController::class, 'create'])->name('items.create');
    Route::post('items', [ItemsController::class, 'store'])->name('items.store');
    Route::get('items/{item}/edit', [ItemsController::class, 'edit'])->name('items.edit');
    Route::put('items/{item}', [ItemsController::class, 'update'])->name('items.update');
    Route::delete('items/{item}', [ItemsController::class, 'destroy'])->name('items.destroy');
    Route::post('items/{id}/addstore', [ItemsController::class, 'updateStock'])->name('items.updateStock');
    Route::post('items/{id}/downstock', [ItemsController::class, 'downStock'])->name('items.downstock');

    // Realtime search endpoint for items
    Route::get('items/search', [ItemsController::class, 'searchItem'])->name('items.search');

    Route::get('items/itemsall', [ItemsController::class, 'itemsAll'])->name('items.itemsalllist');


    Route::get('/cartitem', [CartItemsController::class, 'showitems'])->name('cartitem.index');
    Route::post('/cartitem/add', [CartItemsController::class, 'addToCart'])->name('cartitem.add');

    Route::delete('/cartitem/{id}', [CartItemsController::class, 'destroy'])->name('cartitem.destroy');
    Route::post('/cartitem/update/{id}', [CartItemsController::class, 'update'])->name('cartitem.update');
    Route::post('/cartitem/checkout', [CartItemsController::class, 'confirmRequisition'])->name('cartitem.checkout');

    // Items Type management
    Route::get('items_type', [ItemsTypeController::class, 'index'])->name('items_type.index');
    Route::post('items_type', [ItemsTypeController::class, 'store'])->name('items_type.store');
    Route::put('items_type/{id}', [ItemsTypeController::class, 'update'])->name('items_type.update');
    Route::delete('items_type/{id}', [ItemsTypeController::class, 'destroy'])->name('items_type.destroy');
    Route::post('items_type/{id}/toggle-status', [ItemsTypeController::class, 'updateStatus'])->name('items_type.toggleStatus');


    //Requisitions routes
    Route::get('requisitions/reqpending', [RequisitionsController::class, 'ReqlistPending'])->name('requisitions.reqlistpending');

    Route::get('requisitions/reqlistall', [RequisitionsController::class, 'ReqlistAll'])->name('requisitions.reqlistall');
    Route::get('requisitions/detailreqpedding/{id}', [RequisitionsController::class, 'DetailReqPending'])->name('requisitions.detailreqpedding');
    Route::get('requisitions/detailreqlistall/{id}', [RequisitionsController::class, 'DetailReqAlllist'])->name('requisitions.detailreqlistall');
    Route::get('requisitions/cancel/{id}', [RequisitionsController::class, 'cancel'])->name('requisitions.cancel');
    //dashboard route
    Route::get('requisitions/dashboard', [RequisitionsController::class, 'dashboardRequisition'])->name('requisitions.dashboard');
    Route::get('requisitions/dashboard/data', [RequisitionsController::class, 'dashboardData'])->name('requisitions.dashboard.data');

    Route::get('requisitions/reportslistall', [RequisitionsController::class, 'Reportslistall'])->name('requisitions.reportslistall');
    Route::get('requisitions/reportslistall/export/pdf', [RequisitionsController::class, 'ReportslistallExportPdf'])->name('requisitions.reportslistall.export.pdf');
    Route::get('requisitions/reportslistall/export/csv', [RequisitionsController::class, 'ReportslistallExportCsv'])->name('requisitions.reportslistall.export.csv');


    //Checklist route
    Route::get('requisitions/reqchecklist', [RequisitionsController::class, 'reqChecklist'])->name('requisitions.reqchecklist');
    Route::get('requisitions/detailchecklist/{id}', [RequisitionsController::class, 'DetailChecklist'])->name('requisitions.detailchecklist');

    // ChecklistController routes
    Route::post('checklist/submitreq/{id}', [ChecklistController::class, 'submitReq'])->name('checklist.submitreq');
    Route::post('checklist/cancelreq/{id}', [ChecklistController::class, 'cancelReq'])->name('checklist.cancelreq');
    Route::get('checklist/successreq', [ChecklistController::class, 'successReq'])->name('checklist.successreq');

    // updateCheckItem
    Route::post('checklist/updatecheckitem/{id}', [ChecklistController::class, 'updateCheckItem'])->name('checklist.updatecheckitem');
    // Route::get('requisitions/reqchecklist', [RequisitionsController::class, 'ReqlistChecklist'])->name('requisitions.reqlistchecklist');


    //rooms
    Route::resource('rooms', RoomsController::class);
    //reservations
    Route::get('reservations/welcomemeeting', [ReservationsController::class, 'welcomeReservations'])->name('reservations.welcomemeeting');
    Route::post('reservations/store', [ReservationsController::class, 'store'])->name('reservations.store');
    Route::get('reservations/events', [ReservationsController::class, 'events'])->name('reservations.events');
    Route::post('reservations/cancel/{id}', [ReservationsController::class, 'cancel'])->name('reservations.cancel');

    // Backend Booking Meeting
    Route::prefix('backend/bookingmeeting')->name('backend.bookingmeeting.')->group(function () {
        Route::resource('rooms', \App\Http\Controllers\bookingmeeting\BackendRoomsController::class);
        Route::resource('reservations', \App\Http\Controllers\bookingmeeting\BackendReservationsController::class);
        Route::get('report', [\App\Http\Controllers\bookingmeeting\BackendReportController::class, 'index'])->name('report.index');
    });

    //bookingcar
    Route::prefix('bookingcar')->name('bookingcar.')->group(function () {
        Route::get('welcome', [BookingCarController::class, 'welcome'])->name('welcome');
        Route::get('vehicles', [BookingCarController::class, 'vehicles'])->name('vehicles');
        Route::get('check-availability', [BookingCarController::class, 'checkAvailability'])->name('checkAvailability');
        Route::post('store', [BookingCarController::class, 'store'])->name('store');

        // Admin / Management routes
        Route::get('dashboard', [BookingCarController::class, 'dashboard'])->name('dashboard');
        Route::get('export-excel', [BookingCarController::class, 'exportExcel'])->name('export.excel');
        Route::get('report', [BookingCarController::class, 'report'])->name('report');
        Route::get('edit/{id}', [BookingCarController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [BookingCarController::class, 'update'])->name('update');
        Route::put('approve/{id}', [BookingCarController::class, 'approve'])->name('approve');
        Route::post('{id}/cancel', [BookingCarController::class, 'cancel'])->name('cancel');
        Route::post('{id}/return', [BookingCarController::class, 'returnCar'])->name('returnCar');
        Route::get('get-districts', [BookingCarController::class, 'getDistricts'])->name('getDistricts');
    });

    // Backend Vehicles Management
    Route::prefix('backend/bookingcar')->name('backend.bookingcar.')->group(function () {
        Route::get('dashboard', [BackendVehicleController::class, 'dashboard'])->name('dashboard');
        Route::get('table', [BackendVehicleController::class, 'table'])->name('table');
        // Route::get('addvehicles', [BackendVehicleController::class, 'addvehicles'])->name('addvehicles');
        Route::post('store', [BackendVehicleController::class, 'store'])->name('store');
        Route::get('{id}/edit', [BackendVehicleController::class, 'edit'])->name('edit');
        Route::put('{id}', [BackendVehicleController::class, 'update'])->name('update');
        Route::delete('{id}', [BackendVehicleController::class, 'destroy'])->name('destroy');
        Route::post('inspections', [BackendVehicleController::class, 'storeInspection'])->name('inspections.store');
        Route::put('inspections/{id}', [BackendVehicleController::class, 'updateInspection'])->name('inspections.update');
        Route::delete('inspections/{id}', [BackendVehicleController::class, 'destroyInspection'])->name('inspections.destroy');
    });

    // Backend Management
    Route::resource('users', UserController::class);
    Route::resource('usertypes', \App\Http\Controllers\backend\UserTypeController::class);
    Route::resource('sections', \App\Http\Controllers\backend\SectionController::class);
    Route::resource('divisions', \App\Http\Controllers\backend\DivisionController::class);
    Route::resource('departments', \App\Http\Controllers\backend\DepartmentController::class);

    // API-like route for User filtering (as used in users/index.blade.php)
    Route::get('api/users', [UserController::class, 'index'])->name('api.users.index');

    // Employee Housing System
    Route::prefix('housing')->name('housing.')->group(function () {
        Route::get('welcome', [EmployeeHousingController::class, 'welcome'])->name('welcome');
        Route::get('houselist', [EmployeeHousingController::class, 'houselist'])->name('houselist');
        Route::get('request/create', [EmployeeHousingController::class, 'requestForm'])->name('request.create');
        Route::post('request/store', [EmployeeHousingController::class, 'storeRequest'])->name('request.store');
        Route::get('agreement/create', [EmployeeHousingController::class, 'agreementForm'])->name('agreement.create');
        Route::post('agreement/store', [EmployeeHousingController::class, 'storeAgreement'])->name('agreement.store');
        Route::get('guest/create', [EmployeeHousingController::class, 'guestForm'])->name('guest.create');
        Route::post('guest/store', [EmployeeHousingController::class, 'storeGuest'])->name('guest.store');
        Route::get('leave/create', [EmployeeHousingController::class, 'leaveForm'])->name('leave.create');
        Route::post('leave/store', [EmployeeHousingController::class, 'storeLeave'])->name('leave.store');
        Route::get('management', [EmployeeHousingController::class, 'management'])->name('management');
        Route::delete('destroy/{type}/{id}', [EmployeeHousingController::class, 'destroy'])->name('destroy');
        Route::post('approve/{type}/{id}', [EmployeeHousingController::class, 'approve'])->name('approve');
        Route::post('update-approver', [EmployeeHousingController::class, 'updateApprover'])->name('update_approver');
        Route::post('assign-room', [EmployeeHousingController::class, 'assignRoom'])->name('assign_room');
        Route::get('room-detail/{id}', [EmployeeHousingController::class, 'roomDetail'])->name('room_detail');
        Route::get('my-requests', [EmployeeHousingController::class, 'myRequests'])->name('my_requests');
    });



});

Route::get('/profileUser', [UserController::class, 'profileUser'])->middleware('auth')->name('profileUser');
Route::post('/profile/update-avatar', [UserController::class, 'updateAvatar'])->middleware('auth')->name('users.update_avatar');

require __DIR__ . '/auth.php';
