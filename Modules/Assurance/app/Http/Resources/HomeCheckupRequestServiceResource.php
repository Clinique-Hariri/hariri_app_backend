<?php

namespace Modules\Assurance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\HomeCheckupRequestService;

/** @mixin HomeCheckupRequestService */
class HomeCheckupRequestServiceResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'request_id' => $this->request_id,
      'home_service_id' => $this->home_service_id,
      'service_name' => $this->service_name,
      'service_price' => $this->service_price,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
