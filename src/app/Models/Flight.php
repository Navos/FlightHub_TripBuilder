<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Flight extends Model
{
    use HasFactory;

    public function airline(): HasOne {
        return $this->hasOne(Airline::class);
    }
    public function departureAirPort(): HasOne
    {
        return $this->hasOne(Airport::class, 'departure_airport_id');
    }
    public function arrivalAirPort(): HasOne
    {
        return $this->hasOne(Airport::class, 'arrival_airport_id');
    }
}
