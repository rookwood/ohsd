<?php

namespace App\Observers;

use App\Audiogram;

class AudiogramObserver
{
    public function creating(Audiogram $audiogram)
    {
        if ($this->firstForPatient($audiogram)) {
            $audiogram->baseline = true;
        }
    }

    protected function firstForPatient(Audiogram $audiogram)
    {
        return ! Audiogram::where('patient_id', $audiogram->patient_id)->count() > 0;
    }
}
