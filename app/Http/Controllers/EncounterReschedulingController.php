<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\EncounterReschedulingRequest;
use App\Http\Resources\EncounterResource;

class EncounterReschedulingController extends Controller
{
    public function store(EncounterReschedulingRequest $request, Encounter $encounter)
    {
        $newEncounter = $encounter->reschedule(
            ...array_values($request->only('date', 'time', 'reason'))
        );

        return response()->json(['data' => new EncounterResource($newEncounter)], 201);
    }
}
