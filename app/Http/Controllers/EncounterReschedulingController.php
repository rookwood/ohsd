<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Resources\EncounterResource;
use Illuminate\Http\Request;

class EncounterReschedulingController extends Controller
{
    public function store(Request $request, Encounter $encounter)
    {
        $newEncounter = $encounter->reschedule(
            ...array_values($request->only('date', 'time', 'reason'))
        );

        return response()->json(['data' => new EncounterResource($newEncounter)], 201);
    }
}
