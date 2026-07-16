<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subjectgroup extends Model
{
    public function getbranch(){
   
        return $this->hasOne('App\activities','activity_subject','id');
        //return $this->belongsTo(SubjectGroup::class, 'activity_subject', 'id');
    }

}
