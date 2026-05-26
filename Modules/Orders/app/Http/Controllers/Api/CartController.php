<?php

namespace Modules\Orders\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Orders\Http\Rules\ValidCoupon;
use Modules\Orders\Resources\CartResource;
use Modules\Orders\Services\AmountCalculatorService;

class CartController extends Controller
{
  use ApiResponseTrait;
  public function update(Request $request){
    $this->validateRequest($request, [
      'product_id' => 'required|exists:products,id',
      'quantity' => 'required|integer'
    ]);

    try{

      DB::beginTransaction();

      $user = auth()->user();
      $cart = $user->cart();


        if($request->quantity == 0 ){
          $cart->items()->where('product_id',$request->product_id)->delete();
        }else{
          $cart->items()->updateOrCreate(
            ['product_id' => $request->product_id],
            ['quantity' => $request->quantity]
          );
        }

      DB::commit();

      $cart->refresh();

      return $this->successResponse(data: new CartResource($cart));

    }catch(Exception $e){
      DB::rollBack();
      return $this->errorResponse($e->getMessage());
    }
  }

  public function delete(Request $request){

    try{

      $user = auth()->user();
      $cart = $user->cart();

      $cart->delete();

      return $this->successResponse();

    }catch(Exception $e){
      return $this->errorResponse($e->getMessage());
    }
  }

  public function get(Request $request){

    try{

      $user = auth()->user();
      $cart = $user->cart();
      $cart->load('items.product');

      return $this->successResponse(data: new CartResource($cart));

    }catch(Exception $e){
      return $this->errorResponse($e->getMessage());
    }
  }

  public function info(Request $request)
  {
    $this->validateRequest($request, [
      'code' => 'sometimes|nullable',
      'longitude' => 'sometimes|nullable',
      'latitude' => 'sometimes|nullable',
    ]);

    $user = auth()->user();
    $cart = $user->cart();
    $cart->load('items.product');

    $calculator = new AmountCalculatorService($cart->items);

    if ($request->has('longitude', 'latitude')) {
        $calculator->calculateDeliveryAmount($request->only('longitude', 'latitude'));
    }

    if ((new ValidCoupon())->passes('code',$request->code)) {
        $calculator->applyCoupon($request->code);
    }

    return $this->successResponse(data: [
        'purchase_amount' => number_format($calculator->getPurchaseAmount(), 2),
        'distance_amount' => number_format($calculator->getDistanceAmount(), 2),
        'delivery_amount' => number_format($calculator->getDeliveryAmount(), 2),
        'discount_amount' => number_format($calculator->getDiscountAmount(), 2),
        'total_amount' => number_format($calculator->getTotalAmount(), 2),
        'total_quantity' => $cart->total_quantity
    ]);
}
}
