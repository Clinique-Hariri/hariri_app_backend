<?php

use Illuminate\Support\Facades\Route;
use Modules\Assurance\Http\Controllers\Api\AssuranceApiController;
use Modules\Assurance\Http\Controllers\Api\HomeCheckupApiController;
use Modules\Assurance\Http\Controllers\Api\HomeServiceApiController;

Route::group(['prefix' => 'v1'], function () {
  Route::get('home-services', [HomeServiceApiController::class, 'index']);
  Route::get('home-checkups/visit-types', [HomeCheckupApiController::class, 'visitTypes']);

  Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('family-members', [AssuranceApiController::class, 'getFamilyMembers']);
    Route::get('account-statement', [AssuranceApiController::class, 'getAccountStatement']);
    Route::get('examinations', [AssuranceApiController::class, 'getExaminations']);
    Route::get('transactions', [AssuranceApiController::class, 'getTransactions']);

    Route::get('home-checkups', [HomeCheckupApiController::class, 'index']);
    Route::post('home-checkups', [HomeCheckupApiController::class, 'store']);
    Route::patch('home-checkups/{homeCheckup}/status', [HomeCheckupApiController::class, 'updateStatus']);
    Route::delete('home-checkups/{homeCheckup}', [HomeCheckupApiController::class, 'destroy']);
  });
});
