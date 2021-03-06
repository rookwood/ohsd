<?php

namespace Tests\Feature\UserManagement;

use App\Exceptions\PolicyException;
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

    /** @test */
    public function invalid_action_causes_an_exception_to_be_thrown()
    {
    	$user = factory(User::class)->make();

        $this->expectException(PolicyException::class);

        $user->can('do_some_fake_action');
    }

    /** @test */
    public function missing_policy_class_causes_an_exception_to_be_thrown()
    {
        app()->bind('policy', function () {
            return new class
            {
                public function get($policy)
                {
                    return \App\Policies\ThisClassDoesNotExist::class;
                }

                public function __invoke($action)
                {
                    return $this->get($action);
                }
            };
        });

        $user = factory(User::class)->make();

        $this->expectException(PolicyException::class);

        $user->can('do_some_fake_action');
    }
}
