<?php

use App\Http\Controllers\CorporateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('corporates', CorporateController::class);
Route::resource('vendors', VendorController::class);

Route::get('/invoice/filter/{vendorId}', [InvoiceController::class, 'filterInvoice'])->name('filter.invoice');
Route::get('/invoice/create/{vendorId}', [InvoiceController::class, 'createInvoice'])->name('create.invoice');
Route::get('/invoice/create/corporate/{corpId}/bulk', [InvoiceController::class, 'createBulkInvoice'])->name('create.bulkInvoice');
Route::post('/invoice/create/corporate/{corpId}/bulk', [InvoiceController::class, 'storeBulkInvoice'])->name('store.bulkInvoice');

Route::get('/invoice/{id}/vendor/{vendorId}', [InvoiceController::class, 'showInvoice'])->name('show.invoice');
Route::get('/invoice/{id}/vendor/{vendorId}/edit', [InvoiceController::class, 'editInvoice'])->name('edit.invoice');

Route::resource('invoices', InvoiceController::class);

// require __DIR__.'/auth.php';
