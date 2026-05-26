<?php

namespace Modules\Categories\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Categories\Models\Category;
use Symfony\Component\HttpFoundation\File\File;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'local') {
            $categories = Category::factory()->count(5)->create();
            foreach ($categories as $category) {
                $file = new File(public_path('assets/img/avatars/1.png'));
                storeWebPWithSpatie($category, $file, Category::IMAGE);
            }
        }
    }
}
