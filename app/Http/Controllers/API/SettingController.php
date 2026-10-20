<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
use ApiResponseTrait;
  public function index()
  {
    $api_settings = [
      'android_version_number',
      'android_build_number',
      'android_priority',
      'android_link',
      'ios_version_number',
      'ios_build_number',
      'ios_priority',
      'ios_link',
      'clinic_whatsapp',
      'clinic_email',
      'clinic_facebook',
      'clinic_instagram',
      'pharmacy_whatsapp',
      'pharmacy_email', 
      'pharmacy_facebook',
      'pharmacy_instagram',
      'pharmacy_enabled',
      'clinic_enabled',
    ];
    $settings = Setting::whereIn('key', $api_settings)->pluck('value', 'key');
    return $this->successResponse(data: $settings);
  }

  public function store(Request $request)
  {
    $validated = $this->validateRequest($request, [
      'home_checkup_nurse_only_price' => 'required|numeric|min:0',
      'home_checkup_with_doctor_price' => 'required|numeric|min:0',
    ]);

    try {
      foreach ($validated as $key => $value) {
        Setting::updateOrInsert(['key' => $key], ['value' => $value]);
      }

      return $this->successResponse('Settings updated successfully.', data: $validated);
    } catch (Exception $e) {
      return $this->errorResponse($e->getMessage());
    }
  }

}
