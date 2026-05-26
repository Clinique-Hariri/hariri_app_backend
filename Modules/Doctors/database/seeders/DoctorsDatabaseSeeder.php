<?php

namespace Modules\Doctors\Database\Seeders;

use App\Support\Enum\WeekDays;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Modules\Doctors\Constants\Status;
use Modules\Doctors\Models\Doctor;
use Modules\Doctors\Models\Speciality;
use Symfony\Component\HttpFoundation\File\File;

class DoctorsDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Speciality::factory()
      ->count(5)
      ->create();

    $doctors = Doctor::factory()
      ->count(5)
      ->create();

    foreach ($doctors as $doctor) {
      $filePath = storeWebP(new File(public_path('assets/img/avatars/1.png')), 'uploads/doctors/avatars');
      $doctor->update(['avatar' => $filePath]);

      //add schedules
      foreach (WeekDays::lists() as $day) {
        if ($day !== WeekDays::FRIDAY) {
          $schedules = $doctor->schedules()->create([
            'day_of_week' => $day,
            'status' => Status::ACTIVE,
          ]);

          //add working periods
          $schedules->workingPeriods()->create([
            'start_time' => Carbon::parse('08:00:00')->toTimeString(),
            'end_time' => Carbon::parse('12:00:00')->toTimeString(),
          ]);
          $schedules->workingPeriods()->create([
            'start_time' => Carbon::parse('13:00:00')->toTimeString(),
            'end_time' => Carbon::parse('17:00:00')->toTimeString(),
          ]);
        }
      }
    }
  }
}
