<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activities_areas extends Model
{
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
        return $this->hasMany('App\addschools_areas', 'school_activity', 'id');
    }
}
