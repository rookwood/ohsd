<?php

use App\Employer;
use App\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'gender' => $faker->randomElement(['male', 'female', 'other', 'non-binary', 'undisclosed']),
        'hire_date' => $faker->date,
        'mrn' => $faker->numberBetween(1000000, 99999999),
        'birthdate' => $faker->date(),
        'employer_id' => 1
    ];
});

$factory->afterCreating(Patient::class, function($patient, $faker) {
    $patient->employer_id = factory(Employer::class)->create()->id;
});


