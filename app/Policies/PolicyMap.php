<?php

namespace App\Policies;

use App\Exceptions\PolicyException;
use App\Policies\Fake\FakePassing;
use App\Policies\User\UserRegistrationPolicy;

class PolicyMap
{
    protected $actions = [
        'register_new_users' => UserRegistrationPolicy::class,
        'fake_action' => FakePassing::class
    ];

    public function get($action)
    {
        if ( ! array_key_exists($action, $this->actions)) {
            throw new PolicyException("No policy found for action '$action'.");
        }

        return $this->actions[$action];
    }

    public function __get($action)
    {
        return $this->get($action);
    }

    public function __invoke($action)
    {
        return $this->get($action);
    }
}
