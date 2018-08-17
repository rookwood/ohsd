<?php

namespace Tests\Feature;

use App\Policies\Fake\FakeFailing;
use App\Policies\Fake\FakePassing;
use App\Policies\PolicyMap;
use App\Users\User;
use Tests\TestCase;

class AuthorizeByPolicyTest extends TestCase
{
    /** @test */
    public function users_can_be_authorized_for_actions()
    {
        $user = factory(User::class)->make();

        app()->bind('policy', function () {
            return new class
            {
                public function get($policy)
                {
                    if ($policy == 'do_something') {
                        return FakePassing::class;
                    }

                    return FakeFailing::class;
                }

                public function __invoke($action)
                {
                    return $this->get($action);
                }
            };
        });

        $this->assertTrue($user->can('do_something'));
        $this->assertFalse($user->can('be_blocked_for_something_else'));
        $this->assertTrue($user->cannot('be_blocked_for_something_else'));
    }

    /** @test */
    public function policies_are_located_via_action_name()
    {
        $this->assertEquals(FakePassing::class, (new PolicyMap)->get('fake_action'));
    }
}
