<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configs extends Model
{
    public function getschoolgroup(){
        return $this->hasOne('App\Schoolgroup','id','schoolgroup');
    }
}
