<?php

namespace App\Observers;

use App\Patient;

class PatientObserver
{
    public function creating(Patient $patient)
    {
        if (is_null($patient->mrn)) {
            $patient->mrn = Patient::count() + 1;
        }
    }
}
