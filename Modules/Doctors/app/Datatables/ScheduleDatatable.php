<?php

namespace Modules\Doctors\Datatables;

use App\Support\Enum\PermissionNames;
use App\Support\Enum\WeekDays;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Doctors\Constants\Doctor\Status;
use Modules\Doctors\Models\DoctorSchedule;

class ScheduleDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "day_of_week",
      "working_periods",
      "status",
      "action",
    ];
  }

  public function datatables($request, $doctor)
  {
    try {
      return datatables($this->query($request, $doctor))
        ->addColumn("action", function (DoctorSchedule $model) {
          return $this
//            ->edit(route("doctors.schedules.edit", [$model->doctor->id, $model->id]), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS))
            ->makeLabelledIcons();
        })
        ->addColumn("day_of_week", function (DoctorSchedule $model) {
          return $this->bold(WeekDays::getName($model->day_of_week));
        })
        ->addColumn("working_periods", function (DoctorSchedule $model) {
          $periods = '';
          foreach ($model->workingPeriods as $period) {
            $periods.= $this->badge('secondary', $period->start_time->format('H:i') . ' - ' . $period->end_time->format('H:i'));
          }
          return $periods;
        })
        ->addColumn("status", function (DoctorSchedule $model) {
          return $this->badge(Status::get_color($model->status),Status::get_name($model->status));
        })

        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request, $doctor)
  {
    $filters = [
      'department_filter' => 'department_id',
      'speciality_filter' => 'speciality_id',
      'status_filter' => 'status',
    ];

    $query = DoctorSchedule::query()->with(['doctor', 'workingPeriods'])
      ->orderByRaw("FIELD(day_of_week, '" . implode("','", WeekDays::lists()) . "')")
      ->where('doctor_id', $doctor->id);

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }
}
