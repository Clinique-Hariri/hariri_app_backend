<?php

namespace Modules\Assurance\Http\Resources;

use App\Constants\BloodType;
use App\Constants\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Assurance\Models\FamilyMember;

/** @mixin FamilyMember */
class FamilyMemberResource extends JsonResource
{

  public function toArray(Request $request): array
  {
    $lastExamination = $this->medicalExaminations()
      ->with('examinationServices')
      ->orderBy('date', 'desc')
      ->orderBy('id', 'desc')
      ->first();
    return [
      'id' => $this->id,
      'firstname' => $this->firstname,
      'lastname' => $this->lastname,
      'birthdate' => $this->birthdate?->format('Y-m-d'),
      'phone' => $this->phone,
      'gender' => $this->gender,
      'gender_translated' => Gender::get_name($this->gender),
      'blood_type' => $this->blood_type,
      'blood_type_translated' => $this->blood_type ? BloodType::get_name($this->blood_type) : null,
      'avatar_url' => $this->avatar_url,
      'family_relation' => $this->family_relation,
      'status' => $this->status,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'user_id' => $this->user_id,

      'last_examination_date' => $lastExamination?->date->format('Y-m-d'),
      'last_examination_total_price' => $lastExamination?->total_price,
      'last_examination_medical_services' => $lastExamination?->examinationServices->map(function ($examinationService) {
        return [
          'service_name' => $examinationService->service_name,
          'service_price' => $examinationService->service_price,
        ];
      }),

    ];
  }
}
