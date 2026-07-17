<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    protected $fillable = ['notice_text', 'notice_detail', 'notice_file', 'notice_type', 'schoolgroup', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
     public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','schoolgroup');
    }
}
