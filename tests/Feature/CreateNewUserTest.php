<?php

namespace Tests\Feature;

use App\Mail\CompleteUserRegistration;
use App\Users\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_a_new_screening_provider()
    {
        $admin = factory(User::class)->state('admin')->create();

    	$response = $this->actingAs($admin)
            ->post(route('users.store'), [
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
    public function send_email_to_new_user_with_password_reset_link()
    {
    	Mail::fake();

    	$this->actingAs(factory(User::class)->state('admin')->create())
            ->post(route('users.store'), $this->validData());

    	// Newly created user
    	$user = User::find(2);

    	Mail::assertSent(CompleteUserRegistration::class, function ($mail) use ($user) {
    	    return $mail->hasTo($this->validData()['email'])
                && $mail->user->id == $user->id;
        });
    }

    /** @test */
    public function guests_may_not_register_users()
    {
        $this->withExceptionHandling();

        $response = $this->post(route('users.store'), $this->validData());
        $response->assertRedirect(route('login'));
        $this->assertEmpty(User::all());
    }

    /** @test */
    public function nonadministrative_users_may_not_register_users()
    {
    	$this->withExceptionHandling();

        $audiologist = factory(User::class)->state('audiologist')->create();

        $response = $this->actingAs($audiologist)
            ->post(route('users.store'), $this->validData());

        $response->assertStatus(403);

        $this->assertCount(1, User::all());
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

    /** @test */
    public function email_is_required()
    {
        $this->expectValidationErrorFromBadData('email', array_except($this->validData(), 'email'));
    }

    /** @test */
    public function email_must_be_an_email_address()
    {
        $this->expectValidationErrorFromBadData('email', $this->validData(['email' => 'not an email']));
    }

    /** @test */
    public function email_must_be_unique()
    {
        $this->withExceptionHandling();
        $admin = factory(User::class)->state('admin')->create();

        $response = Collection::times(2, function() use ($admin) {
            return $this->actingAs($admin)
                ->from(route('users.create'))
                ->post(route('users.store'), $this->validData());
        })->last();

        $response->assertValidationError('email');

        $response->assertStatus(302);
        $response->assertRedirect(route('users.create'));

        // Admin user already exists, ensure no more users
        $this->assertCount(2, User::all());
    }

    /** @test */
    public function remove_periods_from_degree_abbreviations()
    {
    	$user = new User;
    	$user->degree = 'Au.D.';
    	$this->assertEquals('AuD', $user->degree);
    }

    /**
     * @param  string $error Field on which the validation error is expected
     * @param  array  $data  Bad data to submit
     * @return void
     */
    protected function expectValidationErrorFromBadData($error, $data)
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->state('admin')->create();

        $response = $this->actingAs($admin)
            ->from(route('users.create'))
            ->post(route('users.store'), $data);

        $response->assertValidationError($error);

        $response->assertStatus(302);
        $response->assertRedirect(route('users.create'));

        // Admin user already exists, ensure no more users
        $this->assertCount(1, User::all());
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
