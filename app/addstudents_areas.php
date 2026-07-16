<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class addstudents_areas extends Model
{
     ///  model ปลายทาง , ฟิล ปลายทาง , ฟิล ต้นทาง (ของโมเดล นี้) 
    public function getstudentactivity(){
        return $this->hasOne('App\Activities','id','activity');
    }
    public function getstudenttitle(){
        return $this->hasOne('App\titles','title_id','title');
    }
    public function getaddschool(){
        return $this->hasOne('App\addschools_areas','id','school');
    }
    public function schools()
    {
        return $this->belongsTo('App\addschools_areas','school_activity','activity');
    }
    public function getschools()
    {
        return $this->belongsTo('App\School','school','id');
    }
}
