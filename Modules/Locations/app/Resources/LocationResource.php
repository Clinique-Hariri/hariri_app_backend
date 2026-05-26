<?php

namespace Modules\Locations\Resources;

use Illuminate\Http\Request;
use Modules\Locations\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Location */
class LocationResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'address' => $this->address,
      'longitude' => $this->longitude,
      'latitude' => $this->latitude,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
