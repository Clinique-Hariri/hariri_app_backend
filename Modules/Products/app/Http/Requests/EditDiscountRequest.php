<?php

namespace Modules\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Products\Models\Discount;

class EditDiscountRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'discount' => 'required|numeric|min:0',
      'start_date' => 'required|date|after_or_equal:today',
      'end_date' => 'nullable|date|after_or_equal:start_date',
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
      'start_date.after_or_equal' => trans('products::validation.custom.after_or_equal', [
        'attribute' => trans('products::validation.attributes.start_date'),
        'date' => trans('products::validation.values.today'),
      ]),
      'end_date.after_or_equal' => trans('products::validation.custom.after_or_equal', [
        'attribute' => trans('products::validation.attributes.end_date'),
        'date' => trans('products::validation.attributes.start_date'),
      ]),
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if ($validator->errors()->isEmpty()) {
        $this->validateDateOverlap($validator);
      }
    });
  }

  protected function validateDateOverlap($validator)
  {
    $productId = $this->input('product_id');
    $startDate = $this->input('start_date');
    $endDate = $this->input('end_date') ?? '9999-12-31';
    $discountId = $this->route('discount');

    // Check for overlapping discounts
    $overlappingDiscount = Discount::where('product_id', $productId)
      ->whereNot('id', $discountId)
      ->where(function ($query) use ($startDate, $endDate) {
        // New discount starts during an existing discount
        $query->where(function ($q) use ($startDate) {
          $q->where('start_date', '<=', $startDate)
            ->whereRaw('COALESCE(end_date, ?) >= ?', ['9999-12-31', $startDate]);
        })
          // New discount ends during an existing discount
          ->orWhere(function ($q) use ($endDate) {
          $q->where('start_date', '<=', $endDate)
            ->whereRaw('COALESCE(end_date, ?) >= ?', ['9999-12-31', $endDate]);
        })
          // New discount completely contains an existing discount
          ->orWhere(function ($q) use ($startDate, $endDate) {
          $q->where('start_date', '>=', $startDate)
            ->whereRaw('COALESCE(end_date, ?) <= ?', ['9999-12-31', $endDate]);
        });
      })
      ->first();

    if ($overlappingDiscount) {
      $validator->errors()->add(
        'date',
        trans('products::validation.custom.overlap')
      );
    }
  }
}
