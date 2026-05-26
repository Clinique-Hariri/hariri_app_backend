<?php

namespace Modules\Doctors\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Doctors\Constants\Doctor\Status;

class CreateAppointmentRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'doctor_id' => ['required', 'exists:doctors,id'],
//      'user_id' => ['required', 'exists:users,id'],
      'patient_name' => ['required', 'string', 'max:255'],
      'patient_phone' => ['required', 'string', 'max:15', 'regex:/^\+?[0-9]{1,15}$/'],
      'patient_age' => ['required', 'integer', 'min:0', 'max:120'],
      'date' => ['required', 'date', 'after_or_equal:today'],
      'time' => ['required', 'date_format:H:i'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
