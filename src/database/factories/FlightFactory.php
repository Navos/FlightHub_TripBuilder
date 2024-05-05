<?php

namespace Database\Factories;

use App\Models\Airline;
use App\Models\Airport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'airline_id' => Airline::factory(),
            'departure_airport_id' => Airport::factory(),
            'arrival_airport_id' => Airport::factory(),
            'number' => strval(fake()->randomNumber(3)),
            'departure_time' => fake()->time('H:i'),
            'arrival_time' => fake()->time('H:i'),
            'price' => strval(fake()->randomFloat(2, 100, 300)),
        ];
    }
}
