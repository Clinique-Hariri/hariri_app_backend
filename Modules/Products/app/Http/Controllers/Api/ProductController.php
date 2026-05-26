<?php

namespace Modules\Products\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

use App\Http\Controllers\Controller;
use Modules\Products\Models\Product;
use Modules\Products\Constants\Status;
use Modules\Products\Resources\ProductResource;


class ProductController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request)
  {
    $model = Product::with('discount','cartItem')
    ->where('status', Status::ACTIVE);

    if($request->has('category_id')){
      $model = $model->where('category_id', $request->category_id);
    }

    if($request->has('search')){
      $model = $model->where(function($query) use ($request){
        $query->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($request->search) . '%'])
        ->orWhereRaw("LOWER(scientific_name) LIKE ?", ['%' . strtolower($request->search) . '%']);
      });
    }

     return $this->successResponse(
       data: ProductResource::collection($model->paginate(10))
     );
  }

  public function recents()
  {
    return $this->successResponse(
      data: ProductResource::collection(Product::recent()->limit(10)->get())
    );
  }
}
