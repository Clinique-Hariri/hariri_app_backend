<?php

namespace Modules\Assurance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Assurance\Models\HomeService;
use Modules\Categories\Constants\Status;

class HomeServiceFactory extends Factory
{
  protected $model = HomeService::class;

  public function definition(): array
  {
    return [
      'name' => $this->faker->word(),
      'price' => $this->faker->randomFloat(2, 0, 5000),
      'description' => $this->faker->sentence(),
      'status' => Status::ACTIVE,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ];
  }
}
