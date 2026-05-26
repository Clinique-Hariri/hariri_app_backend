<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\Api\ProductController;
use Modules\Products\Http\Controllers\Api\AlternativeController;

Route::group(['prefix' => 'v1'], function () {

  Route::middleware('api.guest')->group(function () {
    Route::post('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('recents', [ProductController::class, 'recents'])->name('recents.index');
    Route::post('alternatives', [AlternativeController::class, 'index'])->name('alternatives.index');
  });

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('reminders', [\Modules\Products\Http\Controllers\Api\ReminderController::class, 'index']);
    Route::post('reminder/create', [\Modules\Products\Http\Controllers\Api\ReminderController::class, 'store']);
    Route::post('reminder/delete', [\Modules\Products\Http\Controllers\Api\ReminderController::class, 'destroy']);
  });
});

