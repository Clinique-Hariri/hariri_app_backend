<?php

namespace Modules\Purchases\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Purchases\Constants\Status;
use Modules\Purchases\Models\Purchase;

class PurchaseDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "supplier",
      "total_amount",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
      ->addIndexColumn()
        ->addColumn("action", function (Purchase $model) {
          return $this
            //->edit(route("purchases.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES))
            ->show(route("purchases.show", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES))
            ->makeLabelledIcons();
        })
        ->addColumn("supplier", function (Purchase $model) {
          return $this->bold($model->supplier?->name ?? '-');
        })
        ->addColumn("total_amount", function (Purchase $model) {
          return $this->money($model->total_amount);
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

    $query = Purchase::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
