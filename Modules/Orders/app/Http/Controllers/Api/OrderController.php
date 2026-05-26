<?php

namespace Modules\Orders\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Modules\Orders\Models\Order;
use Illuminate\Support\Facades\DB;
use Modules\Coupons\Models\Coupon;
use App\Http\Controllers\Controller;
use Modules\Orders\Models\OrderItem;
use Modules\Locations\Constants\Status;
use Modules\Orders\Constants\OrderType;
use Modules\Orders\Constants\OrderStatus;
use Modules\Orders\Http\Rules\ValidCoupon;
use Modules\Orders\Resources\OrderResource;
use Modules\Orders\Services\AmountCalculatorService;
use Modules\Orders\Jobs\GenerateInvoiceJob;

class OrderController extends Controller
{
  use ApiResponseTrait;

  public function create(Request $request)
  {
    $this->validateRequest($request, [
      'location_id' => 'nullable|exists:locations,id',
      'coupon_code' => 'nullable|exists:coupons,code',
      'longitude' => 'required_unless:location_id,|numeric',
      'latitude' => 'required_unless:location_id,|numeric',
      'address' => 'required_unless:location_id,|string',
      'phone' => 'required|string|max:10',
      'delivery_time' => 'nullable|date',
    ]);

    try {
      DB::beginTransaction();

      $user = auth()->user();
      $cart = $user->cart();
      $cart->load('items.product');

      $locationId = $request->has('location_id')
        ? $request->location_id
        : $user->locations()->create([
          'longitude' => $request->longitude,
          'latitude' => $request->latitude,
          'address' => $request->address,
          'status' => Status::INACTIVE
        ])->id;

      $couponId = (new ValidCoupon())->passes('coupon_code', $request->coupon_code)
        ? Coupon::where('code', $request->coupon_code)->first()->id
        : null;

      $order = Order::create([
        'user_id' => $user->id,
        'location_id' => $locationId,
        'coupon_id' => $couponId,
        'type' => OrderType::ONLINE,
        'phone' => $request->phone,
        'delivery_time' => $request->delivery_time,
      ]);

      if ($request->has(ORDER::IMAGE)) {
        storeWebPWithSpatie($order, $request->file(ORDER::IMAGE), ORDER::IMAGE);
      } else {
        $cartItems = $cart->items;
        $orderItems = [];
        foreach ($cartItems as $item) {
          $orderItems[] = [
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'final_price' => $item->final_price,
            'total_price' => $item->total_price,
            'created_at' => now(),
            'updated_at' => now()
          ];
        }
        OrderItem::insert($orderItems);

        $calculator = new AmountCalculatorService(collect($cartItems));

        if ($request->has('longitude', 'latitude')) {
          $calculator->calculateDeliveryAmount($request->only('longitude', 'latitude'));
        }

        if ($order->coupon) {
          $calculator->applyCoupon($order->coupon->code);
        }

        $order->update([
          'purchase_amount' => $calculator->getPurchaseAmount(),
          'delivery_amount' => $calculator->getDeliveryAmount(),
          'discount_amount' => $calculator->getDiscountAmount(),
          'total_amount' => $calculator->getTotalAmount(),
        ]);


        $order->refresh();
        $cart->delete();
        GenerateInvoiceJob::dispatch($order);

      }

      DB::commit();

      return $this->successResponse(data: new OrderResource($order));

    } catch (Exception $e) {
      DB::rollBack();
      return $this->errorResponse($e->getMessage());
    }
  }

  public function get(Request $request)
  {
    try {
      $this->validateRequest($request, [
        'status' => ['sometimes', 'nullable', 'in:' . implode(',', OrderStatus::all())]
      ]);

      $user = auth()->user();

      $orders = Order::with(['items.product', 'location'])
        ->where('user_id', $user->id)
        ->orderBy('updated_at', 'DESC');

      if ($request->has('status')) {
        $orders->where('status', $request->status);
      }

      return $this->successResponse(data: OrderResource::collection($orders->paginate(10)));


    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

  public function show(Request $request)
  {
    try {
      $this->validateRequest($request, [
        'id' => 'nullable|exists:orders,id',
      ]);

      $user = auth()->user();


      $order = Order::with(['items.product', 'location'])
        ->where('user_id', $user->id)
        ->findOrFail($request->id);

      return $this->successResponse(data: new OrderResource($order));


    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }
}
