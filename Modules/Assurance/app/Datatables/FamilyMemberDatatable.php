<?php

namespace Modules\Assurance\Datatables;

use App\Constants\BloodType;
use App\Constants\Gender;
use App\Support\Enum\PermissionNames;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Assurance\Constants\FamilyMember\Status;
use Modules\Assurance\Models\FamilyMember;


class FamilyMemberDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "family-member",
      "phone",
      "gender",
      "blood_type",
      "family_relation",
      "status",
      "action",
    ];
  }

  public function datatables($request, $userId)
  {
    try {
      return datatables($this->query($request, $userId))
        ->addColumn("action", function (FamilyMember $model) {
          return $this
            ->edit(route("patients.family-members.edit", [$model->user->id, $model->id]), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS))
            ->delete($model->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS))
            ->redirectButton(route('patients.family-members.generate-insurance-card',[$model->user->id, $model->id]), __('assurance::app.insurance-card'), 'bx bx-credit-card',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS))
            ->makeLabelledIcons();
        })
        ->addColumn("family-member", function (FamilyMember $model) {
          return $this->thumbnailTitleMeta($model->avatar_url, $model->fullname, $model->insurance_number);
        })
        ->addColumn("phone", function (FamilyMember $model) {
          return $model->phone;
        })
        ->addColumn("avatar", function (FamilyMember $model) {
          return $this->image($model->avatar_url, $model->fullname);
        })
        ->addColumn("gender", function (FamilyMember $model) {
          return $this->badge(Gender::get_color($model->gender), Gender::get_name($model->gender));
        })
        ->addColumn("blood_type", function (FamilyMember $model) {
          return $model->blood_type ? $this->badge(BloodType::get_color($model->blood_type), BloodType::get_name($model->blood_type)) : '';
        })
        ->addColumn("family_relation", function (FamilyMember $model) {
          return $model->family_relation;
        })
        ->addColumn("status", function (FamilyMember $model) {
          return $this->badge(Status::get_color($model->status),Status::get_name($model->status));
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

    $query =FamilyMember::query()
      ->with(['user'])
      ->where('user_id', $userId);
//      ->whereNot('family_relation', 'self');

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    return $query->latest()->get();
  }
}
