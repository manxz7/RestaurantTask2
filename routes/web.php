<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;

// Public routes
Route::get('/', [PageController::class, 'home']);
Route::post('/booking', [PageController::class, 'storeBooking']);
Route::post('/contact', [PageController::class, 'storeContact']);

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/bookings', [AdminController::class, 'bookings']);
    Route::post('/bookings/{id}/status', [AdminController::class, 'updateBookingStatus']);
    Route::delete('/bookings/{id}', [AdminController::class, 'deleteBooking']);
});