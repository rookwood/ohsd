<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
       'name',
       'address',
       'city',
       'state',
       'zip',
       'contact',
       'phone'
    ];

    public function employees()
    {
        return $this->hasMany(Patient::class);
    }
}
