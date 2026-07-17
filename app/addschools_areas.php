<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class addschools_areas extends Model
{
    protected $fillable = ['school', 'school_name', 'school_schoolgroup', 'school_activity', 'school_class', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
       public function getschoolname(){
        return $this->hasOne('App\School','id','school');
    }
    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','school_schoolgroup');
    }
    public function getactivityname(){
        return $this->hasOne('App\Activities','id','school_activity');
    }
    public function getstudentname(){
        return $this->hasMany('App\addstudents_areas','school','id');
    }

    public function getactivityclass(){
        return $this->hasOne('App\classes','id','school_class');
    }
    public function students()
    {
        return $this->hasMany('App\addstudents_areas','activity','school_activity');
    }
}
