<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Airline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    public function flights(): BelongsToMany {
        return $this->belongsToMany(Flight::class);
    }
}
