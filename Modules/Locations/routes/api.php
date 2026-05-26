<?php

use Illuminate\Support\Facades\Route;
use Modules\Locations\Http\Controllers\LocationController;

Route::group(['prefix' => 'v1'], function () {
  Route::middleware('auth:sanctum')->group(function () {
    Route::post('locations', [LocationController::class, 'index']);
    Route::post('location/create', [LocationController::class, 'create']);
    Route::post('location/update', [LocationController::class, 'update']);
    Route::post('location/delete', [LocationController::class, 'delete']);
  });
});
