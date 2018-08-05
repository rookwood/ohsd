<?php

use App\Employer;
use Faker\Generator as Faker;

$factory->define(Employer::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'zip' => $faker->postcode,
        'contact' => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => $faker->safeEmail,
    ];
});
