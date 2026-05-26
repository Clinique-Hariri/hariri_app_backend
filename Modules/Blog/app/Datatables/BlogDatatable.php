<?php

namespace Modules\Blog\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Blog\Models\Blog;

class BlogDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "title",
            "author",
            "category",
            "clicks_count",
            "published_at",
            "created_at",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn("action", function (Blog $model) {
                    return $this
                        ->edit(route("blogs.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOGS))
                        ->makeLabelledIcons();
                })
                ->addColumn("title", function (Blog $model) {
                    return $this->bold($model->title);
                })
                ->addColumn("author", function (Blog $model) {
                    $name = $model->author_name;
                    if ($model->isAuthorDoctor()) {
                        return $this->badge('info', $name);
                    }
                    return $this->badge('secondary', $name ?: '-');
                })
                ->addColumn("category", function (Blog $model) {
                    $name = $model->category_name;
                    if ($model->isCategorySpeciality()) {
                        return $this->badge('warning', $name);
                    }
                    return $this->badge('secondary', $name ?: '-');
                })
                ->addColumn("clicks_count", function (Blog $model) {
                    return $model->clicks_count;
                })
                ->addColumn("published_at", function (Blog $model) {
                    if ($model->published_at) {
                        return $this->badge('success', $model->published_at->format('Y-m-d H:i'));
                    }
                    return $this->badge('secondary', __('blog::app.draft'));
                })
                ->addColumn("created_at", function (Blog $model) {
                    return $model->created_at->format('Y-m-d');
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . " Error " . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = Blog::with(['authorable', 'categoriable']);

        if ($request->has('published_filter') && $request->published_filter != '') {
            if ($request->published_filter === 'published') {
                $query->published();
            } else {
                $query->draft();
            }
        }

        if ($request->has('author_filter') && $request->author_filter != '') {
            $query->where('authorable_type', 'Modules\\Blog\\Models\\Author')
                  ->where('authorable_id', $request->author_filter);
        }

        if ($request->has('category_filter') && $request->category_filter != '') {
            $query->where('categoriable_type', 'Modules\\Blog\\Models\\Category')
                  ->where('categoriable_id', $request->category_filter);
        }

        return $query->latest()->get();
    }
}
