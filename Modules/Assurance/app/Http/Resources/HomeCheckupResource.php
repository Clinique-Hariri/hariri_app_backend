<?php

namespace Modules\Assurance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\HomeCheckup;

/** @mixin HomeCheckup */
class HomeCheckupResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'location_id' => $this->location_id,
      'patient_number' => $this->patient_number,
      'patient_fullname' => $this->patient_fullname,
      'patient_phone' => $this->patient_phone,
      'patient_birthdate' => $this->patient_birthdate?->format('Y-m-d'),
      'notes' => $this->notes,
      'preferred_date' => $this->preferred_date?->format('Y-m-d'),
      'preferred_time' => $this->preferred_time,
      'status' => $this->status,
      'visit_type' => $this->visit_type,
      'reviewed_at' => $this->reviewed_at,
      'rejection_reason' => $this->rejection_reason,
      'base_price' => $this->base_price,
      'total_price' => $this->total_price,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'location' => $this->whenLoaded('location'),
      'services' => HomeCheckupRequestServiceResource::collection($this->whenLoaded('services')),
    ];
  }
}
