<?php

namespace Modules\Assurance\Http\Requests;

use App\Constants\BloodType;
use App\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\FamilyMember\Status;

class CreateFamilyMemberRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'firstname' => ['required', 'string', 'max:255'],
      'lastname' => ['required', 'string', 'max:255'],
      'phone' => ['required', 'regex:/^(\+?\d{1,3})?(\d{9})$/'],
      'gender' => ['required', 'string', 'in:' . implode(',', Gender::all())],
      'birthdate' => ['nullable', 'date', 'before:today'],
      'blood_type' => ['nullable', 'in:' . implode(',', BloodType::all())],
      'family_relation' => ['nullable', 'string', 'max:255'],
      'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:8192'],
      'status' => ['required', 'in:' . implode(',', Status::all())],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
