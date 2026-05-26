<?php

namespace Modules\Orders\Datatables;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Orders\Constants\OrderType;
use Modules\Orders\Models\Order;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Modules\Orders\Constants\OrderStatus;

class OrderDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            "id",
            "user",
            "driver",
            "type",
            "status",
            "total_amount",
            "delivery_time",
            "action",
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
            ->addIndexColumn()
                ->addColumn("action", function (Order $model) {
                    $this
                    //->edit(route("orders.edit", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS))
                        ->show(route("orders.show", $model->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS));

                        foreach(OrderStatus::next($model->status) as $status){
                          $this->modalButton(
                            modalId: 'updateStatusModal',
                            text: OrderStatus::actions()[$status],
                            icon: OrderStatus::icons()[$status],
                            data: ['id' => $model->id, 'status' => $status],
                            show: true,
                            color: OrderStatus::colors()[$status]
                          );
                        }
                        $this->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_ORDERS));
                        return $this->makeLabelledIcons();
                })
                ->addColumn("user", function (Order $model) {
                    return $model->user ? $this->bold($model->user->fullname) : '-';
                })
                ->addColumn("driver", function (Order $model) {
                    return $model->driver ? $this->bold($model->driver->name) : '-';
                })
                ->addColumn("type", function (Order $model) {
                  return $this->badge(OrderType::colors()[$model->type], OrderType::all(true)[$model->type]);
                })
                ->addColumn("status", function (Order $model) {
                    return $this->badge(OrderStatus::colors()[$model->status], OrderStatus::all(true)[$model->status]);
                })
                ->addColumn("total_amount", function (Order $model) {
                  return $this->money($model->total_amount);
                })
                ->addColumn("delivery_time", function (Order $model) {
                    return $model->delivery_time ? $this->date($model->delivery_time) : '-';
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
        'type_filter' => 'type',
        'driver_filter' => 'driver_id',
      ];

      $query = Order::query();

      foreach ($filters as $param => $column) {
        if ($request->has($param) && $request->$param != '') {
          $query->where($column, $request->$param);
        }
      }

      if($request->date_filter){
        $query->whereDate('created_at', $request->date_filter);
      }

      return $query->with(['user', 'driver', 'location'])->latest()->get();

    }
}
