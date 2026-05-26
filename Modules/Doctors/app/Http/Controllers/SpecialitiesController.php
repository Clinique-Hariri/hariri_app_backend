<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Doctors\Datatables\SpecialityDatatable;
use Modules\Doctors\Http\Requests\CreateSpecialityRequest;
use Modules\Doctors\Http\Requests\EditSpecialityRequest;
use Modules\Doctors\Models\Speciality;

class SpecialitiesController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new SpecialityDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("doctors::speciality.index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }
    return view("doctors::speciality.create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }
    return view("doctors::speciality.create-edit")->with(["edit" => true, "model" => Speciality::findOrFail($id)]);
  }

  public function store(CreateSpecialityRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Speciality::create($request->validated());

      return redirect()->route('specialities.index')->with('success', __('app.created_successfully', ['name' => __('doctors::app.speciality')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditSpecialityRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Speciality::findOrFail($id);
      $model->update($request->validated());
      return redirect()->route('specialities.index')->with('success', __('app.updated_successfully', ['name' => __('doctors::app.speciality')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Speciality::findOrFail($id);
      $model->delete();
      return redirect()->route('specialities.index')->with('success', __('app.deleted_successfully', ['name' => __('doctors::app.speciality')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
