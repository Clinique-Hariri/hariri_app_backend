<?php

namespace Modules\Assurance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\ExaminationService;

/** @mixin ExaminationService */
class ExaminationServiceResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    $image = $this->getFirstMediaUrl(ExaminationService::IMAGE);
    $pdfs = $this->getMedia(ExaminationService::PDF)->map(fn ($media) => $media->getUrl())->values();
    return [
      'id' => $this->id,
      'service_name' => $this->service_name,
      'service_price' => $this->service_price,
      'image' => $image !== "" ? $image : null,
      'pdfs' => $pdfs,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'medical_service_id' => $this->medical_service_id,
      'medical_examination_id' => $this->medical_examination_id,
    ];
  }
}
