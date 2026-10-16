<?php

namespace Modules\Assurance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HomeService extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  const string IMAGE = 'image';
  protected $fillable = [
    'name',
    'price',
    'description',
    'status',
  ];

  protected $casts = [
    'price' => 'float',
    'status' => 'string',
  ];

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }

  public static function factory()
  {
    return \Modules\Assurance\Database\Factories\HomeServiceFactory::new();
  }
}
