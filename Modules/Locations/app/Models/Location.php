<?php

namespace Modules\Locations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'address',
    'longitude',
    'latitude',
    'status'
  ];

  protected $casts = [
    'longitude' => 'float',
    'latitude' => 'float',
  ];

  /**
   * Get the user that owns the location.
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public static function factory()
  {
    return \Modules\Locations\Database\Factories\LocationFactory::new();
  }
}
