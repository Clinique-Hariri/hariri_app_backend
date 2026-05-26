<?php

namespace Modules\Products\database\factories;

use Modules\Products\Models\Stock;
use Modules\Products\Constants\Type;
use Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        $products = Product::pluck('id')->toArray();

        return [
            'product_id' => $this->faker->randomElement($products),
            'purchase_price' => $this->faker->randomFloat(2, 10, 1000),
            'quantity' => $this->faker->numberBetween(1, 100),
            'type' => Type::MANUAL,
            'batch_number' => $this->faker->unique()->regexify('[A-Z0-9]{8}'),
            'expiration_date' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
        ];
    }
}
