<?php

namespace Modules\Departments\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Departments\Constants\Status;
use Modules\Departments\Models\Department;

class DepartmentDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "image",
      "name",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Department $model) {
          return $this
            ->edit(route("departments.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_DEPARTMENTS))
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (Department $model) {
          return $this->bold($model->name);
        })
        ->addColumn("image", function (Department $model) {
          return $this->imageRectangle($model->getFirstMediaUrl(Department::IMAGE));
        })
        ->addColumn("status", function (Department $model) {
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

    $query =Department::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
