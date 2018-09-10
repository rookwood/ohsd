<?php

namespace Tests\Feature;

use App\Encounters\Encounter;
use App\Patient;
use Dotenv\Exception\ValidationException;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEncounterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function schedule_future_encounter_for_patient()
    {
    	Auth::shouldReceive('user')->andReturn((object) ['id' => 1]);

        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', route('encounters.store', $patient), [
            'date' => '2018-09-09',
            'time' => '10:00 am',
            'notes' => 'Test encounter note',
        ]);

    	$response->assertStatus(201);

    	$response->assertJsonStructure([
    	    'data' => [
    	        'start_at',
                'patient',
                'status',
                'notes',
            ]
        ]);

    	$this->assertCount(1, Encounter::all());
    }

    /** @test */
    public function date_is_required()
    {
        $this->expectValidationErrorFromBadData('date', array_except($this->validData(), 'date'));
    }

    /** @test */
    public function date_must_be_a_date()
    {
        $this->expectValidationErrorFromBadData('date', $this->validData(['date' => 'not-a-date']));
    }

    /** @test */
    public function date_must_be_correctly_formatted()
    {
        $this->expectValidationErrorFromBadData('date', $this->validData(['date' => '3 weeks ago']));
    }

    /** @test */
    public function time_is_required()
    {
        $this->expectValidationErrorFromBadData('time', array_except($this->validData(), 'time'));
    }

    /** @test */
    public function time_must_be_correctly_formatted()
    {
        $this->expectValidationErrorFromBadData('time', $this->validData(['time' => 'in a little while']));
    }

    /** @test */
    public function notes_must_be_a_string()
    {
        $this->expectValidationErrorFromBadData('string', $this->validData(['string' => 123456]));
    }

    protected function expectValidationErrorFromBadData($field, $data)
    {
        $this->withExceptionHandling();

        $patient = factory(Patient::class)->create();

        $response = $this->json('POST', route('encounters.store', $patient), $data);

        $response->assertValidationError($field);

        $this->assertEmpty(Encounter::all());
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'date'  => '2018-09-09',
            'time'  => '10:15 am',
            'notes' => 'Test encounter note',
        ], $overrides);
    }
}
