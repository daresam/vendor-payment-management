<?php

use App\Http\Controllers\CorporateServiceController;
use App\Http\Controllers\InvoiceServiceController;
use App\Http\Controllers\VendorServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('corporates', CorporateServiceController::class);
Route::resource('vendors', VendorServiceController::class);
Route::resource('invoices', InvoiceServiceController::class);
// Route::resource('corporates', CorporateServiceController::class)->middleware('auth');
// Route::resource('vendors', VendorServiceController::class)->middleware('auth');
// Route::resource('invoices', InvoiceServiceController::class)->middleware('auth');