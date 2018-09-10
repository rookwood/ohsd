<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\CreateEncounterRequest;
use App\Http\Resources\EncounterResource;
use App\Patient;

class EncountersController extends Controller
{
    public function store(CreateEncounterRequest $request, Patient $patient)
    {
        $encounter = Encounter::schedule($patient, $request->only('date', 'time', 'notes'));

        return response()->json(['data' => new EncounterResource($encounter)], 201);
    }
}
