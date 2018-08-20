<?php

namespace App\Users;

use App\Mail\CompleteUserRegistrationEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, CanResetPassword, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'degree',
        'title',
        'license',
        'registration_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'registration_token',
    ];

    public static function registerNew(array $requestData)
    {
        $userData = array_merge($requestData, ['registration_token' => Str::random(64)]);

        return tap(static::create($userData), function($user) {
            Mail::to($user->email)
                ->send(new CompleteUserRegistrationEmail($user));
        });
    }

    /**
     * APA style for life! We aren't some silly humanities discipline
     *
     * @param string $degree
     */
    public function setDegreeAttribute($degree)
    {
        $this->attributes['degree'] = str_replace('.', '', $degree);
    }
}
