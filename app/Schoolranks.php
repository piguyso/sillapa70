<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schoolranks extends Model
{
    protected $fillable = ['school_name', 'schoolpoint', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
}
