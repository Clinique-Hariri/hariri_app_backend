<?php

namespace Modules\Categories\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Categories\Constants\Status;

class CreateCategoryRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'array'],
      'name.ar' => ['required', 'string', 'max:255'],
      'name.en' => ['required', 'string', 'max:255'],
      'name.fr' => ['required', 'string', 'max:255'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }

    public function attributes()
  {
    return trans('categories::validation.attributes');
  }
}
