<?php

namespace Modules\Ads\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Ads\Models\Ad;

/** @mixin Ad */
class AdResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'type' => $this->type,
      'link' => $this->link,
      'expiration_date' => $this->expiration_date->format('Y-m-d'),
      'image' => $this->getFirstMediaUrl(Ad::Image),
      'status' => $this->status,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
