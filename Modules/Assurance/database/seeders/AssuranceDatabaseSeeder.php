<?php

namespace Modules\Assurance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Assurance\Models\MedicalService;
use Symfony\Component\HttpFoundation\File\File;

class AssuranceDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $this->call(HomeServiceSeeder::class);

    if (env('APP_ENV') === 'local') {
      $services = MedicalService::factory()->count(5)->create();
      foreach ($services as $service) {
        $file = new File(public_path('assets/img/avatars/1.png'));
        storeWebPWithSpatie($service, $file, MedicalService::IMAGE);
      }
    }
  }
}
