<?php

namespace Modules\Orders\Models;

use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Modules\Coupons\Models\Coupon;
use Modules\Orders\Models\OrderItem;
use Modules\Locations\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  const string IMAGE = 'image';

  const string INVOICE = 'invoice';

  protected $fillable = [
    'user_id',
    'location_id',
    'coupon_id',
    'driver_id',
    'type',
    'phone',
    'status',
    'delivery_time',
    'note',
    'purchase_amount',
    'delivery_amount',
    'discount_amount',
    'total_amount',
    'invoice'
  ];

  protected $casts = [
    'delivery_time' => 'datetime',
    'delivery_amount' => 'decimal:2',
    'discount_amount' => 'decimal:2',
    'total_amount' => 'decimal:2',
    'purchase_amount' => 'decimal:2',
  ];

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();

    $this->addMediaCollection(self::INVOICE)
      ->singleFile();
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function location(): BelongsTo
  {
    return $this->belongsTo(Location::class);
  }

  public function coupon(): BelongsTo
  {
    return $this->belongsTo(Coupon::class);
  }

  public function driver(): BelongsTo
  {
    return $this->belongsTo(Driver::class, 'driver_id');
  }

  public function items(): HasMany
  {
    return $this->hasMany(OrderItem::class);
  }

  public function getWithPrescriptionAttribute()
  {
    if ($this->hasMedia(self::IMAGE)) {
      return true;
    }

    return false;
  }

  public function getLongitudeAttribute()
  {
    return $this->location?->longitude;
  }
  public function getLatitudeAttribute()
  {
    return $this->location?->latitude;
  }
  public function getAddressAttribute()
  {
    return $this->location?->address;
  }

  public function getIdentifierAttribute()
  {
    return "#{$this->id}";
  }

  public function getLocationUrlAttribute()
  {
    if ($this->location) {
      return "https://www.google.com/maps?q={$this->location->latitude},{$this->location->longitude}";
    }
    return null;
  }
}
