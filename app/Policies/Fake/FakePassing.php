<?php

namespace App\Policies\Fake;

use App\Policies\PolicyContract;
use App\Users\User;

class FakePassing implements PolicyContract
{

    public function execute(User $user, $data = null)
    {
        return true;
    }
}
