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


// Public (no login required)

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{id}', [BrandController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/beauty-experts', [BeautyExpertController::class, 'index']);
Route::get('/beauty-experts/{id}', [BeautyExpertController::class, 'show']);


// Auth (login/register)

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Protected (requires login)

Route::middleware('auth:sanctum')->group(function () {

    
    Route::post('/logout', [AuthController::class, 'logout']);

    // ---------- Admin-only ----------
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);

        Route::post('/brands', [BrandController::class, 'store']);
        Route::put('/brands/{id}', [BrandController::class, 'update']);
        Route::delete('/brands/{id}', [BrandController::class, 'destroy']);

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::apiResource('product-images', ProductImageController::class);
        Route::apiResource('beauty-experts', BeautyExpertController::class)->except(['index', 'show']);
    });

    // ---------- Expert-only ----------
    Route::middleware('role:expert')->group(function () {
        Route::get('/my-expert-profile/bookings', [BeautyExpertController::class, 'myBookings']);
        Route::get('/my-expert-profile/reviews', [BeautyExpertController::class, 'myReviews']);
        Route::put('/my-expert-profile', [BeautyExpertController::class, 'updateMyProfile']);
    });

    // ---------- Customer-only ----------
    Route::middleware('role:customer')->group(function () {
        // Reviews for products
        Route::get('/products/{product}/reviews', [ReviewController::class, 'indexForProduct']);
        Route::post('/products/{product}/reviews', [ReviewController::class, 'storeForProduct']);
        Route::put('/products/{product}/reviews/{review}', [ReviewController::class, 'updateForProduct']);

        // Reviews for beauty experts
        Route::get('/beauty-experts/{beautyExpert}/reviews', [ReviewController::class, 'indexForExpert']);
        Route::post('/beauty-experts/{beautyExpert}/reviews', [ReviewController::class, 'storeForExpert']);
        Route::put('/beauty-experts/{beautyExpert}/reviews/{review}', [ReviewController::class, 'updateForExpert']);

        // Delete a review for a product
        Route::delete('/products/{product}/reviews/{review}', [ReviewController::class, 'destroyForProduct']);
        Route::delete('/beauty-experts/{beautyExpert}/reviews/{review}', [ReviewController::class, 'destroyForExpert']);

        // Bookings + Orders
        Route::apiResource('bookings', BookingController::class);
        Route::apiResource('orders', OrderController::class);
    });

});
