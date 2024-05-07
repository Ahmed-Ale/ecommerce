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
    // Authenticated routes for order management
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', 'index'); // List orders (usually for users)
        Route::get('/{order}', 'show'); // View specific order details
        Route::post('/', 'store'); // Create a new order (typically from checkout)
    });

    // Admin-specific routes (assuming roles or permissions for authorization)
    Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
        Route::get('/all', 'getAllOrders'); // List all orders (for admins)
        Route::put('/{order}', 'update'); // Update order details (e.g., status)
        Route::delete('/{order}', 'destroy'); // Delete an order (usually for admins)
    });
});
