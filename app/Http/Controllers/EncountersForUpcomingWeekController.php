<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use App\Http\Resources\EncounterCollection;

class EncountersForUpcomingWeekController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => new EncounterCollection(Encounter::nextSevenDays())
        ]);
    }
}
