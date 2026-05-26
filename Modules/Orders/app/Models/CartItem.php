<?php

namespace Modules\Orders\Models;

use Modules\Orders\Models\Cart;
use Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'cart_id',
    'quantity',
  ];

  protected $casts = [
    'quantity' => 'integer',
  ];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  public function cart(): BelongsTo
  {
    return $this->belongsTo(Cart::class);
  }

  public function getUnitPriceAttribute(){
    return $this->product->price;
  }

  public function getFinalPriceAttribute(){
    return $this->product->final_price;
  }

  public function getTotalPriceAttribute(){
    return $this->quantity * $this->final_price;
  }

}
