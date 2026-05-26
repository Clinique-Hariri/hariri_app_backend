<?php

namespace Modules\Assurance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Assurance\Models\MedicalService;
use Modules\Categories\Constants\Status;

class MedicalServiceFactory extends Factory
{
  protected $model = MedicalService::class;

  public function definition(): array
  {
    return [
      'name' => $this->faker->word(),
      'price' => $this->faker->randomFloat(2, 0, 5000),
      'status' => Status::ACTIVE,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ];
  }
}
