<?php

namespace Modules\Doctors\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Doctors\Constants\Appointment\Status;
use Modules\Doctors\Http\Resources\DoctorResource;
use Modules\Doctors\Models\Appointment;

/** @mixin Appointment */
class AppointmentResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'patient_name' => $this->patient_name,
      'patient_phone' => $this->patient_phone,
      'patient_age' => $this->patient_age,
      'date' => $this->date->format('d-m-Y'),
      'day' => $this->date->day,
      'month' => $this->date->month,
      'time' => $this->time?->format('H:i'),
      'status' => $this->status,
      'status_translated' => Status::get_name($this->status),

      'doctor_id' => $this->doctor_id,
      'user_id' => $this->user_id,

      'doctor' => new DoctorResource($this->whenLoaded('doctor')),
    ];
  }
}
