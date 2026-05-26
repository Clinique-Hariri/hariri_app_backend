<?php

namespace Modules\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Coupons\Constants\Status;

class Coupon extends Model {

    protected $fillable = [
      'name',
      'code',
      'discount',
      'start_date',
      'end_date',
      'status',
      'max_uses',
      'usage_count',
    ];

    protected function casts(): array
    {
      return [
        'start_date' => 'date',
        'end_date' => 'date',
      ];
    }

    protected function getUsesCountAttribute(){
      return 0;
    }

    protected function scopeActive($query)
    {
        return $query->where('status', Status::ACTIVE)
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now())
                     ->where(function($query) {
                         $query->whereNull('max_uses')
                               ->orWhereColumn('usage_count', '<', 'max_uses');
                     });
    }
}
