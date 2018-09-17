<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\EncounterArrivalRequest;

class EncounterArrivalController extends Controller
{
    public function store(EncounterArrivalRequest $request, Encounter $encounter)
    {
        $encounter->arrive();

        return response()->json(['data' => ['message' => 'Encounter arrived']]);
    }
}
