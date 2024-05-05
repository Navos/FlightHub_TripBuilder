<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code', 3);
            $table->string('name', 100);
        });

        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code', 3);
            $table->string('city', 100);
            $table->string('city_code', 3);
            $table->string('name', 100);
            $table->string('region_code', 3);
            $table->string('country_code', 3);
            $table->integer('latitude');
            $table->integer('longitude');
            $table->string('timezone', 100);
        });

        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('airline_id');
            $table->foreignId('departure_airport_id');
            $table->foreignId('arrival_airport_id');
            $table->string('number', 10);
            $table->string('departure_time', 5);
            $table->string('arrival_time', 5);
            $table->string('price', 10);

            $table->foreign('airline_id')->references('id')->on('airlines');
            $table->foreign('departure_airport_id')->references('id')->on('airports');
            $table->foreign('arrival_airport_id')->references('id')->on('airports');
        });

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type', 20);
            $table->string('total_price', 10);

            $table->foreignId('first_flight_id')->nullable(false);
            $table->string('first_flight_time', 50)->nullable(false);
            $table->foreignId('second_flight_id')->nullable(true);
            $table->string('second_flight_time', 50)->nullable(true);
            $table->foreignId('third_flight_id')->nullable(true);
            $table->string('third_flight_time', 50)->nullable(true);
            $table->foreignId('fourth_flight_id')->nullable(true);
            $table->string('fourth_flight_time', 50)->nullable(true);
            $table->foreignId('fifth_flight_id')->nullable(true);
            $table->string('fifth_flight_time', 50)->nullable(true);

            $table->foreign('first_flight_id')->references('id')->on('flights');
            $table->foreign('second_flight_id')->references('id')->on('flights');
            $table->foreign('third_flight_id')->references('id')->on('flights');
            $table->foreign('fourth_flight_id')->references('id')->on('flights');
            $table->foreign('fifth_flight_id')->references('id')->on('flights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
        Schema::dropIfExists('airlines');
        Schema::dropIfExists('flights');
        Schema::dropIfExists('trips');
    }
};
