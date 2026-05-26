<?php

namespace App\Http\Controllers\front_pages;

use App\Http\Controllers\Controller;
use Modules\Documentation\Models\Documentation;

class DocumentationController extends Controller
{

  public function privacyPolicy()
  {
    $pageConfigs = ['myLayout' => 'front'];
    $privacy_policy = Documentation::where('key', 'privacy_policy')->value('value');
    return view('front-pages.privacy-policy', compact('privacy_policy', 'pageConfigs'));
  }

  public function aboutUs()
  {
    $pageConfigs = ['myLayout' => 'front'];
    $about_us = Documentation::where('key', 'about_us')->value('value');
    return view('front-pages.about-us', compact('about_us', 'pageConfigs'));
  }

    public function deleteAccount()
  {
    $pageConfigs = ['myLayout' => 'front'];
    $delete_account = Documentation::where('key', 'delete_account')->value('value');
    return view('front-pages.delete-account', compact('delete_account', 'pageConfigs'));
  }
}
