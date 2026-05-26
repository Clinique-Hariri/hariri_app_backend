<?php

namespace Modules\Doctors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Support\Enum\PermissionNames;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Modules\Doctors\Datatables\DoctorDatatable;
use Modules\Doctors\Datatables\ScheduleDatatable;
use Modules\Doctors\Datatables\SpecialityDatatable;
use Modules\Doctors\Http\Requests\CreateDoctorRequest;
use Modules\Doctors\Http\Requests\CreateScheduleRequest;
use Modules\Doctors\Http\Requests\CreateSpecialityRequest;
use Modules\Doctors\Http\Requests\EditDoctorRequest;
use Modules\Doctors\Http\Requests\EditSpecialityRequest;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\DoctorSchedule;
use Modules\Doctors\Models\Speciality;

class SchedulesController extends Controller
{
  public function index($doctorId, Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }

    $doctor = Doctor::findOrFail($doctorId);

    $datatable = new ScheduleDatatable();
    if ($request->wantsJson()) {
      return $datatable->datatables($request, $doctor);
    }
    return view("doctors::schedule.index")->with([
      "columns" => $datatable::columns(),
      "doctor" => $doctor,
    ]);
  }

  public function create($doctorId)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }
    $doctor = Doctor::findOrFail($doctorId);
    return view("doctors::schedule.create")
      ->with(["edit" => false, "doctor" => $doctor]);
  }

  public function store($doctorId, CreateScheduleRequest $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }
    try {
      foreach ($request->days_of_week as $day) {
        $schedule = DoctorSchedule::firstOrCreate([
          'doctor_id' => $doctorId,
          'day_of_week' => $day,
        ]);

        $schedule->workingPeriods()->create([
          'start_time' => $request->start_time,
          'end_time' => $request->end_time,
        ]);
      }

      return redirect()->route('doctors.schedules.index', $doctorId)->with('success', __('app.created_successfully', ['name' => __('doctors::app.schedule')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($doctorId, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS)) {
      return redirect()->route('unauthorized');
    }
    try {
      $model = DoctorSchedule::where('doctor_id', $doctorId)
        ->where('id', $id)
        ->firstOrFail();
      $model->delete();
      return redirect()->route('doctors.schedules.index', $doctorId)->with('success', __('app.deleted_successfully', ['name' => __('doctors::app.schedule')]));

    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
