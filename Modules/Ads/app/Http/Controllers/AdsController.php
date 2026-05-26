<?php

namespace Modules\Ads\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Ads\Datatables\AdDatatable;
use Modules\Ads\Http\Requests\CreateAdRequest;
use Modules\Ads\Http\Requests\EditAdRequest;
use Modules\Ads\Models\Ad;

class AdsController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new AdDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("ads::index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }
    return view("ads::create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }
    return view("ads::create-edit")->with(["edit" => true, "model" => Ad::findOrFail($id)]);
  }

  public function store(CreateAdRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $ad = Ad::create($request->validated());
      if ($request->hasFile(Ad::Image)) {
        storeWebPWithSpatie($ad, $request->file(Ad::Image), Ad::Image);
      }
      return redirect()->route('ads.index')->with('success', __('app.created_successfully', ['name' => __('ads::app.ad')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditAdRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $ad = Ad::findOrFail($id);
      $ad->update($request->validated());

      if ($request->hasFile(Ad::Image)) {
        $ad->clearMediaCollection(Ad::Image);
        storeWebPWithSpatie($ad, $request->file(Ad::Image), Ad::Image);
      }
      return redirect()->route('ads.index')->with('success', __('app.updated_successfully', ['name' => __('ads::app.ad')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ADS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $ad = Ad::findOrFail($id);
      $ad->clearMediaCollection(Ad::Image);
      $ad->delete();
      return redirect()->route('ads.index')->with('success', __('app.deleted_successfully', ['name' => __('ads::app.ad')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
