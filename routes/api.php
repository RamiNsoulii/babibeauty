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

/// ========================
// Protected routes
// ========================
Route::middleware('auth:sanctum')->group(function () {

    // ---------- Auth ----------
    Route::post('/logout', [AuthController::class, 'logout']);

    // ---------- Admin-only ----------
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('categories', CategoryController::class);
    });

    // ---------- Admin + Expert ----------
    Route::middleware('role:admin,expert')->group(function () {
        Route::apiResource('beauty-experts', BeautyExpertController::class);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('product-images', ProductImageController::class);

        // Expert self-service (only for experts)
        Route::middleware('role:expert')->group(function () {
            Route::get('/my-expert-profile/bookings', [BeautyExpertController::class, 'myBookings']);
            Route::get('/my-expert-profile/reviews', [BeautyExpertController::class, 'myReviews']);
            Route::put('/my-expert-profile', [BeautyExpertController::class, 'updateMyProfile']);
        });
    });

    // ---------- Admin + Customer ----------
    Route::middleware('role:admin,customer')->group(function () {
        // Reviews for products
        Route::get('/products/{product}/reviews', [ReviewController::class, 'indexForProduct']);
        Route::post('/products/{product}/reviews', [ReviewController::class, 'storeForProduct']);

        // Reviews for beauty experts
        Route::get('/beauty-experts/{beautyExpert}/reviews', [ReviewController::class, 'indexForExpert']);
        Route::post('/beauty-experts/{beautyExpert}/reviews', [ReviewController::class, 'storeForExpert']);

        // Bookings + Orders
        Route::apiResource('bookings', BookingController::class);
        Route::apiResource('orders', OrderController::class);
    });
});
