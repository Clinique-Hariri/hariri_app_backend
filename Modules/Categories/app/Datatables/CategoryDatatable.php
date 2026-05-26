<?php

namespace Modules\Categories\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Categories\Constants\Status;
use Modules\Categories\Models\Category;

class CategoryDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "category",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (Category $model) {
          return $this
            ->edit(route("categories.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_CATEGORIES))
            ->makeLabelledIcons();
        })
        ->addColumn("category", function (Category $model) {
          return $this->thumbnailTitleMeta(
            $model->getFirstMediaUrl(Category::IMAGE),
            $model->name,
            $model->products_count . ' '. __('categories::app.products'),
            route('categories.edit', $model->id)
          );
        })
        ->addColumn("status", function (Category $model) {
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

    $query =Category::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }

}
