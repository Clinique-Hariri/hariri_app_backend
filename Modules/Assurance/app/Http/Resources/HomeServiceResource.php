<?php

namespace Modules\Assurance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\HomeService;

/** @mixin HomeService */
class HomeServiceResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    $image = $this->getFirstMediaUrl(HomeService::IMAGE);
    return [
      'id' => $this->id,
      'name' => $this->name,
      'price' => $this->price,
      'description' => $this->description,
      'status' => $this->status,
      'image' => $image !== "" ? $image : null,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
    ];
  }
}
