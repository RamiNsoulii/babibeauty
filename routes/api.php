<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeautyExpertController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users
    Route::apiResource('users', UserController::class);

    // Beauty Experts
    Route::apiResource('beauty-experts', BeautyExpertController::class);

    // Brands
    Route::apiResource('brands', BrandController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Products
    Route::apiResource('products', ProductController::class);

    // Product Images
    Route::apiResource('product-images', ProductImageController::class);

    // Reviews
    Route::apiResource('reviews', ReviewController::class);

    // Bookings
    Route::apiResource('bookings', BookingController::class);

    // Orders
    Route::apiResource('orders', OrderController::class);
});
