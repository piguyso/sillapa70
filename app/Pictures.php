<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pictures extends Model
{
    protected $fillable = ['picture_name', 'picture_file', 'year'];

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
