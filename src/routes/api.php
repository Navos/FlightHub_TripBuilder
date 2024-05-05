<?php

use App\Http\Controllers\TripController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/findTrips', function (Request $request, TripController $controller) {
    return $controller->findTrips($request);
})->middleware(ForceJsonResponse::class);

Route::post('/saveTrip', function (Request $request, TripController $controller) {
    return $controller->saveTrip($request);
})->middleware(ForceJsonResponse::class);

Route::get('/getTrips', function (Request $request, TripController $controller) {
    return $controller->getTrips($request);
})->middleware(ForceJsonResponse::class);