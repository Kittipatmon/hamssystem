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
use App\Http\Controllers\backend\SystemLogController;
use App\Http\Controllers\MicrosoftAuthController;
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
    $announcements = \App\Models\Announcement::orderByDesc('published_date')->get();

    return view('welcome', compact('news', 'policies', 'operations', 'announcements'));
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
    Route::resource('backend/policy', \App\Http\Controllers\backend\PolicyController::class, ['as' => 'backend']);
    Route::resource('backend/announcement', \App\Http\Controllers\backend\AnnouncementController::class, ['as' => 'backend']);


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
    Route::get('items/export', [ItemsController::class, 'exportStockSummary'])->name('items.export');
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

    Route::post('/cartitem/destroy/{id}', [CartItemsController::class, 'destroy'])->name('cartitem.destroy');
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
    Route::get('requisitions/export-summary', [RequisitionsController::class, 'exportSummary'])->name('requisitions.export_summary');
    Route::get('requisitions/detailreqpedding/{id}', [RequisitionsController::class, 'DetailReqPending'])->name('requisitions.detailreqpedding');
    Route::get('requisitions/detailreqlistall/{id}', [RequisitionsController::class, 'DetailReqAlllist'])->name('requisitions.detailreqlistall');
    Route::get('requisitions/detail/pdf/{id}', [RequisitionsController::class, 'DetailExportPdf'])->name('requisitions.detail.pdf');
    Route::get('requisitions/cancel/{id}', [RequisitionsController::class, 'cancel'])->name('requisitions.cancel');
    Route::post('requisitions/update-all-approvers', [RequisitionsController::class, 'updateAllApprovers'])->name('requisitions.update_all_approvers');
    Route::post('requisitions/quick-approve', [RequisitionsController::class, 'quickApprove'])->name('requisitions.quick_approve');
    //dashboard and report route
    Route::middleware('hams.report.access')->group(function () {
        Route::get('requisitions/dashboard', [RequisitionsController::class, 'dashboardRequisition'])->name('requisitions.dashboard');
        Route::get('requisitions/dashboard/data', [RequisitionsController::class, 'dashboardData'])->name('requisitions.dashboard.data');

        Route::get('requisitions/reportslistall', [RequisitionsController::class, 'Reportslistall'])->name('requisitions.reportslistall');
        Route::get('requisitions/reportslistall/export/pdf', [RequisitionsController::class, 'ReportslistallExportPdf'])->name('requisitions.reportslistall.export.pdf');
        Route::get('requisitions/reportslistall/export/csv', [RequisitionsController::class, 'ReportslistallExportCsv'])->name('requisitions.reportslistall.export.csv');
    });


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


    //bookingcar
    Route::prefix('bookingcar')->name('bookingcar.')->group(function () {
        Route::get('welcome', [BookingCarController::class, 'welcome'])->name('welcome');
        Route::get('vehicles', [BookingCarController::class, 'vehicles'])->name('vehicles');
        Route::get('check-availability', [BookingCarController::class, 'checkAvailability'])->name('checkAvailability');
        Route::post('store', [BookingCarController::class, 'store'])->name('store');

        // Admin / Management routes
        Route::middleware('hams.report.access')->group(function () {
            Route::get('dashboard', [BookingCarController::class, 'dashboard'])->name('dashboard');
            Route::get('export-excel', [BookingCarController::class, 'exportExcel'])->name('export.excel');
            Route::get('report', [BookingCarController::class, 'report'])->name('report');
        });
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

    // Backend Booking Meeting Room Routes
    Route::prefix('backend/bookingmeeting')->name('backend.bookingmeeting.')->group(function () {
        Route::resource('rooms', \App\Http\Controllers\bookingmeeting\BackendRoomsController::class);
        Route::post('reservations/{id}/update-status', [\App\Http\Controllers\bookingmeeting\BackendReservationsController::class, 'updateStatus'])->name('reservations.update_status');
        Route::resource('reservations', \App\Http\Controllers\bookingmeeting\BackendReservationsController::class);
        Route::get('report', [\App\Http\Controllers\bookingmeeting\BackendReportController::class, 'index'])->name('report.index');
    });


    Route::post('users/{id}/toggle-hams-editor', [UserController::class, 'toggleHamsEditor'])->name('users.toggle_hams_editor');
    Route::put('users/{id}/role', [UserController::class, 'updateRole'])->name('users.update_role');

    // Backend Management
    Route::resource('users', UserController::class);
    Route::get('system-logs', [SystemLogController::class, 'index'])->name('system-logs.index');
    Route::get('system-logs/archives', [SystemLogController::class, 'archives'])->name('system-logs.archives');
    Route::get('system-logs/archives/{filename}', [SystemLogController::class, 'downloadArchive'])->name('system-logs.download-archive');
    
    // Security Alerts Dashboard
    Route::get('security-alerts', [\App\Http\Controllers\backend\SecurityAlertController::class, 'index'])->name('security-alerts.index');
    Route::post('security-alerts/toggle-ban', [\App\Http\Controllers\backend\SecurityAlertController::class, 'toggleBanIp'])->name('security-alerts.toggle-ban');
    Route::resource('usertypes', \App\Http\Controllers\backend\UserTypeController::class);
    Route::resource('sections', \App\Http\Controllers\backend\SectionController::class);
    Route::resource('divisions', \App\Http\Controllers\backend\DivisionController::class);
    Route::resource('departments', \App\Http\Controllers\backend\DepartmentController::class);
    Route::get('managers', [\App\Http\Controllers\backend\DepartmentController::class, 'managers'])->name('managers.index');

    // API-like route for User filtering (as used in users/index.blade.php)
    Route::get('api/users', [UserController::class, 'index'])->name('api.users.index');

    // Employee Housing System
    Route::prefix('housing')->name('housing.')->group(function () {
        Route::get('welcome', [EmployeeHousingController::class, 'welcome'])->name('welcome');
        Route::get('houselist', [EmployeeHousingController::class, 'houselist'])->name('houselist');
        Route::get('residence-info/{id}', [EmployeeHousingController::class, 'residenceInfo'])->name('residence.info');
        Route::get('request/create', [EmployeeHousingController::class, 'requestForm'])->name('request.create');
        Route::post('request/store', [EmployeeHousingController::class, 'storeRequest'])->name('request.store');
        Route::get('request/{id}/pdf', [EmployeeHousingController::class, 'exportRequestPdf'])->name('request.pdf');
        Route::get('agreement/{id}/pdf', [EmployeeHousingController::class, 'exportAgreementPdf'])->name('agreement.pdf');
        Route::get('guest/{id}/pdf', [EmployeeHousingController::class, 'exportGuestPdf'])->name('guest.pdf');
        Route::get('leave/{id}/pdf', [EmployeeHousingController::class, 'exportLeavePdf'])->name('leave.pdf');
        Route::get('agreement/create', [EmployeeHousingController::class, 'agreementForm'])->name('agreement.create');
        Route::post('agreement/store', [EmployeeHousingController::class, 'storeAgreement'])->name('agreement.store');
        Route::get('guest/create', [EmployeeHousingController::class, 'guestForm'])->name('guest.create');
        Route::post('guest/store', [EmployeeHousingController::class, 'storeGuest'])->name('guest.store');
        Route::get('leave/create', [EmployeeHousingController::class, 'leaveForm'])->name('leave.create');
        Route::post('leave/store', [EmployeeHousingController::class, 'storeLeave'])->name('leave.store');
        Route::get('request/{id}/edit', [EmployeeHousingController::class, 'editRequest'])->name('request.edit');
        Route::put('request/{id}', [EmployeeHousingController::class, 'updateRequest'])->name('request.update');
        Route::get('agreement/{id}/edit', [EmployeeHousingController::class, 'editAgreement'])->name('agreement.edit');
        Route::put('agreement/{id}', [EmployeeHousingController::class, 'updateAgreement'])->name('agreement.update');
        Route::get('guest/{id}/edit', [EmployeeHousingController::class, 'editGuest'])->name('guest.edit');
        Route::put('guest/{id}', [EmployeeHousingController::class, 'updateGuest'])->name('guest.update');
        Route::get('leave/{id}/edit', [EmployeeHousingController::class, 'editLeave'])->name('leave.edit');
        Route::put('leave/{id}', [EmployeeHousingController::class, 'updateLeave'])->name('leave.update');

        // Management & Report (Protected)
        Route::middleware('hams.report.access')->group(function () {
            Route::get('management', [EmployeeHousingController::class, 'management'])->name('management');
            Route::get('report', [EmployeeHousingController::class, 'reportDashboard'])->name('report');
            Route::get('residence/create', [EmployeeHousingController::class, 'residenceCreate'])->name('residence.create');
            Route::post('residence/store', [EmployeeHousingController::class, 'residenceStore'])->name('residence.store');
            Route::get('residence/{id}/edit', [EmployeeHousingController::class, 'residenceEdit'])->name('residence.edit');
            Route::post('residence/{id}/update-all', [EmployeeHousingController::class, 'residenceUpdateAll'])->name('residence.update_all');
            Route::post('room/update', [EmployeeHousingController::class, 'roomUpdate'])->name('room.update');
            Route::post('update-approver', [EmployeeHousingController::class, 'updateApprover'])->name('update_approver');
            Route::post('update-all-approvers', [EmployeeHousingController::class, 'updateAllApprovers'])->name('update_all_approvers');
            Route::post('assign-room', [EmployeeHousingController::class, 'assignRoom'])->name('assign_room');
            Route::post('committee/store', [EmployeeHousingController::class, 'storeCommittee'])->name('committee.store');
            Route::put('committee/{id}', [EmployeeHousingController::class, 'updateCommittee'])->name('committee.update');
            Route::delete('committee/{id}', [EmployeeHousingController::class, 'destroyCommittee'])->name('committee.destroy');
            Route::delete('destroy/{type}/{id}', [EmployeeHousingController::class, 'destroy'])->name('destroy');
        });
        
        Route::post('approve/{type}/{id}', [EmployeeHousingController::class, 'approve'])->name('approve');

        // Other Housing Routes
        Route::get('room-detail/{id}', [EmployeeHousingController::class, 'roomDetail'])->name('room_detail');
        Route::get('my-requests', [EmployeeHousingController::class, 'myRequests'])->name('my_requests');
        Route::get('request-detail/{type}/{id}', [EmployeeHousingController::class, 'requestDetail'])->name('request_detail');
        Route::get('committee/chart', [EmployeeHousingController::class, 'committeeChart'])->name('committee_chart');

        // Repairs
        Route::get('repair/create', [EmployeeHousingController::class, 'repairForm'])->name('repair.create');
        Route::post('repair/store', [EmployeeHousingController::class, 'storeRepair'])->name('repair.store');
        Route::post('repair/assign', [EmployeeHousingController::class, 'assignRepair'])->name('repair.assign');
        Route::post('repair/finish', [EmployeeHousingController::class, 'finishRepair'])->name('repair.finish');
    });

    // Parking System
    Route::prefix('parking')->name('parking.')->group(function () {
        Route::get('api/notifications/check-new', [\App\Http\Controllers\NotificationController::class, 'checkNewRequests'])->name('api.notifications.check_new');
        Route::get('dashboard', [\App\Http\Controllers\Parking\DashboardController::class, 'index'])->name('dashboard');
        
        // Employee Parking
        Route::get('employees', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'index'])->name('employees.index');
        Route::get('employees/create', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'create'])->name('employees.create');
        Route::post('employees', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'store'])->name('employees.store');
        Route::get('employees/{id}/edit', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'edit'])->name('employees.edit');
        Route::put('employees/{id}', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'update'])->name('employees.update');
        Route::delete('employees/{id}', [\App\Http\Controllers\Parking\EmployeeParkingController::class, 'destroy'])->name('employees.destroy');

        // Map
        // Map (View is public for employees, but editing requires Admin)
        Route::get('map', [\App\Http\Controllers\Parking\ParkingMapController::class, 'index'])->name('map');
        
        Route::middleware('hams.report.access')->group(function () {
            Route::post('map/save-layout', [\App\Http\Controllers\Parking\ParkingMapController::class, 'saveLayout'])->name('map.save_layout');
            Route::post('map/add-slot', [\App\Http\Controllers\Parking\ParkingMapController::class, 'addSlot'])->name('map.add_slot');
            Route::delete('map/delete-slot/{slot_number}', [\App\Http\Controllers\Parking\ParkingMapController::class, 'deleteSlot'])->name('map.delete_slot');
            Route::post('map/add-element', [\App\Http\Controllers\Parking\ParkingMapController::class, 'addElement'])->name('map.add_element');
            Route::delete('map/delete-element/{id}', [\App\Http\Controllers\Parking\ParkingMapController::class, 'deleteElement'])->name('map.delete_element');
        });

        // Visitor Reservation
        Route::get('visitors/approvals', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'approvals'])->name('visitors.approvals');
        Route::get('visitors', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'index'])->name('visitors.index');
        Route::post('visitors/{id}/cancel', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'cancel'])->name('visitors.cancel');
        Route::post('visitors/{id}/toggle-lock', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'toggleLock'])->name('visitors.toggleLock');
        Route::post('visitors/{id}/check-in', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'checkIn'])->name('visitors.checkin');
        Route::post('visitors/{id}/check-out', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'checkout'])->name('visitors.checkout');
        
        Route::post('visitors/{id}/approve', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'approve'])->name('visitors.approve');
        Route::post('visitors/{id}/reject', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'reject'])->name('visitors.reject');
        Route::post('visitors/{id}/acknowledge', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'acknowledge'])->name('visitors.acknowledge');
    });

});

// Public Visitor Reservation Routes
Route::get('parking/visitors/create', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'create'])->name('parking.visitors.create');
Route::post('parking/visitors', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'store'])->middleware('throttle:5,1')->name('parking.visitors.store');

// Employee Reservation Routes
Route::get('parking/employee-reservations', [\App\Http\Controllers\Parking\EmployeeReservationController::class, 'index'])->name('parking.employee_reservations.index');
Route::get('parking/employee-reservations/create', [\App\Http\Controllers\Parking\EmployeeReservationController::class, 'create'])->name('parking.employee_reservations.create');
Route::post('parking/employee-reservations', [\App\Http\Controllers\Parking\EmployeeReservationController::class, 'store'])->name('parking.employee_reservations.store');
Route::get('parking/api/departments/{dept_id}/manager', [\App\Http\Controllers\Parking\EmployeeReservationController::class, 'getManager'])->name('parking.api.department_manager');

// Public Map Routes (for selectors and visitors)
Route::get('parking/map/full', [\App\Http\Controllers\Parking\ParkingMapController::class, 'mapFull'])->name('parking.map.full');
Route::get('parking/map/building', [\App\Http\Controllers\Parking\ParkingMapController::class, 'mapBuilding'])->name('parking.map.building');


// Public Visitor self-registration routes (accessed via QR Code)
Route::get('parking/register-visitor', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'guestCreate'])->name('parking.visitors.guestCreate');
Route::post('parking/register-visitor', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'guestStore'])->middleware('throttle:3,1')->name('parking.visitors.guestStore');
Route::get('parking/register-visitor/success/{id}', [\App\Http\Controllers\Parking\VisitorReservationController::class, 'guestSuccess'])->name('parking.visitors.guestSuccess');


Route::get('/profileUser', [UserController::class, 'profileUser'])->middleware('auth')->name('profileUser');
Route::post('/profile/update-avatar', [UserController::class, 'updateAvatar'])->middleware('auth')->name('users.update_avatar');

// Toggle Services Section on Welcome Page setting route
Route::post('backend/settings/toggle-services', function (\Illuminate\Http\Request $request) {
    $showServices = (bool) $request->input('show_services');
    
    $settings = \Illuminate\Support\Facades\Storage::exists('settings.json') ? json_decode(\Illuminate\Support\Facades\Storage::get('settings.json'), true) : [];
    $settings['show_services'] = $showServices;
    
    \Illuminate\Support\Facades\Storage::put('settings.json', json_encode($settings));
    return response()->json(['success' => true, 'message' => $showServices ? 'เปิดแสดงผลงานสนับสนุนและบริการแล้ว' : 'ปิดแสดงผลงานสนับสนุนและบริการแล้ว']);
})->middleware('auth')->name('backend.settings.toggle-services');

Route::post('backend/settings/save-systems', function (\Illuminate\Http\Request $request) {
    $showServices = (bool) $request->input('show_services', true);
    $disabled = [
        'office_supplies' => (bool) $request->input('office_supplies'),
        'car_booking' => (bool) $request->input('car_booking'),
        'employee_housing' => (bool) $request->input('employee_housing'),
        'parking_system' => (bool) $request->input('parking_system'),
        'central_data' => (bool) $request->input('central_data'),
    ];
    
    \Illuminate\Support\Facades\Storage::put('settings.json', json_encode([
        'show_services' => $showServices,
        'disabled_systems' => $disabled
    ]));
    
    return response()->json(['success' => true, 'message' => 'บันทึกการตั้งค่าระบบเรียบร้อยแล้ว']);
})->middleware('auth')->name('backend.settings.save-systems');

Route::post('backend/settings/toggle-parking-auto-reset', function (\Illuminate\Http\Request $request) {
    if (!\Illuminate\Support\Facades\Auth::user()->is_hams_admin) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }
    $enabled = filter_var($request->input('enabled'), FILTER_VALIDATE_BOOLEAN);
    
    $settings = \Illuminate\Support\Facades\Storage::exists('settings.json') ? json_decode(\Illuminate\Support\Facades\Storage::get('settings.json'), true) : [];
    $settings['parking_auto_reset'] = $enabled;
    
    \Illuminate\Support\Facades\Storage::put('settings.json', json_encode($settings));
    return response()->json(['success' => true, 'message' => $enabled ? 'เปิดระบบรีเซ็ตที่จอดรถอัตโนมัติแล้ว' : 'ปิดระบบรีเซ็ตที่จอดรถอัตโนมัติแล้ว', 'enabled' => $enabled]);
})->middleware('auth')->name('backend.settings.toggle-parking-auto-reset');

Route::get('/clear-cache-now', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Caches cleared successfully!';
});

require __DIR__ . '/auth.php';
