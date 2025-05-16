<?php

use App\Http\Controllers\ServiceAccountController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

 // Vendor Routes
 Route::prefix('/corporate/vendor')->group(function () {
    Route::post('/', [VendorController::class, 'store']);
    Route::get('/', [VendorController::class, 'index']);
    Route::put('/{id}', [VendorController::class, 'update']);
});

Route::post('service-accounts/token', [ServiceAccountController::class, 'issueToken']);