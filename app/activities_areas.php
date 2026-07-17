<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activities_areas extends Model
{
    protected $fillable = [
        'activity_name',
        'activity_subject',
        'activity_branch',
        'activity_class',
        'activity_status',
        'year'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
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
