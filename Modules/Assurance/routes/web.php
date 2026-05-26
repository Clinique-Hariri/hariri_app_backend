<?php

use Illuminate\Support\Facades\Route;
use Modules\Assurance\Http\Controllers\ExaminationsController;
use Modules\Assurance\Http\Controllers\FamilyMembersController;
use Modules\Assurance\Http\Controllers\MedicalServicesController;
use Modules\Assurance\Http\Controllers\PatientsController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::resource('services', MedicalServicesController::class);
  Route::resource('patients/{userId}/family-members', FamilyMembersController::class)->names('patients.family-members');
  Route::resource('patients/{userId}/examinations', ExaminationsController::class)->names('patients.examinations');
  Route::resource('patients', PatientsController::class)->only(['index', 'show']);
  Route::get('patients/{userId}/add-funds', [PatientsController::class, 'addFunds'])->name('patients.add-funds');
  Route::post('patients/{userId}/add-funds', [PatientsController::class, 'addFundsAction'])->name('patients.add-funds');
  Route::get('patients/{userId}/activate-insurance', [PatientsController::class, 'activateInsurance'])->name('patients.activate-insurance');
  Route::post('patients/{userId}/activate-insurance', [PatientsController::class, 'activateInsuranceAction'])->name('patients.activate-insurance.action');
  Route::get('patients/{userId}/insurance-contract', [PatientsController::class, 'generateInsuranceContract'])->name('patients.generate-insurance-contract');
  Route::get('patients/{userId}/payment-receipt', [PatientsController::class, 'generatePaymentReceipt'])->name('patients.generate-payment-receipt');
  Route::get('patients/{userId}/insurance-card', [PatientsController::class, 'generateInsuranceCard'])->name('patients.generate-insurance-card');
  Route::get('patients/{userId}/family-members/{familyMember}/insurance-card', [FamilyMembersController::class, 'generateInsuranceCard'])->name('patients.family-members.generate-insurance-card');
  Route::get('patients/get-family-member-with-qr-code/{qrCode}', [FamilyMembersController::class, 'getFamilyMemberWithQrCode'])->name('patients.family-members.get-family-member-with-qr-code');
});
