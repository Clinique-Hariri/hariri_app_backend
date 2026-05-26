<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\AppointmentsController;
use Modules\Doctors\Http\Controllers\DoctorsController;
use Modules\Doctors\Http\Controllers\SchedulesController;
use Modules\Doctors\Http\Controllers\SpecialitiesController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::resource('specialities', SpecialitiesController::class);
  Route::resource('doctors', DoctorsController::class);
  Route::resource('doctors/{doctorId}/schedules', SchedulesController::class)->names('doctors.schedules');
  Route::resource('appointments', AppointmentsController::class);
});
