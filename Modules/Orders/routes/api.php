<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Http\Controllers\Api\CartController;
use Modules\Orders\Http\Controllers\Api\OrderController;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('cart')->middleware('auth:sanctum')->group(function () {
        Route::get('get', [CartController::class, 'get'])->name('cart.get');
        Route::post('update', [CartController::class, 'update'])->name('cart.update');
        Route::get('delete', [CartController::class, 'delete'])->name('cart.delete');
        Route::post('info', [CartController::class, 'info'])->name('cart.info');
    });

    Route::prefix('order')->middleware('auth:sanctum')->group(function () {
        Route::post('create', [OrderController::class, 'create'])->name('orders.store');
        Route::post('get', [OrderController::class, 'get'])->middleware('record.visits')->name('orders.index');
        Route::post('show', [OrderController::class, 'show'])->name('order.show');
    });
});
