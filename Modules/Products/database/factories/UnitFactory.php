<?php

namespace Modules\Products\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Products\Models\Unit;

class UnitFactory extends Factory
{
  protected $model = Unit::class;

  public function definition(): array
  {
    return [
      'name' => [
        'en' => $this->faker->word(),
        'ar' => $this->faker->word(),
        'fr' => $this->faker->word(),
      ],
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ];
  }
}
