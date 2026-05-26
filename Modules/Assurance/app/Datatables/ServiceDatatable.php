<?php

namespace Modules\Assurance\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Assurance\Constants\MedicalService\Status;
use Modules\Assurance\Models\MedicalService;

class ServiceDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "image",
      "name",
      "price",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (MedicalService $model) {
          return $this
            ->edit(route("services.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_SERVICES))
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (MedicalService $model) {
          return $this->bold($model->name);
        })
        ->addColumn("price", function (MedicalService $model) {
          return $this->money($model->price);
        })
        ->addColumn("image", function (MedicalService $model) {
          return $this->imageRectangle($model->getFirstMediaUrl(MedicalService::IMAGE));
        })
        ->addColumn("status", function (MedicalService $model) {
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

    $query = MedicalService::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
