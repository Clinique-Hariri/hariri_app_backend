<?php

namespace Modules\Assurance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\HomeCheckup\VisitType;

class StoreHomeCheckupRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'location' => ['required', 'array'],
      'location.address' => ['required', 'string', 'max:255'],
      'location.longitude' => ['required', 'numeric'],
      'location.latitude' => ['required', 'numeric'],
      'patient_number' => ['nullable', 'string', 'max:255'],
      'notes' => ['nullable', 'string'],
      'preferred_date' => ['required', 'date', 'after_or_equal:today'],
      'preferred_time' => ['nullable', 'date_format:H:i'],
      'visit_type' => ['nullable', 'in:' . implode(',', VisitType::all())],
      'service_ids' => ['required', 'array', 'min:1'],
      'service_ids.*' => ['required', 'integer', 'exists:home_services,id'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
