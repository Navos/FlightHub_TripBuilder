<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Trip;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TripController extends Controller
{
    public function findTrips(Request $request) {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['one_way', 'round_trip'])],
        ]);

        $type = $validated['type'];

        switch ($type) {
            case 'one_way':
                return $this->findOneWayFlight($request);
                break;
            case 'round_trip':
                return $this->findRoundTripFlight($request);
                break;
        }
    }

    public function saveTrip(Request $request) {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['one_way', 'round_trip'])],
            'first_flight_id' => ['required', 'exists:flights,id'],
            'first_flight_time' => ['required', 'date'],
            'second_flight_id' => ['nullable', 'exists:flights,id'],
            'second_flight_time' => ['required_with:second_flight_id', 'date'],
            'third_flight_id' => ['nullable', 'exists:flights,id'],
            'third_flight_time' => ['required_with:third_flight_id', 'date'],
            'fourth_flight_id' => ['nullable', 'exists:flights,id'],
            'fourth_flight_time' => ['required_with:fourth_flight_id', 'date'],
            'fifth_flight_id' => ['nullable', 'exists:flights,id'],
            'fifth_flight_time' => ['required_with:fifth_flight_id', 'date'],
        ]);

        $flightIds = [];
        foreach ($validated as $key => $value) {
            if (str_contains($key, 'flight_id')) {
                array_push($flightIds, $value);
            }
        }
        $flights = Flight::select('price')->whereIn('id', $flightIds)->get();
        $totalPrice = 0;
        foreach ($flights as $flight) {
            $totalPrice += floatval($flight['price']);
        }

        $trip = Trip::create(array_merge(
            $validated,
            [
                'total_price' => $totalPrice
            ]
        ));
        return $trip;
    }

    public function getTrips(Request $request) {
        $columns = ['id', 'type', 'total_price','first_flight_id','first_flight_time','second_flight_id','second_flight_time','third_flight_id','third_flight_time','fourth_flight_id','fourth_flight_time','fifth_flight_id','fifth_flight_time'];
        $validated = $request->validate([
            'sort_column' => ['required_with:sort_direction', Rule::in($columns)],
            'sort_direction' => ['required_with:sort_column', Rule::in(['asc', 'desc'])],
            'page' => ['required_with:limit','integer', 'min:1'],
            'limit' => ['required_with:page', 'integer', 'between:1,10'],
        ]);

        $query = Trip::select(...$columns);
        if (isset($validated['sort_column']) && isset($validated['sort_direction'])) {
            $query->orderBy($validated['sort_column'], $validated['sort_direction']);
        }

        if (isset($validated['page']) && isset($validated['limit'])) {
            $offset = ($validated['page'] - 1) * $validated['limit'];
            $query->offset($offset)->limit($validated['limit']);
        }

        return $query->get();
    }

    private function findOneWayFlight(Request $request) {
        $validated = $request->validate([
            'departure_airport' => ['required', 'exists:airports,code'],
            'arrival_airport' => ['required', 'exists:airports,code'],
            'departure_date' => ['required'],
            'preferred_airline' => ['exists:airlines,code']
        ]);

        $departureAirport = $validated['departure_airport'];
        $arrivalAirport = $validated['arrival_airport'];
        $departureDate = $validated['departure_date'];
        $preferredAirline = $validated['preferred_airline'] ?? null;

        $departureFlightsFormatted = $this->getAndFormatFlights($departureAirport, $arrivalAirport, $departureDate, $preferredAirline);

        return response()->json([
            'departure_flights' => $departureFlightsFormatted,
        ]);
    }

    private function findRoundTripFlight(Request $request) {
        $validated = $request->validate([
            'departure_airport' => ['required', 'exists:airports,code'],
            'arrival_airport' => ['required', 'exists:airports,code'],
            'departure_date' => ['required'],
            'return_date' => ['required'],
            'preferred_airline' => ['exists:airlines,code']
        ]);

        $departureAirport = $validated['departure_airport'];
        $arrivalAirport = $validated['arrival_airport'];
        $departureDate = $validated['departure_date'];
        $returnDate = $validated['return_date'];
        $preferredAirline = $validated['preferred_airline'] ?? null;

        $departureFlightsFormatted = $this->getAndFormatFlights($departureAirport, $arrivalAirport, $departureDate, $preferredAirline);
        $returnFlightsFormatted = $this->getAndFormatFlights($arrivalAirport, $departureAirport, $returnDate, $preferredAirline);

        return response()->json([
            'departure_flights' => $departureFlightsFormatted,
            'return_flights' => $returnFlightsFormatted,
        ]);
    }

    private function getAndFormatFlights($departureAirport, $arrivalAirport, $departureDate, $preferredAirline = null) {
        $rawFlights = $this->getFlights($departureAirport, $arrivalAirport, $preferredAirline);
        $formattedFlights = [];
        foreach ($rawFlights as $flight) {
            array_push($formattedFlights, $this->formatFlight($flight['id'], $flight['number'], $departureDate, $flight['departure_time'], $flight['timezone'], $flight['price'], $flight['code']));
        }
        return $formattedFlights;
    }

    private function formatFlight($id, $number, $departureDate, $departureTime, $timezone, $price, $airline) {
        return [
            'id' => $id,
            'number' => $number,
            'datetime' => (new DateTimeImmutable($departureDate . ' ' . $departureTime, new DateTimeZone($timezone)))->format('c'),
            'price' => $price,
            'airline' => $airline,
        ];
    }

    private function getFlights($departureAirport, $arrivalAirport, $preferredAirline = null) {
        $query = Flight::select('flights.id', 'flights.number', 'flights.departure_time', 'dairports.timezone', 'flights.price', 'airlines.code')
            ->join('airlines', 'flights.airline_id', 'airlines.id')
            ->join('airports as dairports', 'flights.departure_airport_id', 'dairports.id')
            ->join('airports as aairports', 'flights.arrival_airport_id', 'aairports.id')
            ->where('dairports.code', $departureAirport)
            ->where('aairports.code', $arrivalAirport);

        if ($preferredAirline) {
            $query->where('airlines.code', $preferredAirline);
        }

        return $query->get();
    }
}
