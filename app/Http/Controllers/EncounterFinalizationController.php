<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Requests\EncounterFinalizationRequest;

class EncounterFinalizationController extends Controller
{
    public function store(EncounterFinalizationRequest $request, Encounter $encounter)
    {
        if ($request->get('audiogram_id')) {
            $encounter->associateAudiogramById($request->get('audiogram_id'));
        }

        $encounter->finalize(...array_values($request->only('outcome', 'notes')));

        return response()->json(['data' => 'Encounter marked as final.'], 201);
    }
}
