<?php

namespace Modules\Doctors\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
  use HasFactory;

  protected $fillable = [
    'patient_name',
    'patient_phone',
    'patient_age',
    'date',
    'time',
    'status',
    'doctor_id',
    'user_id',
  ];

  protected $casts = [
    'date' => 'date',
    'time' => 'datetime:H:i',
  ];

  public function doctor(): BelongsTo
  {
    return $this->belongsTo(Doctor::class);
  }

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }
}
