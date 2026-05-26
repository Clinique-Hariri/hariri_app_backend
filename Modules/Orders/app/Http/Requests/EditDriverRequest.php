<?php

namespace Modules\Orders\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Orders\Constants\Status;
use Illuminate\Foundation\Http\FormRequest;

class EditDriverRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'phone' => ['required', 'regex:/^(\+?\d{1,3})?(\d{9})$/', Rule::unique('drivers', 'phone')->ignore($this->route('driver'))],
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
