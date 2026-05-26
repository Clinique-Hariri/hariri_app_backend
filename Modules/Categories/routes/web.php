<?php

use Illuminate\Support\Facades\Route;
use Modules\Categories\Http\Controllers\CategoriesController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('categories', CategoriesController::class);
});
