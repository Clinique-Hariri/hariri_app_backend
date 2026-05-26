<?php

namespace Modules\Assurance\Datatables;

use App\Constants\Gender;
use App\Constants\Statuses\UserStatus;
use App\Models\User;
use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;


class PatientDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "patient",
      "phone",
      "gender",
      "is_insured",
      "insurance_date",
      "wallet_balance",
      "status",
      "action",
    ];
  }

  public function datatables($request)
  {
    try {
      return datatables($this->query($request))
        ->addColumn("action", function (User $user) {
          return $this
            ->show(route("patients.show", $user->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS))
            ->redirectButton(route("patients.family-members.index", $user->id), __('assurance::app.family-members'), 'bx bx-group',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS) && $user->is_insured)
            ->redirectButton(route("patients.examinations.index", $user->id), __('assurance::app.examinations'), 'bx bx-heart',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS) && $user->is_insured)
            ->redirectButton(route('patients.activate-insurance', $user->id), __('assurance::app.activate-insurance'), 'bx bx-shield',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS) && !$user->is_insured)
            ->redirectButton(route('patients.add-funds', $user->id), __('assurance::app.add-funds'), 'bx bx-wallet',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS) && $user->is_insured)
            ->redirectButton(route('patients.generate-insurance-card', $user->id), __('assurance::app.insurance-card'), 'bx bx-credit-card',
              Auth::user()->hasPermissionTo(PermissionNames::MANAGE_PATIENTS) && $user->is_insured)
            ->make();
        })
        ->addColumn("patient", function (User $user) {
          return $this->thumbnailTitleMeta($user->avatar_url, $user->fullname, $user->email);
        })
        ->addColumn("phone", function (User $user) {
          return $user->phone;
        })
        ->addColumn("gender", function (User $user) {
          return $this->badge(Gender::get_color($user->gender), Gender::get_name($user->gender));
        })
        ->addColumn("type", function (User $user) {
          return $this->badge(UserTypes::get_color($user->type),UserTypes::get_name($user->type), 'bx bx-user');
        })
        ->addColumn("role", function (User $user) {
          $role = $user->getRoleNames()->first()? UserRoles::get_name($user->getRoleNames()->first()) : "-";
          return $this->badge('secondary', $role);
        })
        ->addColumn("is_insured", function (User $user) {
          return $this->boolean($user->is_insured);
        })
        ->addColumn("insurance_date", function (User $user) {
          return $user->insurance_date ? $user->insurance_date->format('Y-m-d') : '-';
        })
        ->addColumn("wallet_balance", function (User $user) {
          return $this->money($user->wallet_balance);
        })
        ->addColumn("status", function (User $user) {
          return $this->badge(UserStatus::get_color($user->status),UserStatus::get_name($user->status));
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

    $query = User::query()
            ->where('type', UserTypes::USER);

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }
    return $query->latest()->get();
  }
}
