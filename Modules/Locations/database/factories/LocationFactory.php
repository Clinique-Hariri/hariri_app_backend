<?php

namespace Modules\Locations\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Locations\Constants\Status;
use Modules\Locations\Models\Location;

class LocationFactory extends Factory
{
  protected $model = Location::class;

  public function definition(): array
  {
    return [
      'user_id' => User::factory(),
      'address' => $this->faker->address(),
      'longitude' => $this->faker->longitude(),
      'latitude' => $this->faker->latitude(),
      'status' => Status::ACTIVE,
    ];
  }
}
