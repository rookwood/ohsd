<?php

namespace Tests\Feature;

use App\Users\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompleteRegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_registration_using_provided_url()
    {
        $user = factory(User::class)->state('audiologist')->create([
            'registration_token' => $this->validData()['token']
        ]);

        $this->assertNotNull($user->registration_token);

        $this->post(route('registration.store'), $this->validData());

        $this->assertNull($user->fresh()->registration_token);
        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }

    /** @test */
    public function password_is_required()
    {
    	$this->expectValidationErrorFromBadData('password', array_except($this->validData(), 'password'));
    }

    protected function expectValidationErrorFromBadData($error, $data)
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->state('audiologist')->create([
            'registration_token' => $this->validData()['token'],
            'password' => 'correct horse battery staple'
        ]);

        $response = $this->post(route('registration.store'), $data);

        $response->assertValidationError($error);
        $response->assertStatus(302);
        $this->assertNotNull($user->fresh()->registration_token);
        $this->assertFalse(Hash::check($this->validData()['password'], $user->fresh()->password));
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'token' => 'TEST_TOKEN',
            'password' => 'secret', 
            'password_confirmation' => 'secret'
        ], $overrides);
    }
}
