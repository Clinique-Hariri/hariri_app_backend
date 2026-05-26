<?php

namespace Modules\Doctors\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Departments\Models\Department;
use Modules\Doctors\database\factories\DoctorFactory;

class Doctor extends Model
{
  use HasFactory;

  protected $fillable = [
    'firstname',
    'lastname',
    'avatar',
    'bio',
    'display_label',
    'sort_order',
    'status',
    'speciality_id',
    'department_id',
  ];

  public function speciality(): BelongsTo
  {
    return $this->belongsTo(Speciality::class);
  }

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }

  public function schedules(): HasMany
  {
    return $this->hasMany(DoctorSchedule::class);
  }

  public function appointments(): HasMany
  {
    return $this->hasMany(Appointment::class);
  }

  public function getNextAvailableDate(): ?string
  {

    $workingDays = $this->schedules()->pluck('day_of_week')->toArray();
    $date = now();

    for ($i = 0; $i < 60; $i++) {
      $day = strtolower($date->format('l'));
      if (in_array($day, $workingDays)) {
        return $date->toDateString();
      }
      $date->addDay();
    }

    return null;
  }


  public function getFullnameAttribute(): string
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  public function getAvatarUrlAttribute(): string
  {
    return asset('storage/' . $this->avatar);
  }

  public static function getNextSortOrder(): int
  {
    $maxSortOrder = self::max('sort_order');
    return $maxSortOrder ? $maxSortOrder + 1 : 1;
  }

  public static function adjustSortOrder(?int $oldSort, ?int $newSort): void
  {
    // No change needed if values are the same or both null
    if ($oldSort === $newSort) return;

    // Case 1: INSERT
    if (is_null($oldSort) && !is_null($newSort)) {
      if (self::where('sort_order', $newSort)->exists()) {
        self::where('sort_order', '>=', $newSort)
          ->increment('sort_order');
      }
      return;
    }

    // Case 2: DELETE
    if (!is_null($oldSort) && is_null($newSort)) {
      self::where('sort_order', '>', $oldSort)
        ->decrement('sort_order');
      return;
    }

    // Case 3: UPDATE
    if (!is_null($oldSort) && !is_null($newSort)) {
      // If the new value is empty (gap), don’t touch others
      if (!self::where('sort_order', $newSort)->exists()) {
        return;
      }

      if ($newSort > $oldSort) {
        self::where('sort_order', '>', $oldSort)
          ->where('sort_order', '<=', $newSort)
          ->decrement('sort_order');
      } else {
        self::where('sort_order', '>=', $newSort)
          ->where('sort_order', '<', $oldSort)
          ->increment('sort_order');
      }
    }
  }

  public static function factory()
  {
    return DoctorFactory::new();
  }
}
