<?php

namespace App;

use App\Events\TestResultWasLogged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\TestResult;

class Audiogram extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'otoscopy',
        'noise_exposure',
        'hearing_protection',
        'comment',
        'date'
    ];

    protected $casts = [
        'otoscopy'           => 'boolean',
        'noise_exposure'     => 'boolean',
        'hearing_protection' => 'boolean',
        'baseline'           => 'boolean',
    ];

    public static function newScreeningForPatient(Patient $patient, $testData, $responses)
    {
        $audiogram = static::create(array_merge($testData, [
            'patient_id' => $patient->id,
            'user_id'    => Auth::user()->id
        ]));

        $audiogram->responses()->saveMany(array_map(function ($response) {
            return new Response($response);
        }, $responses));

        event(new TestResultWasLogged($audiogram));

        return $audiogram;
    }

    public function isBaseline()
    {
        return $this->baseline;
    }

    public function passedOtoscopicEvaluation()
    {
        return $this->otoscopy;
    }

    public function avoidedNoiseExposurePriorToEvaluation()
    {
        return $this->noise_exposure;
    }

    public function woreHearingProtectionSinceLastEvaluation()
    {
        return $this->hearing_protection;
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function setOtoscopyAttribue($value)
    {
        $this->attributes['otoscopy'] = $value == 'pass' ? true : false;
    }

    public function setNoiseExposureAttribute($value)
    {
        $this->attributes['noise_exposure'] = $value == 'no' ? true : false;
    }

    public function setHearingProtectionAttribute($value)
    {
        $this->attributes['hearing_protection'] = $value == 'yes' ? true : false;
    }
}
