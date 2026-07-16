<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addschool extends Model
{
    protected $fillable = ['school', 'school_name', 'school_schoolgroup', 'school_activity'];
    
    public function getschoolname(){
        return $this->hasOne('App\School','id','school');
    }
    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','school_schoolgroup');
    }
    public function getactivityname(){
        return $this->hasOne('App\activities','id','school_activity');
    }
    public function getstudentname(){
        return $this->hasMany('App\Addstudent','school','id');
    }

    public function students()
    {
        return $this->hasMany('App\Addstudent','activity','school_activity');
    }
}
