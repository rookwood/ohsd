<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'frequency',
        'ear',
        'stimulus',
        'amplitude',
        'test',
        'masking',
        'modality',
        'no_response'
    ];

    protected $casts = [
        'masking' => 'boolean',
        'no_response' => 'boolean',
    ];
}
