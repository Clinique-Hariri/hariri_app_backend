<?php

namespace Modules\Doctors\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Doctors\Http\Resources\AppointmentResource;
use Modules\Doctors\Models\Appointment;
use Modules\Doctors\Models\Doctor;

class AppointmentsApiController extends Controller
{
  use ApiResponseTrait;

  public function index(Request $request)
  {
    $data = $request->validate([
      'status' => ['sometimes', 'string', 'in:' . implode(',', array_keys(\Modules\Doctors\Constants\Appointment\Status::all()))],
    ]);
    $model = Appointment::with(['doctor', 'doctor.speciality', 'doctor.department'])
      ->where('user_id', auth()->id())
      ->orderByRaw("
        FIELD(status, 'pending', 'confirmed', 'cancelled'),
        date ASC,
        time ASC
    ");

    if (isset($data['status'])) {
      $model = $model->where('status', $data['status']);
    }

    if (request()->boolean('paginate')) {
      $model = $model->paginate($request->get('per_page', 10));
    } else {
      $model = $model->get();
    }

    return $this->successResponse(
      data: AppointmentResource::collection($model),
    );
  }
  public function store(Request $request)
  {
    $data = $request->validate([
      'doctor_id' => ['required', 'exists:doctors,id'],
      'date' => ['required', 'date_format:d-m-Y', 'after_or_equal:today'],
      'patient_name' => ['required', 'string', 'max:255'],
      'patient_phone' => ['required', 'regex:/^\+?[0-9]{7,15}$/'],
      'patient_age' => ['required', 'integer', 'min:0'],
    ]);

    $doctor = Doctor::where('status', \Modules\Doctors\Constants\Doctor\Status::ACTIVE)
      ->where('id', $data['doctor_id'])
      ->firstOrFail();

    $appointment = $doctor->appointments()->create([
      'date' => $data['date'],
      'patient_name' => $data['patient_name'],
      'patient_phone' => $data['patient_phone'],
      'patient_age' => $data['patient_age'],
      'user_id' => auth()->id(),
    ])->refresh();
    return $this->successResponse(
      data: new AppointmentResource($appointment),
    );
  }
}
