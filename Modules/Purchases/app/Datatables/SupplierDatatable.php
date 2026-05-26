<?php

namespace Modules\Purchases\Datatables;

use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Purchases\Models\Supplier;

class SupplierDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "supplier",
            "purchases_count",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
            ->addIndexColumn()
                ->addColumn("action", function (Supplier $model) {
                    return $this
                        ->edit(route("suppliers.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES))
                        ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PURCHASES))
                        ->makeLabelledIcons();
                })
                ->addColumn("supplier", function (Supplier $model) {
                  return $this->thumbnailTitleMeta(
                    $model->getFirstMediaUrl(Supplier::IMAGE),
                    $model->name,
                    $model->phone,
                    route('suppliers.edit', $model->id)
                  );
                })
                ->addColumn("purchases_count", function (Supplier $model) {
                    return $model->purchases_count;
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function query($request)
    {
        return Supplier::with('location')->latest()->get();
    }
}
