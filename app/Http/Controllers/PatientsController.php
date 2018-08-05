<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Patient;

class PatientsController extends Controller
{
    public function index()
    {
        return view('patients.index', ['patients' => PatientResource::collection(Patient::all())]);
    }

    public function show(Patient $patient)
    {
        return view('patients.show', [
            'audiograms' => $patient->audiograms
        ]);
    }

    public function store(CreatePatientRequest $request)
    {
        if ( ! is_null($request->get('employer_id'))) {
            $patient = Patient::create($request->all());
        } else {
            $patient = Patient::createWithEmployer($request->all(), $request->get('employer'));
        }

        return redirect()->route('patients.show', $patient);
    }
}
