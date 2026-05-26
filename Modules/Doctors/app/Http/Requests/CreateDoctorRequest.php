<?php

namespace Modules\Doctors\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Doctors\Constants\Doctor\Status;

class CreateDoctorRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'firstname' => ['required', 'string', 'max:255'],
      'lastname' => ['required', 'string', 'max:255'],
      'bio' => ['nullable', 'string'],
      'display_label'   => ['required', 'in:speciality,department'],
      'speciality_id'   => ['required_if:display_label,speciality', 'nullable', 'exists:specialities,id'],
      'department_id'   => ['required_if:display_label,department', 'nullable', 'exists:departments,id'],
      'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'sort_order' => ['required', 'integer'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
