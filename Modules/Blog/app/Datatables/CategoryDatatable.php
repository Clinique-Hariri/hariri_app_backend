<?php

namespace Modules\Blog\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Blog\Models\Category;

class CategoryDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "name",
            "blogs_count",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn("action", function (Category $model) {
                    return $this
                        ->edit(route("blog.categories.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_CATEGORIES))
                        ->makeLabelledIcons();
                })
                ->addColumn("name", function (Category $model) {
                    return $this->bold($model->name);
                })
                ->addColumn("blogs_count", function (Category $model) {
                    return $model->blogs_count;
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . " Error " . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Category::withCount('blogs');

        return $query->latest()->get();
    }
}
