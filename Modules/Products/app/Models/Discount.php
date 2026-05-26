<?php

namespace Modules\Products\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Products\Models\Product;

class Discount extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'discount',
    'start_date',
    'end_date',
  ];

  protected $casts = [
    'discount' => 'float',
    'start_date' => 'date',
    'end_date' => 'date',
  ];

  public function product(): BelongsTo
  {
    return $this->belongsTo(Product::class);
  }

}
