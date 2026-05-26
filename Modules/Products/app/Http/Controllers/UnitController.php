<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Products\Http\Requests\CreateUnitRequest;
use Modules\Products\Datatables\UnitDatatable;
use Modules\Products\Http\Requests\EditUnitRequest;
use Modules\Products\Models\Unit;

class UnitController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new UnitDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("products::unit/index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    return view("products::unit/create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    return view("products::unit/create-edit")->with(["edit" => true, "model" => Unit::findOrFail($id)]);
  }

  public function store(CreateUnitRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Unit::create($request->validated());
      return redirect()->route('units.index')->with('success', __('app.created_successfully', ['name' => __('products::app.unit')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditUnitRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Unit::findOrFail($id);
      $model->update($request->validated());
      return redirect()->route('units.index')->with('success', __('app.updated_successfully', ['name' => __('products::app.unit')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Unit::findOrFail($id);
      $model->delete();
      return redirect()->route('units.index')->with('success', __('app.deleted_successfully', ['name' => __('products::app.unit')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
