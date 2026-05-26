<?php

namespace Modules\Orders\Resources\web;

use Illuminate\Http\Request;
use Modules\Orders\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
  /** @mixin Order */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'identifier' => $this->identifier,
      'phone' => $this->phone,
      'address' => $this->address,
      'purchase_amount' => $this->purchase_amount,
      'delivery_amount' => $this->delivery_amount,
      'discount_amount' => $this->discount_amount,
      'total_amount' => $this->total_amount,
      'created_at' => $this->created_at->format('Y-m-d H:i')
    ];
  }
}
