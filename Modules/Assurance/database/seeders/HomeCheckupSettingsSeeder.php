<?php

namespace Modules\Assurance\Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Modules\Assurance\Constants\HomeCheckup\VisitType;

class HomeCheckupSettingsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    foreach (VisitType::PRICE_SETTING_KEYS as $type => $key) {
      Setting::updateOrCreate(
        ['key' => $key],
        ['value' => VisitType::DEFAULT_PRICES[$type]],
      );
    }
  }
}
