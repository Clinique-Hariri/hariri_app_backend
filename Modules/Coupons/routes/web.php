<?php

use Illuminate\Support\Facades\Route;
use Modules\Coupons\Http\Controllers\CouponController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::resource('coupons', CouponController::class);
});
