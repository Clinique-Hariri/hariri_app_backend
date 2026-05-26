<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchases\Http\Controllers\PurchaseController;
use Modules\Purchases\Http\Controllers\SupplierController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update']);
});
