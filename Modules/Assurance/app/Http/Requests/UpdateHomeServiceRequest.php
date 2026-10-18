<?php

namespace Modules\Assurance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\MedicalService\Status;

class UpdateHomeServiceRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'price' => ['required', 'numeric', 'min:0'],
      'description' => ['nullable', 'string'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
