<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/user/register', [UserController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/token-revoke', [AuthController::class, 'tokenRevoke']);
    Route::put('/auth/token-refresh', [AuthController::class, 'tokenRefresh']);

    // Get Logged In User
    Route::get('/user', [AuthController::class, 'getLoggedInUser']);

    // Vendor Routes
    Route::prefix('/corporate/vendor')->group(function () {
        Route::post('/', [VendorController::class, 'store']);
        Route::get('/', [VendorController::class, 'index']);
        Route::put('/{id}', [VendorController::class, 'update']);
    });

    // Corporate Routes
    Route::post('/corporate', [CorporateController::class, 'store']);
    Route::get('/corporate', [CorporateController::class, 'index']);
    Route::get('/corporate/{id}', [CorporateController::class, 'show']);

    // Invoice Routes
    Route::post('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'store']);
    Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'index']);
    Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'show']);
    Route::put('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'update']);
});
