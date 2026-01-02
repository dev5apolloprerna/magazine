<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerPasswordController;
use App\Http\Controllers\Api\MagazineApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('customer-login', [CustomerAuthController::class, 'login']);


Route::post('customer-register', [CustomerAuthController::class, 'register']);

Route::post('customer/forgot-password', [CustomerPasswordController::class, 'forgot']);
Route::post('customer/reset-password',  [CustomerPasswordController::class, 'reset']);


Route::middleware('auth:api')->group(function () {
    Route::post('magazines', [MagazineApiController::class, 'index']);          // list
    Route::post('magazines/detail', [MagazineApiController::class, 'show']);      // detail
});
