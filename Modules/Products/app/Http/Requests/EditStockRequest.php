<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStockRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'purchase_price' => 'required|numeric|min:0',
      'quantity' => 'required|integer|min:0',
      'expiration_date' => 'nullable|date|after_or_equal:today',
      //'batch_number' => 'nullable|string|max:255',
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

  public function messages()
  {
    return [
      'expiration_date.after_or_equal' => trans('products::validation.custom.after_or_equal', [
        'attribute' => trans('products::validation.attributes.expiration_date'),
        'date' => trans('products::validation.values.today'),
      ]),
    ];
  }
}
