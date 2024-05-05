<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => 'round_trip',
            'total_price' => fake()->randomFloat(2, 200, 500),
            'first_flight_id' => Flight::factory(),
            'first_flight_time' => fake()->iso8601(),
            'second_flight_id' => Flight::factory(),
            'second_flight_time' => fake()->iso8601(),
        ];
    }
}
