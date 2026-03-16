<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [PageController::class, 'home']);
Route::post('/booking', [PageController::class, 'storeBooking']);
Route::post('/contact', [PageController::class, 'storeContact']);
Route::get('/cart', [PageController::class, 'cart']);
Route::get('/checkout', [PageController::class, 'checkout']);         // ← TAMBAH
Route::post('/checkout', [PageController::class, 'placeOrder']);      // ← TAMBAH
Route::get('/order-confirmed/{id}', [PageController::class, 'orderConfirmed']); // ← TAMBAH

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus']);
    Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking']);
    Route::get('/menus', [AdminController::class, 'menus']);
    Route::post('/menus', [AdminController::class, 'storeMenu']);
    Route::delete('/menus/{id}', [AdminController::class, 'deleteMenu']);
    Route::get('/menus/{id}/edit', [AdminController::class, 'editMenu']);
    Route::put('/menus/{id}', [AdminController::class, 'updateMenu']);
});