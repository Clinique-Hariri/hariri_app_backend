<?php

namespace Modules\Purchases\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\Request;
use Modules\Purchases\Http\Requests\CreateSupplierRequest;
use Modules\Purchases\Http\Requests\EditSupplierRequest;
use Modules\Purchases\Datatables\SupplierDatatable;
use Modules\Purchases\Models\Supplier;
use Modules\Locations\Models\Location;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }

        $datatable = new SupplierDatatable();
        if ($request->wantsJson()) {
            return $datatable->datatables($request);
        }
        return view("purchases::supplier.index")->with([
            "columns" => $datatable::columns(),
        ]);
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        $locations = Location::all();
        return view("purchases::supplier.create-edit")->with(["edit" => false, "locations" => $locations]);
    }

    public function edit($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        $locations = Location::all();
        return view("purchases::supplier.create-edit")->with(["edit" => true, "model" => Supplier::findOrFail($id), "locations" => $locations]);
    }

    public function store(CreateSupplierRequest $request)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }

        try {
            $model = Supplier::create($request->validated());

            if ($request->hasFile('image')) {
              $model->addMediaFromRequest('image')->toMediaCollection(Supplier::IMAGE);
            }

            return redirect()->route('suppliers.index')->with('success', __('app.created_successfully', ['name' => __('purchases::app.supplier')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(EditSupplierRequest $request, $id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        try {
            $model = Supplier::findOrFail($id);
            $model->update($request->validated());

            if ($request->hasFile('image')) {
              $model->clearMediaCollection(Supplier::IMAGE);
              $model->addMediaFromRequest('image')->toMediaCollection(Supplier::IMAGE);
            }

            return redirect()->route('suppliers.index')->with('success', __('app.updated_successfully', ['name' => __('purchases::app.supplier')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES)) {
            return redirect()->route('unauthorized');
        }
        try {
            $model = Supplier::findOrFail($id);
            $model->delete();
            return redirect()->route('suppliers.index')->with('success', __('app.deleted_successfully', ['name' => __('purchases::app.supplier')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
