<?php

namespace Modules\Departments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Departments\Constants\Status;

class CreateDepartmentRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'array'],
      'name.ar' => ['required', 'string', 'max:255'],
      'name.en' => ['required', 'string', 'max:255'],
      'name.fr' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
