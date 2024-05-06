<?php

namespace Tests\Feature;

use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testFindTrips_Validation(): void {
        $response = $this->get('/api/findTrips');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'type' => ['The type field is required.'],
            ]
        ]);

        $response = $this->get('/api/findTrips?type=unknown');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'type' => ['The selected type is invalid.'],
            ]
        ]);

        $response = $this->get('/api/findTrips?type=round_trip');
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'departure_airport' => ['The departure airport field is required.'],
                'arrival_airport' => ['The arrival airport field is required.'],
                'departure_date' => ['The departure date field is required.'],
                'return_date' => ['The return date field is required.'],
            ]
        ]);
    }

    public function testFindTrips_OneWay(): void {
        $response = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&type=one_way');
        $response->assertStatus(200);
        $response->assertJson([
            'departure_flights' => [
                [
                    'number' => '301',
                    'datetime' => '2024-05-01T07:35:00-04:00',
                    'price' => '273.23',
                    'airline' => 'AC'
                ],
                [
                    'number' => '201',
                    'datetime' => '2024-05-01T09:35:00-04:00',
                    'price' => '243.23',
                    'airline' => 'TS'
                ]
            ]
        ]);
    }

    public function testFindTrips_OneWay_PreferredAirlines(): void {
        $response = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&type=one_way&preferred_airline=TS');
        $response->assertStatus(200);
        $response->assertJson([
            'departure_flights' => [
                [
                    'number' => '201',
                    'datetime' => '2024-05-01T09:35:00-04:00',
                    'price' => '243.23',
                    'airline' => 'TS'
                ]
            ]
        ]);
    }

    public function testFindTrips_RoundTrip(): void {
        $response = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&return_date=2024-05-10&type=round_trip');
        $response->assertStatus(200);
        $response->assertJson([
            'departure_flights' => [
                [
                    'number' => '301',
                    'datetime' => '2024-05-01T07:35:00-04:00',
                    'price' => '273.23',
                    'airline' => 'AC'
                ],
                [
                    'number' => '201',
                    'datetime' => '2024-05-01T09:35:00-04:00',
                    'price' => '243.23',
                    'airline' => 'TS'
                ]
            ],
            'return_flights' => [
                [
                    'number' => '302',
                    'datetime' => '2024-05-10T11:30:00-07:00',
                    'price' => '220.63',
                    'airline' => 'AC'
                ],
                [
                    'number' => '202',
                    'datetime' => '2024-05-10T06:30:00-07:00',
                    'price' => '240.63',
                    'airline' => 'TS'
                ]
            ]
        ]);
    }

    public function testFindTrips_RoundTrip_PreferredAirline(): void {
        $response = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&return_date=2024-05-10&type=round_trip&preferred_airline=TS');
        $response->assertStatus(200);
        $response->assertJson([
            'departure_flights' => [
                [
                    'number' => '201',
                    'datetime' => '2024-05-01T09:35:00-04:00',
                    'price' => '243.23',
                    'airline' => 'TS'
                ]
            ],
            'return_flights' => [
                [
                    'number' => '202',
                    'datetime' => '2024-05-10T06:30:00-07:00',
                    'price' => '240.63',
                    'airline' => 'TS'
                ]
            ]
        ]);
    }

    public function testSaveTrip_Validation(): void {
        $response = $this->post('/api/saveTrip', []);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'type' => ['The type field is required.'],
                'first_flight_id' => ['The first flight id field is required.'],
                'first_flight_time' => ['The first flight time field is required.'],
            ]
        ]);

        $response = $this->post('/api/saveTrip', ['type' => 'one_way','first_flight_id' => '-1','first_flight_time' => '']);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'first_flight_id' => ['The selected first flight id is invalid.'],
            ]
        ]);

        $response = $this->post('/api/saveTrip', ['type' => 'one_way','first_flight_id' => 1,'first_flight_time' => 'asdasd']);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'first_flight_time' => ['The first flight time field must be a valid date.'],
            ]
        ]);
    }

    public function testSaveTrip_OneWay(): void {
        $findTripResponse = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&type=one_way');
        $findTripResponse->assertStatus(200);
        $foundFlights = $findTripResponse->baseResponse->getOriginalContent();

        $saveTripResponse = $this->post('/api/saveTrip', [
            'type' => 'one_way',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
        ]);
        $saveTripResponse->assertStatus(201);
        $saveTripResponse->assertJson([
            'type' => 'one_way',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
            'total_price' => $foundFlights['departure_flights'][0]['price']
        ]);

        $this->assertDatabaseHas('trips', [
            'type' => 'one_way',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
            'total_price' => $foundFlights['departure_flights'][0]['price']
        ]);
    }

    public function testSaveTrip_RoundTrip(): void {
        $findTripResponse = $this->get('/api/findTrips?departure_airport=YUL&arrival_airport=YVR&departure_date=2024-05-01&return_date=2024-05-10&type=round_trip');
        $findTripResponse->assertStatus(200);
        $foundFlights = $findTripResponse->baseResponse->getOriginalContent();

        $saveTripResponse = $this->post('/api/saveTrip', [
            'type' => 'round_trip',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
            'second_flight_id' => $foundFlights['return_flights'][0]['id'],
            'second_flight_time' => $foundFlights['return_flights'][0]['datetime'],
        ]);
        $saveTripResponse->assertStatus(201);
        $saveTripResponse->assertJson([
            'type' => 'round_trip',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
            'second_flight_id' => $foundFlights['return_flights'][0]['id'],
            'second_flight_time' => $foundFlights['return_flights'][0]['datetime'],
            'total_price' => floatval($foundFlights['departure_flights'][0]['price']) + floatval($foundFlights['return_flights'][0]['price'])
        ]);

        $this->assertDatabaseHas('trips', [
            'type' => 'round_trip',
            'first_flight_id' => $foundFlights['departure_flights'][0]['id'],
            'first_flight_time' => $foundFlights['departure_flights'][0]['datetime'],
            'second_flight_id' => $foundFlights['return_flights'][0]['id'],
            'second_flight_time' => $foundFlights['return_flights'][0]['datetime'],
            'total_price' => floatval($foundFlights['departure_flights'][0]['price']) + floatval($foundFlights['return_flights'][0]['price'])
        ]);
    }

    public function testGetTrips_validate(): void {
        $getTripResponse = $this->get('/api/getTrips?sort_column=id&page=1');
        $getTripResponse->assertStatus(422);
        $getTripResponse->assertJson([
            "errors" => [
                "sort_direction" => ["The sort direction field is required when sort column is present."],
                "limit" => ["The limit field is required when page is present."]
            ]
        ]);

        $getTripResponse = $this->get('/api/getTrips?sort_direction=desc&limit=2');
        $getTripResponse->assertStatus(422);
        $getTripResponse->assertJson([
            "errors" => [
                "sort_column" => ["The sort column field is required when sort direction is present."],
                "page" => ["The page field is required when limit is present."]
            ]
        ]);
    }

    public function testGetTrips_all(): void {
        $flight1 = Trip::factory()->create([
            'total_price' => '100',
            'type' => 'round_trip'
        ]);
        $flight2 = Trip::factory()->create([
            'total_price' => '200',
            'type' => 'one_way'
        ]);
        $flight3 = Trip::factory()->create([
            'total_price' => '300',
            'type' => 'round_trip'
        ]);

        $getTripResponse = $this->get('/api/getTrips');
        $getTripResponse->assertStatus(200);
        $getTripResponse->assertJson([
            ['id' => $flight1->id],
            ['id' => $flight2->id],
            ['id' => $flight3->id],
        ]);
    }

    public function testGetTrips_sortAndPaginate(): void {
        $flight1 = Trip::factory()->create([
            'total_price' => '100',
            'type' => 'round_trip'
        ]);
        $flight2 = Trip::factory()->create([
            'total_price' => '200',
            'type' => 'one_way'
        ]);
        $flight3 = Trip::factory()->create([
            'total_price' => '300',
            'type' => 'round_trip'
        ]);

        $getTripResponse = $this->get('/api/getTrips?sort_column=id&sort_direction=desc&limit=2&page=1');
        $getTripResponse->assertStatus(200);
        $getTripResponse->assertJson([
            ['id' => $flight3->id],
            ['id' => $flight2->id],
        ]);

        $getTripResponse = $this->get('/api/getTrips?sort_column=id&sort_direction=desc&limit=2&page=20');
        $getTripResponse->assertStatus(200);
        $getTripResponse->assertExactJson([]);
    }
}
