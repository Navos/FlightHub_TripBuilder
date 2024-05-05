<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Airport extends Model
{
    use HasFactory;

    public function departureFlights(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class, 'departure_airport_id');
    }
    public function arrivalFlights(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class, 'arrival_airport_id');
    }
}
