<?php

namespace Modules\Orders\Resources\web;

use Illuminate\Http\Request;
use Modules\Orders\Models\OrderItem;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /** @mixin OrderItem */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'name' => $this->product->name,
            'barcode' => $this->product->barcode,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'final_price' => $this->final_price,
            'total_price' => $this->total_price,
        ];
    }
}
