<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use Illuminate\Http\Request;

class EncounterArrivalController extends Controller
{
    public function store(Encounter $encounter)
    {
        $encounter->arrive();
    }
}
