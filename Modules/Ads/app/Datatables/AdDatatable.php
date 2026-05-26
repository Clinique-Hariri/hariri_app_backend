<?php

namespace Modules\Ads\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Ads\Constants\AdType;
use Modules\Ads\Constants\Status;
use Modules\Ads\Constants\UserType;
use Modules\Ads\Models\Ad;

class AdDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "image",
            "name",
            "link",
            "type",
            "user_types",
            "expiration_date",
            "status",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn("action", function (Ad $model) {
                    return $this
                        ->edit(route("ads.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ADS))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ADS))
                        ->makeLabelledIcons();
                })
                ->addColumn("name", function (Ad $model) {
                    return $this->bold($model->name);
                })
                ->addColumn("type", function (Ad $model) {
                    return $this->badge(AdType::get_color($model->type), AdType::get_name($model->type));
                })
                ->addColumn("user_types", function (Ad $model) {
                    $badges = '';
                    foreach ($model->user_types as $userType) {
                        $badges .= $this->badge(UserType::get_color($userType), UserType::get_name($userType)) . ' ';
                    }
                    return $badges;
                })
                ->addColumn("link", function (Ad $model) {
                    return $this->link($model?->link);
                })
                ->addColumn("expiration_date", function (Ad $model) {
                    return $this->date($model->expiration_date);
                })
                ->addColumn("status", function (Ad $model) {
                    return $this->badge(Status::get_color($model->status), Status::get_name($model->status));
                })
                ->addColumn("image", function (Ad $model) {
                    return $this->image($model->getFirstMediaUrl(Ad::Image));
                })
                ->rawColumns(["action", "name", "type", "user_types", "link", "expiration_date", "status", "image"])
                ->make(true);
        } catch (Exception $e) {
            Log::error('AdDatatable Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the request.'], 500);
        }
    }

    private function query($request)
    {
        $query = Ad::query();

        if ($request->filled('type_filter')) {
            $query->where('type', $request->type_filter);
        }

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }

        if ($request->filled('user_types_filter')) {
            $query->whereJsonContains('user_types', $request->user_types_filter);
        }

        return $query->latest()->get();
    }
}
