<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'full_address' => $this->full_address,
            'avatar' => $this->avatar_url,
            'birthdate' => $this->birthdate,
            'status' => $this->status,
            'is_insured' => $this->is_insured,
            'insurance_date' => $this->insurance_date,
            'wallet_balance' => $this->wallet_balance,
            'device_token' => $this->device_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
