<?php

namespace Modules\Doctors\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Doctors\Constants\Speciality\Status;
use Modules\Doctors\Models\Speciality;

class SpecialityFactory extends Factory
{
  protected $model = Speciality::class;

  public function definition(): array
  {
    return [
      'name' => [
        'en' => $this->faker->word(),
        'ar' => $this->faker->word(),
        'fr' => $this->faker->word(),
      ],
      'status' => Status::ACTIVE,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ];
  }
}
