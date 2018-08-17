<?php

namespace Tests\Unit;

use App\Users\Role;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function roles_can_be_added_by_object_or_string()
    {
    	$userA = factory(User::class)->create();
    	$userB = factory(User::class)->create();
    	$role = factory(Role::class)->create();

    	$userA->addRole($role);
    	$userB->addRole($role->name);

    	$this->assertTrue($userA->isA($role));
    	$this->assertTrue($userB->isAn($role));
    	$this->assertFalse($userA->isA('facist'));
    }

    /** @test */
    public function check_if_user_has_any_of_a_list_of_roles()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->addRole($role);

        $this->assertTrue($user->isA(['picker', 'grinner', 'lover', 'sinner', $role->name]));
        $this->assertFalse($user->isA(['joker', 'smoker', 'midnight toker']));
    }
}
