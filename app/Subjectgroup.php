<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjectgroup extends Model
{
    protected $fillable = ['subj_name', 'in_dex', 'year'];

    protected static function booted()
    {
        static::addGlobalScope(new \App\Scopes\YearScope);

        static::creating(function ($model) {
            if (empty($model->year)) {
                $model->year = active_year();
            }
        });
    }
    public function getbranch(){
   
        return $this->hasOne('App\activities','activity_subject','id');
        //return $this->belongsTo(SubjectGroup::class, 'activity_subject', 'id');
    }

}
