<?php

namespace Modules\Blog\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Blog\Models\Author;

class AuthorDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "image",
            "fullname",
            "bio",
            "blogs_count",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn("action", function (Author $model) {
                    return $this
                        ->edit(route("blog.authors.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_BLOG_AUTHORS))
                        ->makeLabelledIcons();
                })
                ->addColumn("image", function (Author $model) {
                    $imageUrl = $model->getFirstMediaUrl('image') ?: asset('assets/img/avatars/9.png');
                    return '<img src="' . $imageUrl . '" alt="Avatar" class="rounded-circle" width="40" height="40">';
                })
                ->addColumn("fullname", function (Author $model) {
                    return $this->bold($model->fullname);
                })
                ->addColumn("bio", function (Author $model) {
                    return $model->bio ? Str::limit($model->bio, 50) : '-';
                })
                ->addColumn("blogs_count", function (Author $model) {
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
        $query = Author::withCount('blogs');

        return $query->latest()->get();
    }
}
