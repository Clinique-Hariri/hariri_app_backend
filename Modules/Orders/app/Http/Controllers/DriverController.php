<?php

namespace Modules\Orders\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Orders\Models\Driver;
use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Modules\Orders\Datatables\DriverDatatable;
use Modules\Orders\Http\Requests\EditDriverRequest;
use Modules\Orders\Http\Requests\CreateDriverRequest;

class DriverController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new DriverDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }

    return view("orders::driver/index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    return view("orders::driver/create-edit")->with([
      "edit" => false,
    ]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    $model = Driver::findOrFail($id);

    return view("orders::driver/create-edit")->with([
      "edit" => true,
      "model" => $model,
    ]);
  }

  public function store(CreateDriverRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $driver = Driver::create($request->validated());
      if ($request->hasFile('image')) {
        $driver->addMediaFromRequest('image')->toMediaCollection(Driver::IMAGE);
      }
      return redirect()->route('drivers.index')->with('success', __('app.created_successfully', ['name' => __('orders::app.driver')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditDriverRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $driver = Driver::findOrFail($id);
      $driver->update($request->validated());
      if ($request->hasFile('image')) {
        $driver->clearMediaCollection(Driver::IMAGE);
        $driver->addMediaFromRequest('image')->toMediaCollection(Driver::IMAGE);
      }
      return redirect()->route('drivers.index')->with('success', __('app.updated_successfully', ['name' => __('orders::app.driver')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $driver = Driver::findOrFail($id);
      $driver->delete();
      return redirect()->route('drivers.index')->with('success', __('app.deleted_successfully', ['name' => __('orders::app.driver')]));
    } catch (Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

  }
}
