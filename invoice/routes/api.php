<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Invoice Routes
Route::post('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'store']);
Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice', [InvoiceController::class, 'index']);
Route::get('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'show']);
Route::put('/corporate/{corp_id}/vendor/{vendor_id}/invoice/{invoice_id}', [InvoiceController::class, 'update']);