<?php

namespace Modules\Assurance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Assurance\Models\HomeService;
use Symfony\Component\HttpFoundation\File\File;

class HomeServiceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    if (env('APP_ENV') === 'local') {
      $services = HomeService::factory()->count(5)->create();
      foreach ($services as $service) {
        $file = new File(public_path('assets/img/avatars/1.png'));
        storeWebPWithSpatie($service, $file, HomeService::IMAGE);
      }
    }
  }
}
