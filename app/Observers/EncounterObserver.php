<?php

namespace App\Observers;

use App\Encounters\Encounter;
use Illuminate\Support\Facades\Auth;

class EncounterObserver
{
    public function creating(Encounter $encounter)
    {
        $encounter->scheduled_by = Auth::user()->id;
    }
}
