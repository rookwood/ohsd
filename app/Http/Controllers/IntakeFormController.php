<?php

namespace App\Http\Controllers;

use App\IntakeForm;
use App\Patient;
use Illuminate\Http\Request;

class IntakeFormController extends Controller
{
    public function store(Request $request, Patient $patient)
    {
        $form = IntakeForm::registerPatient($patient, $request->all());

        return response()->json($form, 201);
    }
}
