<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Products\Constants\Status;

class EditUnitRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'array'],
      'name.ar' => ['required', 'string', 'max:255'],
      'name.en' => ['required', 'string', 'max:255'],
      'name.fr' => ['required', 'string', 'max:255'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }

  public function attributes()
  {
    return trans('products::validation.attributes');
  }
}
