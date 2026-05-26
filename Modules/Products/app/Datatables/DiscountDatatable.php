<?php

namespace Modules\Products\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Products\Models\Discount;

class DiscountDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "discount",
            "start_date",
            "end_date",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn("action", function (Discount $model) {
                    return $this
                        ->edit(route("discounts.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
                        ->makeLabelledIcons();
                })
                ->addColumn("discount", function (Discount $model) {
                  return $this->percentage($model->discount);
                })
                ->addColumn("start_date", function (Discount $model) {
                  return $model->start_date ? $this->date($model->start_date) : $this->badge('secondary', __('coupons::app.none'));
                })
                ->addColumn("end_date", function (Discount $model) {
                  return $model->end_date ? $this->date($model->end_date) : $this->badge('secondary', __('coupons::app.none'));
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . " Error " . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Discount::query();

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        return $query->latest()->get();
    }
}
