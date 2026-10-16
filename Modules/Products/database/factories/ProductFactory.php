<?php

namespace Modules\Products\database\factories;

use Modules\Products\Models\Unit;
use Modules\Products\Models\Product;
use Modules\Products\Constants\Status;
use Modules\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $categories = Category::pluck('id')->toArray();
        $units = Unit::pluck('id')->toArray();

        return [
            'category_id' => $this->faker->randomElement($categories),
            'unit_id' => $this->faker->randomElement($units),
            'name' => $this->faker->word(),
            'barcode' => $this->faker->ean13(),
            //'reference' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{5}'),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'unit_count' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(Status::all()),
            'is_compensable' => $this->faker->boolean(),
            'with_prescription' => $this->faker->boolean(),
        ];
    }
}
