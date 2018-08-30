<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAudiogramRequest;
use App\Http\Resources\PatientResource;
use App\Patient;

class AudiogramsController extends Controller
{
    public function store(Patient $patient, CreateAudiogramRequest $request)
    {
        $patient->logHearingScreeningResults($request->except('responses'), $request->get('responses'));

        return response()->json(new PatientResource($patient), 201);
    }

    public function create()
    {

    }
}
