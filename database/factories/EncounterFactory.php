<?php

use App\Patient;
use App\Users\User;
use Faker\Generator as Faker;
use App\Encounters\Encounter;
use Illuminate\Support\Carbon;

$factory->define(Encounter::class, function (Faker $faker) {
    return [
        'start_at' => $faker->dateTimeBetween('-3 years', '+3 years'),
        'patient_id' => function() {
            return factory(Patient::class)->create()->id;
        },
        'notes' => $faker->sentence,
        'scheduled_at' => $faker->dateTime('now'),
        'arrived_at' => null,
        'cancelled_at' => null,
        'rescheduled_to' => null,
        'scheduled_by' => function() {
            return factory(User::class)->create()->id;
        },
    ];
});

$factory->state(Encounter::class, 'today', function (Faker $faker) {
    return [
        'start_at' => $faker->dateTimeBetween(Carbon::today()->startOfDay(), Carbon::today()->endOfDay())
    ];
});

$factory->state(Encounter::class, 'old', [
    'start_at' => Carbon::now()->subWeek()
]);
