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
    public function show_completion_form_when_using_a_valid_token()
    {
        $user = factory(User::class)->state('audiologist')->create([
            'registration_token' => $this->validData()['token']
        ]);

        $response = $this->get(route('registration.create', $user->registration_token));
        $response->assertOk();
        $response->assertViewIs('registration.create');
        $response->assertSee('Complete registration');
    }

    /** @test */
    public function viewing_the_registration_completion_form_with_a_bad_token_returns_a_404()
    {
        $this->withExceptionHandling();

        $response = $this->get(route('registration.create', 'BAD_TOKEN'));
        $response->assertStatus(404);
    }

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

    /** @test */
    public function password_must_be_confirmed()
    {
        $this->expectValidationErrorFromBadData('password', $this->validData(['password_confirmation' => 'nope']));
    }

    /** @test */
    public function registration_token_is_required()
    {
        $this->expectValidationErrorFromBadData('token', array_except($this->validData(), 'token'));
    }

    /** @test */
    public function registration_token_must_be_64_characters_in_length()
    {
        $this->expectValidationErrorFromBadData('token', $this->validData(['token' => 'short_token']));
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
            'token' => 'TEST_TOKEN_THAT_IS_64_CHARACTERS_LONG_1234567890_ABCDEFGHIJKLOMN',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ], $overrides);
    }
}
