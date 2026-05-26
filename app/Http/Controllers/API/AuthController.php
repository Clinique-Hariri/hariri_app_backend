<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Constants\Gender;
use App\Traits\RandomTrait;
use App\Constants\BloodType;
use Illuminate\Http\Request;
use App\Traits\FirebaseTrait;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;

class AuthController extends Controller
{
  use FirebaseTrait, ApiResponseTrait, RandomTrait;

  public function login(Request $request)
  {

    $this->validateRequest($request, [
      'id_token' => 'required|string',
      'device_token' => 'sometimes|string',
    ]);

    try {

      $firebase_user = $this->getFirebaseUser($request->id_token);

      $user = User::firstOrCreate(
        ['email' => $firebase_user->email],
        [
          'firstname' => $firebase_user->displayName ?? 'user#' . $this->random(8,'numbers'),
          'phone' => $firebase_user->phoneNumber,
          'avatar' => $firebase_user->photoUrl,
        ]
      )->refresh();

      $user->update([
        'device_token' => $request->device_token?? null,
      ]);

      $token = $user->createToken($this->random(8))->plainTextToken;

      return $this->successResponse(data: [
        'token' => $token,
        'user' => new UserResource($user),
      ]);

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }


  }

  public function logout(Request $request)
  {
    try {

      $request->user()->currentAccessToken()->delete();

      return $this->successResponse('Logged out.');

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }

  }

  public function deleteAccount(Request $request)
  {
    try {

      $user = $request->user();
      $user->tokens()->delete();
      $user->delete();

      return $this->successResponse('Account deleted.');

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }

  }

  public function updateAccount(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      //'email' => 'required|email|unique:users,email,' . auth()->id(),
      'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:users,phone,' . auth()->id(),
      'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
      'birthdate' => 'nullable|date',
      'full_address' => 'nullable|string',
      'gender' => 'required|string|in:' . implode(',', Gender::all()),
      'blood_type' => 'nullable|string|in:' . implode(',', array_keys(BloodType::all()))
    ]);

    try {
      DB::beginTransaction();

      $user = auth()->user();


      if ($request->hasFile('avatar')) {
        $validated['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/avatars');
      }

      $user->update($validated);

      DB::commit();

      return $this->successResponse(data: new UserResource($user));

    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage());
    }
  }
}
