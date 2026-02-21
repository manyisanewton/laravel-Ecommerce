<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::post('/payments/mpesa/callback', [WebhookController::class, 'mpesa']);
Route::post('/payments/flutterwave/callback', [WebhookController::class, 'flutterwave']);
Route::post('/payments/pesapal/callback', [WebhookController::class, 'pesapal']);

Route::middleware(['auth:api', 'activity.log'])->group(function (): void {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/orders/my', [CheckoutController::class, 'myOrders']);

    Route::middleware('role:admin')->prefix('admin')->group(function (): void {
        Route::get('/users', [AdminController::class, 'users']);
        Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);

        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::get('/orders', [AdminController::class, 'orders']);

        Route::get('/reports/users', [ReportController::class, 'users']);
        Route::get('/reports/orders', [ReportController::class, 'orders']);
        Route::get('/reports/activity', [ReportController::class, 'activity']);
        Route::get('/reports/export/{type}', [ReportController::class, 'export']);
    });
});
