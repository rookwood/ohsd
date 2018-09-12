<?php

namespace Tests\Unit\Users;

use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function roles_can_be_removed_from_a_user()
    {
    	$user = factory(User::class)->state('audiologist')->create();

    	$user->removeRole('audiologist');

    	$this->assertFalse($user->fresh()->isAn('audiologist'));
    }
}
