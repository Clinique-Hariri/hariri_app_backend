<?php

use Illuminate\Support\Facades\Route;
use Modules\Documentation\Http\Controllers\DocumentationController;

  Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::resource('documentations', DocumentationController::class)->only('index', 'store');
});
