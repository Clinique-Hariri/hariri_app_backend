<?php

namespace Modules\Doctors\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Modules\Departments\Models\Department;
use Modules\Doctors\Constants\Doctor\Status;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;

class DoctorFactory extends Factory
{
  protected $model = Doctor::class;

  public function definition(): array
  {
    return [
      'firstname' => $this->faker->firstName(),
      'lastname' => $this->faker->lastName(),
      'bio' => $this->faker->paragraph(1),
      'status' => Status::ACTIVE,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now(),

      'speciality_id' => Speciality::InRandomOrder()->first() ?? Speciality::factory(),
      'department_id' => Department::InRandomOrder()->first() ?? Department::factory(),
    ];
  }
}
