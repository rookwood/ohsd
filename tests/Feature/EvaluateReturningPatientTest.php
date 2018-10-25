<?php

namespace Tests\Feature;

use App\Audiogram;
use App\Patient;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluateReturningPatientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function evaluate_a_returning_patient()
    {
        $audiologist = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $firstAudiogram = factory(Audiogram::class)->state('normal')->create();
    }
}
