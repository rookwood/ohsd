<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\EncounterDepartureRequest;

class EncounterDepartureController extends Controller
{
    public function store(EncounterDepartureRequest $request, Encounter $encounter)
    {
        $encounter->depart();

        return response()->json(['data' => ['message' => 'Encounter departed']]);
    }
}
