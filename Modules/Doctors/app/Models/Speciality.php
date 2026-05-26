<?php

namespace Modules\Doctors\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Doctors\database\factories\SpecialityFactory;

class Speciality extends Model
{
  use HasFactory, HasGoogleTranslationTrait;

  protected $fillable = [
    'name',
    'status',
  ];

  protected array $translatable = [
    'name',
  ];

  protected $casts = [
    'name' => 'array',
  ];

  public function doctors(): HasMany
  {
    return $this->hasMany(Doctor::class);
  }

  public static function factory()
  {
    return SpecialityFactory::new();
  }
}
