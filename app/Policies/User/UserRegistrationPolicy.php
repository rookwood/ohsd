<?php

namespace App\Policies\User;

use App\Policies\Policy;
use App\Users\User;

class UserRegistrationPolicy extends Policy
{

    public function execute(User $user, $data = null)
    {
        return $user->isAn('admin');
    }
}
