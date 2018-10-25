<?php

use App\Audiogram;
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

$factory->state(Encounter::class, 'tomorrow', function (Faker $faker) {
    return [
        'start_at' => $faker->dateTimeBetween(Carbon::tomorrow()->startOfDay(), Carbon::tomorrow()->endOfDay())
    ];
});

$factory->state(Encounter::class, 'old', [
    'start_at' => Carbon::now()->subWeek()
]);

$factory->state(Encounter::class, 'arrived', [
    'arrived_at' => Carbon::now(),
]);

$factory->state(Encounter::class, 'cancelled', [
    'cancelled_at' => Carbon::now(),
]);

$factory->state(Encounter::class, 'rescheduled', [
    'rescheduled_to' => Carbon::now(),
]);

$factory->state(Encounter::class, 'departed', [
    'start_at' => Carbon::now()->subMinutes(20),
    'arrived_at' => Carbon::now()->subMinute(18),
    'departed_at' => Carbon::now()
]);

$factory->state(Encounter::class, 'finalized', [
    'start_at'    => Carbon::now()->subYears(2),
    'arrived_at'  => Carbon::now()->subYears(2),
    'departed_at' => Carbon::now()->subYears(2),
    'finalized_at' => Carbon::now()->subYears(2),
    'finalized_by' => function() {
        return factory(User::class)->create()->id;
    },
    'outcome' => 'complete',
    'notes' => 'intake baseline',
    'audiogram_id' => function() {
        return factory(Audiogram::class)->state('normal')->create()->id;
    }
]);
