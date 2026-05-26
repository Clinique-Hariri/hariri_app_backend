<?php

namespace Modules\Departments\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Departments\Models\Department;
use Symfony\Component\HttpFoundation\File\File;

class DepartmentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'local') {
            $departments = Department::factory()->count(5)->create();
            foreach ($departments as $department) {
                $file = new File(public_path('assets/img/avatars/1.png'));
                storeWebPWithSpatie($department, $file, Department::IMAGE);
            }
        }
    }
}
