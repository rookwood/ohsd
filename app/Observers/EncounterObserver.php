<?php

namespace App\Observers;

use App\Encounters\Encounter;
use Illuminate\Support\Facades\Auth;

class EncounterObserver
{
    public function creating(Encounter $encounter)
    {
        if (is_null($encounter->scheduled_by)) {
            $encounter->scheduled_by = Auth::user()->id;
        }
    }
}
