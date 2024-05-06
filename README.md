# FlightHub Trip Builder

## <ins>Setup</ins>

1. Make sure you have [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed
2. In a terminal, make your way to the project folder.
3. Run `docker-compose up --detach`
4. After a few minutes, the server will be up at http://localhost:8000/

## <ins>Tests</ins>

To run the tests, use the command `docker exec -ti flighthubtestproject-app-1 php /app/artisan test`

## <ins>Available APIs</ins>

### <ins>GET /api/findTrips</ins>

See available flights based on given requirements.

Query variables:

* `type`: (required) Type of trip wanted. Accepted values: `one_way`, `round_trip`
* `departure_airport`: (required) Departure airport. Must be an airport code. Ex: `YVR`, `YUL`, `YYZ`
* `arrival_airport`: (required) Destination airport. Must be an airport code. Ex: `YVR`, `YUL`, `YYZ`
* `departure_date`: (required): Date to start the trip. Must be in format YYYY-MM-DD. Ex: `2024-05-01`
* `return_date`: (sometimes required) Date to return if trip type is `round_trip`. Must be in format YYYY-MM-DD. Ex: `2024-05-01`
* `preferred_airline`: (optional) Restrict search to a specific airline. Must be an airline code. Ex: `AC`, `TS`, `WS`, `QK`

Example Response:
```
[
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
]
```

### <ins>POST /api/saveTrip</ins>

Save a number of flights for a trip

Body variables:

* `type`: (required) Type of trip wanted. Accepted values: `one_way`, `round_trip`
* `first_flight_id`: (required) Integer Id of the first flight.
* `first_flight_time`: (required) Datetime of the first flight. Ex: `2024-05-01T07:35:00-04:00`
* `second_flight_id`: (sometimes required) Integer Id of the second flight.
* `second_flight_time`: (sometimes required) Datetime of the second flight. Ex: `2024-05-01T07:35:00-04:00`

Example Response:
```
[
  "type":"round_trip",
  "first_flight_id":1,
  "first_flight_time":"2024-05-01T07:35:00-04:00",
  "second_flight_id":2,
  "second_flight_time":"2024-05-10T11:30:00-07:00",
  "total_price":493.86,
  "updated_at":"2024-05-06T00:45:48.000000Z",
  "created_at":"2024-05-06T00:45:48.000000Z",
  "id":2
]
```

### <ins>GET /api/getTrips</ins>

Get all saved trips

Query variables:
* sort_column (optional): Column to sort by. Accepted values: `id`, `type`, `total_price`,`first_flight_id`,`first_flight_time`,`second_flight_id`,`second_flight_time`
* sort_direction (optional): Direction to sort the column. Accepted values: `asc` or `desc`
* page (optional): Integer. Page of results to grab. Minimum value is 1.
* limit (optional): Integer. Number of results by page. Between 1 and 10.