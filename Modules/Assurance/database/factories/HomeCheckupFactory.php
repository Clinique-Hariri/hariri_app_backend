<?php

namespace Modules\Assurance\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Assurance\Constants\HomeCheckup\Status;
use Modules\Assurance\Constants\HomeCheckup\VisitType;
use Modules\Assurance\Models\HomeCheckup;
use Modules\Locations\Models\Location;

class HomeCheckupFactory extends Factory
{
  protected $model = HomeCheckup::class;

  public function definition(): array
  {
    $visitType = $this->faker->randomElement(VisitType::all());

    return [
      'user_id' => User::factory(),
      'location_id' => Location::factory(),
      'patient_number' => $this->faker->optional()->numerify('PT-####'),
      'patient_fullname' => $this->faker->name(),
      'patient_phone' => $this->faker->phoneNumber(),
      'patient_birthdate' => $this->faker->optional()->date(),
      'notes' => $this->faker->sentence(),
      'preferred_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
      'preferred_time' => $this->faker->time('H:i'),
      'status' => $this->faker->randomElement(Status::all()),
      'visit_type' => $visitType,
      'reviewed_at' => null,
      'rejection_reason' => null,
      'base_price' => VisitType::get_price($visitType),
      'total_price' => $this->faker->randomFloat(2, 0, 5000),
    ];
  }

  public function pending(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => Status::PENDING,
      'reviewed_at' => null,
      'rejection_reason' => null,
    ]);
  }

  public function approved(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => Status::APPROVED,
      'reviewed_at' => now(),
      'rejection_reason' => null,
    ]);
  }

  public function rejected(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => Status::REJECTED,
      'reviewed_at' => now(),
      'rejection_reason' => $this->faker->sentence(),
    ]);
  }
}
