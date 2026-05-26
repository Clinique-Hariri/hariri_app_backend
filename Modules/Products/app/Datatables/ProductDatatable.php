<?php

namespace Modules\Products\Datatables;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Products\Models\Product;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Modules\Products\Constants\Status;

class ProductDatatable
{
  use DataTableActionsTrait;

  public static function columns(): array
  {
    return [
      "product",
      "category",
      'quantity',
      "price",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Product $model) {
          return $this
            ->edit(route("products.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS))

            ->redirectButton(
              route: route('discounts.index', parameters: $model->id),
              text: __('products::app.discounts'),
              icon: 'bx bxs-discount',
              show: Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS),
              color: 'teal'
            )
            ->redirectButton(
              route: route('stocks.index', $model->id),
              text: __('products::app.stocks'),
              icon: 'bx bxs-package',
              show: Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PRODUCTS),
              color: 'blue'
            )
            ->makeLabelledIcons();
        })

        ->addColumn("product", function (Product $model) {
          return $this->thumbnailTitleMeta(
            $model->getFirstMediaUrl(Product::Image),
            $model->name,
            $model->unit_count . ' ' . $model->unit?->name,
            route('products.edit', $model->id)
          );
        })
        ->addColumn("category", function (Product $model) {
          return $this->bold($model->category ? $model->category->name : '-');
        })
        ->addColumn("price", function (Product $model) {
          return $this->money($model->price);
        })
        ->addColumn("quantity", function (Product $model) {
          return "{$model->stock_quantity} ({$model->available_quantity})" ;
        })
        ->addColumn("status", function (Product $model) {
          return $this->badge(Status::colors()[$model->status], Status::all(true)[$model->status]);
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
      'category_filter' => 'category_id',
      'unit_filter' => 'unit_id'
    ];

    $query = Product::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->with(['category', 'unit'])->latest()->get();
  }
}
