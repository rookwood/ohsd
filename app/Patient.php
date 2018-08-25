<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'mrn',
        'birthdate',
        'employer_id',
        'gender',
        'hire_date',
        'title',
        'employee_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'birthdate',
        'hire_date',
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

    public function intakeForms()
    {
        return $this->hasMany(IntakeForm::class);
    }

    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = Carbon::parse($value)->toDateString();
    }

    public function setHireDateAttribute($value)
    {
        $this->attributes['hire_date'] = Carbon::parse($value)->toDateString();
    }
}
