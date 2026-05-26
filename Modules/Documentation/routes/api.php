<?php

use Illuminate\Support\Facades\Route;
use Modules\Documentation\Http\Controllers\Api\DocumentationsApiController;
use Modules\Documentation\Http\Controllers\DocumentationController;

Route::group(['prefix' => 'v1'], function () {
  Route::get('documentations/{key}', [DocumentationsApiController::class, 'index']);
});
