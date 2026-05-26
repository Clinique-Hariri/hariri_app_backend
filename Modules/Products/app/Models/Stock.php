<?php

namespace Modules\Products\Models;

use Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'purchase_price',
    'quantity',
    'type',
    'expiration_date',
    'batch_number'
  ];

  protected $casts = [
    'purchase_price' => 'float',
    'quantity' => 'integer',
    'expiration_date' => 'date',
  ];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

  public static function factory()
  {
    return \Modules\Products\database\factories\StockFactory::new();
  }

  protected static function booted(): void
  {
      static::saved(function (Stock $stock) {
          if ($stock->quantity === 0) {
              $stock->delete();
          }
      });
  }

}
