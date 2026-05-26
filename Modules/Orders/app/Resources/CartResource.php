<?php

namespace Modules\Orders\Resources;
use Illuminate\Http\Request;
use Modules\Orders\Models\Cart;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
  /** @mixin Cart */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'total' => number_format($this->total, 2),
      'items_count' => $this->items()->count(),

      'items' => $this->when($request->routeIs('api.cart.get'), function(){
        return CartItemResource::collection($this->items);
      })
    ];
  }
}
