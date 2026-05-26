<?php

namespace Modules\Doctors\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Departments\Constants\Status;

class EditSpecialityRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'array'],
      'name.ar' => ['required', 'string', 'max:255'],
      'name.en' => ['required', 'string', 'max:255'],
      'name.fr' => ['required', 'string', 'max:255'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
