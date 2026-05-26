<?php

namespace Modules\Orders\Resources\web;

use Illuminate\Http\Request;
use Modules\Products\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
{
  /** @mixin Product */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'category_id' => $this->category_id,
      'unit_id' => $this->unit_id,
      'name' => $this->name,
      'image' => $this->getFirstMediaUrl(Product::Image),
      'category' => $this->category?->name,
      'price' => number_format($this->price, 2),
      'unit_name' => $this->unit?->name,
      'unit_count' => $this->unit_count,
      'unit_type' => $this->unit_count . ' ' . $this->unit?->name,
      'status' => $this->status,
      'barcode' => $this->barcode,
      'description' => $this->description,
      'is_compensable' => $this->is_compensable,
      'with_prescription' => $this->with_prescription,
      'is_discounted' => $this->is_discounted,
      'discount_amount' => $this->discount_amount,
      'purchase_price' => $this->purchase_price,
      'final_price' => $this->final_price,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
