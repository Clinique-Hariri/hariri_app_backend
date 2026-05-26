<?php

namespace Modules\Assurance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class FamilyMember extends Model
{
  use HasFactory;

  protected $fillable = [
    'firstname',
    'lastname',
    'birthdate',
    'phone',
    'gender',
    'blood_type',
    'avatar',
    'family_relation',
    'insurance_number',
    'status',
    'user_id',
  ];

  protected $casts = [
    'birthdate' => 'date',
    'status' => 'string',
  ];
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function medicalExaminations(): HasMany
  {
    return $this->hasMany(MedicalExamination::class);
  }

  public function getFullnameAttribute()
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  public function getAvatarUrlAttribute()
  {
    return $this->avatar && Storage::disk('public')->exists($this->avatar)
      ? Storage::disk('public')->url($this->avatar)
      : $this->avatar;
  }

  public static function generateUniqueInsuranceNumber(): string
  {
    do {
      // Example format: 202506180001 (date + random number)
      $insuranceNumber = now()->format('Ymd') . random_int(1000, 9999);
    } while (self::where('insurance_number', $insuranceNumber)->exists());

    return $insuranceNumber;
  }
}
