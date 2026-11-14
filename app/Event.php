<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_number',
        'academic_year',
        'name',
        'status',
        'is_active',
    ];

    protected $casts = [
        'event_number' => 'integer',
        'is_active' => 'boolean',
    ];
}
