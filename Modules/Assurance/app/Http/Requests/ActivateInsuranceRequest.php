<?php

namespace Modules\Assurance\Http\Requests;

use App\Constants\BloodType;
use App\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Assurance\Constants\FamilyMember\Status;

class ActivateInsuranceRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'firstname' => ['required', 'string', 'max:255'],
      'lastname' => ['required', 'string', 'max:255'],
      'phone' => ['required', 'string', 'regex:/^\+?[0-9\s\-()]{7,20}$/', 'unique:users,phone,' . $this->route('userId')],
      'gender' => ['required', 'string', 'in:' . implode(',', Gender::all())],
      'blood_type' => ['required', 'string', 'in:' . implode(',', BloodType::all())],
      'birthdate' => ['required', 'date', 'before:today'],
      'full_address' => ['required', 'string', 'max:255'],
      'passport_number' => ['required', 'string', 'max:255'],

      'wallet_balance' => ['sometimes', 'nullable', 'numeric', 'min:0'],
      'contract' => ['sometimes', 'nullable', 'file', 'mimes:png,jpg,jpeg,gif,svg', 'max:2048'],

      'family_members' => ['sometimes', 'array'],
      'family_members.*.firstname' => ['required', 'string', 'max:255'],
      'family_members.*.lastname' => ['sometimes', 'nullable', 'string', 'max:255'],
      'family_members.*.phone' => ['sometimes', 'nullable', 'string', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
      'family_members.*.gender' => ['sometimes', 'nullable', 'string', 'in:' . implode(',', Gender::all())],
      'family_members.*.blood_type' => ['sometimes', 'nullable', 'string', 'in:' . implode(',', BloodType::all())],
      'family_members.*.birthdate' => ['sometimes', 'nullable', 'date', 'before:today'],
      'family_members.*.family_relation' => ['sometimes', 'nullable', 'string', 'max:255'],
      'family_members.*.avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:8192'],
    ];
  }

  public function authorize(): bool
  {
    return true;
  }
}
