<?php

namespace Modules\Coupons\Datatables;

use Exception;
use Modules\Coupons\Models\Coupon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Modules\Coupons\Constants\Status;

class CouponDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "name",
      "code",
      "start_date",
      "end_date",
      "discount",
      "max_uses",
      //"usage_count",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Coupon $model) {
          return $this
            ->edit(route("coupons.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_COUPONS))
            ->makeLabelledIcons();
        })
        ->addColumn("name", function (Coupon $model) {
          return $this->bold($model->name);
        })
        ->addColumn("code", function (Coupon $model) {
          return $model?->code;
        })
        ->addColumn("discount", function (Coupon $model) {
          return $this->percentage($model->discount);
        })
        ->addColumn("start_date", function (Coupon $model) {
          return $model->start_date ? $this->date($model->start_date) : $this->badge('secondary', __('coupons::app.none'));
        })
        ->addColumn("end_date", function (Coupon $model) {
          return $model->end_date ? $this->date($model->end_date) : $this->badge('secondary', __('coupons::app.none'));
        })
        ->addColumn("status", function (Coupon $model) {
          return $this->badge(Status::get_color($model->status),Status::get_name($model->status));
        })
        ->addColumn("max_uses", function (Coupon $model) {
          return $model->max_uses ? $model->max_uses : $this->badge('secondary', __('coupons::app.unlimited'));
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

    $query = Coupon::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
