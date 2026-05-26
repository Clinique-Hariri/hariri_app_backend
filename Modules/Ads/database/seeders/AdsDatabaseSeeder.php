<?php

namespace Modules\Ads\Database\Seeders;

use Illuminate\Http\File;
use Modules\Ads\Models\Ad;
use Illuminate\Database\Seeder;

class AdsDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $ads = Ad::factory()->count(10)->create();

    foreach ($ads as $ad) {
      $file = new File(public_path('assets/img/backgrounds/'.rand(1,18).'.jpg'));
      storeWebPWithSpatie($ad, $file, Ad::Image);
    }
  }
}
