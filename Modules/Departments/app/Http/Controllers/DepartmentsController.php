<?php

namespace Modules\Departments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Departments\Http\Requests\CreateDepartmentRequest;
use Modules\Departments\Datatables\DepartmentDatatable;
use Modules\Departments\Http\Requests\EditDepartmentRequest;
use Modules\Departments\Models\Department;

class DepartmentsController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new DepartmentDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("departments::index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }
    return view("departments::create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }
    return view("departments::create-edit")->with(["edit" => true, "model" => Department::findOrFail($id)]);
  }

  public function store(CreateDepartmentRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = Department::create($request->validated());
      if ($request->hasFile(Department::IMAGE)) {
        storeWebPWithSpatie($model, $request->file(Department::IMAGE), Department::IMAGE);
      }
      return redirect()->route('departments.index')->with('success', __('app.created_successfully', ['name' => __('departments::app.department')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditDepartmentRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Department::findOrFail($id);
      $model->update($request->validated());

      if ($request->hasFile(Department::IMAGE)) {
        $model->clearMediaCollection(Department::IMAGE);
        storeWebPWithSpatie($model, $request->file(Department::IMAGE), Department::IMAGE);
      }
      return redirect()->route('departments.index')->with('success', __('app.updated_successfully', ['name' => __('departments::app.department')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = Department::findOrFail($id);
      $model->clearMediaCollection(Department::IMAGE);
      $model->delete();
      return redirect()->route('departments.index')->with('success', __('app.deleted_successfully', ['name' => __('departments::app.department')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
