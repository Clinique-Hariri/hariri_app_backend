<?php

use Illuminate\Support\Facades\Route;
use Modules\Doctors\Http\Controllers\Api\AppointmentsApiController;
use Modules\Doctors\Http\Controllers\Api\DoctorsApiController;
use Modules\Doctors\Http\Controllers\Api\SpecialitiesApiController;

Route::group(['prefix' => 'v1'], function () {
  Route::get('specialities', [SpecialitiesApiController::class, 'index'])->name('specialities.index');

  Route::get('doctors', [DoctorsApiController::class, 'index'])->name('specialities.doctors');
  Route::get('doctors/{doctorId}', [DoctorsApiController::class, 'show'])->name('doctors.show');
  Route::get('specialities/{specialityId}/doctors', [DoctorsApiController::class, 'getDoctorsBySpeciality'])->name('specialities.doctors');
  Route::get('departments/{departmentId}/doctors', [DoctorsApiController::class, 'getDoctorsByDepartment'])->name('departments.doctors');

  Route::get('doctors/{doctorId}/schedules', [DoctorsApiController::class, 'getDoctorSchedules'])->name('doctors.schedules');
  Route::get('doctors/{doctorId}/availability', [DoctorsApiController::class, 'getAvailability']);

  Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('appointments', [AppointmentsApiController::class, 'index'])->name('appointments.index');
    Route::post('appointments', [AppointmentsApiController::class, 'store'])->name('appointments.store');
  });
});
