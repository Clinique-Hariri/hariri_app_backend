<?php

namespace App\Http\Controllers\API;

use Exception;
use Modules\Ads\Models\Ad;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Modules\Products\Models\Product;
use Modules\Categories\Models\Category;
use Modules\Ads\Http\Resources\AdResource;
use Modules\Products\Resources\ProductResource;
use Modules\Categories\Resources\CategoryResource;


class HomeController extends Controller
{
  use ApiResponseTrait;

  public function pharmacy(Request $request)
  {

    try {

      $data = [
        'ads' => AdResource::collection(Ad::inRandomOrder()->take(5)->get()),
        'categories' => CategoryResource::collection(Category::inRandomOrder()->take(5)->get()),
        'products' => ProductResource::collection(Product::with('discount','cartItem')->inRandomOrder()->take(10)->get()),
      ];

      return $this->successResponse(data: $data);


    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }


  }

}
