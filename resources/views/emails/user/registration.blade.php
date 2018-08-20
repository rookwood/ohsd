<h1>Complete Registration</h1>

<p>
    You have been registered in the OSHA Hearing Screening Database. To complete this process, you must <a href="{{ url(route('registration.create', $user->registration_token)) }}"> set a password.</a>
</p>

<p>
    <a href="{{ url(route('registration.create', $user->registration_token)) }}">
        {{ url(route('registration.create', $user->registration_token)) }}
    </a>
</p>
