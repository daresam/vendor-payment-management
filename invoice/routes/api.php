<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ServiceAccountController;
use Illuminate\Support\Facades\Route;

// Invoice Routes
Route::post('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'store']);
Route::post('/corporate/{corp_id}/invoices/bulk', [InvoiceController::class, 'bulkStore']);
Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'index']);
Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'show']);
Route::put('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'update']);

Route::post('service-accounts/token', [ServiceAccountController::class, 'issueToken']);
