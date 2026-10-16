<?php

namespace Modules\Assurance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Locations\Models\Location;

class HomeCheckup extends Model
{
  use HasFactory;

  protected $table = 'home_checkup_requests';

  protected $fillable = [
    'user_id',
    'location_id',
    'patient_number',
    'patient_fullname',
    'patient_phone',
    'patient_birthdate',
    'notes',
    'preferred_date',
    'preferred_time',
    'status',
    'visit_type',
    'reviewed_at',
    'rejection_reason',
    'base_price',
    'total_price',
  ];

  protected $casts = [
    'patient_number' => 'string',
    'patient_fullname' => 'string',
    'patient_phone' => 'string',
    'patient_birthdate' => 'date',
    'preferred_date' => 'date',
    'preferred_time' => 'string',
    'status' => 'string',
    'visit_type' => 'string',
    'reviewed_at' => 'datetime',
    'base_price' => 'float',
    'total_price' => 'float',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function location(): BelongsTo
  {
    return $this->belongsTo(Location::class);
  }

  public function services(): HasMany
  {
    return $this->hasMany(HomeCheckupRequestService::class, 'request_id', 'id');
  }

  public static function factory()
  {
    return \Modules\Assurance\Database\Factories\HomeCheckupFactory::new();
  }
}
