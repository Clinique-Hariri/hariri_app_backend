<?php

namespace App\Http\Controllers\Synchronization;

use App\Http\Controllers\Controller;
use App\Models\IdempotencyKey;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Orders\Constants\OrderType;
use Modules\Orders\Models\Order;

class OrderController extends Controller
{
  use ApiResponseTrait;

  public function store(Request $request)
  {
    // ===== Read Idempotency Key =====
    $idempotencyKey = $request->header('X-IDEMPOTENCY-KEY');

    if (!$idempotencyKey) {
      return response()->json([
        'message' => 'Missing Idempotency Key'
      ], 400);
    }

    // ===== Check if key exists and return cached response =====
    $existingKey = IdempotencyKey::where('key', $idempotencyKey)->first();

    if ($existingKey && $existingKey->response) {
      return response()->json($existingKey->response, 200);
    }

    // ===== If not exists, create placeholder =====
    if (!$existingKey) {
      IdempotencyKey::create([
        'key' => $idempotencyKey,
        'response' => null,
      ]);
    }

    // ===== Validate Request =====
    $this->validateRequest($request, [
      'phone' => 'nullable|string|max:10',
      'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    try {
      DB::beginTransaction();

      $order = Order::create([
        'type' => OrderType::ONLINE,
        'phone' => $request->phone ?? null,
      ]);

      if ($request->has(Order::IMAGE)) {
        storeWebPWithSpatie($order, $request->file(Order::IMAGE), Order::IMAGE);
      }

      DB::commit();

      // ===== Build API Response =====
      $response = [
        'message' => 'Order created',
        'order_id' => $order->id,
      ];

      // ===== Save Response in Idempotency Table =====
      IdempotencyKey::where('key', $idempotencyKey)->update([
        'response' => $response
      ]);

      return response()->json($response, 201);

    } catch (Exception $e) {

      DB::rollBack();
      return $this->errorResponse($e->getMessage());
    }
  }
}
