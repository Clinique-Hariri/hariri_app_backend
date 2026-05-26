<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditProductRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'category_id' => ['required', 'exists:categories,id'],
      'unit_id' => ['required', 'exists:units,id'],
      'name' => ['required', 'string', 'max:255'],
      'barcode' => [
        'nullable',
        'string',
        'max:255',
        Rule::unique('products', 'barcode')->ignore($this->route('product'))
      ],
      'scientific_name' => [
        'nullable',
        'string',
        'max:255'
      ],
      'price' => ['required', 'numeric', 'min:0'],
      'unit_count' => ['required', 'integer', 'min:1'],
      'description' => ['nullable', 'string'],
      'status' => ['required', 'string', 'in:active,inactive'],
      'is_compensable' => ['boolean'],
      'with_prescription' => ['boolean'],
      'image' => ['nullable', 'image', 'max:2048'],
      'gallery.*' => ['nullable', 'image', 'max:2048'],
      'clear_gallery' => ['nullable', 'boolean'],
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
