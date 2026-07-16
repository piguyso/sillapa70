<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class addschools_areas extends Model
{
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
