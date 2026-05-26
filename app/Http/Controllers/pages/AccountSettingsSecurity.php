<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsSecurity extends Controller
{
  public function index()
  {
    return view('pages.pages-account-settings-security');
  }
}
