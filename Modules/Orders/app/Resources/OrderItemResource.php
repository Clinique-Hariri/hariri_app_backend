<?php

namespace Modules\Orders\Resources;

use Illuminate\Http\Request;
use Modules\Orders\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Products\Resources\ProductResource;

class OrderItemResource extends JsonResource
{
    /** @mixin OrderItem */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => number_format($this->unit_price, 2),
            'final_price' => number_format($this->final_price, 2),
            'total_price' => number_format($this->total_price, 2),
            'product' => new ProductResource($this->product)
        ];
    }
}
