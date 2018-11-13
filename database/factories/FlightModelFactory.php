<?php

$factory->define(App\Airport::class, function (Faker\Generator $faker) {
    return [
        'iata_code' => str_random(3),
        'city' => $faker->city,
        'state' => $faker->stateAbbr
    ]; 
});

$factory->define(App\Flight::class, function (Faker\Generator $faker) {
    $flightHours = $faker->numberBetween(1, 5);
    $flightTime = new DateInterval('PT'. $flightHours . 'H');
    $arrival = $faker->dateTime;
    $depart = clone $arrival;
    $depart->sub($flightTime);
    
    return [
        'flight_number' => str_random(3) . $faker->unique()->randomNumber(5),
        'arrival_airport_id' => $faker->numberBetween(1, 5),
        'arrival_date_time' => $arrival,
        'departure_airport_id' => $faker->numberBetween(1, 5),
        'departure_date_time' => $depart,
        'status' => $faker->boolean ? 'ontime' : 'delayed'
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
    ];
});