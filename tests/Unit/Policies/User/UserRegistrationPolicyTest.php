<?php

namespace Tests\Unit\Policies\User;

use App\Policies\User\UserRegistrationPolicy;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function only_admins_may_register_users()
    {
    	$admin = factory(User::class)->state('admin')->create();
    	$audiologist = factory(User::class)->state('audiologist')->create();

    	$policy = new UserRegistrationPolicy;

    	$this->assertTrue($policy($admin));
    	$this->assertFalse($policy($audiologist));
    }
}
