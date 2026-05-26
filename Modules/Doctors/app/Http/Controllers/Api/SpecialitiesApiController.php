<?php

namespace Modules\Doctors\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Modules\Doctors\Constants\Speciality\Status;
use Modules\Doctors\Http\Resources\SpecialityResource;
use Modules\Doctors\Models\Speciality;

class SpecialitiesApiController extends Controller
{
  use ApiResponseTrait;
  public function index()
  {
    $model = Speciality::where('status', Status::ACTIVE)->get();
    return $this->successResponse(
      data: SpecialityResource::collection($model)
    );
  }
}
