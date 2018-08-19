<?php

namespace App\Users;

use App\Mail\CompleteUserRegistration;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable, CanResetPassword, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'degree', 'title', 'license'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function registerNew(array $requestData)
    {
        return tap(static::create($requestData), function($user) {
            Mail::to($user->email)
                ->send(new CompleteUserRegistration($user));
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
