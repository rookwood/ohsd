<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\EncounterCancellationRequest;

class EncounterCancellationController extends Controller
{
    public function store(Encounter $encounter, EncounterCancellationRequest $request)
    {
        $encounter->cancel($request->get('reason', null));

        return response()->json(['data' => ['message' => 'Encounter cancelled']]);
    }
}
