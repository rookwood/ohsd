<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Patient;
use Illuminate\Http\Request;

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
}
