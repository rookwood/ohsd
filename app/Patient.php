<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function audiograms()
    {
        return $this->hasMany(Audiogram::class);
    }
}
