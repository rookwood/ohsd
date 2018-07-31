<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    public function show(Patient $patient)
    {
        return view('patients.show', [
            'audiograms' => $patient->audiograms
        ]);
    }
}
