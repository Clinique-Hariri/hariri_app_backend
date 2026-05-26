<?php

namespace Modules\Documentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Modules\Documentation\Models\Documentation;

class DocumentationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SETTINGS)) {
      return redirect()->route('unauthorized');
    }

    $documentations = Documentation::all()->pluck('value', 'key')->toArray();
    return view('documentation::index', compact('documentations'));
  }

  public function store(Request $request)
  {

    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SETTINGS)) {
      return redirect()->route('unauthorized');
    }

    $request->validate([
      'documentations' => 'required|array',
      'documentations.*.key' => 'required|in:about_us,privacy_policy,delete_account',
      'documentations.*.value' => 'required|string'
    ]);

    try {

      foreach ($request->documentations as $documentation) {
        Documentation::where('key', '=', $documentation['key'])->update(['value' => $documentation['value']]);
      }

      return redirect()->route('documentations.index')->with('success', __('app.updated_successfully', ['name' => __('app.documentations')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

  }
}
