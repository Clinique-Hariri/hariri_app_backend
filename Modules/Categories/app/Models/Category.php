<?php

namespace Modules\Categories\Models;

use Spatie\MediaLibrary\HasMedia;
use Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasGoogleTranslationTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia, HasGoogleTranslationTrait;

  const string IMAGE = 'image';
  protected $fillable = [
    'name',
    'status',
  ];

  protected array $translatable = [
    'name',
  ];
  protected $casts = [
    'name' => 'array',
    'status' => 'string',
  ];

  public static function factory()
  {
    return \Modules\Categories\database\factories\CategoryFactory::new();
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }

  public function products(){
    return $this->hasMany(Product::class);
  }

  public function getProductsCountAttribute(){
    return $this->products()->count();
  }
}
