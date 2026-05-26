<?php

namespace Modules\Assurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalExamination extends Model
{
  protected $fillable = [
    'total_price',
    'date',
    'is_paid',
    'family_member_id'
  ];

  protected $casts = [
    'total_price' => 'float',
    'date' => 'date'
  ];

  public function familyMember(): BelongsTo
  {
    return $this->belongsTo(FamilyMember::class);
  }

  public function examinationServices(): HasMany
  {
    return $this->hasMany(ExaminationService::class, 'medical_examination_id', 'id');
  }
}
