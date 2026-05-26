<?php

namespace App\Datatables;

use App\Constants\Gender;
use App\Constants\Statuses\UserStatus;
use App\Models\User;
use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use App\Traits\DataTableActionsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class UserDatatable
{
  use DataTableActionsTrait;
  public static function columns(): array
  {
    return [
      "user",
      "phone",
      "gender",
      "type",
      "role",
      "is_insured",
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
            ->edit(route("users.edit", $user->id), Auth::user()->hasPermissionTo(PermissionNames::MANAGE_USERS))
            ->delete($user->id, Auth::user()->hasPermissionTo(PermissionNames::MANAGE_USERS))
            ->modalButton('activate-modal', __('app.activate'), 'bx bx-check-circle', ['id' => $user->id],
                Auth::user()->hasPermissionTo(PermissionNames::MANAGE_USERS) && $user->status === UserStatus::BANNED)
            ->modalButton('suspend-modal', __('app.suspend'), 'bx bx-x-circle', ['id' => $user->id],
                Auth::user()->hasPermissionTo(PermissionNames::MANAGE_USERS) && $user->status === UserStatus::ACTIVE)
            ->make();
        })
        ->addColumn("user", function (User $user) {
          return $this->thumbnailTitleMeta($user->avatar_url, $user->fullname, $user->email, route('users.edit', $user->id));
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
      'user_type_filter' => 'type',
      'user_status_filter' => 'status',
    ];

    $query =User::query();

    foreach ($filters as $param => $column) {
      if ($request->has($param) && $request->$param != '') {
        $query->where($column, $request->$param);
      }
    }

    if ($request->has('user_role_filter') && $request->user_role_filter != '') {
      $query->role($request->user_role_filter);
    }

    return $query->latest()->get();
  }
}
