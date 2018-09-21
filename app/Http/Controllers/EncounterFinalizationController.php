<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use Illuminate\Http\Request;

class EncounterFinalizationController extends Controller
{
    public function store(Request $request, Encounter $encounter)
    {
        $encounter->finalize(...array_values($request->only('outcome', 'notes')));

        return response()->json(['data' => 'Encounter marked as final.'], 201);
    }
}
