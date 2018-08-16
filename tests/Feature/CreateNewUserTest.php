<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_a_new_screening_provider()
    {
    	$response = $this->post(route('users.store'), [
    	    'firstname' => 'Some',
            'lastname' => 'Audiologist',
            'email' => 'someaudiologist@example.com',
            'degree' => 'AuD',
            'title' => 'audiologist',
            'license' => 1234
        ]);

    	$this->assertDatabaseHas('users', [
            'firstname' => 'Some',
            'lastname'  => 'Audiologist',
            'email'     => 'someaudiologist@example.com',
            'degree'    => 'AuD',
            'title'     => 'audiologist',
            'license'   => 1234
        ]);

    	$this->assertNotNull(User::whereFirstname('Some')->first()->password);

        $response->assertRedirect(route('users.create'));
    }

    /** @test */
    public function firstname_is_required()
    {
        $this->expectValidationErrorFromBadData('firstname', array_except($this->validData(), 'firstname'));
    }

    /** @test */
    public function lastname_is_required()
    {
        $this->expectValidationErrorFromBadData('lastname', array_except($this->validData(), 'lastname'));
    }

    /**
     * @param  string $error Field on which the validation error is expected
     * @param  array  $data  Bad data to submit
     * @return void
     */
    protected function expectValidationErrorFromBadData($error, $data)
    {
        $this->withExceptionHandling();

        $response = $this->from(route('users.create'))
            ->post(route('users.store'), $data);

        $response->assertValidationError($error);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.create'));

        $this->assertCount(0, User::all());
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'firstname' => 'Some',
            'lastname'  => 'Audiologist',
            'email'     => 'someaudiologist@example.com',
            'degree'    => 'AuD',
            'title'     => 'audiologist',
            'license'   => 1234
        ], $overrides);
    }
}
