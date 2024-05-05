<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'total_price',
        'first_flight_id',
        'first_flight_time',
        'second_flight_id',
        'second_flight_time',
        'third_flight_id',
        'third_flight_time',
        'fourth_flight_id',
        'fourth_flight_time',
        'fifth_flight_id',
        'fifth_flight_time',
    ];

}
