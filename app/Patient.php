<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function logHearingScreeningResults($testData, $responses)
    {
        $this->audiograms()->save(Audiogram::newScreeningForPatient($this, $testData, $responses));

        // return tap($this, function($patient) use ($testData, $responses) {
        //     $patient->audiograms()->save(Audiogram::newScreeningForPatient($patient, $testData, $responses));
        // });

        return $this;
    }

    public function audiograms()
    {
        return $this->hasMany(Audiogram::class);
    }
}
