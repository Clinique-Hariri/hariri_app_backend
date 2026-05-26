<?php

namespace Modules\Assurance\Models;

use App\Traits\HasGoogleTranslationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MedicalService extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  const string IMAGE = 'image';
  protected $fillable = [
    'name',
    'price',
    'status',
  ];

  protected $casts = [
    'price' => 'float',
    'status' => 'string',
  ];

  public function ExaminationServices(): HasMany
  {
    return $this->hasMany(ExaminationService::class, 'medical_service_id', 'id');
  }

  public static function factory()
  {
    return \Modules\Assurance\Database\Factories\MedicalServiceFactory::new();
  }
}
