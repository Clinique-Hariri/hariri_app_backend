<?php

namespace Modules\Doctors\Datatables;

use App\Constants\Statuses\UserStatus;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Doctors\Constants\Appointment\Status;
use Modules\Doctors\Models\Appointment;

class AppointmentDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "patient_name",
      "patient_phone",
      "patient_age",
      "date",
      "time",
      "doctor",
      "user",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Appointment $model) {
            $g1 = $this->modalButton('accept-modal', __('app.accept'), 'bx bx-check-circle', ['id' => $model->id],
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS) && $model->status === Status::PENDING)
            ->modalButton('cancel-modal', __('app.cancel'), 'bx bx-x-circle', ['id' => $model->id],
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS) && $model->status === Status::PENDING)
            ->modalButton('complete-modal', __('app.complete'), 'bx bx-check-circle', ['id' => $model->id],
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS) && $model->status === Status::CONFIRMED)
            ->make();

            $g2 = $this->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_APPOINTMENTS))
            ->makeLabelledIcons();

            return $this->groupActions([$g1, $g2]);
        })
        ->addColumn("patient_name", function (Appointment $model) {
          return $this->bold($model->patient_name);
        })
        ->addColumn("patient_phone", function (Appointment $model) {
          return $model->patient_phone;
        })
        ->addColumn("patient_age", function (Appointment $model) {
          return $model->patient_age;
        })
        ->addColumn("date", function (Appointment $model) {
          return $this->date($model->date);
        })
        ->addColumn("time", function (Appointment $model) {
          return $this->time($model->time);
        })
        ->addColumn("doctor", function (Appointment $model) {
          return $this->bold($model->doctor->fullname);
        })
        ->addColumn("user", function (Appointment $model) {
          return $this->bold($model->user->fullname);
        })
        ->addColumn("status", function (Appointment $model) {
          return $this->badge(Status::get_color($model->status),Status::get_name($model->status));
        })

        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request)
  {
    $filters = [
      'doctor_filter' => 'doctor_id',
      'user_filter' => 'user_id',
      'status_filter' => 'status',
    ];

    $query = Appointment::query()->with(['doctor', 'user']);
      /* ->orderByRaw("
        FIELD(status, 'pending', 'confirmed', 'cancelled'),
        date ASC,
        time ASC
    "); */

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
