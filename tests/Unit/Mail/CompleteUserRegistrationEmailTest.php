<?php

namespace Tests\Unit\Mail;

use App\Mail\CompleteUserRegistrationEmail;
use App\Users\User;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompleteUserRegistrationEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_contains_a_link_to_complete_registration()
    {
        $user = factory(User::class)->make(['registration_token' => 'TESTTOKEN901']);

        $email = new CompleteUserRegistrationEmail($user);

        $rendered = $this->render($email);

        $this->assertContains(url(route('registration.create', $user->registration_token)), $rendered);
    }

    /** @test */
    public function email_has_a_subject()
    {
    	$user = factory(User::class)->make();

    	$email = new CompleteUserRegistrationEmail($user);

    	$this->assertEquals('Register for OSHA Hearing Screening Database', $email->build()->subject);
    }

    private function render(Mailable $mailable)
    {
        $mailable->build();

        return view($mailable->view, $mailable->buildViewData())->render();
    }
}
