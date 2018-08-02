<?php

namespace Tests\Feature;

use App\Audiogram;
use App\Patient;
use App\Response;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewAudiogramTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function viewing_a_patients_audiograms()
    {
        $audiologist = factory(User::class)->create();
        $patient = factory(Patient::class)->create();
        $audiograms = factory(Audiogram::class, 3)->create([
            'patient_id' => $patient->id,
            'user_id' => $audiologist->id
        ]);

        $response = $this->get(route('patients.show', $patient));

        $response->assertSuccessful();
        $response->assertViewIs('patients.show');

        $this->assertCount(3, $response->data('audiograms'));
        $audiograms->assertEquals($response->data('audiograms'));
    }

    /** @test */
    public function test_results_can_be_logged()
    {
        $user = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->post(route('audiograms.store', $patient), $this->validData());


        $response->assertStatus(302);
        $response->assertRedirect(route('patients.show', $patient));

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
    public function noise_exposure_data_is_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)
            ->from(route('audiograms.create', $patient))
            ->post(
                route('audiograms.store',$patient),
                array_except($this->validData(), 'noise_exposure')
            );


        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('noise_exposure');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function hearing_protection_data_is_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), array_except($this->validData(), 'hearing_protection'));

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('hearing_protection');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function otoscopy_results_are_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), array_except($this->validData(), 'otoscopy'));

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('otoscopy');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function test_date_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), array_except($this->validData(), 'date'));

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('date');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function date_field_must_contain_an_actual_date()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), ['date' => 'not-a-real-date']);

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('date');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function responses_are_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), array_except($this->validData(), 'responses'));

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('responses');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function responses_must_be_in_array_format()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), ['responses' => 'poorly formatted data']);

        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $response->assertValidationError('responses');

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function response_frequency_is_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();


        $testData = $this->validData();
        unset($testData['responses'][0]['frequency']);

        $response = $this->actingAs($user)
                ->from(route('audiograms.create', $patient))
                ->post(route('audiograms.store', $patient), $testData);

        $response->assertValidationError('responses.0.frequency');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function response_frequency_must_be_an_integer()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();


        $testData = $this->validData();
        $testData['responses'][0]['frequency'] = 'not-a-valid-frequency';

        $response = $this->actingAs($user)
                ->from(route('audiograms.create', $patient))
                ->post(route('audiograms.store', $patient), $testData);

        $response->assertValidationError('responses.0.frequency');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function must_be_a_valid_audiometric_frequency()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();


        $testData = $this->validData();
        $testData['responses'][0]['frequency'] = '9001';

        $response = $this->actingAs($user)
                ->from(route('audiograms.create', $patient))
                ->post(route('audiograms.store', $patient), $testData);

        $response->assertValidationError('responses.0.frequency');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function stimulus_is_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        unset($testData['responses'][0]['ear']);

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), $testData);

        $response->assertValidationError('responses.0.ear');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function must_be_a_valid_ear_or_binaural_stimulus_source()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['ear'] = 'not-a-valid-ear';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
                $patient), $testData);

        $response->assertValidationError('responses.0.ear');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function amplitude_is_required()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        unset($testData['responses'][0]['amplitude']);

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.amplitude');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function amplitude_must_be_an_integer()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['amplitude'] = 'pretty freaking loud';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.amplitude');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function amplitude_must_be_in_the_testable_audiometric_range()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['amplitude'] = 9001;

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.amplitude');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function masking_must_be_a_boolean_or_integer()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['masking'] = 'student blowing in microphone loudly';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.masking');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function must_be_a_valid_test_modality()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['modality'] = 'cowbell outside soundbooth';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.modality');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function no_response_flag_must_be_boolean()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['no_response'] = 'invalid-data';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.no_response');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function must_be_a_valid_audiometric_stimulus()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['stimulus'] = 'cowbell';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.stimulus');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

        $this->assertEmpty(Audiogram::all());
        $this->assertEmpty(Response::all());
    }

    /** @test */
    public function must_be_a_valid_audiologic_test()
    {
        $this->withExceptionHandling();

        $user    = factory(User::class)->create();
        $patient = factory(Patient::class)->create();

        $testData = $this->validData();
        $testData['responses'][0]['test'] = 'tuning fork';

        $response = $this->actingAs($user)->from(route('audiograms.create', $patient))->post(route('audiograms.store',
            $patient), $testData);

        $response->assertValidationError('responses.0.test');
        $response->assertStatus(302);
        $response->assertRedirect(route('audiograms.create', $patient));

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
