<?php

namespace Modules\Assurance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Assurance\Datatables\ServiceDatatable;
use Modules\Assurance\Http\Requests\CreateServiceRequest;
use Modules\Assurance\Http\Requests\EditServiceRequest;
use Modules\Assurance\Models\MedicalService;
use Modules\Categories\Models\Category;

class MedicalServicesController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new ServiceDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("assurance::service.index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }
    return view("assurance::service.create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }
    return view("assurance::service.create-edit")->with(["edit" => true, "model" => MedicalService::findOrFail($id)]);
  }

  public function store(CreateServiceRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }

    try {
      $model = MedicalService::create($request->validated());
      if ($request->hasFile(MedicalService::IMAGE)) {
        storeWebPWithSpatie($model, $request->file(MedicalService::IMAGE), MedicalService::IMAGE);
      }
      return redirect()->route('services.index')->with('success', __('app.created_successfully', ['name' => __('assurance::app.service')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditServiceRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = MedicalService::findOrFail($id);
      $model->update($request->validated());

      if ($request->hasFile(MedicalService::IMAGE)) {
        $model->clearMediaCollection(MedicalService::IMAGE);
        storeWebPWithSpatie($model, $request->file(MedicalService::IMAGE), Category::IMAGE);
      }
      return redirect()->route('services.index')->with('success', __('app.updated_successfully', ['name' => __('assurance::app.service')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = MedicalService::findOrFail($id);
      $model->clearMediaCollection(MedicalService::IMAGE);
      $model->delete();
      return redirect()->route('services.index')->with('success', __('app.deleted_successfully', ['name' => __('assurance::app.service')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
