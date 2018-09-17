<?php

namespace Tests\Feature\Patients;

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

    	$response = $this->actingAs(new User)->json('GET', route('patients.index'));

    	$response->assertOk();
    	$decodedResponse = $response->decodeResponseJson();

    	$this->assertCount(3, $decodedResponse['data']);
    	$this->assertEquals('Jim', $decodedResponse['data'][0]['firstname']);
    	$this->assertEquals('Bob', $decodedResponse['data'][0]['lastname']);
    	$this->assertEquals(12345678, $decodedResponse['data'][0]['mrn']);
    	$this->assertEquals('1970-10-10', $decodedResponse['data'][0]['birthdate']);
    	$this->assertArrayNotHasKey('created_at', $decodedResponse['data'][0]);
    	$this->assertArrayNotHasKey('updated_at', $decodedResponse['data'][0]);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_the_patient_list()
    {
        $this->withExceptionHandling();

        factory(Patient::class, 3)->create();

    	$response = $this->json('GET', route('patients.index'));

    	$response->assertStatus(401);
    }

    /** @test */
    public function unauthenticated_users_cannot_view_individual_patients()
    {
        $this->withExceptionHandling();

        $patient = factory(Patient::class)->create();

        $response = $this->json('GET', route('patients.show', $patient));

        $response->assertStatus(401);
    }

    /** @test */
    public function non_existent_patients_also_cause_401_if_not_authenticated()
    {
        $this->withExceptionHandling();

        $response = $this->json('GET', route('patients.show', 'not-a-patient'));

        $response->assertStatus(401);
    }
}
