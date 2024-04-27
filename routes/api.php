<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(BrandController::class)->prefix('brands')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::post('store', 'store');
        Route::post('update/{id}', 'update');
        Route::get('delete/{id}', 'destroy');
    });
});
