<?php

namespace Modules\Documentation\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentationResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Ads\Http\Resources\AdResource;
use Modules\Ads\Models\Ad;
use Modules\Documentation\Models\Documentation;

class DocumentationsApiController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request, $key)
  {
    $model = Documentation::where('key', $key)->first();

    if (!$model) {
      return $this->errorResponse(
        message: 'documentation_not_found',
        statusCode: 404
      );
    }

    return $this->successResponse(
      data: new DocumentationResource($model),
    );
  }
}
