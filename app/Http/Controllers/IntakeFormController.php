<?php

namespace App\Http\Controllers;

use App\Http\Resources\IntakeFormResource;
use App\Http\Resources\PatientResource;
use App\IntakeForm;
use App\Patient;
use Illuminate\Http\Request;

class IntakeFormController extends Controller
{
    public function create(Patient $patient)
    {
        return response()->json(['data' => [
            'intake' => new IntakeFormResource($patient->intakeForms->last()),
            'patient' => new PatientResource($patient)
        ]]);
    }

    public function store(Request $request, Patient $patient)
    {
        $form = IntakeForm::registerPatient($patient, $request->all());

        return response()->json($form, 201);
    }

    public function update(Request $request, IntakeForm $form)
    {
        $form->update($request->all());

        return response()->json(['data' => new IntakeFormResource($form)]);
    }
}
