<?php

use Illuminate\Support\Facades\Route;
use Modules\Ads\Http\Controllers\AdsApiController;

Route::group(['prefix' => 'v1'], function () {
    Route::get('ads', [AdsApiController::class, 'index'])->name('ads.index');
});
