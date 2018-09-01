<?php

use App\IntakeForm;
use App\Patient;
use Faker\Generator as Faker;

$factory->define(IntakeForm::class, function (Faker $faker) {
    return [
        'date' => $faker->date(),
        'patient_id' => function() {
            return factory(Patient::class)->create()->id;
        },
        'hearing' => $faker->randomElement(['good', 'fair', 'poor']),
        'health' => $faker->randomElement(['good', 'fair', 'poor']),
        'allergies' => $faker->boolean,
        'diabetes' => $faker->boolean,
        'dizziness' => $faker->boolean,
        'head_injury' => $faker->boolean,
        'hypertension' => $faker->boolean,
        'kidney_disease' => $faker->boolean,
        'measles' => $faker->boolean,
        'mumps' => $faker->boolean,
        'scarlet_fever' => $faker->boolean,
        'otorrhea' => $faker->boolean,
        'otalgia' => $faker->boolean,
        'ear_surgery' => $faker->boolean,
        'ear_medications' => $faker->boolean,
        'tinnitus' => $faker->boolean,
        'aural_pressure' => $faker->boolean,
        'perforated_tympanic_membrane' => $faker->boolean,
        'cerumen' => $faker->boolean,
        'ent_consult' => $faker->boolean,
        'hearing_loss' => $faker->randomElement(['left', 'right', 'both']),
        'family_history_hearing_loss' => $faker->randomElement(['father', 'mother', 'sibling']),
        'use_amplification' => $faker->boolean,
        'previously_work_noise_exposure' => $faker->boolean,
        'audiology_consult' => $faker->boolean,
        'noise_exposure_recreational_gun_use' => $faker->boolean,
        'noise_exposure_power_tools' => $faker->boolean,
        'noise_exposure_engines' => $faker->boolean,
        'noise_exposure_loud_music' => $faker->boolean,
        'noise_exposure_farm_machinery' => $faker->boolean,
        'noise_exposure_military' => $faker->boolean,
        'noise_exposure_other' => $faker->boolean,

    ];
});
