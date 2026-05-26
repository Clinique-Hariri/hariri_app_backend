<?php

namespace Modules\Products\Models;

use Spatie\MediaLibrary\HasMedia;
use Modules\Orders\Models\CartItem;
use Modules\Orders\Models\OrderItem;
use Modules\Products\Constants\Type;
use Illuminate\Database\Eloquent\Model;
use Modules\Categories\Models\Category;
use App\Traits\HasGoogleTranslationTrait;
use Modules\Orders\Constants\OrderStatus;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
  use InteractsWithMedia, HasFactory, HasGoogleTranslationTrait;

  const string Image = 'image';
  const string Gallery = 'gallery';
  protected $fillable = [
    'category_id',
    'unit_id',
    'name',
    'scientific_name',
    'barcode',
    'price',
    'unit_count',
    'description',
    'status',
    'is_compensable',
    'with_prescription'
  ];

  protected $casts = [
    'price' => 'float',
    'unit_count' => 'integer',
    'status' => 'string',
    'is_compensable' => 'boolean',
    'with_prescription' => 'boolean',
  ];

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::Image)
      ->singleFile();

    $this->addMediaCollection(self::Gallery);
  }

  public function category(): BelongsTo
  {
    return $this->belongsTo(Category::class);
  }

  public function unit(): BelongsTo
  {
    return $this->belongsTo(Unit::class);
  }

  public function discounts()
  {
    return $this->hasMany(Discount::class);
  }

  public function discount()
  {
    return $this->hasOne(Discount::class)
      ->where(function($query) {
        $query->whereNull('start_date')
          ->orWhere('start_date', '<=', now());
      })
      ->where(function($query) {
        $query->whereNull('end_date')
          ->orWhere('end_date', '>=', now());
      })
      ->oldestOfMany();
  }

  public function stocks()
  {
    return $this->hasMany(Stock::class);
  }

  public function stock()
  {
      return $this->hasOne(Stock::class)
          ->where('quantity', '>', 0)
          ->orderByRaw("CASE WHEN type = '".Type::RETURN."' THEN 0 ELSE 1 END")
          ->oldestOfMany('created_at');
  }

  public function getIsDiscountedAttribute()
  {
    if ($this->relationLoaded('discount')) {
      return !empty($this->discount);
    }
    return $this->discount()->exists();
  }

  public function getDiscountAmountAttribute()
  {
    return $this->discount?->discount ?? 0;
  }

  public function getFinalPriceAttribute()
  {
    return round($this->price * (1 - $this->discount_amount / 100), 2);
  }

  public function getPurchasePriceAttribute(){
    return round($this->stocks()->latest()->first()?->purchase_price ?? $this->price, 2);
  }

  public function cartItem()
  {
    return $this->hasOne(CartItem::class)->where('cart_id', auth()->user()?->cart()?->id);
  }

  public function orderItems()
  {
    return $this->hasMany(OrderItem::class);
  }

  public function getCartQuantityAttribute()
  {
    return $this->cartItem?->quantity ?? 0;
  }

  public function getInCartAttribute()
  {
    return boolval($this->cart_quantity);
  }

  public function getStockQuantityAttribute()
  {
    return intval($this->stocks()->sum('quantity'));
  }

  public function getOrderedQuantityAttribute()
  {
    return intval($this->orderItems()
      ->whereHas('order', function($query) {
        $query->whereNotIn('status', [
          OrderStatus::DELIVERED,
          OrderStatus::CANCELED
        ]);
      })
      ->sum('order_items.quantity'));
  }

  public function getAvailableQuantityAttribute(){
    return $this->stock_quantity - $this->ordered_quantity;
  }

  public function reminders()
  {
    return $this->hasMany(Reminder::class);
  }

  public function alternatives()
  {
    return $this->hasMany(Alternative::class, 'product_id');
  }

  public function isAlternativeFor()
  {
    return $this->hasMany(Alternative::class, 'alternate_product_id');
  }

  public function alternateProducts()
  {
    return $this->hasManyThrough(Product::class,Alternative::class,'product_id','id','id','alternate_product_id');
  }

  public static function recent(){
    return self::where('created_at', '>=', now()->subWeek())->inRandomOrder();
  }

  public static function factory()
  {
    return \Modules\Products\database\factories\ProductFactory::new();
  }
}
