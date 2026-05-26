<?php

namespace Modules\Assurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExaminationService extends Model implements HasMedia
{
  use InteractsWithMedia;
  protected $table = 'examination_service';

  const IMAGE = 'image';

  protected $fillable = [
    'service_name',
    'service_price',
    'medical_service_id',
    'medical_examination_id'
  ];

  protected $casts = [
    'service_price' => 'float',
  ];

  public function medicalService(): BelongsTo
  {
    return $this->belongsTo(MedicalService::class);
  }

  public function medicalExamination(): BelongsTo
  {
    return $this->belongsTo(MedicalExamination::class);
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }
}
