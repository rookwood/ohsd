<?php

use App\Audiogram;
use App\Patient;
use App\Response;
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

$factory->state(Audiogram::class, 'normal', []);

$factory->afterCreatingstate(Audiogram::class, 'normal', function(Audiogram $audiogram, Faker $faker) {
    return tap($audiogram, function ($audiogram) {
        $audiogram->responses()->saveMany(array_map(function ($frequency) {
            return new Response([
                'frequency' => $frequency,
                'amplitude' => 10,
                'ear'       => 'right',
                'stimulus'  => 'tone',
                'test'      => 'threshold',
                'modality'  => 'air',
            ]);
        }, [500, 1000, 2000, 3000, 4000]));
    });
});

$factory->state(Audiogram::class, 'moderate-hearing-loss', []);

$factory->afterCreatingstate(Audiogram::class, 'moderate-hearing-loss', function(Audiogram $audiogram, Faker $faker) {
    return tap($audiogram, function ($audiogram) {
        $audiogram->responses()->saveMany(array_map(function ($frequency) {
            return new Response([
                'frequency' => $frequency,
                'amplitude' => 40,
                'ear' => 'right',
                'stimulus' => 'tone',
                'test' => 'threshold',
                'modality' => 'air',
            ]);
        }, [500, 1000, 2000, 3000, 4000]));
    });
});
