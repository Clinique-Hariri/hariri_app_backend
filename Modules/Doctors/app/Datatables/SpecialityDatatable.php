<?php

namespace Modules\Doctors\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Doctors\Constants\Speciality\Status;
use Modules\Doctors\Models\Speciality;

class SpecialityDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "name",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Speciality $model) {
          return $this
            ->edit(route("specialities.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_SPECIALITIES))
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (Speciality $model) {
          return $this->bold($model->name);
        })
        ->addColumn("status", function (Speciality $model) {
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
      'status_filter' => 'status',
    ];

    $query = Speciality::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
