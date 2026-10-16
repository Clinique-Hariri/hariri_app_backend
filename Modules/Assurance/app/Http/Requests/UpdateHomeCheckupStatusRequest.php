<?php

namespace Modules\Assurance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\HomeCheckup\Status;

class UpdateHomeCheckupStatusRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'status' => ['required', 'in:' . implode(',', Status::all())],
      'rejection_reason' => ['required_if:status,' . Status::REJECTED, 'nullable', 'string'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
