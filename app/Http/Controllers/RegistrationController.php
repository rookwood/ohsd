<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteRegistrationRequest;
use App\Users\User;

class RegistrationController extends Controller
{
    public function store(CompleteRegistrationRequest $request)
    {
        $user = User::where('registration_token', $request->token)
            ->first()
            ->completeRegistration($request->get('password'));

        return response()->json($user);
    }
}
