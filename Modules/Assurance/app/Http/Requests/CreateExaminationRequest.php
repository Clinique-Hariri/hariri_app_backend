<?php

namespace Modules\Assurance\Http\Requests;

use App\Constants\BloodType;
use App\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\FamilyMember\Status;

class CreateExaminationRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'family_member_id' => ['required', 'exists:family_members,id'],
      'total_price' => ['required', 'numeric', 'min:0'],
      'date' => ['required', 'date'],
      'services' => ['required', 'array'],
      'services.*.service_id' => ['nullable', 'exists:medical_services,id'],
      'services.*.service_name' => ['required', 'string', 'max:255'],
      'services.*.service_price' => ['required', 'numeric', 'min:0'],
      'services.*.image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:8192'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
