<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class AudiogramsController extends Controller
{
    public function store(Patient $patient, Request $request)
    {
        $patient->logHearingScreeningResults($request->except('responses'), $request->get('responses'));

        return redirect()->route('patients.show', $patient);
    }
}
