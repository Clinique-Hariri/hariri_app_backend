<?php

namespace Modules\Orders\Models;

use App\Models\User;
use Modules\Orders\Models\CartItem;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  const string IMAGE = 'image';
  protected $fillable = [
    'user_id',
    'status'
  ];

  protected $casts = [
    'status' => 'string',
  ];

  public function user(){
    return $this->belongsTo(User::class);
  }

  public function items(){
    return $this->hasMany(CartItem::class);
  }

  public function getTotalAttribute()
  {
    return $this->items->sum(function ($item) {
      return $item->total_price;
    });
  }

  public function getTotalQuantityAttribute(){
    return $this->items->sum(function ($item) {
      return $item->quantity;
    });
  }

}
