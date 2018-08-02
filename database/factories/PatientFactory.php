<?php

use App\Employer;
use App\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'mrn' => $faker->numberBetween(1000000, 99999999),
        'birthdate' => $faker->date(),
        'employer_id' => function() {
            return factory(Employer::class)->create()->id;
        }
    ];
});
