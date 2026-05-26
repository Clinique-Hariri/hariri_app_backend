<?php

namespace Modules\Ads\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Ads\Constants\AdType;
use Modules\Ads\Constants\Status;
use Modules\Ads\Models\Ad;

class AdFactory extends Factory
{
    protected $model = Ad::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'type' => $this->faker->randomElement([AdType::NONE, AdType::INTERNAL, AdType::EXTERNAL]),
            'link' => $this->faker->url(),
            'expiration_date' => $this->faker->dateTimeBetween('+1 week', '+1 year'),
            'status' => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
        ];
    }

    /**
     * Indicate that the ad is active.
     */
    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Status::ACTIVE,
                'expiration_date' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            ];
        });
    }

    /**
     * Indicate that the ad is internal.
     */
    public function internal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => AdType::INTERNAL,
            ];
        });
    }

    /**
     * Indicate that the ad is external.
     */
    public function external(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => AdType::EXTERNAL,
            ];
        });
    }
}
