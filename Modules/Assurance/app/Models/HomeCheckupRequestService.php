<?php

namespace Modules\Assurance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeCheckupRequestService extends Model
{
  protected $table = 'home_checkup_request_services';

  protected $fillable = [
    'request_id',
    'home_service_id',
    'service_name',
    'service_price',
  ];

  protected $casts = [
    'service_price' => 'float',
  ];

  public function request(): BelongsTo
  {
    return $this->belongsTo(HomeCheckup::class, 'request_id', 'id');
  }

  public function homeService(): BelongsTo
  {
    return $this->belongsTo(HomeService::class, 'home_service_id', 'id');
  }
}
