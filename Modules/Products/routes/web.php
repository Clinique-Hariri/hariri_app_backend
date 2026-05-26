<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\UnitController;
use Modules\Products\Http\Controllers\StockController;
use Modules\Products\Http\Controllers\ProductController;
use Modules\Products\Http\Controllers\DiscountController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('units', UnitController::class)->names('units');
    Route::resource('products', ProductController::class)->names('products');
    Route::get('products/{product}/discounts', [DiscountController::class, 'index'])->name('discounts.index');
    Route::get('products/{product}/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::resource('discounts', DiscountController::class)->names('discounts')->except('index');
    Route::resource('stocks', StockController::class)->names('stocks')->except('index');
});
