<?php

namespace Modules\Coupons\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Coupons\Constants\Status;

class CreateCouponRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'code' => ['required', 'string', 'size:6', 'unique:coupons,code'],
      'discount' => ['required', 'numeric', 'min:0', 'max:100'],
      'start_date' => ['sometimes', 'nullable', 'date'],
      'end_date' => ['sometimes', 'nullable', 'date', 'after:start_date'],
      'max_uses' => ['sometimes', 'nullable', 'integer', 'min:1'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }

    public function messages(): array
  {
    return [
      'end_date.after' => __('coupons::validation.custom.after', [
        'attribute' => __('coupons::validation.attributes.end_date'),
        'date' => __('coupons::validation.attributes.start_date')
      ]),
    ];
  }

  public function attributes(){
    return __('coupons::validation')['attributes'];
  }
}
