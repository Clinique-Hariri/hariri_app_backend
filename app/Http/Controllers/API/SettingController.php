<?php

namespace App\Http\Controllers\API;

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

}
