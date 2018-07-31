<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class AudiogramsController extends Controller
{
    public function store(Patient $patient)
    {
        return redirect()->route('patients.show', $patient);
    }
}
