<?php

namespace App\Policies\User;

use App\Policies\PolicyContract;
use App\Users\User;

class UserRegistrationPolicy implements PolicyContract
{

    public function execute(User $user, $data = null)
    {
        return $user->isAn('admin');
    }
}
