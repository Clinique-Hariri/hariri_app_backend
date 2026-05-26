<?php

namespace Modules\Products\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

use App\Http\Controllers\Controller;
use Modules\Products\Models\Product;
use Modules\Products\Constants\Status;
use Modules\Products\Resources\ProductResource;


class AlternativeController extends Controller
{
  use ApiResponseTrait;
  public function index(Request $request)
  {

    $this->validateRequest($request, [
      'product_id' => 'required|exists:products,id'
    ]);

    $product = Product::find($request->product_id);

    $model = $product->alternateProducts()
      ->where('status', Status::ACTIVE);


    return $this->successResponse(
      data: ProductResource::collection($model->paginate(10))
    );
  }
}
