<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Constants\Gender;
use App\Datatables\UserDatatable;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Transactions\Constants\Status;
use Modules\Transactions\Constants\Type;

class UsersController extends Controller
{

  public function index(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }

    $users = new UserDatatable();
    if ($request->wantsJson()) {
      return $users->datatables($request);
    }
    return view("dashboard.user.list")->with([
      "columns" => $users::columns(),
    ]);
  }

  public function create()
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }
    return view("dashboard.user.create-edit")->with(["edit" => false]);
  }

  public function edit($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }
    return view("dashboard.user.create-edit")->with(["edit" => true, "user" => User::findOrFail($id)]);
  }

  public function store(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }
    $data = $request->validate([
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      //'username' => 'required|string|max:255|unique:users',
      'email' => 'required|email|unique:users,email',
      'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:users,phone',
      'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
      'gender' => 'required|string|in:' . implode(',', Gender::all()),
      'password' => 'required|string|min:8',
      'type' => 'required|string|in:' . implode(',', UserTypes::all()),
      'role' => 'nullable|string|in:' . implode(',', UserRoles::all()),
    ]);

    try {
      DB::beginTransaction();
      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/avatars');
      } else {
        $file = new File(public_path('assets/img/avatars/1.png'));
        $data['avatar'] = storeWebP($file, 'uploads/users/avatars');
      }
      $data['password'] = Hash::make($data['password']);
      $user = User::create($data);

      if ($request->role) {
        $user->assignRole($request->role);
      }

      DB::commit();
      return redirect()->route('users.index')->with('success', __('app.created_successfully', ['name' => __('app.user')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    } catch (\Throwable $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }

  }

  public function update(Request $request, $id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }
    $data = $request->validate([
      'firstname' => 'required|string|max:255',
      'lastname' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $id,
      'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/',
      'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
      'gender' => 'required|string|in:' . implode(',', Gender::all()),
      'type' => 'required|string|in:' . implode(',', UserTypes::all()),
      'role' => 'nullable|string|in:' . implode(',', UserRoles::all()),
    ]);

    try {
      DB::beginTransaction();
      $user = User::findOrFail($id);
      if ($request->hasFile('avatar')) {
        $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/avatars');
      }
      $user->update($data);

      if ($request->role) {
        $user->syncRoles([$request->role]);
      }

      DB::commit();
      return redirect()->route('users.index')->with('success', __('app.updated_successfully', ['name' => __('app.user')]));
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function destroy($id)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }

    try {
      $user = User::findOrFail($id);
      $user->syncRoles([]);
      $user->delete();
      return redirect()->route('users.index')->with('success', __('app.deleted_successfully', ['name' => __('app.user')]));
    } catch (\Exception $e) {
      return redirect()->back()->with('error', $e->getMessage());
    }
  }

  public function updateStatus(Request $request)
  {
    if (!auth()->user()->hasPermissionTo(PermissionNames::MANAGE_USERS)) {
      return redirect()->route('unauthorized');
    }

    $data = $request->validate([
      'id' => 'required|exists:users,id',
      'status' => 'required|string|in:active,banned',
      'confirmed' => 'required|accepted',
    ]);

    try {
      DB::beginTransaction();
      $user = User::findOrFail($data['id']);
      $user->status = $data['status'];
      $user->save();
      DB::commit();

      $statusMessage = $data['status'] === 'active'
        ? __('user.activated_successfully')
        : __('user.suspended_successfully');

      return redirect()->route('users.index')->with('success', $statusMessage);
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', $e->getMessage());
    }
  }
}
