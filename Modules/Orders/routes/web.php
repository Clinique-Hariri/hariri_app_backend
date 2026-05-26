<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Http\Controllers\OrderController;
use Modules\Orders\Http\Controllers\DriverController;
use Modules\Orders\Http\Controllers\OrderItemController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::resource('orders', OrderController::class);
  Route::resource('drivers', DriverController::class);
  Route::post('orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
/*   Route::get('order/{id}/items/index', [OrderItemController::class, 'index'])->name('order-items.index');
  Route::post('order/{id}/items/update', [OrderItemController::class, 'update'])->name('order-items.update'); */
});



