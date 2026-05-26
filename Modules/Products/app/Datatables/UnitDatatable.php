<?php

namespace Modules\Products\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Products\Constants\Status;
use Modules\Products\Models\Unit;

class UnitDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "name",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Unit $model) {
          return $this
            ->edit(route("units.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (Unit $model) {
          return $this->bold($model->name);
        })

        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request)
  {
    return Unit::latest()->get();
  }

}
