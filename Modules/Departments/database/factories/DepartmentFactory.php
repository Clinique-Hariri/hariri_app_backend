<?php

namespace Modules\Departments\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Departments\Constants\Status;
use Modules\Departments\Models\Department;

class DepartmentFactory extends Factory
{
  protected $model = Department::class;

  public function definition(): array
  {
    return [
      'name' => [
        'en' => $this->faker->word(),
        'ar' => $this->faker->word(),
        'fr' => $this->faker->word(),
      ],
      'description' => '<p>The cardiology department focuses on diagnosing and treating disorders of the heart and blood vessels.</p>
<p>Our specialists provide care for a wide range of conditions, from hypertension and arrhythmias to advanced heart failure.</p>
<p>Equipped with state-of-the-art diagnostic tools and a multidisciplinary team, we ensure accurate assessments and personalized treatment plans.</p>',
      'status' => Status::ACTIVE,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),
    ];
  }
}
