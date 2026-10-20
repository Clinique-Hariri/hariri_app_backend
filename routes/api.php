<?php

use App\Http\Controllers\Synchronization\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\FirebaseController;
use App\Http\Controllers\API\NotificationsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/get-access-token', [FcmController::class, 'getAccessToken']);
Route::post('/send-to-device', [FcmController::class, 'sendToDevice']);
Route::post('/send-to-multiple', [FcmController::class, 'sendToMultiple']);
Route::post('/send-to-topic', [FcmController::class, 'sendToTopic']);
Route::post('/send-to-condition', [FcmController::class, 'sendToCondition']);
Route::post('/send-data-message', [FcmController::class, 'sendDataMessage']);
Route::post('/send-custom-message', [FcmController::class, 'sendCustomMessage']);

Route::prefix('v1')->group(function () {
  //synchronization routes
  Route::middleware('verify.system.key')->post('synch/orders', [OrderController::class, 'store']);


  Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
      'status' => 1,
      'message' => 'success',
      'data' => new \App\Http\Resources\User\UserResource($request->user())
    ]);
  });

  Route::get('settings', [SettingController::class, 'index']);
  Route::post('settings', [SettingController::class, 'store']);

  Route::prefix('firebase')->group(function () {
    Route::post('id-token', [FirebaseController::class, 'idToken']);
    Route::post('user', [FirebaseController::class, 'user']);
  });

  Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('delete-account', [AuthController::class, 'deleteAccount'])->middleware('auth:sanctum');
    Route::post('update-account', [AuthController::class, 'updateAccount'])->middleware('auth:sanctum');
  });

  Route::prefix('home')->group(function () {
    Route::get('pharmacy', [HomeController::class, 'pharmacy'])->middleware('api.guest');
  });


  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::get('/notifications/unread', [NotificationsController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markAsRead']);
    Route::post('/notifications/test', [NotificationsController::class, 'sendTestNotification']);
  });

  Route::middleware('api.guest')->group(function () {
    Route::get('/counts', function () {
      $user = auth()->user();
      return response()->json([
        'status' => 1,
        'message' => 'success',
        'data' => [
          'notifications' => $user?->notifications()->whereNull('read_at')->count() ?? 0,
          'orders' => $user?->order_count ?? 0,
          'appointements' => $user?->appointment_count ?? 0,
        ]
      ]);
    });
  });
});
