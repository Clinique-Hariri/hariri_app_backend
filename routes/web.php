<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\Auth\ForgotPassword;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\front_pages\HelpCenterArticle;
use App\Http\Controllers\Dashboard\Roles\RolesController;
use App\Http\Controllers\Dashboard\Users\UsersController;
use App\Http\Controllers\front_pages\DocumentationController;
use App\Http\Controllers\Dashboard\Settings\SettingController;
use App\Http\Controllers\Dashboard\Permissions\PermissionsController;
use App\Http\Controllers\Dashboard\Notifications\NotificationsController;


// locale
Route::get('/{locale}', function ($locale) {
    session()->put('locale', $locale);
    return redirect()->back();
})->where('locale', 'en|fr|ar');

Route::get('/downloadApp', function () { 
    return view('front-pages.downloadApp');
});

Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
Route::get('/pages/misc-comingsoon', [MiscComingSoon::class, 'index'])->name('pages-misc-comingsoon');
Route::get('/pages/misc-not-authorized', [MiscNotAuthorized::class, 'index'])->name('unauthorized');

// Front Pages
Route::get('/', [Landing::class, 'index'])->name('front-pages-landing');
Route::get('/privacy-policy', [DocumentationController::class, 'privacyPolicy']);
Route::get('/about-us', [DocumentationController::class, 'aboutUs']);
Route::get('/delete-account', [DocumentationController::class, 'deleteAccount']);

// Auth Routes
Route::group(['prefix' => 'admin'], function () {

  Route::get('login', [LoginController::class, 'index'])->middleware('guest')->name('login');
  Route::post('login', [LoginController::class, 'action'])->middleware('guest')->name('login.action');
  Route::get('forgot-password', [ForgotPassword::class, 'index'])->middleware('guest')->name('reset-password');
  Route::get('logout', [LogoutController::class, 'action'])->middleware('auth')->name('logout');
  Route::get('register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
  Route::post('register', [RegisterController::class, 'action'])->middleware('guest')->name('register.action');

  Route::group(['middleware' => ['auth']], function () {
    //navbar routes
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');


    Route::get('/dashboard', function () {
      return view('dashboard.index');
    })->name('dashboard');

    Route::get('/', function () {
      return redirect()->route('dashboard');
    })->name('home');

    Route::resource('roles', RolesController::class);

    Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [PermissionsController::class, 'update'])->name('permissions.update');

    Route::resource('settings', SettingController::class)->only('index','store');

    Route::resource('users', UsersController::class)->except(['show']);
    Route::post('users/update-status', [UsersController::class, 'updateStatus'])->name('users.status.update');

    Route::get('send-notification', [NotificationsController::class, 'index'])->name('send-notification');
    Route::post('send-notification', [NotificationsController::class, 'send'])->name('send-notification.send');
  });
});
