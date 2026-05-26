<?php

namespace Database\Seeders;

use App\Constants\Gender;
use App\Models\User;
use App\Support\Enum\UserRoles;
use App\Support\Enum\UserTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $user = User::create([
      'firstname' => 'Super',
      'lastname' => 'Admin',
      'email' => 'admin@admin.com',
      'phone' => '0666666666',
      'password' => bcrypt('admin123'),
      'type' => UserTypes::ADMIN,
      'gender' => Gender::MALE,
      'birthdate' => '1990-01-01',
      'full_address' => 'Algiers, Algeria',
      'status' => 'active',
      //        'city_id' => 1,
    ]);
      $filePath = storeWebP(new File(public_path('assets/img/avatars/1.png')), 'uploads/users/avatars');
      $user->avatar = $filePath;
      $user->save();
    $user->assignRole(UserRoles::SUPER_ADMIN);
  }
}
