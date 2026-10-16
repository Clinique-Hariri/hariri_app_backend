<?php

namespace Modules\Assurance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Assurance\Http\Resources\HomeServiceResource;
use Modules\Assurance\Services\HomeServiceService;

class HomeServiceApiController extends Controller
{
  use ApiResponseTrait;

  public function __construct(private readonly HomeServiceService $homeServiceService)
  {
  }

  public function index(Request $request)
  {
    $homeServices = $this->homeServiceService->list($request);

    return $this->successResponse(
      data: HomeServiceResource::collection($homeServices),
    );
  }
}
