<?php

namespace Modules\Orders\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Orders\Constants\DriverStatus;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  const string IMAGE = 'image';

  protected $fillable = [
    'name',
    'phone',
  ];

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function getStatusAttribute($value)
  {
    if($this->orders()->whereNot('status', 'delivered')->exists()){
      return DriverStatus::BUSY;
    }

    return DriverStatus::FREE;
  }
}
