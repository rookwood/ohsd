<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAudiogramRequest;
use App\Patient;

class AudiogramsController extends Controller
{
    public function store(Patient $patient, CreateAudiogramRequest $request)
    {
        $patient->logHearingScreeningResults($request->except('responses'), $request->get('responses'));

        return redirect()->route('patients.show', $patient);
    }

    public function create()
    {

    }
}
