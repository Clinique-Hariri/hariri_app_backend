<?php

namespace Modules\Products\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
  use HasFactory, HasGoogleTranslationTrait;

  protected $fillable = [
    'name',
  ];

  protected array $translatable = [
    'name',
  ];
  protected $casts = [
    'name' => 'array',
  ];

  public static function factory()
  {
    return \Modules\Products\database\factories\UnitFactory::new();
  }
}
