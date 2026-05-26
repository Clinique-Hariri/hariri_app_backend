<?php

namespace Modules\Ads\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Ads\Constants\AdType;
use Modules\Ads\Constants\Status;
use Modules\Ads\Constants\UserType;

class EditAdRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'name' => ['required', 'string', 'max:255'],
      'type' => ['required', 'in:' . implode(',', AdType::all())],
      'link' => ['required_if::type,' . AdType::INTERNAL, 'string', 'url'],
      'expiration_date' => ['required', 'date', 'after_or_equal:today'],
      'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
      'user_types' => ['required', 'array', 'min:1'],
      'user_types.*' => ['required', 'in:' . implode(',', UserType::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
    public function messages(): array
  {
    return [
      'expiration_date.after_or_equal' => __('ads::validation.custom.after_or_equal', [
        'attribute' => __('ads::validation.attributes.expiration_date'),
        'date' => __('ads::validation.values.today')
      ]),
    ];
  }

  public function attributes(){
    return __('ads::validation')['attributes'];
  }
}
