<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Addstudent extends Model
{
 ///  model ปลายทาง , ฟิล ปลายทาง , ฟิล ต้นทาง (ของโมเดล นี้) 
    protected $fillable = ['title', 'name', 'lastname', 'activity', 'school', 'type', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
    
    public function getstudentactivity(){
        return $this->hasOne('App\Activities','id','activity');
    }
    public function getstudenttitle(){
        return $this->hasOne('App\titles','title_id','title');
    }
    public function getaddschool(){
        return $this->hasOne('App\Addschool','id','school');
    }
    public function schools()
    {
        return $this->belongsTo('App\Addschool','school_activity','activity');
    }

}
