<?php

use App\Http\Controllers\Api\BeautyExpertController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


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
