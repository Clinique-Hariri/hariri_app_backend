<?php

namespace Modules\Doctors\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Doctors\Constants\Doctor\Status;
use Modules\Doctors\Models\Doctor;

class DoctorDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "doctor",
      "display_label",
      "department",
      "speciality",
      "bio",
      "sort_order",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Doctor $model) {
          return $this->redirectButton(route('doctors.schedules.index', $model->id), __('doctors::app.schedules'), 'bx bxs-calendar', Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS))
            ->edit(route("doctors.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DOCTORS))
            ->makeLabelledIcons();
        })
        ->addColumn("doctor", function (Doctor $model) {
          return $this->thumbnailTitleMeta($model->avatar_url, $model->fullname, $model->speciality?->name, route('doctors.edit', $model->id));
        })
        ->addColumn("display_label", function (Doctor $model) {
          return $model->display_label == 'speciality' ? __('doctors::app.speciality') : __('doctors::app.department');
        })
        ->addColumn("department", function (Doctor $model) {
          return $this->badge('primary', $model->department?->name);
        })
        ->addColumn("speciality", function (Doctor $model) {
          return $this->badge('secondary', $model->speciality?->name);
        })
        ->addColumn("bio", function (Doctor $model) {
          return $this->longText($model->bio);
        })
        ->addColumn("sort_order", function (Doctor $model) {
          return $this->badge('info', $model->sort_order ?? '-');
        })
        ->addColumn("status", function (Doctor $model) {
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
      'department_filter' => 'department_id',
      'speciality_filter' => 'speciality_id',
      'status_filter' => 'status',
    ];

    $query = Doctor::query()->with(['speciality', 'department'])
      ->orderByRaw('sort_order IS NULL, sort_order ASC');

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
