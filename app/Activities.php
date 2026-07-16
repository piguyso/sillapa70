<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class Activities extends Model
{
    //
    public function getactivitysubject(){
        return $this->hasOne('App\Subjectgroup','id','activity_subject');
    }
    public function getactivitybranch(){
        return $this->hasOne('App\Branch','id','activity_branch');
    }
    public function getclass(){
        return $this->hasOne('App\Classes','id','activity_class');
    }
    public function addschools()
    {
        return $this->hasMany('App\Addschool', 'school_activity', 'id');
    }

  public function addschools_areas()
    {
        return $this->hasMany('App\Addschools_areas', 'school_activity', 'id');
    }

    public function getactivitystatus2(){
        return $this->hasOne('App\Addschools_areas','school_activity', 'id')
        ->where('school_schoolgroup', Auth::user()->schoolgroup?? session('area'));
    }

    public function getactivitystatus(){
        return $this->hasOne('App\Addschools_areas','school_activity', 'id')
        ->where('school_schoolgroup',session('area'));
    }

}
