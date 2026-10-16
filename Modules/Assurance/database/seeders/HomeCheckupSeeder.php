<?php

namespace Modules\Assurance\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Assurance\Models\HomeCheckup;
use Modules\Assurance\Models\HomeService;
use Modules\Locations\Models\Location;

class HomeCheckupSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    if (env('APP_ENV') !== 'local') {
      return;
    }

    $user = User::query()->firstOrCreate(
      ['email' => 'patient@example.com'],
      [
        'firstname' => 'Test',
        'lastname' => 'Patient',
        'phone' => '0777777777',
        'password' => bcrypt('password'),
        'type' => 'user',
        'status' => 'active',
      ]
    );

    $location = Location::query()->firstOrCreate(
      ['user_id' => $user->id],
      [
        'address' => 'Seed Address',
        'longitude' => 3.0588,
        'latitude' => 36.7538,
        'status' => \Modules\Locations\Constants\Status::ACTIVE,
      ]
    );

    $homeServices = HomeService::query()->get();

    HomeCheckup::factory()->count(5)->create([
      'user_id' => $user->id,
      'location_id' => $location->id,
    ])->each(function (HomeCheckup $homeCheckup) use ($homeServices) {
      if ($homeServices->isEmpty()) {
        return;
      }

      $services = $homeServices->random(min(3, $homeServices->count()));
      foreach ($services as $service) {
        $homeCheckup->services()->create([
          'home_service_id' => $service->id,
          'service_name' => $service->name,
          'service_price' => $service->price,
        ]);
      }
    });
  }
}
