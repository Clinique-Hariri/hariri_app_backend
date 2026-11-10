<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\DeviceTokenService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\User\DeviceTokenResource;

class DeviceTokenController extends Controller
{
  use ApiResponseTrait;

  public function __construct(private DeviceTokenService $deviceTokens)
  {
  }

  public function store(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'token' => 'required|string|max:512',
      'platform' => 'nullable|string|in:android,ios,web',
    ]);

    try {

      $deviceToken = $this->deviceTokens->register(
        $request->user(),
        $validated['token'],
        $validated['platform'] ?? null,
      );

      return $this->successResponse(
        message: 'Device token registered.',
        data: new DeviceTokenResource($deviceToken),
      );

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function destroy(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'token' => 'required|string|max:512',
    ]);

    try {

      $deleted = $this->deviceTokens->unregister($request->user(), $validated['token']);

      if (!$deleted) {
        return $this->errorResponse('Device token not found.', 404);
      }

      return $this->successResponse('Device token removed.');

    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }
}
