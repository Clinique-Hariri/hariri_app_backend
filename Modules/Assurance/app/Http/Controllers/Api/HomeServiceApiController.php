<?php

namespace Modules\Assurance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Assurance\Http\Requests\StoreHomeServiceRequest;
use Modules\Assurance\Http\Requests\UpdateHomeServiceRequest;
use Modules\Assurance\Http\Resources\HomeServiceResource;
use Modules\Assurance\Models\HomeService;
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

  public function show(HomeService $homeService)
  {
    return $this->successResponse(
      data: new HomeServiceResource($this->homeServiceService->find($homeService)),
    );
  }

  public function store(StoreHomeServiceRequest $request)
  {
    $homeService = $this->homeServiceService->create($request->validated());

    return $this->successResponse(
      message: __('assurance::app.home_service.created_successfully'),
      data: new HomeServiceResource($homeService),
      statusCode: 201,
    );
  }

  public function update(HomeService $homeService, UpdateHomeServiceRequest $request)
  {
    $homeService = $this->homeServiceService->update($homeService, $request->validated());

    return $this->successResponse(
      message: __('assurance::app.home_service.updated_successfully'),
      data: new HomeServiceResource($homeService),
    );
  }

  public function destroy(HomeService $homeService)
  {
    $this->homeServiceService->delete($homeService);

    return $this->successResponse(
      message: __('assurance::app.home_service.deleted_successfully'),
    );
  }
}
