<?php

namespace App\Policies\Fake;

use App\Policies\Policy;
use App\Users\User;

class FakeFailing extends Policy
{

    public function execute(User $user, $data = null)
    {
        return false;
    }
}
