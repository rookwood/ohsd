<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Users\User;

class UsersController extends Controller
{
    public function create()
    {
        return 'User created.';
    }

    public function store(CreateUserRequest $request)
    {
        User::registerNew($request->all());

        return redirect(route('users.create'));
    }
}
