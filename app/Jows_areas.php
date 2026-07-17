<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jows_areas extends Model
{
    protected $fillable = ['jow_title', 'jow_name', 'jow_lastname', 'jow_position', 'jow_branch', 'jow_activity', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
     public function getjowtitle(){
        return $this->hasOne('App\titles','title_id','jow_title');
    }
    public function getjowbranch(){
        return $this->hasOne('App\branch','id','jow_branch');
    }

}
