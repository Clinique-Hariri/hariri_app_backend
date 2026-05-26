<?php

namespace Modules\Orders\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Orders\Constants\Status;

class CreateDriverRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:drivers,phone',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
  }

  public function authorize(): bool
  {
    return true;
  }

  public function attributes()
  {
    return trans('orders::validation.attributes');
  }
}
