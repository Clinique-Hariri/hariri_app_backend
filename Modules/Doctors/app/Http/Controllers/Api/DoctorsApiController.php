<?php

namespace Modules\Doctors\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Enum\WeekDays;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Doctors\Constants\Speciality\Status;
use Modules\Doctors\Http\Resources\DoctorResource;
use Modules\Doctors\Http\Resources\DoctorScheduleResource;
use Modules\Doctors\Models\Doctor;

class DoctorsApiController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    $model = Doctor::with(['department', 'speciality'])
      ->where('status', Status::ACTIVE)
      ->orderByRaw('sort_order IS NULL, sort_order ASC');

    if (request()->boolean('paginate')) {
      $model = $model->paginate($request->get('per_page', 10));
    } else {
      $model = $model->get();
    }

    return $this->successResponse(
      data: DoctorResource::collection($model),
    );
  }

  public function show($doctorId, Request $request)
  {
    $model = Doctor::with(['department', 'speciality'])
      ->where('status', Status::ACTIVE)
      ->where('id', $doctorId)
      ->firstOrFail();

    return $this->successResponse(
      data: new DoctorResource($model),
    );
  }

  public function getDoctorsBySpeciality($specialityId, Request $request)
  {
    $model = Doctor::where('status', Status::ACTIVE)
      ->where('speciality_id', $specialityId)
      ->orderByRaw('sort_order IS NULL, sort_order ASC')
      ->get();

    return $this->successResponse(
      data: DoctorResource::collection($model),
    );
  }

  public function getDoctorsByDepartment($departmentId, Request $request)
  {
    $model = Doctor::where('status', Status::ACTIVE)
      ->where('department_id', $departmentId)
      ->orderByRaw('sort_order IS NULL, sort_order ASC')
      ->get();

    return $this->successResponse(
      data: DoctorResource::collection($model),
    );
  }

    public function getDoctorSchedules($doctorId, Request $request)
    {
      $model = Doctor::where('status', Status::ACTIVE)
        ->where('id', $doctorId)
        ->firstOrFail();

      $schedules = $model->schedules()
        ->with(['workingPeriods'])
        ->where('status', Status::ACTIVE)
        ->orderByRaw("FIELD(day_of_week, '" . implode("','", WeekDays::lists()) . "')")
        ->get();


      return $this->successResponse(
        data: DoctorScheduleResource::collection($schedules),
      );
    }

  public function getAvailability($doctorId, Request $request)
  {
    $data = $request->validate([
      'month' => 'required|integer|min:1|max:12',
      'year' => 'required|integer|min:1900|max:2100',
    ]);

    $doctor = Doctor::where('status', Status::ACTIVE)
      ->where('id', $doctorId)
      ->firstOrFail();

    $workingDays = $doctor->schedules()->pluck('day_of_week')->toArray();

    // Collect all matching days in the month
    $result = collect();
    $daysInMonth = Carbon::create($data['year'], $data['month'], 1)->daysInMonth;

    for ($i = 1; $i <= $daysInMonth; $i++) {
      $date = Carbon::create($data['year'], $data['month'], $i);
      $dayName = strtolower($date->format('l'));

      if (in_array($dayName, $workingDays)) {
        $result->push([
          'day' => $dayName,
          'day_translated' => WeekDays::getName($dayName),
          'date' => $date->day,
          'month' => $data['month'],
          'year' => $data['year'],
          'iso_date' => $date->toDateString(),
        ]);
      }
    }

    return $this->successResponse(
      data: $result,
    );
  }
}
