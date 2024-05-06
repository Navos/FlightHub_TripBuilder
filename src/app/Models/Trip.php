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
    ];

}
