<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Resources\EncounterCollection;
use Illuminate\Http\Request;

class EncountersForTodayController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => new EncounterCollection(Encounter::today())
        ]);
    }
}