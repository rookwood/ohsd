<?php

namespace App\Policies;

use App\Users\User;

abstract class Policy implements PolicyContract
{
    public function __invoke(User $user, $data = null)
    {
        return $this->execute($user, $data);
    }
}
