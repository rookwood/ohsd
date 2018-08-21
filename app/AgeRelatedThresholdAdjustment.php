<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Str;

class AgeRelatedThresholdAdjustment
{
    /**
     * Minimum age available for OSHA Data
     * @var integer
     */
    const MINIMUM_AGE = 20;

    /**
     * Maximum age for OSHA Data
     */
    const MAXIMUM_AGE = 60;

    protected static $data = [
        'male'   => [
            20 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 4,  '4kHz' => 5,  '6kHz' => 8],
            21 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 4,  '4kHz' => 5,  '6kHz' => 8],
            22 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 4,  '4kHz' => 5,  '6kHz' => 8],
            23 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 4,  '4kHz' => 6,  '6kHz' => 9],
            24 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 5,  '4kHz' => 6,  '6kHz' => 9],
            25 => ['1kHz' => 5,  '2kHz' => 3,  '3kHz' => 5,  '4kHz' => 7,  '6kHz' => 10],
            26 => ['1kHz' => 5,  '2kHz' => 4,  '3kHz' => 5,  '4kHz' => 7,  '6kHz' => 10],
            27 => ['1kHz' => 5,  '2kHz' => 4,  '3kHz' => 6,  '4kHz' => 7,  '6kHz' => 11],
            28 => ['1kHz' => 6,  '2kHz' => 4,  '3kHz' => 6,  '4kHz' => 8,  '6kHz' => 11],
            29 => ['1kHz' => 6,  '2kHz' => 4,  '3kHz' => 6,  '4kHz' => 8,  '6kHz' => 12],
            30 => ['1kHz' => 6,  '2kHz' => 4,  '3kHz' => 6,  '4kHz' => 9,  '6kHz' => 12],
            31 => ['1kHz' => 6,  '2kHz' => 4,  '3kHz' => 7,  '4kHz' => 9,  '6kHz' => 13],
            32 => ['1kHz' => 6,  '2kHz' => 5,  '3kHz' => 7,  '4kHz' => 10, '6kHz' => 14],
            33 => ['1kHz' => 6,  '2kHz' => 5,  '3kHz' => 7,  '4kHz' => 10, '6kHz' => 14],
            34 => ['1kHz' => 6,  '2kHz' => 5,  '3kHz' => 8,  '4kHz' => 11, '6kHz' => 15],
            35 => ['1kHz' => 7,  '2kHz' => 5,  '3kHz' => 8,  '4kHz' => 11, '6kHz' => 15],
            36 => ['1kHz' => 7,  '2kHz' => 5,  '3kHz' => 9,  '4kHz' => 12, '6kHz' => 16],
            37 => ['1kHz' => 7,  '2kHz' => 6,  '3kHz' => 9,  '4kHz' => 12, '6kHz' => 17],
            38 => ['1kHz' => 7,  '2kHz' => 6,  '3kHz' => 9,  '4kHz' => 13, '6kHz' => 17],
            39 => ['1kHz' => 7,  '2kHz' => 6,  '3kHz' => 10, '4kHz' => 14, '6kHz' => 18],
            40 => ['1kHz' => 7,  '2kHz' => 6,  '3kHz' => 10, '4kHz' => 14, '6kHz' => 19],
            41 => ['1kHz' => 7,  '2kHz' => 6,  '3kHz' => 10, '4kHz' => 14, '6kHz' => 20],
            42 => ['1kHz' => 8,  '2kHz' => 7,  '3kHz' => 11, '4kHz' => 16, '6kHz' => 20],
            43 => ['1kHz' => 8,  '2kHz' => 7,  '3kHz' => 12, '4kHz' => 16, '6kHz' => 21],
            44 => ['1kHz' => 8,  '2kHz' => 7,  '3kHz' => 12, '4kHz' => 17, '6kHz' => 22],
            45 => ['1kHz' => 8,  '2kHz' => 7,  '3kHz' => 13, '4kHz' => 18, '6kHz' => 23],
            46 => ['1kHz' => 8,  '2kHz' => 8,  '3kHz' => 13, '4kHz' => 19, '6kHz' => 24],
            47 => ['1kHz' => 8,  '2kHz' => 8,  '3kHz' => 14, '4kHz' => 19, '6kHz' => 24],
            48 => ['1kHz' => 9,  '2kHz' => 8,  '3kHz' => 14, '4kHz' => 20, '6kHz' => 25],
            49 => ['1kHz' => 9,  '2kHz' => 9,  '3kHz' => 15, '4kHz' => 21, '6kHz' => 26],
            50 => ['1kHz' => 9,  '2kHz' => 9,  '3kHz' => 16, '4kHz' => 22, '6kHz' => 27],
            51 => ['1kHz' => 9,  '2kHz' => 9,  '3kHz' => 16, '4kHz' => 23, '6kHz' => 28],
            52 => ['1kHz' => 9,  '2kHz' => 10, '3kHz' => 17, '4kHz' => 24, '6kHz' => 29],
            53 => ['1kHz' => 9,  '2kHz' => 10, '3kHz' => 18, '4kHz' => 25, '6kHz' => 30],
            54 => ['1kHz' => 10, '2kHz' => 10, '3kHz' => 18, '4kHz' => 26, '6kHz' => 31],
            55 => ['1kHz' => 10, '2kHz' => 11, '3kHz' => 19, '4kHz' => 27, '6kHz' => 32],
            56 => ['1kHz' => 10, '2kHz' => 11, '3kHz' => 20, '4kHz' => 28, '6kHz' => 34],
            57 => ['1kHz' => 10, '2kHz' => 11, '3kHz' => 21, '4kHz' => 29, '6kHz' => 35],
            58 => ['1kHz' => 10, '2kHz' => 12, '3kHz' => 22, '4kHz' => 31, '6kHz' => 36],
            59 => ['1kHz' => 11, '2kHz' => 12, '3kHz' => 22, '4kHz' => 32, '6kHz' => 37],
            60 => ['1kHz' => 11, '2kHz' => 13, '3kHz' => 23, '4kHz' => 33, '6kHz' => 38],
        ],
        'female' => [
            20 => ['1kHz' => 7,  '2kHz' => 4,  '3kHz' => 3,  '4kHz' => 3,  '6kHz' => 6],
            21 => ['1kHz' => 7,  '2kHz' => 4,  '3kHz' => 4,  '4kHz' => 3,  '6kHz' => 6],
            22 => ['1kHz' => 7,  '2kHz' => 4,  '3kHz' => 4,  '4kHz' => 4,  '6kHz' => 6],
            23 => ['1kHz' => 7,  '2kHz' => 5,  '3kHz' => 4,  '4kHz' => 4,  '6kHz' => 7],
            24 => ['1kHz' => 7,  '2kHz' => 5,  '3kHz' => 4,  '4kHz' => 4,  '6kHz' => 7],
            25 => ['1kHz' => 8,  '2kHz' => 5,  '3kHz' => 4,  '4kHz' => 4,  '6kHz' => 7],
            26 => ['1kHz' => 8,  '2kHz' => 5,  '3kHz' => 5,  '4kHz' => 4,  '6kHz' => 8],
            27 => ['1kHz' => 8,  '2kHz' => 5,  '3kHz' => 5,  '4kHz' => 5,  '6kHz' => 8],
            28 => ['1kHz' => 8,  '2kHz' => 5,  '3kHz' => 5,  '4kHz' => 5,  '6kHz' => 8],
            29 => ['1kHz' => 8,  '2kHz' => 5,  '3kHz' => 5,  '4kHz' => 5,  '6kHz' => 9],
            30 => ['1kHz' => 8,  '2kHz' => 6,  '3kHz' => 5,  '4kHz' => 5,  '6kHz' => 9],
            31 => ['1kHz' => 8,  '2kHz' => 6,  '3kHz' => 6,  '4kHz' => 5,  '6kHz' => 9],
            32 => ['1kHz' => 9,  '2kHz' => 6,  '3kHz' => 6,  '4kHz' => 6,  '6kHz' => 10],
            33 => ['1kHz' => 9,  '2kHz' => 6,  '3kHz' => 6,  '4kHz' => 6,  '6kHz' => 10],
            34 => ['1kHz' => 9,  '2kHz' => 6,  '3kHz' => 6,  '4kHz' => 6,  '6kHz' => 10],
            35 => ['1kHz' => 9,  '2kHz' => 6,  '3kHz' => 7,  '4kHz' => 7,  '6kHz' => 11],
            36 => ['1kHz' => 9,  '2kHz' => 7,  '3kHz' => 7,  '4kHz' => 7,  '6kHz' => 11],
            37 => ['1kHz' => 9,  '2kHz' => 7,  '3kHz' => 7,  '4kHz' => 7,  '6kHz' => 12],
            38 => ['1kHz' => 10, '2kHz' => 7,  '3kHz' => 7,  '4kHz' => 7,  '6kHz' => 12],
            39 => ['1kHz' => 10, '2kHz' => 7,  '3kHz' => 8,  '4kHz' => 8,  '6kHz' => 12],
            40 => ['1kHz' => 10, '2kHz' => 7,  '3kHz' => 8,  '4kHz' => 8,  '6kHz' => 13],
            41 => ['1kHz' => 10, '2kHz' => 8,  '3kHz' => 8,  '4kHz' => 8,  '6kHz' => 13],
            42 => ['1kHz' => 10, '2kHz' => 8,  '3kHz' => 9,  '4kHz' => 9,  '6kHz' => 13],
            43 => ['1kHz' => 11, '2kHz' => 8,  '3kHz' => 9,  '4kHz' => 9,  '6kHz' => 14],
            44 => ['1kHz' => 11, '2kHz' => 8,  '3kHz' => 9,  '4kHz' => 9,  '6kHz' => 14],
            45 => ['1kHz' => 11, '2kHz' => 8,  '3kHz' => 10, '4kHz' => 10, '6kHz' => 15],
            46 => ['1kHz' => 11, '2kHz' => 9,  '3kHz' => 10, '4kHz' => 10, '6kHz' => 15],
            47 => ['1kHz' => 11, '2kHz' => 9,  '3kHz' => 10, '4kHz' => 11, '6kHz' => 16],
            48 => ['1kHz' => 12, '2kHz' => 9,  '3kHz' => 11, '4kHz' => 11, '6kHz' => 16],
            49 => ['1kHz' => 12, '2kHz' => 9,  '3kHz' => 11, '4kHz' => 11, '6kHz' => 16],
            50 => ['1kHz' => 12, '2kHz' => 10, '3kHz' => 11, '4kHz' => 12, '6kHz' => 17],
            51 => ['1kHz' => 12, '2kHz' => 10, '3kHz' => 12, '4kHz' => 12, '6kHz' => 17],
            52 => ['1kHz' => 12, '2kHz' => 10, '3kHz' => 12, '4kHz' => 13, '6kHz' => 18],
            53 => ['1kHz' => 13, '2kHz' => 10, '3kHz' => 13, '4kHz' => 13, '6kHz' => 18],
            54 => ['1kHz' => 13, '2kHz' => 11, '3kHz' => 13, '4kHz' => 14, '6kHz' => 19],
            55 => ['1kHz' => 13, '2kHz' => 11, '3kHz' => 14, '4kHz' => 14, '6kHz' => 19],
            56 => ['1kHz' => 13, '2kHz' => 11, '3kHz' => 14, '4kHz' => 15, '6kHz' => 20],
            57 => ['1kHz' => 13, '2kHz' => 11, '3kHz' => 15, '4kHz' => 15, '6kHz' => 20],
            58 => ['1kHz' => 14, '2kHz' => 12, '3kHz' => 15, '4kHz' => 16, '6kHz' => 21],
            59 => ['1kHz' => 14, '2kHz' => 12, '3kHz' => 16, '4kHz' => 16, '6kHz' => 21],
            60 => ['1kHz' => 14, '2kHz' => 12, '3kHz' => 16, '4kHz' => 17, '6kHz' => 22],
        ]
    ];

    /**
     * @param Patient   $patient
     * @param Audiogram $baseline
     * @param Audiogram $current
     * @return array
     */
    public function forPatient(Patient $patient, Audiogram $baseline, Audiogram $current)
    {
        $gender = $this->genderAssignment($patient->gender);

        $baselineAge = $this->ageAt($patient->birthdate, $baseline->date);
        $testAge = $this->ageAt($patient->birthdate, $current->date);

        $baselineAdjustments = static::$data[$gender][$baselineAge];
        $currentAdjustments = static::$data[$gender][$testAge];

        return collect($baselineAdjustments)->mapWithKeys(function($adjustment, $frequency) use ($currentAdjustments) {
            return [Str::removeHertzAbbreviation($frequency) => $currentAdjustments[$frequency] - $adjustment];
        })->all();
    }

    /**
     * OSHA documents provide corrections for only male and female with no guidance
     * on assessing age adjustments for other genders.  This method is a good-faith
     * attempt to rectify that by using the more liberal "male" adjustments to minimize
     * type I errors.  There are, I am sure, very valid arguments to be made to minimize
     * type II errors, and I am open to any other approach if someone has a suggestion.
     *
     * @param  string $gender
     * @return string
     */
    public function genderAssignment($gender)
    {
        if (Str::contains($gender, 'female')) {
            return 'female';
        }

        return 'male';
    }

    /**
     * Return the patient's age on the day of the test bounded by the maximum
     * and minimum in available data.
     *
     * @param Carbon $birthDate
     * @param Carbon $testDate
     * @return mixed
     */
    public function ageAt(Carbon $birthDate, Carbon $testDate)
    {
        return min(static::MAXIMUM_AGE, max(static::MINIMUM_AGE, $testDate->diffInYears($birthDate)));
    }

    public function nullAdjustment()
    {
        return [1000 => 0, 2000 => 0, 3000 => 0, 4000 => 0, 5000 => 0];
    }

    public function __invoke(Patient $patient, Audiogram $baseline, Audiogram $current)
    {
        return $this->forPatient($patient, $baseline, $current);
    }
}
