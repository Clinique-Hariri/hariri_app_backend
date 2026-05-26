<?php

use Illuminate\Support\Facades\Route;
use Modules\Departments\Http\Controllers\DepartmentsController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('departments', DepartmentsController::class)->names('departments');
});
