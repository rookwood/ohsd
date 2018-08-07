<?php

namespace Tests\Feature;

use App\Events\TestResultWasLogged;
use App\User;
use App\Patient;
use App\Response;
use App\Audiogram;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAudiogramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_results_can_be_logged()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)
            ->post(route('audiograms.store', $patient), $this->validData());

        $response->assertStatus(302);
        $response->assertRedirect(route('patients.show', $patient));

        Event::assertDispatched(TestResultWasLogged::class, function($event) {
            return $event->audiogram->id === Audiogram::first()->id;
        });

        $this->assertCount(1, Audiogram::all());
        $this->assertCount(2, Response::all());

        $audiogram = Audiogram::first();

        $this->assertEquals($user->id, $audiogram->user_id);
        $this->assertEquals($patient->id, $audiogram->patient_id);
        $this->assertTrue($audiogram->passedOtoscopicEvaluation());
        $this->assertTrue($audiogram->avoidedNoiseExposurePriorToEvaluation());
        $this->assertTrue($audiogram->woreHearingProtectionSinceLastEvaluation());
        $this->assertEquals('test comment', $audiogram->comment);

        $response = Response::first();

        $this->assertEquals(500, $response->frequency);
        $this->assertEquals('right', $response->ear);
        $this->assertEquals('pulse', $response->stimulus);
        $this->assertEquals(20, $response->amplitude);
        $this->assertEquals('threshold', $response->test);
        $this->assertFalse($response->masking);
        $this->assertEquals('air', $response->modality);
        $this->assertFalse($response->no_response);
    }

    /** @test */
    public function audiograms_cannot_be_created_by_unauthenticated_users()
    {
    	$this->withExceptionHandling();

        $response = $this->post(route('audiograms.store', 1), $this->validData());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function noise_exposure_data_is_required()
    {
        $this->expectValidationErrorFromBadData('noise_exposure', array_except($this->validData(), 'noise_exposure'));
    }

    /** @test */
    public function hearing_protection_data_is_required()
    {
        $this->expectValidationErrorFromBadData('hearing_protection', array_except($this->validData(), 'hearing_protection'));
    }

    /** @test */
    public function otoscopy_results_are_required()
    {
        $this->expectValidationErrorFromBadData('otoscopy', array_except($this->validData(), 'otoscopy'));
    }

    /** @test */
    public function test_date_required()
    {
        $this->expectValidationErrorFromBadData('date', array_except($this->validData(), 'date'));
    }

    /** @test */
    public function date_field_must_contain_an_actual_date()
    {
        $this->expectValidationErrorFromBadData('date', array_merge($this->validData(), ['date' => 'not-a-real-date']));
    }

    /** @test */
    public function responses_are_required()
    {
        $this->expectValidationErrorFromBadData('responses', array_except($this->validData(), 'responses'));
    }

    /** @test */
    public function responses_must_be_in_array_format()
    {
        $this->expectValidationErrorFromBadData('responses', array_merge($this->validData(), ['responses' => 'poorly formatted data']));
    }

    /** @test */
    public function response_frequency_is_required()
    {
        $testData = $this->validData();
        unset($testData['responses'][0]['frequency']);

        $this->expectValidationErrorFromBadData('responses.0.frequency', $testData);
    }

    /** @test */
    public function response_frequency_must_be_an_integer()
    {
        $testData = $this->validData();
        $testData['responses'][0]['frequency'] = 'not-a-valid-frequency';

        $this->expectValidationErrorFromBadData('responses.0.frequency', $testData);
    }

    /** @test */
    public function must_include_a_valid_audiometric_frequency()
    {
        $testData = $this->validData();
        $testData['responses'][0]['frequency'] = '9001';

        $this->expectValidationErrorFromBadData('responses.0.frequency', $testData);
    }

    /** @test */
    public function stimulus_target_is_required()
    {
        $testData = $this->validData();
        unset($testData['responses'][0]['ear']);

        $this->expectValidationErrorFromBadData('responses.0.ear', $testData);
    }

    /** @test */
    public function must_include_a_valid_ear_or_binaural_stimulus_source()
    {
        $testData = $this->validData();
        $testData['responses'][0]['ear'] = 'not-a-valid-ear';

        $this->expectValidationErrorFromBadData('responses.0.ear', $testData);
    }

    /** @test */
    public function amplitude_is_required()
    {
        $testData = $this->validData();
        unset($testData['responses'][0]['amplitude']);

        $this->expectValidationErrorFromBadData('responses.0.amplitude', $testData);
    }

    /** @test */
    public function amplitude_must_be_an_integer()
    {
        $testData = $this->validData();
        $testData['responses'][0]['amplitude'] = 'pretty freaking loud';

        $this->expectValidationErrorFromBadData('responses.0.amplitude', $testData);
    }

    /** @test */
    public function amplitude_must_be_in_the_testable_audiometric_range()
    {
        $testData = $this->validData();
        $testData['responses'][0]['amplitude'] = 9001;

        $this->expectValidationErrorFromBadData('responses.0.amplitude', $testData);
    }

    /** @test */
    public function masking_must_be_a_boolean_or_integer()
    {
        $testData = $this->validData();
        $testData['responses'][0]['masking'] = 'student blowing in microphone loudly';

        $this->expectValidationErrorFromBadData('responses.0.masking', $testData);
    }

    /** @test */
    public function must_be_a_valid_test_modality()
    {
        $testData = $this->validData();
        $testData['responses'][0]['modality'] = 'cowbell outside soundbooth';

        $this->expectValidationErrorFromBadData('responses.0.modality', $testData);
    }

    /** @test */
    public function no_response_flag_must_be_boolean()
    {
        $testData = $this->validData();
        $testData['responses'][0]['no_response'] = 'invalid-data';

        $this->expectValidationErrorFromBadData('responses.0.no_response', $testData);
    }

    /** @test */
    public function must_be_a_valid_audiometric_stimulus()
    {
        $testData = $this->validData();
        $testData['responses'][0]['stimulus'] = 'cowbell';

        $this->expectValidationErrorFromBadData('responses.0.stimulus', $testData);
    }

    /** @test */
    public function must_be_a_valid_audiologic_test()
    {
        $testData = $this->validData();
        $testData['responses'][0]['test'] = 'tuning fork';

        $this->expectValidationErrorFromBadData('responses.0.test', $testData);
    }

    /**
     * @param  string $error Field on which the validation error is expected
     * @param  array  $data  Bad data to submit
     * @return void
     */
    protected function expectValidationErrorFromBadData($error, $data)
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)
            ->from(route('audiograms.create', $patient))
            ->post(route('audiograms.store', $patient), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError($error);

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'noise_exposure'     => 'no',
            'hearing_protection' => 'yes',
            'otoscopy'           => 'pass',
            'comment'            => 'test comment',
            'date'               => '2018-07-01',
            'responses'          => [
                [
                    'frequency'   => '500',
                    'stimulus'    => 'pulse',
                    'ear'         => 'right',
                    'amplitude'   => '20',
                    'test'        => 'threshold',
                    'masking'     => false,
                    'modality'    => 'air',
                    'no_response' => false
                ],
                [
                    'frequency'   => '1000',
                    'stimulus'    => 'fm',
                    'ear'         => 'left',
                    'amplitude'   => '26',
                    'test'        => 'mcl',
                    'masking'     => '20',
                    'modality'    => 'bone',
                    'no_response' => false
                ],
            ]
        ], $overrides);
    }
}
