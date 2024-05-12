<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
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
        Route::put('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });
});

Route::controller(CategoryController::class)->prefix('categories')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });
});

Route::controller(LocationController::class)->prefix('location')->middleware('auth:sanctum')->group(function () {
    Route::get('/{id}', 'show');
    Route::post('store', 'store');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::controller(ProductController::class)->prefix('products')->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'show');
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::post('store', 'store');
        Route::put('update/{id}', 'update');
        Route::delete('delete/{id}', 'destroy');
    });
});

Route::controller(OrderController::class)->prefix('orders')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', 'index');
        Route::get('show/{order}', 'show');
        Route::post('store', 'store');
    });

    Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {
        Route::get('/', 'index');
        Route::get('show/{order}', 'show');
        Route::get('get_order_items/{id}', 'getOrderItems');
        Route::get('get_user_orders/{id}', 'getUserOrders');
        Route::put('change_order_status/{id}', 'changeOrderStatus');
    });
});
