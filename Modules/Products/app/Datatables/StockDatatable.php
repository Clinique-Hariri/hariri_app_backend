<?php

namespace Modules\Products\Datatables;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Products\Models\Stock;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;

class StockDatatable
{
  use DataTableActionsTrait;

  public static function columns(): array
  {
    return [
      "purchase_price",
      "quantity",
      "expiration_date",
      "batch_number",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Stock $model) {
          return $this
            ->edit(route("stocks.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
            ->makeLabelledIcons();
        })
        ->addColumn("product", function (Stock $model) {
          return $model->product ? $this->bold($model->product->name) : '-';
        })
        ->addColumn("purchase_price", function (Stock $model) {
          return number_format($model->purchase_price, 2);
        })
        ->addColumn("quantity", function (Stock $model) {
          return $model->quantity;
        })
        ->addColumn("expiration_date", function (Stock $model) {
          return $model->expiration_date ? $this->date($model->expiration_date) : $this->badge('secondary', __('products::app.none'));
        })
        ->addColumn("batch_number", function (Stock $model) {
          return $model->batch_number ?? '-';
        })
        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request)
  {
    $query = Stock::query()->with(['product']);

    if ($request->has('product_id')) {
      $query->where('product_id', $request->product_id);
    }

    return $query->latest()->get();
  }
}
