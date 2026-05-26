<?php

namespace Modules\Assurance\Datatables;

use App\Constants\BloodType;
use App\Constants\Gender;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Assurance\Models\MedicalExamination;


class ExaminationDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "family-member",
      "total_price",
      "date",
      "is_paid",
      "action",
    ];
  }

  public function datatables($request, $userId)
  {
    try {
      return datatables($this->query($request, $userId))
        ->addColumn("action", function (MedicalExamination $model) {
          return $this
//            ->edit(route("patients.examinations.edit", [$model->familyMember->user->id, $model->id]), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_EXAMINATIONS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_EXAMINATIONS))
            ->makeLabelledIcons();
        })
        ->addColumn("family-member", function (MedicalExamination $model) {
          return $this->bold($model->familyMember->fullname);
        })
        ->addColumn("total_price", function (MedicalExamination $model) {
          return $this->money($model->total_price);
        })
        ->addColumn("date", function (MedicalExamination $model) {
          return $this->date($model->date);
        })
        ->addColumn("is_paid", function (MedicalExamination $model) {
          return $this->boolean($model->is_paid);
        })
        ->rawColumns(self::columns())
        ->make(true);
    } catch (Exception $e) {
      Log::error(get_class($this) . " Error " . $e->getMessage());
    }
  }

  public function query($request, $userId)
  {
    $filters = [
      'status_filter' => 'status',
    ];

    $query = MedicalExamination::query()
      ->with('familyMember')
      ->whereHas('familyMember', function ($query) use ($userId) {
        $query->where('user_id', $userId);
      })
      ->orderBy('date', 'desc');

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }
}
