<?php

namespace Modules\Categories\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Categories\Constants\Status;
use Modules\Categories\Models\Category;

class CategoryFactory extends Factory
{
  protected $model = Category::class;

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
