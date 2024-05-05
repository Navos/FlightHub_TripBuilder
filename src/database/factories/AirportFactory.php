<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => Str::random(3),
            'city_code' => Str::random(3),
            'name' => Str::random(10),
            'city' => Str::random(10),
            'country_code' => Str::random(2),
            'region_code' => Str::random(2),
            'latitude' => fake()->randomFloat(4, 0, 180),
            'longitude' => fake()->randomFloat(4, 0, 180),
            'timezone' => fake()->timezone(),
        ];
    }
}
