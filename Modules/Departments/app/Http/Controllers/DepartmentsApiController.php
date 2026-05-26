<?php

namespace Modules\Departments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Ads\Http\Resources\AdResource;
use Modules\Ads\Models\Ad;
use Modules\Departments\Constants\Status;
use Modules\Departments\Models\Department;
use Modules\Departments\Resource\DepartmentResource;

class DepartmentsApiController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request)
  {
    $model = Department::where('status', Status::ACTIVE)->get();

     return $this->successResponse(
       data: DepartmentResource::collection($model)
     );
  }
}
