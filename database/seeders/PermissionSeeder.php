<?php
namespace Database\Seeders;


use App\Support\Enum\PermissionNames;
use App\Support\Enum\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {

    foreach (PermissionNames::all() as $permission) {
      Permission::findOrCreate($permission);
    }

    //assign super admin permissions
    $superAdminPermissions = PermissionNames::all();

    Permission::whereIn('name', $superAdminPermissions)
      ->each(function ($permission) {
        $permission->assignRole([UserRoles::SUPER_ADMIN]);
      });

    //assign admin permissions
    $adminPermissions = [
    ];

    Permission::whereIn('name', $adminPermissions)
      ->each(function ($permission) {
        $permission->assignRole([UserRoles::ADMIN]);
      });
  }
}
