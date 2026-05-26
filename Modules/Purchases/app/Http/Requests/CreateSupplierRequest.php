<?php

namespace Modules\Purchases\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:suppliers,phone',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
      //'location_id' => 'required|exists:locations,id',
    ];
  }

  public function authorize(): bool
  {
    return true;
  }

  public function attributes()
  {
    return trans('purchases::validation.attributes');
  }
}
