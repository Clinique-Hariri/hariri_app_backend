<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Doctors\Datatables\DoctorDatatable;
use Modules\Doctors\Datatables\SpecialityDatatable;
use Modules\Doctors\Http\Requests\CreateDoctorRequest;
use Modules\Doctors\Http\Requests\CreateSpecialityRequest;
use Modules\Doctors\Http\Requests\EditDoctorRequest;
use Modules\Doctors\Http\Requests\EditSpecialityRequest;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;

class DoctorsController extends Controller
{
  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }

    $datatable = new DoctorDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request);
    }
    return view("doctors::doctor.index")->with([
      "columns" => $datatable::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }
    return view("doctors::doctor.create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }
    return view("doctors::doctor.create-edit")->with(["edit" => true, "model" => Doctor::with(['department', 'speciality'])->findOrFail($id)]);
  }

  public function store(CreateDoctorRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }

    try {
      DB::beginTransaction();

      $data = $request->validated();

      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/doctors/avatars');
      } else {
        $file = new File(public_path('assets/img/avatars/1.png'));
        $data['avatar'] = storeWebP($file, 'uploads/doctors/avatars');
      }

      Doctor::adjustSortOrder(null, $data['sort_order'] ?? null);
      Doctor::create($data);

      DB::commit();

      return redirect()->route('doctors.index')
        ->with('success', __('app.created_successfully', ['name' => __('doctors::app.doctor')]));
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function update(EditDoctorRequest $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }

    try {
      DB::beginTransaction();

      $data = $request->validated();
      $model = Doctor::findOrFail($id);

      $newSort = $data['sort_order'] ?? null;
      $oldSort = $model->sort_order;

      // Handle avatar upload
      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/doctors/avatars');
      }

      Doctor::adjustSortOrder($model->sort_order, $data['sort_order'] ?? null);
      $model->update($data);

      DB::commit();

      return redirect()->route('doctors.index')->with('success', __('app.updated_successfully', ['name' => __('doctors::app.doctor')]));
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }

    try {
      DB::beginTransaction();

      $model = Doctor::findOrFail($id);

      Doctor::adjustSortOrder($model->sort_order, null);
      $model->delete();

      DB::commit();

      return redirect()->route('doctors.index')
        ->with('success', __('app.deleted_successfully', ['name' => __('doctors::app.doctor')]));
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

}
