<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketTypeMasterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ManagerController;



use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\JobWorkProductController;
use App\Http\Controllers\IpBlockController;
use App\Http\Controllers\RangeController;
use App\Http\Controllers\JobWorkProductDetailController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\OrderInCartController;
use App\Http\Controllers\ProductOrderController;
use App\Http\Controllers\JobWorkOrderController;
use App\Http\Controllers\EmployeeOrderInCartController;
use App\Http\Controllers\EmployeeJobWorkOrderController;
use App\Http\Controllers\EmployeeProductOrderController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Routes
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'getProfile'])->name('detail');
    Route::get('/edit', [HomeController::class, 'EditProfile'])->name('EditProfile');
    Route::post('/update', [HomeController::class, 'updateProfile'])->name('update');
    Route::post('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Roles
Route::resource('roles', App\Http\Controllers\RolesController::class);

// Permissions
Route::resource('permissions', App\Http\Controllers\PermissionsController::class);

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

//Ticket Master
Route::prefix('admin')->name('tickettype.')->middleware('auth')->group(function () {
    Route::get('/ticket/index', [TicketTypeMasterController::class, 'index'])->name('index');
    Route::get('/ticket/create', [TicketTypeMasterController::class, 'createview'])->name('create');
    Route::post('/ticket/store', [TicketTypeMasterController::class, 'create'])->name('store');
    Route::get('/ticket/edit/{Id?}', [TicketTypeMasterController::class, 'editview'])->name('edit');
    Route::post('/ticket/update', [TicketTypeMasterController::class, 'update'])->name('update');
    Route::delete('/ticket/delete', [TicketTypeMasterController::class, 'delete'])->name('delete');

    Route::any('/Pending-Ticket', [TicketController::class, 'adminshowpendingticket'])->name('adminshowpendingticket');
});

//Add New Ticket
Route::prefix('employee')->name('ticket.')->middleware('auth')->group(function () {
    Route::get('/ticket/index', [TicketController::class, 'index'])->name('index');
    Route::get('/ticket/create', [TicketController::class, 'createview'])->name('create');
    Route::post('/ticket/store', [TicketController::class, 'create'])->name('store');
    Route::get('/ticket/edit/{id?}', [TicketController::class, 'editview'])->name('edit');
    Route::post('/ticket/update/{id}', [TicketController::class, 'update'])->name('update');
    Route::delete('/ticket/delete', [TicketController::class, 'delete'])->name('delete');
    Route::any('/ticket/Pending-Ticket', [TicketController::class, 'pendingticket'])->name('pendingticket');
});


//Manager Show Ticket
Route::prefix('manager')->name('managerticket.')->middleware('auth')->group(function () {
    Route::any('/ticket/Pending-Ticket', [ManagerController::class, 'pendingticket'])->name('pendingticket');
});
