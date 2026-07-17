<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dayofsillapas extends Model
{
    protected $fillable = ['schoolgroup', 'day_sillapa', 'year'];

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
