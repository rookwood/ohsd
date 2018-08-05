<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'mrn',
        'birthdate',
        'employer_id'
    ];

    public static function createWithEmployer($patientData, $employerData)
    {
        $employer = Employer::create($employerData);

        return static::create(array_merge($patientData, ['employer_id' => $employer->id]));
    }

    public function logHearingScreeningResults($testData, $responses)
    {
        $this->audiograms()->save(Audiogram::newScreeningForPatient($this, $testData, $responses));

        return $this;
    }

    public function audiograms()
    {
        return $this->hasMany(Audiogram::class);
    }
}
