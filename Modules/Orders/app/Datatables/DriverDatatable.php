<?php

namespace Modules\Orders\Datatables;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Orders\Models\Driver;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Modules\Orders\Constants\DriverStatus;

class DriverDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "driver",
            "status",
            "orders_count",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
            ->addIndexColumn()
                ->addColumn("action", function (Driver $model) {
                    return $this
                        ->edit(route("drivers.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS))
                        ->makeLabelledIcons();
                })
                ->addColumn("driver", function (Driver $model) {
                  return $this->thumbnailTitleMeta(
                    $model->getFirstMediaUrl(Driver::IMAGE),
                    $model->name,
                    $model->phone,
                    route('drivers.edit', $model->id)
                  );
                })
                ->addColumn("status", function (Driver $model) {
                    return $this->badge(DriverStatus::colors()[$model->status], DriverStatus::all(true)[$model->status]);
                })
                ->addColumn("orders_count", function (Driver $model) {
                    return $model->orders()->count();
                })
                ->addColumn("image", function (Driver $model) {
                    $image = $model->getFirstMediaUrl(Driver::IMAGE);
                    return $this->image($image ?: asset('images/placeholder.png'));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . " Error " . $e->getMessage());
        }
    }

    public function query($request)
    {
        return Driver::withCount('orders')->latest()->get();
    }
}
