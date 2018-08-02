<?php

use Faker\Generator as Faker;
use App\Response;

$factory->define(Response::class, function (Faker $faker) {
    return [
        'frequency' => $faker->randomElement([250, 500, 1000, 2000, 4000, 8000]),
        'ear' => $faker->randomElement(['right', 'left']),
        'amplitude' => $faker->numberBetween(-2,24) * 5,
        'stimulus' => 'tone',
    ];
});
