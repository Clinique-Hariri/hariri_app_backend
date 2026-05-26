<?php

namespace Modules\Orders\Resources;

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
      //'type' => $this->type,
      'phone' => $this->phone,
      'longitude' => $this->longitude,
      'latitude' => $this->latitude,
      'address' => $this->address,
      'status' => $this->status,
      //'delivery_time' => $this->delivery_time?->format('Y-m-d H:i:s'),
      //'note' => $this->note,
      'purchase_amount' => number_format($this->purchase_amount, 2),
      'delivery_amount' => number_format($this->delivery_amount, 2),
      'discount_amount' => number_format($this->discount_amount, 2),
      'total_amount' => number_format($this->total_amount, 2),
      'with_prescription' => $this->with_prescription,
      'invoice' => $this->getFirstMediaUrl(Order::INVOICE),
      'image' => $this->getFirstMediaUrl(Order::IMAGE),
      'created_at' => $this->created_at->format('Y-m-d H:i:s'),
      'items_count' => $this->items()->count(),
      'items' => $this->when($request->routeIs('api.order.show'), function () {
        return OrderItemResource::collection($this->items);
      }),
    ];
  }
}
