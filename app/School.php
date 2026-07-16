<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','school_group');
    }
}
