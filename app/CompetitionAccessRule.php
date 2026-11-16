<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitionAccessRule extends Model
{
    protected $fillable = [
        'event_id', 'competition_level', 'subject_type', 'scope_type',
        'scope_id', 'is_closed', 'message', 'changed_by',
    ];

    protected $casts = ['is_closed' => 'boolean'];
}
