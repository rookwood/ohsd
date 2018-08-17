<?php

namespace App\Users;

use App\Exceptions\PolicyException;
use ReflectionException;

trait Authorizable
{
    /**
     * Determine if the current user is authorized to perform an action
     *
     * @param  string $action
     * @param  mixed  $data
     * @return boolean
     * @throws PolicyException
     */
    public function can($action, $data = null)
    {
        $policy = app()->make('policy')($action);

        try {
            $status = app()->make($policy)($this, $data);
        } catch (ReflectionException $e) {
            throw new PolicyException("Policy $e does not exist.");
        }

        return $status === true;
    }

    /**
     * Opposite of can()
     *
     * @param  string $action
     * @param  mixed  $data
     * @return boolean
     * @throws PolicyException
     */
    public function cannot($action, $data = null)
    {
        return !$this->can($action, $data);
    }

    /**
     * Add a role to the current user
     *
     * @param $role   string|Role
     * @return mixed
     */
    public function addRole($role)
    {
        if (!$role instanceof Role) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->attach($role);
    }

    public function removeRole($role)
    {
        if (!$role instanceof Role) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->dettach($role);
    }

    /**
     * Determine if the user fulfills the given Role or any of an array of Roles
     *
     * @param $role string|array<string>|Role
     * @return bool
     */
    public function isA($role)
    {
        if (is_array($role)) {
            return $this->roles->reduce(function ($carry, $item) use ($role) {
                if (in_array($item->name, $role)) {
                    return $carry = true;
                }

                return $carry;
            }, false);
        } else {
            if ($role instanceof Role) {
                $role = $role->name;
            }
        }

        return $this->roles->contains('name', $role);
    }

    /**
     * Alias for $this->isA($role)
     *
     * @param $role string|array<string>|Role
     * @return bool
     */
    public function isAn($role)
    {
        return $this->isA($role);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
