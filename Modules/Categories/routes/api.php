<?php

use Illuminate\Support\Facades\Route;
use Modules\Categories\Http\Controllers\CategoriesApiController;
use Modules\Categories\Http\Controllers\CategoriesController;

Route::group(['prefix' => 'v1'], function () {
  Route::post('categories', [CategoriesApiController::class, 'index'])->name('ads.index');
});
