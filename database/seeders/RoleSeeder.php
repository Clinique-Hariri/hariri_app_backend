<?php
namespace Database\Seeders;

use App\Support\Enum\UserRoles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Role::firstOrcreate(['name' => UserRoles::SUPER_ADMIN]);
    Role::firstOrcreate(['name' => UserRoles::ADMIN]);
    Role::firstOrcreate(['name' => UserRoles::CLINIC_ADMIN]);
    Role::firstOrcreate(['name' => UserRoles::PHARMACY_ADMIN]);
  }
}
