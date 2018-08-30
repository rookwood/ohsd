<?php

namespace Tests\Feature;

use App\Employer;
use App\Patient;
use App\Users\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePatientsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_a_new_patient_with_new_employer()
    {
        $response = $this->actingAs(new User)
            ->post(route('patients.store'), $this->validData());

        $patient = Patient::first();

        $this->assertDatabaseHas('patients', [
            'firstname'   => 'Horace',
            'lastname'    => 'Washburn',
            'gender'      => 'male',
            'mrn'         => '12345678',
            'hire_date'   => '1970-10-10',
            'birthdate'   => '2218-04-23',
            'title'       => 'worker guy',
            'employee_id' => 'ASDF1234',
        ]);

        $this->assertDatabaseHas('employers', [
            'name'    => 'Blue Sun Corp',
            'address' => '123 Some St.',
            'city'    => 'Memphis',
            'state'   => 'TN',
            'zip'     => '38119',
            'contact' => 'That Guy',
            'phone'   => '(901)678-0000',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('patients.show', $patient));
    }

    /** @test */
    public function unauthenticated_users_cannot_register_patients()
    {
        $this->withExceptionHandling();

        $response = $this->post(route('patients.store'), $this->validData());

        $response->assertStatus(302);
        $response->assertRedirect('/login');

        $this->assertCount(0, Patient::all());
    }

    /** @test */
    public function register_a_new_user_with_existing_employer()
    {
        $response = $this->actingAs(new User)
            ->post(route('patients.store'), $this->validData([
                'employer_id' => factory(Employer::class)->create()->id,
                'employer' => null,
            ]));

        $this->assertDatabaseHas('patients', [
            'firstname' => 'Horace',
            'lastname'  => 'Washburn',
            'mrn'       => '12345678',
            'birthdate' => '2218-04-23',
            'employer_id' => 1
        ]);

        $response->assertRedirect(route('patients.show', 1));
        $response->assertStatus(302);

        $this->assertCount(1, Employer::all());
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
    public function mrn_must_be_an_integer_if_provided()
    {
    	$data = $this->validData();
    	$data['mrn'] = 'not-a-valid-mrn';

        $this->expectValidationErrorFromBadData('mrn', $data);
    }

    /** @test */
    public function gender_is_required()
    {
    	$this->expectValidationErrorFromBadData('gender', array_except($this->validData(), 'gender'));
    }

    /** @test */
    public function gender_must_be_a_listed_option()
    {
        $data = $this->validData();
        $data['gender'] = 'Apache Attack Helicopter';

        $this->expectValidationErrorFromBadData('gender', $data);
    }

    /** @test */
    public function birthdate_is_required()
    {
    	$this->expectValidationErrorFromBadData('birthdate', array_except($this->validData(), 'birthdate'));
    }

    /** @test */
    public function birthdate_must_be_a_date()
    {
    	$data = $this->validData();
    	$data['birthdate'] = 'way back when';

        $this->expectValidationErrorFromBadData('birthdate', $data);
    }

    /** @test */
    public function hire_date_must_be_a_date()
    {
    	$data = $this->validData();
    	$data['hire_date'] = 'bout 14 years ago';

        $this->expectValidationErrorFromBadData('hire_date', $data);
    }

    /** @test */
    public function employer_id_or_data_must_be_provided()
    {
    	$this->expectValidationErrorFromBadData('employer_id', array_except($this->validData(), 'employer'));
    	$this->expectValidationErrorFromBadData('employer', array_except($this->validData(), 'employer'));
    }

    /** @test */
    public function employer_data_must_be_an_array()
    {
    	$data = $this->validData();
    	$data['employer'] = 'not an array';

        $this->expectValidationErrorFromBadData('employer', $data);
    }

    /** @test */
    public function employer_name_is_required()
    {
    	$this->expectValidationErrorFromBadData('employer.name', array_except($this->validData(), 'employer.name'));
    }

    /** @test */
    public function employer_email_must_be_an_email()
    {
        $data = $this->validData();
        $data['employer']['email'] = 'not an email';

        $this->expectValidationErrorFromBadData('employer.email', $data);
    }

    /** @test */
    public function employer_state_must_be_in_the_us()
    {
        $data = $this->validData();
        $data['employer']['state'] = 'RU';

        $this->expectValidationErrorFromBadData('employer.state', $data);
    }

    /** @test */
    public function employer_phone_must_be_a_phone_number()
    {
        $data = $this->validData();
        $data['employer']['phone'] = 'dont call me';

        $this->expectValidationErrorFromBadData('employer.phone', $data);
    }

    /**
     * @param  string $error Field on which the validation error is expected
     * @param  array  $data  Bad data to submit
     * @return void
     */
    protected function expectValidationErrorFromBadData($error, $data)
    {
        $this->withExceptionHandling();

        $response = $this->actingAs(new User)
            ->from(route('patients.create'))
            ->post(route('patients.store'), $data);

        $response->assertValidationError($error);

        $this->assertEmpty(Patient::all());
        $this->assertEmpty(Employer::all());

        $response->assertStatus(302);
        $response->assertRedirect(route('patients.create'));
    }

    protected function validData($overrides = [])
    {
        return array_merge([
            'firstname' => 'Horace',
            'lastname' => 'Washburn',
            'mrn' => '12345678',
            'hire_date' => '10/10/1970',
            'gender' => 'male',
            'title' => 'worker guy',
            'employee_id' => 'ASDF1234',
            'birthdate' => '4/23/2218',
            'employer_id' => null,
            'employer' => [
                'name' => 'Blue Sun Corp',
                'address' => '123 Some St.',
                'city' => 'Memphis',
                'state' => 'TN',
                'zip' => '38119',
                'contact' => '  That Guy',
                'email' => 'thatguy@example.com',
                'phone' => '(901)678-0000',
            ],
        ], $overrides);
    }
}
