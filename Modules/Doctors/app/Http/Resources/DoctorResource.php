<?php

namespace Modules\Doctors\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Departments\Resource\DepartmentResource;
use Modules\Doctors\Models\Doctor;

/** @mixin Doctor */
class DoctorResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'firstname' => $this->firstname,
      'lastname' => $this->lastname,
      'full_name' => $this->fullname,
      'bio' => $this->bio,
      'avatar' => $this->avatar_url,
      'status' => $this->status,
      'next_available' => $this->getNextAvailableDate(),
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,

      'speciality_id' => $this->speciality_id,
      'department_id' => $this->department_id,
      'display_label' => $this->display_label == 'speciality' ? $this->speciality?->name : $this->department?->name,
    ];
  }
}
