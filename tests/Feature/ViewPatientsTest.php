<?php

namespace Tests\Feature;

use App\Users\User;
use App\Patient;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewPatientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_patient_list()
    {
    	$patientA = factory(Patient::class)->create([
    	    'firstname' => 'Jim',
            'lastname' => 'Bob',
            'mrn' => '12345678',
            'birthdate' => '10/10/1970',
        ]);
    	$patientB = factory(Patient::class)->create();
    	$patientC = factory(Patient::class)->create();

    	$response = $this->actingAs(new User)->get(route('patients.index'));

    	$response->assertOk();

    	$this->assertCount(3, $response->data('patients'));
    	$this->assertTrue($patientA->is($response->data('patients')[0]));
    	$this->assertEquals('Jim', $response->data('patients')[0]['firstname']);
    	$this->assertEquals('Bob', $response->data('patients')[0]['lastname']);
    	$this->assertEquals(12345678, $response->data('patients')[0]['mrn']);
    	$this->assertEquals('10/10/1970', $response->data('patients')[0]['birthdate']);
    	$this->assertArrayNotHasKey('created_at', $response->data('patients')[0]);
    	$this->assertArrayNotHasKey('updated_at', $response->data('patients')[0]);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_the_patient_list()
    {
        $this->withExceptionHandling();

        factory(Patient::class, 3)->create();

    	$response = $this->get(route('patients.index'));

    	$response->assertStatus(302);
    	$response->assertRedirect('/login');
    }

    /** @test */
    public function unauthenticated_users_cannot_view_individual_patients()
    {
        $this->withExceptionHandling();

        $patient = factory(Patient::class)->create();

        $response = $this->get(route('patients.show', $patient));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function non_existant_patients_also_cause_redirect_if_not_authenticated()
    {
        $this->withExceptionHandling();

        $response = $this->get(route('patients.show', 'not-a-patient'));

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
