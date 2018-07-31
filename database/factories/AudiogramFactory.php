<?php

use App\Audiogram;
use App\Patient;
use App\User;
use Faker\Generator as Faker;

$factory->define(Audiogram::class, function (Faker $faker) {
    return [
        'patient_id' => function() {
            return factory(Patient::class)->create()->id;
        },
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'noise_exposure' => $faker->boolean,
        'hearing_protection' => $faker->boolean,
        'otoscopy' => $faker->boolean,
        'comment' => $faker->sentence,
        'date' => $faker->date()
    ];
});
