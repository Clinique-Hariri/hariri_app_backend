<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Http\File;
use Illuminate\Database\Seeder;
use Modules\Products\Models\Unit;
use Modules\Products\Models\Stock;
use Modules\Products\Models\Product;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (env('APP_ENV') === 'local') {
            Unit::factory()->count(5)->create();
            $products = Product::factory()->count(20)->create();
            Stock::factory()->count(12)->create();

            foreach ($products as $product) {
                $file = new File(public_path('assets/img/ecommerce-images/product-'.rand(1,26).'.png'));
                storeWebPWithSpatie($product, $file, Product::Image);
            }
        }
    }
}
