<?php

namespace Modules\Assurance\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Assurance\Constants\HomeCheckup\VisitType;
use Modules\Assurance\Http\Requests\StoreHomeCheckupRequest;
use Modules\Assurance\Http\Requests\UpdateHomeCheckupStatusRequest;
use Modules\Assurance\Http\Resources\HomeCheckupResource;
use Modules\Assurance\Models\HomeCheckup;
use Modules\Assurance\Services\HomeCheckupService;

class HomeCheckupApiController extends Controller
{
  use ApiResponseTrait;

  public function __construct(private readonly HomeCheckupService $homeCheckupService)
  {
  }

  public function index(Request $request)
  {
    $homeCheckups = $this->homeCheckupService->list($request, auth()->user());

    return $this->successResponse(
      data: HomeCheckupResource::collection($homeCheckups),
    );
  }

  public function store(StoreHomeCheckupRequest $request)
  {
    $homeCheckup = $this->homeCheckupService->create(auth()->user(), $request->validated());

    return $this->successResponse(
      message: __('assurance::app.home_checkup.created_successfully'),
      data: new HomeCheckupResource($homeCheckup),
      statusCode: 201,
    );
  }

  public function updateStatus(HomeCheckup $homeCheckup, UpdateHomeCheckupStatusRequest $request)
  {
    $data = $request->validated();

    $homeCheckup = $this->homeCheckupService->updateStatus(
      $homeCheckup,
      auth()->user(),
      $data['status'],
      $data['rejection_reason'] ?? null,
    );

    return $this->successResponse(
      message: __('assurance::app.home_checkup.status_updated'),
      data: new HomeCheckupResource($homeCheckup),
    );
  }

  public function destroy(HomeCheckup $homeCheckup)
  {
    $this->homeCheckupService->delete($homeCheckup, auth()->user());

    return $this->successResponse(
      message: __('assurance::app.home_checkup.deleted_successfully'),
    );
  }

  public function visitTypes()
  {
    $types = collect(VisitType::all(true))->map(fn ($label, $type) => [
      'type' => $type,
      'name' => $label,
      'default_price' => VisitType::get_price($type),
    ])->values();

    return $this->successResponse(data: $types);
  }
}
