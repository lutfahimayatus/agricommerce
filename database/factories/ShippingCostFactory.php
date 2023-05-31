<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingCost>
 */
class ShippingCostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cost' => fake()->numberBetween(100000, 900000),
            'province_code' => fake()->numberBetween(11, 19),
            'city_code' => fake()->numberBetween(1101, 1118),
        ];
    }
}
