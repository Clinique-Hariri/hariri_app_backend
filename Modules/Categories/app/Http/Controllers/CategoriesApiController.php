<?php

namespace Modules\Categories\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Categories\Constants\Status;
use Modules\Categories\Models\Category;
use Modules\Categories\Resources\CategoryResource;

class CategoriesApiController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request)
  {
    $model = Category::where('status', Status::ACTIVE)
    ->whereHas('products');

    if($request->has('search')){

      $model = $model->where(function($query) use ($request) {
        $query->whereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ['%' . strtolower($request->search) . '%'])
              ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.fr')) LIKE ?", ['%' . strtolower($request->search) . '%'])
              ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.ar')) LIKE ?", ['%' . strtolower($request->search) . '%']);
      });
    }

     return $this->successResponse(
       data: CategoryResource::collection($model->paginate(10))
     );
  }
}
