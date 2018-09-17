<?php

namespace App\Http\Controllers;

use App\Encounters\Encounter;
use Illuminate\Http\Request;

class EncounterCancellationController extends Controller
{
    public function store(Encounter $encounter, Request $request)
    {
        $encounter->cancel($request->get('reason', null));
    }
}
