<?php

use Illuminate\Support\Facades\Route;
use Modules\Departments\Http\Controllers\DepartmentsApiController;
use Modules\Departments\Http\Controllers\DepartmentsController;

Route::group(['prefix' => 'v1'], function () {
  Route::get('departments', [DepartmentsApiController::class, 'index'])->name('ads.index');
});
