<?php

use Illuminate\Support\Facades\Route;
use Modules\Ads\Http\Controllers\AdsController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::resource('ads', AdsController::class);
});
