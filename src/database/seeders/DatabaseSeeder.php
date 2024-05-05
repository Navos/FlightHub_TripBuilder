<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Flight;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $airport_yyc = Airport::factory()->create([
            'code' => 'YYC',
            'name' => 'Calgary International Airport',
            'country_code' => 'CA',
            'region_code' => 'AB',
            'city_code' => 'YYC',
            'city' => 'Calgary',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Edmonton',
        ]);
        $airport_yow = Airport::factory()->create([
            'code' => 'YOW',
            'name' => 'Ottawa International Airport',
            'country_code' => 'CA',
            'region_code' => 'ON',
            'city_code' => 'YOW',
            'city' => 'Ottawa',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Toronto',
        ]);
        $airport_yeg = Airport::factory()->create([
            'code' => 'YEG',
            'name' => 'Edmonton International Airport',
            'country_code' => 'CA',
            'region_code' => 'AB',
            'city_code' => 'YEG',
            'city' => 'Edmonton',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Edmonton',
        ]);
        $airport_yvr = Airport::factory()->create([
            'code' => 'YVR',
            'name' => 'Vancouver International Airport',
            'country_code' => 'CA',
            'region_code' => 'BC',
            'city_code' => 'YVR',
            'city' => 'Vancouver',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Vancouver',
        ]);
        $airport_ywg = Airport::factory()->create([
            'code' => 'YWG',
            'name' => 'Richardson International Airport',
            'country_code' => 'CA',
            'region_code' => 'MA',
            'city_code' => 'YWG',
            'city' => 'Winnipeg',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Winnipeg',
        ]);
        $airport_yyt = Airport::factory()->create([
            'code' => 'YYT',
            'name' => 'St. John\'s International Airport',
            'country_code' => 'CA',
            'region_code' => 'NL',
            'city_code' => 'YYT',
            'city' => 'St. John\'s',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/St_Johns',
        ]);
        $airport_yyz = Airport::factory()->create([
            'code' => 'YYZ',
            'name' => 'Pearson International Airport',
            'country_code' => 'CA',
            'region_code' => 'ON',
            'city_code' => 'YTO',
            'city' => 'Toronto',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Toronto',
        ]);
        $airport_yul = Airport::factory()->create([
            'code' => 'YUL',
            'name' => 'Pierre Elliott Trudeau International Airport',
            'country_code' => 'CA',
            'region_code' => 'QC',
            'city_code' => 'YMQ',
            'city' => 'Montreal',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Montreal',
        ]);
        $airport_yqg = Airport::factory()->create([
            'code' => 'YQB',
            'name' => 'Jean Lesage International Airport',
            'country_code' => 'CA',
            'region_code' => 'QC',
            'city_code' => 'YQB',
            'city' => 'Quebec City',
            'latitude' => 0.0,
            'longitude' => 0.0,
            'timezone' => 'America/Montreal',
        ]);
        $airline_ac = Airline::factory()->create([
            'code' => 'AC',
            'name' => 'Air Canada',
        ]);
        $airline_ts = Airline::factory()->create([
            'code' => 'TS',
            'name' => 'Air Transat',
        ]);
        $airline_qk = Airline::factory()->create([
            'code' => 'QK',
            'name' => 'Jazz Aviation',
        ]);
        $airline_ws = Airline::factory()->create([
            'code' => 'WS',
            'name' => 'WestJet',
        ]);

        Flight::factory()->create([
            'number' => '301',
            'airline_id' => $airline_ac->id,
            'departure_airport_id' => $airport_yul->id,
            'departure_time' => '07:35',
            'arrival_airport_id' => $airport_yvr->id,
            'arrival_time' => '10:05',
            'price' => '273.23'
        ]);
        Flight::factory()->create([
            'number' => '302',
            'airline_id' => $airline_ac->id,
            'departure_airport_id' => $airport_yvr->id,
            'departure_time' => '11:30',
            'arrival_airport_id' => $airport_yul->id,
            'arrival_time' => '19:11',
            'price' => '220.63'
        ]);

        Flight::factory()->create([
            'number' => '201',
            'airline_id' => $airline_ts->id,
            'departure_airport_id' => $airport_yul->id,
            'departure_time' => '9:35',
            'arrival_airport_id' => $airport_yvr->id,
            'arrival_time' => '12:05',
            'price' => '243.23'
        ]);
        Flight::factory()->create([
            'number' => '202',
            'airline_id' => $airline_ts->id,
            'departure_airport_id' => $airport_yvr->id,
            'departure_time' => '06:30',
            'arrival_airport_id' => $airport_yul->id,
            'arrival_time' => '16:11',
            'price' => '240.63'
        ]);
    }
}
