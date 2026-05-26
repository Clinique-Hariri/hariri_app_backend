<?php

namespace Modules\Assurance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\MedicalExamination;

/** @mixin MedicalExamination */
class MedicalExaminationResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'total_price' => $this->total_price,
      'date' => $this->date?->format('Y-m-d'),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'family_member_id' => $this->family_member_id,

      'examination_services' => ExaminationServiceResource::collection($this->whenLoaded('examinationServices')),
    ];
  }
}
