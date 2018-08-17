<?php

namespace App\Policies;

use App\Users\User;

interface PolicyContract
{
    public function execute(User $user, $data = null);
}
