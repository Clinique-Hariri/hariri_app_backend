<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Traits\FirebaseTrait;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class FirebaseController extends Controller
{
  use FirebaseTrait, ApiResponseTrait;

  public function IdToken(Request $request)
  {
    $this->validateRequest($request, [
      'uid' => 'required|string',
    ]);

    try {
      return $this->successResponse(data: $this->generateIdToken($request->uid));
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }

  }

  public function user(Request $request){
    $this->validateRequest($request, [
      'id_token' => 'required|string',
    ]);

    try {
      return $this->successResponse(data: $this->getFirebaseUser($request->id_token));
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

}
