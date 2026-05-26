<?php

namespace Modules\Documentation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Documentation\Constants\DocumentationKey;
use Modules\Documentation\Models\Documentation;

class DocumentationDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    foreach (DocumentationKey::all() as $key) {
      Documentation::firstOrCreate(['key' => $key]);
    }
  }
}
