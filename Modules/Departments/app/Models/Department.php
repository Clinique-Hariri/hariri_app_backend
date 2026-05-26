<?php

namespace Modules\Departments\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Doctors\Models\Doctor;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Department extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia, HasGoogleTranslationTrait;

  const string IMAGE = 'image';
  protected $fillable = [
    'name',
    'description',
    'status',
  ];

  protected array $translatable = [
    'name',
  ];
  protected $casts = [
    'name' => 'array',
    'status' => 'string',
  ];

  public function doctors(): HasMany
  {
    return $this->hasMany(Doctor::class);
  }

  public static function factory()
  {
    return \Modules\Departments\database\factories\DepartmentFactory::new();
  }
}
