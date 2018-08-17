<?php

use App\Users\Role;
use App\Users\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->state(User::class, 'audiologist', [
    'degree' => 'AuD',
    'title' => 'audiologist'
]);

$factory->afterCreatingState(User::class, 'audiologist', function ($user) {
    factory(Role::class)->create(['name' => 'audiologist']);
    $user->addRole('audiologist');
});

$factory->state(User::class, 'admin', []);

$factory->afterCreatingState(User::class, 'admin', function ($user) {
    factory(Role::class)->create(['name' => 'admin']);
    $user->addRole('admin');
});
