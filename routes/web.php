<?php
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\MagazineController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ReportController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::fallback(function () {
     return view('errors.404');
});

Route::get('/login', function () {
    return redirect()->route('login');
});


Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', RolesController::class);

// Permissions
Route::resource('permissions', PermissionsController::class);

// Users
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id?}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/delete/{user}', [UserController::class, 'delete'])->name('destroy');
    Route::get('/update/status/{user_id}/{status}', [UserController::class, 'updateStatus'])->name('status');
    Route::post('/password-update/{Id?}', [UserController::class, 'passwordupdate'])->name('passwordupdate');
    Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
    Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('upload');
    Route::get('export/', [UserController::class, 'export'])->name('export');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('magazine', MagazineController::class);
    Route::post('magazine/bulk-delete', [MagazineController::class, 'bulkDelete'])->name('magazine.bulk-delete');
    Route::post('magazine/toggle-status', [MagazineController::class, 'toggleStatus'])->name('magazine.toggle-status');
});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::post('customer/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('customer.bulk-delete');
    Route::post('customer/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customer.toggle-status');
    Route::get('admin/customer-subscriptions', [CustomerController::class, 'subscriptionTabs'])->name('customer.subscriptions');

});


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('plan', [PlanController::class, 'index'])->name('plan.index');
    Route::post('plan', [PlanController::class, 'store'])->name('plan.store');
    Route::get('plan/{id}/edit', [PlanController::class, 'edit'])->name('plan.edit');
    Route::put('plan/{id}', [PlanController::class, 'update'])->name('plan.update');
    Route::delete('plan/{id}', [PlanController::class, 'destroy'])->name('plan.destroy');
    Route::post('plan/bulk-delete', [PlanController::class, 'bulkDelete'])->name('plan.bulk-delete');
    Route::post('plan/toggle-status', [PlanController::class, 'toggleStatus'])->name('plan.toggle-status');
});


// user login log history 
Route::prefix('admin')->middleware(['auth'])->group(function () 
{
    Route::get('/customers', [ReportController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{customer_id}/login-history', [ReportController::class, 'loginHistory'])->name('admin.customers.loginHistory');
    Route::get('/customers/login-history/{customer_id}', [ReportController::class, 'loginHistoryAjax'])
        ->name('admin.customers.loginHistoryAjax');

});

// user wise magazine view history  
Route::prefix('admin')->middleware(['auth:web'])->group(function () {
    Route::get('/reports/user-wise-pdf-views', [ReportController::class, 'userWisePdfViews'])
        ->name('admin.reports.userWisePdfViews');

    Route::get('/reports/user-wise-pdf-views/{customer_id}', [ReportController::class, 'userPdfViewsDetail'])
        ->name('admin.reports.userPdfViewsDetail');
});


// magazine view by users history 
Route::prefix('admin')->middleware(['auth:web'])->group(function () {

    Route::get('/reports/magazine-wise-pdf-views', [ReportController::class, 'magazineWisePdfViews'])
        ->name('admin.reports.magazineWisePdfViews');

    Route::get('/reports/magazine-wise-pdf-views/{magazine_id}', [ReportController::class, 'magazinePdfViewsDetail'])
        ->name('admin.reports.magazinePdfViewsDetail');
});
