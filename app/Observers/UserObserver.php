<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user)
    {
        if (empty($user->password)) {
            $user->password = Hash::make(Str::random(16));
        }
    }
}
